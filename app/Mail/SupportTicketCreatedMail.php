<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportTicket $ticket;

    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('Support Ticket Created: ' . $this->ticket->ticket_number)
            ->view('emails.support-ticket-created');
    }
}