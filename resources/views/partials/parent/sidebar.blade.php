<aside
    class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-black/5 bg-white transition-transform duration-300 lg:translate-x-0"
    :class="mobileSidebar ? 'translate-x-0' : ''"
>
    <div class="flex h-20 items-center justify-between border-b border-black/5 px-5">
        <a href="{{ route('parent.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="parent-gradient flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-white shadow-[0_16px_30px_rgba(98,10,136,0.22)]">
                <i class="fa-solid fa-user-shield text-lg"></i>
            </div>

            <div>
                <div class="text-lg font-black tracking-tight text-black">Letter Getters</div>
                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-black/45">Parent Panel</div>
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
                href="{{ route('parent.dashboard') }}"
                @click="mobileSidebar = false"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->routeIs('parent.dashboard') ? 'parent-menu-link-active' : 'text-black/75' }}"
            >
                <i class="fa-solid fa-grid-2 text-base"></i>
                <span>Dashboard</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-child-reaching text-base"></i>
                <span>My Children</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-user-check text-base"></i>
                <span>Pending Approvals</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-envelope-open-text text-base"></i>
                <span>Letter Previews</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-folder-open text-base"></i>
                <span>Letter Archive</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-bell text-base"></i>
                <span>Notifications</span>
            </a>

            <a
                href="#"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-solid fa-lock text-base"></i>
                <span>Privacy Settings</span>
            </a>

            <a
                href="{{ url('/profile') }}"
                class="parent-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold text-black/75"
            >
                <i class="fa-regular fa-user text-base"></i>
                <span>Profile</span>
            </a>
        </nav>
    </div>

    <div class="border-t border-black/5 p-3">
        <div class="parent-gradient rounded-3xl p-4 text-white">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/75">Parent Access</p>
            <p class="mt-2 text-sm leading-6 text-white/90">
                Review child activity, manage approvals, and track letter updates.
            </p>
        </div>
    </div>
</aside>