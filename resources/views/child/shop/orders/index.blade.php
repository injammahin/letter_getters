@extends('layouts.child')

@section('title', 'My Orders')

@section('content')
    <div class="space-y-6">
        <section class="child-card rounded-[32px] p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-black">My orders</h1>
            <p class="mt-2 text-sm text-black/55">See your order history and shipment progress.</p>
        </section>

        <div class="space-y-4">
            @forelse($orders as $order)
                <a href="{{ route('child.store.orders.show', $order) }}" class="child-card block rounded-[28px] p-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-black">{{ $order->order_number }}</h3>
                            <p class="mt-1 text-sm text-black/50">{{ $order->ordered_at?->format('d M Y, h:i A') }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/60">{{ ucfirst($order->order_status) }}</span>
                            <span
                                class="rounded-full bg-[#f7efff] px-3 py-1 text-xs font-semibold text-[#620A88]">{{ ucfirst($order->shipping_status) }}</span>
                            <span class="text-sm font-bold text-black">{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="child-card rounded-[28px] p-6 text-center text-sm text-black/55">
                    No orders yet.
                </div>
            @endforelse
        </div>

        {{ $orders->links() }}
    </div>
@endsection