<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
    }

    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'inventoryMovements' => fn ($q) => $q->latest()->limit(5)])
            ->latest()
            ->paginate(12);

        return view('admin.store.inventory.index', compact('products'));
    }

    public function adjust(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity_change' => ['required', 'integer', 'not_in:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $this->inventoryService->adjust(
            $product,
            (int) $data['quantity_change'],
            'adjust',
            $data['note'] ?? 'Manual inventory adjustment',
            auth()->id()
        );

        return back()->with('success', 'Inventory adjusted successfully.');
    }
}