@props([
    'showcase' => [],
])

@php
    $defaultShowcase = [
        'kicker' => __('Print Securely'),
        'title_intro' => __('Bring every'),
        'title_focus' => __('print request to life.'),
        'text' => __('Manage secure sign-ins, password recovery, and customer verification in one polished experience built for speed, trust, and clarity.'),
        'chips' => [
            __('Fast OTP verification for protected customer access'),
            __('Smoother recovery flow with clear next-step guidance'),
            __('Production-ready auth flow with local Mailpit testing support'),
        ],
        'metric_value' => __('5 min'),
        'metric_text' => __('Recommended email OTP lifetime for a balanced and usable verification flow.'),
    ];

    $showcaseData = array_merge($defaultShowcase, $showcase);
    $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
    $authMode = match (true) {
        $currentRouteName === 'register' => 'register',
        $currentRouteName === 'login' => 'login',
        in_array($currentRouteName, ['password.request', 'password.reset'], true) => 'recovery',
        $currentRouteName === 'otp.verify' => 'verification',
        default => 'default',
    };
    $switchPanel = match ($authMode) {
        'login' => [
            'eyebrow' => __('New here?'),
            'title' => __('Create your account'),
            'text' => __('Start with a secure customer account so you can browse services, place orders, and track progress in one place.'),
            'cta' => __('Go to Sign Up'),
            'href' => route('register'),
        ],
        'register' => [
            'eyebrow' => __('Client Guide'),
            'title' => __('Before you create your account'),
            'text' => __('A few quick reminders will help you finish registration smoothly and get verified without delays.'),
            'items' => [
                __('Use an active email address because your OTP verification code will be sent there right after sign up.'),
                __('Choose a strong password you can remember easily for future sign-ins and account recovery.'),
                __('Complete your OTP check within five minutes so protected customer access can be unlocked right away.'),
            ],
        ],
        default => null,
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;manrope:500,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --auth-ink: #172033;
                --auth-subtle: #667085;
                --auth-subtle-strong: #475467;
                --auth-line: rgba(255, 255, 255, 0.22);
                --auth-glow: rgba(251, 191, 36, 0.24);
                --auth-glow-strong: rgba(34, 211, 238, 0.16);
                --auth-bg-start: #06111f;
                --auth-bg-mid: #0d1b2b;
                --auth-bg-end: #111827;
                --auth-accent-start: #f59e0b;
                --auth-accent-mid: #fb923c;
                --auth-accent-end: #f97316;
            }

            @keyframes authFloat {
                0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
                50% { transform: translate3d(0, -14px, 0) scale(1.04); }
            }

            @keyframes authShimmer {
                0% { transform: translateX(-120%) skewX(-16deg); opacity: 0; }
                20% { opacity: 0.28; }
                100% { transform: translateX(220%) skewX(-16deg); opacity: 0; }
            }

            @keyframes authRise {
                from { opacity: 0; transform: translateY(26px) scale(0.98); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            @keyframes authPanelSlide {
                from { opacity: 0; transform: translate3d(90px, 0, 0) scale(0.96); filter: blur(8px); }
                to { opacity: 1; transform: translate3d(0, 0, 0) scale(1); filter: blur(0); }
            }

            @keyframes authContentSlide {
                from { opacity: 0; transform: translate3d(-46px, 0, 0); }
                to { opacity: 1; transform: translate3d(0, 0, 0); }
            }

            @keyframes authFormSlide {
                from { opacity: 0; transform: translate3d(56px, 0, 0); }
                to { opacity: 1; transform: translate3d(0, 0, 0); }
            }

            @keyframes authShowcaseSlide {
                from { opacity: 0; transform: translate3d(-96px, 0, 0) scale(0.97); filter: blur(10px); }
                to { opacity: 1; transform: translate3d(0, 0, 0) scale(1); filter: blur(0); }
            }

            @keyframes authPulse {
                0%, 100% { opacity: 0.68; transform: scale(1); }
                50% { opacity: 0.92; transform: scale(1.05); }
            }

            @keyframes authDrift {
                0% { transform: translate3d(0, 0, 0) rotate(0deg); }
                50% { transform: translate3d(18px, -14px, 0) rotate(5deg); }
                100% { transform: translate3d(0, 0, 0) rotate(0deg); }
            }

            @keyframes authChipSweep {
                0%, 100% { transform: translateX(-62%); opacity: 0.22; }
                50% { transform: translateX(42%); opacity: 0.62; }
            }

            .auth-page {
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
                background:
                    radial-gradient(circle at top left, rgba(251, 191, 36, 0.15), transparent 34%),
                    radial-gradient(circle at top right, rgba(34, 211, 238, 0.14), transparent 30%),
                    radial-gradient(circle at bottom center, rgba(59, 130, 246, 0.12), transparent 35%),
                    linear-gradient(135deg, var(--auth-bg-start), var(--auth-bg-mid) 44%, var(--auth-bg-end));
            }

            .auth-page::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
                background-size: 72px 72px;
                mask-image: radial-gradient(circle at center, rgba(0,0,0,0.9), transparent 88%);
                pointer-events: none;
            }

            .auth-page::after {
                content: "";
                position: absolute;
                inset: auto auto 7% 8%;
                width: 180px;
                height: 180px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
                animation: authDrift 14s ease-in-out infinite;
                pointer-events: none;
            }

            .auth-orb {
                position: absolute;
                border-radius: 999px;
                filter: blur(4px);
                animation: authFloat 12s ease-in-out infinite;
                pointer-events: none;
            }

            .auth-orb--amber {
                top: 8%;
                left: -6%;
                width: 260px;
                height: 260px;
                background: radial-gradient(circle, rgba(251, 191, 36, 0.34), rgba(251, 191, 36, 0.02) 70%);
            }

            .auth-orb--blue {
                right: -3%;
                bottom: 8%;
                width: 320px;
                height: 320px;
                background: radial-gradient(circle, rgba(34, 211, 238, 0.23), rgba(34, 211, 238, 0.02) 70%);
                animation-delay: -4s;
            }

            .auth-stage {
                width: 100%;
                max-width: 1180px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1.4rem;
                min-height: calc(100vh - 2.5rem);
                padding: 1rem;
                position: relative;
                overflow: hidden;
                border-radius: 42px;
                border: 1px solid rgba(255,255,255,0.1);
                background:
                    linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.015)),
                    rgba(7, 18, 33, 0.42);
                box-shadow:
                    inset 0 1px 0 rgba(255,255,255,0.06),
                    0 30px 90px rgba(2, 8, 23, 0.34);
                backdrop-filter: blur(14px);
                view-transition-name: auth-shell;
            }

            .auth-stage::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
                background-size: 44px 44px;
                opacity: 0.34;
                pointer-events: none;
            }

            .auth-stage::after {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 36%;
                height: 118%;
                transform: translate(-50%, -50%);
                background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.01));
                border-radius: 999px;
                filter: blur(60px);
                opacity: 0.34;
                pointer-events: none;
            }

            .auth-showcase {
                position: relative;
                display: none;
                flex: 1 1 0;
                min-height: 0;
                padding: 2rem 1.9rem;
                border-radius: 38px;
                overflow-x: hidden;
                overflow-y: auto;
                background:
                    radial-gradient(circle at 15% 16%, rgba(251, 191, 36, 0.26), transparent 30%),
                    radial-gradient(circle at 80% 24%, rgba(34, 211, 238, 0.22), transparent 26%),
                    linear-gradient(150deg, rgba(10, 23, 41, 0.98), rgba(13, 27, 43, 0.92) 48%, rgba(17, 24, 39, 0.9));
                border: 1px solid rgba(255,255,255,0.12);
                box-shadow:
                    inset 0 1px 0 rgba(255,255,255,0.08),
                    0 28px 80px rgba(6, 17, 31, 0.34);
                animation: authShowcaseSlide 0.88s cubic-bezier(.22,1,.36,1);
                will-change: transform, opacity, filter;
                transition: transform 0.6s cubic-bezier(.22,1,.36,1), box-shadow 0.4s ease;
            }

            .auth-showcase::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
                background-size: 56px 56px;
                opacity: 0.45;
                pointer-events: none;
            }

            .auth-showcase::after {
                content: "";
                position: absolute;
                inset: auto -12% -14% auto;
                width: 260px;
                height: 260px;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(249, 115, 22, 0.34), transparent 70%);
                filter: blur(10px);
                pointer-events: none;
            }

            .auth-showcase-inner {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100%;
            }

            .auth-showcase-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.9rem;
            }

            .auth-showcase-branding {
                display: flex;
                align-items: center;
                gap: 0.9rem;
            }

            .auth-showcase-badge {
                width: 64px;
                height: 64px;
                border-radius: 22px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(145deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06));
                border: 1px solid rgba(255,255,255,0.12);
                box-shadow: 0 18px 40px rgba(6, 17, 31, 0.3);
                backdrop-filter: blur(14px);
            }

            .auth-showcase-kicker {
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.28em;
                text-transform: uppercase;
                color: rgba(251, 191, 36, 0.92);
            }

            .auth-home-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 2.7rem;
                padding: 0.75rem 1rem;
                border-radius: 999px;
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.12);
                color: rgba(248, 250, 252, 0.92);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                backdrop-filter: blur(10px);
                transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease;
            }

            .auth-home-link:hover {
                transform: translateY(-1px);
                background: rgba(255,255,255,0.14);
                border-color: rgba(255,255,255,0.2);
                color: #fff;
            }

            .auth-mobile-home {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-top: 0.7rem;
                color: rgba(226, 232, 240, 0.9);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                transition: color 0.18s ease, transform 0.18s ease;
            }

            .auth-mobile-home:hover {
                color: #fff;
                transform: translateY(-1px);
            }

            .auth-showcase-brand {
                margin-top: 0.28rem;
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: 1.15rem;
                font-weight: 800;
                color: rgba(255,255,255,0.95);
                letter-spacing: -0.03em;
            }

            .auth-showcase-copy {
                max-width: 440px;
                margin-top: 1.65rem;
            }

            .auth-showcase-title {
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: clamp(1.95rem, 3.25vw, 3rem);
                line-height: 0.92;
                font-weight: 800;
                letter-spacing: -0.06em;
                color: #fff;
            }

            .auth-showcase-title strong {
                display: block;
                background: linear-gradient(120deg, #f8fafc, #fde68a 48%, #fb923c);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }

            .auth-showcase-text {
                margin-top: 0.92rem;
                max-width: 415px;
                font-size: 0.9rem;
                line-height: 1.58;
                color: rgba(226, 232, 240, 0.84);
            }

            .auth-showcase-stack {
                margin-top: 1.08rem;
                display: grid;
                gap: 0.68rem;
            }

            .auth-showcase-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                position: relative;
                width: 100%;
                min-width: 0;
                padding: 0.74rem 0.88rem;
                border-radius: 20px;
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
                backdrop-filter: blur(10px);
                overflow: hidden;
                isolation: isolate;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                animation: authContentSlide 0.8s cubic-bezier(.22,1,.36,1);
            }

            .auth-showcase-chip::after {
                content: "";
                position: absolute;
                inset: 1px auto 1px 1px;
                width: 34%;
                border-radius: inherit;
                background: linear-gradient(90deg, rgba(249, 115, 22, 0.28), rgba(251, 191, 36, 0.12), transparent);
                opacity: 0.34;
                animation: authChipSweep 6.4s ease-in-out infinite;
                pointer-events: none;
                z-index: 0;
            }

            .auth-showcase-chip:nth-child(2)::after {
                animation-delay: 1.1s;
                width: 42%;
            }

            .auth-showcase-chip:nth-child(3)::after {
                animation-delay: 2.2s;
                width: 50%;
            }

            .auth-showcase-chip-dot {
                width: 0.7rem;
                height: 0.7rem;
                border-radius: 999px;
                background: linear-gradient(135deg, #fde68a, #f97316);
                box-shadow: 0 0 0 6px rgba(249, 115, 22, 0.12);
                flex-shrink: 0;
                animation: authPulse 2.2s ease-in-out infinite;
                position: relative;
                z-index: 1;
            }

            .auth-showcase-chip-text {
                position: relative;
                z-index: 1;
                font-size: 0.85rem;
                line-height: 1.45;
                color: rgba(248, 250, 252, 0.92);
            }

            .auth-showcase-chip:hover {
                transform: translateX(6px);
                border-color: rgba(255,255,255,0.18);
                box-shadow:
                    inset 0 1px 0 rgba(255,255,255,0.08),
                    0 18px 32px rgba(2, 8, 23, 0.22);
            }

            .auth-showcase-chip:hover::after {
                opacity: 0.58;
            }

            .auth-showcase-footer {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 0.85rem;
                margin-top: 0.95rem;
            }

            .auth-switch-panel {
                position: relative;
                z-index: 2;
                width: min(100%, 410px);
                margin-top: 0.95rem;
                padding: 0.84rem 0.95rem;
                border-radius: 22px;
                background:
                    linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)),
                    rgba(15, 23, 42, 0.28);
                border: 1px solid rgba(255,255,255,0.14);
                box-shadow:
                    inset 0 1px 0 rgba(255,255,255,0.08),
                    0 18px 38px rgba(2, 8, 23, 0.18);
                backdrop-filter: blur(14px);
                animation: authContentSlide 0.9s cubic-bezier(.22,1,.36,1);
                overflow: hidden;
            }

            .auth-switch-panel::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(110deg, transparent 20%, rgba(255,255,255,0.08) 50%, transparent 80%);
                transform: translateX(-135%);
                animation: authShimmer 6.4s ease-in-out infinite;
                pointer-events: none;
            }

            .auth-switch-panel p {
                margin: 0;
            }

            .auth-switch-eyebrow {
                font-size: 0.68rem;
                font-weight: 800;
                letter-spacing: 0.26em;
                text-transform: uppercase;
                color: rgba(251, 191, 36, 0.9);
            }

            .auth-switch-title {
                margin-top: 0.45rem !important;
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: 0.94rem;
                line-height: 1.15;
                font-weight: 800;
                color: rgba(255,255,255,0.96);
            }

            .auth-switch-text {
                margin-top: 0.4rem !important;
                font-size: 0.77rem;
                line-height: 1.45;
                color: rgba(226, 232, 240, 0.78);
            }

            .auth-switch-list {
                margin: 0.82rem 0 0;
                padding: 0;
                list-style: none;
                display: grid;
                gap: 0.56rem;
            }

            .auth-switch-list li {
                position: relative;
                padding-left: 1rem;
                font-size: 0.73rem;
                line-height: 1.42;
                color: rgba(226, 232, 240, 0.84);
            }

            .auth-switch-list li::before {
                content: "";
                position: absolute;
                top: 0.45rem;
                left: 0;
                width: 0.34rem;
                height: 0.34rem;
                border-radius: 999px;
                background: linear-gradient(135deg, rgba(251, 191, 36, 0.96), rgba(249, 115, 22, 0.96));
                box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.14);
            }

            .auth-switch-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-top: 0.9rem;
                min-height: 2.75rem;
                padding: 0.78rem 1rem;
                border-radius: 999px;
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.94), rgba(249, 115, 22, 0.94));
                color: #fff;
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                box-shadow: 0 16px 30px rgba(249, 115, 22, 0.24);
                transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            }

            .auth-switch-link:hover {
                transform: translateY(-2px);
                filter: saturate(1.04);
                box-shadow: 0 20px 36px rgba(249, 115, 22, 0.3);
                color: #fff;
            }

            .auth-inline-switch {
                display: none;
                margin-top: 1.1rem;
                padding-top: 0.95rem;
                border-top: 1px solid rgba(226, 232, 240, 0.78);
                text-align: center;
            }

            .auth-inline-switch p {
                margin: 0;
                font-size: 0.86rem;
                line-height: 1.6;
                color: var(--auth-subtle);
            }

            .auth-inline-switch a {
                font-weight: 800;
            }

            .auth-action-hint {
                margin-top: 0.72rem;
                font-size: 0.74rem;
                line-height: 1.55;
                color: var(--auth-subtle);
                text-align: right;
            }

            .auth-showcase-metric {
                padding: 1rem 1.1rem;
                border-radius: 22px;
                background: rgba(255,255,255,0.07);
                border: 1px solid rgba(255,255,255,0.1);
                min-width: 170px;
            }

            .auth-showcase-metric strong {
                display: block;
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: 1.45rem;
                font-weight: 800;
                color: #fff;
            }

            .auth-showcase-metric span {
                display: block;
                margin-top: 0.28rem;
                font-size: 0.8rem;
                line-height: 1.5;
                color: rgba(226,232,240,0.72);
            }

            .auth-showcase-ring {
                position: relative;
                width: 152px;
                height: 152px;
                border-radius: 999px;
                border: 1px solid rgba(255,255,255,0.12);
                background:
                    radial-gradient(circle at center, rgba(255,255,255,0.06), transparent 58%),
                    linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
                overflow: hidden;
            }

            .auth-showcase-ring::before,
            .auth-showcase-ring::after {
                content: "";
                position: absolute;
                border-radius: 999px;
            }

            .auth-showcase-ring::before {
                inset: 18px;
                border: 1px solid rgba(255,255,255,0.16);
            }

            .auth-showcase-ring::after {
                inset: 34px;
                background: linear-gradient(145deg, rgba(249, 115, 22, 0.85), rgba(251, 191, 36, 0.78));
                animation: authPulse 2.8s ease-in-out infinite;
                box-shadow: 0 0 40px rgba(249, 115, 22, 0.28);
            }

            .auth-form-panel {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
                z-index: 2;
                transition: transform 0.6s cubic-bezier(.22,1,.36,1);
                overflow-x: hidden;
            }

            .auth-brand {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 88px;
                height: 88px;
                border-radius: 999px;
                background: linear-gradient(145deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06));
                border: 1px solid rgba(255,255,255,0.16);
                box-shadow: 0 24px 60px rgba(15, 23, 42, 0.34);
                backdrop-filter: blur(16px);
                overflow: hidden;
                animation: authRise 0.55s ease-out;
            }

            .auth-brand::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(110deg, transparent 25%, rgba(255,255,255,0.28) 50%, transparent 75%);
                animation: authShimmer 5.8s ease-in-out infinite;
            }

            .auth-brand::before {
                content: "";
                position: absolute;
                inset: 9px;
                border-radius: 999px;
                border: 1px solid rgba(255,255,255,0.18);
                pointer-events: none;
            }

            .auth-card {
                position: relative;
                width: 100%;
                max-width: 490px;
                padding: 1.85rem 1.65rem 1.5rem;
                background:
                    linear-gradient(180deg, rgba(255,255,255,0.98), rgba(249, 250, 251, 0.93)),
                    rgba(255,255,255,0.92);
                border: 1px solid rgba(255,255,255,0.45);
                border-radius: 34px;
                box-shadow:
                    0 26px 70px rgba(15, 23, 42, 0.30),
                    0 8px 24px rgba(15, 23, 42, 0.12);
                backdrop-filter: blur(18px);
                animation: authPanelSlide 0.72s cubic-bezier(.22,1,.36,1);
                will-change: transform, opacity, filter;
                transition: transform 0.6s cubic-bezier(.22,1,.36,1), box-shadow 0.4s ease;
                overflow-x: hidden;
            }

            .auth-card::before {
                content: "";
                position: absolute;
                inset: 1px;
                border-radius: 33px;
                border: 1px solid rgba(255,255,255,0.35);
                pointer-events: none;
            }

            .auth-card::after {
                content: "";
                position: absolute;
                top: -36%;
                right: -14%;
                width: 250px;
                height: 250px;
                background: radial-gradient(circle, var(--auth-glow), transparent 70%);
                pointer-events: none;
            }

            .auth-shell .auth-eyebrow {
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.34em;
                text-transform: uppercase;
                color: #b45309;
            }

            .auth-shell .auth-title {
                margin-top: 0.55rem;
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: clamp(1.85rem, 3.8vw, 2.45rem);
                line-height: 0.95;
                font-weight: 800;
                letter-spacing: -0.05em;
                color: var(--auth-ink);
            }

            .auth-shell .auth-subtitle {
                margin-top: 0.72rem;
                font-size: 0.94rem;
                line-height: 1.62;
                color: var(--auth-subtle);
            }

            .auth-shell .auth-note {
                margin-top: 0.8rem;
                padding: 0.82rem 0.92rem;
                border-radius: 20px;
                border: 1px solid rgba(245, 158, 11, 0.18);
                background: linear-gradient(135deg, rgba(255, 247, 237, 0.96), rgba(249, 250, 251, 0.94));
                color: #9a3412;
                font-size: 0.81rem;
                line-height: 1.5;
            }

            .auth-shell .auth-note--danger {
                border-color: rgba(239, 68, 68, 0.16);
                background: linear-gradient(135deg, rgba(254, 242, 242, 0.97), rgba(255, 251, 251, 0.94));
                color: #b42318;
            }

            .auth-shell label {
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: #4b5563;
            }

            .auth-shell input {
                min-height: 3.15rem;
                border-radius: 18px;
                border-color: rgba(148, 163, 184, 0.35) !important;
                background: rgba(248, 250, 252, 0.96);
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
                transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
                color: var(--auth-ink);
            }

            .auth-shell input:focus {
                transform: translateY(-1px);
                border-color: rgba(249, 115, 22, 0.45) !important;
                box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12) !important;
            }

            .auth-shell input[type="checkbox"] {
                min-height: 1.2rem;
                width: 1.2rem;
                height: 1.2rem;
                border-radius: 0.45rem;
                background: #fff;
                border: 1px solid rgba(148, 163, 184, 0.55) !important;
                box-shadow: none;
                transform: none;
                accent-color: #f97316;
            }

            .auth-shell input[type="checkbox"]:focus {
                transform: none;
                box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12) !important;
            }

            .auth-shell .remember-wrap {
                display: inline-flex;
                align-items: center;
                gap: 0.7rem;
            }

            .auth-shell .remember-wrap span {
                margin-left: 0 !important;
                font-size: 0.84rem;
                font-weight: 700;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: var(--auth-subtle-strong);
            }

            .auth-shell .auth-code-input {
                min-height: 4rem;
                border-radius: 20px;
                background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(248,250,252,0.98));
                box-shadow:
                    inset 0 1px 0 rgba(255,255,255,0.9),
                    0 14px 30px rgba(15, 23, 42, 0.08);
            }

            .auth-shell .auth-countdown {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                padding: 0.55rem 0.85rem;
                border-radius: 999px;
                background: rgba(255,255,255,0.75);
                border: 1px solid rgba(203, 213, 225, 0.55);
                font-size: 0.83rem;
                color: var(--auth-subtle-strong);
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            }

            .auth-shell .auth-countdown strong {
                color: #1d4ed8;
                animation: authPulse 1.6s ease-in-out infinite;
            }

            .auth-shell .primary-cta,
            .auth-shell button[type="submit"].primary-cta {
                position: relative;
                overflow: hidden;
                min-height: 3.1rem;
                border-radius: 20px;
                border: none;
                background: linear-gradient(135deg, var(--auth-accent-start), var(--auth-accent-mid) 52%, var(--auth-accent-end));
                color: #fff;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                box-shadow: 0 16px 34px rgba(249, 115, 22, 0.28);
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            }

            .auth-shell .primary-cta:hover {
                transform: translateY(-2px);
                box-shadow: 0 22px 40px rgba(249, 115, 22, 0.34);
                filter: saturate(1.04);
            }

            .auth-shell .primary-cta::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(110deg, transparent 25%, rgba(255,255,255,0.26) 50%, transparent 75%);
                transform: translateX(-135%);
                transition: transform 0.55s ease;
            }

            .auth-shell .primary-cta:hover::after {
                transform: translateX(135%);
            }

            .auth-shell a {
                transition: color 0.18s ease, opacity 0.18s ease;
            }

            .auth-shell .auth-link {
                color: #1d4ed8;
                font-weight: 700;
            }

            .auth-shell .auth-link:hover {
                color: #1e40af;
            }

            .auth-shell .auth-secondary-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 3rem;
                border-radius: 18px;
                padding: 0.85rem 1rem;
                background: rgba(248,250,252,0.95);
                border: 1px solid rgba(203, 213, 225, 0.75);
                color: var(--auth-subtle-strong);
                font-weight: 800;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                transition: transform 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
            }

            .auth-shell .auth-secondary-button:hover {
                transform: translateY(-1px);
                border-color: rgba(249, 115, 22, 0.22);
                color: #0f172a;
                box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
            }

            .auth-shell .auth-panel {
                margin-top: 0.9rem;
                border-radius: 24px;
                border: 1px solid rgba(226, 232, 240, 0.8);
                background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(248,250,252,0.92));
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.85);
            }

            .auth-shell .auth-section-title {
                font-family: "Manrope", "Figtree", sans-serif;
                font-size: 1.1rem;
                font-weight: 800;
                color: var(--auth-ink);
            }

            .auth-shell .auth-microcopy {
                font-size: 0.84rem;
                line-height: 1.65;
                color: var(--auth-subtle);
            }

            .auth-shell .auth-rule-list {
                margin-top: 0.8rem;
                border-radius: 22px;
                padding: 0.85rem 0.9rem 0.15rem;
                background: linear-gradient(180deg, rgba(248,250,252,0.95), rgba(255,255,255,0.9));
                border: 1px solid rgba(226,232,240,0.8);
                box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            }

            .auth-shell > * {
                opacity: 0;
                animation: authContentSlide 0.7s cubic-bezier(.22,1,.36,1) forwards;
            }

            .auth-shell > *:nth-child(1) { animation-delay: 0.08s; }
            .auth-shell > *:nth-child(2) { animation-delay: 0.16s; }
            .auth-shell > *:nth-child(3) { animation-delay: 0.24s; }
            .auth-shell > *:nth-child(4) { animation-delay: 0.32s; }

            .auth-shell form {
                opacity: 0;
                animation: authFormSlide 0.78s cubic-bezier(.22,1,.36,1) forwards;
                animation-delay: 0.18s;
            }

            @supports (view-transition-name: auth-shell) {
                @view-transition {
                    navigation: auto;
                }

                ::view-transition-old(root) {
                    animation: authViewOut 220ms ease both;
                }

                ::view-transition-new(root) {
                    animation: authViewIn 320ms cubic-bezier(.22,1,.36,1) both;
                }
            }

            @keyframes authViewOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(-42px); }
            }

            @keyframes authViewIn {
                from { opacity: 0; transform: translateX(42px); }
                to { opacity: 1; transform: translateX(0); }
            }

            .auth-stage.auth-mode-login .auth-form-panel {
                order: 1;
                transform: translateX(-10px);
            }

            .auth-stage.auth-mode-login .auth-showcase {
                order: 2;
                transform: translateX(10px);
            }

            .auth-stage.auth-mode-register .auth-showcase,
            .auth-stage.auth-mode-recovery .auth-showcase,
            .auth-stage.auth-mode-verification .auth-showcase,
            .auth-stage.auth-mode-default .auth-showcase {
                order: 1;
                transform: translateX(-10px);
            }

            .auth-stage.auth-mode-register .auth-showcase-copy {
                max-width: 455px;
            }

            .auth-stage.auth-mode-register .auth-switch-panel {
                width: min(100%, 430px);
            }

            .auth-stage.auth-mode-register .auth-showcase-metric {
                min-width: 0;
                max-width: 245px;
                padding: 0.88rem 0.95rem;
            }

            .auth-stage.auth-mode-register .auth-showcase-metric strong {
                font-size: 1.22rem;
            }

            .auth-stage.auth-mode-register .auth-showcase-metric span {
                font-size: 0.74rem;
                line-height: 1.42;
            }

            .auth-stage.auth-mode-register .auth-showcase-ring {
                width: 118px;
                height: 118px;
                flex-shrink: 0;
            }

            .auth-stage.auth-mode-register .auth-form-panel,
            .auth-stage.auth-mode-recovery .auth-form-panel,
            .auth-stage.auth-mode-verification .auth-form-panel,
            .auth-stage.auth-mode-default .auth-form-panel {
                order: 2;
                transform: translateX(10px);
            }

            .auth-stage.auth-mode-login .auth-card {
                border-top-left-radius: 28px;
                border-bottom-left-radius: 28px;
            }

            .auth-stage.auth-mode-register .auth-card,
            .auth-stage.auth-mode-recovery .auth-card,
            .auth-stage.auth-mode-verification .auth-card,
            .auth-stage.auth-mode-default .auth-card {
                border-top-right-radius: 28px;
                border-bottom-right-radius: 28px;
            }

            .auth-stage.auth-mode-login .auth-showcase {
                border-top-right-radius: 32px;
                border-bottom-right-radius: 32px;
            }

            .auth-stage.auth-mode-register .auth-showcase,
            .auth-stage.auth-mode-recovery .auth-showcase,
            .auth-stage.auth-mode-verification .auth-showcase,
            .auth-stage.auth-mode-default .auth-showcase {
                border-top-left-radius: 32px;
                border-bottom-left-radius: 32px;
            }

            .auth-stage.auth-mode-recovery .auth-showcase {
                background:
                    radial-gradient(circle at 18% 18%, rgba(251, 191, 36, 0.18), transparent 28%),
                    radial-gradient(circle at 82% 22%, rgba(56, 189, 248, 0.18), transparent 22%),
                    linear-gradient(150deg, rgba(9, 19, 34, 0.98), rgba(17, 24, 39, 0.96) 48%, rgba(30, 41, 59, 0.9));
            }

            .auth-stage.auth-mode-verification .auth-showcase {
                background:
                    radial-gradient(circle at 18% 18%, rgba(249, 115, 22, 0.18), transparent 28%),
                    radial-gradient(circle at 82% 22%, rgba(16, 185, 129, 0.18), transparent 22%),
                    linear-gradient(150deg, rgba(9, 19, 34, 0.98), rgba(15, 23, 42, 0.96) 48%, rgba(17, 24, 39, 0.9));
            }

            .auth-page .mb-7 { margin-bottom: 1.2rem !important; }
            .auth-page .mt-8 { margin-top: 1.3rem !important; }
            .auth-page .mt-7 { margin-top: 1rem !important; }
            .auth-page .mt-6 { margin-top: 0.9rem !important; }
            .auth-page .mt-5 { margin-top: 0.8rem !important; }
            .auth-page .mt-4 { margin-top: 0.72rem !important; }

            @media (max-width: 640px) {
                .auth-card {
                    padding: 1.55rem 1.1rem 1.25rem;
                    border-radius: 28px;
                }

                .auth-brand {
                    width: 76px;
                    height: 76px;
                }

                .auth-shell .auth-title {
                    font-size: 1.8rem;
                }

                .auth-shell .auth-subtitle {
                    font-size: 0.9rem;
                }

                .auth-switch-panel {
                    padding: 0.9rem 0.95rem;
                }
            }

            @media (max-width: 1023px) {
                .auth-stage {
                    padding: 0;
                    background: transparent;
                    border: none;
                    box-shadow: none;
                    overflow: visible;
                }

                .auth-stage::before,
                .auth-stage::after {
                    display: none;
                }

                .auth-stage.auth-mode-login .auth-form-panel,
                .auth-stage.auth-mode-register .auth-form-panel,
                .auth-stage.auth-mode-recovery .auth-form-panel,
                .auth-stage.auth-mode-verification .auth-form-panel,
                .auth-stage.auth-mode-default .auth-form-panel {
                    transform: none;
                }

                .auth-inline-switch {
                    display: block;
                }
            }

            @media (min-width: 1024px) {
                .auth-showcase {
                    display: block;
                    height: min(760px, calc(100vh - 2.5rem));
                }

                .auth-form-panel {
                    flex: 0 0 490px;
                }

                .auth-card {
                    max-width: 490px;
                    max-height: min(760px, calc(100vh - 2.5rem));
                    overflow-y: auto;
                }

                .auth-mobile-home {
                    display: none;
                }

                .auth-inline-switch {
                    display: none;
                }
            }

            @media (max-height: 860px) {
                .auth-brand {
                    width: 74px;
                    height: 74px;
                }

                .auth-card {
                    padding: 1.4rem 1rem 1rem;
                    max-width: 470px;
                }

                .auth-showcase {
                    height: min(700px, calc(100vh - 2rem));
                    padding: 1.55rem 1.45rem;
                }

                .auth-showcase-title {
                    font-size: clamp(1.85rem, 3vw, 2.8rem);
                }

                .auth-showcase-text {
                    font-size: 0.88rem;
                    line-height: 1.55;
                }

                .auth-showcase-stack {
                    gap: 0.72rem;
                    margin-top: 1rem;
                }

                .auth-showcase-chip {
                    padding: 0.7rem 0.82rem;
                    min-width: 210px;
                }

                .auth-showcase-chip-text {
                    font-size: 0.82rem;
                }

                .auth-switch-panel {
                    width: min(100%, 330px);
                    margin-top: 1rem;
                    padding: 0.82rem 0.9rem;
                }

                .auth-switch-title {
                    font-size: 0.92rem;
                }

                .auth-switch-text {
                    font-size: 0.76rem;
                }

                .auth-switch-list li {
                    font-size: 0.72rem;
                }

                .auth-shell .auth-title {
                    font-size: clamp(1.65rem, 3.2vw, 2.1rem);
                }

                .auth-shell .auth-subtitle {
                    font-size: 0.88rem;
                    line-height: 1.5;
                }

                .auth-shell .auth-note {
                    font-size: 0.77rem;
                    padding: 0.72rem 0.82rem;
                }

                .auth-page .mb-7 { margin-bottom: 0.95rem !important; }
                .auth-page .mt-8 { margin-top: 1rem !important; }
                .auth-page .mt-7 { margin-top: 0.82rem !important; }
                .auth-page .mt-6 { margin-top: 0.75rem !important; }
                .auth-page .mt-5 { margin-top: 0.65rem !important; }
                .auth-page .mt-4 { margin-top: 0.6rem !important; }
            }

            @media (max-height: 760px) {
                .auth-card {
                    padding: 1.2rem 0.95rem 0.9rem;
                }

                .auth-brand {
                    width: 68px;
                    height: 68px;
                }

                .auth-showcase {
                    padding: 1.3rem 1.25rem;
                }

                .auth-showcase-copy {
                    margin-top: 1.45rem;
                }

                .auth-showcase-footer {
                    margin-top: 1rem;
                }

                .auth-showcase-ring {
                    width: 124px;
                    height: 124px;
                }

                .auth-showcase-metric {
                    padding: 0.85rem 0.95rem;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased auth-page">
        <div class="auth-orb auth-orb--amber"></div>
        <div class="auth-orb auth-orb--blue"></div>
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-5">
            <div class="auth-stage auth-mode-{{ $authMode }}">
                <aside class="auth-showcase">
                    <div class="auth-showcase-inner">
                        <div>
                            <div class="auth-showcase-top">
                                <div class="auth-showcase-branding">
                                    <div class="auth-showcase-badge">
                                        <x-application-logo class="w-10 h-10 fill-current text-white" />
                                    </div>
                                    <div>
                                        <p class="auth-showcase-kicker">{{ $showcaseData['kicker'] }}</p>
                                        <p class="auth-showcase-brand">{{ config('app.name', 'Printify & Co.') }}</p>
                                    </div>
                                </div>
                                <a href="/" class="auth-home-link">{{ __('Home') }}</a>
                            </div>

                            <div class="auth-showcase-copy">
                                <h1 class="auth-showcase-title">
                                    {{ $showcaseData['title_intro'] }}
                                    <strong>{{ $showcaseData['title_focus'] }}</strong>
                                </h1>
                                <p class="auth-showcase-text">
                                    {{ $showcaseData['text'] }}
                                </p>

                                <div class="auth-showcase-stack">
                                    @foreach ($showcaseData['chips'] as $chip)
                                        <div class="auth-showcase-chip">
                                            <span class="auth-showcase-chip-dot"></span>
                                            <span class="auth-showcase-chip-text">{{ $chip }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($switchPanel)
                                    <div class="auth-switch-panel">
                                        <p class="auth-switch-eyebrow">{{ $switchPanel['eyebrow'] }}</p>
                                        <p class="auth-switch-title">{{ $switchPanel['title'] }}</p>
                                        <p class="auth-switch-text">{{ $switchPanel['text'] }}</p>
                                        @if (!empty($switchPanel['items']))
                                            <ul class="auth-switch-list">
                                                @foreach ($switchPanel['items'] as $switchItem)
                                                    <li>{{ $switchItem }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if (!empty($switchPanel['cta']) && !empty($switchPanel['href']))
                                            <a href="{{ $switchPanel['href'] }}" class="auth-switch-link">
                                                {{ $switchPanel['cta'] }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="auth-showcase-footer">
                            <div class="auth-showcase-metric">
                                <strong>{{ $showcaseData['metric_value'] }}</strong>
                                <span>{{ $showcaseData['metric_text'] }}</span>
                            </div>
                            <div class="auth-showcase-ring" aria-hidden="true"></div>
                        </div>
                    </div>
                </aside>

                <div class="auth-form-panel">
                    <div class="mb-5 lg:hidden">
                        <a href="/">
                            <div class="auth-brand">
                                <x-application-logo class="w-14 h-14 fill-current text-white" />
                            </div>
                        </a>
                        <a href="/" class="auth-mobile-home">{{ __('Back to Home') }}</a>
                    </div>

                    <div class="auth-card auth-shell">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
