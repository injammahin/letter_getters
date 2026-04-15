<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
    }

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('product_category_id', $request->integer('category'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = ProductCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.store.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = ProductCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.store.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('store/products', 'public')
            : null;

        $product = Product::create([
            'product_category_id' => $data['product_category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(5)),
            'sku' => $data['sku'],
            'image_path' => $imagePath,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'current_price' => $data['current_price'],
            'old_price' => $data['old_price'] ?? null,
            'stock_qty' => $data['stock_qty'],
            'is_out_of_stock' => $request->boolean('is_out_of_stock') || $data['stock_qty'] <= 0,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $this->inventoryService->adjust(
            $product,
            (int) $product->stock_qty,
            'initial',
            'Initial stock on product creation',
            auth()->id()
        );

        return redirect()->route('admin.store.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.store.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product->id);

        $imagePath = $product->image_path;

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('store/products', 'public');
        }

        $product->update([
            'product_category_id' => $data['product_category_id'],
            'name' => $data['name'],
            'sku' => $data['sku'],
            'image_path' => $imagePath,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'current_price' => $data['current_price'],
            'old_price' => $data['old_price'] ?? null,
            'stock_qty' => $data['stock_qty'],
            'is_out_of_stock' => $request->boolean('is_out_of_stock') || $data['stock_qty'] <= 0,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.store.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    protected function validatedData(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku,' . $productId],
            'image' => ['nullable', 'image', 'max:4096'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'current_price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'gte:current_price'],
            'stock_qty' => ['required', 'integer', 'min:0'],
        ]);
    }
}