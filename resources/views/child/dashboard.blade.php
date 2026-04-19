@extends('layouts.child')

@section('title', 'Child Dashboard')

@section('content')
    <div class="space-y-6">
        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Welcome Back
                    </div>

                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">
                        Hi {{ $child->name }}, your child space is ready
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-8 text-black/60">
                        This is your safe and colorful space for pen pals, letters, rewards, and fun profile updates.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('child.profile.complete') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-semibold text-white">
                            <i class="fa-solid fa-user-pen text-xs"></i>
                            Edit Profile
                        </a>

                        <a href="{{ route('child.pen-pals') }}"
                            class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-semibold text-black/70">
                            <i class="fa-solid fa-user-group text-xs"></i>
                            Explore Dashboard
                        </a>
                    </div>
                </div>

                <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                            <i class="fa-solid fa-rocket text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/45">Profile Status</p>
                            <p class="mt-2 text-3xl font-bold text-black">Ready to explore</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-[#fff7fc] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Interests</p>
                            <p class="mt-2 text-3xl font-bold text-black">{{ $child->interests->count() }}</p>
                        </div>

                        <div class="child-card rounded-[28px] p-5">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                            <h3 class="text-lg font-bold text-black">Coins</h3>
                            <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['coins'] }}</p>
                            <p class="mt-2 text-sm text-black/55">Reward wallet</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <h3 class="text-lg font-bold text-black">My Pen Pals</h3>
                <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['matches'] }}</p>
                <p class="mt-2 text-sm text-black/55">Approved child matches</p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f7efff] text-[#620A88]">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Letters</h3>
                <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['letters'] }}</p>
                <p class="mt-2 text-sm text-black/55">Sent and visible letters</p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff7fc] text-[#CB148B]">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Unread Letters</h3>
                <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['unread_letters'] }}</p>
                <p class="mt-2 text-sm text-black/55">New approved letters</p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100 text-black">
                    <i class="fa-solid fa-message"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Messages</h3>
                <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['unread_messages'] }}</p>
                <p class="mt-2 text-sm text-black/55">Unread chat messages</p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fffaf2] text-amber-500">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Pending</h3>
                <p class="mt-3 text-4xl font-bold text-black">{{ $dashboardStats['pending_requests'] }}</p>
                <p class="mt-2 text-sm text-black/55">Waiting for admin approval</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="child-card rounded-[30px] p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-black">Quick actions</h2>
                        <p class="mt-1 text-sm text-black/55">Jump into the things you will use most.</p>
                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('child.pen-pals') }}"
                        class="rounded-[24px] border border-black/8 p-5 hover:border-[#CB148B] hover:bg-[#fff7fc]">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-black">Find Pen Pals</h3>
                        <p class="mt-2 text-sm leading-7 text-black/55">See suggestions, pending approvals, and approved
                            matches.</p>
                    </a>

                    <a href="{{ route('child.letters') }}"
                        class="rounded-[24px] border border-black/8 p-5 hover:border-[#620A88] hover:bg-[#f7efff]">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f7efff] text-[#620A88]">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-black">View Letters</h3>
                        <p class="mt-2 text-sm leading-7 text-black/55">See submitted, approved, and received letters.</p>
                    </a>

                    <a href="{{ route('child.rewards') }}"
                        class="rounded-[24px] border border-black/8 p-5 hover:border-black/15 hover:bg-neutral-50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100 text-black">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-black">Rewards</h3>
                        <p class="mt-2 text-sm leading-7 text-black/55">See coins, unlocks, and reward activity.</p>
                    </a>

                    <a href="{{ route('child.shop') }}"
                        class="rounded-[24px] border border-black/8 p-5 hover:border-[#CB148B] hover:bg-[#fff7fc]">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                            <i class="fa-solid fa-shop"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-black">Shop</h3>
                        <p class="mt-2 text-sm leading-7 text-black/55">Spend coins on fun reward items and printables.</p>
                    </a>
                </div>
            </div>

            <div class="child-card rounded-[30px] p-6">
                <h2 class="text-2xl font-bold text-black">Approved pen pals</h2>
                <p class="mt-1 text-sm text-black/55">A quick view of active child-safe matches.</p>

                <div class="mt-5 space-y-4">
                    @forelse($approvedPenPals->take(4) as $penPal)
                        <div class="flex items-center justify-between gap-4 rounded-[24px] border border-black/8 p-4">
                            <div class="flex items-center gap-3">
                                @if($penPal?->profile?->avatar_type === 'upload' && $penPal?->profile?->avatar)
                                    <img src="{{ asset('storage/' . $penPal->profile->avatar) }}" alt="Avatar"
                                        class="h-12 w-12 rounded-2xl object-cover">
                                @elseif($penPal?->profile?->avatar_type === 'library' && $penPal?->profile?->avatarLibrary?->image_path)
                                    <img src="{{ asset('storage/' . $penPal->profile->avatarLibrary->image_path) }}" alt="Avatar"
                                        class="h-12 w-12 rounded-2xl object-cover">
                                @else
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-sm font-bold text-black">{{ $penPal->name }}</h3>
                                    <p class="text-xs text-black/45">{{ $penPal?->profile?->age_or_grade ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('child.messages.chat', $penPal) }}"
                                class="rounded-full border border-black/10 px-4 py-2 text-xs font-semibold text-black/70 hover:border-[#CB148B] hover:text-[#CB148B]">
                                Open Chat
                            </a>
                        </div>
                    @empty
                        <div class="rounded-[24px] border border-dashed border-black/10 p-5 text-sm text-black/55">
                            No approved pen pals yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection