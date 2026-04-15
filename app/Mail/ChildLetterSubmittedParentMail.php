<?php

namespace App\Mail;

use App\Models\ChildLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildLetterSubmittedParentMail extends Mailable
{
    use Queueable, SerializesModels;

    public ChildLetter $letter;

    public function __construct(ChildLetter $letter)
    {
        $this->letter = $letter;
    }

    public function build()
    {
        return $this->subject('A new child letter was submitted for review')
            ->view('emails.child-letter-submitted-parent');
    }
}