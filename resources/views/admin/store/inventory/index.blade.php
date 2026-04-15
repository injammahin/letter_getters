@extends('layouts.admin')

@section('title', 'Inventory')
@section('page_title', 'Inventory')
@section('page_subtitle', 'Adjust stock and view inventory history')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <div class="grid gap-6">
            @foreach($products as $product)
                <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-neutral-900">{{ $product->name }}</h2>
                                    <p class="mt-1 text-sm text-neutral-500">{{ $product->sku }} •
                                        {{ $product->category?->name }}</p>
                                </div>
                                <span
                                    class="rounded-full {{ $product->is_out_of_stock ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700' }} px-3 py-1 text-xs font-medium">
                                    {{ $product->is_out_of_stock ? 'Out of Stock' : 'In Stock' }}
                                </span>
                            </div>

                            <div class="mt-5 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl bg-neutral-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Stock</p>
                                    <p class="mt-2 text-2xl font-semibold text-neutral-900">{{ $product->stock_qty }}</p>
                                </div>
                                <div class="rounded-2xl bg-neutral-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Price</p>
                                    <p class="mt-2 text-2xl font-semibold text-neutral-900">
                                        {{ number_format($product->current_price, 2) }}</p>
                                </div>
                                <div class="rounded-2xl bg-neutral-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">Status</p>
                                    <p class="mt-2 text-base font-semibold text-neutral-900">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h3 class="text-sm font-semibold text-neutral-900">Recent Movements</h3>
                                <div class="mt-3 space-y-2">
                                    @forelse($product->inventoryMovements as $movement)
                                        <div class="rounded-2xl border border-black/8 px-4 py-3 text-sm text-neutral-700">
                                            {{ ucfirst($movement->type) }}: {{ $movement->quantity_change }} |
                                            {{ $movement->qty_before }} → {{ $movement->qty_after }}
                                            <span
                                                class="ml-2 text-neutral-400">{{ $movement->created_at?->format('d M Y, h:i A') }}</span>
                                        </div>
                                    @empty
                                        <div
                                            class="rounded-2xl border border-dashed border-black/10 px-4 py-3 text-sm text-neutral-500">
                                            No inventory movements yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="rounded-[24px] border border-black/8 p-5">
                                <h3 class="text-lg font-semibold text-neutral-900">Adjust Inventory</h3>

                                <form action="{{ route('admin.store.inventory.adjust', $product) }}" method="POST"
                                    class="mt-5 space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <input type="number" name="quantity_change"
                                        class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                                        placeholder="Use negative for deduction">
                                    <textarea name="note" rows="3"
                                        class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                                        placeholder="Adjustment note"></textarea>

                                    <button type="submit"
                                        class="w-full rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">
                                        Save Adjustment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $products->links() }}
        </div>
    </div>
@endsection