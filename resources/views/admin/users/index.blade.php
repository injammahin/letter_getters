@extends('layouts.admin')

@section('title', $pageTitle)
@section('page_title', $pageTitle)
@section('page_subtitle', $pageSubtitle)

@section('content')
<div
    x-data="{
        suspendModal: false,
        suspendUserName: '',
        suspendAction: '',

        openSuspendModal(name, action) {
            this.suspendUserName = name;
            this.suspendAction = action;
            this.suspendModal = true;
        },

        closeSuspendModal() {
            this.suspendModal = false;
            this.suspendUserName = '';
            this.suspendAction = '';
        }
    }"
    class="space-y-6"
>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-neutral-900">{{ $pageTitle }}</h2>
            <p class="mt-1 text-sm text-neutral-500">{{ $pageSubtitle }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.users.children') }}"
               class="rounded-full px-4 py-2 text-sm font-medium transition {{ $roleKey === 'child' ? 'bg-[#fff0f9] text-[#CB148B]' : 'border border-black/10 bg-white text-neutral-600 hover:border-[#CB148B] hover:text-[#CB148B]' }}">
                Children
            </a>

            <a href="{{ route('admin.users.parents') }}"
               class="rounded-full px-4 py-2 text-sm font-medium transition {{ $roleKey === 'parent' ? 'bg-[#f5efff] text-[#620A88]' : 'border border-black/10 bg-white text-neutral-600 hover:border-[#620A88] hover:text-[#620A88]' }}">
                Parents
            </a>

            <a href="{{ route('admin.users.adults') }}"
               class="rounded-full px-4 py-2 text-sm font-medium transition {{ $roleKey === 'adult' ? 'bg-[#eef7ff] text-sky-700' : 'border border-black/10 bg-white text-neutral-600 hover:border-sky-400 hover:text-sky-700' }}">
                Adults
            </a>

            <div class="rounded-full border border-black/8 bg-white px-4 py-2 text-sm text-neutral-600 shadow-sm">
                Total:
                <span class="font-medium text-neutral-900">{{ $users->total() }}</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/6">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/6">
                    @forelse($users as $index => $user)
                        <tr class="transition hover:bg-neutral-50/70">
                            <td class="px-6 py-5 text-sm text-neutral-500">
                                {{ $users->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-sm font-medium text-white shadow-sm">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">{{ $user->name }}</p>

                                      @if($user->username)
                                        <p class="mt-1 text-xs text-neutral-500">
                                            {{ $user->username }}
                                        </p>
                                      @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                    {{ $user->role === 'child' ? 'bg-pink-50 text-pink-600' : '' }}
                                    {{ $user->role === 'parent' ? 'bg-violet-50 text-violet-600' : '' }}
                                    {{ $user->role === 'adult' ? 'bg-sky-50 text-sky-700' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-sm text-neutral-700">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-5 text-sm text-neutral-500">
                                {{ $user->profile->city ?? 'N/A' }}{{ ($user->profile && $user->profile->state) ? ', '.$user->profile->state : '' }}
                            </td>

                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                    {{ ucfirst(str_replace('_', ' ', $user->account_status)) }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-right">
                                <button
                                    type="button"
                                    @click="openSuspendModal('{{ addslashes($user->name) }}', '{{ route('admin.users.suspend', $user) }}')"
                                    class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100"
                                >
                                    <i class="fa-solid fa-user-slash text-xs"></i>
                                    Suspend
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center text-sm text-neutral-500">
                                No active {{ strtolower($roleLabel) }} users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="flex flex-col gap-4 border-t border-black/6 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-neutral-500">
                    Showing
                    <span class="font-medium text-neutral-900">{{ $users->firstItem() }}</span>
                    to
                    <span class="font-medium text-neutral-900">{{ $users->lastItem() }}</span>
                    of
                    <span class="font-medium text-neutral-900">{{ $users->total() }}</span>
                    users
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-full border border-black/8 bg-neutral-50 px-4 py-2 text-sm text-neutral-600">
                        Page
                        <span class="font-medium text-neutral-900">{{ $users->currentPage() }}</span>
                        of
                        <span class="font-medium text-neutral-900">{{ $users->lastPage() }}</span>
                    </div>

                    {{ $users->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Suspend Confirmation Modal --}}
    <div
        x-show="suspendModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40 p-4"
    >
        <div
            @click.outside="closeSuspendModal()"
            class="w-full max-w-md rounded-[30px] bg-white p-6 text-center shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
        >
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <i class="fa-solid fa-user-slash text-lg"></i>
            </div>

            <h3 class="mt-4 text-xl font-semibold text-neutral-900">Suspend User</h3>

            <p class="mt-2 text-sm leading-7 text-neutral-500">
                Are you sure you want to suspend
                <span class="font-medium text-neutral-900" x-text="suspendUserName"></span>?
            </p>

            <form :action="suspendAction" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('PATCH')

                <button
                    type="button"
                    @click="closeSuspendModal()"
                    class="rounded-full border border-black/10 px-5 py-2.5 text-sm font-medium text-neutral-700"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-medium text-white"
                >
                    Confirm Suspend
                </button>
            </form>
        </div>
    </div>
</div>
@endsection