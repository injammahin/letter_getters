<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HabitantAsset;
use App\Models\HabitantTheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HabitantThemeController extends Controller
{
    public function index(): View
    {
        $themes = HabitantTheme::query()
            ->withCount('assets')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.habitants.index', compact('themes'));
    }

    public function create(): View
    {
        return view('admin.habitants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:habitant_themes,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'thumbnail_image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail_image')) {
            $thumbnailPath = $request->file('thumbnail_image')->store('habitant/themes', 'public');
        }

        $theme = HabitantTheme::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(5)),
            'description' => $data['description'] ?? null,
            'thumbnail_image' => $thumbnailPath,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.habitants.edit', $theme)
            ->with('success', 'Theme created successfully. Now upload the theme assets.');
    }

    public function edit(HabitantTheme $habitant): View
    {
        $habitant->load([
            'assets' => function ($query) {
                $query->orderBy('sort_order')->orderBy('type')->orderBy('name');
            },
        ]);

        return view('admin.habitants.edit', [
            'theme' => $habitant,
        ]);
    }

    public function update(Request $request, HabitantTheme $habitant): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('habitant_themes', 'name')->ignore($habitant->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'thumbnail_image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $thumbnailPath = $habitant->thumbnail_image;

        if ($request->hasFile('thumbnail_image')) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            $thumbnailPath = $request->file('thumbnail_image')->store('habitant/themes', 'public');
        }

        $habitant->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'thumbnail_image' => $thumbnailPath,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Theme updated successfully.');
    }

    public function destroy(HabitantTheme $habitant): RedirectResponse
    {
        foreach ($habitant->assets as $asset) {
            $this->deleteAssetFiles($asset);
        }

        if ($habitant->thumbnail_image) {
            Storage::disk('public')->delete($habitant->thumbnail_image);
        }

        $habitant->delete();

        return redirect()
            ->route('admin.habitants.index')
            ->with('success', 'Theme deleted successfully.');
    }

    public function storeAsset(Request $request, HabitantTheme $habitant): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['background', 'avatar', 'food', 'toy', 'decoration'])],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],

            'image_path' => ['required', 'image', 'max:8192'],
            'walking_image_path' => ['nullable', 'image', 'max:8192'],
            'eating_image_path' => ['nullable', 'image', 'max:8192'],
            'sad_image_path' => ['nullable', 'image', 'max:8192'],

            'price_coins' => ['required', 'integer', 'min:0'],
            'default_x' => ['required', 'numeric', 'min:0', 'max:100'],
            'default_y' => ['required', 'numeric', 'min:0', 'max:100'],
            'default_scale' => ['required', 'numeric', 'min:0.2', 'max:3'],
            'default_rotation' => ['required', 'numeric', 'min:-180', 'max:180'],
            'default_direction' => ['required', Rule::in(['left', 'right'])],
            'default_z_index' => ['required', 'integer', 'min:1', 'max:999'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'avatar') {
            $request->validate([
                'walking_image_path' => ['required', 'image', 'max:8192'],
                'eating_image_path' => ['required', 'image', 'max:8192'],
                'sad_image_path' => ['required', 'image', 'max:8192'],
            ]);
        }

        $folder = 'habitant/assets/' . $habitant->id;

        $mainImage = $request->file('image_path')->store($folder, 'public');

        $walkingImage = $request->hasFile('walking_image_path')
            ? $request->file('walking_image_path')->store($folder, 'public')
            : null;

        $eatingImage = $request->hasFile('eating_image_path')
            ? $request->file('eating_image_path')->store($folder, 'public')
            : null;

        $sadImage = $request->hasFile('sad_image_path')
            ? $request->file('sad_image_path')->store($folder, 'public')
            : null;

        HabitantAsset::create([
            'theme_id' => $habitant->id,
            'type' => $data['type'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(5)),
            'description' => $data['description'] ?? null,
            'image_path' => $mainImage,
            'walking_image_path' => $walkingImage,
            'eating_image_path' => $eatingImage,
            'sad_image_path' => $sadImage,
            'price_coins' => $data['price_coins'],
            'default_x' => $data['default_x'],
            'default_y' => $data['default_y'],
            'default_scale' => $data['default_scale'],
            'default_rotation' => $data['default_rotation'],
            'default_direction' => $data['default_direction'],
            'default_z_index' => $data['default_z_index'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Asset uploaded successfully.');
    }

    public function destroyAsset(HabitantTheme $habitant, HabitantAsset $asset): RedirectResponse
    {
        abort_unless($asset->theme_id === $habitant->id, 404);

        $this->deleteAssetFiles($asset);

        $asset->delete();

        return back()->with('success', 'Asset deleted successfully.');
    }

    protected function deleteAssetFiles(HabitantAsset $asset): void
    {
        $paths = [
            $asset->image_path,
            $asset->walking_image_path,
            $asset->eating_image_path,
            $asset->sad_image_path,
        ];

        foreach ($paths as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
    }
    public function updateAsset(Request $request, HabitantTheme $habitant, HabitantAsset $asset): RedirectResponse
{
    abort_unless($asset->theme_id === $habitant->id, 404);

    $data = $request->validate([
        'type' => ['required', Rule::in(['background', 'avatar', 'food', 'toy', 'decoration'])],
        'name' => ['required', 'string', 'max:150'],
        'description' => ['nullable', 'string', 'max:1000'],

        'image_path' => ['nullable', 'image', 'max:8192'],
        'walking_image_path' => ['nullable', 'image', 'max:8192'],
        'eating_image_path' => ['nullable', 'image', 'max:8192'],
        'sad_image_path' => ['nullable', 'image', 'max:8192'],

        'price_coins' => ['required', 'integer', 'min:0'],
        'default_x' => ['required', 'numeric', 'min:0', 'max:100'],
        'default_y' => ['required', 'numeric', 'min:0', 'max:100'],
        'default_scale' => ['required', 'numeric', 'min:0.2', 'max:3'],
        'default_rotation' => ['required', 'numeric', 'min:-180', 'max:180'],
        'default_direction' => ['required', Rule::in(['left', 'right'])],
        'default_z_index' => ['required', 'integer', 'min:1', 'max:999'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
        'is_required' => ['nullable', 'boolean'],
        'is_active' => ['nullable', 'boolean'],
    ]);

    $folder = 'habitant/assets/' . $habitant->id;

    $mainImage = $asset->image_path;
    $walkingImage = $asset->walking_image_path;
    $eatingImage = $asset->eating_image_path;
    $sadImage = $asset->sad_image_path;

    if ($request->hasFile('image_path')) {
        if ($mainImage) {
            Storage::disk('public')->delete($mainImage);
        }

        $mainImage = $request->file('image_path')->store($folder, 'public');
    }

    if ($request->hasFile('walking_image_path')) {
        if ($walkingImage) {
            Storage::disk('public')->delete($walkingImage);
        }

        $walkingImage = $request->file('walking_image_path')->store($folder, 'public');
    }

    if ($request->hasFile('eating_image_path')) {
        if ($eatingImage) {
            Storage::disk('public')->delete($eatingImage);
        }

        $eatingImage = $request->file('eating_image_path')->store($folder, 'public');
    }

    if ($request->hasFile('sad_image_path')) {
        if ($sadImage) {
            Storage::disk('public')->delete($sadImage);
        }

        $sadImage = $request->file('sad_image_path')->store($folder, 'public');
    }

    $asset->update([
        'type' => $data['type'],
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
        'image_path' => $mainImage,
        'walking_image_path' => $walkingImage,
        'eating_image_path' => $eatingImage,
        'sad_image_path' => $sadImage,
        'price_coins' => $data['price_coins'],
        'default_x' => $data['default_x'],
        'default_y' => $data['default_y'],
        'default_scale' => $data['default_scale'],
        'default_rotation' => $data['default_rotation'],
        'default_direction' => $data['default_direction'],
        'default_z_index' => $data['default_z_index'],
        'sort_order' => $data['sort_order'] ?? 0,
        'is_required' => $request->boolean('is_required'),
        'is_active' => $request->boolean('is_active'),
    ]);

    return back()->with('success', 'Item updated successfully.');
}
}