<aside
    class="fixed inset-y-0 left-0 z-50 flex flex-col border-r border-black/5 bg-white transition-all duration-300"
    :class="[
        desktopCollapsed ? 'w-24' : 'w-72',
        mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
>
    <div class="flex h-20 items-center justify-between border-b border-black/5 px-4" :class="desktopCollapsed ? 'lg:px-3' : 'lg:px-5'">
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters Logo" class="h-16 w-16">


            <div x-show="!desktopCollapsed" x-cloak>
                <div class="text-lg font-black tracking-tight text-black">Letter Getters</div>
                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-black/45">Admin Panel</div>
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
                href="{{ url('/admin/dashboard') }}"
                @click="mobileSidebar = false"
                class="admin-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/dashboard') ? 'admin-menu-link-active' : 'text-black/75' }}"
                :class="desktopCollapsed ? 'justify-center' : ''"
            >
                <i class="fa-solid fa-grid-2 text-base"></i>
                <span x-show="!desktopCollapsed" x-cloak>Dashboard</span>
            </a>

            <div x-data="{ open: {{ request()->is('admin/users*') || request()->is('admin/parents-children*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/users*') || request()->is('admin/parents-children*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-users text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>User Management</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/users') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/users') ? 'admin-submenu-link-active' : 'text-black/60' }}">All Users</a>
                    <a href="{{ url('/admin/users/children') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/users/children*') ? 'admin-submenu-link-active' : 'text-black/60' }}">Child Users</a>
                    <a href="{{ url('/admin/users/parents') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/users/parents*') ? 'admin-submenu-link-active' : 'text-black/60' }}">Parent Users</a>
                    <a href="{{ url('/admin/users/adults') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/users/adults*') ? 'admin-submenu-link-active' : 'text-black/60' }}">Adult Users</a>
                    <a href="{{ url('/admin/users/staff') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/users/staff*') ? 'admin-submenu-link-active' : 'text-black/60' }}">Staff & Moderators</a>
                    <a href="{{ url('/admin/parents-children') }}" class="admin-submenu-link block text-sm {{ request()->is('admin/parents-children*') ? 'admin-submenu-link-active' : 'text-black/60' }}">Parent-Child Links</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/matches*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/matches*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-user-group text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Match Management</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/matches/suggested') }}" class="admin-submenu-link block text-sm text-black/60">Suggested Matches</a>
                    <a href="{{ url('/admin/matches/pending') }}" class="admin-submenu-link block text-sm text-black/60">Pending Approvals</a>
                    <a href="{{ url('/admin/matches/approved') }}" class="admin-submenu-link block text-sm text-black/60">Approved Matches</a>
                    <a href="{{ url('/admin/matches/blocked') }}" class="admin-submenu-link block text-sm text-black/60">Blocked / Removed</a>
                    <a href="{{ url('/admin/matches/history') }}" class="admin-submenu-link block text-sm text-black/60">Match History</a>
                </div>
            </div>
            
            <div x-data="{ open: {{ request()->is('admin/interests*') || request()->is('admin/child-avatars*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/interests*') || request()->is('admin/child-avatars*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-layer-group text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>App Management</span>
                    </div>

                    <i
                        x-show="!desktopCollapsed"
                        x-cloak
                        class="fa-solid fa-chevron-down text-xs transition"
                        :class="open ? 'rotate-180' : ''"
                    ></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a
                        href="{{ route('admin.interests.index') }}"
                        class="admin-submenu-link block text-sm {{ request()->is('admin/interests*') ? 'admin-submenu-link-active' : 'text-black/60' }}"
                    >
                        Interests
                    </a>

                    <a
                        href="{{ route('admin.child-avatars.index') }}"
                        class="admin-submenu-link block text-sm {{ request()->is('admin/child-avatars*') ? 'admin-submenu-link-active' : 'text-black/60' }}"
                    >
                        Child Avatars
                    </a>
                </div>
            </div>
            
            <a
                href="{{ route('admin.support-tickets.index') }}"
                @click="mobileSidebar = false"
                class="admin-menu-link flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/support-tickets*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                :class="desktopCollapsed ? 'justify-center' : ''"
            >
                <i class="fa-solid fa-life-ring text-base"></i>
                <span x-show="!desktopCollapsed" x-cloak>Support Tickets</span>
            </a>

            <div x-data="{ open: {{ request()->is('admin/mail*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/mail*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope-open-text text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Mail Operations</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/mail/incoming') }}" class="admin-submenu-link block text-sm text-black/60">Incoming Letters</a>
                    <a href="{{ url('/admin/mail/preview-queue') }}" class="admin-submenu-link block text-sm text-black/60">Preview Queue</a>
                    <a href="{{ url('/admin/mail/status-tracking') }}" class="admin-submenu-link block text-sm text-black/60">Status Tracking</a>
                    <a href="{{ url('/admin/mail/archive') }}" class="admin-submenu-link block text-sm text-black/60">Letter Archive</a>
                    <a href="{{ url('/admin/mail/fulfillment') }}" class="admin-submenu-link block text-sm text-black/60">Forwarding & Fulfillment</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/moderation*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/moderation*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Moderation</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/moderation/reports') }}" class="admin-submenu-link block text-sm text-black/60">Reports</a>
                    <a href="{{ url('/admin/moderation/flagged-content') }}" class="admin-submenu-link block text-sm text-black/60">Flagged Content</a>
                    <a href="{{ url('/admin/moderation/suspended-accounts') }}" class="admin-submenu-link block text-sm text-black/60">Suspended Accounts</a>
                    <a href="{{ url('/admin/moderation/logs') }}" class="admin-submenu-link block text-sm text-black/60">Moderation Logs</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/rewards*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/rewards*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gift text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Digital Rewards</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/rewards/coins') }}" class="admin-submenu-link block text-sm text-black/60">Coin Rules</a>
                    <a href="{{ url('/admin/rewards/avatar-items') }}" class="admin-submenu-link block text-sm text-black/60">Avatar Items</a>
                    <a href="{{ url('/admin/rewards/printables') }}" class="admin-submenu-link block text-sm text-black/60">Printable Stationery</a>
                    <a href="{{ url('/admin/rewards/unlocks') }}" class="admin-submenu-link block text-sm text-black/60">Reward Unlocks</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/subscriptions*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/subscriptions*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box-open text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Subscriptions</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/subscriptions/plans') }}" class="admin-submenu-link block text-sm text-black/60">Plans</a>
                    <a href="{{ url('/admin/subscriptions/active') }}" class="admin-submenu-link block text-sm text-black/60">Active Subscriptions</a>
                    <a href="{{ url('/admin/subscriptions/renewals') }}" class="admin-submenu-link block text-sm text-black/60">Renewals</a>
                    <a href="{{ url('/admin/subscriptions/kit-fulfillment') }}" class="admin-submenu-link block text-sm text-black/60">Kit Fulfillment</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/store*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/store*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-store text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Store Management</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/store/products') }}" class="admin-submenu-link block text-sm text-black/60">Products</a>
                    <a href="{{ url('/admin/store/orders') }}" class="admin-submenu-link block text-sm text-black/60">Orders</a>
                    <a href="{{ url('/admin/store/shipping') }}" class="admin-submenu-link block text-sm text-black/60">Shipping</a>
                    <a href="{{ url('/admin/store/inventory') }}" class="admin-submenu-link block text-sm text-black/60">Inventory</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/content*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/content*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-file-lines text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>CMS & Content</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/content/homepage') }}" class="admin-submenu-link block text-sm text-black/60">Homepage Content</a>
                    <a href="{{ url('/admin/content/safety-page') }}" class="admin-submenu-link block text-sm text-black/60">Safety Page</a>
                    <a href="{{ url('/admin/content/writing-tips') }}" class="admin-submenu-link block text-sm text-black/60">Writing Tips</a>
                    <a href="{{ url('/admin/content/faq') }}" class="admin-submenu-link block text-sm text-black/60">FAQ / Help</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/reports*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/reports*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-pie text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Reports & Analytics</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/reports/users') }}" class="admin-submenu-link block text-sm text-black/60">User Reports</a>
                    <a href="{{ url('/admin/reports/letters') }}" class="admin-submenu-link block text-sm text-black/60">Letter Reports</a>
                    <a href="{{ url('/admin/reports/revenue') }}" class="admin-submenu-link block text-sm text-black/60">Revenue Reports</a>
                    <a href="{{ url('/admin/reports/rewards') }}" class="admin-submenu-link block text-sm text-black/60">Rewards Reports</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->is('admin/settings*') ? 'true' : 'false' }} }" class="rounded-2xl">
                <button
                    type="button"
                    @click="if (desktopCollapsed) { desktopCollapsed = false } else { open = !open }"
                    class="admin-menu-link flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-sm font-semibold {{ request()->is('admin/settings*') ? 'admin-menu-link-active' : 'text-black/75' }}"
                    :class="desktopCollapsed ? 'justify-center' : 'justify-between'"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gear text-base"></i>
                        <span x-show="!desktopCollapsed" x-cloak>Settings</span>
                    </div>
                    <i x-show="!desktopCollapsed" x-cloak class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open && !desktopCollapsed" x-cloak class="mt-2 space-y-2 pl-11 pr-3">
                    <a href="{{ url('/admin/settings/general') }}" class="admin-submenu-link block text-sm text-black/60">General Settings</a>
                    <a href="{{ url('/admin/settings/roles-permissions') }}" class="admin-submenu-link block text-sm text-black/60">Roles & Permissions</a>
                    <a href="{{ url('/admin/settings/notifications') }}" class="admin-submenu-link block text-sm text-black/60">Notification Settings</a>
                    <a href="{{ url('/admin/settings/security') }}" class="admin-submenu-link block text-sm text-black/60">Security Settings</a>
                </div>
            </div>
        </nav>
    </div>
</aside>