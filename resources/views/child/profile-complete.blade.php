@extends('layouts.child')

@section('title', 'Complete Profile')

@section('content')
@php
    $profile = $user->profile;
    $selectedInterestIds = old('interests', $user->interests->pluck('id')->map(fn($id) => (string) $id)->toArray());
@endphp

<section
    class="space-y-6"
    x-data="{
        avatarMode: @js(old('avatar_mode', $profile?->avatar_type === 'upload' ? 'upload' : 'preset')),
        displayName: @js(old('display_name', $profile?->display_name ?? $user->name ?? '')),
        ageOrGrade: @js(old('age_or_grade', $profile?->age_or_grade ?? '')),
        city: @js(old('city', $profile?->city ?? '')),
        state: @js(old('state', $profile?->state ?? '')),
        shortBio: @js(old('short_bio', $profile?->short_bio ?? '')),
        favoriteColor: @js(old('favorite_color', $profile?->favorite_color ?? '')),
        selectedAvatarPreset: @js(old('avatar_preset', $profile?->avatar_type === 'preset' ? $profile?->avatar : '')),
        selectedInterests: @js($selectedInterestIds),
        hasUploadedAvatar: false,

        hasAvatar() {
            return (this.avatarMode === 'preset' && this.selectedAvatarPreset !== '') ||
                   (this.avatarMode === 'upload' && this.hasUploadedAvatar);
        },

        hasInterests() {
            return this.selectedInterests.length > 0;
        },

        hasBasicInfo() {
            return this.displayName.trim() !== '' &&
                   this.ageOrGrade.trim() !== '' &&
                   this.city.trim() !== '' &&
                   this.state.trim() !== '' &&
                   this.shortBio.trim() !== '';
        },

        completedCount() {
            let count = 0;
            if (this.hasAvatar()) count++;
            if (this.hasInterests()) count++;
            if (this.hasBasicInfo()) count++;
            return count;
        },

        progressPercent() {
            return Math.round((this.completedCount() / 3) * 100);
        }
    }"
