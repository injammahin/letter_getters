@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Store Orders')
@section('page_subtitle', 'View and manage child shop orders')

@section('content')
<div class="space-y-6">
    <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/6">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Child</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/6">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-5">
                                <p class="text-sm font-medium text-neutral-900">{{ $order->order_number }}</p>
                                <p class="mt-1 text-xs text-neutral-500">{{ $order->ordered_at?->format('d M Y, h:i A') }}</p>
                            </td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ $order->user?->name }}</td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ ucfirst($order->order_status) }} / {{ ucfirst($order->shipping_status) }}</td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('admin.store.orders.show', $order) }}" class="rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-5">{{ $orders->links() }}</div>
    </div>
</div>
@endsection