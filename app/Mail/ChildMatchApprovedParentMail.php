<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildMatchApprovedParentMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $child;
    public User $matchedChild;

    public function __construct(User $child, User $matchedChild)
    {
        $this->child = $child;
        $this->matchedChild = $matchedChild;
    }

    public function build()
    {
        return $this->subject('A child match has been approved')
            ->view('emails.child-match-approved-parent');
    }
}