<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Letter Getters') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --lg-pink: #CB148B;
            --lg-purple: #620A88;
            --lg-black: #111111;
            --lg-white: #ffffff;
            --lg-bg: #f8f5fb;
            --lg-card: #ffffff;
            --lg-border: rgba(17, 17, 17, 0.08);
            --lg-muted: #6b7280;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            background: var(--lg-bg);
            color: #111827;
        }

        .admin-gradient {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
        }

        .admin-gradient-soft {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.16), transparent 28%),
                #ffffff;
        }

        .admin-card {
            background: var(--lg-card);
            border: 1px solid var(--lg-border);
            box-shadow: 0 16px 45px rgba(17, 17, 17, 0.05);
        }

        .admin-menu-link {
            transition: all 0.25s ease;
        }

        .admin-menu-link:hover {
            background: rgba(203, 20, 139, 0.08);
            color: var(--lg-pink);
        }

        .admin-menu-link-active {
            background: linear-gradient(135deg, rgba(203, 20, 139, 0.14), rgba(98, 10, 136, 0.12));
            color: #111827;
            border: 1px solid rgba(203, 20, 139, 0.18);
        }

        .admin-submenu-link {
            transition: all 0.25s ease;
        }

        .admin-submenu-link:hover {
            color: var(--lg-pink);
            transform: translateX(2px);
        }

        .admin-submenu-link-active {
            color: var(--lg-pink);
            font-weight: 700;
        }

        .stat-shine {
            position: relative;
            overflow: hidden;
        }

        .stat-shine::after {
            content: "";
            position: absolute;
            top: -20%;
            right: -30%;
            width: 140px;
            height: 140px;
            background: rgba(255,255,255,0.18);
            border-radius: 999px;
            filter: blur(4px);
        }

        .ring-chart {
            width: 92px;
            height: 92px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            position: relative;
        }

        .ring-chart::before {
            content: "";
            width: 62px;
            height: 62px;
            border-radius: 999px;
            background: #fff;
            position: absolute;
        }

        .ring-chart span {
            position: relative;
            z-index: 2;
            font-weight: 800;
            font-size: 14px;
            color: #111827;
        }

        .progress-track {
            height: 10px;
            border-radius: 999px;
            background: #f1edf6;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
        }

        .mini-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
        }
    </style>
</head>
<body class="min-h-screen antialiased">
<div
    x-data="{
        mobileSidebar: false,
        desktopCollapsed: false,
        notificationOpen: false,
        profileOpen: false
    }"
    class="min-h-screen"
>
    <div
        x-show="mobileSidebar"
        x-cloak
        @click="mobileSidebar = false"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    ></div>

    @include('partials.admin.sidebar')

    <div
        class="min-h-screen transition-all duration-300"
        :class="desktopCollapsed ? 'lg:pl-24' : 'lg:pl-72'"
    >
        @include('partials.admin.header')

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>