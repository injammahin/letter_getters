<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildHabitat;
use App\Models\ChildHabitatLayout;
use App\Models\ChildHabitatPurchase;
use App\Models\HabitantAsset;
use App\Models\HabitantTheme;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChildHabitantController extends Controller
{
    public function index(Request $request): View
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $themes = HabitantTheme::query()
            ->with([
                'activeAssets' => function ($query) {
                    $query->orderBy('sort_order')->orderBy('type')->orderBy('name');
                },
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedTheme = $themes->firstWhere('slug', $request->query('theme'));

        if (! $selectedTheme) {
            $selectedTheme = $themes->first();
        }

        $habitat = null;
        $ownedAssetIds = collect();
        $layoutRows = collect();

        if ($selectedTheme) {
            $habitat = ChildHabitat::firstOrCreate(
                [
                    'user_id' => $child->id,
                    'theme_id' => $selectedTheme->id,
                ],
                [
                    'hunger' => 35,
                    'happiness' => 70,
                ]
            );

            $habitat->refreshHungerState();

            $ownedAssetIds = ChildHabitatPurchase::query()
                ->where('user_id', $child->id)
                ->where('theme_id', $selectedTheme->id)
                ->pluck('asset_id');

            $layoutRows = ChildHabitatLayout::query()
                ->where('child_habitat_id', $habitat->id)
                ->get()
                ->keyBy('asset_id');
        }

        return view('child.habitant', [
            'child' => $child,
            'themes' => $themes,
            'selectedTheme' => $selectedTheme,
            'habitat' => $habitat,
            'ownedAssetIds' => $ownedAssetIds,
            'layoutRows' => $layoutRows,
        ]);
    }

    public function purchase(Request $request, HabitantAsset $asset): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        if (! $asset->is_active || ! $asset->theme?->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This item is not available right now.',
            ], 422);
        }

        $result = DB::transaction(function () use ($child, $asset) {
            /** @var User $lockedChild */
            $lockedChild = User::query()
                ->whereKey($child->id)
                ->lockForUpdate()
                ->firstOrFail();

            $alreadyOwned = ChildHabitatPurchase::query()
                ->where('user_id', $lockedChild->id)
                ->where('asset_id', $asset->id)
                ->exists();

            if ($alreadyOwned) {
                return [
                    'success' => true,
                    'message' => 'You already own this item.',
                    'coin_balance' => (int) $lockedChild->coin_balance,
                    'already_owned' => true,
                ];
            }

            $price = (int) $asset->price_coins;

            if ((int) $lockedChild->coin_balance < $price) {
                return [
                    'success' => false,
                    'message' => 'You do not have enough coins for this item.',
                    'coin_balance' => (int) $lockedChild->coin_balance,
                ];
            }

            $lockedChild->decrement('coin_balance', $price);
            $lockedChild->refresh();

            ChildHabitatPurchase::create([
                'user_id' => $lockedChild->id,
                'theme_id' => $asset->theme_id,
                'asset_id' => $asset->id,
                'price_paid' => $price,
                'purchased_at' => now(),
            ]);

            $habitat = ChildHabitat::firstOrCreate(
                [
                    'user_id' => $lockedChild->id,
                    'theme_id' => $asset->theme_id,
                ],
                [
                    'hunger' => 35,
                    'happiness' => 70,
                ]
            );

            if ($asset->type === 'background') {
                $habitat->update([
                    'active_background_asset_id' => $asset->id,
                ]);
            }

            if ($asset->type === 'avatar') {
                $habitat->update([
                    'active_avatar_asset_id' => $asset->id,
                ]);
            }

            if ($asset->type !== 'background') {
                ChildHabitatLayout::updateOrCreate(
                    [
                        'child_habitat_id' => $habitat->id,
                        'asset_id' => $asset->id,
                    ],
                    [
                        'x' => $asset->default_x,
                        'y' => $asset->default_y,
                        'scale' => $asset->default_scale,
                        'rotation' => $asset->default_rotation,
                        'direction' => $asset->default_direction,
                        'z_index' => $asset->default_z_index,
                        'is_visible' => true,
                    ]
                );
            }

            return [
                'success' => true,
                'message' => $asset->name . ' purchased successfully.',
                'coin_balance' => (int) $lockedChild->coin_balance,
                'asset_id' => $asset->id,
                'asset_type' => $asset->type,
            ];
        });

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function activate(Request $request, HabitantAsset $asset): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $owned = ChildHabitatPurchase::query()
            ->where('user_id', $child->id)
            ->where('asset_id', $asset->id)
            ->exists();

        if (! $owned) {
            return response()->json([
                'success' => false,
                'message' => 'Please purchase this item first.',
            ], 422);
        }

        $habitat = ChildHabitat::firstOrCreate(
            [
                'user_id' => $child->id,
                'theme_id' => $asset->theme_id,
            ],
            [
                'hunger' => 35,
                'happiness' => 70,
            ]
        );

        if ($asset->type === 'background') {
            $habitat->update([
                'active_background_asset_id' => $asset->id,
            ]);
        } elseif ($asset->type === 'avatar') {
            $habitat->update([
                'active_avatar_asset_id' => $asset->id,
            ]);
        } else {
            ChildHabitatLayout::updateOrCreate(
                [
                    'child_habitat_id' => $habitat->id,
                    'asset_id' => $asset->id,
                ],
                [
                    'x' => $asset->default_x,
                    'y' => $asset->default_y,
                    'scale' => $asset->default_scale,
                    'rotation' => $asset->default_rotation,
                    'direction' => $asset->default_direction,
                    'z_index' => $asset->default_z_index,
                    'is_visible' => true,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Item activated.',
        ]);
    }

    public function saveLayout(Request $request): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $data = $request->validate([
            'theme_id' => ['required', 'exists:habitant_themes,id'],
            'items' => ['required', 'array'],
            'items.*.asset_id' => ['required', 'exists:habitant_assets,id'],
            'items.*.x' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.y' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.scale' => ['required', 'numeric', 'min:0.2', 'max:3'],
            'items.*.rotation' => ['required', 'numeric', 'min:-180', 'max:180'],
            'items.*.direction' => ['required', 'in:left,right'],
            'items.*.z_index' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.is_visible' => ['nullable', 'boolean'],
        ]);

        $habitat = ChildHabitat::firstOrCreate(
            [
                'user_id' => $child->id,
                'theme_id' => $data['theme_id'],
            ],
            [
                'hunger' => 35,
                'happiness' => 70,
            ]
        );

        $ownedAssetIds = ChildHabitatPurchase::query()
            ->where('user_id', $child->id)
            ->where('theme_id', $data['theme_id'])
            ->pluck('asset_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        foreach ($data['items'] as $item) {
            $assetId = (int) $item['asset_id'];

            if (! in_array($assetId, $ownedAssetIds, true)) {
                continue;
            }

            $asset = HabitantAsset::find($assetId);

            if (! $asset || $asset->type === 'background') {
                continue;
            }

            ChildHabitatLayout::updateOrCreate(
                [
                    'child_habitat_id' => $habitat->id,
                    'asset_id' => $assetId,
                ],
                [
                    'x' => $item['x'],
                    'y' => $item['y'],
                    'scale' => $item['scale'],
                    'rotation' => $item['rotation'],
                    'direction' => $item['direction'],
                    'z_index' => $item['z_index'],
                    'is_visible' => $item['is_visible'] ?? true,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Habitat layout saved.',
        ]);
    }

    public function feed(Request $request): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $data = $request->validate([
            'theme_id' => ['required', 'exists:habitant_themes,id'],
        ]);

        $habitat = ChildHabitat::firstOrCreate(
            [
                'user_id' => $child->id,
                'theme_id' => $data['theme_id'],
            ],
            [
                'hunger' => 35,
                'happiness' => 70,
            ]
        );

        $hasFood = ChildHabitatPurchase::query()
            ->where('user_id', $child->id)
            ->where('theme_id', $data['theme_id'])
            ->whereHas('asset', function ($query) {
                $query->where('type', 'food');
            })
            ->exists();

        if (! $hasFood) {
            return response()->json([
                'success' => false,
                'message' => 'Please purchase food first.',
            ], 422);
        }

        $habitat->update([
            'hunger' => 100,
            'happiness' => min(100, $habitat->happiness + 10),
            'last_fed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yum yum. Your friend is happy.',
            'hunger' => $habitat->hunger,
            'happiness' => $habitat->happiness,
            'is_sad' => false,
        ]);
    }

    public function play(Request $request): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $data = $request->validate([
            'theme_id' => ['required', 'exists:habitant_themes,id'],
        ]);

        $habitat = ChildHabitat::firstOrCreate(
            [
                'user_id' => $child->id,
                'theme_id' => $data['theme_id'],
            ],
            [
                'hunger' => 35,
                'happiness' => 70,
            ]
        );

        $hasToy = ChildHabitatPurchase::query()
            ->where('user_id', $child->id)
            ->where('theme_id', $data['theme_id'])
            ->whereHas('asset', function ($query) {
                $query->where('type', 'toy');
            })
            ->exists();

        if (! $hasToy) {
            return response()->json([
                'success' => false,
                'message' => 'Please purchase a toy first.',
            ], 422);
        }

        $habitat->update([
            'happiness' => min(100, $habitat->happiness + 7),
            'last_played_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Play time was fun.',
            'hunger' => $habitat->hunger,
            'happiness' => $habitat->happiness,
            'is_sad' => $habitat->isSad(),
        ]);
    }

    public function completeGuide(Request $request): JsonResponse
    {
        $child = auth()->user();

        abort_unless($child && $child->role === 'child', 403);

        $data = $request->validate([
            'theme_id' => ['required', 'exists:habitant_themes,id'],
        ]);

        $habitat = ChildHabitat::firstOrCreate(
            [
                'user_id' => $child->id,
                'theme_id' => $data['theme_id'],
            ],
            [
                'hunger' => 35,
                'happiness' => 70,
            ]
        );

        $habitat->update([
            'guide_completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}