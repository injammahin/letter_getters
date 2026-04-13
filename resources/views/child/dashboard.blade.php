@extends('layouts.child')

@section('title', 'Child Dashboard')

@section('content')
@php
    $child = auth()->user();
    $profile = $child->profile;
    $interestCount = $child->interests()->count();
@endphp

<style>
    @keyframes childFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    @keyframes childGlow {
        0%, 100% { opacity: .35; transform: scale(1); }
        50% { opacity: .55; transform: scale(1.06); }
    }

    @keyframes childFadeUp {
        0% { opacity: 0; transform: translateY(14px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .child-float {
        animation: childFloat 6s ease-in-out infinite;
    }

    .child-float-delay {
        animation: childFloat 7.5s ease-in-out infinite;
        animation-delay: 1.2s;
    }

    .child-glow {
        animation: childGlow 5s ease-in-out infinite;
    }

    .child-fade-up {
        animation: childFadeUp .55s ease-out both;
    }
</style>

<div
    x-data="{ showSuccess: {{ session()->has('profile_completed') ? 'true' : 'false' }} }"
    x-init="if (showSuccess) { setTimeout(() => showSuccess = false, 2200) }"
    class="space-y-6"
>
    <div
        x-show="showSuccess"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/30 p-4"
    >
        <div class="w-full max-w-md rounded-[28px] bg-white p-8 text-center shadow-[0_28px_80px_rgba(17,17,17,0.16)]">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm">
                <i class="fa-solid fa-circle-check text-2xl"></i>
            </div>

            <h2 class="mt-5 text-2xl font-semibold text-neutral-900">
                Profile completed
            </h2>

            <p class="mt-3 text-sm leading-7 text-neutral-600">
                Everything looks good. Your child profile is now ready.
            </p>
        </div>
    </div>

    <section class="child-card child-soft relative overflow-hidden rounded-[30px] p-6 sm:p-8 child-fade-up">
        <div class="child-glow absolute -left-10 -top-10 h-36 w-36 rounded-full bg-[rgba(203,20,139,0.16)] blur-3xl"></div>
        <div class="child-glow absolute right-0 top-6 h-32 w-32 rounded-full bg-[rgba(98,10,136,0.14)] blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <div class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-3.5 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Welcome back
                </div>

                <h1 class="mt-4 text-[28px] leading-tight font-semibold text-neutral-900 sm:text-[34px]">
                    Hi {{ $child->name }}, your child space is ready
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-neutral-600">
                    This is your safe and colorful space for pen pals, letters, rewards, and fun profile updates.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('child.profile.complete') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_16px_30px_rgba(98,10,136,0.18)]">
                        <i class="fa-solid fa-image-portrait text-sm"></i>
                        Edit profile
                    </a>

                    <a href="#quick-actions"
                       class="inline-flex items-center gap-2 rounded-full border border-neutral-200 bg-white px-5 py-3 text-sm font-medium text-neutral-700 transition duration-300 hover:border-[#CB148B] hover:text-[#CB148B]">
                        <i class="fa-solid fa-arrow-down text-sm"></i>
                        Explore dashboard
                    </a>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-sm lg:mx-0">
                <div class="child-float absolute -left-4 top-0 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff1c9] text-lg shadow-sm">
                    ✨
                </div>

                <div class="child-float-delay absolute -right-2 bottom-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#e7f6ff] text-lg shadow-sm">
                    🌈
                </div>

                <div class="rounded-[28px] border border-black/6 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm">
                            <i class="fa-solid fa-rocket text-2xl"></i>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-500">Profile status</p>
                            <p class="mt-1 text-lg font-semibold text-neutral-900">
                                {{ $child->hasCompletedChildProfile() ? 'Ready to explore' : 'Needs setup' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-[#fff7fc] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Interests</p>
                            <p class="mt-2 text-xl font-semibold text-neutral-900">{{ $interestCount }}</p>
                        </div>

                        <div class="rounded-2xl bg-[#f7efff] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Coins</p>
                            <p class="mt-2 text-xl font-semibold text-neutral-900">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4 child-fade-up">
        <div class="child-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_34px_rgba(17,17,17,0.08)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                <i class="fa-solid fa-user-group text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">My Pen Pals</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Safe child matches</p>
        </div>

        <div class="child-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_34px_rgba(17,17,17,0.08)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                <i class="fa-solid fa-envelope-open-text text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Letters</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Sent and received</p>
        </div>

        <div class="child-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_34px_rgba(17,17,17,0.08)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f6f6f6] text-neutral-800">
                <i class="fa-solid fa-coins text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Coins</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Reward wallet</p>
        </div>

        <div class="child-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_34px_rgba(17,17,17,0.08)]">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff8df] text-amber-500">
                <i class="fa-solid fa-star text-base"></i>
            </div>
            <h3 class="text-base font-semibold text-neutral-900">Printables</h3>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">0</p>
            <p class="mt-2 text-sm leading-6 text-neutral-500">Unlocked items</p>
        </div>
    </section>

    <section id="quick-actions" class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] child-fade-up">
        <div class="child-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-900">Quick actions</h2>
                    <p class="mt-1 text-sm text-neutral-500">Jump into the things you will use most.</p>
                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white sm:flex">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <a href="#"
                   class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#CB148B] hover:bg-[#fff8fc]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B] transition group-hover:scale-105">
                            <i class="fa-solid fa-user-group text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">My Pen Pals</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">See approved child matches</p>
                        </div>
                    </div>
                </a>

                <a href="#"
                   class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#620A88] hover:bg-[#f8f2ff]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88] transition group-hover:scale-105">
                            <i class="fa-solid fa-envelope-open-text text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Letters</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">Track sending and receiving</p>
                        </div>
                    </div>
                </a>

                <a href="#"
                   class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-neutral-300 hover:bg-neutral-50">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f6f6f6] text-neutral-800 transition group-hover:scale-105">
                            <i class="fa-solid fa-coins text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">Coin Wallet</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">See rewards and balance</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('child.profile.complete') }}"
                   class="group rounded-[24px] border border-neutral-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-[#CB148B] hover:bg-[#fff8fc]">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white transition group-hover:scale-105">
                            <i class="fa-solid fa-image-portrait text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">My Profile</p>
                            <p class="mt-1 text-xs leading-6 text-neutral-500">Update avatar and interests</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="child-card rounded-[30px] p-6">
                <h2 class="text-xl font-semibold text-neutral-900">Profile summary</h2>
                <p class="mt-1 text-sm text-neutral-500">A quick view of the child account.</p>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-[#fff7fc] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Role</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst($child->role) }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f7efff] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Status</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ ucfirst($child->account_status ?? 'active') }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#eaf8ff] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-sky-700">Profile completed</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">
                            {{ $child->hasCompletedChildProfile() ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="child-card rounded-[30px] p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-900">Fun corner</h2>
                        <p class="mt-1 text-sm text-neutral-500">A soft playful touch for the child dashboard.</p>
                    </div>

                    <div class="child-float flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff1c9] text-lg shadow-sm">
                        🎈
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-[#fff8df] p-4 text-center">
                        <div class="text-2xl">⭐</div>
                        <p class="mt-2 text-xs font-medium text-neutral-700">Shine</p>
                    </div>

                    <div class="rounded-2xl bg-[#eef9ff] p-4 text-center">
                        <div class="text-2xl">🚀</div>
                        <p class="mt-2 text-xs font-medium text-neutral-700">Explore</p>
                    </div>

                    <div class="rounded-2xl bg-[#f8efff] p-4 text-center">
                        <div class="text-2xl">🦄</div>
                        <p class="mt-2 text-xs font-medium text-neutral-700">Imagine</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection