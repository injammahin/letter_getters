<header class="sticky top-0 z-30 border-b border-black/5 bg-white/90 backdrop-blur-xl lg:pl-72">
    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="mobileSidebar = !mobileSidebar"
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70 lg:hidden"
            >
                <i class="fa-solid fa-bars-staggered"></i>
            </button>

            <div>
                <h1 class="text-xl font-black text-black">@yield('page_title', 'Child Dashboard')</h1>
                <p class="text-sm text-black/50">@yield('page_subtitle', 'Your letters, rewards, and pen pals')</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="button"
                class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black/70"
            >
                <i class="fa-regular fa-bell"></i>
                <span class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-[#CB148B]"></span>
            </button>

            <div class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white px-3 py-2">
                <div class="child-gradient flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-black text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'C', 0, 1)) }}
                </div>

                <div class="hidden text-left sm:block">
                    <div class="text-sm font-bold text-black">{{ auth()->user()->name ?? 'Child User' }}</div>
                    <div class="text-xs font-medium uppercase tracking-[0.18em] text-black/45">
                        {{ auth()->user()->role ?? 'child' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>