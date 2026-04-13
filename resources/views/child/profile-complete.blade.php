@extends('layouts.child')

@section('title', 'Complete Profile')

@section('content')
@php
    $profile = $user->profile;
    $selectedInterestIds = old('interests', $user->interests->pluck('id')->map(fn($id) => (string) $id)->toArray());
    $previewAvatars = $avatars->take(6);
@endphp

<section
    class="space-y-6"
    x-data="{
        avatarMode: @js(old('avatar_mode', $profile?->avatar_type === 'upload' ? 'upload' : 'library')),
        displayName: @js(old('display_name', $profile?->display_name ?? $user->name ?? '')),
        ageOrGrade: @js(old('age_or_grade', $profile?->age_or_grade ?? '')),
        city: @js(old('city', $profile?->city ?? '')),
        state: @js(old('state', $profile?->state ?? '')),
        shortBio: @js(old('short_bio', $profile?->short_bio ?? '')),
        favoriteColor: @js(old('favorite_color', $profile?->favorite_color ?? '')),
        selectedAvatarId: @js((string) old('avatar_library_id', $profile?->avatar_library_id ?? '')),
        modalAvatarId: @js((string) old('avatar_library_id', $profile?->avatar_library_id ?? '')),
        selectedInterests: @js($selectedInterestIds),
        hasUploadedAvatar: false,
        showAvatarModal: false,

        hasAvatar() {
            return (this.avatarMode === 'library' && this.selectedAvatarId !== '') ||
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
        },

        choosePreviewAvatar(id) {
            this.selectedAvatarId = String(id);
            this.modalAvatarId = String(id);
            this.avatarMode = 'library';
            this.hasUploadedAvatar = false;
        },

        openAvatarModal() {
            this.modalAvatarId = this.selectedAvatarId;
            this.showAvatarModal = true;
        },

        applyAvatarSelection() {
            this.selectedAvatarId = this.modalAvatarId;
            this.avatarMode = 'library';
            this.hasUploadedAvatar = false;
            this.showAvatarModal = false;
        }
    }"
