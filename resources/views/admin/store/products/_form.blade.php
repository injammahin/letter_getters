<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-medium text-neutral-800">Category</label>
        <select name="product_category_id" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-neutral-800">SKU</label>
        <input type="text" name="sku" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
            value="{{ old('sku', $product->sku ?? '') }}">
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-neutral-800">Name</label>
        <input type="text" name="name" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
            value="{{ old('name', $product->name ?? '') }}">
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-neutral-800">Image</label>
        <input type="file" name="image" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-neutral-800">Short Description</label>
        <textarea name="short_description" rows="3"
            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">{{ old('short_description', $product->short_description ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-neutral-800">Description</label>
        <textarea name="description" rows="8"
            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-neutral-800">Current Price</label>
        <input type="number" step="0.01" name="current_price"
            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
            value="{{ old('current_price', $product->current_price ?? '') }}">
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-neutral-800">Old Price</label>
        <input type="number" step="0.01" name="old_price"
            class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
            value="{{ old('old_price', $product->old_price ?? '') }}">
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-neutral-800">Stock Qty</label>
        <input type="number" name="stock_qty" class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm"
            value="{{ old('stock_qty', $product->stock_qty ?? 0) }}">
    </div>

    <div class="space-y-3 pt-7">
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-neutral-700">Active</span>
        </label>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
            <span class="text-sm text-neutral-700">Featured</span>
        </label>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_out_of_stock" value="1" {{ old('is_out_of_stock', $product->is_out_of_stock ?? false) ? 'checked' : '' }}>
            <span class="text-sm text-neutral-700">Mark Out of Stock</span>
        </label>
    </div>
</div>