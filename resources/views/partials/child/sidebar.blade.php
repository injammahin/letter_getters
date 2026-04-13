<aside
    class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-black/5 bg-white transition-transform duration-300 lg:translate-x-0"
    :class="mobileSidebar ? 'translate-x-0' : ''"
>
    <div class="flex h-20 items-center justify-between border-b border-black/5 px-5">
        <a href="{{ route('child.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters Logo" class="h-14 w-14 rounded-full">

            <div>
                <div class="text-lg font-black tracking-tight text-black">Letter Getters</div>
                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-black/45">Child Panel</div>
            </div>
        </a>

        <button
            type="button"
            @click="mobileSidebar = false"
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-black/10 text-black/70 lg:hidden"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-3 py-4">
        <nav class="space-y-2">
            <a
                href="{{ route('child.dashboard') }}"
                class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->routeIs('child.dashboard') ? 'child-menu-link-active' : 'text-black/75' }}"
            >
                <i class="fa-solid fa-grid-2 text-base"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75">
                <i class="fa-solid fa-user-group text-base"></i>
                <span>My Pen Pals</span>
            </a>

            <a href="#" class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75">
                <i class="fa-solid fa-envelope-open-text text-base"></i>
                <span>Letters</span>
            </a>

            <a href="#" class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75">
                <i class="fa-solid fa-coins text-base"></i>
                <span>Coin Wallet</span>
            </a>

            <a href="#" class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75">
                <i class="fa-solid fa-image-portrait text-base"></i>
                <span>Avatar Shop</span>
            </a>

            <a href="#" class="child-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75">
                <i class="fa-solid fa-bell text-base"></i>
                <span>Notifications</span>
            </a>
        </nav>
    </div>

    <div class="border-t border-black/5 p-3">
        <div class="child-gradient rounded-3xl p-4 text-white">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/75">Child Space</p>
            <p class="mt-2 text-sm leading-6 text-white/90">
                Safe, fun, and easy to use.
            </p>
        </div>
    </div>
</aside>