@extends('layouts.admin')

@section('title', 'Interests')
@section('page_title', 'Interests')
@section('page_subtitle', 'Manage interest categories shown on registration and matching')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-black">Interest List</h2>
                <p class="mt-1 text-sm text-black/55">Admin-added interests will appear on the register page.</p>
            </div>

            <a href="{{ route('admin.interests.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-bold text-white">
                <i class="fa-solid fa-plus"></i>
                Add Interest
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-[28px] border border-black/8 bg-white shadow-sm">
            <table class="min-w-full">
                <thead class="bg-black/[0.03]">
                    <tr>
                        <th class="px-5 py-4 text-left text-sm font-bold text-black">Name</th>
                        <th class="px-5 py-4 text-left text-sm font-bold text-black">Slug</th>
                        <th class="px-5 py-4 text-left text-sm font-bold text-black">Sort Order</th>
                        <th class="px-5 py-4 text-left text-sm font-bold text-black">Status</th>
                        <th class="px-5 py-4 text-right text-sm font-bold text-black">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interests as $interest)
                        <tr class="border-t border-black/6">
                            <td class="px-5 py-4 text-sm text-black">{{ $interest->name }}</td>
                            <td class="px-5 py-4 text-sm text-black/60">{{ $interest->slug }}</td>
                            <td class="px-5 py-4 text-sm text-black">{{ $interest->sort_order }}</td>
                            <td class="px-5 py-4 text-sm">
                                @if($interest->is_active)
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-600">Active</span>
                                @else
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.interests.edit', $interest) }}"
                                       class="rounded-full border border-black/10 px-4 py-2 text-sm font-semibold text-black hover:border-[#CB148B] hover:text-[#CB148B]">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.interests.destroy', $interest) }}" method="POST" onsubmit="return confirm('Delete this interest?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-full border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-black/55">
                                No interests found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection