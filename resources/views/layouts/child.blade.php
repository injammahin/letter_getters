<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Child Space') - {{ config('app.name', 'Letter Getters') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $childUser = auth()->user();
        $childProfile = $childUser?->profile;

        $presetMap = [
            'rocket' => ['emoji' => '🚀', 'bg' => 'from-pink-500 to-purple-600'],
            'star' => ['emoji' => '⭐', 'bg' => 'from-yellow-400 to-orange-400'],
            'tiger' => ['emoji' => '🐯', 'bg' => 'from-orange-400 to-pink-500'],
            'panda' => ['emoji' => '🐼', 'bg' => 'from-slate-400 to-slate-700'],
            'unicorn' => ['emoji' => '🦄', 'bg' => 'from-fuchsia-400 to-violet-600'],
            'planet' => ['emoji' => '🪐', 'bg' => 'from-sky-400 to-indigo-500'],
        ];

        $avatarKey = $childProfile?->avatar ?? 'rocket';
        $avatarPreset = $presetMap[$avatarKey] ?? $presetMap['rocket'];
    @endphp

    <style>
        :root {
            --child-pink: #CB148B;
            --child-purple: #620A88;
            --child-bg: #FCF8FD;
            --child-card: #FFFFFF;
            --child-border: rgba(17, 17, 17, 0.08);
            --child-yellow: #FFE58F;
            --child-sky: #DDF4FF;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            background: var(--child-bg);
            color: #111827;
        }

        .child-gradient {
            background: linear-gradient(135deg, var(--child-pink), var(--child-purple));
        }

        .child-card {
            background: var(--child-card);
            border: 1px solid var(--child-border);
            box-shadow: 0 18px 50px rgba(17, 17, 17, 0.05);
        }

        .child-nav-link {
            transition: all 0.25s ease;
        }

        .child-nav-link:hover {
            color: var(--child-pink);
        }

        .child-nav-link-active {
            color: var(--child-pink);
            font-weight: 800;
        }

        .child-soft {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.10), transparent 30%),
                #ffffff;
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    <div x-data="{ mobileMenu: false, profileOpen: false, notificationOpen: false }" class="min-h-screen">
        <header class="sticky top-0 z-40 border-b border-black/5 bg-white/90 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="mobileMenu = !mobileMenu"
                        class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70 lg:hidden"
                    >
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <a href="{{ route('child.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters" class="h-12 w-12 rounded-full object-cover">
                        <div class="hidden sm:block">
                            <div class="text-lg font-black tracking-tight text-black">Letter Getters</div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-black/45">Child Space</div>
                        </div>
                    </a>
                </div>

                <nav class="hidden items-center gap-7 lg:flex">
                    <a href="{{ route('child.dashboard') }}" class="child-nav-link text-sm {{ request()->routeIs('child.dashboard') ? 'child-nav-link-active' : 'text-black/70' }}">Home</a>
                    <a href="{{ route('child.profile.complete') }}" class="child-nav-link text-sm {{ request()->routeIs('child.profile.complete') ? 'child-nav-link-active' : 'text-black/70' }}">Complete Profile</a>
                    <a href="#" class="child-nav-link text-sm text-black/70">My Pen Pals</a>
                    <a href="#" class="child-nav-link text-sm text-black/70">Letters</a>
                    <a href="#" class="child-nav-link text-sm text-black/70">Rewards</a>
                    <a href="#" class="child-nav-link text-sm text-black/70">Printables</a>
                    <a href="#" class="child-nav-link text-sm text-black/70">Safety</a>
                </nav>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="notificationOpen = !notificationOpen; profileOpen = false"
                        class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70"
                    >
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#CB148B]"></span>
                    </button>

                    <div class="relative">
                        <button
                            type="button"
                            @click="profileOpen = !profileOpen; notificationOpen = false"
                            class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white px-3 py-2"
                        >
                            @if($childProfile?->avatar_type === 'upload' && $childProfile?->avatar)
                                <img src="{{ asset('storage/'.$childProfile->avatar) }}" alt="Avatar" class="h-11 w-11 rounded-2xl object-cover">
                            @else
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br {{ $avatarPreset['bg'] }} text-lg text-white shadow">
                                    {{ $avatarPreset['emoji'] }}
                                </div>
                            @endif

                            <div class="hidden text-left sm:block">
                                <div class="text-sm font-bold text-black">{{ $childUser?->name }}</div>
                                <div class="text-xs text-black/45">Child Account</div>
                            </div>
                        </button>

                        <div
                            x-show="profileOpen"
                            x-cloak
                            @click.outside="profileOpen = false"
                            class="absolute right-0 mt-3 w-64 rounded-3xl border border-black/8 bg-white p-3 shadow-[0_24px_70px_rgba(17,17,17,0.10)]"
                        >
                            <a href="{{ route('child.profile.complete') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-regular fa-user"></i>
                                <span>Edit Profile</span>
                            </a>

                            <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-solid fa-coins"></i>
                                <span>My Coins</span>
                            </a>

                            <div class="my-2 border-t border-black/6"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="mobileMenu" x-cloak class="border-t border-black/5 bg-white lg:hidden">
                <nav class="mx-auto grid max-w-7xl gap-1 px-4 py-4 sm:px-6">
                    <a href="{{ route('child.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('child.dashboard') ? 'bg-[#fff7fc] text-[#CB148B]' : 'text-black/70' }}">Home</a>
                    <a href="{{ route('child.profile.complete') }}" class="rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('child.profile.complete') ? 'bg-[#f7efff] text-[#620A88]' : 'text-black/70' }}">Complete Profile</a>
                    <a href="#" class="rounded-2xl px-4 py-3 text-sm font-semibold text-black/70">My Pen Pals</a>
                    <a href="#" class="rounded-2xl px-4 py-3 text-sm font-semibold text-black/70">Letters</a>
                    <a href="#" class="rounded-2xl px-4 py-3 text-sm font-semibold text-black/70">Rewards</a>
                    <a href="#" class="rounded-2xl px-4 py-3 text-sm font-semibold text-black/70">Printables</a>
                    <a href="#" class="rounded-2xl px-4 py-3 text-sm font-semibold text-black/70">Safety</a>
                </nav>
            </div>
        </header>

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>