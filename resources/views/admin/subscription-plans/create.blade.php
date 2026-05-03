@extends('layouts.admin')

@section('title', 'Create Subscription Plan')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
            <div
                class="inline-flex items-center gap-2 rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                <span>💎</span>
                New Plan
            </div>

            <h1 class="mt-4 text-2xl font-black text-black">Create Subscription Plan</h1>

            <p class="mt-2 text-sm leading-6 text-black/55">
                Add plan details, price, billing period, and included features.
            </p>
        </section>

        @include('admin.subscription-plans._form', [
            'subscriptionPlan' => $subscriptionPlan,
            'action' => route('admin.subscription-plans.store'),
            'method' => 'POST',
            'buttonText' => 'Create Plan',
        ])
        </div>
@endsection