<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Mail\ChildLetterSubmittedParentMail;
use App\Models\ChildLetter;
use App\Models\ChildMatch;
use App\Models\ParentChildLink;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ChildLetterController extends Controller
{
    public function index(): View
    {
        $child = $this->ensureChildAndReturn();

        $letters = ChildLetter::query()
            ->with([
                'sender.profile.avatarLibrary',
                'receiver.profile.avatarLibrary',
                'reviewer',
            ])
            ->where(function ($query) use ($child) {
                $query->where('sender_user_id', $child->id)
                    ->orWhere(function ($subQuery) use ($child) {
                        $subQuery->where('receiver_user_id', $child->id)
                            ->where('status', 'approved');
                    });
            })
            ->latest()
            ->paginate(12);

        return view('child.letters.index', compact('letters', 'child'));
    }

    public function create(User $penPal): View
    {
        $child = $this->ensureChildAndReturn();

        $match = $this->findApprovedMatch($child->id, $penPal->id);

        abort_unless($match, 403);

        return view('child.letters.create', [
            'child' => $child,
            'penPal' => $penPal->loadMissing('profile.avatarLibrary'),
            'match' => $match,
        ]);
    }

    public function store(Request $request, User $penPal): RedirectResponse
    {
        $child = $this->ensureChildAndReturn();

        $match = $this->findApprovedMatch($child->id, $penPal->id);

        abort_unless($match, 403);

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'min:10', 'max:6000'],
        ]);

        $letter = ChildLetter::create([
            'child_match_id' => $match->id,
            'sender_user_id' => $child->id,
            'receiver_user_id' => $penPal->id,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'submitted',
        ]);

        $letter->load(['sender', 'receiver']);

        if (filter_var(env('SEND_PARENT_EMAIL_ON_CHILD_LETTER_SUBMIT', false), FILTER_VALIDATE_BOOLEAN)) {
            $parentEmail = $this->findParentEmailForChild($child);

            if ($parentEmail) {
                Mail::to($parentEmail)->send(new ChildLetterSubmittedParentMail($letter));
            }
        }

        return redirect()
            ->route('child.letters')
            ->with('success', 'Letter submitted successfully. It is now waiting for admin review.');
    }

    public function show(ChildLetter $childLetter): View
    {
        $child = $this->ensureChildAndReturn();

        $canViewAsSender = $childLetter->sender_user_id === $child->id;
        $canViewAsReceiver = $childLetter->receiver_user_id === $child->id && $childLetter->status === 'approved';

        abort_unless($canViewAsSender || $canViewAsReceiver, 403);

        if ($canViewAsReceiver && is_null($childLetter->read_at)) {
            $childLetter->update([
                'read_at' => now(),
            ]);
        }

        $childLetter->load([
            'sender.profile.avatarLibrary',
            'receiver.profile.avatarLibrary',
            'reviewer',
        ]);

        return view('child.letters.show', compact('childLetter', 'child'));
    }

    protected function ensureChildAndReturn(): User
    {
        abort_unless(auth()->check() && auth()->user()->role === 'child', 403);

        return auth()->user();
    }

    protected function findApprovedMatch(int $firstUserId, int $secondUserId): ?ChildMatch
    {
        return ChildMatch::query()
            ->where('status', 'active')
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('user_one_id', $firstUserId)
                        ->where('user_two_id', $secondUserId);
                })->orWhere(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('user_one_id', $secondUserId)
                        ->where('user_two_id', $firstUserId);
                });
            })
            ->first();
    }

    protected function findParentEmailForChild(User $child): ?string
    {
        $link = ParentChildLink::query()
            ->with('parent')
            ->where('child_user_id', $child->id)
            ->first();

        return $link?->parent?->email ?? $child->parent_email;
    }
}