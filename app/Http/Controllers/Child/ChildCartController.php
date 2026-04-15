<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChildCartController extends Controller
{
    public function index(): View
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product.category')
            ->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->line_total);

        return view('child.shop.cart', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->isPurchasable(), 422);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem = CartItem::query()->firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $newQuantity = ($cartItem->exists ? $cartItem->quantity : 0) + (int) $data['quantity'];

        if ($newQuantity > $product->stock_qty) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return redirect()->route('child.store.cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($data['quantity'] > $cartItem->product->stock_qty) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->update([
            'quantity' => $data['quantity'],
        ]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Cart item removed.');
    }
}