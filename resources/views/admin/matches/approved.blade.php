@extends('layouts.admin')

@section('title', 'Approved Matches')
@section('page_title', 'Approved Matches')
@section('page_subtitle', 'Approved child matches visible to both children')

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
            @forelse($approvedMatches as $match)
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Active Match
                        </span>

                        <span class="text-xs text-black/45">
                            {{ $match->approved_at?->format('d M Y') }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-[24px] bg-[#fff7fc] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#CB148B]">Child One</p>
                            <h3 class="mt-2 text-lg font-semibold text-black">{{ $match->userOne?->name }}</h3>
                            <p class="mt-1 text-sm text-black/55">
                                {{ $match->userOne?->profile?->age_or_grade ?? 'N/A' }}
                                •
                                {{ $match->userOne?->profile?->city ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-[24px] bg-[#f7efff] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#620A88]">Child Two</p>
                            <h3 class="mt-2 text-lg font-semibold text-black">{{ $match->userTwo?->name }}</h3>
                            <p class="mt-1 text-sm text-black/55">
                                {{ $match->userTwo?->profile?->age_or_grade ?? 'N/A' }}
                                •
                                {{ $match->userTwo?->profile?->city ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-black/6 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Approved By</p>
                            <p class="mt-2 text-sm font-semibold text-black">
                                {{ $match->approver?->name ?? 'Admin' }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.matches.remove', $match) }}" method="POST" class="mt-6">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600">
                            <i class="fa-solid fa-link-slash text-xs"></i>
                            Remove Match
                        </button>
                    </form>
                </div>
            @empty
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm lg:col-span-2 xl:col-span-3">
                    <p class="text-sm text-black/55">No approved child matches yet.</p>
                </div>
            @endforelse
        </div>

        @if($approvedMatches->hasPages())
            <div class="rounded-[30px] border border-black/8 bg-white px-6 py-5 shadow-sm">
                {{ $approvedMatches->links() }}
            </div>
        @endif
    </div>
@endsection