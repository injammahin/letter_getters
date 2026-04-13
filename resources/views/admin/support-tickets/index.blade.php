@extends('layouts.admin')

@section('title', 'Support Tickets')
@section('page_title', 'Support Tickets')
@section('page_subtitle', 'View, filter, and manage support tickets')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-neutral-900">Support Tickets</h2>
            <p class="mt-1 text-sm text-neutral-500">All support tickets from the public support form.</p>
        </div>

        <div class="rounded-full border border-black/8 bg-white px-4 py-2 text-sm text-neutral-600 shadow-sm">
            Total:
            <span class="font-medium text-neutral-900">{{ $tickets->total() }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-[30px] border border-black/8 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="xl:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search ticket number, name, email, subject"
                    class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                >
            </div>

            <div>
                <select name="category" class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]">
                    <option value="">All Categories</option>
                    @foreach($categories as $item)
                        <option value="{{ $item }}" {{ $category === $item ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $item)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="priority" class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]">
                    <option value="">All Priorities</option>
                    <option value="normal" {{ $priority === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <select name="status" class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]">
                    <option value="">All Statuses</option>
                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="solved" {{ $status === 'solved' ? 'selected' : '' }}>Solved</option>
                    <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="xl:col-span-5 flex gap-3">
                <button type="submit" class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white">
                    Filter
                </button>

                <a href="{{ route('admin.support-tickets.index') }}" class="rounded-full border border-black/10 px-5 py-3 text-sm font-medium text-neutral-700">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/6">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Ticket</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/6">
                    @forelse($tickets as $ticket)
                        <tr class="transition hover:bg-neutral-50/70">
                            <td class="px-6 py-5">
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $ticket->ticket_number }}</p>
                                    <p class="mt-1 text-xs text-neutral-500">{{ $ticket->subject }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $ticket->full_name }}</p>
                                    <p class="mt-1 text-xs text-neutral-500">{{ $ticket->email }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-sm text-neutral-700">
                                {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                            </td>

                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    {{ $ticket->priority === 'normal' ? 'bg-neutral-100 text-neutral-700' : '' }}
                                    {{ $ticket->priority === 'high' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $ticket->priority === 'urgent' ? 'bg-red-50 text-red-600' : '' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>

                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    {{ $ticket->status === 'open' ? 'bg-pink-50 text-pink-600' : '' }}
                                    {{ $ticket->status === 'in_progress' ? 'bg-violet-50 text-violet-700' : '' }}
                                    {{ $ticket->status === 'solved' ? 'bg-green-50 text-green-700' : '' }}
                                    {{ $ticket->status === 'closed' ? 'bg-neutral-100 text-neutral-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-sm text-neutral-500">
                                {{ $ticket->created_at?->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('admin.support-tickets.show', $ticket) }}"
                                   class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center text-sm text-neutral-500">
                                No support tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="flex flex-col gap-4 border-t border-black/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-neutral-500">
                    Showing
                    <span class="font-medium text-neutral-900">{{ $tickets->firstItem() }}</span>
                    to
                    <span class="font-medium text-neutral-900">{{ $tickets->lastItem() }}</span>
                    of
                    <span class="font-medium text-neutral-900">{{ $tickets->total() }}</span>
                    tickets
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-full border border-black/8 bg-neutral-50 px-4 py-2 text-sm text-neutral-600">
                        Page
                        <span class="font-medium text-neutral-900">{{ $tickets->currentPage() }}</span>
                        of
                        <span class="font-medium text-neutral-900">{{ $tickets->lastPage() }}</span>
                    </div>

                    {{ $tickets->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection