<header class="sticky top-0 z-50 border-b border-black/5 bg-white/90 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
           

            <div>
                <img src="{{ asset('/img/update logo.png') }}" alt="Letter Getters Logo" class="h-26 w-26">
            </div>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <a href="#home" class="lg-nav-link text-sm font-semibold text-black/80">Home</a>
            <a href="#how-it-works" class="lg-nav-link text-sm font-semibold text-black/80">How It Works</a>
            <a href="#pathways" class="lg-nav-link text-sm font-semibold text-black/80">Pathways</a>
            <a href="#safety" class="lg-nav-link text-sm font-semibold text-black/80">Safety</a>
            <a href="#subscription" class="lg-nav-link text-sm font-semibold text-black/80">Subscription</a>
            <a href="#contact" class="lg-nav-link text-sm font-semibold text-black/80">Contact</a>
        </nav>

        <div class="hidden items-center gap-3 md:flex">
            <a href="{{ route('login') }}" class="lg-btn-outline rounded-full px-5 py-2.5 text-sm font-semibold">
                Sign In
            </a>
            <a href="{{ route('register') }}" class="lg-btn-primary rounded-full px-5 py-2.5 text-sm font-semibold">
                Get Started
            </a>
        </div>

        <div class="md:hidden">
            <a href="{{ route('login') }}" class="lg-btn-primary rounded-full px-4 py-2 text-sm font-semibold">
                Sign In
            </a>
        </div>
    </div>
</header>