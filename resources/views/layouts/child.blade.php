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
        $header = $childHeaderData ?? [
            'unread_message_count' => 0,
            'unread_letter_count' => 0,
            'recent_messages' => collect(),
            'recent_letters' => collect(),
        ];
        $totalBellCount = ($header['unread_message_count'] ?? 0) + ($header['unread_letter_count'] ?? 0);
    @endphp

    <style>
        :root {
            --child-pink: #CB148B;
            --child-purple: #620A88;
            --child-bg: #FCF8FD;
            --child-card: #FFFFFF;
            --child-border: rgba(17, 17, 17, 0.08);
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
            position: relative;
            transition: color 0.22s ease;
        }

        .child-nav-link:hover {
            color: var(--child-pink);
        }

        .child-nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -7px;
            width: 0;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--child-pink), var(--child-purple));
            transition: width 0.22s ease;
        }

        .child-nav-link:hover::after,
        .child-nav-link-active::after {
            width: 100%;
        }

        .child-nav-link-active {
            color: var(--child-pink);
            font-weight: 700;
        }

        .child-soft {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.10), transparent 30%),
                #ffffff;
        }

        .child-mobile-link {
            transition: all 0.22s ease;
        }

        .child-mobile-link:hover {
            transform: translateX(3px);
        }
    </style>
</head>

<body class="min-h-screen antialiased">
    <div x-data="{ mobileMenu: false, profileOpen: false, notificationOpen: false }" class="min-h-screen">
        <header class="sticky top-0 z-40 border-b border-black/5 bg-white/90 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" @click="mobileMenu = true"
                        class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70 lg:hidden">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <a href="{{ route('child.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters"
                            class="h-12 w-12 rounded-full object-cover">
                        <div class="hidden sm:block">
                            <div class="text-lg font-bold tracking-tight text-black">Letter Getters</div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-black/45">Child Space
                            </div>
                        </div>
                    </a>
                </div>

                <nav class="hidden items-center gap-6 xl:gap-7 lg:flex">
                    <a href="{{ route('child.dashboard') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.dashboard') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Home
                    </a>

                    <a href="{{ route('child.profile.complete') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.profile.complete') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Complete Profile
                    </a>

                    <a href="{{ route('child.pen-pals') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.pen-pals') ? 'child-nav-link-active' : 'text-black/70' }}">
                        My Pen Pals
                    </a>

                    <a href="{{ route('child.letters') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.letters*') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Letters
                    </a>

                    <a href="{{ route('child.shop') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.shop') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Shop
                    </a>

                    <a href="{{ route('child.rewards') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.rewards') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Rewards
                    </a>

                    <a href="{{ route('child.printables') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.printables') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Printables
                    </a>

                    <a href="{{ route('child.safety') }}"
                        class="child-nav-link text-sm {{ request()->routeIs('child.safety') ? 'child-nav-link-active' : 'text-black/70' }}">
                        Safety
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <div class="relative">
                        <button type="button" @click="notificationOpen = !notificationOpen; profileOpen = false"
                            class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70">
                            <i class="fa-regular fa-bell"></i>

                            @if($totalBellCount > 0)
                                <span
                                    class="absolute -right-1 -top-1 inline-flex min-h-[20px] min-w-[20px] items-center justify-center rounded-full bg-[#CB148B] px-1 text-[11px] font-bold text-white">
                                    {{ $totalBellCount > 99 ? '99+' : $totalBellCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="notificationOpen" x-cloak @click.outside="notificationOpen = false"
                            class="absolute right-0 mt-3 w-[360px] rounded-[28px] border border-black/8 bg-white p-4 shadow-[0_24px_70px_rgba(17,17,17,0.10)]">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-bold text-black">Notifications</h3>
                                    <p class="mt-1 text-xs text-black/45">Unread messages and approved letters</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-[#fff7fc] p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#CB148B]">
                                        Unread Messages</p>
                                    <p class="mt-2 text-2xl font-bold text-black">{{ $header['unread_message_count'] }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-[#f7efff] p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-[#620A88]">New
                                        Letters</p>
                                    <p class="mt-2 text-2xl font-bold text-black">{{ $header['unread_letter_count'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h4 class="text-xs font-semibold uppercase tracking-[0.16em] text-black/45">Unread chat
                                    messages</h4>

                                <div class="mt-3 space-y-3">
                                    @forelse($header['recent_messages'] as $messageItem)
                                        <a href="{{ route('child.messages.chat', $messageItem->sender) }}"
                                            class="block rounded-2xl border border-black/6 p-3 hover:bg-[#fff7fc]">
                                            <p class="text-sm font-semibold text-black">
                                                New message from {{ $messageItem->sender?->name }}
                                            </p>
                                            <p class="mt-1 text-xs leading-6 text-black/50">
                                                {{ \Illuminate\Support\Str::limit($messageItem->message, 70) }}
                                            </p>
                                            <p class="mt-1 text-[11px] text-black/40">
                                                {{ $messageItem->created_at?->diffForHumans() }}
                                            </p>
                                        </a>
                                    @empty
                                        <div
                                            class="rounded-2xl border border-dashed border-black/8 p-3 text-xs text-black/45">
                                            No unread chat messages.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-5">
                                <h4 class="text-xs font-semibold uppercase tracking-[0.16em] text-black/45">New approved
                                    letters</h4>

                                <div class="mt-3 space-y-3">
                                    @forelse($header['recent_letters'] as $letterItem)
                                        <a href="{{ route('child.letters.show', $letterItem) }}"
                                            class="block rounded-2xl border border-black/6 p-3 hover:bg-[#f7efff]">
                                            <p class="text-sm font-semibold text-black">
                                                New letter from {{ $letterItem->sender?->name }}
                                            </p>
                                            <p class="mt-1 text-xs leading-6 text-black/50">
                                                Subject: {{ $letterItem->subject }}
                                            </p>
                                            <p class="mt-1 text-[11px] text-black/40">
                                                {{ $letterItem->created_at?->diffForHumans() }}
                                            </p>
                                        </a>
                                    @empty
                                        <div
                                            class="rounded-2xl border border-dashed border-black/8 p-3 text-xs text-black/45">
                                            No new approved letters.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button" @click="profileOpen = !profileOpen; notificationOpen = false"
                            class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white px-3 py-2">
                            @if($childProfile?->avatar_type === 'upload' && $childProfile?->avatar)
                                <img src="{{ asset('storage/' . $childProfile->avatar) }}" alt="Avatar"
                                    class="h-11 w-11 rounded-2xl object-cover">
                            @elseif($childProfile?->avatar_type === 'library' && $childProfile?->avatarLibrary?->image_path)
                                <img src="{{ asset('storage/' . $childProfile->avatarLibrary->image_path) }}" alt="Avatar"
                                    class="h-11 w-11 rounded-2xl object-cover">
                            @else
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white shadow-sm">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif

                            <div class="hidden text-left sm:block">
                                <div class="text-sm font-semibold text-black">{{ $childUser?->name }}</div>
                                <div class="text-xs text-black/45">Child Account</div>
                            </div>
                        </button>

                        <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                            class="absolute right-0 mt-3 w-64 rounded-3xl border border-black/8 bg-white p-3 shadow-[0_24px_70px_rgba(17,17,17,0.10)]">
                            <a href="{{ route('child.profile.complete') }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-regular fa-user"></i>
                                <span>Edit Profile</span>
                            </a>

                            <a href="{{ route('child.rewards') }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-solid fa-coins"></i>
                                <span>My Coins</span>
                            </a>

                            <a href="{{ route('child.shop') }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-solid fa-shop"></i>
                                <span>Shop</span>
                            </a>
                            <a href="{{ route('child.store.cart.index') }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span>Cart</span>
                            </a>

                            <a href="{{ route('child.store.orders.index') }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 hover:bg-[#fff7fc] hover:text-[#CB148B]">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <span>My Orders</span>
                            </a>

                            <div class="my-2 border-t border-black/6"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div x-show="mobileMenu" x-cloak class="fixed inset-0 z-[70] lg:hidden">
            <div class="absolute inset-0 bg-black/35 backdrop-blur-[2px]" @click="mobileMenu = false"></div>

            <div class="relative flex min-h-screen items-start justify-center px-4 pb-6 pt-5">
                <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-180"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-140"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="child-soft relative w-full max-w-md overflow-hidden rounded-[32px] border border-white/70 shadow-[0_24px_70px_rgba(17,17,17,0.16)]">
                    <div class="relative border-b border-black/6 px-5 pb-4 pt-5">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('child.dashboard') }}" class="flex items-center gap-3"
                                @click="mobileMenu = false">
                                <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters" class="h-14 w-auto">
                            </a>

                            <button type="button" @click="mobileMenu = false"
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-black/15 bg-white text-neutral-700">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative px-5 pb-5 pt-5">
                        <nav class="space-y-2.5">
                            <a href="{{ route('child.dashboard') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.dashboard') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Home</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.profile.complete') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.profile.complete') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Complete Profile</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.pen-pals') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.pen-pals') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>My Pen Pals</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.letters') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.letters*') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Letters</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.shop') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.shop') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Shop</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.rewards') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.rewards') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Rewards</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.printables') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.printables') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Printables</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>

                            <a href="{{ route('child.safety') }}" @click="mobileMenu = false"
                                class="child-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-lg font-medium {{ request()->routeIs('child.safety') ? 'bg-[#fbf1f9] text-[#620A88] shadow-sm' : 'text-neutral-700 hover:bg-white/80' }}">
                                <span>Safety</span>
                                <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>