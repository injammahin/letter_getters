@extends('layouts.admin')

@section('title', 'Shipping')
@section('page_title', 'Shipping')
@section('page_subtitle', 'All shipment records')

@section('content')
    <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/6">
                <thead class="bg-neutral-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                            Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                            Carrier</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                            Tracking</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                            Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/6">
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="px-6 py-5 text-sm text-neutral-700">
                                <a href="{{ route('admin.store.orders.show', $shipment->order) }}"
                                    class="font-medium text-neutral-900">{{ $shipment->order?->order_number }}</a>
                            </td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ $shipment->carrier ?: 'N/A' }}</td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ $shipment->tracking_number ?: 'N/A' }}</td>
                            <td class="px-6 py-5 text-sm text-neutral-700">{{ ucfirst($shipment->status) }}</td>
                            <td class="px-6 py-5 text-sm text-neutral-500">{{ $shipment->updated_at?->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-5">{{ $shipments->links() }}</div>
    </div>
@endsection