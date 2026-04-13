@extends('layouts.guest')

@section('title', 'Support')

@section('content')
<section class="bg-[#fcfbfe] py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-black/6 bg-[linear-gradient(135deg,#fff7fc,#f8f3ff)] p-8 shadow-sm sm:p-12">
            <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr] xl:items-center">
                <div>
                    <span class="inline-flex rounded-full border border-[rgba(98,10,136,0.12)] bg-[rgba(98,10,136,0.06)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#620A88]">
                        Support
                    </span>

                    <h1 class="mt-5 text-4xl font-semibold leading-tight text-neutral-900 sm:text-5xl">
                        Create a support ticket
                    </h1>

                    <p class="mt-4 max-w-3xl text-base leading-8 text-neutral-600">
                        Need help with login, account verification, subscriptions, letters, or technical issues?
                        Submit a support ticket and we will send a confirmation email with your ticket number and a short summary.
                    </p>
                </div>

                <div class="rounded-[28px] border border-black/6 bg-white p-6 shadow-sm">
                    <div class="grid gap-4 sm:grid-cols-3 xl:grid-cols-1">
                        <div class="rounded-2xl bg-[#fff7fc] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Response</p>
                            <p class="mt-2 text-2xl font-semibold text-neutral-900">24h</p>
                            <p class="mt-1 text-sm text-neutral-500">Standard support review window</p>
                        </div>

                        <div class="rounded-2xl bg-[#f7efff] p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Email Update</p>
                            <p class="mt-2 text-2xl font-semibold text-neutral-900">Yes</p>
                            <p class="mt-1 text-sm text-neutral-500">Ticket confirmation is sent instantly</p>
                        </div>

                        <div class="rounded-2xl bg-white border border-black/6 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</p>
                            <p class="mt-2 text-2xl font-semibold text-neutral-900">Open</p>
                            <p class="mt-1 text-sm text-neutral-500">All new tickets start as open</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-8 rounded-[24px] border border-green-200 bg-green-50 p-5">
                <h3 class="text-lg font-semibold text-green-800">Support ticket created successfully</h3>
                <p class="mt-2 text-sm leading-7 text-green-700">
                    {{ session('success') }}
                </p>

                @if(session('ticket_number'))
                    <div class="mt-3 inline-flex rounded-full border border-green-200 bg-white px-4 py-2 text-sm font-medium text-green-800">
                        Ticket Number: {{ session('ticket_number') }}
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-10 grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[32px] border border-black/6 bg-white p-8 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-neutral-900">Support form</h2>
                        <p class="mt-2 text-sm leading-7 text-neutral-500">
                            Fill in the details clearly so we can respond faster.
                        </p>
                    </div>

                    <div class="hidden h-14 w-14 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm sm:flex">
                        <i class="fa-solid fa-life-ring text-lg"></i>
                    </div>
                </div>

                <form action="{{ route('support.store') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Full Name</label>
                            <input
                                type="text"
                                name="full_name"
                                value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                                placeholder="Enter your full name"
                            >
                            @error('full_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email ?? '') }}"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                                placeholder="Enter your email"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Subject</label>
                            <input
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                                placeholder="Brief subject of your issue"
                            >
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Category</label>
                            <select
                                name="category"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                            >
                                <option value="">Select category</option>
                                <option value="account" {{ old('category') === 'account' ? 'selected' : '' }}>Account Issue</option>
                                <option value="verification" {{ old('category') === 'verification' ? 'selected' : '' }}>Email Verification</option>
                                <option value="parent_approval" {{ old('category') === 'parent_approval' ? 'selected' : '' }}>Parent Approval</option>
                                <option value="subscription" {{ old('category') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                                <option value="letters" {{ old('category') === 'letters' ? 'selected' : '' }}>Letters / Mail</option>
                                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="safety" {{ old('category') === 'safety' ? 'selected' : '' }}>Safety / Report</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Support</option>
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Priority</label>
                            <select
                                name="priority"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                            >
                                <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Message</label>
                            <textarea
                                name="message"
                                rows="7"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                                placeholder="Describe the issue clearly. Include what happened, when it happened, and what you need help with."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-neutral-500">
                            After submission, a confirmation email will be sent to your email address.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_16px_28px_rgba(98,10,136,0.18)]"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            Submit Support Ticket
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">What happens next</h3>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-[#fff7fc] p-4">
                            <p class="text-sm font-medium text-neutral-900">1. Ticket is created</p>
                            <p class="mt-1 text-sm leading-7 text-neutral-500">A unique ticket number is generated and saved.</p>
                        </div>

                        <div class="rounded-2xl bg-[#f7efff] p-4">
                            <p class="text-sm font-medium text-neutral-900">2. Confirmation email is sent</p>
                            <p class="mt-1 text-sm leading-7 text-neutral-500">You will receive a summary and your ticket number by email.</p>
                        </div>

                        <div class="rounded-2xl border border-black/6 p-4">
                            <p class="text-sm font-medium text-neutral-900">3. Support reviews the request</p>
                            <p class="mt-1 text-sm leading-7 text-neutral-500">The issue is checked and followed up by the support team.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[32px] border border-black/6 bg-white p-7 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Helpful guidance</h3>

                    <div class="mt-5 space-y-3 text-sm leading-7 text-neutral-500">
                        <p>Use a clear subject line so your ticket can be categorized correctly.</p>
                        <p>Add the exact issue details, not just “it is not working”.</p>
                        <p>Use the same email address you used for your account whenever possible.</p>
                        <p>Choose the right priority level so urgent items can be reviewed faster.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection