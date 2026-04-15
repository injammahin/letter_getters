@extends('layouts.admin')

@section('title', 'Rejected Letters')
@section('page_title', 'Rejected Letters')
@section('page_subtitle', 'All rejected child letters with admin notes and review details')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.child-letters.pending') }}"
                class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 hover:border-[#620A88] hover:text-[#620A88]">
                <i class="fa-solid fa-clock text-xs"></i>
                Pending Review
            </a>

            <a href="{{ route('admin.child-letters.approved') }}"
                class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 hover:border-[#CB148B] hover:text-[#CB148B]">
                <i class="fa-solid fa-circle-check text-xs"></i>
                Approved Letters
            </a>

            <div
                class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-3 text-sm font-medium text-red-600">
                <i class="fa-solid fa-circle-xmark text-xs"></i>
                Rejected Letters
            </div>
        </div>

        <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/6">
                    <thead class="bg-neutral-50/80">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Subject</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Sender</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Receiver</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Rejected By</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Rejected At</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Admin Note</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-black/6">
                        @forelse($letters as $letter)
                            <tr class="transition hover:bg-neutral-50/70">
                                <td class="px-6 py-5">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">{{ $letter->subject }}</p>
                                        <p class="mt-1 text-xs text-neutral-500">
                                            {{ \Illuminate\Support\Str::limit($letter->body, 90) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        @if($letter->sender?->profile?->avatar_type === 'upload' && $letter->sender?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $letter->sender->profile->avatar) }}" alt="Sender Avatar"
                                                class="h-10 w-10 rounded-2xl object-cover">
                                        @elseif($letter->sender?->profile?->avatar_type === 'library' && $letter->sender?->profile?->avatarLibrary?->image_path)
                                            <img src="{{ asset('storage/' . $letter->sender->profile->avatarLibrary->image_path) }}"
                                                alt="Sender Avatar" class="h-10 w-10 rounded-2xl object-cover">
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                                <i class="fa-solid fa-user text-xs"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-neutral-900">{{ $letter->sender?->name }}</p>
                                            <p class="text-xs text-neutral-500">{{ $letter->sender?->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        @if($letter->receiver?->profile?->avatar_type === 'upload' && $letter->receiver?->profile?->avatar)
                                            <img src="{{ asset('storage/' . $letter->receiver->profile->avatar) }}"
                                                alt="Receiver Avatar" class="h-10 w-10 rounded-2xl object-cover">
                                        @elseif($letter->receiver?->profile?->avatar_type === 'library' && $letter->receiver?->profile?->avatarLibrary?->image_path)
                                            <img src="{{ asset('storage/' . $letter->receiver->profile->avatarLibrary->image_path) }}"
                                                alt="Receiver Avatar" class="h-10 w-10 rounded-2xl object-cover">
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-2xl bg-neutral-900 text-white">
                                                <i class="fa-solid fa-user text-xs"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-neutral-900">{{ $letter->receiver?->name }}</p>
                                            <p class="text-xs text-neutral-500">{{ $letter->receiver?->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-neutral-700">
                                    {{ $letter->reviewer?->name ?? 'Admin' }}
                                </td>

                                <td class="px-6 py-5 text-sm text-neutral-500">
                                    {{ $letter->reviewed_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-5">
                                    <p class="text-sm text-red-600">
                                        {{ $letter->admin_notes ?: 'No note added' }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('admin.child-letters.show', $letter) }}"
                                        class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:border-[#CB148B] hover:text-[#CB148B]">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-sm text-neutral-500">
                                    No rejected letters found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($letters->hasPages())
                <div class="border-t border-black/6 px-6 py-5">
                    {{ $letters->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection