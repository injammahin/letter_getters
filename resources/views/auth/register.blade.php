@extends('layouts.guest')

@section('content')
<section class="relative overflow-hidden bg-white py-14 sm:py-20" x-data="{ role: '{{ old('role', 'child') }}' }">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.12)] blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-[rgba(98,10,136,0.12)] blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-start">
            <div>
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                    Join Letter Getters
                </span>

                <h1 class="mt-6 text-4xl font-black leading-tight text-black sm:text-5xl">
                    Create a
                    <span class="bg-[linear-gradient(135deg,#CB148B,#620A88)] bg-clip-text text-transparent">
                        child or adult
                    </span>
                    account
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-8 text-black/65">
                    Child and adult registration must follow separate flows. Child accounts require parent email approval,
                    while adult accounts can register directly and enter their interests right away. :contentReference[oaicite:2]{index=2}
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <button
                        type="button"
                        @click="role = 'child'"
                        :class="role === 'child' ? 'border-[#CB148B] bg-[#fff7fc] ring-4 ring-[rgba(203,20,139,0.10)]' : 'border-black/10 bg-white'"
                        class="rounded-[28px] border p-5 text-left transition"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                            <i class="fa-solid fa-child-reaching"></i>
                        </div>
                        <h3 class="text-xl font-black text-black">Join as Child</h3>
                        <p class="mt-2 text-sm leading-7 text-black/65">
                            Basic signup first. Parent approval is required before the child account becomes active.
                        </p>
                    </button>

                    <button
                        type="button"
                        @click="role = 'adult'"
                        :class="role === 'adult' ? 'border-[#620A88] bg-[#f7efff] ring-4 ring-[rgba(98,10,136,0.10)]' : 'border-black/10 bg-white'"
                        class="rounded-[28px] border p-5 text-left transition"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h3 class="text-xl font-black text-black">Join as Adult</h3>
                        <p class="mt-2 text-sm leading-7 text-black/65">
                            Direct signup with interests and profile details for adult-only discovery and matching.
                        </p>
                    </button>
                </div>

                <div class="mt-8 rounded-[28px] border border-black/8 bg-black p-6 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/70">Important</p>
                    <p class="mt-3 text-sm leading-7 text-white/80">
                        Parents do not register from this page. For child accounts, the parent receives an approval email
                        and enters the system from that approval flow. :contentReference[oaicite:3]{index=3}
                    </p>
                </div>
            </div>

            <div class="mx-auto w-full max-w-2xl">
                <div class="rounded-[32px] border border-black/8 bg-white p-6 shadow-[0_24px_70px_rgba(17,17,17,0.08)] sm:p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-black text-black">Registration Form</h2>
                        <p class="mt-2 text-sm text-black/55">
                            Friendly, safe onboarding based on the selected account type
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <input type="hidden" name="role" x-model="role">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="mb-2 block text-sm font-bold text-black">Display Name / Nickname</label>
                                <input
                                    type="text"
                                    name="display_name"
                                    value="{{ old('display_name') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                                    placeholder="Enter display name"
                                    required
                                >
                                @error('display_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black">Username</label>
                                <input
                                    type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                    placeholder="Choose username"
                                    required
                                >
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                                    placeholder="Enter email"
                                    required
                                >
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black" x-text="role === 'child' ? 'Age / Grade' : 'Age Range'"></label>
                                <input
                                    type="text"
                                    name="age_or_grade"
                                    value="{{ old('age_or_grade') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                    :placeholder="role === 'child' ? 'Example: Grade 5' : 'Example: 18-24'"
                                    required
                                >
                                @error('age_or_grade')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black">City</label>
                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                                    placeholder="Enter city"
                                    required
                                >
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black">State</label>
                                <input
                                    type="text"
                                    name="state"
                                    value="{{ old('state') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                    placeholder="Enter state"
                                    required
                                >
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role === 'child'" x-cloak class="sm:col-span-2">
                                <label class="mb-2 block text-sm font-bold text-black">Parent / Guardian Email</label>
                                <input
                                    type="email"
                                    name="parent_email"
                                    value="{{ old('parent_email') }}"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                                    placeholder="Enter parent email"
                                >
                                <p class="mt-2 text-xs leading-6 text-black/55">
                                    Parent approval is required before the child account becomes active. :contentReference[oaicite:4]{index=4}
                                </p>
                                @error('parent_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role === 'adult'" x-cloak class="sm:col-span-2">
                                <label class="mb-2 block text-sm font-bold text-black">Short Bio</label>
                                <textarea
                                    name="short_bio"
                                    rows="4"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                    placeholder="Write a short introduction"
                                >{{ old('short_bio') }}</textarea>
                                @error('short_bio')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="role === 'adult'" x-cloak class="sm:col-span-2">
                                <label class="mb-3 block text-sm font-bold text-black">Choose Interests</label>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach($interests as $interest)
                                        <label class="flex items-center gap-3 rounded-2xl border border-black/8 px-4 py-3 text-sm text-black/75 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                                            <input
                                                type="checkbox"
                                                name="interests[]"
                                                value="{{ $interest->id }}"
                                                class="rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                                                {{ in_array($interest->id, old('interests', [])) ? 'checked' : '' }}
                                            >
                                            <span>{{ $interest->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                @error('interests')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @error('interests.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-black">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
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
                                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                    placeholder="Confirm password"
                                    required
                                >
                            </div>

                            <div class="sm:col-span-2 rounded-2xl border border-black/8 bg-[#fcf8fd] p-4">
                                <label class="flex items-start gap-3 text-sm leading-7 text-black/70">
                                    <input
                                        type="checkbox"
                                        name="agreement"
                                        value="1"
                                        class="mt-1 rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                                        {{ old('agreement') ? 'checked' : '' }}
                                    >
                                    <span>
                                        I agree to the platform rules, safety expectations, and account terms.
                                        <span x-show="role === 'child'" x-cloak>
                                            This includes the child-safe signup flow and parent approval requirement.
                                        </span>
                                        <span x-show="role === 'adult'" x-cloak>
                                            This includes adult-only matching and moderated platform use.
                                        </span>
                                    </span>
                                </label>
                                @error('agreement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.22)]"
                            x-text="role === 'child' ? 'Create Child Account' : 'Create Adult Account'"
                        ></button>

                        <div class="text-center text-sm text-black/60">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-bold text-[#620A88] hover:text-[#CB148B]">
                                Sign in
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection