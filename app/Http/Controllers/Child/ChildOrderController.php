<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class ChildOrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items', 'shipment'])
            ->latest()
            ->paginate(12);

        return view('child.shop.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['items', 'shipment']);

        return view('child.shop.orders.show', compact('order'));
    }
}