<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildLetter;
use App\Models\ChildMatch;
use App\Models\ChildMatchRequest;
use App\Models\ChildMessage;
use Illuminate\View\View;

class ChildDashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->check() && auth()->user()->role === 'child', 403);

        $child = auth()->user()->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $approvedMatches = ChildMatch::query()
            ->with([
                'userOne.profile.avatarLibrary',
                'userTwo.profile.avatarLibrary',
            ])
            ->where('status', 'active')
            ->where(function ($query) use ($child) {
                $query->where('user_one_id', $child->id)
                    ->orWhere('user_two_id', $child->id);
            })
            ->latest('approved_at')
            ->get();

        $approvedPenPals = $approvedMatches->map(function (ChildMatch $match) use ($child) {
            return $match->user_one_id === $child->id ? $match->userTwo : $match->userOne;
        });

        $dashboardStats = [
            'matches' => $approvedMatches->count(),
            'pending_requests' => ChildMatchRequest::query()
                ->where('requester_user_id', $child->id)
                ->where('status', 'pending_admin_approval')
                ->count(),
            'letters' => ChildLetter::query()
                ->where(function ($query) use ($child) {
                    $query->where('sender_user_id', $child->id)
                        ->orWhere(function ($subQuery) use ($child) {
                            $subQuery->where('receiver_user_id', $child->id)
                                ->where('status', 'approved');
                        });
                })
                ->count(),
            'unread_messages' => ChildMessage::query()
                ->where('receiver_user_id', $child->id)
                ->whereNull('read_at')
                ->count(),
            'unread_letters' => ChildLetter::query()
                ->where('receiver_user_id', $child->id)
                ->where('status', 'approved')
                ->whereNull('read_at')
                ->count(),
            'coins' => 0,
            'printables' => 0,
        ];

        return view('child.dashboard', [
            'child' => $child,
            'approvedPenPals' => $approvedPenPals,
            'dashboardStats' => $dashboardStats,
        ]);
    }
}