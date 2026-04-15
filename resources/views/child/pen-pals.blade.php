@extends('layouts.child')

@section('title', 'My Pen Pals')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-[24px] border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-[24px] border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        My Pen Pals
                    </div>

                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">
                        Find child-safe pen pal matches
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        Suggested matches are based on interests, age or grade, and profile readiness.
                        When you request a match, it stays pending until admin approves it.
                        After approval, both children can see the match, open chat, and write letters.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[24px] bg-[#fff7fc] p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#CB148B]">Suggestions</p>
                        <p class="mt-3 text-3xl font-bold text-black">{{ $suggestions->count() }}</p>
                    </div>

                    <div class="rounded-[24px] bg-[#f7efff] p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#620A88]">Pending</p>
                        <p class="mt-3 text-3xl font-bold text-black">{{ $pendingRequests->count() }}</p>
                    </div>

                    <div class="rounded-[24px] bg-neutral-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/45">Approved</p>
                        <p class="mt-3 text-3xl font-bold text-black">{{ $approvedMatches->count() }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <div>
                <h2 class="text-2xl font-bold text-black">Suggested matches</h2>
                <p class="mt-1 text-sm text-black/55">Children with shared interests and a good profile fit.</p>
            </div>

            @if($suggestions->isEmpty())
                <div class="child-card rounded-[28px] p-6 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                        <i class="fa-solid fa-user-group text-lg"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-black">No suggested matches yet</h3>
                    <p class="mt-2 text-sm leading-7 text-black/55">
                        Make sure your profile is complete and your interests are selected. More child-safe suggestions will
                        appear here.
                    </p>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($suggestions as $suggestion)
                        <div class="child-card rounded-[28px] p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    @if($suggestion->profile?->avatar_type === 'upload' && $suggestion->profile?->avatar)
                                        <img src="{{ asset('storage/' . $suggestion->profile->avatar) }}" alt="Avatar"
                                            class="h-14 w-14 rounded-2xl object-cover">
                                    @elseif($suggestion->profile?->avatar_type === 'library' && $suggestion->profile?->avatarLibrary?->image_path)
                                        <img src="{{ asset('storage/' . $suggestion->profile->avatarLibrary->image_path) }}" alt="Avatar"
                                            class="h-14 w-14 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="text-lg font-bold text-black">{{ $suggestion->name }}</h3>
                                        <p class="text-sm text-black/50">{{ $suggestion->profile?->age_or_grade ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <span class="rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-bold text-[#CB148B]">
                                    {{ $suggestion->match_score }} pts
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-[#fff7fc] p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Shared Interests
                                    </p>
                                    <p class="mt-2 text-xl font-bold text-black">{{ $suggestion->shared_interest_count }}</p>
                                </div>

                                <div class="rounded-2xl bg-[#f7efff] p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Location</p>
                                    <p class="mt-2 text-sm font-semibold text-black">
                                        {{ $suggestion->profile?->city ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Common interests</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($suggestion->shared_interest_names as $interestName)
                                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/65">
                                            {{ $interestName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <form action="{{ route('child.pen-pals.request', $suggestion) }}" method="POST" class="mt-5">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                    Send Match Request
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="space-y-4">
            <div>
                <h2 class="text-2xl font-bold text-black">Pending requests</h2>
                <p class="mt-1 text-sm text-black/55">Requests you sent that are still waiting for admin approval.</p>
            </div>

            @if($pendingRequests->isEmpty())
                <div class="child-card rounded-[28px] p-6">
                    <p class="text-sm text-black/55">No pending requests right now.</p>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($pendingRequests as $requestItem)
                        <div class="child-card rounded-[28px] p-5">
                            <div class="flex items-center gap-3">
                                @if($requestItem->target?->profile?->avatar_type === 'upload' && $requestItem->target?->profile?->avatar)
                                    <img src="{{ asset('storage/' . $requestItem->target->profile->avatar) }}" alt="Avatar"
                                        class="h-14 w-14 rounded-2xl object-cover">
                                @elseif($requestItem->target?->profile?->avatar_type === 'library' && $requestItem->target?->profile?->avatarLibrary?->image_path)
                                    <img src="{{ asset('storage/' . $requestItem->target->profile->avatarLibrary->image_path) }}"
                                        alt="Avatar" class="h-14 w-14 rounded-2xl object-cover">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-bold text-black">{{ $requestItem->target?->name }}</h3>
                                    <p class="text-sm text-black/50">{{ $requestItem->target?->profile?->age_or_grade ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl bg-[#f7efff] p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Status</p>
                                <p class="mt-2 text-sm font-semibold text-black">Waiting for admin approval</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Common interests</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($requestItem->shared_interest_names as $interestName)
                                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/65">
                                            {{ $interestName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <p class="mt-4 text-xs text-black/45">
                                Requested on {{ $requestItem->created_at?->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="space-y-4">
            <div>
                <h2 class="text-2xl font-bold text-black">Approved pen pals</h2>
                <p class="mt-1 text-sm text-black/55">These matches are approved and visible to both children.</p>
            </div>

            @if($approvedMatches->isEmpty())
                <div class="child-card rounded-[28px] p-6">
                    <p class="text-sm text-black/55">No approved pen pals yet.</p>
                </div>
            @else
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($approvedMatches as $approvedMatch)
                        <div class="child-card rounded-[28px] p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    @if($approvedMatch->pen_pal?->profile?->avatar_type === 'upload' && $approvedMatch->pen_pal?->profile?->avatar)
                                        <img src="{{ asset('storage/' . $approvedMatch->pen_pal->profile->avatar) }}" alt="Avatar"
                                            class="h-14 w-14 rounded-2xl object-cover">
                                    @elseif($approvedMatch->pen_pal?->profile?->avatar_type === 'library' && $approvedMatch->pen_pal?->profile?->avatarLibrary?->image_path)
                                        <img src="{{ asset('storage/' . $approvedMatch->pen_pal->profile->avatarLibrary->image_path) }}"
                                            alt="Avatar" class="h-14 w-14 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl child-gradient text-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="text-lg font-bold text-black">{{ $approvedMatch->pen_pal?->name }}</h3>
                                        <p class="text-sm text-black/50">
                                            {{ $approvedMatch->pen_pal?->profile?->age_or_grade ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                    Approved
                                </span>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-[#fff7fc] p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">City</p>
                                    <p class="mt-2 text-sm font-semibold text-black">
                                        {{ $approvedMatch->pen_pal?->profile?->city ?? 'N/A' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-[#f7efff] p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">State</p>
                                    <p class="mt-2 text-sm font-semibold text-black">
                                        {{ $approvedMatch->pen_pal?->profile?->state ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Common interests</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($approvedMatch->shared_interest_names as $interestName)
                                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/65">
                                            {{ $interestName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <p class="mt-4 text-xs text-black/45">
                                Approved on {{ $approvedMatch->approved_at?->format('d M Y, h:i A') }}
                            </p>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <a href="{{ route('child.messages.chat', $approvedMatch->pen_pal) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-full border border-black/10 bg-white px-4 py-3 text-sm font-semibold text-black/70 hover:border-[#620A88] hover:text-[#620A88]">
                                    <i class="fa-solid fa-message text-xs"></i>
                                    Open Chat
                                </a>

                                <a href="{{ route('child.letters.create', $approvedMatch->pen_pal) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-4 py-3 text-sm font-semibold text-white">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    Write Letter
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection