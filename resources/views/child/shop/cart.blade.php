@extends('layouts.child')

@section('title', 'Cart')

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

        <section class="child-card rounded-[32px] p-6 sm:p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-black">Your cart</h1>
                    <p class="mt-2 text-sm text-black/55">Review your products before checkout.</p>
                </div>

                @if($cartItems->isNotEmpty())
                    <a href="{{ route('child.store.checkout') }}"
                        class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                        Checkout
                    </a>
                @endif
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-4">
                @forelse($cartItems as $item)
                    <div class="child-card rounded-[28px] p-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-4">
                                @if($item->product?->image_path)
                                    <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                        class="h-20 w-20 rounded-3xl object-cover" alt="">
                                @else
                                    <div
                                        class="flex h-20 w-20 items-center justify-center rounded-3xl bg-neutral-100 text-neutral-400">
                                        <i class="fa-solid fa-box text-2xl"></i>
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-bold text-black">{{ $item->product?->name }}</h3>
                                    <p class="mt-1 text-sm text-black/50">
                                        {{ number_format($item->product?->current_price ?? 0, 2) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <form action="{{ route('child.store.cart.update', $item) }}" method="POST"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" min="1" max="{{ $item->product?->stock_qty ?? 1 }}"
                                        value="{{ $item->quantity }}"
                                        class="w-20 rounded-2xl border border-black/10 px-3 py-2 text-sm">
                                    <button type="submit"
                                        class="rounded-full border border-black/10 px-4 py-2 text-sm font-medium text-neutral-700">Update</button>
                                </form>

                                <form action="{{ route('child.store.cart.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="child-card rounded-[28px] p-6 text-center text-sm text-black/55">
                        Your cart is empty.
                    </div>
                @endforelse
            </div>

            <div>
                <div class="child-card rounded-[28px] p-6">
                    <h2 class="text-xl font-bold text-black">Cart summary</h2>
                    <div class="mt-5 flex items-center justify-between text-sm text-black/70">
                        <span>Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-black/70">
                        <span>Shipping</span>
                        <span>0.00</span>
                    </div>
                    <div class="mt-4 border-t border-black/8 pt-4 flex items-center justify-between">
                        <span class="text-base font-bold text-black">Total</span>
                        <span class="text-lg font-bold text-black">{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($cartItems->isNotEmpty())
                        <a href="{{ route('child.store.checkout') }}"
                            class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                            Continue to Checkout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection