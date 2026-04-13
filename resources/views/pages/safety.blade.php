@extends('layouts.guest')

@section('title', 'Safety')

@section('content')
<section class="relative overflow-hidden bg-[#fcfbfe] pb-20 pt-8 sm:pb-24">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.08)] blur-3xl"></div>
    <div class="absolute right-0 top-40 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.08)] blur-3xl"></div>
    <div class="absolute bottom-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-[rgba(98,10,136,0.05)] blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Hero --}}
        <section class="rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#ffffff,#f7f3ff)] px-6 py-10 shadow-sm sm:px-10 sm:py-14 lg:px-14">
            <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                        Safety
                    </span>

                    <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight text-neutral-900 sm:text-5xl lg:text-[58px]">
                        Safety is built into every step of the platform
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">
                        Letter Getters is not just a matching platform. It is a guided communication system with approval layers, moderation tools, profile controls, and review workflows designed to support safer use for everyone.
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
                            Support
                        </a>
                    </div>
                </div>

                <div class="rounded-[32px] border border-black/6 bg-white p-6 shadow-[0_20px_60px_rgba(17,17,17,0.06)] sm:p-7">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[24px] bg-[#f7efff] p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                                <i class="fa-solid fa-user-lock text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Controlled Access</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Different users follow different routes and permissions.
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-[#fff7fc] p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                <i class="fa-solid fa-user-check text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Approval Layers</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Parent approval and adult email verification add control.
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-neutral-50 p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                                <i class="fa-solid fa-eye text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Preview & Review</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Communication can be checked before moving forward.
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-[#fffaf2] p-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                                <i class="fa-solid fa-shield-heart text-lg"></i>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-neutral-900">Moderation Tools</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Admin tools support suspension, reports, and audit visibility.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-[24px] border border-black/6 bg-neutral-50 p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-neutral-900">Safety is continuous</h4>
                                <p class="mt-2 text-sm leading-7 text-neutral-500">
                                    Safety does not begin after matching. It begins at registration and continues through account approval, profile setup, moderation, letter review, and support handling.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Safety Pillars --}}
        <section class="mt-12">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Safety Pillars
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    The core layers that protect the experience
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    The platform uses multiple layers instead of relying on a single rule or feature.
                </p>
            </div>

            <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f7efff] text-[#620A88]">
                        <i class="fa-solid fa-route text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">Pathway Control</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Child, parent, and adult users are kept in the correct pathway from the start.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff7fc] text-[#CB148B]">
                        <i class="fa-solid fa-envelope-circle-check text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">Approval & Verification</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Child accounts depend on parent approval and adult accounts depend on email verification.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-900">
                        <i class="fa-solid fa-eye text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">Review Workflows</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Preview queues and admin visibility help keep letter handling structured.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fffaf2] text-amber-500">
                        <i class="fa-solid fa-flag text-lg"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-neutral-900">Moderation & Support</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Reports, suspended accounts, support tickets, and admin controls provide operational oversight.
                    </p>
                </div>
            </div>
        </section>

        {{-- Detailed Sections --}}
        <section class="mt-12 grid gap-6 xl:grid-cols-2">
            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff7fc] text-[#CB148B]">
                    <i class="fa-solid fa-user-lock text-lg"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-neutral-900">1. Safer registration and access</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    The first safety layer begins before a user can fully use the platform. Child registration needs a parent email.
                    Adult registration requires email verification. This reduces uncontrolled account access.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">Children do not enter the system freely without approval</div>
                    <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">Adults must verify email ownership before login</div>
                    <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">Different roles get different access from the start</div>
                </div>
            </div>

            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f7efff] text-[#620A88]">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-neutral-900">2. Parent involvement for child accounts</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    Parent approval is a central part of child account safety. The parent confirms the account and can remain connected to the child flow where visibility is needed.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">Parent gets an approval email link</div>
                    <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">Child account stays pending until the parent confirms</div>
                    <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">Parent-child linking supports oversight and trust</div>
                </div>
            </div>

            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-900">
                    <i class="fa-solid fa-id-card text-lg"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-neutral-900">3. Profile-based control and matching</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    User profiles are not only for display. They help shape matching, define the correct pathway flow,
                    and give the platform clearer structure for communication and moderation.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">Interests help create more suitable matches</div>
                    <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">Avatars and child profile design support age-appropriate setup</div>
                    <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">Adult and child flows stay separated</div>
                </div>
            </div>

            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fffaf2] text-amber-500">
                    <i class="fa-solid fa-envelope-open-text text-lg"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-neutral-900">4. Reviewed communication workflow</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    Letters can pass through a structured review flow before final fulfillment. This keeps communication from moving directly without visibility in sensitive cases.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-[#fffaf2] px-4 py-3 text-sm text-neutral-700">Incoming letter review and preview queue</div>
                    <div class="rounded-2xl bg-[#fffaf2] px-4 py-3 text-sm text-neutral-700">Status tracking and forwarding workflow</div>
                    <div class="rounded-2xl bg-[#fffaf2] px-4 py-3 text-sm text-neutral-700">Better traceability in mail operations</div>
                </div>
            </div>
        </section>

        {{-- Visual Process --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
            <div class="text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Safety Flow
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    How safety moves through the system
                </h2>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-5">
                <div class="rounded-[28px] bg-[#fff7fc] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Register</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">User enters through the correct pathway.</p>
                </div>

                <div class="rounded-[28px] bg-[#f7efff] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#620A88] shadow-sm">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Approve</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Parent approval or email verification is completed.</p>
                </div>

                <div class="rounded-[28px] bg-neutral-50 p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-black text-white shadow-sm">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Profile</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Profile details and interests shape matching rules.</p>
                </div>

                <div class="rounded-[28px] bg-[#fffaf2] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-amber-500 shadow-sm">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Review</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Communication and content can enter review flow.</p>
                </div>

                <div class="rounded-[28px] bg-[#f5f7ff] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-neutral-900">Moderate</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">Admin actions, logs, support, and suspension controls apply when needed.</p>
                </div>
            </div>
        </section>

        {{-- Admin & Support --}}
        <section class="mt-12 grid gap-6 xl:grid-cols-2">
            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <h3 class="text-2xl font-semibold text-neutral-900">Admin safety tools</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    Safety is supported by the admin panel, not left to chance.
                    Admin areas can manage users, suspend accounts, monitor support issues, review letters, and handle moderation reports.
                </p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-[#fff7fc] p-4 text-sm text-neutral-700">User suspension and reactivation</div>
                    <div class="rounded-2xl bg-[#f7efff] p-4 text-sm text-neutral-700">Moderation reports and logs</div>
                    <div class="rounded-2xl bg-neutral-50 p-4 text-sm text-neutral-700">Parent-child account linking visibility</div>
                    <div class="rounded-2xl bg-[#fffaf2] p-4 text-sm text-neutral-700">Support ticket review and response</div>
                </div>
            </div>

            <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                <h3 class="text-2xl font-semibold text-neutral-900">Support and issue handling</h3>
                <p class="mt-3 text-sm leading-8 text-neutral-500">
                    When something goes wrong, users should not be left without a path forward.
                    The support system gives users a clear way to contact the team, receive updates, and track the resolution process.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl bg-[#fff7fc] px-4 py-3 text-sm text-neutral-700">Users can submit support tickets from the support page</div>
                    <div class="rounded-2xl bg-[#f7efff] px-4 py-3 text-sm text-neutral-700">Ticket confirmation is sent by email</div>
                    <div class="rounded-2xl bg-neutral-50 px-4 py-3 text-sm text-neutral-700">Admins can reply and update status</div>
                    <div class="rounded-2xl bg-[#fffaf2] px-4 py-3 text-sm text-neutral-700">Users receive email updates when ticket status changes</div>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="mt-12">
            <div class="mb-8 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Common Questions
                </span>
                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Safety questions users may ask
                </h2>
            </div>

            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Why is parent approval needed for child accounts?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Parent approval ensures that a child account is created with family awareness and that the right adult is connected to the child pathway from the beginning.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Why do adults need email verification?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Email verification helps confirm account ownership before the adult can sign in and use the platform.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">How is communication kept under control?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Communication can pass through preview, review, moderation, and status tracking before fulfillment, depending on the role and workflow involved.
                    </p>
                </div>

                <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">What happens when a user reports a problem?</h3>
                    <p class="mt-3 text-sm leading-8 text-neutral-500">
                        Admin teams can review support tickets, moderation reports, flagged content, and suspended accounts through the platform’s management tools.
                    </p>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="mt-12 rounded-[36px] border border-black/6 bg-[linear-gradient(135deg,#ffffff,#faf6fd)] px-6 py-10 shadow-sm sm:px-10 sm:py-12">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                    Safe by Design
                </span>

                <h2 class="mt-4 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Understand the protection behind the experience
                </h2>

                <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                    Letter Getters combines registration rules, approvals, moderation, and structured workflows so the platform remains easier to trust and easier to manage.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('pathways') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.18)]">
                        <i class="fa-solid fa-route text-sm"></i>
                        View Pathways
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