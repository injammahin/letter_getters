@extends('layouts.child')

@section('title', 'Rewards')

@section('content')
    <div class="space-y-6">
        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-black/10 bg-black/5 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-black/70">
                        Rewards
                    </div>

                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">
                        Your coins and rewards
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        Earn coins by completing child-safe actions. These coins can be used later inside your system.
                    </p>
                </div>

                <div class="rounded-[28px] border border-[#f5d67b] bg-[#fff6d6] px-6 py-5 coin-pill">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-black/45">Current Balance</p>
                    <div class="mt-3 flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-[#d49b00] shadow-sm">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <p class="text-3xl font-black text-black">{{ auth()->user()->coin_balance }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <h2 class="text-2xl font-bold text-black">Recent rewards</h2>

            <div class="mt-5 space-y-3">
                @forelse(auth()->user()->coinTransactions()->take(10)->get() as $transaction)
                    <div class="flex items-center justify-between rounded-2xl border border-black/8 px-4 py-4">
                        <div>
                            <p class="text-sm font-semibold text-black">{{ $transaction->label }}</p>
                            <p class="mt-1 text-xs text-black/45">{{ $transaction->created_at?->format('d M Y, h:i A') }}</p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-black text-[#CB148B]">+{{ $transaction->amount }}</p>
                            <p class="mt-1 text-xs text-black/45">Balance: {{ $transaction->balance_after }}</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-black/10 px-4 py-5 text-sm text-black/55">
                        No rewards yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection