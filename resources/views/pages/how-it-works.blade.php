@extends('layouts.guest')

@section('title', 'How It Works')

@section('content')
<section class="relative overflow-hidden bg-[#fcfbfe] pb-20 pt-8 sm:pb-24">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.08)] blur-3xl"></div>
    <div class="absolute right-0 top-40 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.08)] blur-3xl"></div>
    <div class="absolute bottom-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-[rgba(203,20,139,0.05)] blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Hero --}}
        <section class="rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#fff7fc,#f8f3ff)] px-6 py-10 shadow-sm sm:px-10 sm:py-14 lg:px-14">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                        How It Works
                    </span>

                    <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight text-neutral-900 sm:text-5xl lg:text-[58px]">
                        A safe and guided way to connect, write, and grow
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">
                        Letter Getters is designed to make communication simple, structured, and safe.
                        Users join through the right pathway, complete their profile, get matched carefully,
                        and send letters through a monitored process that keeps parents and safety controls involved where needed.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.18)]">
                            <i class="fa-solid fa-user-plus text-sm"></i>
                            Get Started
                        </a>

                        <a href="{{ route('support') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                            <i class="fa-solid fa-life-ring text-sm"></i>
                            Need Help?
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <div class="rounded-full border border-black/6 bg-white px-4 py-2 text-sm text-neutral-700">
                            <i class="fa-solid fa-child-reaching mr-2 text-[#CB148B]"></i>
                            Child pathway
                        </div>
                        <div class="rounded-full border border-black/6 bg-white px-4 py-2 text-sm text-neutral-700">
                            <i class="fa-solid fa-user-shield mr-2 text-[#620A88]"></i>
                            Parent approval
                        </div>
                        <div class="rounded-full border border-black/6 bg-white px-4 py-2 text-sm text-neutral-700">
                            <i class="fa-solid fa-user-group mr-2 text-[#CB148B]"></i>
                            Adult pathway
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -left-4 -top-4 h-24 w-24 rounded-3xl bg-[rgba(203,20,139,0.12)] blur-2xl"></div>
                    <div class="absolute -bottom-4 -right-4 h-28 w-28 rounded-3xl bg-[rgba(98,10,136,0.12)] blur-2xl"></div>

                    <div class="relative rounded-[32px] border border-black/6 bg-white p-5 shadow-[0_20px_60px_rgba(17,17,17,0.06)] sm:p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-[24px] bg-[#fff7fc] p-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                    <i class="fa-solid fa-route text-lg"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-neutral-900">Choose pathway</h3>
                                <p class="mt-2 text-sm leading-7 text-neutral-500">
                                    Child and adult users start with the right registration flow.
                                </p>
                            </div>

                            <div class="rounded-[24px] bg-[#f7efff] p-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                                    <i class="fa-solid fa-envelope-circle-check text-lg"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-neutral-900">Verify & approve</h3>
                                <p class="mt-2 text-sm leading-7 text-neutral-500">
                                    Parents approve child registration and adults verify email.
                                </p>
                            </div>

                            <div class="rounded-[24px] bg-[#fffaf2] p-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                                    <i class="fa-solid fa-id-badge text-lg"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-neutral-900">Complete profile</h3>
                                <p class="mt-2 text-sm leading-7 text-neutral-500">
                                    Add interests, details, and preferences for better matching.
                                </p>
                            </div>

                            <div class="rounded-[24px] bg-[#f5f7ff] p-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm">
                                    <i class="fa-solid fa-envelope-open-text text-lg"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-semibold text-neutral-900">Write letters</h3>
                                <p class="mt-2 text-sm leading-7 text-neutral-500">
                                    Letters move through the review and fulfillment flow safely.
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-[24px] border border-black/6 bg-neutral-50 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-black text-white">
                                    <i class="fa-solid fa-shield-heart"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-neutral-900">Built with safety in mind</h4>
                                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                                        Registration, approval, matching, preview, moderation, and account controls all work together to keep the experience structured and trustworthy.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Main Steps --}}
        <section class="mt-10">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Step by Step
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    How the full process works
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    From joining the platform to building meaningful communication, each step is guided clearly so users always know what comes next.
                </p>
            </div>

            <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
                @php
                    $steps = [
                        [
                            'number' => '01',
                            'icon' => 'fa-route',
                            'title' => 'Choose the right pathway',
                            'desc' => 'Users begin by choosing either the child pathway or the adult pathway. This ensures the right rules, approvals, and experience are applied from the start.',
                            'bg' => 'bg-[#fff7fc]',
                            'iconBg' => 'bg-[linear-gradient(135deg,#CB148B,#620A88)]',
                            'iconText' => 'text-white',
                        ],
                        [
                            'number' => '02',
                            'icon' => 'fa-user-check',
                            'title' => 'Register the account',
                            'desc' => 'Users enter their name, username, email, password, and basic profile details. Children also provide a parent email so a parent can approve the account.',
                            'bg' => 'bg-[#f7efff]',
                            'iconBg' => 'bg-white',
                            'iconText' => 'text-[#620A88]',
                        ],
                        [
                            'number' => '03',
                            'icon' => 'fa-envelope-circle-check',
                            'title' => 'Complete verification or approval',
                            'desc' => 'Adults must verify their email before signing in. Children remain pending until the parent opens the approval link, confirms the account, and becomes linked to the child.',
                            'bg' => 'bg-[#fffaf2]',
                            'iconBg' => 'bg-white',
                            'iconText' => 'text-amber-500',
                        ],
                        [
                            'number' => '04',
                            'icon' => 'fa-id-card',
                            'title' => 'Complete the profile',
                            'desc' => 'After access is granted, the user completes profile information such as interests, avatar, bio, age or grade, city, state, and preferences used for matching.',
                            'bg' => 'bg-[#f5f7ff]',
                            'iconBg' => 'bg-white',
                            'iconText' => 'text-sky-600',
                        ],
                        [
                            'number' => '05',
                            'icon' => 'fa-user-group',
                            'title' => 'Get matched safely',
                            'desc' => 'The system uses pathway rules, interests, and profile information to produce safe and suitable matches. Admin controls and moderation can guide or approve this flow where required.',
                            'bg' => 'bg-[#fff7fc]',
                            'iconBg' => 'bg-black',
                            'iconText' => 'text-white',
                        ],
                        [
                            'number' => '06',
                            'icon' => 'fa-envelope-open-text',
                            'title' => 'Write, review, and send letters',
                            'desc' => 'Letters move through a controlled workflow. For child accounts, parent preview and admin review can be applied. After approval, letters move toward forwarding and fulfillment.',
                            'bg' => 'bg-[#f7efff]',
                            'iconBg' => 'bg-white',
                            'iconText' => 'text-[#CB148B]',
                        ],
                    ];
                @endphp

                @foreach($steps as $step)
                    <div class="group rounded-[30px] border border-black/6 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_50px_rgba(17,17,17,0.06)]">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $step['iconBg'] }} {{ $step['iconText'] }}">
                                <i class="fa-solid {{ $step['icon'] }} text-lg"></i>
                            </div>
                            <span class="rounded-full {{ $step['bg'] }} px-3 py-1 text-xs font-semibold tracking-[0.16em] text-neutral-700">
                                {{ $step['number'] }}
                            </span>
                        </div>

                        <h3 class="mt-5 text-xl font-semibold text-neutral-900">
                            {{ $step['title'] }}
                        </h3>

                        <p class="mt-3 text-sm leading-8 text-neutral-500">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Visual Flow --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
            <div class="text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Visual Flow
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    From registration to real communication
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    This flow is designed to be easy to understand for families, children, and adults using the platform for the first time.
                </p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-5">
                <div class="relative rounded-[28px] bg-[#fff7fc] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                        <i class="fa-solid fa-user-plus text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Join</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Register through the child or adult pathway.</p>
                </div>

                <div class="relative rounded-[28px] bg-[#f7efff] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                        <i class="fa-solid fa-badge-check text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Approve</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Parent approval or adult email verification is completed.</p>
                </div>

                <div class="relative rounded-[28px] bg-[#fffaf2] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                        <i class="fa-solid fa-pen-ruler text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Set Up</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Profile, interests, and preferences are completed.</p>
                </div>

                <div class="relative rounded-[28px] bg-[#f5f7ff] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm">
                        <i class="fa-solid fa-people-arrows text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Match</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Users are paired using safe pathway and interest rules.</p>
                </div>

                <div class="relative rounded-[28px] bg-neutral-50 p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-black text-white shadow-sm">
                        <i class="fa-solid fa-envelope-open-text text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Write</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Letters move through preview, approval, and delivery flow.</p>
                </div>
            </div>
        </section>
        {{-- FAQ Style Explanations --}}
        <section class="mt-12">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Common Questions
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Clear answers for first-time users
                </h2>
            </div>

            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">What happens after a child registers?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        The child account stays pending. A parent approval email is sent to the parent email address.
                        Only after the parent confirms can the child continue into the platform and complete the profile.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">What happens after an adult registers?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        The adult account is created, but email verification must be completed before login is allowed.
                        After verification, the adult can enter the adult dashboard and continue profile setup and matching.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">How are matches created?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Matching can use interests, pathway rules, age or profile data, and admin-guided approval flow.
                        This helps the platform create more suitable and safer communication opportunities.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">How does the letter process stay safe?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        The platform can apply parent preview, admin review, moderation, and tracking before letters are fulfilled.
                        This creates structure around communication instead of leaving it unmanaged.
                    </p>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#ffffff,#faf6fd)] px-6 py-10 shadow-sm sm:px-10 sm:py-12">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Start the Journey
                </span>

                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Ready to join Letter Getters?
                </h2>

                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    Choose the right pathway, complete the right steps, and begin a structured communication experience designed around clarity and safety.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.18)]">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                        Create Account
                    </a>

                    <a href="{{ route('support') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                        <i class="fa-solid fa-headset text-sm"></i>
                        Contact Support
                    </a>
                </div>
            </div>
        </section>
    </div>
</section>
@endsection