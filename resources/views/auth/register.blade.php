@extends('layouts.guest')

@section('content')
<section class="relative overflow-hidden bg-[#fcf8fd] py-10 sm:py-14" x-data="{ role: '{{ old('role', 'child') }}' }">
    <div class="absolute left-0 top-0 h-72 w-72 rounded-full bg-[rgba(203,20,139,0.10)] blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-[rgba(98,10,136,0.10)] blur-3xl"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[0.95fr_1.2fr]">
            <div class="rounded-[30px] border border-black/6 bg-white p-6 shadow-[0_20px_60px_rgba(17,17,17,0.06)] sm:p-8">
                <span class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-[#CB148B]">
                    Letter Getters
                </span>

                <h1 class="mt-5 text-3xl font-black leading-tight text-black sm:text-4xl">
                    Create your account
                </h1>

                <p class="mt-4 text-sm leading-7 text-black/60">
                    Choose the correct path to get started.
                </p>

                <div class="mt-6 grid gap-4">
                    <div class="rounded-3xl border border-black/6 bg-[#fff7fc] p-5">
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                            <i class="fa-solid fa-child-reaching"></i>
                        </div>
                        <h3 class="text-lg font-black text-black">Child Account</h3>
                        <p class="mt-2 text-sm leading-7 text-black/60">
                            Parent email is required. Account stays pending until parent approval.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-black/6 bg-[#f7efff] p-5">
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h3 class="text-lg font-black text-black">Adult Account</h3>
                        <p class="mt-2 text-sm leading-7 text-black/60">
                            Direct signup with bio and interests for adult-only matching.
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl bg-black p-5 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/70">Note</p>
                    <p class="mt-2 text-sm leading-7 text-white/80">
                        Parents do not register here. They receive an approval email from the child registration flow.
                    </p>
                </div>
            </div>

            <div class="rounded-[30px] border border-black/6 bg-white p-5 shadow-[0_20px_60px_rgba(17,17,17,0.06)] sm:p-8">
                <div class="mb-6">
                    <div class="mx-auto flex w-full max-w-md rounded-full bg-[#f4eef7] p-1">
                        <button
                            type="button"
                            @click="role = 'child'"
                            :class="role === 'child'
                                ? 'bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow'
                                : 'text-black/65'"
                            class="flex-1 rounded-full px-5 py-3 text-sm font-bold transition"
                        >
                            Child
                        </button>

                        <button
                            type="button"
                            @click="role = 'adult'"
                            :class="role === 'adult'
                                ? 'bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow'
                                : 'text-black/65'"
                            class="flex-1 rounded-full px-5 py-3 text-sm font-bold transition"
                        >
                            Adult
                        </button>
                    </div>

                    <div class="mt-5 text-center">
                        <h2 class="text-2xl font-black text-black" x-text="role === 'child' ? 'Child Registration' : 'Adult Registration'"></h2>
                        <p class="mt-2 text-sm text-black/55" x-text="role === 'child' ? 'Safe signup with parent approval' : 'Quick signup for adult users'"></p>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="role" x-model="role">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-black">Display Name</label>
                            <input
                                type="text"
                                name="display_name"
                                value="{{ old('display_name') }}"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                                placeholder="Enter parent email"
                            >
                            <p class="mt-2 text-xs text-black/50">
                                Child account will remain pending until parent approval.
                            </p>
                            @error('parent_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="role === 'adult'" x-cloak class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-bold text-black">Short Bio</label>
                            <textarea
                                name="short_bio"
                                rows="3"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                placeholder="Write a short introduction"
                            >{{ old('short_bio') }}</textarea>
                            @error('short_bio')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="role === 'adult'" x-cloak class="sm:col-span-2">
                            <label class="mb-3 block text-sm font-bold text-black">Interests</label>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @forelse($interests as $interest)
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
                                @empty
                                    <div class="sm:col-span-2 rounded-2xl border border-dashed border-black/10 bg-[#fcf8fd] px-4 py-4 text-sm text-black/50">
                                        No interests available yet.
                                    </div>
                                @endforelse
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
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
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                                placeholder="Confirm password"
                                required
                            >
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-black/8 bg-[#faf7fc] p-4">
                            <label class="flex items-start gap-3 text-sm leading-7 text-black/70">
                                <input
                                    type="checkbox"
                                    name="agreement"
                                    value="1"
                                    class="mt-1 rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                                    {{ old('agreement') ? 'checked' : '' }}
                                >
                                <span>
                                    I agree to the platform rules and account terms.
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
</section>
@endsection