<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Mail\StoreOrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipment;
use App\Models\ParentChildLink;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChildCheckoutController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
    }

    public function create(): View
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        abort_if($cartItems->isEmpty(), 422, 'Cart is empty.');

        $subtotal = $cartItems->sum(fn ($item) => $item->line_total);
        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        $child = auth()->user();
        $parentEmail = $this->parentEmailForChild($child);

        return view('child.shop.checkout', compact('cartItems', 'subtotal', 'shippingFee', 'total', 'parentEmail'));
    }

    public function store(Request $request): RedirectResponse
    {
        $child = auth()->user();
        $cartItems = $child->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('child.store.cart.index')->with('error', 'Cart is empty.');
        }

        $data = $request->validate([
            'shipping_recipient_name' => ['required', 'string', 'max:120'],
            'shipping_email' => ['nullable', 'email', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'shipping_state' => ['required', 'string', 'max:120'],
            'shipping_postal_code' => ['nullable', 'string', 'max:50'],
            'shipping_country' => ['required', 'string', 'max:120'],
            'customer_note' => ['nullable', 'string', 'max:1000'],

            'card_holder' => ['required', 'string', 'max:120'],
            'card_number' => ['required', 'digits_between:12,19'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:' . now()->year, 'max:' . (now()->year + 20)],
            'cvc' => ['required', 'digits_between:3,4'],
        ]);

        foreach ($cartItems as $cartItem) {
            if (! $cartItem->product || ! $cartItem->product->isPurchasable()) {
                return back()->with('error', 'One or more products are no longer available.');
            }

            if ($cartItem->quantity > $cartItem->product->stock_qty) {
                return back()->with('error', 'One or more cart quantities exceed available stock.');
            }
        }

        $parentEmail = $this->parentEmailForChild($child);

        $order = DB::transaction(function () use ($child, $cartItems, $data, $parentEmail) {
            $subtotal = $cartItems->sum(fn ($item) => $item->line_total);
            $shippingFee = 0;
            $total = $subtotal + $shippingFee;

            $order = Order::create([
                'user_id' => $child->id,
                'order_number' => 'LG-ORD-' . now()->format('YmdHis') . Str::upper(Str::random(4)),
                'parent_email' => $parentEmail,
                'shipping_recipient_name' => $data['shipping_recipient_name'],
                'shipping_email' => $data['shipping_email'] ?? $parentEmail,
                'shipping_phone' => $data['shipping_phone'] ?? null,
                'shipping_address_line1' => $data['shipping_address_line1'],
                'shipping_address_line2' => $data['shipping_address_line2'] ?? null,
                'shipping_city' => $data['shipping_city'],
                'shipping_state' => $data['shipping_state'],
                'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
                'shipping_country' => $data['shipping_country'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => 'card',
                'payment_status' => 'paid',
                'payment_last_four' => substr($data['card_number'], -4),
                'order_status' => 'confirmed',
                'shipping_status' => 'pending',
                'customer_note' => $data['customer_note'] ?? null,
                'ordered_at' => now(),
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'unit_price' => $cartItem->product->current_price,
                    'quantity' => $cartItem->quantity,
                    'line_total' => $cartItem->quantity * $cartItem->product->current_price,
                ]);

                $this->inventoryService->adjust(
                    $cartItem->product,
                    -$cartItem->quantity,
                    'order_sale',
                    'Stock deducted for order ' . $order->order_number,
                    $child->id
                );
            }

            OrderShipment::create([
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            CartItem::query()->where('user_id', $child->id)->delete();

            return $order;
        });

        $order->load('items');

        if ($parentEmail) {
            Mail::to($parentEmail)->send(new StoreOrderConfirmationMail($order));
        }

        return redirect()
            ->route('child.store.orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    protected function parentEmailForChild($child): ?string
    {
        $link = ParentChildLink::query()
            ->with('parent')
            ->where('child_user_id', $child->id)
            ->first();

        return $link?->parent?->email ?? $child->parent_email;
    }
}