>
    <div class="child-card child-soft rounded-[28px] p-6 sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <div class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-3.5 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Complete profile
                </div>

                <h1 class="mt-4 text-[28px] leading-tight font-semibold text-neutral-900 sm:text-[34px]">
                    Finish your child profile
                </h1>

                <p class="mt-3 max-w-xl text-sm leading-7 text-neutral-600">
                    Choose an avatar, add interests, and complete the child profile.
                </p>
            </div>

            <div class="w-full max-w-xs rounded-[24px] border border-black/6 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-neutral-500">Progress</p>
                        <p class="mt-2 text-2xl font-semibold text-neutral-900">
                            <span x-text="progressPercent()"></span>%
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm">
                        <i class="fa-solid fa-wand-magic-sparkles text-base"></i>
                    </div>
                </div>

                <div class="mt-4 h-2.5 w-full overflow-hidden rounded-full bg-[#f2ebf5]">
                    <div
                        class="h-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] transition-all duration-300"
                        :style="`width: ${progressPercent()}%`"
                    ></div>
                </div>

                <p class="mt-3 text-xs text-neutral-500">
                    <span class="font-medium text-neutral-800" x-text="completedCount()"></span> of 3 sections completed
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('child.profile.store') }}" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        @csrf

        <input type="hidden" name="avatar_mode" x-model="avatarMode">
        <input type="hidden" name="avatar_library_id" :value="avatarMode === 'library' ? selectedAvatarId : ''">

        <div class="space-y-6">
            <div class="child-card rounded-[28px] p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-900">Basic information</h2>
                        <p class="mt-1 text-sm text-neutral-500">These details help complete the child profile.</p>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em]"
                        :class="hasBasicInfo() ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-500'"
                    >
                        <span x-text="hasBasicInfo() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Display name</label>
                        <input
                            type="text"
                            name="display_name"
                            x-model="displayName"
                            value="{{ old('display_name', $profile?->display_name ?? $user->name) }}"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                        >
                        @error('display_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Age / Grade</label>
                        <input
                            type="text"
                            name="age_or_grade"
                            x-model="ageOrGrade"
                            value="{{ old('age_or_grade', $profile?->age_or_grade) }}"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                        >
                        @error('age_or_grade')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Favorite color</label>
                        <select
                            name="favorite_color"
                            x-model="favoriteColor"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
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
                        <label class="mb-2 block text-sm font-medium text-neutral-800">City</label>
                        <input
                            type="text"
                            name="city"
                            x-model="city"
                            value="{{ old('city', $profile?->city) }}"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                        >
                        @error('city')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">State</label>
                        <input
                            type="text"
                            name="state"
                            x-model="state"
                            value="{{ old('state', $profile?->state) }}"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                        >
                        @error('state')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-800">About me</label>
                        <textarea
                            name="short_bio"
                            rows="4"
                            x-model="shortBio"
                            class="w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]"
                        >{{ old('short_bio', $profile?->short_bio) }}</textarea>
                        @error('short_bio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="child-card rounded-[28px] p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-900">Interests</h2>
                        <p class="mt-1 text-sm text-neutral-500">Select interests for child-safe matching.</p>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em]"
                        :class="hasInterests() ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-500'"
                    >
                        <span x-text="hasInterests() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($interests as $interest)
                        <label class="flex items-center gap-3 rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-700 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                            <input
                                type="checkbox"
                                name="interests[]"
                                value="{{ $interest->id }}"
                                x-model="selectedInterests"
                                class="rounded border-neutral-300 text-[#CB148B] focus:ring-[#CB148B]"
                            >
                            <span class="font-medium">{{ $interest->name }}</span>
                        </label>
                    @endforeach
                </div>

                <p class="mt-3 text-xs text-neutral-500">
                    Selected:
                    <span class="font-medium text-neutral-800" x-text="selectedInterests.length"></span>
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
            <div class="child-card rounded-[28px] p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-900">Avatar</h2>
                        <p class="mt-1 text-sm text-neutral-500">Choose from admin uploaded avatars or upload your own.</p>
                    </div>

                    <span
                        class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em]"
                        :class="hasAvatar() ? 'bg-green-50 text-green-700' : 'bg-neutral-100 text-neutral-500'"
                    >
                        <span x-text="hasAvatar() ? 'Done' : 'Pending'"></span>
                    </span>
                </div>

                <div class="flex gap-3 rounded-full bg-[#f5eef8] p-1">
                    <button
                        type="button"
                        @click="avatarMode = 'library'; hasUploadedAvatar = false"
                        :class="avatarMode === 'library' ? 'bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm' : 'text-neutral-600'"
                        class="flex-1 rounded-full px-4 py-2.5 text-sm font-medium transition"
                    >
                        Avatar Library
                    </button>

                    <button
                        type="button"
                        @click="avatarMode = 'upload'; selectedAvatarId = ''"
                        :class="avatarMode === 'upload' ? 'bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm' : 'text-neutral-600'"
                        class="flex-1 rounded-full px-4 py-2.5 text-sm font-medium transition"
                    >
                        Upload Image
                    </button>
                </div>

                <div x-show="avatarMode === 'library'" x-cloak class="mt-5">
                    @if($avatars->count())
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach($previewAvatars as $avatar)
                                <button
                                    type="button"
                                    @click="choosePreviewAvatar('{{ $avatar->id }}')"
                                    class="rounded-[22px] border bg-white p-4 text-center transition"
                                    :class="selectedAvatarId === '{{ $avatar->id }}'
                                        ? 'border-[#CB148B] bg-[#fff7fc] ring-2 ring-[rgba(203,20,139,0.12)]'
                                        : 'border-neutral-200 hover:border-[#CB148B]/60 hover:bg-[#fffafc]'"
                                >
                                    <img
                                        src="{{ asset('storage/'.$avatar->image_path) }}"
                                        alt="Avatar"
                                        class="mx-auto h-20 w-20 rounded-3xl object-cover shadow-sm"
                                    >
                                </button>
                            @endforeach
                        </div>

                        @if($avatars->count() > 6)
                            <div class="mt-5 text-center">
                                <button
                                    type="button"
                                    @click="openAvatarModal()"
                                    class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                                >
                                    <i class="fa-solid fa-grid-2"></i>
                                    See more
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="rounded-2xl border border-dashed border-neutral-200 bg-neutral-50 px-4 py-6 text-center text-sm text-neutral-500">
                            No avatars uploaded by admin yet.
                        </div>
                    @endif
                </div>

                <div x-show="avatarMode === 'upload'" x-cloak class="mt-5">
                    <label class="mb-2 block text-sm font-medium text-neutral-800">Upload avatar image</label>
                    <input
                        type="file"
                        name="avatar_upload"
                        @change="hasUploadedAvatar = $event.target.files.length > 0"
                        class="block w-full rounded-2xl border border-neutral-200 bg-white px-4 py-3 text-sm"
                    >
                    <p class="mt-2 text-xs text-neutral-500">JPG, PNG, or WEBP. Max 2MB.</p>
                </div>

                @error('avatar_library_id')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('avatar_upload')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="child-card rounded-[28px] p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-900">Profile checklist</h2>
                        <p class="mt-1 text-sm text-neutral-500">Complete all sections before saving.</p>
                    </div>

                    <span class="rounded-full bg-neutral-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-600">
                        <span x-text="completedCount()"></span>/3
                    </span>
                </div>

                <div class="mt-5 space-y-3">
                    <div
                        class="flex items-center justify-between rounded-2xl border p-4 text-sm transition"
                        :class="hasAvatar() ? 'border-green-100 bg-green-50 text-green-700' : 'border-transparent bg-[#fff7fc] text-neutral-700'"
                    >
                        <span class="font-medium">Choose an avatar</span>
                        <i class="fa-solid fa-circle-check" x-show="hasAvatar()" x-cloak></i>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-2xl border p-4 text-sm transition"
                        :class="hasInterests() ? 'border-green-100 bg-green-50 text-green-700' : 'border-transparent bg-[#f7efff] text-neutral-700'"
                    >
                        <span class="font-medium">Add your interests</span>
                        <i class="fa-solid fa-circle-check" x-show="hasInterests()" x-cloak></i>
                    </div>

                    <div
                        class="flex items-center justify-between rounded-2xl border p-4 text-sm transition"
                        :class="hasBasicInfo() ? 'border-green-100 bg-green-50 text-green-700' : 'border-transparent bg-[#eaf8ff] text-neutral-700'"
                    >
                        <span class="font-medium">Fill in your basic details</span>
                        <i class="fa-solid fa-circle-check" x-show="hasBasicInfo()" x-cloak></i>
                    </div>
                </div>

                <button
                    type="submit"
                    class="mt-6 w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:shadow-[0_16px_28px_rgba(98,10,136,0.18)]"
                >
                    Save profile
                </button>
            </div>
        </div>
    </form>

    <div
        x-show="showAvatarModal"
        x-cloak
        class="fixed inset-0 z-[80] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="showAvatarModal = false"
            class="relative w-full max-w-7xl rounded-[30px] bg-white shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="flex items-center justify-between border-b border-black/6 px-6 py-5 sm:px-8">
                <div>
                    <h3 class="text-xl font-semibold text-neutral-900">Choose an avatar</h3>
                    <p class="mt-1 text-sm text-neutral-500">Pick any avatar from the full library.</p>
                </div>

                <button
                    type="button"
                    @click="showAvatarModal = false"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-black/10 text-neutral-500 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="max-h-[75vh] overflow-y-auto px-6 py-6 sm:px-8">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                    @foreach($avatars as $avatar)
                        <button
                            type="button"
                            @click="modalAvatarId = '{{ $avatar->id }}'"
                            class="rounded-[24px] border bg-white p-4 transition"
                            :class="modalAvatarId === '{{ $avatar->id }}'
                                ? 'border-[#CB148B] bg-[#fff7fc] ring-2 ring-[rgba(203,20,139,0.12)]'
                                : 'border-neutral-200 hover:border-[#CB148B]/60 hover:bg-[#fffafc]'"
                        >
                            <img
                                src="{{ asset('storage/'.$avatar->image_path) }}"
                                alt="Avatar"
                                class="mx-auto h-24 w-24 rounded-3xl object-cover shadow-sm"
                            >
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-black/6 px-6 py-4 sm:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-neutral-500">
                        <span class="font-medium text-neutral-800" x-show="modalAvatarId">Avatar selected</span>
                        <span x-show="!modalAvatarId">Choose one avatar from the list</span>
                    </p>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="showAvatarModal = false"
                            class="rounded-full border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                        >
                            Close
                        </button>

                        <button
                            type="button"
                            @click="applyAvatarSelection()"
                            :disabled="!modalAvatarId"
                            class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-2.5 text-sm font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Use selected avatar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection