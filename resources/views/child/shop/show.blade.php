@extends('layouts.child')

@section('title', $product->name)

@section('content')
<div class="space-y-6">
    <section class="child-card rounded-[32px] p-6 sm:p-8">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div>
                @if($product->image_path)
                    <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-[420px] w-full rounded-[28px] object-cover">
                @else
                    <div class="flex h-[420px] w-full items-center justify-center rounded-[28px] bg-neutral-100 text-neutral-400">
                        <i class="fa-solid fa-box text-4xl"></i>
                    </div>
                @endif
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#620A88]">{{ $product->category?->name }}</p>
                <h1 class="mt-3 text-4xl font-bold text-black">{{ $product->name }}</h1>
                <p class="mt-4 text-sm leading-8 text-black/60">{{ $product->description ?: $product->short_description }}</p>

                <div class="mt-5 flex items-center gap-3">
                    <span class="text-3xl font-bold text-black">{{ number_format($product->current_price, 2) }}</span>
                    @if($product->old_price)
                        <span class="text-lg text-black/35 line-through">{{ number_format($product->old_price, 2) }}</span>
                    @endif
                    @if($product->discount_percent)
                        <span class="rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-semibold text-[#CB148B]">{{ $product->discount_percent }}% OFF</span>
                    @endif
                </div>

                <p class="mt-4 text-sm {{ $product->isPurchasable() ? 'text-green-700' : 'text-red-600' }}">
                    {{ $product->isPurchasable() ? 'In stock' : 'Out of stock' }}
                </p>

                @if($product->isPurchasable())
                    <form action="{{ route('child.store.cart.store', $product) }}" method="POST" class="mt-6 flex flex-wrap items-center gap-3">
                        @csrf
                        <input type="number" name="quantity" min="1" max="{{ $product->stock_qty }}" value="1" class="w-28 rounded-2xl border border-black/10 px-4 py-3 text-sm">
                        <button type="submit" class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-semibold text-white">
                            Add to Cart
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-black">Related products</h2>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach($relatedProducts as $item)
                    <a href="{{ route('child.store.products.show', $item) }}" class="child-card rounded-[28px] p-5">
                        @if($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" class="h-44 w-full rounded-[20px] object-cover" alt="">
                        @endif
                        <h3 class="mt-4 text-base font-bold text-black">{{ $item->name }}</h3>
                        <p class="mt-2 text-sm text-black/55">{{ number_format($item->current_price, 2) }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection