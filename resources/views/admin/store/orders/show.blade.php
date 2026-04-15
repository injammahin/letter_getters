@extends('layouts.admin')

@section('title', 'Order Details')
@section('page_title', 'Order Details')
@section('page_subtitle', 'Order, shipment, and line items')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-neutral-900">{{ $order->order_number }}</h2>
                <p class="mt-2 text-sm text-neutral-500">Ordered by {{ $order->user?->name }}</p>

                <div class="mt-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="rounded-2xl border border-black/8 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-semibold text-neutral-900">{{ $item->product_name }}</h3>
                                    <p class="mt-1 text-sm text-neutral-500">SKU: {{ $item->product_sku }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-neutral-700">Qty: {{ $item->quantity }}</p>
                                    <p class="mt-1 text-sm font-medium text-neutral-900">
                                        {{ number_format($item->line_total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-neutral-900">Order Status</h3>

                    <form action="{{ route('admin.store.orders.update-status', $order) }}" method="POST"
                        class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <select name="order_status" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
                            @foreach(['confirmed', 'processing', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->order_status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}</option>
                            @endforeach
                        </select>

                        <select name="shipping_status" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
                            @foreach(['pending', 'packed', 'shipped', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->shipping_status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}</option>
                            @endforeach
                        </select>

                        <textarea name="admin_note" rows="4"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            placeholder="Admin note">{{ old('admin_note', $order->admin_note) }}</textarea>

                        <button type="submit"
                            class="w-full rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">Update
                            Status</button>
                    </form>
                </div>

                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-neutral-900">Shipment</h3>

                    <form action="{{ route('admin.store.orders.update-shipment', $order) }}" method="POST"
                        class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <input type="text" name="carrier"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Carrier"
                            value="{{ old('carrier', $order->shipment?->carrier) }}">
                        <input type="text" name="tracking_number"
                            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            placeholder="Tracking Number"
                            value="{{ old('tracking_number', $order->shipment?->tracking_number) }}">

                        <select name="status" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
                            @foreach(['pending', 'packed', 'shipped', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ ($order->shipment?->status ?? 'pending') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>

                        <textarea name="note" rows="4" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            placeholder="Shipment note">{{ old('note', $order->shipment?->note) }}</textarea>

                        <button type="submit"
                            class="w-full rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700">Update
                            Shipment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection