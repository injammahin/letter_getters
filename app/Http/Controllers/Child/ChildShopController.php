<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChildShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_out_of_stock', false)
            ->where('stock_qty', '>', 0)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($subQuery) use ($request) {
                    $subQuery->where('slug', $request->input('category'));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('child.shop.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $relatedProducts = Product::query()
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('child.shop.show', compact('product', 'relatedProducts'));
    }
}