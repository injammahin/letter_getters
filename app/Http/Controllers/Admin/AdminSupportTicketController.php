<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SupportTicketReplyMail;
use App\Mail\SupportTicketStatusUpdatedMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $category = trim((string) $request->get('category'));
        $priority = trim((string) $request->get('priority'));
        $status = trim((string) $request->get('status'));

        $tickets = SupportTicket::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($priority !== '', fn ($query) => $query->where('priority', $priority))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = SupportTicket::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.support-tickets.index', compact(
            'tickets',
            'categories',
            'search',
            'category',
            'priority',
            'status'
        ));
    }

    public function show(SupportTicket $supportTicket): View
    {
        $supportTicket->load(['replies.user']);

        return view('admin.support-tickets.show', compact('supportTicket'));
    }

    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:4000'],
        ]);

        $reply = SupportTicketReply::create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => auth()->id(),
            'sender_type' => 'admin',
            'message' => $data['message'],
        ]);

        if ($supportTicket->status === 'open') {
            $supportTicket->status = 'in_progress';
        }

        $supportTicket->last_replied_at = now();
        $supportTicket->save();

        Mail::to($supportTicket->email)->send(
            new SupportTicketReplyMail($supportTicket, $reply, auth()->user()->name ?? 'Support Team')
        );

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Reply sent successfully and email delivered to the user.');
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,solved,closed'],
        ]);

        $supportTicket->status = $data['status'];

        if ($data['status'] === 'solved') {
            $supportTicket->solved_at = now();
        }

        if ($data['status'] !== 'solved') {
            $supportTicket->solved_at = null;
        }

        $supportTicket->save();

        Mail::to($supportTicket->email)->send(
            new SupportTicketStatusUpdatedMail($supportTicket)
        );

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Ticket status updated and email sent to the user.');
    }
}