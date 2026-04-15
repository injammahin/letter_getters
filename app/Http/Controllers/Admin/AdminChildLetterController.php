<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChildLetter;
use App\Services\ChildLetterScanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminChildLetterController extends Controller
{
    public function __construct(
        protected ChildLetterScanService $scanner
    ) {
    }

    public function pending(): View
    {
        $this->ensureAdmin();

        $letters = ChildLetter::query()
            ->with([
                'sender.profile.avatarLibrary',
                'receiver.profile.avatarLibrary',
                'scanner',
            ])
            ->where('status', 'submitted')
            ->latest()
            ->paginate(12);

        $stats = [
            'submitted' => ChildLetter::query()
                ->where('status', 'submitted')
                ->count(),

            'unscanned' => ChildLetter::query()
                ->where('status', 'submitted')
                ->where('scan_status', 'not_scanned')
                ->count(),

            'clean_scanned' => ChildLetter::query()
                ->where('status', 'submitted')
                ->where('scan_status', 'scanned')
                ->where('scan_flagged', false)
                ->count(),

            'flagged' => ChildLetter::query()
                ->where('status', 'submitted')
                ->where('scan_status', 'scanned')
                ->where('scan_flagged', true)
                ->count(),
        ];

        return view('admin.mail.letters.pending', compact('letters', 'stats'));
    }

    public function show(ChildLetter $childLetter): View
    {
        $this->ensureAdmin();

        $childLetter->load([
            'sender.profile.avatarLibrary',
            'receiver.profile.avatarLibrary',
            'reviewer',
            'scanner',
        ]);

        return view('admin.mail.letters.show', compact('childLetter'));
    }

    public function scan(ChildLetter $childLetter): RedirectResponse
    {
        $this->ensureAdmin();

        if ($childLetter->status !== 'submitted') {
            return back()->with('error', 'Only submitted letters can be scanned.');
        }

        $this->scanner->applyScan($childLetter, auth()->id());

        return back()->with('success', 'Letter scanned successfully.');
    }

    public function scanAllSubmitted(): RedirectResponse
    {
        $this->ensureAdmin();

        $letters = ChildLetter::query()
            ->where('status', 'submitted')
            ->get();

        if ($letters->isEmpty()) {
            return back()->with('error', 'There are no submitted letters to scan.');
        }

        $scannedCount = 0;
        $flaggedCount = 0;
        $cleanCount = 0;

        foreach ($letters as $letter) {
            $freshLetter = $this->scanner->applyScan($letter, auth()->id());

            $scannedCount++;

            if ($freshLetter->scan_flagged) {
                $flaggedCount++;
            } else {
                $cleanCount++;
            }
        }

        return back()->with(
            'success',
            "Scan complete. Total scanned: {$scannedCount}. Clean: {$cleanCount}. Flagged: {$flaggedCount}."
        );
    }

    public function approve(Request $request, ChildLetter $childLetter): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        if ($childLetter->status !== 'submitted') {
            return back()->with('error', 'This letter is no longer waiting for review.');
        }

        if ($childLetter->scan_status !== 'scanned') {
            return back()->with('error', 'Please scan this letter before approving it.');
        }

        if ($childLetter->scan_flagged) {
            return back()->with('error', 'This letter is flagged. Use Force Approve if you still want to approve it.');
        }

        $this->markApproved($childLetter, $data['admin_notes'] ?? null);

        return redirect()
            ->route('admin.child-letters.show', $childLetter)
            ->with('success', 'Letter approved successfully. It is now visible to the receiving child.');
    }

    public function forceApprove(Request $request, ChildLetter $childLetter): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        if ($childLetter->status !== 'submitted') {
            return back()->with('error', 'This letter is no longer waiting for review.');
        }

        if ($childLetter->scan_status !== 'scanned') {
            return back()->with('error', 'Please scan this letter before force approving it.');
        }

        $this->markApproved($childLetter, $data['admin_notes'] ?? null);

        return redirect()
            ->route('admin.child-letters.show', $childLetter)
            ->with('success', 'Flagged letter force approved successfully.');
    }

    public function approveAllCleanSubmitted(): RedirectResponse
    {
        $this->ensureAdmin();

        $letters = ChildLetter::query()
            ->where('status', 'submitted')
            ->where('scan_status', 'scanned')
            ->where('scan_flagged', false)
            ->get();

        if ($letters->isEmpty()) {
            return back()->with('error', 'No clean scanned submitted letters are ready for batch approval.');
        }

        $approvedCount = 0;

        DB::transaction(function () use ($letters, &$approvedCount) {
            foreach ($letters as $letter) {
                $letter->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'approved_at' => now(),
                    'admin_notes' => 'Batch approved after clean scan.',
                ]);

                $approvedCount++;
            }
        });

        $remainingFlagged = ChildLetter::query()
            ->where('status', 'submitted')
            ->where('scan_status', 'scanned')
            ->where('scan_flagged', true)
            ->count();

        $remainingUnscanned = ChildLetter::query()
            ->where('status', 'submitted')
            ->where('scan_status', 'not_scanned')
            ->count();

        return back()->with(
            'success',
            "Batch approval complete. Approved: {$approvedCount}. Flagged left: {$remainingFlagged}. Unscanned left: {$remainingUnscanned}."
        );
    }

    public function reject(Request $request, ChildLetter $childLetter): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        if ($childLetter->status !== 'submitted') {
            return back()->with('error', 'This letter is no longer waiting for review.');
        }

        $childLetter->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'approved_at' => null,
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.child-letters.show', $childLetter)
            ->with('success', 'Letter rejected successfully.');
    }

    protected function markApproved(ChildLetter $childLetter, ?string $adminNotes = null): void
    {
        $childLetter->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }

    protected function ensureAdmin(): void
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'], true), 403);
    }
    public function approved(): View
    {
        $this->ensureAdmin();

        $letters = ChildLetter::query()
            ->with([
                'sender.profile.avatarLibrary',
                'receiver.profile.avatarLibrary',
                'reviewer',
            ])
            ->where('status', 'approved')
            ->latest('approved_at')
            ->paginate(12);

        return view('admin.mail.letters.approved', compact('letters'));
    }
    public function rejected(): View
    {
        $this->ensureAdmin();

        $letters = ChildLetter::query()
            ->with([
                'sender.profile.avatarLibrary',
                'receiver.profile.avatarLibrary',
                'reviewer',
            ])
            ->where('status', 'rejected')
            ->latest('reviewed_at')
            ->paginate(12);

        return view('admin.mail.letters.rejected', compact('letters'));
    }
}