>
    <div class="child-card child-soft rounded-[32px] p-6 sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                    Complete Profile
                </div>

                <h1 class="mt-4 text-3xl font-black text-black sm:text-4xl">
                    Make your profile awesome
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                    Pick an avatar, choose interests, and fill in your details so your child profile is ready.
                </p>
            </div>

            <div class="w-full max-w-xs rounded-[28px] border border-black/6 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">Progress</p>
                        <p class="mt-2 text-2xl font-black text-black">
                            <span x-text="progressPercent()"></span>%
                        </p>
                    </div>

                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow">
                        <i class="fa-solid fa-star text-2xl"></i>
                    </div>
                </div>

                <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-[#f3ebf7]">
                    <div
                        class="h-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] transition-all duration-300"
                        :style="`width: ${progressPercent()}%`"
                    ></div>
                </div>

                <p class="mt-3 text-xs text-black/50">
                    <span x-text="completedCount()"></span> of 3 completed
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('child.profile.store') }}" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        @csrf

        <div class="space-y-6">
            <div class="child-card rounded-[30px] p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-black">Basic Info</h2>
                    <span
                        class="rounded-full px-3 py-1 text-xs font-bold"
                        :class="hasBasicInfo() ? 'bg-green-50 text-green-600' : 'bg-[#f4eef7] text-black/50'"
                    >
                        <span x-text="hasBasicInfo() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-black">Display Name</label>
                        <input
                            type="text"
                            name="display_name"
                            x-model="displayName"
                            value="{{ old('display_name', $profile?->display_name ?? $user->name) }}"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                            placeholder="Enter your display name"
                        >
                        @error('display_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Age / Grade</label>
                        <input
                            type="text"
                            name="age_or_grade"
                            x-model="ageOrGrade"
                            value="{{ old('age_or_grade', $profile?->age_or_grade) }}"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                            placeholder="Example: Grade 5"
                        >
                        @error('age_or_grade')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">Favorite Color</label>
                        <select
                            name="favorite_color"
                            x-model="favoriteColor"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                        >
                            <option value="">Choose color</option>
                            @foreach(['Pink','Purple','Blue','Green','Yellow','Orange'] as $color)
                                <option value="{{ $color }}">{{ $color }}</option>
                            @endforeach
                        </select>
                        @error('favorite_color')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-black">City</label>
                        <input
                            type="text"
                            name="city"
                            x-model="city"
                            value="{{ old('city', $profile?->city) }}"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                            placeholder="Enter city"
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
                            x-model="state"
                            value="{{ old('state', $profile?->state) }}"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.10)]"
                            placeholder="Enter state"
                        >
                        @error('state')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-black">About Me</label>
                        <textarea
                            name="short_bio"
                            rows="4"
                            x-model="shortBio"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.10)]"
                            placeholder="Write a short friendly intro"
                        >{{ old('short_bio', $profile?->short_bio) }}</textarea>
                        @error('short_bio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="child-card rounded-[30px] p-6">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-black">Interests</h2>
                        <p class="mt-1 text-sm text-black/55">Choose interests for child-safe matching.</p>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-bold"
                        :class="hasInterests() ? 'bg-green-50 text-green-600' : 'bg-[#f4eef7] text-black/50'"
                    >
                        <span x-text="hasInterests() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($interests as $interest)
                        <label class="flex items-center gap-3 rounded-2xl border border-black/8 px-4 py-3 text-sm text-black/75 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                            <input
                                type="checkbox"
                                name="interests[]"
                                value="{{ $interest->id }}"
                                x-model="selectedInterests"
                                class="rounded border-black/20 text-[#CB148B] focus:ring-[#CB148B]"
                            >
                            <span>{{ $interest->name }}</span>
                        </label>
                    @endforeach
                </div>

                <p class="mt-3 text-xs text-black/50">
                    Selected:
                    <span class="font-bold text-black" x-text="selectedInterests.length"></span>
                </p>

                @error('interests')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('interests.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-6">
            <div class="child-card rounded-[30px] p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-black">Choose Avatar</h2>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-bold"
                        :class="hasAvatar() ? 'bg-green-50 text-green-600' : 'bg-[#f4eef7] text-black/50'"
                    >
                        <span x-text="hasAvatar() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="flex gap-3 rounded-full bg-[#f5eef8] p-1">
                    <button
                        type="button"
                        @click="avatarMode = 'preset'; hasUploadedAvatar = false"
                        :class="avatarMode === 'preset' ? 'child-gradient text-white shadow' : 'text-black/65'"
                        class="flex-1 rounded-full px-4 py-3 text-sm font-bold transition"
                    >
                        Preset Avatar
                    </button>

                    <button
                        type="button"
                        @click="avatarMode = 'upload'; selectedAvatarPreset = ''"
                        :class="avatarMode === 'upload' ? 'child-gradient text-white shadow' : 'text-black/65'"
                        class="flex-1 rounded-full px-4 py-3 text-sm font-bold transition"
                    >
                        Upload Image
                    </button>
                </div>

                <input type="hidden" name="avatar_mode" x-model="avatarMode">

                <div x-show="avatarMode === 'preset'" x-cloak class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach($presetAvatars as $avatar)
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="avatar_preset"
                                value="{{ $avatar['key'] }}"
                                x-model="selectedAvatarPreset"
                                class="peer hidden"
                            >
                            <div class="rounded-[24px] border border-black/8 p-4 text-center transition peer-checked:border-[#CB148B] peer-checked:bg-[#fff7fc] peer-checked:ring-4 peer-checked:ring-[rgba(203,20,139,0.10)]">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-3xl text-white shadow">
                                    {{ $avatar['emoji'] }}
                                </div>
                                <div class="mt-3 text-sm font-bold text-black">{{ $avatar['label'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div x-show="avatarMode === 'upload'" x-cloak class="mt-5">
                    <label class="mb-2 block text-sm font-bold text-black">Upload Avatar Image</label>
                    <input
                        type="file"
                        name="avatar_upload"
                        @change="hasUploadedAvatar = $event.target.files.length > 0"
                        class="block w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm"
                    >
                    <p class="mt-2 text-xs text-black/50">JPG, PNG, or WEBP. Max 2MB.</p>
                </div>

                @error('avatar_preset')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('avatar_upload')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="child-card rounded-[30px] p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-black">Profile Checklist</h2>
                    <span class="rounded-full bg-[#f4eef7] px-3 py-1 text-xs font-bold text-black/60">
                        <span x-text="completedCount()"></span>/3
                    </span>
                </div>

                <div class="mt-5 space-y-3">
                    <div
                        class="flex items-center justify-between rounded-2xl p-4 text-sm transition"
                        :class="hasAvatar() ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-[#fff7fc] text-black/70 border border-transparent'"
                    >
                        <span class="font-semibold">Choose an avatar</span>
                        <i class="fa-solid fa-circle-check" x-show="hasAvatar()" x-cloak></i>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-2xl p-4 text-sm transition"
                        :class="hasInterests() ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-[#f7efff] text-black/70 border border-transparent'"
                    >
                        <span class="font-semibold">Add your interests</span>
                        <i class="fa-solid fa-circle-check" x-show="hasInterests()" x-cloak></i>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-2xl p-4 text-sm transition"
                        :class="hasBasicInfo() ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-[#eaf8ff] text-black/70 border border-transparent'"
                    >
                        <span class="font-semibold">Fill in your basic details</span>
                        <i class="fa-solid fa-circle-check" x-show="hasBasicInfo()" x-cloak></i>
                    </div>
                </div>

                <button
                    type="submit"
                    class="mt-6 w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_rgba(98,10,136,0.22)]"
                >
                    Save Profile
                </button>
            </div>
        </div>
    </form>
</section>
@endsection