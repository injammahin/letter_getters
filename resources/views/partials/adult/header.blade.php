@php
    $adultUser = auth()->user();

    $notifications = [
        ['title' => 'Your profile is active and visible', 'time' => 'Just now'],
        ['title' => 'New adult matches will appear here', 'time' => 'Today'],
    ];
@endphp

<header class="sticky top-0 z-40 border-b border-black/5 bg-white/90 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <button
                    type="button"
                    @click="mobileMenu = !mobileMenu"
                    class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B] lg:hidden"
                >
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>

                <a href="{{ route('adult.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters" class="h-12 w-12 rounded-full object-cover">
                    <div class="hidden sm:block">
                        <div class="text-lg font-semibold tracking-tight text-neutral-900">Letter Getters</div>
                        <div class="text-[11px] font-medium uppercase tracking-[0.16em] text-neutral-500">Adult Space</div>
                    </div>
                </a>
            </div>

            <nav class="hidden items-center gap-7 lg:flex">
                <a href="{{ route('adult.dashboard') }}" class="adult-nav-link text-sm {{ request()->routeIs('adult.dashboard') ? 'adult-nav-link-active' : 'text-neutral-600' }}">
                    Dashboard
                </a>
                <a href="#" class="adult-nav-link text-sm text-neutral-600">My Matches</a>
                <a href="#" class="adult-nav-link text-sm text-neutral-600">Messages</a>
                <a href="#" class="adult-nav-link text-sm text-neutral-600">Letters</a>
                <a href="#" class="adult-nav-link text-sm text-neutral-600">Profile</a>
                <a href="#" class="adult-nav-link text-sm text-neutral-600">Preferences</a>
            </nav>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <button
                        type="button"
                        @click="notificationOpen = !notificationOpen; profileOpen = false"
                        class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                    >
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#CB148B]"></span>
                    </button>

                    <div
                        x-show="notificationOpen"
                        x-cloak
                        @click.outside="notificationOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 mt-3 w-[320px] rounded-[28px] border border-black/8 bg-white p-4 shadow-[0_24px_70px_rgba(17,17,17,0.10)]"
                    >
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-neutral-900">Notifications</h3>
                            <p class="mt-1 text-xs text-neutral-500">Recent adult account updates</p>
                        </div>

                        <div class="space-y-3">
                            @foreach($notifications as $item)
                                <div class="rounded-2xl border border-black/6 p-3">
                                    <p class="text-sm font-medium leading-6 text-neutral-900">{{ $item['title'] }}</p>
                                    <p class="mt-1 text-xs text-neutral-500">{{ $item['time'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <button
                        type="button"
                        @click="profileOpen = !profileOpen; notificationOpen = false"
                        class="flex items-center gap-3 rounded-[22px] border border-black/10 bg-white px-3 py-2 transition hover:border-[#620A88]"
                    >
                        <div class="adult-gradient flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-semibold text-white shadow-sm">
                            {{ strtoupper(substr($adultUser?->name ?? 'A', 0, 1)) }}
                        </div>

                        <div class="hidden text-left sm:block">
                            <div class="text-sm font-semibold text-neutral-900">{{ $adultUser?->name ?? 'Adult User' }}</div>
                            <div class="text-xs text-neutral-500">Adult Account</div>
                        </div>

                        <i class="fa-solid fa-chevron-down hidden text-[11px] text-neutral-400 sm:block"></i>
                    </button>

                    <div
                        x-show="profileOpen"
                        x-cloak
                        @click.outside="profileOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 mt-3 w-64 rounded-[28px] border border-black/8 bg-white p-3 shadow-[0_24px_70px_rgba(17,17,17,0.10)]"
                    >
                        <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#fff7fc] hover:text-[#CB148B]">
                            <i class="fa-regular fa-user"></i>
                            <span>Profile</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#fff7fc] hover:text-[#CB148B]">
                            <i class="fa-solid fa-gear"></i>
                            <span>Settings</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#fff7fc] hover:text-[#CB148B]">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Preferences</span>
                        </a>

                        <div class="my-2 border-t border-black/6"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="mobileMenu" x-cloak class="border-t border-black/5 bg-white lg:hidden">
            <nav class="grid gap-1 py-4">
                <a href="{{ route('adult.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('adult.dashboard') ? 'bg-[#fff7fc] text-[#CB148B]' : 'text-neutral-600' }}">
                    Dashboard
                </a>
                <a href="#" class="rounded-2xl px-4 py-3 text-sm font-medium text-neutral-600">My Matches</a>
                <a href="#" class="rounded-2xl px-4 py-3 text-sm font-medium text-neutral-600">Messages</a>
                <a href="#" class="rounded-2xl px-4 py-3 text-sm font-medium text-neutral-600">Letters</a>
                <a href="#" class="rounded-2xl px-4 py-3 text-sm font-medium text-neutral-600">Profile</a>
                <a href="#" class="rounded-2xl px-4 py-3 text-sm font-medium text-neutral-600">Preferences</a>
            </nav>
        </div>
    </div>
</header>