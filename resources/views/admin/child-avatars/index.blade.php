@extends('layouts.admin')

@section('title', 'Child Avatars')
@section('page_title', 'Child Avatars')
@section('page_subtitle', 'Upload, edit, and manage child avatars from one page')

@section('content')
<div
    x-data="{
        createModal: false,
        editModal: false,
        deleteModal: false,
        selectedAvatar: null,
        updateBaseUrl: '{{ url('/admin/child-avatars') }}',

        openEdit(avatar) {
            this.selectedAvatar = avatar;
            this.editModal = true;
            this.deleteModal = false;
        },

        openDelete(avatar) {
            this.selectedAvatar = avatar;
            this.deleteModal = true;
            this.editModal = false;
        },

        closeAll() {
            this.createModal = false;
            this.editModal = false;
            this.deleteModal = false;
            this.selectedAvatar = null;
        }
    }"
    class="space-y-6"
>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-neutral-900">Avatar Library</h2>
            <p class="mt-1 text-sm text-neutral-500">
                Upload and manage child avatars in one place.
            </p>
        </div>

        <button
            type="button"
            @click="createModal = true"
            class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:-translate-y-0.5"
        >
            <i class="fa-solid fa-plus"></i>
            Add Avatar
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Please check the form and try again.
        </div>
    @endif

    <div class="rounded-[30px] border border-black/8 bg-white p-5 shadow-sm">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            <button
                type="button"
                @click="createModal = true"
                class="flex aspect-square items-center justify-center rounded-[24px] border border-dashed border-black/15 bg-[#fcf8fd] text-neutral-500 transition hover:border-[#CB148B] hover:bg-[#fff7fc] hover:text-[#CB148B]"
            >
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm">
                        <i class="fa-solid fa-plus text-base"></i>
                    </div>
                    <p class="mt-3 text-xs font-medium">Add Avatar</p>
                </div>
            </button>

            @foreach($avatars as $avatar)
                <div class="group relative aspect-square overflow-hidden rounded-[24px] border border-black/8 bg-neutral-50 shadow-sm">
                    <img
                        src="{{ asset('storage/'.$avatar->image_path) }}"
                        alt="Avatar"
                        class="h-full w-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent opacity-0 transition duration-200 group-hover:opacity-100"></div>

                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-center gap-2 p-3 opacity-0 transition duration-200 group-hover:opacity-100">
                        <button
                            type="button"
                            @click='openEdit(@json([
                                "id" => $avatar->id,
                                "image_url" => asset("storage/".$avatar->image_path),
                            ]))'
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-neutral-800 shadow-sm transition hover:text-[#CB148B]"
                            title="Edit Avatar"
                        >
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </button>

                        <button
                            type="button"
                            @click='openDelete(@json([
                                "id" => $avatar->id,
                                "image_url" => asset("storage/".$avatar->image_path),
                            ]))'
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-red-600 shadow-sm transition hover:bg-red-50"
                            title="Delete Avatar"
                        >
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($avatars->isEmpty())
            <div class="mt-6 rounded-2xl border border-dashed border-black/10 bg-[#fcf8fd] px-4 py-8 text-center text-sm text-neutral-500">
                No avatars uploaded yet.
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <div
        x-show="createModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="closeAll()"
            class="w-full max-w-lg rounded-[30px] bg-white p-6 shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-neutral-900">Add Avatar</h3>
                    <p class="mt-1 text-sm text-neutral-500">Upload a new child avatar image.</p>
                </div>

                <button type="button" @click="closeAll()" class="flex h-10 w-10 items-center justify-center rounded-full border border-black/10 text-neutral-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.child-avatars.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-800">Avatar Image</label>
                    <input
                        type="file"
                        name="image"
                        class="block w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm"
                        required
                    >
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="closeAll()"
                        class="rounded-full border border-black/10 px-5 py-2.5 text-sm font-medium text-neutral-700"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-2.5 text-sm font-medium text-white"
                    >
                        Upload Avatar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div
        x-show="editModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="closeAll()"
            class="w-full max-w-lg rounded-[30px] bg-white p-6 shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-neutral-900">Edit Avatar</h3>
                    <p class="mt-1 text-sm text-neutral-500">Replace the current avatar image.</p>
                </div>

                <button type="button" @click="closeAll()" class="flex h-10 w-10 items-center justify-center rounded-full border border-black/10 text-neutral-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mt-5" x-show="selectedAvatar">
                <div class="rounded-[24px] border border-black/8 bg-neutral-50 p-4">
                    <img
                        :src="selectedAvatar ? selectedAvatar.image_url : ''"
                        alt="Current Avatar"
                        class="mx-auto h-40 w-40 rounded-[24px] object-cover shadow-sm"
                    >
                </div>
            </div>

            <form
                x-bind:action="selectedAvatar ? `${updateBaseUrl}/${selectedAvatar.id}` : '#'"
                method="POST"
                enctype="multipart/form-data"
                class="mt-5 space-y-5"
            >
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-800">Replace Image</label>
                    <input
                        type="file"
                        name="image"
                        class="block w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm"
                    >
                    <p class="mt-2 text-xs text-neutral-500">Leave empty if you do not want to change it.</p>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button
                        type="button"
                        @click="closeAll()"
                        class="rounded-full border border-black/10 px-5 py-2.5 text-sm font-medium text-neutral-700"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-2.5 text-sm font-medium text-white"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div
        x-show="deleteModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="closeAll()"
            class="w-full max-w-md rounded-[30px] bg-white p-6 text-center shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <i class="fa-solid fa-trash text-lg"></i>
            </div>

            <h3 class="mt-4 text-xl font-semibold text-neutral-900">Delete Avatar</h3>
            <p class="mt-2 text-sm leading-7 text-neutral-500">
                Are you sure you want to delete this avatar?
            </p>

            <div class="mt-5" x-show="selectedAvatar">
                <img
                    :src="selectedAvatar ? selectedAvatar.image_url : ''"
                    alt="Avatar"
                    class="mx-auto h-32 w-32 rounded-[24px] object-cover shadow-sm"
                >
            </div>

            <form
                x-bind:action="selectedAvatar ? `${updateBaseUrl}/${selectedAvatar.id}` : '#'"
                method="POST"
                class="mt-6 flex items-center justify-center gap-3"
            >
                @csrf
                @method('DELETE')

                <button
                    type="button"
                    @click="closeAll()"
                    class="rounded-full border border-black/10 px-5 py-2.5 text-sm font-medium text-neutral-700"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-medium text-white"
                >
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection