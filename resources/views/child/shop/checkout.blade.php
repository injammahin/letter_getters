@extends('layouts.child')

@section('title', 'Checkout')

@section('content')
    <div class="space-y-6">
        <section class="child-card rounded-[32px] p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-black">Checkout</h1>
            <p class="mt-2 text-sm text-black/55">Fill the shipping details and card-style payment form to place the order.
            </p>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="child-card rounded-[30px] p-6">
                <form action="{{ route('child.store.checkout.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid gap-4 md:grid-cols-2">
                        <input type="text" name="shipping_recipient_name"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Recipient Name"
                            value="{{ old('shipping_recipient_name') }}">
                        <input type="email" name="shipping_email"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Parent Email"
                            value="{{ old('shipping_email', $parentEmail) }}">
                        <input type="text" name="shipping_phone"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Phone"
                            value="{{ old('shipping_phone') }}">
                        <input type="text" name="shipping_city" class="rounded-2xl border border-black/10 px-4 py-3 text-sm"
                            placeholder="City" value="{{ old('shipping_city') }}">
                        <input type="text" name="shipping_state"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="State"
                            value="{{ old('shipping_state') }}">
                        <input type="text" name="shipping_postal_code"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Postal Code"
                            value="{{ old('shipping_postal_code') }}">
                        <input type="text" name="shipping_country"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2" placeholder="Country"
                            value="{{ old('shipping_country', 'Bangladesh') }}">
                        <input type="text" name="shipping_address_line1"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2"
                            placeholder="Address Line 1" value="{{ old('shipping_address_line1') }}">
                        <input type="text" name="shipping_address_line2"
                            class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2"
                            placeholder="Address Line 2" value="{{ old('shipping_address_line2') }}">
                    </div>

                    <textarea name="customer_note" rows="4"
                        class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
                        placeholder="Order note">{{ old('customer_note') }}</textarea>

                    <div class="rounded-[24px] border border-black/8 p-5">
                        <h2 class="text-xl font-bold text-black">Card payment</h2>
                        <p class="mt-2 text-sm text-black/55">This stores only the last 4 digits and does not charge a live
                            gateway.</p>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <input type="text" name="card_holder"
                                class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2"
                                placeholder="Card Holder Name" value="{{ old('card_holder') }}">
                            <input type="text" name="card_number"
                                class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2"
                                placeholder="Card Number" value="{{ old('card_number') }}">
                            <input type="number" name="expiry_month"
                                class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Expiry Month"
                                value="{{ old('expiry_month') }}">
                            <input type="number" name="expiry_year"
                                class="rounded-2xl border border-black/10 px-4 py-3 text-sm" placeholder="Expiry Year"
                                value="{{ old('expiry_year') }}">
                            <input type="text" name="cvc"
                                class="rounded-2xl border border-black/10 px-4 py-3 text-sm md:col-span-2" placeholder="CVC"
                                value="{{ old('cvc') }}">
                        </div>
                    </div>

                    <button type="submit"
                        class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-semibold text-white">
                        Place Order
                    </button>
                </form>
            </div>

            <div class="child-card rounded-[30px] p-6">
                <h2 class="text-xl font-bold text-black">Order summary</h2>

                <div class="mt-5 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between gap-4 text-sm">
                            <div>
                                <p class="font-medium text-black">{{ $item->product?->name }}</p>
                                <p class="text-black/45">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-medium text-black">{{ number_format($item->line_total, 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t border-black/8 pt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span><span>{{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex items-center justify-between">
                        <span>Shipping</span><span>{{ number_format($shippingFee, 2) }}</span></div>
                    <div class="flex items-center justify-between text-base font-bold text-black">
                        <span>Total</span><span>{{ number_format($total, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection