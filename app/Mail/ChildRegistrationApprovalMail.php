<?php

namespace App\Mail;

use App\Models\ParentApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildRegistrationApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public ParentApproval $approval;

    public function __construct(ParentApproval $approval)
    {
        $this->approval = $approval;
    }

    public function build()
    {
        return $this->subject('Approve Child Registration - Letter Getters')
            ->view('emails.child-registration-approval');
    }
}