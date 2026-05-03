@extends('layouts.child')

@section('title', $penPal->name . "'s Habitant")

@section('content')
    @php
        $activeBackgroundId = $activeBackgroundId ?? null;
        $activeAvatarId = $activeAvatarId ?? null;

        $themePayload = $themes->map(function ($theme) use ($ownedAssetIds, $layoutRows, $activeBackgroundId, $activeAvatarId) {
            return [
                'id' => $theme->id,
                'name' => $theme->name,
                'slug' => $theme->slug,
                'description' => $theme->description,
                'thumbnail_url' => $theme->thumbnail_url,
                'assets' => $theme->activeAssets->map(function ($asset) use ($ownedAssetIds, $layoutRows, $activeBackgroundId, $activeAvatarId) {
                    $layout = $layoutRows->get($asset->id);

                    return [
                        'id' => (int) $asset->id,
                        'theme_id' => (int) $asset->theme_id,
                        'type' => $asset->type,
                        'name' => $asset->name,

                        'owned' =>
                            $ownedAssetIds->contains((int) $asset->id)
                            || (int) $activeBackgroundId === (int) $asset->id
                            || (int) $activeAvatarId === (int) $asset->id
                            || $layoutRows->has($asset->id),

                        'has_saved_layout' => $layout !== null,

                        'image_url' => $asset->image_url,
                        'walking_image_url' => $asset->walking_image_url,
                        'eating_image_url' => $asset->eating_image_url,
                        'sad_image_url' => $asset->sad_image_url,

                        'default_x' => (float) $asset->default_x,
                        'default_y' => (float) $asset->default_y,
                        'default_scale' => (float) $asset->default_scale,
                        'default_rotation' => (float) $asset->default_rotation,
                        'default_direction' => $asset->default_direction,
                        'default_z_index' => (int) $asset->default_z_index,

                        'layout' => $layout ? [
                            'x' => (float) $layout->x,
                            'y' => (float) $layout->y,
                            'scale' => (float) $layout->scale,
                            'rotation' => (float) $layout->rotation,
                            'direction' => $layout->direction,
                            'z_index' => (int) $layout->z_index,
                            'is_visible' => (bool) $layout->is_visible,
                        ] : null,
                    ];
                })->values(),
            ];
        })->values();
    @endphp

    <div x-data="penPalHabitantViewer({
                themes: @js($themePayload),
                selectedThemeId: {{ (int) ($selectedTheme?->id ?? 0) }},
                activeBackgroundId: {{ $activeBackgroundId ? (int) $activeBackgroundId : 'null' }},
                activeAvatarId: {{ $activeAvatarId ? (int) $activeAvatarId : 'null' }},
                isSad: {{ $isSad ? 'true' : 'false' }},
            })" x-init="init()" class="-mx-4 -my-6 sm:-mx-6 lg:-mx-8">
        <style>
            [x-cloak] {
                display: none !important;
            }

            .visit-habitant-page {
                min-height: calc(100vh - 88px);
                background:
                    radial-gradient(circle at 15% 8%, rgba(255, 240, 249, .95), transparent 28%),
                    radial-gradient(circle at 86% 12%, rgba(247, 239, 255, .95), transparent 30%),
                    linear-gradient(135deg, #fff7fc, #f8f3ff);
            }

            .visit-top {
                position: relative;
                z-index: 50;
                padding: 14px 18px;
            }

            .visit-top-card {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                border-radius: 26px;
                background: rgba(255, 255, 255, .94);
                border: 1px solid rgba(255, 255, 255, .96);
                box-shadow: 0 16px 45px rgba(17, 17, 17, .08);
                padding: 12px 14px;
                backdrop-filter: blur(14px);
            }

            .visit-scene-wrap {
                padding: 0 18px 18px;
            }

            .visit-scene {
                position: relative;
                height: calc(100vh - 178px);
                min-height: 650px;
                overflow: hidden;
                border-radius: 32px;
                background-size: cover;
                background-position: center;
                background-color: #fff7fc;
                box-shadow: 0 20px 60px rgba(17, 17, 17, .08);
                isolation: isolate;
            }

            .visit-scene::before {
                content: "";
                position: absolute;
                inset: 0;
                z-index: 1;
                pointer-events: none;
                background:
                    radial-gradient(circle at 20% 13%, rgba(255, 255, 255, .42), transparent 25%),
                    radial-gradient(circle at 76% 10%, rgba(255, 255, 255, .28), transparent 28%),
                    linear-gradient(180deg, rgba(255, 255, 255, .08), rgba(255, 255, 255, 0) 58%);
            }

            .visit-sparkle-layer {
                position: absolute;
                inset: 0;
                z-index: 2;
                pointer-events: none;
                background-image:
                    radial-gradient(circle, rgba(255, 255, 255, .88) 0 1px, transparent 2px),
                    radial-gradient(circle, rgba(255, 191, 225, .8) 0 1px, transparent 2px),
                    radial-gradient(circle, rgba(255, 226, 92, .9) 0 1px, transparent 2px);
                background-size: 130px 130px, 180px 180px, 240px 240px;
                animation: visitSparkle 12s linear infinite;
                opacity: .72;
            }

            .visit-empty {
                position: absolute;
                inset: 0;
                z-index: 30;
                display: grid;
                place-items: center;
                padding: 20px;
                text-align: center;
            }

            .visit-empty-card {
                width: min(440px, 100%);
                border-radius: 32px;
                background: rgba(255, 255, 255, .94);
                border: 1px solid rgba(255, 255, 255, .95);
                padding: 26px;
                box-shadow: 0 24px 70px rgba(17, 17, 17, .10);
            }

            .visit-item {
                position: absolute;
                transform-origin: center center;
                user-select: none;
                pointer-events: none;
            }

            .visit-item img {
                display: block;
                width: 100%;
                height: auto;
                filter: drop-shadow(0 20px 24px rgba(45, 72, 38, .18));
            }

            .visit-avatar img {
                animation: visitAvatarIdle 3s ease-in-out infinite;
            }

            .visit-toy img {
                animation: visitToyFloat 3s ease-in-out infinite;
            }

            @keyframes visitAvatarIdle {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-7px);
                }
            }

            @keyframes visitToyFloat {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-7px);
                }
            }

            @keyframes visitSparkle {
                0% {
                    background-position: 20px 40px, 80px 20px, 160px 100px;
                }

                100% {
                    background-position: 150px 170px, 260px 200px, 400px 340px;
                }
            }

            @media (max-width: 768px) {
                .visit-top-card {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .visit-scene {
                    height: calc(100vh - 230px);
                    min-height: 560px;
                }
            }
        </style>

        <section class="visit-habitant-page">
            <div class="visit-top">
                <div class="visit-top-card">
                    <div class="flex items-center gap-3">
                        @if($penPal->profile?->avatar_type === 'upload' && $penPal->profile?->avatar)
                            <img src="{{ asset('storage/' . $penPal->profile->avatar) }}" alt="Avatar"
                                class="h-12 w-12 rounded-2xl object-cover">
                        @elseif($penPal->profile?->avatar_type === 'library' && $penPal->profile?->avatarLibrary?->image_path)
                            <img src="{{ asset('storage/' . $penPal->profile->avatarLibrary->image_path) }}" alt="Avatar"
                                class="h-12 w-12 rounded-2xl object-cover">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl child-gradient text-white">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.16em] text-[#CB148B]">
                                Pen Pal Habitant
                            </p>

                            <h1 class="text-xl font-black text-black">
                                {{ $penPal->name }}’s world
                            </h1>
                        </div>
                    </div>

                    <a href="{{ route('child.pen-pals') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-black text-black/60 hover:border-[#620A88] hover:text-[#620A88]">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back to Pen Pals
                    </a>
                </div>
            </div>

            <div class="visit-scene-wrap">
                <div class="visit-scene"
                    :style="activeBackground ? `background-image: url('${activeBackground.image_url}')` : ''">
                    <div class="visit-sparkle-layer"></div>

                    <template x-if="!activeBackground && visibleSceneAssets.length === 0">
                        <div class="visit-empty">
                            <div class="visit-empty-card">
                                <div
                                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#fff0f9] text-3xl">
                                    🏡
                                </div>

                                <h2 class="mt-4 text-xl font-black text-black">
                                    No habitant set up yet
                                </h2>

                                <p class="mt-2 text-sm leading-6 text-black/50">
                                    Your pen pal has not decorated a habitant yet.
                                </p>
                            </div>
                        </div>
                    </template>

                    <template x-for="asset in visibleSceneAssets" :key="asset.id">
                        <div class="visit-item" :class="{
                                    'visit-avatar': asset.type === 'avatar',
                                    'visit-toy': asset.type === 'toy'
                                }" :style="itemStyle(asset)">
                            <img :src="imageForAsset(asset)" :alt="asset.name">
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </div>

    <script>
        function penPalHabitantViewer(config) {
            return {
                themes: config.themes || [],
                selectedThemeId: Number(config.selectedThemeId || 0),
                activeBackgroundId: config.activeBackgroundId || null,
                activeAvatarId: config.activeAvatarId || null,
                isSad: Boolean(config.isSad),

                init() {
                    if (!this.selectedThemeId && this.themes.length > 0) {
                        this.selectedThemeId = this.themes[0].id;
                    }

                    this.prepareActiveAvatarFallback();
                },

                get selectedTheme() {
                    return this.themes.find(theme => Number(theme.id) === Number(this.selectedThemeId)) || null;
                },

                get selectedThemeAssets() {
                    return this.selectedTheme?.assets || [];
                },

                get activeBackground() {
                    return this.selectedThemeAssets.find(asset => Number(asset.id) === Number(this.activeBackgroundId)) || null;
                },

                get activeAvatar() {
                    return this.selectedThemeAssets.find(asset => Number(asset.id) === Number(this.activeAvatarId)) || null;
                },

                get visibleSceneAssets() {
                    return this.selectedThemeAssets.filter(asset => {
                        if (asset.type === 'background') {
                            return false;
                        }

                        if (asset.type === 'avatar') {
                            return Number(asset.id) === Number(this.activeAvatarId);
                        }

                        if (asset.has_saved_layout && asset.layout) {
                            return asset.layout.is_visible !== false;
                        }

                        return false;
                    });
                },

                prepareActiveAvatarFallback() {
                    this.selectedThemeAssets.forEach(asset => {
                        if (
                            asset.type === 'avatar' &&
                            Number(asset.id) === Number(this.activeAvatarId) &&
                            !asset.layout
                        ) {
                            asset.layout = {
                                x: Number(asset.default_x || 50),
                                y: Number(asset.default_y || 70),
                                scale: Number(asset.default_scale || 1),
                                rotation: Number(asset.default_rotation || 0),
                                direction: asset.default_direction || 'right',
                                z_index: Number(asset.default_z_index || 50),
                                is_visible: true,
                            };
                        }
                    });
                },

                imageForAsset(asset) {
                    if (asset.type !== 'avatar') {
                        return asset.image_url;
                    }

                    if (this.isSad) {
                        return asset.sad_image_url || asset.image_url;
                    }

                    return asset.image_url;
                },

                itemStyle(asset) {
                    const layout = asset.layout || {};

                    let width = 130;

                    if (asset.type === 'avatar') {
                        width = 210 * Number(layout.scale || 1);
                    } else if (asset.type === 'food' || asset.type === 'toy') {
                        width = 95 * Number(layout.scale || 1);
                    } else {
                        width = 130 * Number(layout.scale || 1);
                    }

                    const flip = layout.direction === 'left' ? -1 : 1;

                    return [
                        `left:${layout.x ?? 50}%`,
                        `top:${layout.y ?? 70}%`,
                        `width:${width}px`,
                        `z-index:${Math.max(Number(layout.z_index ?? 10), 5)}`,
                        `transform:translate(-50%, -50%) rotate(${layout.rotation ?? 0}deg) scaleX(${flip})`,
                    ].join(';');
                },
            };
        }
    </script>
@endsection