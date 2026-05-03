@php
    $isEdit = filled($asset);
    $formAction = $isEdit
        ? route('admin.habitants.assets.update', [$theme, $asset])
        : route('admin.habitants.assets.store', $theme);

    $submitText = $isEdit ? 'Save Item' : 'Upload Item';

    $nameValue = old('name', $asset?->name ?? $defaultName);
    $descriptionValue = old('description', $asset?->description);
    $priceValue = old('price_coins', $asset?->price_coins ?? $defaultPrice);

    $xValue = old('default_x', $asset?->default_x ?? 50);
    $yValue = old('default_y', $asset?->default_y ?? 70);
    $scaleValue = old('default_scale', $asset?->default_scale ?? 1);
    $rotationValue = old('default_rotation', $asset?->default_rotation ?? 0);
    $directionValue = old('default_direction', $asset?->default_direction ?? 'right');
    $zIndexValue = old('default_z_index', $asset?->default_z_index ?? 10);
    $sortOrderValue = old('sort_order', $asset?->sort_order ?? 0);
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-5">
    @csrf

    @if($isEdit)
        @method('PUT')
    @endif

    <input type="hidden" name="type" value="{{ $type }}">

    <div class="grid gap-5 lg:grid-cols-3">
        <div>
            <label class="{{ $labelClass }}">Item Name</label>
            <input type="text" name="name" value="{{ $nameValue }}" class="{{ $fieldClass }}" required>
        </div>

        <div>
            <label class="{{ $labelClass }}">Price Coins</label>
            <input type="number" name="price_coins" value="{{ $priceValue }}" min="0" class="{{ $fieldClass }}"
                required>
        </div>

        <div>
            <label class="{{ $labelClass }}">Sort Order</label>
            <input type="number" name="sort_order" value="{{ $sortOrderValue }}" min="0" class="{{ $fieldClass }}">
        </div>
    </div>

    <div>
        <label class="{{ $labelClass }}">Short Description</label>
        <textarea name="description" rows="2" class="{{ $fieldClass }}">{{ $descriptionValue }}</textarea>
    </div>

    <div class="grid gap-5 {{ $requiresAvatarStates ? 'lg:grid-cols-4' : 'lg:grid-cols-1' }}">
        <div>
            <label class="{{ $labelClass }}">
                {{ $requiresAvatarStates ? 'Idle Image' : 'Main Image' }}
                @if(!$isEdit)
                    <span class="text-red-600">*</span>
                @endif
            </label>

            <input type="file" name="image_path" accept="image/*" class="{{ $fieldClass }}" {{ $isEdit ? '' : 'required' }}>

            @if($asset?->image_url)
                <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}"
                    class="mt-3 h-24 w-24 rounded-2xl bg-gray-50 object-contain">
            @endif
        </div>

        @if($requiresAvatarStates)
            <div>
                <label class="{{ $labelClass }}">
                    Walking Image
                    @if(!$isEdit)
                        <span class="text-red-600">*</span>
                    @endif
                </label>

                <input type="file" name="walking_image_path" accept="image/*" class="{{ $fieldClass }}" {{ $isEdit ? '' : 'required' }}>

                @if($asset?->walking_image_url)
                    <img src="{{ $asset->walking_image_url }}" alt="Walking"
                        class="mt-3 h-24 w-24 rounded-2xl bg-gray-50 object-contain">
                @endif
            </div>

            <div>
                <label class="{{ $labelClass }}">
                    Eating Image
                    @if(!$isEdit)
                        <span class="text-red-600">*</span>
                    @endif
                </label>

                <input type="file" name="eating_image_path" accept="image/*" class="{{ $fieldClass }}" {{ $isEdit ? '' : 'required' }}>

                @if($asset?->eating_image_url)
                    <img src="{{ $asset->eating_image_url }}" alt="Eating"
                        class="mt-3 h-24 w-24 rounded-2xl bg-gray-50 object-contain">
                @endif
            </div>

            <div>
                <label class="{{ $labelClass }}">
                    Sad Image
                    @if(!$isEdit)
                        <span class="text-red-600">*</span>
                    @endif
                </label>

                <input type="file" name="sad_image_path" accept="image/*" class="{{ $fieldClass }}" {{ $isEdit ? '' : 'required' }}>

                @if($asset?->sad_image_url)
                    <img src="{{ $asset->sad_image_url }}" alt="Sad"
                        class="mt-3 h-24 w-24 rounded-2xl bg-gray-50 object-contain">
                @endif
            </div>
        @endif
    </div>

    <details class="rounded-2xl border border-black/10 bg-gray-50 p-4">
        <summary class="cursor-pointer text-sm font-black text-black">
            Advanced Position Settings
        </summary>

        <div class="mt-4 grid gap-5 md:grid-cols-6">
            <div>
                <label class="{{ $labelClass }}">X Position</label>
                <input type="number" step="0.01" name="default_x" value="{{ $xValue }}" class="{{ $fieldClass }}">
            </div>

            <div>
                <label class="{{ $labelClass }}">Y Position</label>
                <input type="number" step="0.01" name="default_y" value="{{ $yValue }}" class="{{ $fieldClass }}">
            </div>

            <div>
                <label class="{{ $labelClass }}">Size</label>
                <input type="number" step="0.01" name="default_scale" value="{{ $scaleValue }}"
                    class="{{ $fieldClass }}">
            </div>

            <div>
                <label class="{{ $labelClass }}">Rotation</label>
                <input type="number" step="0.01" name="default_rotation" value="{{ $rotationValue }}"
                    class="{{ $fieldClass }}">
            </div>

            <div>
                <label class="{{ $labelClass }}">Direction</label>
                <select name="default_direction" class="{{ $fieldClass }}">
                    <option value="right" {{ $directionValue === 'right' ? 'selected' : '' }}>Right</option>
                    <option value="left" {{ $directionValue === 'left' ? 'selected' : '' }}>Left</option>
                </select>
            </div>

            <div>
                <label class="{{ $labelClass }}">Layer</label>
                <input type="number" name="default_z_index" value="{{ $zIndexValue }}" class="{{ $fieldClass }}">
            </div>
        </div>
    </details>

    <div class="grid gap-4 md:grid-cols-2">
        <label
            class="flex items-center gap-3 rounded-2xl border border-black/10 px-4 py-3 text-sm font-semibold text-black/70">
            <input type="checkbox" name="is_required" value="1" {{ $asset?->is_required ? 'checked' : '' }}>
            Required Item
        </label>

        <label
            class="flex items-center gap-3 rounded-2xl border border-black/10 px-4 py-3 text-sm font-semibold text-black/70">
            <input type="checkbox" name="is_active" value="1" {{ $isEdit ? ($asset?->is_active ? 'checked' : '') : 'checked' }}>
            Active Item
        </label>
    </div>

    <div class="flex justify-end">
        <button type="submit"
            class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-bold text-white">
            {{ $submitText }}
        </button>
    </div>
</form>