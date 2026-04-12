@php
    $notifications = [
        ['title' => 'A child registration is waiting for approval', 'time' => '5 min ago'],
        ['title' => 'A new letter preview is ready', 'time' => '20 min ago'],
    ];
@endphp

<header class="sticky top-0 z-30 border-b border-black/5 bg-white/90 backdrop-blur-xl">
    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button
                type="button"
                @click="mobileSidebar = !mobileSidebar"
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70 transition hover:border-pink-200 hover:text-[#CB148B] lg:hidden"
            >
                <i class="fa-solid fa-bars-staggered"></i>
            </button>

            <div class="min-w-0">
                <h1 class="truncate text-xl font-black text-black">@yield('page_title', 'Parent Dashboard')</h1>
                <p class="truncate text-sm text-black/50">@yield('page_subtitle', 'Monitor approvals, children, and letter activity')</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <button
                    type="button"
                    @click="notificationOpen = !notificationOpen; profileOpen = false"
                    class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70 transition hover:border-pink-200 hover:text-[#CB148B]"
                >
                    <i class="fa-regular fa-bell"></i>
                    <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#CB148B]"></span>
                </button>

                <div
                    x-show="notificationOpen"
                    x-cloak
                    @click.outside="notificationOpen = false"
                    class="absolute right-0 mt-3 w-[320px] rounded-3xl border border-black/8 bg-white p-4 shadow-[0_24px_70px_rgba(17,17,17,0.10)]"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-black text-black">Notifications</h3>
                        <span class="rounded-full bg-[rgba(203,20,139,0.08)] px-3 py-1 text-xs font-bold text-[#CB148B]">2 New</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($notifications as $notification)
                            <div class="rounded-2xl border border-black/6 p-3">
                                <p class="text-sm font-semibold leading-6 text-black">{{ $notification['title'] }}</p>
                                <p class="mt-1 text-xs text-black/45">{{ $notification['time'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative">
                <button
                    type="button"
                    @click="profileOpen = !profileOpen; notificationOpen = false"
                    class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white px-3 py-2 transition hover:border-violet-200"
                >
                    <div class="parent-gradient flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-black text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                    </div>

                    <div class="hidden text-left sm:block">
                        <div class="text-sm font-bold text-black">{{ auth()->user()->name ?? 'Parent User' }}</div>
                        <div class="text-xs font-medium uppercase tracking-[0.18em] text-black/45">
                            {{ auth()->user()->role ?? 'parent' }}
                        </div>
                    </div>

                    <i class="fa-solid fa-chevron-down text-xs text-black/45"></i>
                </button>

                <div
                    x-show="profileOpen"
                    x-cloak
                    @click.outside="profileOpen = false"
                    class="absolute right-0 mt-3 w-64 rounded-3xl border border-black/8 bg-white p-3 shadow-[0_24px_70px_rgba(17,17,17,0.10)]"
                >
                    <a href="{{ url('/profile') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 transition hover:bg-[rgba(203,20,139,0.08)] hover:text-[#CB148B]">
                        <i class="fa-regular fa-user"></i>
                        <span>Profile</span>
                    </a>

                    <a href="{{ url('/profile') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 transition hover:bg-[rgba(203,20,139,0.08)] hover:text-[#CB148B]">
                        <i class="fa-solid fa-key"></i>
                        <span>Change Password</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75 transition hover:bg-[rgba(203,20,139,0.08)] hover:text-[#CB148B]">
                        <i class="fa-solid fa-gear"></i>
                        <span>Settings</span>
                    </a>

                    <div class="my-2 border-t border-black/6"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>