<?php

namespace App\Providers;

use App\Models\ChildLetter;
use App\Models\ChildMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.child', function ($view) {
            if (! Auth::check() || Auth::user()->role !== 'child') {
                return;
            }

            $child = Auth::user();

            $unreadMessages = ChildMessage::query()
                ->with(['sender.profile.avatarLibrary'])
                ->where('receiver_user_id', $child->id)
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();

            $unreadLetters = ChildLetter::query()
                ->with(['sender.profile.avatarLibrary'])
                ->where('receiver_user_id', $child->id)
                ->where('status', 'approved')
                ->whereNull('read_at')
                ->latest()
                ->take(5)
                ->get();

            $view->with('childHeaderData', [
                'unread_message_count' => ChildMessage::query()
                    ->where('receiver_user_id', $child->id)
                    ->whereNull('read_at')
                    ->count(),

                'unread_letter_count' => ChildLetter::query()
                    ->where('receiver_user_id', $child->id)
                    ->where('status', 'approved')
                    ->whereNull('read_at')
                    ->count(),

                'recent_messages' => $unreadMessages,
                'recent_letters' => $unreadLetters,
            ]);
        });
    }
}