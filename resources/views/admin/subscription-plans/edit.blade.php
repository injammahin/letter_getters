@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
            <div
                class="inline-flex items-center gap-2 rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                <span>💎</span>
                Edit Plan
            </div>

            <h1 class="mt-4 text-2xl font-black text-black">
                Edit {{ $subscriptionPlan->name }}
            </h1>

            <p class="mt-2 text-sm leading-6 text-black/55">
                Update the price, benefits, and visibility of this subscription plan.
            </p>
        </section>

        @include('admin.subscription-plans._form', [
            'subscriptionPlan' => $subscriptionPlan,
            'action' => route('admin.subscription-plans.update', $subscriptionPlan),
            'method' => 'PUT',
            'buttonText' => 'Update Plan',
        ])
        </div>
@endsection