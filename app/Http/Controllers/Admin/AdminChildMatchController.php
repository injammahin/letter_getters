<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ChildMatchApprovedChildMail;
use App\Mail\ChildMatchApprovedParentMail;
use App\Models\ChildConversation;
use App\Models\ChildMatch;
use App\Models\ChildMatchRequest;
use App\Models\ParentChildLink;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminChildMatchController extends Controller
{
    public function pending(): View
    {
        $this->ensureAdmin();

        $pendingRequests = ChildMatchRequest::query()
            ->with([
                'requester.profile.avatarLibrary',
                'requester.interests',
                'target.profile.avatarLibrary',
                'target.interests',
            ])
            ->where('status', 'pending_admin_approval')
            ->latest()
            ->paginate(12);

        return view('admin.matches.pending', compact('pendingRequests'));
    }

    public function approved(): View
    {
        $this->ensureAdmin();

        $approvedMatches = ChildMatch::query()
            ->with([
                'userOne.profile.avatarLibrary',
                'userOne.interests',
                'userTwo.profile.avatarLibrary',
                'userTwo.interests',
                'approver',
            ])
            ->where('status', 'active')
            ->latest('approved_at')
            ->paginate(12);

        return view('admin.matches.approved', compact('approvedMatches'));
    }

    public function approve(Request $request, ChildMatchRequest $childMatchRequest): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($childMatchRequest->status !== 'pending_admin_approval') {
            return back()->with('error', 'This match request is no longer pending.');
        }

        $requester = $childMatchRequest->requester()->first();
        $target = $childMatchRequest->target()->first();

        $match = DB::transaction(function () use ($childMatchRequest, $data) {
            $userIds = [$childMatchRequest->requester_user_id, $childMatchRequest->target_user_id];
            sort($userIds);

            $match = ChildMatch::query()->firstOrCreate(
                [
                    'user_one_id' => $userIds[0],
                    'user_two_id' => $userIds[1],
                ],
                [
                    'approved_request_id' => $childMatchRequest->id,
                    'approved_by' => auth()->id(),
                    'status' => 'active',
                    'approved_at' => now(),
                ]
            );

            if ($match->status !== 'active') {
                $match->update([
                    'status' => 'active',
                    'approved_request_id' => $childMatchRequest->id,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            }

            $childMatchRequest->update([
                'status' => 'approved',
                'admin_notes' => $data['admin_notes'] ?? null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            ChildConversation::firstOrCreate([
                'child_match_id' => $match->id,
            ]);

            return $match;
        });

        if ($requester && $requester->email) {
            Mail::to($requester->email)->send(new ChildMatchApprovedChildMail($requester, $target));
        }

        if ($target && $target->email) {
            Mail::to($target->email)->send(new ChildMatchApprovedChildMail($target, $requester));
        }

        $requesterParentEmail = $this->findParentEmailForChild($requester);
        $targetParentEmail = $this->findParentEmailForChild($target);

        if ($requesterParentEmail) {
            Mail::to($requesterParentEmail)->send(new ChildMatchApprovedParentMail($requester, $target));
        }

        if ($targetParentEmail) {
            Mail::to($targetParentEmail)->send(new ChildMatchApprovedParentMail($target, $requester));
        }

        return back()->with('success', 'Match approved. Both children can now see the match, write letters, and open chat.');
    }

    public function reject(Request $request, ChildMatchRequest $childMatchRequest): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($childMatchRequest->status !== 'pending_admin_approval') {
            return back()->with('error', 'This match request is no longer pending.');
        }

        $childMatchRequest->update([
            'status' => 'rejected',
            'admin_notes' => $data['admin_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Match request rejected successfully.');
    }

    public function remove(ChildMatch $childMatch): RedirectResponse
    {
        $this->ensureAdmin();

        $childMatch->update([
            'status' => 'removed',
        ]);

        return back()->with('success', 'Approved match removed successfully.');
    }

    protected function ensureAdmin(): void
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'], true), 403);
    }

    protected function findParentEmailForChild(?User $child): ?string
    {
        if (! $child) {
            return null;
        }

        $link = ParentChildLink::query()
            ->with('parent')
            ->where('child_user_id', $child->id)
            ->first();

        return $link?->parent?->email;
    }
}