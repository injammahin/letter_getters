@extends('layouts.child')

@section('title', 'Rewards')

@section('content')
    <div class="child-card child-soft rounded-[32px] p-6 sm:p-8">
        <div
            class="inline-flex rounded-full border border-black/10 bg-black/5 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-black/70">
            Rewards
        </div>
        <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">Coins and rewards</h1>
        <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
            This page will show coin balance, earn history, spend history, and unlocked reward items.
        </p>
    </div>
@endsection