@extends('layouts.admin')

@section('title', 'Create Interest')
@section('page_title', 'Create Interest')
@section('page_subtitle', 'Add a new interest for registration and matching')

@section('content')
    <div class="max-w-3xl">
        <div class="rounded-[28px] border border-black/8 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.interests.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-black">Interest Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]"
                           placeholder="Example: Sports">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-black">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#620A88]">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-3 text-sm text-black/70">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-black/20 text-[#CB148B]" checked>
                    <span>Active</span>
                </label>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-bold text-white">
                        Save Interest
                    </button>

                    <a href="{{ route('admin.interests.index') }}"
                       class="rounded-full border border-black/10 px-6 py-3 text-sm font-bold text-black">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection