<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderShipment;
use Illuminate\View\View;

class AdminShipmentController extends Controller
{
    public function index(): View
    {
        $shipments = OrderShipment::query()
            ->with('order.user')
            ->latest()
            ->paginate(12);

        return view('admin.store.shipping.index', compact('shipments'));
    }
}