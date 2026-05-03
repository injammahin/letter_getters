<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChildNotificationController extends Controller
{
    public function poll(): JsonResponse
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'child', 403);

        $penPalTypes = [
            'penpal_request_received',
            'penpal_request_accepted',
        ];

        $unreadQuery = $user->unreadNotifications()
            ->where(function ($query) use ($penPalTypes) {
                foreach ($penPalTypes as $type) {
                    $query->orWhere('data->type', $type);
                }
            });

        $recentNotifications = $user->notifications()
            ->where(function ($query) use ($penPalTypes) {
                foreach ($penPalTypes as $type) {
                    $query->orWhere('data->type', $type);
                }
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'url' => $notification->data['url'] ?? route('child.pen-pals'),
                    'icon' => $notification->data['icon'] ?? 'fa-bell',
                    'read_at' => $notification->read_at,
                    'created_at_human' => $notification->created_at?->diffForHumans(),
                ];
            })
            ->values();

        return response()->json([
            'unread_penpal_count' => (clone $unreadQuery)->count(),
            'recent_penpal_notifications' => $recentNotifications,
        ]);
    }

    public function markPenPalAsRead(): JsonResponse
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'child', 403);

        $penPalTypes = [
            'penpal_request_received',
            'penpal_request_accepted',
        ];

        $notifications = $user->unreadNotifications()
            ->where(function ($query) use ($penPalTypes) {
                foreach ($penPalTypes as $type) {
                    $query->orWhere('data->type', $type);
                }
            })
            ->get();

        $notifications->markAsRead();

        return response()->json([
            'success' => true,
            'unread_penpal_count' => 0,
        ]);
    }
}