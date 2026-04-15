<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;

class InventoryService
{
    public function adjust(Product $product, int $change, ?string $type = 'adjust', ?string $note = null, ?int $adminId = null): Product
    {
        $before = $product->stock_qty;
        $after = max(0, $before + $change);

        $product->update([
            'stock_qty' => $after,
            'is_out_of_stock' => $after <= 0,
        ]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'type' => $type ?? 'adjust',
            'quantity_change' => $change,
            'qty_before' => $before,
            'qty_after' => $after,
            'note' => $note,
            'created_by' => $adminId,
        ]);

        return $product->fresh();
    }
}