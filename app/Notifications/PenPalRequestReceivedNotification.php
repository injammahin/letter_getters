<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PenPalRequestReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $sender
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'penpal_request_received',
            'title' => 'New pen pal request',
            'message' => $this->sender->name . ' sent you a pen pal request.',
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'url' => route('child.pen-pals'),
            'icon' => 'fa-paper-plane',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}