@extends('layouts.admin')

@section('title', 'Pending Match Approvals')
@section('page_title', 'Pending Match Approvals')
@section('page_subtitle', 'Approve or reject child match requests')

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

        <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
            @forelse($pendingRequests as $matchRequest)
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-semibold text-[#CB148B]">
                            Pending Approval
                        </span>

                        <span class="text-xs text-black/45">
                            {{ $matchRequest->created_at?->format('d M Y') }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-[24px] bg-[#fff7fc] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Requester</p>
                            <h3 class="mt-2 text-lg font-semibold text-black">{{ $matchRequest->requester?->name }}</h3>
                            <p class="mt-1 text-sm text-black/55">
                                {{ $matchRequest->requester?->profile?->age_or_grade ?? 'N/A' }}
                                •
                                {{ $matchRequest->requester?->profile?->city ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-[#f7efff] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#620A88]">Target Child</p>
                            <h3 class="mt-2 text-lg font-semibold text-black">{{ $matchRequest->target?->name }}</h3>
                            <p class="mt-1 text-sm text-black/55">
                                {{ $matchRequest->target?->profile?->age_or_grade ?? 'N/A' }}
                                •
                                {{ $matchRequest->target?->profile?->city ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-black/6 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Shared Interests</p>
                                <p class="mt-2 text-2xl font-semibold text-black">{{ $matchRequest->shared_interest_count }}</p>
                            </div>

                            <div class="rounded-2xl border border-black/6 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Score</p>
                                <p class="mt-2 text-2xl font-semibold text-black">{{ number_format($matchRequest->score, 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3">
                        <form action="{{ route('admin.matches.approve', $matchRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                Approve Match
                            </button>
                        </form>

                        <form action="{{ route('admin.matches.reject', $matchRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                                Reject Request
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm lg:col-span-2 xl:col-span-3">
                    <p class="text-sm text-black/55">No pending child match requests right now.</p>
                </div>
            @endforelse
        </div>

        @if($pendingRequests->hasPages())
            <div class="rounded-[30px] border border-black/8 bg-white px-6 py-5 shadow-sm">
                {{ $pendingRequests->links() }}
            </div>
        @endif
    </div>
@endsection