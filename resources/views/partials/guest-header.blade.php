<div
    x-data="{ mobileMenu: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 24 })"
    class="fixed inset-x-0 top-0 z-50"
>
    <style>
        .lg-nav-link {
            position: relative;
            transition: color 0.25s ease;
        }

        .lg-nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(135deg, #CB148B, #620A88);
            transition: width 0.25s ease;
        }

        .lg-nav-link:hover::after,
        .lg-nav-link.active::after {
            width: 100%;
        }

        .lg-btn-primary {
            background: linear-gradient(135deg, #CB148B, #620A88);
            color: white;
            transition: all 0.3s ease;
        }

        .lg-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(98, 10, 136, 0.18);
        }

        .lg-btn-outline {
            border: 1px solid rgba(17, 17, 17, 0.10);
            background: white;
            color: #111827;
            transition: all 0.25s ease;
        }

        .lg-btn-outline:hover {
            border-color: rgba(203, 20, 139, 0.25);
            color: #CB148B;
        }

        .lg-mobile-link {
            transition: all 0.25s ease;
        }

        .lg-mobile-link:hover {
            transform: translateX(4px);
        }
    </style>

    <header class="px-3 pt-3 sm:px-5 sm:pt-4">
        <div
            class="mx-auto border border-black/5 bg-white/90 backdrop-blur-xl transition-all duration-500 ease-out"
            :class="scrolled
                ? 'max-w-6xl rounded-full shadow-[0_18px_40px_rgba(17,17,17,0.08)]'
                : 'max-w-7xl rounded-[28px]'"
        >
            <div
                class="flex items-center justify-between transition-all duration-500 ease-out"
                :class="scrolled ? 'px-4 py-3 sm:px-5' : 'px-4 py-4 sm:px-6 lg:px-8'"
            >
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    <img
                        src="{{ asset('/img/update logo.png') }}"
                        alt="Letter Getters Logo"
                        class="h-14 w-auto transition-all duration-500 ease-out sm:h-16"
                        :class="scrolled ? 'sm:h-14' : 'sm:h-16'"
                    >
                </a>

                <nav class="hidden items-center gap-8 md:flex">
                    <a href="{{ route('home') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('home') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        Home
                    </a>
                    <a href="{{ route('how-it-works') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('how-it-works') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        How It Works
                    </a>
                    <a href="{{ route('pathways') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('pathways') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        Pathways
                    </a>
                    <a href="{{ route('safety') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('safety') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        Safety
                    </a>
                    {{-- <a href="{{ route('subscription') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('subscription') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        Subscription
                    </a> --}}
                    <a href="{{ route('support') }}" class="lg-nav-link text-sm font-medium {{ request()->routeIs('support') ? 'active text-[#620A88]' : 'text-black/80' }}">
                        Support
                    </a>
                </nav>

                <div class="hidden items-center gap-3 md:flex">
                    <a href="{{ route('login') }}" class="lg-btn-outline rounded-full px-5 py-2.5 text-sm font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="lg-btn-primary rounded-full px-5 py-2.5 text-sm font-medium">
                        Get Started
                    </a>
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    <a href="{{ route('login') }}" class="lg-btn-primary rounded-full px-4 py-2 text-sm font-medium">
                        Sign In
                    </a>

                    <button
                        type="button"
                        @click="mobileMenu = true"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-black/10 bg-white text-neutral-700 shadow-sm transition hover:border-[#CB148B] hover:text-[#CB148B]"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div
        x-show="mobileMenu"
        x-cloak
        class="fixed inset-0 z-[70] md:hidden"
    >
        <div
            class="absolute inset-0 bg-[rgba(17,17,17,0.42)] backdrop-blur-[3px]"
            @click="mobileMenu = false"
        ></div>

        <div class="relative flex min-h-screen items-start justify-center px-4 pb-6 pt-5">
            <div
                x-show="mobileMenu"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-[0.98]"
                class="relative w-full max-w-md overflow-hidden rounded-[34px] border border-white/60 bg-white shadow-[0_28px_80px_rgba(17,17,17,0.18)]"
            >
                <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-[rgba(203,20,139,0.10)] blur-3xl"></div>
                <div class="absolute -right-8 top-24 h-40 w-40 rounded-full bg-[rgba(98,10,136,0.10)] blur-3xl"></div>
                <div class="absolute bottom-0 left-1/2 h-48 w-48 -translate-x-1/2 rounded-full bg-[rgba(203,20,139,0.06)] blur-3xl"></div>

                <div class="relative border-b border-black/6 px-5 pb-4 pt-5">
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-3" @click="mobileMenu = false">
                            <img
                                src="{{ asset('/img/update logo.png') }}"
                                alt="Letter Getters Logo"
                                class="h-14 w-auto"
                            >
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="lg-btn-primary rounded-full px-5 py-2.5 text-sm font-medium">
                                Sign In
                            </a>

                            <button
                                type="button"
                                @click="mobileMenu = false"
                                class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-black/70 bg-white text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]"
                            >
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative px-5 pb-5 pt-4">
                    <nav class="space-y-2">
                        <a
                            href="{{ route('home') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('home') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>Home</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a>

                        <a
                            href="{{ route('how-it-works') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('how-it-works') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>How It Works</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a>

                        <a
                            href="{{ route('pathways') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('pathways') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>Pathways</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a>

                        <a
                            href="{{ route('safety') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('safety') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>Safety</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a>

                        {{-- <a
                            href="{{ route('subscription') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('subscription') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>Subscription</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a> --}}

                        <a
                            href="{{ route('support') }}"
                            @click="mobileMenu = false"
                            class="lg-mobile-link flex items-center justify-between rounded-[22px] px-5 py-4 text-[28px] font-medium leading-none {{ request()->routeIs('support') ? 'bg-[#fbf1f9] text-[#620A88]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            <span>Support</span>
                            <i class="fa-solid fa-arrow-right text-sm opacity-50"></i>
                        </a>
                    </nav>

                    <div class="mt-5 border-t border-black/6 pt-5">
                        <a
                            href="{{ route('register') }}"
                            @click="mobileMenu = false"
                            class="flex w-full items-center justify-center rounded-[22px] border border-black/10 bg-white px-5 py-4 text-base font-medium text-neutral-800 shadow-sm transition hover:border-[#CB148B] hover:text-[#CB148B]"
                        >
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>