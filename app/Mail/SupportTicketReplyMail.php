<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportTicket $ticket;
    public SupportTicketReply $reply;
    public string $responderName;

    public function __construct(SupportTicket $ticket, SupportTicketReply $reply, string $responderName)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
        $this->responderName = $responderName;
    }

    public function build()
    {
        return $this->subject('Reply to Support Ticket: ' . $this->ticket->ticket_number)
            ->view('emails.support-ticket-reply');
    }
}