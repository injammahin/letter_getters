@extends('layouts.child')

@section('title', 'Plans')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <section class="relative overflow-hidden rounded-[30px] border border-black/5 bg-white p-6 shadow-sm">
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[#fff0f9]"></div>
            <div class="absolute -bottom-12 left-12 h-24 w-24 rounded-full bg-[#f7efff]"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                        <span>💎</span>
                        Premium Plans
                    </div>

                    <h1 class="mt-3 text-2xl font-black tracking-tight text-black sm:text-3xl">
                        Choose your premium plan
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-black/55">
                        Unlock premium benefits, extra coins, more habitant items, and special rewards.
                    </p>
                </div>

                @if($currentSubscription)
                    <div class="rounded-[24px] border border-[#CB148B]/15 bg-[#fff7fc] px-5 py-4 shadow-sm">
                        <p class="text-[11px] font-black uppercase tracking-[0.16em] text-[#CB148B]">
                            Current Active Plan
                        </p>
                        <p class="mt-2 text-lg font-black text-black">
                            {{ $currentSubscription->plan?->name ?? 'Premium Plan' }}
                        </p>
                        <p class="mt-1 text-xs text-black/45">
                            @if($currentSubscription->ends_at)
                                Active until {{ $currentSubscription->ends_at->format('d M Y') }}
                            @else
                                Premium is active
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($plans as $plan)
                @php
                    $features = is_array($plan->features) ? $plan->features : [];
                    $isCurrentPlan = $currentSubscription && $currentSubscription->subscription_plan_id == $plan->id;
                @endphp

                <article
                    class="group relative overflow-hidden rounded-[30px] border border-black/6 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl {{ $plan->is_featured ? 'ring-2 ring-[#ffd7ef] border-[#CB148B]/20' : '' }}">
                    @if($plan->is_featured)
                        <div
                            class="absolute right-4 top-4 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.12em] text-white">
                            Popular
                        </div>
                    @endif

                    <div
                        class="absolute -right-12 -top-12 h-28 w-28 rounded-full {{ $plan->is_featured ? 'bg-[#fff0f9]' : 'bg-neutral-50' }}">
                    </div>

                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-[20px] {{ $plan->is_featured ? 'bg-[#fff0f9]' : 'bg-[#f7efff]' }} text-2xl">
                                💎
                            </div>

                            @if($plan->badge)
                                <span
                                    class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-black uppercase tracking-[0.12em] text-amber-700">
                                    {{ $plan->badge }}
                                </span>
                            @endif
                        </div>

                        <h2 class="mt-4 text-xl font-black text-black">
                            {{ $plan->name }}
                        </h2>

                        <p class="mt-2 min-h-[44px] text-sm leading-6 text-black/55">
                            {{ $plan->short_description ?: 'Premium plan for a better and more magical experience.' }}
                        </p>

                        <div class="mt-4 rounded-[24px] bg-neutral-50 p-4">
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black tracking-tight text-black">
                                    {{ $plan->formatted_price }}
                                </p>

                                @if((float) $plan->price > 0 && !in_array($plan->billing_interval, ['lifetime', 'one_time']))
                                    <p class="pb-1 text-sm font-bold text-black/45">
                                        / {{ strtolower($plan->billing_label) }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white px-3 py-1 text-[11px] font-black text-black/55">
                                    {{ $plan->billing_label }}
                                </span>

                                @if((int) $plan->trial_days > 0)
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-[11px] font-black text-green-700">
                                        {{ $plan->trial_days }} day trial
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-black/40">
                                Plan includes
                            </p>

                            <div class="mt-3 space-y-2.5">
                                @foreach(array_slice($features, 0, 4) as $feature)
                                    <div class="flex items-start gap-3 rounded-2xl border border-black/6 bg-white px-3 py-3">
                                        <div
                                            class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-700">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </div>

                                        <p class="text-sm font-semibold leading-6 text-black/65">
                                            {{ $feature }}
                                        </p>
                                    </div>
                                @endforeach

                                @if(count($features) === 0)
                                    <div class="rounded-2xl border border-dashed border-black/10 p-4 text-sm text-black/45">
                                        Plan features will be added soon.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5">
                            @if($isCurrentPlan)
                                <button type="button" disabled
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-green-100 px-5 py-3.5 text-sm font-black text-green-700">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Current Plan
                                </button>
                            @elseif(!$plan->stripe_price_id)
                                <button type="button" disabled
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-neutral-200 px-5 py-3.5 text-sm font-black text-neutral-500">
                                    <i class="fa-solid fa-lock"></i>
                                    Coming Soon
                                </button>
                            @else
                                <form method="POST" action="{{ route('child.plans.checkout', $plan) }}">
                                    @csrf

                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-fuchsia-900/10 transition hover:-translate-y-0.5">
                                        <i class="fa-solid fa-gem text-xs"></i>
                                        Choose Plan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div
                    class="col-span-full rounded-[30px] border border-dashed border-black/10 bg-white p-10 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#fff0f9] text-3xl">
                        💎
                    </div>

                    <h3 class="mt-4 text-lg font-black text-black">
                        No active plans available
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-black/50">
                        Please check again later.
                    </p>
                </div>
            @endforelse
        </section>
    </div>
@endsection