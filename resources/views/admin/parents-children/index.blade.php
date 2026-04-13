@extends('layouts.admin')

@section('title', 'Parent Child Links')
@section('page_title', 'Parent Child Links')
@section('page_subtitle', 'View and manage linked parent and child accounts')

@section('content')
<div
    x-data="{
        deleteModal: false,
        deleteAction: '',
        parentName: '',
        childName: '',

        openDeleteModal(action, parentName, childName) {
            this.deleteAction = action;
            this.parentName = parentName;
            this.childName = childName;
            this.deleteModal = true;
        },

        closeDeleteModal() {
            this.deleteModal = false;
            this.deleteAction = '';
            this.parentName = '';
            this.childName = '';
        }
    }"
    class="space-y-6"
>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-neutral-900">Parent Child Links</h2>
            <p class="mt-1 text-sm text-neutral-500">
                Review linked parent and child accounts and remove links when necessary.
            </p>
        </div>

        <div class="rounded-full border border-black/8 bg-white px-4 py-2 text-sm text-neutral-600 shadow-sm">
            Total Links:
            <span class="font-medium text-neutral-900">{{ $totalLinks }}</span>
        </div>
    </div>

    <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.parents-children.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by parent name, child name, username, or email"
                    class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-neutral-900 outline-none transition focus:border-[#CB148B] focus:ring-4 focus:ring-[rgba(203,20,139,0.08)]"
                >
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white"
                >
                    Search
                </button>

                <a
                    href="{{ route('admin.parents-children.index') }}"
                    class="rounded-full border border-black/10 px-5 py-3 text-sm font-medium text-neutral-700"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/6">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Parent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Child</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Link Info</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/6">
                    @forelse($links as $index => $link)
                        <tr class="transition hover:bg-neutral-50/70">
                            <td class="px-6 py-5 text-sm text-neutral-500">
                                {{ $links->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#620A88,#8b5cf6)] text-sm font-medium text-white shadow-sm">
                                        {{ strtoupper(substr($link->parent->name ?? 'P', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">
                                            {{ $link->parent->name ?? 'N/A' }}
                                        </p>

                                        @if($link->parent?->username)
                                            <p class="mt-1 text-xs text-neutral-500">
                                                {{ '@' . $link->parent->username }}
                                            </p>
                                        @endif

                                        <p class="mt-1 text-xs text-neutral-500">
                                            {{ $link->parent->email ?? 'N/A' }}
                                        </p>

                                        <div class="mt-2">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                                {{ ($link->parent->account_status ?? '') === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                                {{ ucfirst(str_replace('_', ' ', $link->parent->account_status ?? 'unknown')) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#ec4899)] text-sm font-medium text-white shadow-sm">
                                        {{ strtoupper(substr($link->child->name ?? 'C', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">
                                            {{ $link->child->name ?? 'N/A' }}
                                        </p>

                                        @if($link->child?->username)
                                            <p class="mt-1 text-xs text-neutral-500">
                                                {{ '@' . $link->child->username }}
                                            </p>
                                        @endif

                                        <p class="mt-1 text-xs text-neutral-500">
                                            {{ $link->child->email ?? 'N/A' }}
                                        </p>

                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-pink-50 px-3 py-1 text-xs font-medium text-pink-600">
                                                {{ ucfirst($link->child->role ?? 'child') }}
                                            </span>

                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                                {{ ($link->child->account_status ?? '') === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                                {{ ucfirst(str_replace('_', ' ', $link->child->account_status ?? 'unknown')) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="space-y-2 text-sm text-neutral-500">
                                    <p>
                                        <span class="font-medium text-neutral-800">Linked:</span>
                                        {{ $link->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-neutral-800">Child Location:</span>
                                        {{ $link->child->profile->city ?? 'N/A' }}{{ ($link->child->profile && $link->child->profile->state) ? ', '.$link->child->profile->state : '' }}
                                    </p>

                                    <p>
                                        <span class="font-medium text-neutral-800">Parent Location:</span>
                                        {{ $link->parent->profile->city ?? 'N/A' }}{{ ($link->parent->profile && $link->parent->profile->state) ? ', '.$link->parent->profile->state : '' }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-right">
                                <button
                                    type="button"
                                    @click="openDeleteModal('{{ route('admin.parents-children.destroy', $link) }}', '{{ addslashes($link->parent->name ?? 'Parent') }}', '{{ addslashes($link->child->name ?? 'Child') }}')"
                                    class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100"
                                >
                                    <i class="fa-solid fa-link-slash text-xs"></i>
                                    Remove Link
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center text-sm text-neutral-500">
                                No parent-child links found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($links->hasPages())
            <div class="flex flex-col gap-4 border-t border-black/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-neutral-500">
                    Showing
                    <span class="font-medium text-neutral-900">{{ $links->firstItem() }}</span>
                    to
                    <span class="font-medium text-neutral-900">{{ $links->lastItem() }}</span>
                    of
                    <span class="font-medium text-neutral-900">{{ $links->total() }}</span>
                    links
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-full border border-black/8 bg-neutral-50 px-4 py-2 text-sm text-neutral-600">
                        Page
                        <span class="font-medium text-neutral-900">{{ $links->currentPage() }}</span>
                        of
                        <span class="font-medium text-neutral-900">{{ $links->lastPage() }}</span>
                    </div>

                    {{ $links->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>

    <div
        x-show="deleteModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="closeDeleteModal()"
            class="w-full max-w-md rounded-[30px] bg-white p-6 text-center shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <i class="fa-solid fa-link-slash text-lg"></i>
            </div>

            <h3 class="mt-4 text-xl font-semibold text-neutral-900">Remove Parent Child Link</h3>

            <p class="mt-2 text-sm leading-7 text-neutral-500">
                Are you sure you want to remove the link between
                <span class="font-medium text-neutral-900" x-text="parentName"></span>
                and
                <span class="font-medium text-neutral-900" x-text="childName"></span>?
            </p>

            <form :action="deleteAction" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')

                <button
                    type="button"
                    @click="closeDeleteModal()"
                    class="rounded-full border border-black/10 px-5 py-2.5 text-sm font-medium text-neutral-700"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-medium text-white"
                >
                    Confirm Remove
                </button>
            </form>
        </div>
    </div>
</div>
@endsection