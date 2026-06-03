@props([
    'showcase' => [],
])

@php
    $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
    $authMode = match (true) {
        $currentRouteName === 'register' => 'register',
        $currentRouteName === 'login' => 'login',
        in_array($currentRouteName, ['password.request', 'password.reset'], true) => 'recovery',
        $currentRouteName === 'otp.verify' => 'verification',
        default => 'default',
    };

    $isAuthPair = in_array($authMode, ['login', 'register'], true);
    $defaultPanel = [
        'login' => [
            'eyebrow' => __('New here?'),
            'title' => __('Hello, friend'),
            'text' => __('Create a customer account to browse services, place orders, and track every print request in one place.'),
            'cta' => __('Sign Up'),
            'href' => route('register'),
        ],
        'register' => [
            'eyebrow' => __('Already with us?'),
            'title' => __('Welcome Back!'),
            'text' => __('Sign in with your existing account to continue orders, checkout, and account verification.'),
            'cta' => __('Log In'),
            'href' => route('login'),
        ],
        'default' => [
            'eyebrow' => $showcase['kicker'] ?? __('Secure Access'),
            'title' => $showcase['metric_value'] ?? __('Printify & Co.'),
            'text' => $showcase['metric_text'] ?? __('Protected customer access for orders, checkout, and account recovery.'),
            'cta' => __('Home'),
            'href' => route('home'),
        ],
    ];

    $panel = $defaultPanel[$authMode] ?? $defaultPanel['default'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Printify & Co.') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --auth-bg: #06111f;
                --auth-card: #ffffff;
                --auth-ink: #151927;
                --auth-muted: #667085;
                --auth-line: #e4e7ec;
                --auth-field: #f2f4f7;
                --auth-orange: #ff6b2b;
                --auth-gold: #ffcf26;
                --auth-red: #fc4f4f;
                --auth-navy: #172033;
            }

            @keyframes authCardIn {
                from { opacity: 0; transform: translateY(22px) rotateX(4deg); }
                to { opacity: 1; transform: translateY(0) rotateX(0); }
            }

            @keyframes authPanelFlipLogin {
                0% { transform: translateX(-115%) rotateY(-42deg) scale(0.94); opacity: 0.22; filter: blur(8px); }
                62% { transform: translateX(8%) rotateY(8deg) scale(1.02); opacity: 1; filter: blur(0); }
                100% { transform: translateX(0) rotateY(0) scale(1); opacity: 1; filter: blur(0); }
            }

            @keyframes authPanelFlipRegister {
                0% { transform: translateX(115%) rotateY(42deg) scale(0.94); opacity: 0.22; filter: blur(8px); }
                62% { transform: translateX(-8%) rotateY(-8deg) scale(1.02); opacity: 1; filter: blur(0); }
                100% { transform: translateX(0) rotateY(0) scale(1); opacity: 1; filter: blur(0); }
            }

            @keyframes authFormFade {
                from { opacity: 0; transform: scale(0.96) translateY(10px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }

            @keyframes authSwitchOut {
                0% { transform: rotateY(0deg) translateX(0) scale(1); filter: blur(0); opacity: 1; }
                35% { transform: rotateY(-10deg) translateX(-10px) scale(1.01); filter: blur(0); opacity: 1; }
                100% { transform: rotateY(-58deg) translateX(-56px) scale(0.92); filter: blur(7px); opacity: 0.18; }
            }

            @keyframes authFlipPanelExit {
                0% { transform: translateX(0) rotateY(0deg) scale(1); opacity: 1; filter: blur(0); }
                44% { transform: translateX(-16px) rotateY(-22deg) scale(1.02); opacity: 1; filter: blur(0); }
                100% { transform: translateX(-86%) rotateY(-78deg) scale(0.9); opacity: 0.12; filter: blur(7px); }
            }

            @keyframes authFlipPanelExitReverse {
                0% { transform: translateX(0) rotateY(0deg) scale(1); opacity: 1; filter: blur(0); }
                44% { transform: translateX(16px) rotateY(22deg) scale(1.02); opacity: 1; filter: blur(0); }
                100% { transform: translateX(86%) rotateY(78deg) scale(0.9); opacity: 0.12; filter: blur(7px); }
            }

            @keyframes authPanelGlow {
                0%, 100% { transform: translate3d(-7%, -4%, 0) scale(1); opacity: 0.38; }
                50% { transform: translate3d(5%, 3%, 0) scale(1.08); opacity: 0.62; }
            }

            @keyframes authBgCrossfade {
                0% { opacity: 0; transform: scale(1.04); }
                6% { opacity: 1; }
                31% { opacity: 1; }
                39% { opacity: 0; transform: scale(1.09); }
                100% { opacity: 0; transform: scale(1.09); }
            }

            * {
                box-sizing: border-box;
            }

            body.auth-page {
                min-height: 100vh;
                margin: 0;
                overflow-x: hidden;
                background:
                    radial-gradient(circle at 16% 12%, rgba(251, 191, 36, 0.20), transparent 28%),
                    radial-gradient(circle at 82% 84%, rgba(249, 115, 22, 0.20), transparent 30%),
                    linear-gradient(135deg, rgba(6, 17, 31, 0.97), rgba(13, 27, 43, 0.92) 42%, rgba(17, 24, 39, 0.96)),
                    var(--auth-bg);
                color: var(--auth-ink);
                font-family: "Montserrat", "Figtree", system-ui, sans-serif;
                -webkit-font-smoothing: antialiased;
            }

            .auth-bg-slideshow {
                position: fixed;
                inset: 0;
                z-index: 0;
                overflow: hidden;
                background: #06111f url('/images/auth/auth-bg-ink-printer.jpg') center / cover no-repeat;
                pointer-events: none;
            }

            .auth-bg-slideshow span {
                position: absolute;
                inset: 0;
                background-position: center;
                background-size: cover;
                opacity: 0;
                transform: scale(1.04);
                animation: authBgCrossfade 24s ease-in-out infinite;
                will-change: opacity, transform;
            }

            .auth-bg-slideshow span:nth-child(1) {
                background-image: url('/images/auth/auth-bg-ink-printer.jpg');
                opacity: 1;
            }

            .auth-bg-slideshow span:nth-child(2) {
                background-image: url('/images/auth/auth-bg-3d-workshop.jpg');
                animation-delay: 8s;
            }

            .auth-bg-slideshow span:nth-child(3) {
                background-image: url('/images/auth/auth-bg-printer-close.jpg');
                animation-delay: 16s;
            }

            .auth-bg-slideshow::after {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(rgba(255,255,255,0.032) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.032) 1px, transparent 1px),
                    radial-gradient(circle at 18% 12%, rgba(251, 191, 36, 0.16), transparent 26%),
                    radial-gradient(circle at 82% 88%, rgba(249, 115, 22, 0.18), transparent 30%),
                    linear-gradient(115deg, rgba(6,17,31,0.94), rgba(6,17,31,0.64) 46%, rgba(6,17,31,0.96));
                background-size: 72px 72px, 72px 72px, auto, auto, auto;
            }

            .auth-page-shell {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                perspective: 1500px;
                position: relative;
                z-index: 1;
                isolation: isolate;
            }

            .auth-page-shell::before {
                content: "";
                position: fixed;
                inset: 0;
                z-index: -2;
                background:
                    radial-gradient(circle at 18% 18%, rgba(251, 191, 36, 0.18), transparent 28%),
                    radial-gradient(circle at 82% 76%, rgba(34, 211, 238, 0.10), transparent 30%);
                opacity: 0.95;
                pointer-events: none;
            }

            .auth-page-shell::after {
                content: "";
                position: fixed;
                inset: 0;
                z-index: -1;
                background:
                    radial-gradient(circle at 50% 38%, rgba(255,255,255,0.08), transparent 40%);
                background-size: cover;
                opacity: 0.7;
                pointer-events: none;
            }

            .auth-stage {
                position: relative;
                width: min(960px, 100%);
                min-height: 580px;
                border-radius: 28px;
                border: 1px solid rgba(255,255,255,0.14);
                background:
                    linear-gradient(135deg, rgba(255,255,255,0.10), rgba(255,255,255,0.025)),
                    rgba(7, 18, 33, 0.58);
                box-shadow:
                    0 34px 90px rgba(2, 8, 23, 0.42),
                    inset 0 1px 0 rgba(255,255,255,0.08);
                overflow: hidden;
                display: grid;
                animation: authCardIn 0.6s cubic-bezier(.22,1,.36,1);
                transform-style: preserve-3d;
                backdrop-filter: blur(18px);
            }

            .auth-stage--split {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }

            .auth-stage--single {
                width: min(520px, 100%);
                min-height: auto;
                padding: 0;
            }

            .auth-stage.is-flipping {
                pointer-events: none;
                animation: authSwitchOut 0.56s cubic-bezier(.2,.8,.2,1) both;
            }

            .auth-stage.is-flipping .auth-flip-panel {
                animation: authFlipPanelExit 0.56s cubic-bezier(.2,.8,.2,1) both;
            }

            .auth-stage.auth-mode-register.is-flipping .auth-flip-panel {
                animation-name: authFlipPanelExitReverse;
            }

            .auth-form-panel {
                position: relative;
                z-index: 2;
                min-width: 0;
                background:
                    linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.94));
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 36px;
                box-shadow: 0 0 0 1px rgba(255,255,255,0.08);
            }

            .auth-mode-login .auth-form-panel {
                order: 1;
            }

            .auth-mode-register .auth-form-panel {
                order: 2;
            }

            .auth-stage--single .auth-form-panel {
                padding: 30px;
            }

            .auth-card {
                width: 100%;
                max-width: 390px;
                animation: authFormFade 0.58s cubic-bezier(.22,1,.36,1);
            }

            .auth-stage--single .auth-card {
                max-width: 440px;
            }

            .auth-flip-panel {
                position: relative;
                z-index: 1;
                order: 2;
                min-width: 0;
                padding: 44px 42px;
                color: #fff;
                background:
                    linear-gradient(145deg, rgba(6, 17, 31, 0.88), rgba(13, 27, 43, 0.72) 42%, rgba(249, 115, 22, 0.70)),
                    url('/images/auth/auth-bg-3d-workshop.jpg') center center / cover no-repeat;
                background-blend-mode: multiply, normal;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                transform-origin: left center;
                overflow: hidden;
                animation: authPanelFlipLogin 0.92s cubic-bezier(.18,.88,.2,1.08);
                backface-visibility: hidden;
                transform-style: preserve-3d;
            }

            .auth-mode-register .auth-flip-panel {
                order: 1;
                transform-origin: right center;
                animation-name: authPanelFlipRegister;
            }

            .auth-flip-panel::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 22% 18%, rgba(255,255,255,0.20), transparent 25%),
                    radial-gradient(circle at 86% 82%, rgba(251,191,36,0.30), transparent 30%),
                    linear-gradient(rgba(255,255,255,0.065) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.065) 1px, transparent 1px);
                background-size: auto, auto, 44px 44px, 44px 44px;
                opacity: 0.62;
                pointer-events: none;
            }

            .auth-flip-panel::after {
                content: "";
                position: absolute;
                width: 310px;
                height: 310px;
                right: -95px;
                bottom: -95px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(251,191,36,0.32), rgba(249,115,22,0.16), transparent 70%);
                filter: blur(2px);
                pointer-events: none;
                animation: authPanelGlow 8s ease-in-out infinite;
            }

            .auth-flip-content {
                position: relative;
                z-index: 1;
                max-width: 330px;
            }

            .auth-logo-row {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 24px;
                color: #fff;
                font-weight: 900;
                letter-spacing: -0.04em;
            }

            .auth-logo-badge {
                width: 46px;
                height: 46px;
                border-radius: 16px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(255,255,255,0.18);
                border: 1px solid rgba(255,255,255,0.24);
                box-shadow: 0 14px 34px rgba(90, 33, 6, 0.18);
            }

            .auth-flip-eyebrow,
            .auth-eyebrow {
                margin: 0;
                font-size: 11px;
                font-weight: 900;
                letter-spacing: 0.22em;
                text-transform: uppercase;
            }

            .auth-flip-eyebrow {
                color: rgba(255,255,255,0.78);
            }

            .auth-flip-title {
                margin: 12px 0 0;
                font-size: clamp(34px, 5vw, 46px);
                line-height: 0.98;
                font-weight: 900;
                letter-spacing: -0.06em;
            }

            .auth-flip-text {
                margin: 18px auto 0;
                color: rgba(255,255,255,0.88);
                font-size: 14px;
                line-height: 1.7;
                font-weight: 600;
            }

            .auth-flip-switch {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 150px;
                min-height: 46px;
                margin-top: 28px;
                border-radius: 999px;
                border: 1px solid rgba(255,255,255,0.86);
                color: #fff;
                background: rgba(255,255,255,0.08);
                text-decoration: none;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                transition: transform 0.18s ease, background 0.18s ease;
            }

            .auth-flip-switch:hover {
                transform: translateY(-2px);
                background: rgba(255,255,255,0.16);
            }

            .auth-home-link {
                position: absolute;
                top: 20px;
                left: 20px;
                z-index: 3;
                color: rgba(248, 250, 252, 0.88);
                font-size: 11px;
                font-weight: 900;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                text-decoration: none;
            }

            .auth-mode-register .auth-home-link {
                left: auto;
                right: 20px;
            }

            .auth-stage--single .auth-home-link {
                position: static;
                display: inline-flex;
                margin-bottom: 18px;
            }

            .auth-shell .auth-eyebrow {
                color: var(--auth-orange);
            }

            .auth-shell .auth-title {
                margin: 8px 0 0;
                color: var(--auth-ink);
                font-size: clamp(28px, 5vw, 38px);
                line-height: 1;
                font-weight: 900;
                letter-spacing: -0.06em;
            }

            .auth-shell .auth-subtitle {
                margin: 12px auto 0;
                color: var(--auth-muted);
                font-size: 13px;
                line-height: 1.65;
                font-weight: 600;
            }

            .auth-shell label,
            .auth-shell .choice-label {
                color: #475467;
                font-size: 11px;
                font-weight: 900;
                letter-spacing: 0.13em;
                text-transform: uppercase;
            }

            .auth-shell input[type="email"],
            .auth-shell input[type="password"],
            .auth-shell input[type="text"] {
                min-height: 48px;
                border: 0;
                border-radius: 0;
                background: var(--auth-field);
                color: var(--auth-ink);
                font-size: 14px;
                font-weight: 700;
                padding: 13px 14px;
                box-shadow: none;
            }

            .auth-shell input[type="email"]:focus,
            .auth-shell input[type="password"]:focus,
            .auth-shell input[type="text"]:focus {
                border: 0;
                box-shadow: 0 0 0 3px rgba(255, 107, 43, 0.16);
                outline: none;
            }

            .auth-shell input[type="checkbox"] {
                border-color: #cbd5e1;
                color: var(--auth-orange);
                box-shadow: none;
            }

            .auth-shell .primary-cta {
                position: relative;
                min-height: 46px;
                border-radius: 999px;
                border: 1px solid var(--auth-orange);
                background: var(--auth-orange);
                color: #fff;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: 0.16em;
                padding: 0 28px;
                text-transform: uppercase;
                box-shadow: 0 14px 28px rgba(255, 107, 43, 0.22);
                transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
            }

            .auth-shell .primary-cta:hover {
                transform: translateY(-1px);
                background: #f15f22;
                box-shadow: 0 18px 34px rgba(255, 107, 43, 0.28);
            }

            .auth-shell .auth-link {
                color: #2563eb;
                font-weight: 800;
                text-decoration-thickness: 2px;
            }

            .auth-google-btn {
                display: flex;
                min-height: 48px;
                width: 100%;
                align-items: center;
                justify-content: center;
                gap: 10px;
                border: 1px solid var(--auth-line);
                background: #fff;
                color: var(--auth-ink);
                border-radius: 0;
                padding: 12px 14px;
                text-decoration: none;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: 0.13em;
                text-transform: uppercase;
                transition: border-color 0.18s ease, background 0.18s ease, transform 0.18s ease;
            }

            .auth-google-btn:hover {
                transform: translateY(-1px);
                border-color: rgba(255, 107, 43, 0.36);
                background: #fff9f5;
            }

            .auth-google-mark {
                display: inline-flex;
                width: 28px;
                height: 28px;
                border-radius: 999px;
                align-items: center;
                justify-content: center;
                background: var(--auth-field);
                color: var(--auth-orange);
                font-weight: 900;
            }

            .auth-divider {
                display: flex;
                align-items: center;
                gap: 12px;
                color: #98a2b3;
                font-size: 11px;
                font-weight: 900;
                letter-spacing: 0.18em;
                text-transform: uppercase;
            }

            .auth-divider::before,
            .auth-divider::after {
                content: "";
                height: 1px;
                flex: 1;
                background: var(--auth-line);
            }

            .auth-note {
                border: 1px solid #fed7aa;
                border-radius: 14px;
                background: #fff7ed;
                color: #9a3412;
                padding: 12px 14px;
                font-size: 12px;
                line-height: 1.55;
                font-weight: 700;
            }

            .auth-note strong,
            .auth-note span {
                display: block;
            }

            .auth-note--danger {
                border-color: #fecdd3;
                background: #fff1f2;
                color: #be123c;
            }

            .auth-inline-switch {
                margin-top: 22px;
                text-align: center;
                color: var(--auth-muted);
                font-size: 13px;
                font-weight: 600;
            }

            .auth-action-hint {
                margin: 16px 0 0;
                color: var(--auth-muted);
                font-size: 12px;
                line-height: 1.55;
                font-weight: 600;
                text-align: center;
            }

            .remember-wrap {
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .remember-wrap span {
                margin: 0;
                color: var(--auth-muted);
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0;
                text-transform: none;
            }

            @media (max-width: 760px) {
                .auth-page-shell {
                    align-items: flex-start;
                    padding: 14px;
                }

                .auth-stage,
                .auth-stage--split {
                    min-height: auto;
                    grid-template-columns: 1fr;
                }

                .auth-mode-login .auth-form-panel,
                .auth-mode-register .auth-form-panel,
                .auth-mode-login .auth-flip-panel,
                .auth-mode-register .auth-flip-panel {
                    order: unset;
                }

                .auth-flip-panel {
                    min-height: 260px;
                    padding: 34px 24px;
                }

                .auth-form-panel {
                    padding: 34px 22px 26px;
                }

                .auth-card {
                    max-width: 100%;
                }

                .auth-home-link,
                .auth-mode-register .auth-home-link {
                    top: 14px;
                    left: 16px;
                    right: auto;
                    color: rgba(255,255,255,0.88);
                }
            }

            @media (max-width: 520px) {
                .auth-page-shell {
                    padding: 14px;
                }

                .auth-stage {
                    border-radius: 24px;
                    width: 100%;
                    min-height: auto;
                    margin: 10px 0;
                }

                .auth-form-panel {
                    padding: 32px 18px 26px;
                }

                .auth-shell .auth-title {
                    font-size: 30px;
                }

                .auth-flip-title {
                    font-size: 34px;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased auth-page">
        <div class="auth-bg-slideshow" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <main class="auth-page-shell">
            <section class="auth-stage auth-mode-{{ $authMode }} {{ $isAuthPair ? 'auth-stage--split' : 'auth-stage--single' }}" id="authStage">
                @if ($isAuthPair)
                    <a href="{{ route('home') }}" class="auth-home-link">{{ __('Home') }}</a>
                @endif

                @if ($isAuthPair && $authMode === 'register')
                    <aside class="auth-flip-panel">
                        <div class="auth-flip-content">
                            <div class="auth-logo-row">
                                <span class="auth-logo-badge">
                                    <x-application-logo class="h-9 w-9 fill-current text-white" />
                                </span>
                                <span>{{ config('app.name', 'Printify & Co.') }}</span>
                            </div>
                            <p class="auth-flip-eyebrow">{{ $panel['eyebrow'] }}</p>
                            <h1 class="auth-flip-title">{{ $panel['title'] }}</h1>
                            <p class="auth-flip-text">{{ $panel['text'] }}</p>
                            <a href="{{ $panel['href'] }}" class="auth-flip-switch" data-auth-switch>{{ $panel['cta'] }}</a>
                        </div>
                    </aside>
                @endif

                <div class="auth-form-panel">
                    <div class="auth-card auth-shell">
                        @unless ($isAuthPair)
                            <a href="{{ route('home') }}" class="auth-home-link">{{ __('Home') }}</a>
                        @endunless

                        {{ $slot }}
                    </div>
                </div>

                @if ($isAuthPair && $authMode === 'login')
                    <aside class="auth-flip-panel">
                        <div class="auth-flip-content">
                            <div class="auth-logo-row">
                                <span class="auth-logo-badge">
                                    <x-application-logo class="h-9 w-9 fill-current text-white" />
                                </span>
                                <span>{{ config('app.name', 'Printify & Co.') }}</span>
                            </div>
                            <p class="auth-flip-eyebrow">{{ $panel['eyebrow'] }}</p>
                            <h1 class="auth-flip-title">{{ $panel['title'] }}</h1>
                            <p class="auth-flip-text">{{ $panel['text'] }}</p>
                            <a href="{{ $panel['href'] }}" class="auth-flip-switch" data-auth-switch>{{ $panel['cta'] }}</a>
                        </div>
                    </aside>
                @endif
            </section>
        </main>

        <script>
            document.querySelectorAll('[data-auth-switch]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    const stage = document.getElementById('authStage');
                    const target = link.getAttribute('href');
                    if (!stage || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                    if (!target) return;

                    event.preventDefault();
                    stage.classList.add('is-flipping');
                    link.setAttribute('aria-disabled', 'true');
                    window.setTimeout(() => {
                        window.location.assign(target);
                    }, 540);
                });
            });
        </script>
    </body>
</html>
