@extends('layouts.guest')

@section('content')
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-[32px] border border-black/8 bg-white p-8 shadow-[0_24px_70px_rgba(17,17,17,0.08)]">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                    Parent Approval
                </span>

                <h1 class="mt-5 text-3xl font-black text-black">
                    Review child registration
                </h1>

                <p class="mt-4 text-base leading-8 text-black/65">
                    This child account is waiting for your approval before it becomes active.
                </p>

                <div class="mt-8 space-y-4 rounded-[24px] border border-black/8 bg-[#fcf8fd] p-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-black/45">Child Name</p>
                        <p class="mt-1 text-base font-bold text-black">
                            {{ $approval->child->profile->display_name ?? $approval->child->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-black/45">Age / Grade</p>
                        <p class="mt-1 text-base font-semibold text-black">
                            {{ $approval->child->profile->age_or_grade ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-black/45">City / State</p>
                        <p class="mt-1 text-base font-semibold text-black">
                            {{ $approval->child->profile->city ?? 'N/A' }}, {{ $approval->child->profile->state ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-black/45">Parent Email</p>
                        <p class="mt-1 text-base font-semibold text-black">
                            {{ $approval->parent_email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-black/45">Token Status</p>
                        <p class="mt-1 text-base font-semibold text-[#CB148B]">
                            {{ ucfirst($approval->status) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[32px] border border-black/8 bg-white p-8 shadow-[0_24px_70px_rgba(17,17,17,0.08)]">
                <h2 class="text-2xl font-black text-black">Approve and create parent access</h2>
                <p class="mt-3 text-sm leading-7 text-black/60">
                    Set your parent account details below. After approval, the child account will become active.
                </p>

                <form method="POST" action="{{ route('parent.approval.store', $approval->token) }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Your Full Name</label>
                        <input
                            type="text"
                            name="parent_name"
                            value="{{ old('parent_name') }}"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                            placeholder="Enter your full name"
                            required
                        >
                        @error('parent_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Email</label>
                        <input
                            type="email"
                            value="{{ $approval->parent_email }}"
                            class="w-full rounded-2xl border border-black/10 bg-gray-50 px-4 py-3 text-sm text-black/70"
                            readonly
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Create Password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                            placeholder="Create password"
                            required
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                            placeholder="Confirm password"
                            required
                        >
                    </div>

                    <div class="rounded-2xl border border-black/8 bg-[#fcf8fd] p-4">
                        <label class="flex items-start gap-3 text-sm leading-7 text-black/70">
                            <input
                                type="checkbox"
                                name="confirm_approval"
                                value="1"
                                class="mt-1 rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                                {{ old('confirm_approval') ? 'checked' : '' }}
                            >
                            <span>
                                I confirm that I am the parent or guardian and approve this child registration.
                            </span>
                        </label>
                        @error('confirm_approval')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.22)]"
                    >
                        Approve Child Registration
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection