@extends('layouts.guest')

@section('content')
    <section id="home" class="relative overflow-hidden lg-soft-gradient">
        <div class="lg-glow left-[-80px] top-[80px] h-72 w-72 bg-[rgba(203,20,139,0.22)]"></div>
        <div class="lg-glow right-[-80px] top-[120px] h-72 w-72 bg-[rgba(98,10,136,0.22)]"></div>

        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-28">
            <div class="relative z-10 flex flex-col justify-center">
                <span class="lg-badge inline-flex w-fit items-center rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                    Safe. Joyful. Organized.
                </span>

                <h1 class="lg-section-title mt-6 max-w-2xl text-4xl font-black leading-tight text-black sm:text-5xl lg:text-6xl">
                    A modern pen pal platform built for
                    <span class="lg-gradient-text">children, parents, and adults</span>
                </h1>

                <p class="mt-6 max-w-2xl text-base leading-8 text-black/70 sm:text-lg">
                    Letter Getters combines child-safe pen pal matching, parent monitoring, managed mail
                    operations, subscription kits, and a delightful reward experience under one trusted brand.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}" class="lg-btn-primary rounded-full px-7 py-3.5 text-sm font-bold">
                        Start Your Journey
                    </a>

                    <a href="#how-it-works" class="lg-btn-outline rounded-full px-7 py-3.5 text-sm font-bold">
                        Explore the Platform
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="lg-card rounded-2xl p-5">
                        <div class="text-2xl font-black" style="color:#CB148B;">01</div>
                        <p class="mt-2 text-sm font-semibold text-black">Child-safe matching</p>
                    </div>
                    <div class="lg-card rounded-2xl p-5">
                        <div class="text-2xl font-black" style="color:#620A88;">02</div>
                        <p class="mt-2 text-sm font-semibold text-black">Parent oversight</p>
                    </div>
                    <div class="lg-card rounded-2xl p-5">
                        <div class="text-2xl font-black text-black">03</div>
                        <p class="mt-2 text-sm font-semibold text-black">Managed mail workflow</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <div class="lg-hero-card relative rounded-[32px] p-6 sm:p-8">
                    <div class="grid gap-5">
                        <div class="rounded-3xl p-6 text-white" style="background: linear-gradient(135deg, #CB148B, #620A88);">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/75">Platform Vision</p>
                                    <h3 class="mt-3 text-2xl font-black">Letters that feel safe, personal, and memorable</h3>
                                </div>
                                <div class="rounded-2xl bg-white/15 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                                    Brand Core
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="lg-card rounded-3xl p-6">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl text-white"
                                     style="background:#CB148B;">
                                    <i class="fa-solid fa-child-reaching text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-black">For Kids</h3>
                                <p class="mt-3 text-sm leading-7 text-black/65">
                                    Friendly onboarding, safe connections, rewards, avatars, and printable stationery.
                                </p>
                            </div>

                            <div class="lg-card rounded-3xl p-6">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl text-white"
                                     style="background:#620A88;">
                                    <i class="fa-solid fa-user-shield text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-black">For Parents</h3>
                                <p class="mt-3 text-sm leading-7 text-black/65">
                                    Approval controls, digital letter preview, privacy settings, and monitoring tools.
                                </p>
                            </div>

                            <div class="lg-card rounded-3xl p-6">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                                    <i class="fa-solid fa-envelope-open-text text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-black">For Operations</h3>
                                <p class="mt-3 text-sm leading-7 text-black/65">
                                    Intake, status tracking, scanning, archive management, and forwarding workflow.
                                </p>
                            </div>

                            <div class="rounded-3xl border border-black/10 bg-[linear-gradient(180deg,rgba(203,20,139,0.06),rgba(98,10,136,0.08))] p-6">
                                <h3 class="text-lg font-bold text-black">One Brand, Four Systems</h3>
                                <ul class="mt-4 space-y-3 text-sm text-black/70">
                                    <li>• Pen pal platform</li>
                                    <li>• Child safety and parent monitoring</li>
                                    <li>• Mail operations management</li>
                                    <li>• Subscription and shop system</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8 lg:pb-16">
            <div class="grid gap-4 rounded-[28px] border border-black/5 bg-white p-5 shadow-[0_20px_60px_rgba(17,17,17,0.05)] md:grid-cols-4 md:p-6">
                <div class="rounded-2xl bg-[#fff7fc] p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color:#CB148B;">Safe Matching</p>
                    <p class="mt-2 text-sm leading-7 text-black/65">Age-aware, pathway-based, interest-driven connections.</p>
                </div>
                <div class="rounded-2xl bg-[#f7efff] p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color:#620A88;">Parent Visibility</p>
                    <p class="mt-2 text-sm leading-7 text-black/65">Preview letters and manage child activity settings.</p>
                </div>
                <div class="rounded-2xl bg-black p-5 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/70">Central Mail Flow</p>
                    <p class="mt-2 text-sm leading-7 text-white/75">All child letters remain protected through a managed process.</p>
                </div>
                <div class="rounded-2xl p-5" style="background: linear-gradient(135deg, #CB148B, #620A88); color: white;">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/75">Digital Rewards</p>
                    <p class="mt-2 text-sm leading-7 text-white/80">Coins, avatars, and printable stationery keep it engaging.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="lg-badge inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                    How It Works
                </span>
                <h2 class="lg-section-title mt-5 text-3xl font-black text-black sm:text-4xl">
                    A simple journey from signup to safe letter exchange
                </h2>
                <p class="mt-5 text-base leading-8 text-black/65">
                    The platform separates child and adult workflows, keeps parents involved, and supports a structured
                    operational process behind every child letter.
                </p>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="lg-card rounded-3xl p-7">
                    <div class="text-sm font-black uppercase tracking-[0.18em]" style="color:#CB148B;">Step 01</div>
                    <h3 class="mt-4 text-xl font-bold text-black">Sign Up</h3>
                    <p class="mt-3 text-sm leading-7 text-black/65">
                        Users choose the correct path: child, parent-linked child account, or adult.
                    </p>
                </div>

                <div class="lg-card rounded-3xl p-7">
                    <div class="text-sm font-black uppercase tracking-[0.18em]" style="color:#620A88;">Step 02</div>
                    <h3 class="mt-4 text-xl font-bold text-black">Complete Profile</h3>
                    <p class="mt-3 text-sm leading-7 text-black/65">
                        Add interests, age range, city/state, short bio, and safe profile details.
                    </p>
                </div>

                <div class="lg-card rounded-3xl p-7">
                    <div class="text-sm font-black uppercase tracking-[0.18em]" style="color:#CB148B;">Step 03</div>
                    <h3 class="mt-4 text-xl font-bold text-black">Get Matched</h3>
                    <p class="mt-3 text-sm leading-7 text-black/65">
                        Matching is based on pathway, interests, age range, and optional preferences.
                    </p>
                </div>

                <div class="lg-card rounded-3xl p-7">
                    <div class="text-sm font-black uppercase tracking-[0.18em]" style="color:#620A88;">Step 04</div>
                    <h3 class="mt-4 text-xl font-bold text-black">Send Letters Safely</h3>
                    <p class="mt-3 text-sm leading-7 text-black/65">
                        Child letters flow through business-managed intake, preview, archive, and forwarding.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="pathways" class="bg-[#fcf8fd] py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="lg-badge inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                        User Pathways
                    </span>
                    <h2 class="lg-section-title mt-5 text-3xl font-black text-black sm:text-4xl">
                        Three clear experiences under one platform
                    </h2>
                </div>

                <p class="max-w-xl text-base leading-8 text-black/65">
                    Adults and children never mix in the same match flow. Parents remain connected to the child pathway.
                </p>
            </div>

            <div class="mt-14 grid gap-6 lg:grid-cols-3">
                <div class="lg-card rounded-[28px] p-8">
                    <div class="mb-5 inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white"
                         style="background:#CB148B;">
                        Child Pathway
                    </div>
                    <h3 class="text-2xl font-black text-black">Creative, safe, and guided</h3>
                    <ul class="mt-6 space-y-3 text-sm leading-7 text-black/70">
                        <li>• Child-safe onboarding</li>
                        <li>• Parent email approval</li>
                        <li>• Matched with children only</li>
                        <li>• Coins, avatars, and printables</li>
                        <li>• No home address exposure</li>
                    </ul>
                </div>

                <div class="lg-card rounded-[28px] p-8">
                    <div class="mb-5 inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white"
                         style="background:#620A88;">
                        Parent Pathway
                    </div>
                    <h3 class="text-2xl font-black text-black">Approval and visibility</h3>
                    <ul class="mt-6 space-y-3 text-sm leading-7 text-black/70">
                        <li>• Child account approval</li>
                        <li>• Activity monitoring</li>
                        <li>• Digital letter preview</li>
                        <li>• Letter archive access</li>
                        <li>• Privacy and control settings</li>
                    </ul>
                </div>

                <div class="rounded-[28px] p-8 text-white" style="background: linear-gradient(135deg, #CB148B, #620A88);">
                    <div class="mb-5 inline-flex rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white/90">
                        Adult Pathway
                    </div>
                    <h3 class="text-2xl font-black">Independent and separate</h3>
                    <ul class="mt-6 space-y-3 text-sm leading-7 text-white/85">
                        <li>• Direct adult registration</li>
                        <li>• Adult-only profile discovery</li>
                        <li>• Adult-to-adult matching</li>
                        <li>• Dedicated dashboard</li>
                        <li>• Separate letter journey</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="safety" class="bg-white py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <span class="lg-badge inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                    Safety First
                </span>
                <h2 class="lg-section-title mt-5 text-3xl font-black text-black sm:text-4xl">
                    Designed around trust, moderation, and child protection
                </h2>
                <p class="mt-6 text-base leading-8 text-black/65">
                    Safety is not an add-on. It is part of the core product structure, from signup to matching,
                    mail handling, and ongoing moderation.
                </p>

                <div class="mt-8 grid gap-4">
                    <div class="rounded-2xl border border-black/8 bg-[#fff7fc] p-5">
                        <h3 class="text-base font-bold text-black">Central address handling</h3>
                        <p class="mt-2 text-sm leading-7 text-black/65">
                            Child letters move through the business address instead of sharing private home details.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-black/8 bg-[#f7efff] p-5">
                        <h3 class="text-base font-bold text-black">Parent review flow</h3>
                        <p class="mt-2 text-sm leading-7 text-black/65">
                            Letters can be opened, scanned, uploaded, reviewed, and archived before forwarding.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-black/8 bg-black p-5 text-white">
                        <h3 class="text-base font-bold">Moderation and reporting</h3>
                        <p class="mt-2 text-sm leading-7 text-white/75">
                            Report, block, review queue, warnings, suspension, and staff-managed safety actions.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg-card rounded-[30px] p-8">
                <h3 class="text-2xl font-black text-black">What makes the platform different</h3>

                <div class="mt-8 space-y-5">
                    <div class="flex gap-4">
                        <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-white" style="background:#CB148B;">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-black">Separated child and adult systems</h4>
                            <p class="mt-2 text-sm leading-7 text-black/65">
                                Different onboarding, matching, and dashboard flows for each user group.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-white" style="background:#620A88;">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-black">Physical and digital workflow together</h4>
                            <p class="mt-2 text-sm leading-7 text-black/65">
                                The system supports real letters, digital previews, and status-based operations.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-black text-white">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-black">Engagement beyond letters</h4>
                            <p class="mt-2 text-sm leading-7 text-black/65">
                                Coins, avatar rewards, and printable stationery help keep the experience joyful.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 rounded-[24px] p-6 text-white" style="background: linear-gradient(135deg, #CB148B, #620A88);">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/75">Letter Getters Promise</p>
                    <p class="mt-3 text-lg font-bold leading-8">
                        Beautiful connections should also be structured, secure, and easy to manage.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="subscription" class="bg-[#f9f7fb] py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg-card rounded-[30px] p-8">
                    <span class="inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white"
                          style="background:#CB148B;">
                        Subscription Kits
                    </span>
                    <h3 class="mt-5 text-3xl font-black text-black">Monthly creativity delivered with every kit</h3>
                    <p class="mt-5 text-base leading-8 text-black/65">
                        Subscription plans can include stationery, stickers, themed materials, writing prompts,
                        and child-friendly extras that support the letter-writing experience.
                    </p>
                </div>

                <div class="rounded-[30px] p-8 text-white" style="background: linear-gradient(135deg, #620A88, #CB148B);">
                    <span class="inline-flex rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white/90">
                        Digital Rewards
                    </span>
                    <h3 class="mt-5 text-3xl font-black">Coins, avatars, and printable stationery</h3>
                    <p class="mt-5 text-base leading-8 text-white/85">
                        Children can earn coins, unlock avatar items, and access printable stationery for a more
                        playful and rewarding experience.
                    </p>
                </div>
            </div>

            <div class="mt-12 rounded-[30px] border border-black/5 bg-white p-8 text-center shadow-[0_18px_60px_rgba(17,17,17,0.05)]">
                <span class="lg-badge inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                    Ready to Build
                </span>
                <h2 class="mt-5 text-3xl font-black text-black sm:text-4xl">
                    A guest-facing homepage built around your product structure
                </h2>
                <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-black/65">
                    This layout presents the platform clearly to first-time visitors and matches the product vision:
                    safe pen pals, parent controls, managed operations, and a joyful branded experience.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="lg-btn-primary rounded-full px-7 py-3.5 text-sm font-bold">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}" class="lg-btn-outline rounded-full px-7 py-3.5 text-sm font-bold">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection