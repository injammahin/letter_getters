<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildConversation;
use App\Models\ChildMatch;
use App\Models\ChildMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChildChatController extends Controller
{
    public function show(User $penPal): View
    {
        $child = $this->ensureChildAndReturn();

        $match = $this->findApprovedMatch($child->id, $penPal->id);

        abort_unless($match, 403);

        $conversation = ChildConversation::firstOrCreate([
            'child_match_id' => $match->id,
        ]);

        ChildMessage::query()
            ->where('child_conversation_id', $conversation->id)
            ->where('receiver_user_id', $child->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        $conversation->load([
            'messages.sender.profile.avatarLibrary',
            'messages.receiver.profile.avatarLibrary',
        ]);

        return view('child.messages.chat', [
            'child' => $child,
            'penPal' => $penPal->loadMissing('profile.avatarLibrary'),
            'conversation' => $conversation,
            'match' => $match,
        ]);
    }

    public function store(Request $request, User $penPal): RedirectResponse
    {
        $child = $this->ensureChildAndReturn();

        $match = $this->findApprovedMatch($child->id, $penPal->id);

        abort_unless($match, 403);

        $data = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1500'],
        ]);

        $conversation = ChildConversation::firstOrCreate([
            'child_match_id' => $match->id,
        ]);

        ChildMessage::create([
            'child_conversation_id' => $conversation->id,
            'sender_user_id' => $child->id,
            'receiver_user_id' => $penPal->id,
            'message' => $data['message'],
        ]);

        return redirect()
            ->route('child.messages.chat', $penPal)
            ->with('success', 'Message sent successfully.');
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
}