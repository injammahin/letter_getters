@extends('layouts.guest')

@section('content')
<section class="relative overflow-hidden bg-[#fcf8fd] py-14 sm:py-20">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.08)] blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.08)] blur-3xl"></div>

    <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-black/8 bg-white p-8 shadow-[0_24px_70px_rgba(17,17,17,0.06)] sm:p-10">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-[0_18px_32px_rgba(98,10,136,0.18)]">
                <i class="fa-solid fa-envelope-circle-check text-3xl"></i>
            </div>

            <div class="mt-6 text-center">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Email Verification Required
                </span>

                <h1 class="mt-5 text-3xl font-semibold text-neutral-900 sm:text-4xl">
                    Verify your adult account
                </h1>

                <p class="mx-auto mt-4 max-w-2xl text-sm leading-8 text-neutral-600 sm:text-base">
                    We sent a verification link to your email address. Please verify your email before signing in.
                </p>

                @if(session('verification_email'))
                    <div class="mt-5 rounded-2xl border border-black/8 bg-[#faf7fc] px-4 py-4 text-sm text-neutral-700">
                        Verification email sent to:
                        <span class="font-medium text-neutral-900">{{ session('verification_email') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <div class="mt-8 rounded-[28px] border border-black/8 bg-white p-6">
                <h2 class="text-lg font-semibold text-neutral-900">Resend verification email</h2>
                <p class="mt-2 text-sm leading-7 text-neutral-500">
                    Enter the adult account email to receive a fresh verification link.
                </p>

                <form method="POST" action="{{ route('verification.send') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Email address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', session('verification_email')) }}"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                            placeholder="Enter your adult account email"
                            required
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_16px_28px_rgba(98,10,136,0.18)]"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            Resend verification
                        </button>

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center rounded-full border border-black/10 px-6 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                        >
                            Back to sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection