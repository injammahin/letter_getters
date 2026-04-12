@extends('layouts.guest')

@section('content')
    <section class="relative overflow-hidden bg-white py-16 sm:py-20">
        <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.12)] blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.12)] blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div class="max-w-xl">
                    <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.18)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Welcome Back
                    </span>

                    <h1 class="mt-6 text-4xl font-black leading-tight text-black sm:text-5xl">
                        Sign in to your
                        <span class="bg-[linear-gradient(135deg,#CB148B,#620A88)] bg-clip-text text-transparent">
                            Letter Getters
                        </span>
                        account
                    </h1>

                    <p class="mt-6 text-base leading-8 text-black/65">
                        One login page for superadmin, admin, child, adult, and parent users.
                        Use your email or username and password to continue.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-black/8 bg-[#fff7fc] p-5 shadow-[0_16px_40px_rgba(17,17,17,0.05)]">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <h3 class="text-lg font-bold text-black">Role-based access</h3>
                            <p class="mt-2 text-sm leading-7 text-black/65">
                                Users are redirected to the correct dashboard after login.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-black/8 bg-[#f7efff] p-5 shadow-[0_16px_40px_rgba(17,17,17,0.05)]">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <h3 class="text-lg font-bold text-black">Simple access</h3>
                            <p class="mt-2 text-sm leading-7 text-black/65">
                                Sign in with either email or username from the same form.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mx-auto w-full max-w-md">
                    <div class="rounded-[32px] border border-black/8 bg-white p-6 shadow-[0_20px_70px_rgba(17,17,17,0.08)] sm:p-8">
                        <div class="mb-6 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-[0_14px_30px_rgba(98,10,136,0.22)]">
                                <i class="fa-solid fa-paper-plane text-xl"></i>
                            </div>
                            <h2 class="mt-4 text-2xl font-black text-black">Login</h2>
                            <p class="mt-2 text-sm text-black/55">Enter your credentials to continue</p>
                        </div>
                        @if (session('error'))
                            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="login" class="mb-2 block text-sm font-bold text-black">
                                    Email or Username
                                </label>
                                <input
                                    id="login"
                                    name="login"
                                    type="text"
                                    value="{{ old('login') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Enter email or username"
                                    class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.12)]"
                                >
                                @error('login')
                                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-bold text-black">
                                    Password
                                </label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter password"
                                    class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.12)]"
                                >
                                @error('password')
                                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="inline-flex items-center gap-2 text-sm text-black/65">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                                    >
                                    <span>Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#620A88] hover:text-[#CB148B]">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3.5 text-sm font-bold text-white transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_16px_30px_rgba(98,10,136,0.22)]"
                            >
                                Sign In
                            </button>
                        </form>

                        <div class="mt-6 border-t border-black/6 pt-6 text-center">
                            <p class="text-sm text-black/60">
                                Don’t have an account?
                                <a href="{{ route('register') }}" class="font-bold text-[#CB148B] hover:text-[#620A88]">
                                    Create account
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection