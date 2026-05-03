<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PenPalRequestAcceptedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $acceptedBy
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'penpal_request_accepted',
            'title' => 'Pen pal request accepted',
            'message' => $this->acceptedBy->name . ' accepted your pen pal request.',
            'sender_id' => $this->acceptedBy->id,
            'sender_name' => $this->acceptedBy->name,
            'url' => route('child.pen-pals'),
            'icon' => 'fa-heart',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}