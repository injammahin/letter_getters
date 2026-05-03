@extends('layouts.child')

@section('title', 'My Pen Pals')

@section('content')
    <div class="space-y-5">
            @if(session('success'))
                <div class="rounded-[22px] border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-[22px] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-[30px] border border-black/5 bg-white shadow-sm">
                <div class="relative p-5 sm:p-6">
                    <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-[44px] bg-[#fff0f9]"></div>

                    <div class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                                <span>💌</span>
                                My Pen Pals
                            </div>

                            <h1 class="mt-3 text-2xl font-black text-black sm:text-3xl">
                                Your pen pal space
                            </h1>

                            <p class="mt-2 max-w-2xl text-sm leading-6 text-black/50">
                                See active pen pals, requests, and safe match suggestions.
                            </p>
                        </div>

                        <div class="grid grid-cols-4 gap-2 rounded-[26px] border border-black/5 bg-neutral-50 p-2">
                            <div class="rounded-[20px] bg-white px-4 py-3 text-center shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-green-700">Active</p>
                                <p class="mt-1 text-xl font-black text-black">{{ $approvedMatches->count() }}</p>
                            </div>

                            <div class="rounded-[20px] bg-white px-4 py-3 text-center shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-amber-700">In</p>
                                <p class="mt-1 text-xl font-black text-black">{{ $incomingRequests->count() }}</p>
                            </div>

                            <div class="rounded-[20px] bg-white px-4 py-3 text-center shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-[#620A88]">Sent</p>
                                <p class="mt-1 text-xl font-black text-black">{{ $pendingRequests->count() }}</p>
                            </div>

                            <div class="rounded-[20px] bg-white px-4 py-3 text-center shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-[#CB148B]">Find</p>
                                <p class="mt-1 text-xl font-black text-black">{{ $suggestions->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-black text-black">Active pen pals</h2>
                        <p class="mt-1 text-sm text-black/45">Visit your pen pal’s habitant world.</p>
                    </div>

                    <span class="rounded-full bg-green-50 px-4 py-2 text-xs font-black text-green-700">
                        {{ $approvedMatches->count() }} Active
                    </span>
                </div>

                @if($approvedMatches->isEmpty())
                    <div class="rounded-[28px] border border-dashed border-black/10 bg-white p-6 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff0f9] text-2xl">
                            💌
                        </div>

                        <h3 class="mt-4 text-lg font-black text-black">No active pen pal yet</h3>

                        <p class="mt-2 text-sm leading-6 text-black/50">
                            When a match is accepted, the active pen pal card will appear here.
                        </p>
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($approvedMatches as $approvedMatch)
                            <div class="group overflow-hidden rounded-[30px] border border-black/5 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex min-w-0 items-center gap-3">
                                        @if($approvedMatch->pen_pal?->profile?->avatar_type === 'upload' && $approvedMatch->pen_pal?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $approvedMatch->pen_pal->profile->avatar) }}"
                                                 alt="Avatar"
                                                 class="h-14 w-14 rounded-2xl object-cover">
                                        @elseif($approvedMatch->pen_pal?->profile?->avatar_type === 'library' && $approvedMatch->pen_pal?->profile?->avatarLibrary?->image_path)
                                            <img src="{{ asset('storage/' . $approvedMatch->pen_pal->profile->avatarLibrary->image_path) }}"
                                                 alt="Avatar"
                                                 class="h-14 w-14 rounded-2xl object-cover">
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <h3 class="truncate text-lg font-black text-black">
                                                {{ $approvedMatch->pen_pal?->name }}
                                            </h3>

                                            <p class="text-sm text-black/45">
                                                {{ $approvedMatch->pen_pal?->profile?->age_or_grade ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-black text-green-700">
                                        Active
                                    </span>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-[#fff7fc] p-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.14em] text-[#CB148B]">City</p>
                                        <p class="mt-2 text-sm font-bold text-black">
                                            {{ $approvedMatch->pen_pal?->profile?->city ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl bg-[#f7efff] p-3">
                                        <p class="text-[10px] font-black uppercase tracking-[0.14em] text-[#620A88]">State</p>
                                        <p class="mt-2 text-sm font-bold text-black">
                                            {{ $approvedMatch->pen_pal?->profile?->state ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                @if(collect($approvedMatch->shared_interest_names)->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="text-[10px] font-black uppercase tracking-[0.14em] text-black/40">
                                            Common interests
                                        </p>

                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach($approvedMatch->shared_interest_names as $interestName)
                                                <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-black/60">
                                                    {{ $interestName }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('child.pen-pals.habitant', $approvedMatch->pen_pal) }}"
                                   class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-black text-white shadow-lg shadow-fuchsia-900/10 transition hover:-translate-y-0.5">
                                    <i class="fa-solid fa-house-chimney-window text-xs"></i>
                                    Visit {{ $approvedMatch->pen_pal?->name }}’s Habitant
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @if($incomingRequests->isNotEmpty())
                <section class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-black text-black">Incoming requests</h2>
                            <p class="mt-1 text-sm text-black/45">Accept or decline match requests.</p>
                        </div>

                        <span class="rounded-full bg-amber-50 px-4 py-2 text-xs font-black text-amber-700">
                            {{ $incomingRequests->count() }} Waiting
                        </span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($incomingRequests as $requestItem)
                            <div class="rounded-[30px] border border-black/5 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3">
                                    @if($requestItem->requester?->profile?->avatar_type === 'upload' && $requestItem->requester?->profile?->avatar)
                                        <img src="{{ asset('storage/' . $requestItem->requester->profile->avatar) }}"
                                             alt="Avatar"
                                             class="h-14 w-14 rounded-2xl object-cover">
                                    @elseif($requestItem->requester?->profile?->avatar_type === 'library' && $requestItem->requester?->profile?->avatarLibrary?->image_path)
                                        <img src="{{ asset('storage/' . $requestItem->requester->profile->avatarLibrary->image_path) }}"
                                             alt="Avatar"
                                             class="h-14 w-14 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="text-lg font-black text-black">{{ $requestItem->requester?->name }}</h3>
                                        <p class="text-sm text-black/45">
                                            {{ $requestItem->requester?->profile?->age_or_grade ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @forelse($requestItem->shared_interest_names as $interestName)
                                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-black/60">
                                            {{ $interestName }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-black/40">No shared interests shown.</span>
                                    @endforelse
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <form action="{{ route('child.pen-pals.requests.accept', $requestItem) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#16a34a,#15803d)] px-4 py-3 text-sm font-black text-white transition hover:-translate-y-0.5">
                                            <i class="fa-solid fa-check text-xs"></i>
                                            Accept
                                        </button>
                                    </form>

                                    <form action="{{ route('child.pen-pals.requests.decline', $requestItem) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-3 text-sm font-black text-red-700 transition hover:bg-red-100">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($suggestions->isNotEmpty())
                <section class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-black text-black">Suggested matches</h2>
                            <p class="mt-1 text-sm text-black/45">Children with shared interests.</p>
                        </div>

                        <span class="rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black text-[#CB148B]">
                            {{ $suggestions->count() }} Suggestions
                        </span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($suggestions as $suggestion)
                            <div class="rounded-[30px] border border-black/5 bg-white p-5 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        @if($suggestion->profile?->avatar_type === 'upload' && $suggestion->profile?->avatar)
                                            <img src="{{ asset('storage/' . $suggestion->profile->avatar) }}"
                                                 alt="Avatar"
                                                 class="h-14 w-14 rounded-2xl object-cover">
                                        @elseif($suggestion->profile?->avatar_type === 'library' && $suggestion->profile?->avatarLibrary?->image_path)
                                            <img src="{{ asset('storage/' . $suggestion->profile->avatarLibrary->image_path) }}"
                                                 alt="Avatar"
                                                 class="h-14 w-14 rounded-2xl object-cover">
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <h3 class="text-lg font-black text-black">{{ $suggestion->name }}</h3>
                                            <p class="text-sm text-black/45">
                                                {{ $suggestion->profile?->age_or_grade ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <span class="rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-black text-[#CB148B]">
                                        {{ $suggestion->match_score }} pts
                                    </span>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($suggestion->shared_interest_names as $interestName)
                                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-black/60">
                                            {{ $interestName }}
                                        </span>
                                    @endforeach
                                </div>

                                <form action="{{ route('child.pen-pals.request', $suggestion) }}" method="POST" class="mt-5">
                                    @csrf

                                    <button type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-0.5">
                                        <i class="fa-solid fa-paper-plane text-xs"></i>
                                        Send Match Request
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($pendingRequests->isNotEmpty())
                <section class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-black text-black">Sent requests</h2>
                            <p class="mt-1 text-sm text-black/45">Requests waiting for another child to accept.</p>
                        </div>

                        <span class="rounded-full bg-[#f7efff] px-4 py-2 text-xs font-black text-[#620A88]">
                            {{ $pendingRequests->count() }} Sent
                        </span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($pendingRequests as $requestItem)
                            <div class="rounded-[30px] border border-black/5 bg-white p-5 shadow-sm">
                                <div class="flex items-center gap-3">
                                    @if($requestItem->target?->profile?->avatar_type === 'upload' && $requestItem->target?->profile?->avatar)
                                        <img src="{{ asset('storage/' . $requestItem->target->profile->avatar) }}"
                                             alt="Avatar"
                                             class="h-14 w-14 rounded-2xl object-cover">
                                    @elseif($requestItem->target?->profile?->avatar_type === 'library' && $requestItem->target?->profile?->avatarLibrary?->image_path)
                                        <img src="{{ asset('storage/' . $requestItem->target->profile->avatarLibrary->image_path) }}"
                                             alt="Avatar"
                                             class="h-14 w-14 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="text-lg font-black text-black">{{ $requestItem->target?->name }}</h3>
                                        <p class="text-sm text-black/45">
                                            Waiting for accept
                                        </p>
                                    </div>
                                </div>

                                @if(collect($requestItem->shared_interest_names)->isNotEmpty())
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($requestItem->shared_interest_names as $interestName)
                                            <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-black/60">
                                                {{ $interestName }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <form action="{{ route('child.pen-pals.requests.cancel', $requestItem) }}" method="POST" class="mt-5">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-black/10 bg-white px-4 py-3 text-sm font-black text-black/65 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                                        <i class="fa-solid fa-ban text-xs"></i>
                                        Cancel Request
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
@endsection