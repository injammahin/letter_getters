@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')
@section('page_subtitle', 'Update store category')

@section('content')
    <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.store.categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="mb-2 block text-sm font-medium text-neutral-800">Name</label>
                <input type="text" name="name" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                    value="{{ old('name', $category->name) }}">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-neutral-800">Description</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-neutral-800">Sort Order</label>
                <input type="number" name="sort_order" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                    value="{{ old('sort_order', $category->sort_order) }}">
            </div>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <span class="text-sm text-neutral-700">Active</span>
            </label>

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">Update
                    Category</button>
                <a href="{{ route('admin.store.categories.index') }}"
                    class="rounded-full border border-black/10 px-5 py-3 text-sm font-medium text-neutral-700">Back</a>
            </div>
        </form>
    </div>
@endsection