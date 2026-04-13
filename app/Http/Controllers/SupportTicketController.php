<?php

namespace App\Http\Controllers;

use App\Mail\SupportTicketAdminMail;
use App\Mail\SupportTicketCreatedMail;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function create(): View
    {
        return view('pages.support');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'in:normal,high,urgent'],
            'message' => ['required', 'string', 'min:20', 'max:3000'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'ticket_number' => $this->generateTicketNumber(),
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        Mail::to($ticket->email)->send(new SupportTicketCreatedMail($ticket));

        $supportInbox = env('SUPPORT_TICKET_TO');
        if ($supportInbox) {
            Mail::to($supportInbox)->send(new SupportTicketAdminMail($ticket));
        }

        return redirect()
            ->route('support')
            ->with('success', 'Your support ticket has been created successfully.')
            ->with('ticket_number', $ticket->ticket_number);
    }

    protected function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'LG-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (SupportTicket::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}