@extends('layouts.admin')

@section('title', 'Review Letter')
@section('page_title', 'Review Letter')
@section('page_subtitle', 'Scan the letter, review flagged words, and approve or reject')

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

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                                {{ ucfirst($childLetter->status) }}
                            </span>

                            @if($childLetter->scan_status === 'not_scanned')
                                <span
                                    class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700">
                                    Not Scanned
                                </span>
                            @elseif($childLetter->scan_flagged)
                                <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600">
                                    Red Flag
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                    Clean Scan
                                </span>
                            @endif
                        </div>

                        <h2 class="mt-4 text-2xl font-semibold text-neutral-900">{{ $childLetter->subject }}</h2>
                        <p class="mt-2 text-sm text-neutral-500">
                            Submitted on {{ $childLetter->created_at?->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    <a href="{{ route('admin.child-letters.pending') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back
                    </a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-[#fff7fc] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Sender</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ $childLetter->sender?->name }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f7efff] p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">Receiver</p>
                        <p class="mt-2 text-sm font-medium text-neutral-900">{{ $childLetter->receiver?->name }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-[24px] border border-black/6 bg-white p-5">
                    <h3 class="text-lg font-semibold text-neutral-900">Letter Body</h3>
                    <div class="mt-4 whitespace-pre-line text-sm leading-8 text-neutral-700">
                        {{ $childLetter->body }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-neutral-900">Scan Result</h3>
                            <p class="mt-2 text-sm leading-7 text-neutral-500">
                                Scan first before approving. Flagged letters should be reviewed carefully.
                            </p>
                        </div>

                        <form action="{{ route('admin.child-letters.scan', $childLetter) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:border-[#620A88] hover:text-[#620A88]">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                Scan Letter
                            </button>
                        </form>
                    </div>

                    <div
                        class="mt-5 rounded-2xl {{ $childLetter->scan_flagged ? 'border border-red-200 bg-red-50' : 'border border-black/6 bg-neutral-50' }} p-4">
                        <p
                            class="text-sm font-medium {{ $childLetter->scan_flagged ? 'text-red-700' : 'text-neutral-700' }}">
                            {{ $childLetter->scan_summary ?: 'This letter has not been scanned yet.' }}
                        </p>
                    </div>

                    <div class="mt-5">
                        <h4 class="text-sm font-semibold text-neutral-900">Matched suspicious words</h4>

                        <div class="mt-3 space-y-2">
                            @forelse(($childLetter->scan_hits ?? []) as $hit)
                                <div
                                    class="flex items-center justify-between rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
                                    <span class="text-sm font-medium text-red-700">{{ $hit['term'] }}</span>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-red-600">
                                        {{ $hit['count'] }} hit{{ $hit['count'] > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-black/8 p-3 text-sm text-neutral-500">
                                    No suspicious words found yet, or the letter has not been scanned.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Approve Clean Letter</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        This works only if the scan is complete and no red flag is found.
                    </p>

                    <form action="{{ route('admin.child-letters.approve', $childLetter) }}" method="POST"
                        class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Admin Notes</label>
                            <textarea name="admin_notes" rows="4"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#620A88]"
                                placeholder="Optional note"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-6 py-3 text-sm font-medium text-white">
                            Approve Letter
                        </button>
                    </form>
                </div>

                @if($childLetter->scan_flagged)
                    <div class="rounded-[30px] border border-red-200 bg-red-50 p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-red-700">Force Approve Flagged Letter</h3>
                        <p class="mt-2 text-sm leading-7 text-red-600">
                            Red flag found. Use this only if you reviewed the content manually and still want to allow it.
                        </p>

                        <form action="{{ route('admin.child-letters.force-approve', $childLetter) }}" method="POST"
                            class="mt-5 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="mb-2 block text-sm font-medium text-red-700">Admin Notes</label>
                                <textarea name="admin_notes" rows="4"
                                    class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm outline-none"
                                    placeholder="Reason for force approval"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full rounded-full border border-red-300 bg-white px-6 py-3 text-sm font-medium text-red-700">
                                Force Approve Letter
                            </button>
                        </form>
                    </div>
                @endif

                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Reject Letter</h3>
                    <p class="mt-2 text-sm leading-7 text-neutral-500">
                        Rejecting keeps the letter hidden from the receiving child.
                    </p>

                    <form action="{{ route('admin.child-letters.reject', $childLetter) }}" method="POST"
                        class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-800">Admin Notes</label>
                            <textarea name="admin_notes" rows="4"
                                class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#CB148B]"
                                placeholder="Optional reason for rejection"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full rounded-full border border-red-200 bg-red-50 px-6 py-3 text-sm font-medium text-red-600">
                            Reject Letter
                        </button>
                    </form>
                </div>

                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-neutral-900">Review Meta</h3>

                    <div class="mt-5 space-y-3 text-sm text-neutral-600">
                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                            <span>Status</span>
                            <span class="font-medium text-neutral-900">{{ ucfirst($childLetter->status) }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                            <span>Scan Status</span>
                            <span
                                class="font-medium text-neutral-900">{{ ucfirst(str_replace('_', ' ', $childLetter->scan_status)) }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                            <span>Scanned At</span>
                            <span
                                class="font-medium text-neutral-900">{{ $childLetter->scanned_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                            <span>Reviewed At</span>
                            <span
                                class="font-medium text-neutral-900">{{ $childLetter->reviewed_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-neutral-50 px-4 py-3">
                            <span>Approved At</span>
                            <span
                                class="font-medium text-neutral-900">{{ $childLetter->approved_at?->format('d M Y, h:i A') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection