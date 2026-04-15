@extends('layouts.child')

@section('title', 'Shop')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-[24px] border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-[24px] border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}</div>
        @endif

        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Child Shop
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">Shop fun products</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        Browse available child-friendly products, add them to cart, and place an order safely.
                    </p>
                </div>

                <a href="{{ route('child.store.cart.index') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    Cart
                </a>
            </div>
        </section>

        <form method="GET" class="flex flex-wrap gap-3">
            <select name="category" class="rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>

            <button type="submit"
                class="rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700">Filter</button>
        </form>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @forelse($products as $product)
                <div class="child-card rounded-[28px] p-5">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                            class="h-52 w-full rounded-[24px] object-cover">
                    @else
                        <div class="flex h-52 w-full items-center justify-center rounded-[24px] bg-neutral-100 text-neutral-400">
                            <i class="fa-solid fa-box text-3xl"></i>
                        </div>
                    @endif

                    <div class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#620A88]">
                            {{ $product->category?->name }}</p>
                        <h3 class="mt-2 text-lg font-bold text-black">{{ $product->name }}</h3>
                        <p class="mt-2 text-sm leading-7 text-black/55">{{ $product->short_description }}</p>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <span class="text-xl font-bold text-black">{{ number_format($product->current_price, 2) }}</span>
                        @if($product->old_price)
                            <span class="text-sm text-black/35 line-through">{{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('child.store.products.show', $product) }}"
                            class="flex-1 rounded-full border border-black/10 px-4 py-3 text-center text-sm font-semibold text-black/70">
                            Details
                        </a>

                        <form action="{{ route('child.store.cart.store', $product) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="w-full rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-4 py-3 text-sm font-semibold text-white">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    class="md:col-span-2 xl:col-span-4 rounded-[28px] border border-dashed border-black/10 bg-white p-8 text-center text-sm text-black/55">
                    No products available right now.
                </div>
            @endforelse
        </section>

        {{ $products->links() }}
    </div>
@endsection