<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'sku',
        'image_path',
        'short_description',
        'description',
        'current_price',
        'old_price',
        'stock_qty',
        'is_out_of_stock',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_out_of_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name . '-' . $product->sku);
            }

            $product->is_out_of_stock = $product->stock_qty <= 0 ? true : (bool) $product->is_out_of_stock;
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function isPurchasable(): bool
    {
        return $this->is_active && ! $this->is_out_of_stock && $this->stock_qty > 0;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->old_price || $this->old_price <= $this->current_price) {
            return null;
        }

        return (int) round((($this->old_price - $this->current_price) / $this->old_price) * 100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}