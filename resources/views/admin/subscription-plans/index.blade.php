@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-center">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-[#fff0f9] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                        <span>💎</span>
                        Premium Plans
                    </div>

                    <h1 class="mt-4 text-2xl font-black text-black">
                        Subscription Plans
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-black/55">
                        Create plans for premium users. Add price, billing type, and plan benefits.
                    </p>
                </div>

                <a href="{{ route('admin.subscription-plans.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-black text-white shadow-lg shadow-fuchsia-900/10">
                    <span>+</span>
                    Create Plan
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-[24px] bg-neutral-50 p-5">
                    <p class="text-xs font-black uppercase tracking-[0.14em] text-black/40">Total Plans</p>
                    <p class="mt-2 text-3xl font-black text-black">{{ $totalPlans }}</p>
                </div>

                <div class="rounded-[24px] bg-green-50 p-5">
                    <p class="text-xs font-black uppercase tracking-[0.14em] text-green-700">Active Plans</p>
                    <p class="mt-2 text-3xl font-black text-green-800">{{ $activePlans }}</p>
                </div>

                <div class="rounded-[24px] bg-[#fff0f9] p-5">
                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#CB148B]">Featured Plans</p>
                    <p class="mt-2 text-3xl font-black text-[#620A88]">{{ $featuredPlans }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-[30px] border border-black/10 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.subscription-plans.index') }}"
                class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by plan name, slug, or description"
                    class="min-h-[48px] flex-1 rounded-2xl border border-black/10 px-4 text-sm outline-none focus:border-[#CB148B]">

                <button type="submit" class="rounded-2xl bg-black px-5 py-3 text-sm font-black text-white">
                    Search
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('admin.subscription-plans.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-black/10 px-5 py-3 text-sm font-black text-black/60">
                        Clear
                    </a>
                @endif
            </form>
        </section>

        <section class="overflow-hidden rounded-[30px] border border-black/10 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-sm">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-[0.14em] text-black/45">
                        <tr>
                            <th class="px-5 py-4">Plan</th>
                            <th class="px-5 py-4">Price</th>
                            <th class="px-5 py-4">Billing</th>
                            <th class="px-5 py-4">Features</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Sort</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-black/5">
                        @forelse($plans as $plan)
                            <tr class="align-top">
                                <td class="px-5 py-5">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#fff0f9] text-xl">
                                            💎
                                        </div>

                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="font-black text-black">{{ $plan->name }}</p>

                                                @if($plan->is_featured)
                                                    <span
                                                        class="rounded-full bg-[#CB148B]/10 px-3 py-1 text-[11px] font-black text-[#CB148B]">
                                                        Featured
                                                    </span>
                                                @endif

                                                @if($plan->badge)
                                                    <span
                                                        class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-black text-amber-700">
                                                        {{ $plan->badge }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-1 text-xs text-black/40">{{ $plan->slug }}</p>

                                            @if($plan->short_description)
                                                <p class="mt-2 max-w-md text-sm leading-6 text-black/55">
                                                    {{ $plan->short_description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-black text-black">{{ $plan->formatted_price }}</p>
                                    @if($plan->trial_days > 0)
                                        <p class="mt-1 text-xs font-bold text-green-700">
                                            {{ $plan->trial_days }} day trial
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    <span class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-black text-black/60">
                                        {{ $plan->billing_label }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    @if(is_array($plan->features) && count($plan->features))
                                        <div class="space-y-1">
                                            @foreach(array_slice($plan->features, 0, 3) as $feature)
                                                <p class="text-xs font-semibold text-black/60">
                                                    ✅ {{ $feature }}
                                                </p>
                                            @endforeach

                                            @if(count($plan->features) > 3)
                                                <p class="text-xs font-black text-[#620A88]">
                                                    + {{ count($plan->features) - 3 }} more
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-black/35">No features added</span>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    @if($plan->is_active)
                                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-black text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-black text-red-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 font-bold text-black/50">
                                    {{ $plan->sort_order }}
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.subscription-plans.edit', $plan) }}"
                                            class="rounded-full border border-black/10 px-4 py-2 text-xs font-black text-black/60 hover:border-[#620A88] hover:text-[#620A88]">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST"
                                            onsubmit="return confirm('Delete this subscription plan?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-full border border-red-200 bg-red-50 px-4 py-2 text-xs font-black text-red-700 hover:bg-red-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#fff0f9] text-3xl">
                                        💎
                                    </div>

                                    <h3 class="mt-4 text-lg font-black text-black">No subscription plans yet</h3>

                                    <p class="mt-2 text-sm text-black/45">
                                        Create your first plan for premium users.
                                    </p>

                                    <a href="{{ route('admin.subscription-plans.create') }}"
                                        class="mt-5 inline-flex rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-black text-white">
                                        Create Plan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($plans->hasPages())
                <div class="border-t border-black/5 px-5 py-4">
                    {{ $plans->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection