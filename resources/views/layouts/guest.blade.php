<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Letter Getters') }}</title>
    <meta name="description" content="Letter Getters - A safe, joyful pen pal platform for children, parents, and adults.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --lg-pink: #CB148B;
            --lg-purple: #620A88;
            --lg-black: #111111;
            --lg-white: #ffffff;
            --lg-soft: #fff7fc;
            --lg-soft-purple: #f7efff;
            --lg-border: rgba(17, 17, 17, 0.08);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #ffffff;
            color: #111111;
        }

        .lg-gradient-text {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .lg-gradient-bg {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
        }

        .lg-soft-gradient {
            background:
                radial-gradient(circle at top left, rgba(203, 20, 139, 0.14), transparent 30%),
                radial-gradient(circle at bottom right, rgba(98, 10, 136, 0.16), transparent 32%),
                #ffffff;
        }

        .lg-card {
            background: #ffffff;
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 18px 50px rgba(17, 17, 17, 0.06);
        }

        .lg-btn-primary {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .lg-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(98, 10, 136, 0.22);
        }

        .lg-btn-outline {
            border: 1px solid rgba(17, 17, 17, 0.12);
            color: #111111;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .lg-btn-outline:hover {
            border-color: rgba(203, 20, 139, 0.35);
            color: var(--lg-pink);
            transform: translateY(-2px);
        }

        .lg-badge {
            background: rgba(203, 20, 139, 0.08);
            color: var(--lg-pink);
            border: 1px solid rgba(203, 20, 139, 0.15);
        }

        .lg-section-title {
            letter-spacing: -0.03em;
        }

        .lg-logo-mark {
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
            box-shadow: 0 12px 30px rgba(98, 10, 136, 0.22);
        }

        .lg-nav-link {
            position: relative;
            transition: color 0.25s ease;
        }

        .lg-nav-link:hover {
            color: var(--lg-pink);
        }

        .lg-nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--lg-pink), var(--lg-purple));
            transition: width 0.3s ease;
        }

        .lg-nav-link:hover::after {
            width: 100%;
        }

        .lg-glow {
            position: absolute;
            inset: auto;
            border-radius: 9999px;
            filter: blur(80px);
            opacity: 0.25;
            pointer-events: none;
        }

        .lg-hero-card {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,255,255,0.92));
            border: 1px solid rgba(17, 17, 17, 0.08);
            box-shadow: 0 25px 70px rgba(17, 17, 17, 0.08);
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    @include('partials.guest-header')

    <main>
        @yield('content')
    </main>

    @include('partials.guest-footer')
</body>
</html>