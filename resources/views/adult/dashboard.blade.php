@extends('layouts.adult')

@section('title', 'Adult Dashboard')

@section('content')
@php
    $adult = auth()->user();
    $interestCount = method_exists($adult, 'interests') ? $adult->interests()->count() : 0;
@endphp

<style>
    @keyframes adultFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-7px); }
    }

    @keyframes adultGlow {
        0%, 100% { opacity: .35; transform: scale(1); }
        50% { opacity: .55; transform: scale(1.05); }
    }

    .adult-float {
        animation: adultFloat 6s ease-in-out infinite;
    }

    .adult-float-delay {
        animation: adultFloat 7.5s ease-in-out infinite;
        animation-delay: 1.2s;
    }

    .adult-glow {
        animation: adultGlow 5s ease-in-out infinite;
    }
</style>

<div class="space-y-6 adult-fade-up">
    <section class="adult-card adult-soft-gradient relative overflow-hidden rounded-[30px] p-6 sm:p-8">
        <div class="adult-glow absolute -left-10 -top-10 h-36 w-36 rounded-full bg-[rgba(203,20,139,0.12)] blur-3xl"></div>
        <div class="adult-glow absolute right-0 top-10 h-32 w-32 rounded-full bg-[rgba(98,10,136,0.10)] blur-3xl"></div>

        <div class="relative grid gap-8 xl:grid-cols-[1.35fr_0.9fr]">
            <div>
                <div class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">
                    Adult Dashboard
                </div>

                <h1 class="mt-5 max-w-3xl text-[30px] leading-tight font-semibold text-neutral-900 sm:text-[40px]">
                    A premium space to manage matching, letters, and your profile
                </h1>

                <p class="mt-4 max-w-3xl text-sm leading-8 text-neutral-600 sm:text-[15px]">
                    This adult dashboard is designed for a clean and focused experience, with room for profile management,
                    match discovery, communication, and letter activity in one place.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="#" class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white shadow-[0_18px_32px_rgba(98,10,136,0.16)] transition hover:-translate-y-0.5">
                        <i class="fa-solid fa-user-group text-sm"></i>
                        Browse Matches
                    </a>

                    <a href="#" class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                        <i class="fa-solid fa-envelope-open-text text-sm"></i>
                        View Letters
                    </a>

                    <a href="#" class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#620A88] hover:text-[#620A88]">
                        <i class="fa-solid fa-gear text-sm"></i>
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="adult-float absolute -left-3 top-0 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff4f8] text-[#CB148B] shadow-sm">
                    <i class="fa-solid fa-heart text-sm"></i>
                </div>

                <div class="adult-float-delay absolute -right-2 bottom-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88] shadow-sm">
                    <i class="fa-solid fa-star text-sm"></i>
                </div>

                <div class="rounded-[28px] border border-black/6 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="adult-gradient flex h-16 w-16 items-center justify-center rounded-3xl text-white shadow-sm">
                            <i class="fa-solid fa-user text-2xl"></i>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-neutral-500">Account Status</p>
                            <p class="mt-1 text-lg font-semibold text-neutral-900">
                                {{ ucfirst($adult->account_status ?? 'active') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-[#fff7fc] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Interests</p>
                            <p class="mt-2 text-xl font-semibold text-neutral-900">{{ $interestCount }}</p>
                        </div>

                        <div class="rounded-2xl bg-[#f7efff] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Verified</p>
                            <p class="mt-2 text-xl font-semibold text-neutral-900">
                                {{ $adult->email_verified_at ? 'Yes' : 'No' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-[#faf7fc] p-4">
                        <p class="text-xs text-neutral-500">Signed in as</p>
                        <p class="mt-1 text-sm font-medium text-neutral-900">{{ $adult->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="adult-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(17,17,17,0.06)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                <i class="fa-solid fa-user-group text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">My Matches</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Approved and suggested matches</p>
        </div>

        <div class="adult-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(17,17,17,0.06)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                <i class="fa-solid fa-envelope-open-text text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Letters</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Letters sent and received</p>
        </div>

        <div class="adult-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(17,17,17,0.06)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f4f4f5] text-neutral-800">
                <i class="fa-solid fa-message text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Messages</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Conversation updates and replies</p>
        </div>

        <div class="adult-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(17,17,17,0.06)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff7ef] text-amber-500">
                <i class="fa-solid fa-sliders text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Preferences</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">{{ $interestCount }}</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Interests and matching filters</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
        <div class="adult-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-[24px] font-semibold text-neutral-900">Quick actions</h3>
                    <p class="mt-1 text-sm text-neutral-500">Start from the areas you will use most often.</p>
                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white sm:flex">
                    <i class="fa-solid fa-bolt text-sm"></i>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <a href="#" class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#CB148B] hover:bg-[#fff8fc]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B] transition group-hover:scale-105">
                            <i class="fa-solid fa-user-group text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Discover Matches</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">Browse compatible adult profiles</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#620A88] hover:bg-[#f8f2ff]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88] transition group-hover:scale-105">
                            <i class="fa-solid fa-envelope-open-text text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Manage Letters</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">Track letter activity and history</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-neutral-300 hover:bg-neutral-50">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f4f4f5] text-neutral-800 transition group-hover:scale-105">
                            <i class="fa-solid fa-message text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Messages</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">See latest conversation updates</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#CB148B] hover:bg-[#fff8fc]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white transition group-hover:scale-105">
                            <i class="fa-solid fa-user-gear text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Update Profile</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">Manage bio, interests, and details</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="adult-card rounded-[30px] p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-[24px] font-semibold text-neutral-900">Profile summary</h3>
                        <p class="mt-1 text-sm text-neutral-500">Your current adult account overview.</p>
                    </div>

                    <div class="adult-float flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff4f8] text-[#CB148B] shadow-sm">
                        <i class="fa-solid fa-sparkles text-sm"></i>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-[#fff7fc] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Role</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst($adult->role) }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f7efff] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Email Verified</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ $adult->email_verified_at ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f4f4f5] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst($adult->account_status ?? 'active') }}</p>
                    </div>
                </div>
            </div>

            <div class="adult-card rounded-[30px] p-6">
                <h3 class="text-[24px] font-semibold text-neutral-900">Next steps</h3>
                <p class="mt-1 text-sm text-neutral-500">A few areas the adult user will likely use next.</p>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl border border-black/6 p-4">
                        <p class="text-sm font-medium text-neutral-900">Complete matching preferences</p>
                        <p class="mt-1 text-sm text-neutral-500">Refine interests and compatibility settings.</p>
                    </div>

                    <div class="rounded-2xl border border-black/6 p-4">
                        <p class="text-sm font-medium text-neutral-900">Prepare letter introduction</p>
                        <p class="mt-1 text-sm text-neutral-500">Create a good first introduction for new matches.</p>
                    </div>

                    <div class="rounded-2xl border border-black/6 p-4">
                        <p class="text-sm font-medium text-neutral-900">Review communication activity</p>
                        <p class="mt-1 text-sm text-neutral-500">Track match engagement and correspondence history.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection