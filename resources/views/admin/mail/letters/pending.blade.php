@extends('layouts.admin')

@section('title', 'Letter Review')
@section('page_title', 'Letter Review')
@section('page_subtitle', 'Scan, flag, and approve submitted child letters')

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

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Submitted</p>
                <p class="mt-3 text-3xl font-semibold text-neutral-900">{{ $stats['submitted'] }}</p>
            </div>

            <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Unscanned</p>
                <p class="mt-3 text-3xl font-semibold text-neutral-900">{{ $stats['unscanned'] }}</p>
            </div>

            <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-green-700">Clean Scanned</p>
                <p class="mt-3 text-3xl font-semibold text-neutral-900">{{ $stats['clean_scanned'] }}</p>
            </div>

            <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-red-600">Flagged</p>
                <p class="mt-3 text-3xl font-semibold text-neutral-900">{{ $stats['flagged'] }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <form action="{{ route('admin.child-letters.scan-all') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 hover:border-[#620A88] hover:text-[#620A88]">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    Scan All Submitted Letters
                </button>
            </form>

            <form action="{{ route('admin.child-letters.approve-all-clean') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    Approve All Clean Letters
                </button>
            </form>
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
                                Scan Result</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Submitted</th>
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
                                            {{ \Illuminate\Support\Str::limit($letter->body, 80) }}</p>
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-neutral-700">{{ $letter->sender?->name }}</td>
                                <td class="px-6 py-5 text-sm text-neutral-700">{{ $letter->receiver?->name }}</td>

                                <td class="px-6 py-5">
                                    @if($letter->scan_status === 'not_scanned')
                                        <span
                                            class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700">
                                            Not Scanned
                                        </span>
                                    @elseif($letter->scan_flagged)
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600">
                                            Red Flag
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                            Clean
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 text-sm text-neutral-500">
                                    {{ $letter->created_at?->format('d M Y, h:i A') }}</td>

                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.child-letters.scan', $letter) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:border-[#620A88] hover:text-[#620A88]">
                                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                                Scan
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.child-letters.show', $letter) }}"
                                            class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:border-[#CB148B] hover:text-[#CB148B]">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                            Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center text-sm text-neutral-500">
                                    No submitted letters are waiting for review.
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