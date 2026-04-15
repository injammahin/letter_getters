@extends('layouts.admin')

@section('title', 'Store Categories')
@section('page_title', 'Store Categories')
@section('page_subtitle', 'Create and manage product categories')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-neutral-900">Create Category</h2>

                <form action="{{ route('admin.store.categories.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Name</label>
                        <input type="text" name="name" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            value="{{ old('name') }}">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-800">Sort Order</label>
                        <input type="number" name="sort_order"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            value="{{ old('sort_order', 0) }}">
                    </div>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span class="text-sm text-neutral-700">Active</span>
                    </label>

                    <button type="submit"
                        class="w-full rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">
                        Create Category
                    </button>
                </form>
            </div>

            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-neutral-900">All Categories</h2>

                <div class="mt-5 space-y-4">
                    @foreach($categories as $category)
                        <div class="rounded-2xl border border-black/8 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900">{{ $category->name }}</h3>
                                    <p class="mt-1 text-sm text-neutral-500">{{ $category->description }}</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.store.categories.edit', $category) }}"
                                        class="rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">Edit</a>

                                    <form action="{{ route('admin.store.categories.destroy', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($categories->hasPages())
                    <div class="mt-6">{{ $categories->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection