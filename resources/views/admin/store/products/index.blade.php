@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Manage child shop products')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-4">
            <form method="GET" class="flex items-center gap-3">
                <select name="category" class="rounded-2xl border border-black/10 px-4 py-3 text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="rounded-full border border-black/10 px-5 py-3 text-sm font-medium text-neutral-700">Filter</button>
            </form>

            <a href="{{ route('admin.store.products.create') }}"
                class="rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-5 py-3 text-sm font-medium text-white">
                Create Product
            </a>
        </div>

        <div class="rounded-[30px] border border-black/8 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/6">
                    <thead class="bg-neutral-50/80">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Product</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Category</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Price</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Stock</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-black/6">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                class="h-12 w-12 rounded-2xl object-cover" alt="">
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100">
                                                <i class="fa-solid fa-box text-neutral-500"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-neutral-900">{{ $product->name }}</p>
                                            <p class="mt-1 text-xs text-neutral-500">{{ $product->sku }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-sm text-neutral-700">{{ $product->category?->name }}</td>
                                <td class="px-6 py-5 text-sm text-neutral-700">
                                    {{ number_format($product->current_price, 2) }}
                                    @if($product->old_price)
                                        <span
                                            class="ml-2 text-xs text-neutral-400 line-through">{{ number_format($product->old_price, 2) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-sm text-neutral-700">
                                    {{ $product->stock_qty }}
                                    @if($product->is_out_of_stock)
                                        <span
                                            class="ml-2 rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-600">Out</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.store.products.edit', $product) }}"
                                            class="rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">Edit</a>
                                        <form action="{{ route('admin.store.products.destroy', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection