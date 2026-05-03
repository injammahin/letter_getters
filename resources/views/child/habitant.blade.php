@extends('layouts.child')

@section('title', 'My Habitant')

@section('content')
    @php
        $themePayload = $themes->map(function ($theme) use ($ownedAssetIds, $layoutRows) {
            return [
                'id' => $theme->id,
                'name' => $theme->name,
                'slug' => $theme->slug,
                'description' => $theme->description,
                'thumbnail_url' => $theme->thumbnail_url,
                'assets' => $theme->activeAssets->map(function ($asset) use ($ownedAssetIds, $layoutRows) {
                    $layout = $layoutRows->get($asset->id);

                    return [
                        'id' => $asset->id,
                        'theme_id' => $asset->theme_id,
                        'type' => $asset->type,
                        'name' => $asset->name,
                        'description' => $asset->description,
                        'price_coins' => $asset->price_coins,
                        'owned' => $ownedAssetIds->contains($asset->id),
                        'image_url' => $asset->image_url,
                        'walking_image_url' => $asset->walking_image_url,
                        'eating_image_url' => $asset->eating_image_url,
                        'sad_image_url' => $asset->sad_image_url,
                        'default_x' => $asset->default_x,
                        'default_y' => $asset->default_y,
                        'default_scale' => $asset->default_scale,
                        'default_rotation' => $asset->default_rotation,
                        'default_direction' => $asset->default_direction,
                        'default_z_index' => $asset->default_z_index,
                        'layout' => $layout ? [
                            'x' => $layout->x,
                            'y' => $layout->y,
                            'scale' => $layout->scale,
                            'rotation' => $layout->rotation,
                            'direction' => $layout->direction,
                            'z_index' => $layout->z_index,
                            'is_visible' => $layout->is_visible,
                        ] : null,
                    ];
                })->values(),
            ];
        })->values();

        $activeBackgroundId = $habitat?->active_background_asset_id;
        $activeAvatarId = $habitat?->active_avatar_asset_id;
    @endphp

    <div x-data="habitantGame({
                                                csrf: @js(csrf_token()),
                                                childCoins: {{ (int) (auth()->user()?->coin_balance ?? 0) }},
                                                themes: @js($themePayload),
                                                selectedThemeId: {{ (int) ($selectedTheme?->id ?? 0) }},
                                                habitat: {
                                                    id: {{ (int) ($habitat?->id ?? 0) }},
                                                    hunger: {{ (int) ($habitat?->hunger ?? 35) }},
                                                    happiness: {{ (int) ($habitat?->happiness ?? 70) }},
                                                    guideCompleted: {{ $habitat?->guide_completed_at ? 'true' : 'false' }},
                                                    activeBackgroundId: {{ $activeBackgroundId ? (int) $activeBackgroundId : 'null' }},
                                                    activeAvatarId: {{ $activeAvatarId ? (int) $activeAvatarId : 'null' }},
                                                    isSad: {{ $habitat?->isSad() ? 'true' : 'false' }},
                                                },
                                                routes: {
                                                    purchaseBase: @js(url('/child/habitant/assets')),
                                                    saveLayout: @js(route('child.habitant.layout.save')),
                                                    feed: @js(route('child.habitant.feed')),
                                                    play: @js(route('child.habitant.play')),
                                                    completeGuide: @js(route('child.habitant.guide.complete')),
                                                }
                                            })" x-init="init()" class="-mx-4 -my-6 sm:-mx-6 lg:-mx-8">
        <style>
            [x-cloak] {
                display: none !important;
            }

            .habitant-stage {
                position: relative;
                min-height: calc(100vh - 88px);
                overflow: hidden;
                background:
                    radial-gradient(circle at 18% 10%, rgba(255, 240, 249, 0.92), transparent 28%),
                    radial-gradient(circle at 80% 12%, rgba(247, 239, 255, 0.95), transparent 28%),
                    linear-gradient(135deg, #fff7fc, #f8f3ff);
            }

            .habitant-scene {
                position: relative;
                min-height: calc(100vh - 88px);
                overflow: hidden;
                background-size: cover;
                background-position: center;
                background-color: #fff7fc;
                isolation: isolate;
            }

            .habitant-scene::before {
                content: "";
                position: absolute;
                inset: 0;
                z-index: 1;
                pointer-events: none;
                background:
                    radial-gradient(circle at 20% 13%, rgba(255, 255, 255, .45), transparent 25%),
                    radial-gradient(circle at 76% 10%, rgba(255, 255, 255, .32), transparent 28%),
                    linear-gradient(180deg, rgba(255, 255, 255, .12), rgba(255, 255, 255, 0) 58%);
            }

            .habitant-scene::after {
                content: "";
                position: absolute;
                inset: 0;
                z-index: 2;
                pointer-events: none;
                background-image:
                    radial-gradient(circle, rgba(255, 255, 255, .92) 0 1px, transparent 2px),
                    radial-gradient(circle, rgba(255, 191, 225, .82) 0 1px, transparent 2px),
                    radial-gradient(circle, rgba(255, 226, 92, .92) 0 1px, transparent 2px);
                background-size: 130px 130px, 180px 180px, 240px 240px;
                background-position: 20px 40px, 80px 20px, 160px 100px;
                animation: sceneSparkleDrift 12s linear infinite;
                opacity: .82;
            }

            .habitant-scene.party-mode::before {
                animation: partyGlow 1.4s ease-in-out infinite;
            }

            .habitant-empty-bg {
                background:
                    radial-gradient(circle at 20% 12%, rgba(255, 240, 249, 0.95), transparent 30%),
                    radial-gradient(circle at 80% 15%, rgba(247, 239, 255, 0.98), transparent 30%),
                    linear-gradient(135deg, #fff7fc, #f7efff);
            }

            .magic-layer {
                position: absolute;
                inset: 0;
                z-index: 3;
                pointer-events: none;
                overflow: hidden;
            }

            .magic-dot {
                position: absolute;
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: rgba(255, 255, 255, .88);
                box-shadow:
                    0 0 12px rgba(255, 255, 255, .9),
                    0 0 28px rgba(203, 20, 139, .18);
                animation: magicFloat 6s ease-in-out infinite;
            }

            .magic-bubble {
                position: absolute;
                width: 20px;
                height: 20px;
                border-radius: 999px;
                border: 2px solid rgba(255, 255, 255, .65);
                background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .9), rgba(255, 255, 255, .1));
                animation: bubbleFloat 8s ease-in-out infinite;
            }

            .habitant-hud {
                position: absolute;
                top: 18px;
                left: 18px;
                right: 18px;
                z-index: 100;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 14px;
                pointer-events: none;
            }

            .hud-group {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 10px;
                pointer-events: auto;
            }

            .hud-pill {
                display: inline-flex;
                align-items: center;
                gap: 9px;
                min-height: 46px;
                padding: 9px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, .88);
                border: 1px solid rgba(255, 255, 255, .96);
                box-shadow: 0 14px 34px rgba(62, 34, 110, .13);
                backdrop-filter: blur(14px);
                color: #620A88;
                font-size: 13px;
                font-weight: 900;
            }

            .hud-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 46px;
                border: 0;
                border-radius: 999px;
                padding: 9px 15px;
                background: rgba(255, 255, 255, .9);
                color: #620A88;
                font-size: 13px;
                font-weight: 900;
                box-shadow: 0 14px 34px rgba(62, 34, 110, .13);
                cursor: pointer;
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .hud-button:hover,
            .habitant-action:hover,
            .asset-btn:hover {
                transform: translateY(-2px) scale(1.03);
            }

            .hud-button.primary {
                color: #ffffff;
                background: linear-gradient(135deg, #CB148B, #620A88);
            }

            .hud-button.sound-muted {
                background: rgba(255, 245, 245, .92);
                color: #dc2626;
            }

            .habitant-workspace {
                position: absolute;
                inset: 0;
                z-index: 5;
            }

            .habitant-workspace::after {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 2;
                height: 180px;
                pointer-events: none;
                background: linear-gradient(180deg, transparent, rgba(41, 126, 56, .12));
            }

            .habitant-item {
                position: absolute;
                transform-origin: center center;
                user-select: none;
                touch-action: none;
                will-change: left, top, transform;
            }

            .habitant-item::before {
                content: "";
                position: absolute;
                inset: 12%;
                z-index: -1;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(255, 255, 255, .75), rgba(255, 255, 255, 0) 65%);
                opacity: 0;
                transform: scale(.8);
                pointer-events: none;
            }

            .habitant-avatar.aura::before,
            .habitant-avatar.happy::before,
            .habitant-avatar.petting::before,
            .habitant-avatar.dancing::before,
            .habitant-avatar.playing::before {
                opacity: 1;
                animation: auraPulse 1.2s ease-in-out infinite;
            }

            .habitant-item img {
                display: block;
                width: 100%;
                height: auto;
                pointer-events: none;
                user-select: none;
                filter: drop-shadow(0 20px 24px rgba(45, 72, 38, .18));
            }

            .habitant-avatar {
                width: 210px;
                transition:
                    width .25s ease,
                    transform .25s ease;
            }

            .habitant-avatar.idle img {
                animation: avatarIdle 3s ease-in-out infinite;
            }

            .habitant-avatar.walking img {
                animation: avatarWalk .44s ease-in-out infinite;
            }

            .habitant-avatar.eating img {
                animation: avatarEat .62s ease-in-out infinite;
            }

            .habitant-avatar.happy img {
                animation: avatarHappy .55s ease-in-out 4;
            }

            .habitant-avatar.sad img {
                animation: avatarSad 2.3s ease-in-out infinite;
            }

            .habitant-avatar.playing img {
                animation: avatarPlay .62s ease-in-out infinite;
            }

            .habitant-avatar.petting img {
                animation: avatarPet 1.1s ease-in-out infinite;
            }

            .habitant-avatar.dancing img {
                animation: avatarDance .72s ease-in-out infinite;
            }

            .habitant-avatar.celebrating img {
                animation: avatarCelebrate .58s ease-in-out infinite;
            }

            .habitant-decoration {
                width: 130px;
            }

            .habitant-food {
                width: 95px;
            }

            .habitant-toy {
                width: 95px;
                animation: toyFloat 3s ease-in-out infinite;
            }

            .habitant-toy.playing-toy img {
                animation: toyPlaySpin .48s linear infinite;
                filter: drop-shadow(0 18px 18px rgba(203, 20, 139, .22));
            }

            .habitant-editing .habitant-item:not(.habitant-background) {
                outline: 2px dashed rgba(203, 20, 139, .48);
                outline-offset: 8px;
                border-radius: 20px;
                cursor: grab;
            }

            .habitant-editing .habitant-item.selected {
                outline: 3px solid rgba(98, 10, 136, .75);
                outline-offset: 10px;
            }

            .habitant-empty-card {
                position: absolute;
                inset: 0;
                z-index: 40;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                text-align: center;
            }

            .habitant-empty-inner {
                width: min(460px, 100%);
                border-radius: 34px;
                background: rgba(255, 255, 255, .92);
                border: 1px solid rgba(255, 255, 255, .95);
                box-shadow: 0 24px 70px rgba(62, 34, 110, .16);
                padding: 28px;
                backdrop-filter: blur(14px);
            }

            .habitant-actions {
                position: absolute;
                left: 50%;
                bottom: 22px;
                z-index: 110;
                transform: translateX(-50%);
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
                pointer-events: auto;
            }

            .habitant-action {
                border: 0;
                border-radius: 999px;
                min-height: 48px;
                padding: 12px 18px;
                background: rgba(255, 255, 255, .9);
                color: #620A88;
                font-size: 14px;
                font-weight: 900;
                box-shadow: 0 12px 28px rgba(62, 34, 110, .16);
                cursor: pointer;
                backdrop-filter: blur(12px);
                transition: transform .18s ease, box-shadow .18s ease;
            }

            .habitant-action.primary {
                color: #ffffff;
                background: linear-gradient(135deg, #CB148B, #620A88);
                box-shadow: 0 14px 32px rgba(203, 20, 139, .25);
            }

            .habitant-action:disabled,
            .hud-button:disabled {
                opacity: .55;
                cursor: not-allowed;
                transform: none;
            }

            .habitant-speech {
                position: absolute;
                z-index: 120;
                min-width: 180px;
                max-width: 335px;
                padding: 13px 17px;
                border-radius: 24px;
                background: rgba(255, 255, 255, .95);
                border: 1px solid rgba(255, 255, 255, .98);
                box-shadow: 0 16px 36px rgba(73, 38, 125, .16);
                color: #620A88;
                text-align: center;
                font-size: 14px;
                font-weight: 900;
                opacity: 0;
                transform: translate(-50%, -50%) scale(.9);
                pointer-events: none;
                transition: .2s ease;
            }

            .habitant-speech.show {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }

            .habitant-effects-layer {
                position: absolute;
                inset: 0;
                z-index: 160;
                pointer-events: none;
            }

            .habitant-effect {
                position: absolute;
                transform: translate(-50%, -50%);
                pointer-events: none;
                user-select: none;
                font-weight: 900;
            }

            .habitant-effect.heart {
                color: #ff4fa4;
                font-size: 26px;
                text-shadow: 0 8px 18px rgba(255, 79, 164, .22);
                animation: habitantHeartRise 1.25s ease-out forwards;
            }

            .habitant-effect.star {
                color: #ffc83d;
                font-size: 24px;
                text-shadow: 0 8px 18px rgba(255, 200, 61, .25);
                animation: habitantStarPop 1.1s ease-out forwards;
            }

            .habitant-effect.confetti {
                font-size: 22px;
                animation: habitantConfetti 1.4s ease-out forwards;
            }

            .habitant-effect.note {
                font-size: 24px;
                animation: musicNoteRise 1.3s ease-out forwards;
            }

            .habitant-effect.paw {
                font-size: 18px;
                opacity: .85;
                animation: pawFade 1.2s ease-out forwards;
            }

            .habitant-effect.ring {
                width: 72px;
                height: 72px;
                border-radius: 999px;
                border: 3px solid rgba(255, 255, 255, .82);
                box-shadow: 0 0 28px rgba(255, 123, 211, .30);
                animation: magicRing 1s ease-out forwards;
            }

            .habitant-effect.pop {
                min-width: 56px;
                padding: 6px 11px;
                border-radius: 999px;
                background: rgba(255, 255, 255, .94);
                color: #620A88;
                font-size: 13px;
                box-shadow: 0 12px 28px rgba(98, 10, 136, .16);
                animation: popFloat 1.3s ease-out forwards;
            }

            .edit-left-panel,
            .edit-right-panel,
            .edit-bottom-panel {
                position: absolute;
                z-index: 130;
                border: 1px solid rgba(255, 255, 255, .92);
                background: rgba(255, 255, 255, .93);
                box-shadow: 0 24px 70px rgba(17, 17, 17, .14);
                backdrop-filter: blur(18px);
            }

            .edit-left-panel {
                top: 96px;
                left: 18px;
                width: 92px;
                border-radius: 28px;
                padding: 10px;
            }

            .edit-tab {
                width: 100%;
                min-height: 70px;
                border: 0;
                border-radius: 22px;
                background: transparent;
                color: rgba(17, 17, 17, .58);
                font-size: 11px;
                font-weight: 900;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
                transition: .18s ease;
            }

            .edit-tab span {
                font-size: 22px;
            }

            .edit-tab.active {
                color: #620A88;
                background: linear-gradient(135deg, #fff7fc, #f7efff);
                box-shadow: inset 0 0 0 1px rgba(98, 10, 136, .10);
            }

            .edit-right-panel {
                top: 96px;
                right: 18px;
                width: 340px;
                max-height: calc(100vh - 220px);
                overflow-y: auto;
                border-radius: 30px;
                padding: 16px;
            }

            .edit-bottom-panel {
                left: 50%;
                bottom: 88px;
                width: min(760px, calc(100% - 36px));
                transform: translateX(-50%);
                border-radius: 28px;
                padding: 14px;
            }

            .asset-card {
                border-radius: 22px;
                border: 1px solid rgba(17, 17, 17, .08);
                background: #ffffff;
                padding: 12px;
                box-shadow: 0 12px 26px rgba(17, 17, 17, .06);
            }

            .asset-card-image {
                height: 112px;
                display: grid;
                place-items: center;
                border-radius: 18px;
                background: linear-gradient(135deg, #fff7fc, #f7efff);
                overflow: hidden;
            }

            .asset-card-image img {
                max-width: 100%;
                max-height: 100px;
                object-fit: contain;
            }

            .asset-btn {
                width: 100%;
                border: 0;
                border-radius: 999px;
                padding: 10px 13px;
                font-size: 12px;
                font-weight: 900;
                cursor: pointer;
                transition: transform .18s ease;
            }

            .asset-btn.buy {
                color: #ffffff;
                background: linear-gradient(135deg, #CB148B, #620A88);
            }

            .asset-btn.owned {
                color: #111111;
                background: #ffffff;
                border: 1px solid rgba(17, 17, 17, .10);
            }

            .theme-modal {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: grid;
                place-items: center;
                padding: 20px;
                background: rgba(17, 17, 17, .38);
                backdrop-filter: blur(6px);
            }

            .theme-modal-card {
                width: min(920px, 100%);
                max-height: min(760px, calc(100vh - 40px));
                overflow-y: auto;
                border-radius: 36px;
                background: #ffffff;
                box-shadow: 0 30px 90px rgba(17, 17, 17, .24);
                padding: 24px;
            }

            .theme-card {
                border-radius: 28px;
                border: 1px solid rgba(17, 17, 17, .08);
                background: #ffffff;
                padding: 14px;
                cursor: pointer;
                transition: .22s ease;
            }

            .theme-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 18px 42px rgba(17, 17, 17, .10);
            }

            .theme-card.active {
                border-color: rgba(203, 20, 139, .40);
                box-shadow: 0 18px 42px rgba(203, 20, 139, .12);
            }

            .theme-thumb {
                height: 160px;
                border-radius: 22px;
                overflow: hidden;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, #fff7fc, #f7efff);
            }

            .theme-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .guide-bubble {
                position: fixed;
                right: 24px;
                bottom: 24px;
                z-index: 9998;
                max-width: 390px;
                border-radius: 30px;
                border: 1px solid rgba(255, 255, 255, .9);
                background: rgba(255, 255, 255, .96);
                box-shadow: 0 24px 80px rgba(17, 17, 17, .16);
                padding: 18px;
            }

            .guide-mascot {
                width: 54px;
                height: 54px;
                display: grid;
                place-items: center;
                border-radius: 22px;
                background: linear-gradient(135deg, #fff7fc, #f7efff);
                font-size: 28px;
            }

            .toast-cute {
                position: fixed;
                left: 50%;
                bottom: 28px;
                z-index: 99999;
                transform: translateX(-50%) translateY(12px);
                opacity: 0;
                border-radius: 999px;
                background: rgba(17, 17, 17, .92);
                color: #ffffff;
                padding: 12px 18px;
                font-size: 13px;
                font-weight: 900;
                box-shadow: 0 18px 40px rgba(17, 17, 17, .22);
                transition: .22s ease;
                pointer-events: none;
            }

            .toast-cute.show {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            @keyframes sceneSparkleDrift {
                0% {
                    background-position: 20px 40px, 80px 20px, 160px 100px;
                }

                100% {
                    background-position: 150px 170px, 260px 200px, 400px 340px;
                }
            }

            @keyframes partyGlow {

                0%,
                100% {
                    opacity: .95;
                }

                50% {
                    opacity: .65;
                }
            }

            @keyframes magicFloat {

                0%,
                100% {
                    transform: translateY(0) scale(.85);
                    opacity: .35;
                }

                50% {
                    transform: translateY(-24px) scale(1.15);
                    opacity: 1;
                }
            }

            @keyframes bubbleFloat {
                0% {
                    transform: translateY(12px) scale(.8);
                    opacity: 0;
                }

                20% {
                    opacity: .7;
                }

                100% {
                    transform: translateY(-70px) scale(1.15);
                    opacity: 0;
                }
            }

            @keyframes auraPulse {

                0%,
                100% {
                    transform: scale(.75);
                    opacity: .35;
                }

                50% {
                    transform: scale(1.15);
                    opacity: .9;
                }
            }

            @keyframes avatarIdle {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-7px);
                }
            }

            @keyframes avatarWalk {

                0%,
                100% {
                    transform: translateY(0) rotate(-1.5deg);
                }

                50% {
                    transform: translateY(-11px) rotate(1.5deg);
                }
            }

            @keyframes avatarEat {

                0%,
                100% {
                    transform: translateY(0) rotate(0);
                }

                50% {
                    transform: translateY(6px) rotate(2deg);
                }
            }

            @keyframes avatarHappy {

                0%,
                100% {
                    transform: translateY(0) scale(1) rotate(0);
                }

                35% {
                    transform: translateY(-18px) scale(1.04) rotate(-3deg);
                }

                70% {
                    transform: translateY(-5px) scale(1.02) rotate(3deg);
                }
            }

            @keyframes avatarSad {

                0%,
                100% {
                    transform: translateY(0);
                    opacity: .95;
                }

                50% {
                    transform: translateY(5px);
                    opacity: 1;
                }
            }

            @keyframes avatarPlay {

                0%,
                100% {
                    transform: translateY(0) rotate(0) scale(1);
                }

                35% {
                    transform: translateY(-26px) rotate(-4deg) scale(1.04);
                }

                70% {
                    transform: translateY(-6px) rotate(4deg) scale(1.02);
                }
            }

            @keyframes avatarPet {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                35% {
                    transform: translateY(-8px) scale(1.04) rotate(-2deg);
                }

                70% {
                    transform: translateY(-3px) scale(1.02) rotate(2deg);
                }
            }

            @keyframes avatarDance {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg) scale(1);
                }

                20% {
                    transform: translateY(-14px) rotate(-7deg) scale(1.03);
                }

                45% {
                    transform: translateY(-4px) rotate(7deg) scale(1.02);
                }

                70% {
                    transform: translateY(-16px) rotate(-5deg) scale(1.05);
                }
            }

            @keyframes avatarCelebrate {

                0%,
                100% {
                    transform: translateY(0) rotate(0) scale(1);
                }

                50% {
                    transform: translateY(-22px) rotate(5deg) scale(1.06);
                }
            }

            @keyframes toyFloat {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-7px);
                }
            }

            @keyframes toyPlaySpin {
                0% {
                    transform: translateY(0) rotate(0deg) scale(1);
                }

                35% {
                    transform: translateY(-18px) rotate(140deg) scale(1.06);
                }

                70% {
                    transform: translateY(-5px) rotate(260deg) scale(1.02);
                }

                100% {
                    transform: translateY(0) rotate(360deg) scale(1);
                }
            }

            @keyframes habitantHeartRise {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(0) scale(.75);
                }

                18% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(-78px) scale(1.25);
                }
            }

            @keyframes habitantStarPop {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(.5) rotate(0deg);
                }

                20% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(-55px) scale(1.15) rotate(120deg);
                }
            }

            @keyframes habitantConfetti {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(0) rotate(0deg) scale(.7);
                }

                20% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(70px) rotate(240deg) scale(1.15);
                }
            }

            @keyframes musicNoteRise {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(0) rotate(-8deg) scale(.75);
                }

                20% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(-85px) rotate(16deg) scale(1.22);
                }
            }

            @keyframes pawFade {
                0% {
                    opacity: .85;
                    transform: translate(-50%, -50%) scale(.9);
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(1.2);
                }
            }

            @keyframes magicRing {
                0% {
                    opacity: .85;
                    transform: translate(-50%, -50%) scale(.3);
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(1.6);
                }
            }

            @keyframes popFloat {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(8px) scale(.9);
                }

                20% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) translateY(-52px) scale(1.05);
                }
            }

            @media (max-width: 1024px) {
                .edit-right-panel {
                    width: 300px;
                }
            }

            @media (max-width: 768px) {

                .habitant-stage,
                .habitant-scene {
                    min-height: calc(100vh - 80px);
                }

                .habitant-hud {
                    top: 10px;
                    left: 10px;
                    right: 10px;
                    flex-direction: column;
                }

                .hud-pill,
                .hud-button {
                    min-height: 40px;
                    padding: 8px 11px;
                    font-size: 12px;
                }

                .habitant-avatar {
                    width: 155px;
                }

                .edit-left-panel {
                    top: auto;
                    left: 10px;
                    right: 10px;
                    bottom: 150px;
                    width: auto;
                    display: flex;
                    overflow-x: auto;
                    border-radius: 24px;
                }

                .edit-tab {
                    min-width: 76px;
                }

                .edit-right-panel {
                    top: auto;
                    left: 10px;
                    right: 10px;
                    bottom: 230px;
                    width: auto;
                    max-height: 250px;
                    border-radius: 24px;
                }

                .edit-bottom-panel {
                    bottom: 86px;
                }

                .habitant-actions {
                    bottom: 14px;
                    width: calc(100% - 20px);
                }

                .habitant-action {
                    min-height: 42px;
                    padding: 10px 12px;
                    font-size: 12px;
                }

                .guide-bubble {
                    left: 14px;
                    right: 14px;
                    bottom: 14px;
                    max-width: none;
                }
            }
        </style>

        @if($themes->isEmpty())
            <div class="min-h-[520px] p-6">
                <div class="mx-auto max-w-xl rounded-[32px] border border-black/10 bg-white p-8 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-amber-50 text-3xl">
                        🏞️
                    </div>

                    <h2 class="mt-5 text-2xl font-bold text-black">No habitant themes yet</h2>

                    <p class="mt-2 text-sm leading-7 text-black/55">
                        Admin needs to upload a Lion Theme, Unicorn Theme, or another theme first.
                    </p>
                </div>
            </div>
        @else
            <section class="habitant-stage">
                <div id="habitantScene" class="habitant-scene" :class="{
                                                                                                'habitant-empty-bg': !activeBackground,
                                                                                                'habitant-editing': editMode,
                                                                                                'party-mode': partyMode
                                                                                            }"
                    :style="activeBackground ? `background-image: url('${activeBackground.image_url}')` : ''">
                    <div class="magic-layer">
                        <template x-for="particle in ambientParticles" :key="particle.id">
                            <div :class="particle.className" :style="particle.style"></div>
                        </template>
                    </div>

                    <div class="habitant-hud">
                        <div class="hud-group">
                            <button type="button" class="hud-button primary" @click="themeChooserOpen = true">
                                🌈 <span x-text="selectedTheme?.name || 'Select Theme'"></span>
                            </button>

                            <div class="hud-pill">
                                🪙 <span x-text="coins"></span>
                            </div>
                        </div>

                        <div class="hud-group">
                            <div class="hud-pill">
                                🍽️ <span x-text="hunger + '%'"></span>
                            </div>

                            <div class="hud-pill">
                                💖 <span x-text="happiness + '%'"></span>
                            </div>

                            <button type="button" class="hud-button" :class="{ 'sound-muted': audioMuted }"
                                @click="toggleAudio()">
                                <span x-text="audioMuted ? '🔇 Sound Off' : '🔊 Sound On'"></span>
                            </button>

                            <button type="button" class="hud-button" @click="editMode ? saveLayout() : toggleEditMode()"
                                :disabled="isBusy">
                                <span x-text="editMode ? '✅ Save & Close' : '✏️ Edit Habitat'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="habitant-workspace" id="habitantWorkspace">
                        <template x-if="!activeBackground">
                            <div class="habitant-empty-card">
                                <div class="habitant-empty-inner">
                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-amber-50 text-3xl">
                                        🏞️
                                    </div>

                                    <h3 class="mt-4 text-2xl font-black text-black">
                                        Choose a theme and buy a background
                                    </h3>

                                    <p class="mt-2 text-sm leading-7 text-black/55">
                                        First select a theme. Then buy the habitat background so your avatar has a home.
                                    </p>

                                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                                        <button type="button" @click="themeChooserOpen = true"
                                            class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-bold text-white">
                                            Select Theme
                                        </button>

                                        <button type="button" @click="openEditFor('background')"
                                            class="rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-bold text-black/70">
                                            Buy Background
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-for="asset in visibleSceneAssets" :key="asset.id">
                            <div class="habitant-item"
                                :class="[itemClass(asset), selectedAssetId === asset.id ? 'selected' : '']"
                                :data-asset-id="asset.id" :style="itemStyle(asset)" @click.stop="selectSceneAsset(asset)"
                                @pointerdown="startDrag($event, asset)">
                                <img :src="imageForAsset(asset)" :alt="asset.name">
                            </div>
                        </template>

                        <div x-show="speech.show" x-cloak class="habitant-speech" :class="{ 'show': speech.show }"
                            :style="`left:${speech.x}%; top:${speech.y}%;`" x-text="speech.text"></div>

                        <div class="habitant-effects-layer">
                            <template x-for="effect in effects" :key="effect.id">
                                <div class="habitant-effect" :class="effect.type" :style="effect.style" x-text="effect.text">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="editMode" x-cloak class="edit-left-panel">
                        <button type="button" class="edit-tab" :class="{ 'active': activeTab === 'background' }"
                            @click="activeTab = 'background'">
                            <span>🏞️</span>
                            BG
                        </button>

                        <button type="button" class="edit-tab" :class="{ 'active': activeTab === 'avatar' }"
                            @click="activeTab = 'avatar'">
                            <span>🦁</span>
                            Avatar
                        </button>

                        <button type="button" class="edit-tab" :class="{ 'active': activeTab === 'food' }"
                            @click="activeTab = 'food'">
                            <span>🍖</span>
                            Food
                        </button>

                        <button type="button" class="edit-tab" :class="{ 'active': activeTab === 'toy' }"
                            @click="activeTab = 'toy'">
                            <span>⚽</span>
                            Toy
                        </button>

                        <button type="button" class="edit-tab" :class="{ 'active': activeTab === 'decoration' }"
                            @click="activeTab = 'decoration'">
                            <span>🌸</span>
                            Decor
                        </button>
                    </div>

                    <div x-show="editMode" x-cloak class="edit-right-panel">
                        <div class="mb-4">
                            <h3 class="text-lg font-black text-black" x-text="tabTitle"></h3>
                            <p class="mt-1 text-xs leading-5 text-black/50" x-text="tabHelp"></p>
                        </div>

                        <div class="space-y-3">
                            <template x-for="asset in tabAssets" :key="asset.id">
                                <div class="asset-card" :id="`shop-asset-${asset.type}`">
                                    <div class="asset-card-image">
                                        <img :src="asset.image_url" :alt="asset.name">
                                    </div>

                                    <div class="mt-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="rounded-full px-3 py-1 text-[11px] font-black"
                                                :class="asset.owned ? 'bg-green-50 text-green-700' : 'bg-[#fff7fc] text-[#CB148B]'"
                                                x-text="asset.owned ? 'Owned' : asset.type"></span>

                                            <span class="text-xs font-black text-amber-700">
                                                🪙 <span x-text="asset.price_coins"></span>
                                            </span>
                                        </div>

                                        <h4 class="mt-2 text-sm font-black text-black" x-text="asset.name"></h4>

                                        <p class="mt-1 text-xs leading-5 text-black/50"
                                            x-text="asset.description || 'Cute habitat item'"></p>

                                        <button type="button" class="asset-btn mt-3" :class="asset.owned ? 'owned' : 'buy'"
                                            @click="asset.owned ? activateAsset(asset) : purchaseAsset(asset)"
                                            x-text="asset.owned ? buttonTextForOwned(asset) : `Buy for ${asset.price_coins} coins`"></button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="tabAssets.length === 0">
                                <div
                                    class="rounded-2xl border border-dashed border-black/10 p-4 text-center text-sm text-black/45">
                                    No item uploaded for this tab yet.
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="editMode && selectedSceneAsset" x-cloak class="edit-bottom-panel">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-black text-black" x-text="selectedSceneAsset?.name"></p>
                                <p class="mt-1 text-xs text-black/45">
                                    Move with drag. Use buttons for size, rotation, and direction.
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="habitant-action" @click="changeScale(-0.1)">➖ Size</button>
                                <button type="button" class="habitant-action" @click="changeScale(0.1)">➕ Size</button>
                                <button type="button" class="habitant-action" @click="rotateSelected(-15)">↺</button>
                                <button type="button" class="habitant-action" @click="rotateSelected(15)">↻</button>
                                <button type="button" class="habitant-action" @click="flipSelected()">↔ Flip</button>
                                <button type="button" class="habitant-action primary" @click="saveLayout()">💾 Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="habitant-actions">
                        <button type="button" class="habitant-action primary" @click="feedAvatar()" :disabled="isBusy">
                            🍖 Feed
                        </button>

                        <button type="button" class="habitant-action" @click="playWithAvatar()" :disabled="isBusy">
                            ⚽ Play
                        </button>

                        <button type="button" class="habitant-action" @click="petAvatar()" :disabled="isBusy">
                            💖 Pet
                        </button>

                        <button type="button" class="habitant-action" @click="cheerAvatar()" :disabled="isBusy">
                            ✨ Cheer
                        </button>

                        <button type="button" class="habitant-action" @click="themeChooserOpen = true" :disabled="isBusy">
                            🌈 Theme
                        </button>

                        <button type="button" class="habitant-action" @click="editMode ? saveLayout() : toggleEditMode()"
                            :disabled="isBusy">
                            <span x-text="editMode ? '✅ Save & Close' : '✏️ Edit'"></span>
                        </button>

                        <button x-show="editMode" x-cloak type="button" class="habitant-action" @click="saveLayout()"
                            :disabled="isBusy">
                            💾 Save
                        </button>
                    </div>
                </div>
            </section>

            <div x-show="themeChooserOpen" x-cloak class="theme-modal">
                <div class="theme-modal-card">
                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <h2 class="text-2xl font-black text-black">Choose Your Theme</h2>

                            <p class="mt-2 text-sm leading-7 text-black/55">
                                Select a theme first. If you already use a theme, it will show as your current theme.
                            </p>
                        </div>

                        <button type="button" @click="themeChooserOpen = false"
                            class="rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-bold text-black/60">
                            Close
                        </button>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        <template x-for="theme in themes" :key="theme.id">
                            <button type="button" class="theme-card text-left"
                                :class="{ 'active': Number(theme.id) === Number(selectedThemeId) }" @click="selectTheme(theme)">
                                <div class="theme-thumb">
                                    <template x-if="theme.thumbnail_url">
                                        <img :src="theme.thumbnail_url" :alt="theme.name">
                                    </template>

                                    <template x-if="!theme.thumbnail_url">
                                        <div class="text-5xl">🌈</div>
                                    </template>
                                </div>

                                <div class="mt-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <h3 class="text-lg font-black text-black" x-text="theme.name"></h3>

                                        <span x-show="Number(theme.id) === Number(selectedThemeId)"
                                            class="rounded-full bg-green-50 px-3 py-1 text-[11px] font-black text-green-700">
                                            Current
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm leading-6 text-black/55"
                                        x-text="theme.description || 'Cute habitant theme'"></p>

                                    <p class="mt-3 text-xs font-black text-[#620A88]">
                                        Tap to use this theme
                                    </p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="guide.visible" x-cloak class="guide-bubble">
                <div class="flex gap-4">
                    <div class="guide-mascot">🦁</div>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-black text-black" x-text="guide.title"></h3>

                        <p class="mt-2 text-sm leading-7 text-black/60" x-text="guide.text"></p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button" @click="nextGuide()"
                                class="rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-4 py-2 text-xs font-black text-white">
                                Next
                            </button>

                            <button type="button" @click="finishGuide()"
                                class="rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-black text-black/60">
                                Skip
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toast-cute" :class="{ 'show': toast.show }" x-text="toast.text"></div>
        @endif
    </div>

    <script>
        function habitantGame(config) {
            return {
                csrf: config.csrf,
                routes: config.routes,

                coins: Number(config.childCoins || 0),
                themes: config.themes || [],
                selectedThemeId: Number(config.selectedThemeId || 0),

                hunger: Number(config.habitat?.hunger || 35),
                happiness: Number(config.habitat?.happiness || 70),
                activeBackgroundId: config.habitat?.activeBackgroundId || null,
                activeAvatarId: config.habitat?.activeAvatarId || null,
                isSad: Boolean(config.habitat?.isSad),

                audioMuted: false,
                selectedVoice: null,
                voicesLoaded: false,

                avatarMode: 'idle',
                editMode: false,
                dragging: null,
                activeTab: 'background',
                selectedAssetId: null,

                foodVisible: false,
                feedingFoodAssetId: null,
                isFeeding: false,

                playingToyAssetId: null,
                isPlaying: false,

                isPetting: false,
                isCheering: false,
                partyMode: false,

                runtimeAvatarLayout: null,
                runtimeToyLayout: null,

                effects: [],
                effectCounter: 1,

                ambientParticles: [],

                themeChooserOpen: false,

                speech: {
                    show: false,
                    text: '',
                    x: 50,
                    y: 42,
                },

                toast: {
                    show: false,
                    text: '',
                },

                guide: {
                    visible: false,
                    index: 0,
                    completed: Boolean(config.habitat?.guideCompleted),
                    title: 'Welcome to My Habitant',
                    text: 'First select a theme. Then buy the background.',
                    steps: [
                        {
                            title: 'Step 1: Pick a magical theme',
                            text: 'First, choose a beautiful world for your little friend.',
                            action: 'theme',
                            speechType: 'guideTheme',
                        },
                        {
                            title: 'Step 2: Buy a cozy background',
                            text: 'A pretty background makes the habitat feel like home.',
                            action: 'background',
                            speechType: 'guideBackground',
                        },
                        {
                            title: 'Step 3: Choose your buddy',
                            text: 'Buy the avatar bundle so your friend can walk, eat, play, and smile.',
                            action: 'avatar',
                            speechType: 'guideAvatar',
                        },
                        {
                            title: 'Step 4: Food and happy tummy',
                            text: 'Buy food, then tap Feed when your little friend gets hungry.',
                            action: 'food',
                            speechType: 'guideFood',
                        },
                        {
                            title: 'Step 5: Decorate the dream home',
                            text: 'Use decorations, drag them around, resize them, and save your cute layout.',
                            action: 'decoration',
                            speechType: 'guideDecor',
                        },
                    ],
                },

                get isBusy() {
                    return this.isFeeding || this.isPlaying || this.isPetting || this.isCheering;
                },

                init() {
                    this.audioMuted = localStorage.getItem('habitant_audio_muted') === '1';
                    this.loadBestFemaleVoice();
                    this.makeAmbientParticles();

                    if (!this.selectedThemeId && this.themes.length > 0) {
                        this.selectedThemeId = this.themes[0].id;
                        this.themeChooserOpen = true;
                    }

                    this.normalizeLayouts();
                    // TEST ONLY: force hungry mood
                    // this.hunger = 5;
                    // this.happiness = 25;
                    // this.isSad = true;
                    // this.avatarMode = 'sad';

                    // setTimeout(() => {
                    //     this.sayCute('hungry', true);
                    //     this.createEffects('pop', 50, 45, 1, 'Hungry');
                    // }, 1000);

                    if (!this.activeBackground && !this.activeAvatar) {
                        this.themeChooserOpen = true;
                    }

                    if (this.isSad) {
                        this.avatarMode = 'sad';

                        setTimeout(() => {
                            this.sayCute('hungry', false);
                        }, 800);
                    } else {
                        setTimeout(() => {
                            this.sayCute('welcome', false);
                        }, 600);
                    }

                    if (!this.guide.completed) {
                        setTimeout(() => {
                            this.openGuide();
                        }, 900);
                    }

                    this.startJoyLoop();
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

                get activeFoodAsset() {
                    if (this.feedingFoodAssetId) {
                        return this.selectedThemeAssets.find(asset =>
                            asset.type === 'food' &&
                            asset.owned &&
                            Number(asset.id) === Number(this.feedingFoodAssetId)
                        ) || null;
                    }

                    return this.selectedThemeAssets.find(asset => asset.type === 'food' && asset.owned) || null;
                },

                get activeToyAsset() {
                    if (this.playingToyAssetId) {
                        return this.selectedThemeAssets.find(asset =>
                            asset.type === 'toy' &&
                            asset.owned &&
                            Number(asset.id) === Number(this.playingToyAssetId)
                        ) || null;
                    }

                    return this.selectedThemeAssets.find(asset => asset.type === 'toy' && asset.owned) || null;
                },

                get tabAssets() {
                    return this.selectedThemeAssets.filter(asset => asset.type === this.activeTab);
                },

                get tabTitle() {
                    const titles = {
                        background: 'Background',
                        avatar: 'Avatar Bundle',
                        food: 'Food',
                        toy: 'Toys',
                        decoration: 'Decorations',
                    };

                    return titles[this.activeTab] || 'Items';
                },

                get tabHelp() {
                    const help = {
                        background: 'Buy or switch the habitat background.',
                        avatar: 'Buy one avatar bundle. It contains idle, walking, eating, and sad images.',
                        food: 'Buy food so you can feed your avatar.',
                        toy: 'Buy toys so the avatar can play.',
                        decoration: 'Buy decorations, then drag them in edit mode.',
                    };

                    return help[this.activeTab] || '';
                },

                get selectedSceneAsset() {
                    return this.selectedThemeAssets.find(asset => Number(asset.id) === Number(this.selectedAssetId)) || null;
                },

                get visibleSceneAssets() {
                    return this.selectedThemeAssets.filter(asset => {
                        if (!asset.owned) {
                            return false;
                        }

                        if (asset.type === 'background') {
                            return false;
                        }

                        if (asset.type === 'avatar') {
                            return Number(asset.id) === Number(this.activeAvatarId);
                        }

                        if (asset.type === 'food') {
                            if (this.editMode) {
                                return true;
                            }

                            if (this.foodVisible && this.feedingFoodAssetId) {
                                return Number(asset.id) === Number(this.feedingFoodAssetId);
                            }

                            return false;
                        }

                        if (asset.type === 'toy') {
                            if (this.editMode) {
                                return true;
                            }

                            if (this.isPlaying && this.playingToyAssetId) {
                                return Number(asset.id) === Number(this.playingToyAssetId);
                            }

                            return asset.layout?.is_visible !== false;
                        }

                        return asset.layout?.is_visible !== false;
                    });
                },

                normalizeLayouts() {
                    this.themes.forEach(theme => {
                        theme.assets.forEach(asset => {
                            if (!asset.layout) {
                                asset.layout = {
                                    x: Number(asset.default_x || 50),
                                    y: Number(asset.default_y || 70),
                                    scale: Number(asset.default_scale || 1),
                                    rotation: Number(asset.default_rotation || 0),
                                    direction: asset.default_direction || 'right',
                                    z_index: Number(asset.default_z_index || 10),
                                    is_visible: true,
                                };
                            }
                        });
                    });
                },

                makeAmbientParticles() {
                    const particles = [];

                    for (let i = 0; i < 30; i++) {
                        const isBubble = i % 5 === 0;
                        const size = isBubble ? this.random(14, 30) : this.random(4, 9);
                        const duration = isBubble ? this.random(7, 12) : this.random(4, 8);

                        particles.push({
                            id: i + 1,
                            className: isBubble ? 'magic-bubble' : 'magic-dot',
                            style: [
                                `left:${this.random(2, 98)}%`,
                                `top:${this.random(8, 92)}%`,
                                `width:${size}px`,
                                `height:${size}px`,
                                `animation-delay:${this.random(0, 6000)}ms`,
                                `animation-duration:${duration}s`,
                            ].join(';'),
                        });
                    }

                    this.ambientParticles = particles;
                },

                startJoyLoop() {
                    setInterval(() => {
                        if (this.isBusy || this.editMode || !this.activeAvatar) {
                            return;
                        }

                        const layout = this.getRenderLayout(this.activeAvatar);

                        if (Math.random() > 0.45) {
                            this.createEffects('star', layout.x, layout.y - 18, 3);
                        }

                        if (Math.random() > 0.72) {
                            this.createEffects('heart', layout.x, layout.y - 12, 2);
                        }

                        if (Math.random() > 0.74) {
                            this.sayCute('idleJoy', false);
                        }
                    }, 15000);
                },

                toggleAudio() {
                    this.audioMuted = !this.audioMuted;

                    localStorage.setItem('habitant_audio_muted', this.audioMuted ? '1' : '0');

                    if (this.audioMuted) {
                        this.stopVoice();
                        this.toastMessage('Sound muted.');
                        return;
                    }

                    this.loadBestFemaleVoice();
                    this.toastMessage('Sound is back.');
                    this.soundGuide();

                    setTimeout(() => {
                        this.say('There we go. My voice is back now.', true);
                    }, 160);
                },

                selectTheme(theme) {
                    if (Number(theme.id) === Number(this.selectedThemeId)) {
                        this.themeChooserOpen = false;
                        return;
                    }

                    const url = new URL(window.location.href);
                    url.searchParams.set('theme', theme.slug);
                    window.location.href = url.toString();
                },

                openEditFor(type) {
                    this.editMode = true;
                    this.activeTab = type;
                    this.themeChooserOpen = false;
                },

                getRenderLayout(asset) {
                    if (asset.type === 'avatar' && Number(asset.id) === Number(this.activeAvatarId) && this.runtimeAvatarLayout) {
                        return {
                            ...asset.layout,
                            ...this.runtimeAvatarLayout,
                        };
                    }

                    if (asset.type === 'toy' && Number(asset.id) === Number(this.playingToyAssetId) && this.runtimeToyLayout) {
                        return {
                            ...asset.layout,
                            ...this.runtimeToyLayout,
                        };
                    }

                    return asset.layout;
                },

                itemClass(asset) {
                    return {
                        'habitant-avatar': asset.type === 'avatar',
                        'habitant-decoration': asset.type === 'decoration',
                        'habitant-food': asset.type === 'food',
                        'habitant-toy': asset.type === 'toy',

                        'idle': asset.type === 'avatar' && this.avatarMode === 'idle',
                        'walking': asset.type === 'avatar' && this.avatarMode === 'walking',
                        'eating': asset.type === 'avatar' && this.avatarMode === 'eating',
                        'happy': asset.type === 'avatar' && this.avatarMode === 'happy',
                        'sad': asset.type === 'avatar' && this.avatarMode === 'sad',
                        'playing': asset.type === 'avatar' && this.avatarMode === 'playing',
                        'petting': asset.type === 'avatar' && this.avatarMode === 'petting',
                        'dancing': asset.type === 'avatar' && this.avatarMode === 'dancing',
                        'celebrating': asset.type === 'avatar' && this.avatarMode === 'celebrating',
                        'aura': asset.type === 'avatar' && ['happy', 'playing', 'petting', 'dancing', 'celebrating'].includes(this.avatarMode),

                        'playing-toy': asset.type === 'toy' && Number(asset.id) === Number(this.playingToyAssetId) && this.isPlaying,
                    };
                },

                itemStyle(asset) {
                    const layout = this.getRenderLayout(asset);

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
                        `left:${layout.x}%`,
                        `top:${layout.y}%`,
                        `width:${width}px`,
                        `z-index:${layout.z_index}`,
                        `transform:translate(-50%, -50%) rotate(${layout.rotation}deg) scaleX(${flip})`,
                    ].join(';');
                },

                imageForAsset(asset) {
                    if (asset.type !== 'avatar') {
                        return asset.image_url;
                    }

                    if (this.avatarMode === 'walking') {
                        return asset.walking_image_url || asset.image_url;
                    }

                    if (this.avatarMode === 'eating') {
                        return asset.eating_image_url || asset.image_url;
                    }

                    if (this.avatarMode === 'sad') {
                        return asset.sad_image_url || asset.image_url;
                    }

                    return asset.image_url;
                },

                buttonTextForOwned(asset) {
                    if (asset.type === 'background') {
                        return Number(asset.id) === Number(this.activeBackgroundId) ? 'Using Background' : 'Use Background';
                    }

                    if (asset.type === 'avatar') {
                        return Number(asset.id) === Number(this.activeAvatarId) ? 'Using Avatar' : 'Use Avatar';
                    }

                    return asset.layout?.is_visible === false ? 'Show Item' : 'Add / Use Item';
                },

                async purchaseAsset(asset) {
                    const response = await this.postJson(`${this.routes.purchaseBase}/${asset.id}/purchase`, {});

                    if (!response.success) {
                        this.toastMessage(response.message || 'Could not purchase item.');
                        this.soundError();
                        return;
                    }

                    asset.owned = true;
                    this.coins = Number(response.coin_balance || this.coins);

                    if (asset.type === 'background') {
                        this.activeBackgroundId = asset.id;
                        this.activeTab = 'avatar';
                    }

                    if (asset.type === 'avatar') {
                        this.activeAvatarId = asset.id;
                        this.avatarMode = this.isSad ? 'sad' : 'idle';
                        this.selectedAssetId = asset.id;
                        this.activeTab = 'food';
                    }

                    asset.layout.is_visible = true;

                    this.toastMessage(response.message || 'Purchased successfully.');
                    this.soundSuccess();

                    if (asset.type === 'background') {
                        this.sayCute('purchaseBackground', true);
                    } else if (asset.type === 'avatar') {
                        this.sayCute('purchaseAvatar', true);
                    } else if (asset.type === 'food') {
                        this.sayCute('purchaseFood', true);
                    } else if (asset.type === 'toy') {
                        this.sayCute('purchaseToy', true);
                    } else if (asset.type === 'decoration') {
                        this.sayCute('purchaseDecoration', true);
                    }

                    this.partyBurst(50, 44);
                },

                async activateAsset(asset) {
                    const response = await this.postJson(`${this.routes.purchaseBase}/${asset.id}/activate`, {});

                    if (!response.success) {
                        this.toastMessage(response.message || 'Could not activate item.');
                        this.soundError();
                        return;
                    }

                    if (asset.type === 'background') {
                        this.activeBackgroundId = asset.id;
                    }

                    if (asset.type === 'avatar') {
                        this.activeAvatarId = asset.id;
                        this.avatarMode = this.isSad ? 'sad' : 'idle';
                    }

                    asset.layout.is_visible = true;
                    this.selectedAssetId = asset.id;

                    this.toastMessage('Item is ready.');
                    this.sayCute('activated', true);
                    this.soundSuccess();
                    this.createEffects('ring', asset.layout.x || 50, asset.layout.y || 50, 1);
                },

                selectSceneAsset(asset) {
                    if (this.editMode) {
                        this.selectedAssetId = asset.id;
                        return;
                    }

                    if (asset.type === 'avatar' && Number(asset.id) === Number(this.activeAvatarId)) {
                        this.petAvatar();
                    }
                },

                toggleEditMode() {
                    if (this.isBusy) {
                        return;
                    }

                    this.editMode = !this.editMode;

                    if (this.editMode) {
                        if (!this.activeBackground) {
                            this.activeTab = 'background';
                        } else if (!this.activeAvatar) {
                            this.activeTab = 'avatar';
                        }

                        this.sayCute('editOn', true);
                    } else {
                        this.selectedAssetId = null;
                        this.sayCute('editOff', true);
                    }
                },

                async saveLayout() {
                    /*
                     * Important:
                     * Do not use visibleSceneAssets here only.
                     * visibleSceneAssets changes depending on editMode, foodVisible, isPlaying, etc.
                     * For saving, we should save all owned non-background items of the selected theme.
                     */
                    const items = this.selectedThemeAssets
                        .filter(asset => asset.owned && asset.type !== 'background' && asset.layout)
                        .map(asset => ({
                            asset_id: asset.id,
                            x: asset.layout.x,
                            y: asset.layout.y,
                            scale: asset.layout.scale,
                            rotation: asset.layout.rotation,
                            direction: asset.layout.direction || 'right',
                            z_index: asset.layout.z_index || 10,
                            is_visible: asset.layout.is_visible !== false,
                        }));

                    const response = await this.postJson(this.routes.saveLayout, {
                        theme_id: this.selectedThemeId,
                        items,
                    });

                    if (!response.success) {
                        this.toastMessage(response.message || 'Layout save failed.');
                        this.soundError();
                        return;
                    }

                    /*
                     * This is the fix.
                     * After successful save, immediately close edit mode
                     * so left panel, right panel, outlines, and bottom editor vanish.
                     */
                    this.editMode = false;
                    this.selectedAssetId = null;
                    this.dragging = null;

                    this.toastMessage('Habitat saved.');
                    this.sayCute('saved', true);
                    this.soundSuccess();
                    this.partyBurst(50, 42);
                },
                async feedAvatar() {
                    if (this.isBusy) {
                        return;
                    }

                    if (!this.activeAvatar) {
                        this.sayCute('needAvatar', true);
                        this.openEditFor('avatar');
                        return;
                    }

                    const food = this.activeFoodAsset;

                    if (!food) {
                        this.sayCute('needFood', true);
                        this.openEditFor('food');
                        return;
                    }

                    this.isFeeding = true;
                    this.editMode = false;
                    this.selectedAssetId = null;

                    const avatar = this.activeAvatar;
                    const home = this.cloneLayout(avatar.layout);
                    const foodLayout = this.cloneLayout(food.layout);

                    const goDirection = foodLayout.x >= home.x ? 'right' : 'left';

                    const eatingSpot = {
                        ...home,
                        x: this.clamp(foodLayout.x + (goDirection === 'right' ? -8 : 8), 5, 95),
                        y: this.clamp(foodLayout.y - 1, 12, 90),
                        direction: goDirection,
                    };

                    const returnDirection = home.x <= eatingSpot.x ? 'left' : 'right';

                    this.feedingFoodAssetId = food.id;
                    this.foodVisible = true;

                    this.runtimeAvatarLayout = {
                        ...home,
                        direction: goDirection,
                    };

                    this.avatarMode = 'walking';
                    this.sayCute('foodStart', true);
                    this.soundGuide();

                    await this.wait(250);
                    await this.animateAvatarTo(home, eatingSpot, 3800);

                    this.avatarMode = 'eating';
                    this.sayCute('eating', true);
                    this.createEffects('heart', foodLayout.x, foodLayout.y - 6, 9);
                    this.createEffects('star', foodLayout.x, foodLayout.y - 9, 8);
                    this.createEffects('pop', foodLayout.x, foodLayout.y - 14, 1, '+ Yum');
                    this.soundNibble();

                    await this.wait(1800);

                    const response = await this.postJson(this.routes.feed, {
                        theme_id: this.selectedThemeId,
                    });

                    if (!response.success) {
                        this.toastMessage(response.message || 'Could not feed.');
                        this.soundError();

                        this.avatarMode = this.isSad ? 'sad' : 'idle';
                        this.foodVisible = false;
                        this.feedingFoodAssetId = null;
                        this.runtimeAvatarLayout = null;
                        this.isFeeding = false;

                        return;
                    }

                    this.hunger = Number(response.hunger || 100);
                    this.happiness = Number(response.happiness || this.happiness);
                    this.isSad = false;

                    this.avatarMode = 'happy';
                    this.sayCute('afterFood', true);
                    this.partyBurst(eatingSpot.x, eatingSpot.y - 10);
                    this.soundSuccess();

                    await this.wait(1250);

                    const returnStart = {
                        ...eatingSpot,
                        direction: returnDirection,
                    };

                    const returnHome = {
                        ...home,
                        direction: returnDirection,
                    };

                    this.avatarMode = 'walking';
                    this.sayCute('foodReturn', false);

                    await this.animateAvatarTo(returnStart, returnHome, 3600);

                    this.avatarMode = 'idle';

                    this.runtimeAvatarLayout = {
                        ...home,
                        direction: home.direction || 'right',
                    };

                    await this.wait(250);

                    this.runtimeAvatarLayout = null;
                    this.foodVisible = false;
                    this.feedingFoodAssetId = null;
                    this.isFeeding = false;

                    this.toastMessage('Fed successfully.');
                },

                async playWithAvatar() {
                    if (this.isBusy) {
                        return;
                    }

                    if (!this.activeAvatar) {
                        this.sayCute('needAvatar', true);
                        this.openEditFor('avatar');
                        return;
                    }

                    const toy = this.activeToyAsset;

                    if (!toy) {
                        this.sayCute('needToy', true);
                        this.openEditFor('toy');
                        return;
                    }

                    this.isPlaying = true;
                    this.editMode = false;
                    this.selectedAssetId = null;
                    this.partyMode = true;

                    const avatar = this.activeAvatar;
                    const home = this.cloneLayout(avatar.layout);
                    const toyHome = this.cloneLayout(toy.layout);

                    const goDirection = toyHome.x >= home.x ? 'right' : 'left';

                    const playSpot = {
                        ...home,
                        x: this.clamp(toyHome.x + (goDirection === 'right' ? -7 : 7), 7, 93),
                        y: this.clamp(toyHome.y - 2, 16, 88),
                        direction: goDirection,
                    };

                    const returnDirection = home.x <= playSpot.x ? 'left' : 'right';

                    this.playingToyAssetId = toy.id;

                    this.runtimeAvatarLayout = {
                        ...home,
                        direction: goDirection,
                    };

                    this.runtimeToyLayout = {
                        ...toyHome,
                        z_index: Math.max(Number(toyHome.z_index || 40), Number(home.z_index || 50) + 6),
                    };

                    this.avatarMode = 'walking';
                    this.sayCute('playStart', true);
                    this.soundGuide();

                    await this.wait(220);
                    await this.animateAvatarTo(home, playSpot, 3300);

                    this.avatarMode = 'playing';
                    this.sayCute('playing', true);
                    this.soundPlayfulStart();

                    await this.animateToyAroundAvatar(toy, playSpot, 5600);

                    const response = await this.postJson(this.routes.play, {
                        theme_id: this.selectedThemeId,
                    });

                    if (response.success) {
                        this.hunger = Number(response.hunger || this.hunger);
                        this.happiness = Number(response.happiness || this.happiness);
                        this.isSad = Boolean(response.is_sad);
                    }

                    this.avatarMode = 'celebrating';
                    this.sayCute('afterPlay', true);
                    this.partyBurst(playSpot.x, playSpot.y - 14);
                    this.soundSuccess();

                    await this.wait(1100);

                    this.avatarMode = 'walking';

                    const returnStart = {
                        ...playSpot,
                        direction: returnDirection,
                    };

                    const returnHome = {
                        ...home,
                        direction: returnDirection,
                    };

                    this.runtimeToyLayout = {
                        ...toyHome,
                        direction: toyHome.direction || 'right',
                    };

                    await this.animateAvatarTo(returnStart, returnHome, 3300);

                    this.avatarMode = this.isSad ? 'sad' : 'idle';

                    this.runtimeAvatarLayout = {
                        ...home,
                        direction: home.direction || 'right',
                    };

                    await this.wait(250);

                    this.runtimeAvatarLayout = null;
                    this.runtimeToyLayout = null;
                    this.playingToyAssetId = null;
                    this.isPlaying = false;
                    this.partyMode = false;

                    this.toastMessage(response.message || 'Played successfully.');
                },

                async petAvatar() {
                    if (this.isBusy || this.editMode) {
                        return;
                    }

                    if (!this.activeAvatar) {
                        this.sayCute('needAvatar', true);
                        this.openEditFor('avatar');
                        return;
                    }

                    this.isPetting = true;
                    const avatar = this.activeAvatar;
                    const layout = this.cloneLayout(avatar.layout);

                    this.avatarMode = 'petting';
                    this.sayCute('pet', true);
                    this.createEffects('heart', layout.x, layout.y - 14, 10);
                    this.createEffects('ring', layout.x, layout.y - 5, 2);
                    this.createEffects('pop', layout.x, layout.y - 23, 1, '+ Love');
                    this.soundSuccess();

                    await this.wait(2200);

                    this.avatarMode = this.isSad ? 'sad' : 'idle';
                    this.isPetting = false;
                },

                async cheerAvatar() {
                    if (this.isBusy || this.editMode) {
                        return;
                    }

                    if (!this.activeAvatar) {
                        this.sayCute('needAvatar', true);
                        this.openEditFor('avatar');
                        return;
                    }

                    this.isCheering = true;
                    this.partyMode = true;

                    const avatar = this.activeAvatar;
                    const layout = this.cloneLayout(avatar.layout);

                    this.avatarMode = 'dancing';
                    this.sayCute('cheer', true);
                    this.createEffects('note', layout.x - 4, layout.y - 18, 6);
                    this.createEffects('note', layout.x + 5, layout.y - 15, 6);
                    this.createEffects('star', layout.x, layout.y - 18, 12);
                    this.createEffects('confetti', layout.x, layout.y - 15, 18);
                    this.soundPlayfulStart();

                    await this.wait(3200);

                    this.avatarMode = 'celebrating';
                    this.sayCute('afterCheer', true);
                    this.partyBurst(layout.x, layout.y - 14);

                    await this.wait(1200);

                    this.avatarMode = this.isSad ? 'sad' : 'idle';
                    this.isCheering = false;
                    this.partyMode = false;
                },

                async animateAvatarTo(fromLayout, toLayout, duration = 3000) {
                    const startTime = performance.now();

                    const from = this.cloneLayout(fromLayout);
                    const to = this.cloneLayout(toLayout);

                    this.runtimeAvatarLayout = {
                        ...from,
                        direction: to.direction || from.direction || 'right',
                    };

                    return new Promise(resolve => {
                        const step = (now) => {
                            const progress = Math.min((now - startTime) / duration, 1);

                            const eased = progress < 0.5
                                ? 4 * progress * progress * progress
                                : 1 - Math.pow(-2 * progress + 2, 3) / 2;

                            const walkBounce = Math.sin(progress * Math.PI * 18) * -0.9;

                            const currentX = Number((from.x + (to.x - from.x) * eased).toFixed(2));
                            const currentY = Number((from.y + (to.y - from.y) * eased + walkBounce).toFixed(2));

                            this.runtimeAvatarLayout = {
                                ...from,
                                x: currentX,
                                y: currentY,
                                scale: from.scale,
                                rotation: from.rotation,
                                direction: to.direction || from.direction || 'right',
                                z_index: from.z_index,
                                is_visible: true,
                            };

                            if (Math.random() > 0.84) {
                                this.createEffects('paw', currentX, currentY + 11, 1);
                            }

                            if (Math.random() > 0.9) {
                                this.createEffects('star', currentX, currentY - 17, 1);
                            }

                            if (progress < 1) {
                                requestAnimationFrame(step);
                            } else {
                                this.runtimeAvatarLayout = {
                                    ...to,
                                    direction: to.direction || from.direction || 'right',
                                };

                                resolve();
                            }
                        };

                        requestAnimationFrame(step);
                    });
                },

                async animateToyAroundAvatar(toy, center, duration = 5600) {
                    const start = performance.now();
                    const radiusX = 11.5;
                    const radiusY = 7.5;

                    return new Promise(resolve => {
                        const step = (now) => {
                            const elapsed = now - start;
                            const progress = Math.min(elapsed / duration, 1);
                            const angle = progress * Math.PI * 2 * 4.8;
                            const jump = Math.sin(progress * Math.PI * 18) * -3;

                            this.runtimeToyLayout = {
                                ...toy.layout,
                                x: this.clamp(center.x + Math.cos(angle) * radiusX, 5, 95),
                                y: this.clamp(center.y - 8 + Math.sin(angle) * radiusY + jump, 10, 90),
                                scale: Number(toy.layout.scale || 1),
                                rotation: Number(toy.layout.rotation || 0) + progress * 1600,
                                direction: toy.layout.direction || 'right',
                                z_index: Number(center.z_index || 50) + (Math.sin(angle) > 0 ? 8 : -2),
                                is_visible: true,
                            };

                            const jumpAvatar = Math.sin(progress * Math.PI * 15) * -2.7;

                            this.runtimeAvatarLayout = {
                                ...center,
                                y: this.clamp(center.y + jumpAvatar, 12, 90),
                                direction: center.direction,
                            };

                            if (Math.random() > 0.78) {
                                this.createEffects('star', this.runtimeToyLayout.x, this.runtimeToyLayout.y - 4, 1);
                            }

                            if (Math.random() > 0.87) {
                                this.createEffects('note', center.x + this.random(-5, 5), center.y - 18, 1);
                            }

                            if (Math.random() > 0.9) {
                                this.createEffects('heart', center.x, center.y - 15, 1);
                            }

                            if (progress < 1) {
                                requestAnimationFrame(step);
                            } else {
                                resolve();
                            }
                        };

                        requestAnimationFrame(step);
                    });
                },

                startDrag(event, asset) {
                    if (!this.editMode || this.isBusy || asset.type === 'background') {
                        return;
                    }

                    event.preventDefault();
                    this.selectedAssetId = asset.id;

                    const scene = document.getElementById('habitantScene');
                    const rect = scene.getBoundingClientRect();

                    this.dragging = {
                        asset,
                        rect,
                    };

                    const move = (moveEvent) => {
                        if (!this.dragging) {
                            return;
                        }

                        let x = ((moveEvent.clientX - rect.left) / rect.width) * 100;
                        let y = ((moveEvent.clientY - rect.top) / rect.height) * 100;

                        x = this.clamp(x, 4, 96);
                        y = this.clamp(y, 10, 92);

                        asset.layout.x = Number(x.toFixed(2));
                        asset.layout.y = Number(y.toFixed(2));
                    };

                    const up = () => {
                        this.dragging = null;
                        window.removeEventListener('pointermove', move);
                        window.removeEventListener('pointerup', up);
                    };

                    window.addEventListener('pointermove', move);
                    window.addEventListener('pointerup', up);
                },

                changeScale(amount) {
                    if (!this.selectedSceneAsset || this.isBusy) {
                        return;
                    }

                    const current = Number(this.selectedSceneAsset.layout.scale || 1);
                    this.selectedSceneAsset.layout.scale = this.clamp(Number((current + amount).toFixed(2)), 0.2, 3);
                },

                rotateSelected(amount) {
                    if (!this.selectedSceneAsset || this.isBusy) {
                        return;
                    }

                    const current = Number(this.selectedSceneAsset.layout.rotation || 0);
                    this.selectedSceneAsset.layout.rotation = current + amount;
                },

                flipSelected() {
                    if (!this.selectedSceneAsset || this.isBusy) {
                        return;
                    }

                    this.selectedSceneAsset.layout.direction = this.selectedSceneAsset.layout.direction === 'left' ? 'right' : 'left';
                },

                openGuide() {
                    this.guide.visible = true;
                    this.applyGuideStep();
                    this.soundGuide();
                },

                nextGuide() {
                    this.guide.index++;

                    if (this.guide.index >= this.guide.steps.length) {
                        this.finishGuide();
                        return;
                    }

                    this.applyGuideStep();
                    this.soundGuide();
                },

                applyGuideStep() {
                    const step = this.guide.steps[this.guide.index];

                    this.guide.title = step.title;
                    this.guide.text = step.text;

                    if (step.action === 'theme') {
                        this.themeChooserOpen = true;
                        return;
                    }

                    this.themeChooserOpen = false;
                    this.openEditFor(step.action);
                },

                async finishGuide() {
                    this.guide.visible = false;
                    this.guide.completed = true;

                    await this.postJson(this.routes.completeGuide, {
                        theme_id: this.selectedThemeId,
                    });
                },

                sayCute(type, voice = true) {
                    const text = this.randomCuteLine(type);
                    this.say(text, voice);
                },

                randomCuteLine(type) {
                    const lines = {
                        welcome: [
                            'There you are. I was hoping you would visit me today.',
                            'Welcome back, my friend. This little place feels warmer when you are here.',
                            'Hi, sweetheart. Let us make this habitat beautiful together.',
                            'I am happy you came. What should we do first?',
                        ],

                        hungry: [
                            'I think my tummy needs a little care. Could you help me with some food?',
                            'I am getting hungry, but I know you will take good care of me.',
                            'A small snack would make me feel much better.',
                            'My tummy is calling. Maybe it is food time.',
                        ],

                        foodStart: [
                            'That looks delicious. I am coming over now.',
                            'Oh, that food looks lovely. Let me come closer.',
                            'Thank you for bringing food. I am on my way.',
                            'Yay, food time. I am coming to enjoy it.',
                        ],

                        eating: [
                            'Mmm, this is really tasty.',
                            'This is perfect. Thank you for feeding me.',
                            'I love this. My tummy is getting happy.',
                            'That tastes so good. You picked the right food.',
                        ],

                        afterFood: [
                            'That helped a lot. I feel happy and cared for.',
                            'Thank you. My tummy is full, and my heart feels happy.',
                            'That was wonderful. I feel so much better now.',
                            'You took good care of me. I feel loved.',
                        ],

                        foodReturn: [
                            'I am going back to my cozy spot now.',
                            'That was lovely. I will rest in my favorite place.',
                            'Back to my little home with a happy tummy.',
                            'I feel good now. Let me settle back in.',
                        ],

                        needAvatar: [
                            'Please choose my avatar first, then I can come live here.',
                            'I need a little body before I can move, play, and smile.',
                            'Pick an avatar for me, and I will be ready.',
                        ],

                        needFood: [
                            'We need food first. Could you buy something tasty for me?',
                            'There is no food yet. Let us get a snack before feeding time.',
                            'My tummy needs food from the shop first.',
                        ],

                        needToy: [
                            'We need a toy first. A ball would make playtime much more fun.',
                            'I would love to play, but we need a toy first.',
                            'Let us buy a toy, then we can have a proper game.',
                        ],

                        playStart: [
                            'Playtime sounds wonderful. I am coming over.',
                            'A little game would make me so happy. I am on my way.',
                            'I am ready to play with you. Let us have fun.',
                            'This is going to be a lovely playtime.',
                        ],

                        playing: [
                            'This is fun. Keep going.',
                            'Round and round we go. I love this.',
                            'That was a good bounce. Let us try again.',
                            'I feel so playful right now.',
                        ],

                        afterPlay: [
                            'That was so much fun. Thank you for playing with me.',
                            'I feel bright and happy after that game.',
                            'You are such a good play buddy.',
                            'That made my whole day better.',
                        ],

                        pet: [
                            'Aww, that feels so sweet. Thank you.',
                            'I like when you check on me like this.',
                            'That little touch made me feel loved.',
                            'You are very gentle. I feel safe with you.',
                        ],

                        cheer: [
                            'Let us make this place sparkle for a moment.',
                            'I feel like dancing. Stay with me.',
                            'This habitat deserves a happy little celebration.',
                            'Come on, let us make this moment magical.',
                        ],

                        afterCheer: [
                            'That was beautiful. I feel full of joy.',
                            'I loved that. This place feels alive now.',
                            'That little celebration made me so happy.',
                        ],

                        editOn: [
                            'Let us decorate my home together.',
                            'I trust your design. Make this place feel special.',
                            'Move things around and make my little world beautiful.',
                            'Decoration time. I am excited to see what you choose.',
                        ],

                        editOff: [
                            'This looks lovely. I like what you did.',
                            'My home feels more personal now.',
                            'That is a cozy setup. Thank you.',
                            'The habitat looks beautiful like this.',
                        ],

                        saved: [
                            'Saved. I will remember this beautiful setup.',
                            'Great, my home is saved just the way you arranged it.',
                            'Perfect. This layout feels cozy and safe.',
                            'Saved successfully. I love this little home.',
                        ],

                        purchaseBackground: [
                            'Now I have a real place to live. It looks beautiful.',
                            'This background makes my world feel warm and cozy.',
                            'What a lovely home. Thank you for choosing it.',
                        ],

                        purchaseAvatar: [
                            'I am here now. Thank you for choosing me.',
                            'Now I can live in this habitat with you.',
                            'You picked me. I am ready for our little adventures.',
                        ],

                        purchaseFood: [
                            'Food is ready now. That will help when I get hungry.',
                            'Good choice. This food will make feeding time special.',
                            'Now we have something tasty for later.',
                        ],

                        purchaseToy: [
                            'That toy looks fun. I cannot wait to play.',
                            'Playtime will be much better with this.',
                            'You picked a lovely toy for me.',
                        ],

                        purchaseDecoration: [
                            'That decoration will make my home prettier.',
                            'Lovely choice. My habitat is getting more beautiful.',
                            'This little item adds so much charm.',
                        ],

                        activated: [
                            'Nice choice. This item looks good here.',
                            'I like this one. It fits the habitat well.',
                            'That feels just right for my home.',
                        ],

                        guideTheme: [
                            'First, choose a theme for my little world.',
                            'Pick the place where I will live and play.',
                        ],

                        guideBackground: [
                            'A background gives my habitat a real home.',
                            'Choose a beautiful background first.',
                        ],

                        guideAvatar: [
                            'Now pick my avatar bundle so I can move and react.',
                            'Choose the friend who will live in this habitat.',
                        ],

                        guideFood: [
                            'Food keeps me happy and cared for.',
                            'When I get hungry, feeding me will help.',
                        ],

                        guideDecor: [
                            'Decorations make the home feel personal.',
                            'Drag, resize, flip, and save your favorite setup.',
                        ],

                        idleJoy: [
                            'This place feels peaceful today.',
                            'I like being here with you.',
                            'Maybe we can add something pretty later.',
                            'This habitat feels more like home every time you visit.',
                            'I am happy just spending time here.',
                        ],
                    };

                    const selectedLines = lines[type] || lines.idleJoy;

                    return selectedLines[this.random(0, selectedLines.length - 1)];
                },

                say(text, voice = false) {
                    const avatar = this.activeAvatar;

                    let x = 50;
                    let y = 42;

                    if (avatar?.layout) {
                        const layout = this.getRenderLayout(avatar);

                        x = layout.x;
                        y = Math.max(15, layout.y - 20);
                    }

                    this.speech = {
                        show: true,
                        text,
                        x,
                        y,
                    };

                    if (voice) {
                        this.browserSpeak(text);
                    }

                    clearTimeout(this.speechTimer);

                    this.speechTimer = setTimeout(() => {
                        this.speech.show = false;
                    }, 3300);
                },

                loadBestFemaleVoice() {
                    if (!('speechSynthesis' in window)) {
                        return;
                    }

                    const applyVoice = () => {
                        const voices = window.speechSynthesis.getVoices() || [];
                        this.selectedVoice = this.pickFemaleVoice(voices);
                        this.voicesLoaded = true;
                    };

                    applyVoice();

                    window.speechSynthesis.onvoiceschanged = () => {
                        applyVoice();
                    };
                },

                pickFemaleVoice(voices) {
                    if (!voices || voices.length === 0) {
                        return null;
                    }

                    const preferred = [
                        'microsoft jenny',
                        'microsoft aria',
                        'microsoft zira',
                        'google uk english female',
                        'google us english',
                        'samantha',
                        'victoria',
                        'serena',
                        'karen',
                        'tessa',
                        'moira',
                        'fiona',
                        'female',
                    ];

                    for (const namePart of preferred) {
                        const voice = voices.find(v => {
                            const name = String(v.name || '').toLowerCase();
                            const lang = String(v.lang || '').toLowerCase();

                            return lang.startsWith('en') && name.includes(namePart);
                        });

                        if (voice) {
                            return voice;
                        }
                    }

                    return voices.find(v => String(v.lang || '').toLowerCase().startsWith('en')) || voices[0];
                },

                browserSpeak(text) {
                    if (this.audioMuted || !text) {
                        return;
                    }

                    try {
                        if (!('speechSynthesis' in window)) {
                            return;
                        }

                        window.speechSynthesis.cancel();

                        if (!this.selectedVoice || !this.voicesLoaded) {
                            this.loadBestFemaleVoice();
                        }

                        const utterance = new SpeechSynthesisUtterance(text);

                        if (this.selectedVoice) {
                            utterance.voice = this.selectedVoice;
                            utterance.lang = this.selectedVoice.lang || 'en-US';
                        } else {
                            utterance.lang = 'en-US';
                        }

                        /*
                         * Natural female-style browser voice tuning.
                         * Not too slow, not chipmunk, not robotic.
                         */
                        utterance.rate = 1.03;
                        utterance.pitch = 1.16;
                        utterance.volume = 0.92;

                        window.speechSynthesis.speak(utterance);
                    } catch (e) {
                        console.warn('Browser voice failed.', e);
                    }
                },

                stopVoice() {
                    try {
                        if ('speechSynthesis' in window) {
                            window.speechSynthesis.cancel();
                        }
                    } catch (e) {
                        console.warn('Voice stop failed.', e);
                    }
                },

                createEffects(type, x, y, count = 6, customText = null) {
                    const confettiChars = ['✦', '★', '●', '♥', '✿', '◆'];
                    const notes = ['♪', '♫', '♬'];
                    const paws = ['🐾'];

                    for (let i = 0; i < count; i++) {
                        const id = this.effectCounter++;
                        const offsetX = this.random(-7, 7);
                        const offsetY = this.random(-9, 6);
                        const delay = i * 0.055;

                        let text = '✦';

                        if (type === 'heart') {
                            text = '♥';
                        }

                        if (type === 'confetti') {
                            text = confettiChars[this.random(0, confettiChars.length - 1)];
                        }

                        if (type === 'note') {
                            text = notes[this.random(0, notes.length - 1)];
                        }

                        if (type === 'paw') {
                            text = paws[0];
                        }

                        if (type === 'ring') {
                            text = '';
                        }

                        if (type === 'pop') {
                            text = customText || '+ Joy';
                        }

                        this.effects.push({
                            id,
                            type,
                            text,
                            style: [
                                `left:${x + offsetX}%`,
                                `top:${y + offsetY}%`,
                                `animation-delay:${delay}s`,
                                `color:${this.effectColor(type)}`,
                            ].join(';'),
                        });

                        setTimeout(() => {
                            this.effects = this.effects.filter(effect => effect.id !== id);
                        }, 2000);
                    }
                },

                partyBurst(x, y) {
                    this.partyMode = true;

                    this.createEffects('heart', x, y - 8, 10);
                    this.createEffects('star', x, y - 10, 12);
                    this.createEffects('confetti', x, y - 12, 18);
                    this.createEffects('ring', x, y - 8, 2);
                    this.createEffects('pop', x, y - 24, 1, '+ Happy');

                    clearTimeout(this.partyTimer);

                    this.partyTimer = setTimeout(() => {
                        if (!this.isPlaying && !this.isCheering) {
                            this.partyMode = false;
                        }
                    }, 1800);
                },

                effectColor(type) {
                    if (type === 'heart') {
                        return '#ff4fa4';
                    }

                    if (type === 'note') {
                        return '#8b5cf6';
                    }

                    if (type === 'paw') {
                        return 'rgba(98, 10, 136, .55)';
                    }

                    const colors = ['#ffc83d', '#ff7bd3', '#8b5cf6', '#38bdf8', '#34d399'];
                    return colors[this.random(0, colors.length - 1)];
                },

                soundSuccess() {
                    this.playTone([660, 880, 1175, 1568], 0.11);
                },

                soundGuide() {
                    this.playTone([880, 1175], 0.10);
                },

                soundError() {
                    this.playTone([280, 220], 0.16);
                },

                soundNibble() {
                    this.playTone([520, 640, 720, 640], 0.08);
                },

                soundPlayfulStart() {
                    this.playTone([740, 980, 1240, 1480, 1240], 0.085);
                },

                playTone(notes, duration = 0.12) {
                    if (this.audioMuted) {
                        return;
                    }

                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;

                        if (!AudioCtx) {
                            return;
                        }

                        const ctx = new AudioCtx();
                        const master = ctx.createGain();

                        master.gain.value = 0.12;
                        master.connect(ctx.destination);

                        const now = ctx.currentTime;

                        notes.forEach((frequency, index) => {
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            const start = now + index * duration;

                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(frequency, start);

                            gain.gain.setValueAtTime(0.0001, start);
                            gain.gain.exponentialRampToValueAtTime(0.12, start + 0.015);
                            gain.gain.exponentialRampToValueAtTime(0.0001, start + duration);

                            osc.connect(gain);
                            gain.connect(master);

                            osc.start(start);
                            osc.stop(start + duration + 0.04);
                        });
                    } catch (e) {
                        console.warn('Sound failed.', e);
                    }
                },

                toastMessage(text) {
                    this.toast.text = text;
                    this.toast.show = true;

                    clearTimeout(this.toastTimer);

                    this.toastTimer = setTimeout(() => {
                        this.toast.show = false;
                    }, 1800);
                },

                async postJson(url, payload) {
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(payload),
                        });

                        const data = await response.json().catch(() => ({
                            success: false,
                            message: 'Invalid server response.',
                        }));

                        if (!response.ok) {
                            return {
                                success: false,
                                message: data.message || 'Request failed.',
                                errors: data.errors || {},
                            };
                        }

                        return data;
                    } catch (e) {
                        return {
                            success: false,
                            message: 'Network error. Please try again.',
                        };
                    }
                },

                cloneLayout(layout) {
                    return {
                        x: Number(layout?.x || 50),
                        y: Number(layout?.y || 70),
                        scale: Number(layout?.scale || 1),
                        rotation: Number(layout?.rotation || 0),
                        direction: layout?.direction || 'right',
                        z_index: Number(layout?.z_index || 50),
                        is_visible: layout?.is_visible !== false,
                    };
                },

                clamp(value, min, max) {
                    return Math.max(min, Math.min(max, Number(value)));
                },

                random(min, max) {
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                },

                wait(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                },
            };
        }
    </script>
@endsection