<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Adult Dashboard') - {{ config('app.name', 'Letter Getters') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --adult-primary: #620A88;
            --adult-accent: #CB148B;
            --adult-bg: #f8f7fb;
            --adult-card: #ffffff;
            --adult-border: rgba(17, 17, 17, 0.08);
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            background: var(--adult-bg);
            color: #111827;
        }

        .adult-card {
            background: var(--adult-card);
            border: 1px solid var(--adult-border);
            box-shadow: 0 18px 50px rgba(17, 17, 17, 0.05);
        }

        .adult-gradient {
            background: linear-gradient(135deg, var(--adult-accent), var(--adult-primary));
        }

        .adult-soft-gradient {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.10), transparent 28%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.10), transparent 30%),
                #ffffff;
        }

        .adult-nav-link {
            transition: all 0.25s ease;
        }

        .adult-nav-link:hover {
            color: var(--adult-accent);
        }

        .adult-nav-link-active {
            color: var(--adult-primary);
            font-weight: 600;
        }

        .adult-fade-up {
            animation: adultFadeUp .55s ease-out both;
        }

        @keyframes adultFadeUp {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    <div x-data="{ mobileMenu: false, profileOpen: false, notificationOpen: false }" class="min-h-screen">
        @include('partials.adult.header')

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>