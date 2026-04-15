@extends('layouts.child')

@section('title', 'Letters')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-[24px] border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(98,10,136,0.16)] bg-[rgba(98,10,136,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#620A88]">
                        Letters
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">Your letters</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        A letter only reaches the receiving child after admin review and approval.
                    </p>
                </div>

                <a href="{{ route('child.pen-pals') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-black/70">
                    <i class="fa-solid fa-user-group text-xs"></i>
                    My Pen Pals
                </a>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <h2 class="text-2xl font-bold text-black">All letters</h2>

            <div class="mt-6 grid gap-4">
                @forelse($letters as $letter)
                    @php
                        $otherChild = $letter->sender_user_id === $child->id ? $letter->receiver : $letter->sender;
                        $direction = $letter->sender_user_id === $child->id ? 'Sent' : 'Received';
                    @endphp

                    <a href="{{ route('child.letters.show', $letter) }}"
                        class="rounded-[24px] border border-black/8 p-5 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/60">
                                        {{ $direction }}
                                    </span>

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $letter->status === 'submitted' ? 'bg-amber-50 text-amber-700' : '' }}
                                            {{ $letter->status === 'approved' ? 'bg-green-50 text-green-700' : '' }}
                                            {{ $letter->status === 'rejected' ? 'bg-red-50 text-red-600' : '' }}
                                        ">
                                        {{ ucfirst($letter->status) }}
                                    </span>

                                    @if($direction === 'Received' && is_null($letter->read_at))
                                        <span
                                            class="inline-flex rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-semibold text-[#CB148B]">
                                            New
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-3 text-xl font-bold text-black">{{ $letter->subject }}</h3>
                                <p class="mt-2 text-sm text-black/55">
                                    {{ $direction }} {{ $direction === 'Sent' ? 'to' : 'from' }}
                                    {{ $otherChild?->name ?? 'Pen Pal' }}
                                </p>
                                <p class="mt-1 text-xs text-black/45">
                                    {{ $letter->created_at?->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-[24px] border border-dashed border-black/10 p-6 text-center text-sm text-black/55">
                        No letters yet.
                    </div>
                @endforelse
            </div>

            @if($letters->hasPages())
                <div class="mt-6">
                    {{ $letters->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection