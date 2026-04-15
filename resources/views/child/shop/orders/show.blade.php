@extends('layouts.child')

@section('title', 'Order Details')

@section('content')
    <div class="space-y-6">
        <section class="child-card rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-black">{{ $order->order_number }}</h1>
                    <p class="mt-2 text-sm text-black/55">{{ $order->ordered_at?->format('d M Y, h:i A') }}</p>
                </div>

                <div class="flex gap-2">
                    <span
                        class="rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-black/60">{{ ucfirst($order->order_status) }}</span>
                    <span
                        class="rounded-full bg-[#f7efff] px-3 py-1 text-xs font-semibold text-[#620A88]">{{ ucfirst($order->shipping_status) }}</span>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="child-card rounded-[30px] p-6">
                <h2 class="text-xl font-bold text-black">Order items</h2>

                <div class="mt-5 space-y-4">
                    @foreach($order->items as $item)
                        <div class="rounded-2xl border border-black/8 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-semibold text-black">{{ $item->product_name }}</h3>
                                    <p class="mt-1 text-sm text-black/45">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-black">{{ number_format($item->line_total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="child-card rounded-[30px] p-6">
                    <h2 class="text-xl font-bold text-black">Shipment</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span>Status</span><span>{{ ucfirst($order->shipment?->status ?? 'pending') }}</span></div>
                        <div class="flex items-center justify-between">
                            <span>Carrier</span><span>{{ $order->shipment?->carrier ?: 'N/A' }}</span></div>
                        <div class="flex items-center justify-between">
                            <span>Tracking</span><span>{{ $order->shipment?->tracking_number ?: 'N/A' }}</span></div>
                    </div>
                </div>

                <div class="child-card rounded-[30px] p-6">
                    <h2 class="text-xl font-bold text-black">Shipping Address</h2>
                    <p class="mt-4 text-sm leading-7 text-black/60">
                        {{ $order->shipping_recipient_name }}<br>
                        {{ $order->shipping_address_line1 }}<br>
                        @if($order->shipping_address_line2){{ $order->shipping_address_line2 }}<br>@endif
                        {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                        {{ $order->shipping_country }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection