@extends('layouts.admin')

@section('title', 'Create Habitant Theme')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-3xl border border-black/10 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-black text-black">Create Habitant Theme</h1>
            <p class="mt-2 text-sm text-black/55">
                Example: Lion Theme, Unicorn Theme, Ocean Theme.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.habitants.store') }}" enctype="multipart/form-data"
            class="space-y-5 rounded-3xl border border-black/10 bg-white p-6 shadow-sm">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-bold text-black">Theme Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88]"
                    placeholder="Lion Theme" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-black">Description</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88]"
                    placeholder="Cute savanna habitat for lion character">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-black">Thumbnail Image</label>
                <input type="file" name="thumbnail_image" accept="image/*"
                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
                @error('thumbnail_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-black">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                        class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88]">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label
                        class="flex w-full items-center gap-3 rounded-2xl border border-black/10 px-4 py-3 text-sm font-semibold text-black/70">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Active Theme
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.habitants.index') }}"
                    class="rounded-full border border-black/10 px-5 py-3 text-sm font-bold text-black/60">
                    Cancel
                </a>

                <button type="submit" class="rounded-full bg-[#620A88] px-5 py-3 text-sm font-bold text-white">
                    Create Theme
                </button>
            </div>
        </form>
    </div>
@endsection