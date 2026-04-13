@extends('layouts.guest')

@section('title', 'Pathways')

@section('content')
<section class="relative overflow-hidden bg-[#fcfbfe] pb-20 pt-8 sm:pb-24">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.08)] blur-3xl"></div>
    <div class="absolute right-0 top-40 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.08)] blur-3xl"></div>
    <div class="absolute bottom-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-[rgba(203,20,139,0.05)] blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Hero --}}
        <section class="rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#fff7fc,#f8f3ff)] px-6 py-10 shadow-sm sm:px-10 sm:py-14 lg:px-14">
            <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                        Pathways
                    </span>

                    <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight text-neutral-900 sm:text-5xl lg:text-[58px]">
                        Three clear pathways, one safe and structured platform
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">
                        Letter Getters is built so every user follows the right journey from the start.
                        Children, parents, and adults all have different roles, permissions, and responsibilities.
                        This keeps the experience easier to understand and much safer to manage.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.18)]">
                            <i class="fa-solid fa-user-plus text-sm"></i>
                            Create Account
                        </a>

                        <a href="{{ route('how-it-works') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                            <i class="fa-solid fa-diagram-project text-sm"></i>
                            See Full Process
                        </a>
                    </div>
                </div>

                <div class="rounded-[32px] border border-black/6 bg-white p-6 shadow-[0_20px_60px_rgba(17,17,17,0.06)] sm:p-7">
                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-[24px] bg-[#fff7fc] p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                <i class="fa-solid fa-child-reaching text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Child</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Safe registration, parent approval, child-friendly profile, and guided communication.
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-[#f7efff] p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                                <i class="fa-solid fa-user-shield text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Parent</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Approves child registration, stays linked to the child account, and supports safe visibility.
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-neutral-50 p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white shadow-sm">
                                <i class="fa-solid fa-user-group text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Adult</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Email verification, adult-only access, profile-based matching, and communication tools.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Overview Cards --}}
        <section class="mt-12">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Overview
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    How each pathway is designed
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    Each pathway has its own purpose, access rules, and workflow. This makes the platform easier to manage for users and administrators.
                </p>
            </div>

            <div class="grid gap-6 xl:grid-cols-3">
                {{-- Child --}}
                <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                            <i class="fa-solid fa-child-reaching text-lg"></i>
                        </div>
                        <span class="rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[#CB148B]">
                            Child Pathway
                        </span>
                    </div>

                    <h3 class="mt-5 text-2xl font-semibold text-neutral-900">Child users</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        The child pathway is built around safety, simplicity, and parent involvement.
                        Children do not enter directly into open communication. Their journey is controlled and structured from the first step.
                    </p>

                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">
                            Parent email is required during registration
                        </div>
                        <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">
                            Account remains pending until parent approval
                        </div>
                        <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">
                            Child completes profile with avatar and interests
                        </div>
                        <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">
                            Matching is managed within child-safe rules
                        </div>
                    </div>

                    <div class="mt-6 rounded-[24px] border border-black/6 bg-white p-5">
                        <h4 class="text-lg font-semibold text-neutral-900">Main goal</h4>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Let children enjoy a meaningful pen-pal experience while keeping adults and safety controls involved where required.
                        </p>
                    </div>
                </div>

                {{-- Parent --}}
                <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </div>
                        <span class="rounded-full bg-[#f7efff] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[#620A88]">
                            Parent Role
                        </span>
                    </div>

                    <h3 class="mt-5 text-2xl font-semibold text-neutral-900">Parents</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Parents are a key part of the child pathway. They do not simply observe from the outside.
                        They confirm the child account and can remain linked to important steps in the communication process.
                    </p>

                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">
                            Receives approval email after child registration
                        </div>
                        <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">
                            Becomes linked to the child account
                        </div>
                        <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">
                            Can support preview and approval visibility
                        </div>
                        <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">
                            Helps keep communication structured and safe
                        </div>
                    </div>

                    <div class="mt-6 rounded-[24px] border border-black/6 bg-white p-5">
                        <h4 class="text-lg font-semibold text-neutral-900">Main goal</h4>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Give parents the right level of involvement without making the platform confusing or difficult to use.
                        </p>
                    </div>
                </div>

                {{-- Adult --}}
                <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-900">
                            <i class="fa-solid fa-user-group text-lg"></i>
                        </div>
                        <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-neutral-700">
                            Adult Pathway
                        </span>
                    </div>

                    <h3 class="mt-5 text-2xl font-semibold text-neutral-900">Adult users</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Adult users follow a separate route for adult-to-adult communication. Their experience is not mixed with the child pathway.
                        They register, verify email, complete their profile, and then use the platform through the adult dashboard.
                    </p>

                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">
                            Email verification is required before login
                        </div>
                        <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">
                            Adults complete interests and profile details
                        </div>
                        <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">
                            Matching remains within adult-only flow
                        </div>
                        <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">
                            Dashboard supports profile, matches, and letters
                        </div>
                    </div>

                    <div class="mt-6 rounded-[24px] border border-black/6 bg-white p-5">
                        <h4 class="text-lg font-semibold text-neutral-900">Main goal</h4>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Provide a cleaner, more independent experience for adult users while preserving structure and platform standards.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Journey Comparison --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Compare the Journeys
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Same platform, different responsibilities
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    The table below helps users quickly understand how the three pathways differ.
                </p>
            </div>

            <div class="overflow-x-auto">
                <div class="min-w-[840px] rounded-[28px] border border-black/6">
                    <div class="grid grid-cols-4 border-b border-black/6 bg-neutral-50">
                        <div class="px-5 py-4 text-sm font-semibold text-neutral-500">Area</div>
                        <div class="px-5 py-4 text-sm font-semibold text-[#CB148B]">Child</div>
                        <div class="px-5 py-4 text-sm font-semibold text-[#620A88]">Parent</div>
                        <div class="px-5 py-4 text-sm font-semibold text-neutral-700">Adult</div>
                    </div>

                    @php
                        $rows = [
                            ['label' => 'Registration', 'child' => 'Registers with parent email', 'parent' => 'Receives child approval request', 'adult' => 'Registers directly'],
                            ['label' => 'Approval', 'child' => 'Waits for parent confirmation', 'parent' => 'Approves child account', 'adult' => 'Verifies email'],
                            ['label' => 'Profile', 'child' => 'Child-friendly profile and avatar', 'parent' => 'Parent account linked to child', 'adult' => 'Independent adult profile'],
                            ['label' => 'Matching', 'child' => 'Child-safe matching flow', 'parent' => 'Supports visibility and approval', 'adult' => 'Adult-only matching flow'],
                            ['label' => 'Communication', 'child' => 'Guided and reviewed letter experience', 'parent' => 'Supports controlled communication', 'adult' => 'Independent adult communication'],
                        ];
                    @endphp

                    @foreach($rows as $row)
                        <div class="grid grid-cols-4 border-b border-black/6 last:border-b-0">
                            <div class="px-5 py-5 text-sm font-semibold text-neutral-900">{{ $row['label'] }}</div>
                            <div class="px-5 py-5 text-sm leading-7 text-neutral-600">{{ $row['child'] }}</div>
                            <div class="px-5 py-5 text-sm leading-7 text-neutral-600">{{ $row['parent'] }}</div>
                            <div class="px-5 py-5 text-sm leading-7 text-neutral-600">{{ $row['adult'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Visual Steps --}}
        <section class="mt-12">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Visual Journey
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    How the pathways connect
                </h2>
            </div>

            <div class="grid gap-5 lg:grid-cols-4">
                <div class="rounded-[30px] bg-[#fff7fc] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">1. Register</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        User selects the correct pathway and creates the account.
                    </p>
                </div>

                <div class="rounded-[30px] bg-[#f7efff] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                        <i class="fa-solid fa-envelope-circle-check"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">2. Verify or Approve</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        Parent approval or adult email verification is completed.
                    </p>
                </div>

                <div class="rounded-[30px] bg-neutral-50 p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-black text-white">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">3. Build Profile</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        Interests, avatar, details, and preferences are added.
                    </p>
                </div>

                <div class="rounded-[30px] bg-[#fffaf2] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">4. Use Platform</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        Matching, letters, review, and communication begin through the correct role flow.
                    </p>
                </div>
            </div>
        </section>

        {{-- Why it matters --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#ffffff,#faf6fd)] px-6 py-10 shadow-sm sm:px-10 sm:py-12">
            <div class="grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-center">
                <div>
                    <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                        Why Pathways Matter
                    </span>
                    <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                        Clear roles make the whole system safer and easier
                    </h2>
                    <p class="mt-4 text-base leading-8 text-neutral-600">
                        Pathways reduce confusion, keep users in the right experience, and help the platform apply the right controls at every step.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[26px] border border-black/6 bg-white p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-neutral-900">More Safety</h3>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Users only access the parts of the platform meant for their role.
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-black/6 bg-white p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-neutral-900">Clearer Workflow</h3>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Every user knows what step comes next and why it matters.
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-black/6 bg-white p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-900">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-neutral-900">Better Management</h3>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Admin tools can apply the right review and moderation logic.
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-black/6 bg-white p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#fffaf2] text-amber-500">
                            <i class="fa-solid fa-users-viewfinder"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-neutral-900">Better Matching</h3>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Interests and role-based journeys make matching more suitable.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-white px-6 py-10 shadow-sm sm:px-10 sm:py-12">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Continue Exploring
                </span>

                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Explore the platform with the right expectations
                </h2>

                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    Understanding the pathways makes it easier for every user to know how the platform works and what role they play in the process.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('how-it-works') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.18)]">
                        <i class="fa-solid fa-circle-info text-sm"></i>
                        How It Works
                    </a>

                    <a href="{{ route('safety') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                        View Safety Page
                    </a>
                </div>
            </div>
        </section>
    </div>
</section>
@endsection