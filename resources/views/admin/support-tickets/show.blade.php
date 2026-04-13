@extends('layouts.admin')

@section('title', 'Support Ticket Details')
@section('page_title', 'Support Ticket Details')
@section('page_subtitle', 'View ticket details, change status, and reply')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-6">
            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="inline-flex rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-medium text-[#CB148B]">
                            {{ $supportTicket->ticket_number }}
                        </div>
                        <h2 class="mt-4 text-2xl font-semibold text-neutral-900">{{ $supportTicket->subject }}</h2>
                        <p class="mt-2 text-sm text-neutral-500">
                            Submitted by {{ $supportTicket->full_name }} • {{ $supportTicket->email }}
                        </p>
                    </div>

                    <a href="{{ route('admin.support-tickets.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back
                    </a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-[#fff7fc] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Category</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst(str_replace('_', ' ', $supportTicket->category)) }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f7efff] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Priority</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst($supportTicket->priority) }}</p>
                    </div>

                    <div class="rounded-2xl bg-neutral-50 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-[24px] border border-black/6 bg-white p-5">
                    <h3 class="text-lg font-semibold text-neutral-900">User Message</h3>
                    <div class="mt-4 whitespace-pre-line text-sm leading-8 text-neutral-700">
                        {{ $supportTicket->message }}
                    </div>
                </div>
            </div>

            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-neutral-900">Reply Thread</h3>

                <div class="mt-6 space-y-4">
                    @forelse($supportTicket->replies as $reply)
                        <div class="rounded-[24px] p-4 {{ $reply->sender_type === 'admin' ? 'bg-[#fff7fc] border border-pink-100' : 'bg-neutral-50 border border-black/6' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">
                                        {{ $reply->sender_type === 'admin' ? ($reply->user->name ?? 'Admin') : 'User' }}
                                    </p>
                                    <p class="mt-1 text-xs text-neutral-500">
                                        {{ $reply->created_at?->format('d M Y, h:i A') }}
                                    </p>
                                </div>

                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $reply->sender_type === 'admin' ? 'bg-pink-100 text-pink-700' : 'bg-neutral-200 text-neutral-700' }}">
                                    {{ ucfirst($reply->sender_type) }}
                                </span>
                            </div>

                            <div class="mt-4 whitespace-pre-line text-sm leading-8 text-neutral-700">
                                {{ $reply->message }}
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-black/10 bg-neutral-50 px-4 py-8 text-center text-sm text-neutral-500">
                            No replies yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-neutral-900">Update Status</h3>
                <p class="mt-2 text-sm leading-7 text-neutral-500">
                    Changing the status will send an email update to the user.
                </p>

                <form action="{{ route('admin.support-tickets.update-status', $supportTicket) }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Status</label>
                        <select
                            name="status"
                            class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                        >
                            <option value="open" {{ $supportTicket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $supportTicket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="solved" {{ $supportTicket->status === 'solved' ? 'selected' : '' }}>Solved</option>
                            <option value="closed" {{ $supportTicket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-6 py-3 text-sm font-medium text-white"
                    >
                        Update Status
                    </button>
                </form>
            </div>

            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-neutral-900">Reply to Ticket</h3>
                <p class="mt-2 text-sm leading-7 text-neutral-500">
                    The reply will be saved in the system and emailed to the user.
                </p>

                <form action="{{ route('admin.support-tickets.reply', $supportTicket) }}" method="POST" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Reply Message</label>
                        <textarea
                            name="message"
                            rows="7"
                            class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                            placeholder="Write your reply to the user..."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white"
                    >
                        Send Reply
                    </button>
                </form>
            </div>

            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-neutral-900">Ticket Meta</h3>

                <div class="mt-5 space-y-3 text-sm text-neutral-600">
                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                        <span>Created</span>
                        <span class="font-medium text-neutral-900">{{ $supportTicket->created_at?->format('d M Y, h:i A') }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                        <span>Last Reply</span>
                        <span class="font-medium text-neutral-900">{{ $supportTicket->last_replied_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                        <span>Solved At</span>
                        <span class="font-medium text-neutral-900">{{ $supportTicket->solved_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection