<style>
    :root {
        --auth-ink: #171717;
        --auth-paper: #fffaf3;
        --auth-orange: #ff7a18;
        --auth-gold: #ffc46b;
        --auth-cyan: #64d8ff;
        --auth-magenta: #f45ca8;
    }

    .min-h-screen {
        position: relative !important;
        overflow: hidden !important;
        background:
            radial-gradient(circle at 15% 18%, rgba(255, 122, 24, 0.20), transparent 28%),
            radial-gradient(circle at 82% 20%, rgba(100, 216, 255, 0.18), transparent 26%),
            radial-gradient(circle at 82% 82%, rgba(244, 92, 168, 0.16), transparent 28%),
            linear-gradient(135deg, #fffdf8 0%, #fff7ec 42%, #f8fbff 100%) !important;
        isolation: isolate;
    }

    .min-h-screen::before,
    .min-h-screen::after {
        content: "";
        position: absolute;
        inset: -25%;
        pointer-events: none;
        z-index: 0;
    }

    .min-h-screen::before {
        opacity: 0.75;
        background:
            linear-gradient(115deg, transparent 0 42%, rgba(255, 122, 24, 0.15) 43% 44%, transparent 45% 100%),
            linear-gradient(65deg, transparent 0 52%, rgba(23, 23, 23, 0.08) 53% 54%, transparent 55% 100%),
            repeating-linear-gradient(90deg, rgba(255, 122, 24, 0.06) 0 1px, transparent 1px 78px),
            repeating-linear-gradient(0deg, rgba(23, 23, 23, 0.045) 0 1px, transparent 1px 78px);
        transform: rotate(-8deg) translate3d(0, 0, 0);
        animation: authPressGrid 28s linear infinite;
    }

    .min-h-screen::after {
        opacity: 0.95;
        background:
            radial-gradient(circle at 18% 58%, rgba(255, 196, 107, 0.34) 0 8px, transparent 9px),
            radial-gradient(circle at 68% 24%, rgba(255, 122, 24, 0.28) 0 10px, transparent 11px),
            radial-gradient(circle at 78% 74%, rgba(100, 216, 255, 0.28) 0 9px, transparent 10px),
            radial-gradient(circle at 32% 78%, rgba(244, 92, 168, 0.24) 0 7px, transparent 8px);
        filter: blur(0.2px);
        animation: authInkFloat 18s ease-in-out infinite alternate;
    }

    .min-h-screen > * {
        position: relative;
        z-index: 2;
    }

    .auth-container,
    .sm\:max-w-md,
    form {
        position: relative;
    }

    .auth-container {
        z-index: 3;
        box-shadow:
            0 28px 80px rgba(15, 23, 42, 0.16),
            0 10px 24px rgba(255, 122, 24, 0.10) !important;
    }

    .auth-container::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(255, 122, 24, 0.42), rgba(255,255,255,0.16), rgba(100, 216, 255, 0.28));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        z-index: 200;
        animation: authBorderGlow 5.5s ease-in-out infinite alternate;
    }

    @keyframes authPressGrid {
        0% {
            background-position: 0 0, 0 0, 0 0, 0 0;
            transform: rotate(-8deg) translate3d(-18px, -12px, 0);
        }
        100% {
            background-position: 180px 80px, -160px 110px, 156px 0, 0 156px;
            transform: rotate(-8deg) translate3d(18px, 12px, 0);
        }
    }

    @keyframes authInkFloat {
        0% {
            transform: translate3d(-14px, 10px, 0) scale(1);
        }
        50% {
            transform: translate3d(10px, -16px, 0) scale(1.04);
        }
        100% {
            transform: translate3d(18px, 14px, 0) scale(1.02);
        }
    }

    @keyframes authBorderGlow {
        from {
            opacity: 0.42;
        }
        to {
            opacity: 0.86;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .min-h-screen::before,
        .min-h-screen::after,
        .auth-container::before {
            animation: none !important;
        }
    }
</style>
