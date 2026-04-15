@extends('layouts.child')

@section('title', 'View Letter')

@section('content')
    <div class="space-y-6">
        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(98,10,136,0.16)] bg-[rgba(98,10,136,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#620A88]">
                        Letter Details
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">{{ $childLetter->subject }}</h1>
                    <p class="mt-3 text-sm leading-7 text-black/60">
                        {{ $childLetter->sender_user_id === $child->id ? 'Sent to' : 'Received from' }}
                        {{ $childLetter->sender_user_id === $child->id ? $childLetter->receiver?->name : $childLetter->sender?->name }}
                    </p>
                </div>

                <a href="{{ route('child.letters') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-black/70">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Back to Letters
                </a>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $childLetter->status === 'submitted' ? 'bg-amber-50 text-amber-700' : '' }}
                    {{ $childLetter->status === 'approved' ? 'bg-green-50 text-green-700' : '' }}
                    {{ $childLetter->status === 'rejected' ? 'bg-red-50 text-red-600' : '' }}
                ">
                    {{ ucfirst($childLetter->status) }}
                </span>

                <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/60">
                    {{ $childLetter->created_at?->format('d M Y, h:i A') }}
                </span>
            </div>

            @if($childLetter->sender_user_id === $child->id && $childLetter->status === 'submitted')
                <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    This letter is waiting for admin review.
                </div>
            @endif

            @if($childLetter->sender_user_id === $child->id && $childLetter->status === 'rejected' && $childLetter->admin_notes)
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Admin note: {{ $childLetter->admin_notes }}
                </div>
            @endif

            <div
                class="mt-6 whitespace-pre-line rounded-[24px] border border-black/8 bg-white p-6 text-sm leading-8 text-black/75">
                {{ $childLetter->body }}
            </div>
        </section>
    </div>
@endsection