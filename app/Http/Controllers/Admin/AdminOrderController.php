<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderShipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with(['user', 'shipment'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('order_status', $request->input('status'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.store.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items', 'shipment']);

        return view('admin.store.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'order_status' => ['required', 'in:confirmed,processing,completed,cancelled'],
            'shipping_status' => ['required', 'in:pending,packed,shipped,delivered,cancelled'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'order_status' => $data['order_status'],
            'shipping_status' => $data['shipping_status'],
            'admin_note' => $data['admin_note'] ?? $order->admin_note,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updateShipment(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'carrier' => ['nullable', 'string', 'max:120'],
            'tracking_number' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:pending,packed,shipped,delivered,cancelled'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $shipment = $order->shipment ?: new OrderShipment(['order_id' => $order->id]);

        $shipment->fill($data);

        if ($data['status'] === 'shipped' && ! $shipment->shipped_at) {
            $shipment->shipped_at = now();
        }

        if ($data['status'] === 'delivered' && ! $shipment->delivered_at) {
            $shipment->delivered_at = now();
        }

        $shipment->save();

        $order->update([
            'shipping_status' => $data['status'],
        ]);

        return back()->with('success', 'Shipment updated successfully.');
    }
}