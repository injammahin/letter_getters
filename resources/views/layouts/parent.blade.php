<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parent Panel') - {{ config('app.name', 'Letter Getters') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --lg-pink: #CB148B;
            --lg-purple: #620A88;
            --lg-black: #111111;
            --lg-bg: #f8f5fb;
            --lg-card: #ffffff;
            --lg-border: rgba(17, 17, 17, 0.08);
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            background: var(--lg-bg);
            color: #111827;
        }

        .parent-gradient {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
        }

        .parent-card {
            background: var(--lg-card);
            border: 1px solid var(--lg-border);
            box-shadow: 0 18px 45px rgba(17, 17, 17, 0.05);
        }

        .parent-menu-link {
            transition: all 0.25s ease;
        }

        .parent-menu-link:hover {
            background: rgba(203, 20, 139, 0.08);
            color: var(--lg-pink);
        }

        .parent-menu-link-active {
            background: linear-gradient(135deg, rgba(203, 20, 139, 0.14), rgba(98, 10, 136, 0.12));
            color: #111827;
            border: 1px solid rgba(203, 20, 139, 0.18);
        }

        .parent-soft-gradient {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.14), transparent 28%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.16), transparent 28%),
                #ffffff;
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    <div
        x-data="{
            mobileSidebar: false,
            profileOpen: false,
            notificationOpen: false
        }"
        class="min-h-screen"
    >
        <div
            x-show="mobileSidebar"
            x-cloak
            @click="mobileSidebar = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        @include('partials.parent.sidebar')

        <div class="min-h-screen lg:pl-72">
            @include('partials.parent.header')

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>