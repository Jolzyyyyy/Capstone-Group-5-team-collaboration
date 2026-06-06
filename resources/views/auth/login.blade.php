<x-guest-layout>
    <style>
        :root {
            --primary-orange: #FF8C00;
            --orange-deep: #ff4b2b;
            --dark-black: #1a1a1a;
            --hover-black: #2a2a2a;
            --link-blue: #2563eb;
            --social-hover: #f3f4f6;
            --slide-ease: cubic-bezier(0.83, 0, 0.17, 1);
            --slide-time: 0.95s;
            --content-time: 0.72s;
        }

        .min-h-screen {
            background-color: #f8f9fa !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .min-h-screen::before,
        .min-h-screen::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            opacity: 0.07;
        }

        .min-h-screen::before {
            background-image:
                linear-gradient(45deg, var(--primary-orange) 25%, transparent 25%),
                linear-gradient(-45deg, var(--primary-orange) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, var(--primary-orange) 75%),
                linear-gradient(-45deg, transparent 75%, var(--primary-orange) 75%);
            background-size: 80px 80px;
            background-position: 0 0, 0 40px, 40px -40px, -40px 0;
            animation: moveBackground1 25s linear infinite;
        }

        .min-h-screen::after {
            background-image: linear-gradient(115deg, transparent 0 35%, rgba(37, 99, 235, 0.5) 48%, transparent 62%);
            background-size: 220% 220%;
            animation: moveBackground2 9s ease-in-out infinite;
        }

        @keyframes moveBackground1 {
            0% { background-position: 0 0, 0 40px, 40px -40px, -40px 0; }
            100% { background-position: 80px 0, 80px 40px, 120px -40px, 40px 0; }
        }

        @keyframes moveBackground2 {
            0%, 35% { background-position: -140% 50%; opacity: 0; }
            55% { opacity: 0.12; }
            100% { background-position: 140% 50%; opacity: 0; }
        }

        .min-h-screen > div:first-child {
            display: none !important;
        }

        .min-h-screen > div:last-child {
            width: 100% !important;
            max-width: none !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            background-color: transparent !important;
            box-shadow: none !important;
            z-index: 10;
        }

        .sm\:max-w-md {
            max-width: none !important;
            width: auto !important;
            box-shadow: none !important;
            background-color: transparent !important;
            padding: 0 !important;
        }

        .auth-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            width: 620px;
            max-width: 95vw;
            min-height: 450px;
            display: flex;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            background-color: #fff;
            transition:
                transform var(--slide-time) var(--slide-ease),
                opacity 0.62s ease,
                filter 0.62s ease;
            will-change: transform, opacity, filter;
            backface-visibility: hidden;
            transform-style: preserve-3d;
        }

        .sign-in-container {
            left: 0;
            z-index: 2;
            opacity: 1;
        }

        .sign-up-container {
            left: 0;
            opacity: 0;
            z-index: 1;
        }

        .auth-container.right-panel-active .sign-in-container {
            transform: translateX(100%);
            opacity: 0;
            z-index: 1;
            filter: blur(1.2px);
        }

        .auth-container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            filter: blur(0);
            animation: show var(--slide-time) var(--slide-ease);
        }

        @keyframes show {
            0%, 45% { opacity: 0; z-index: 1; }
            46%, 100% { opacity: 1; z-index: 5; }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition:
                transform var(--slide-time) var(--slide-ease),
                border-radius var(--slide-time) var(--slide-ease);
            z-index: 100;
            border-radius: 100px 0 0 100px;
            will-change: transform, border-radius;
            backface-visibility: hidden;
            transform-style: preserve-3d;
        }

        .auth-container.right-panel-active .overlay-container {
            transform: translateX(-100%);
            border-radius: 0 100px 100px 0;
        }

        .overlay {
            background: linear-gradient(to right, var(--orange-deep), var(--primary-orange));
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform var(--slide-time) var(--slide-ease);
            will-change: transform;
            backface-visibility: hidden;
            transform-style: preserve-3d;
        }

        .auth-container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            z-index: 110;
            overflow: hidden;
            background-repeat: no-repeat;
            background-size: 190px 190px, 74px 34px, 100% 118px;
            background-image:
                radial-gradient(circle, rgba(255, 211, 112, 0.34) 0 42%, rgba(255, 211, 112, 0.14) 43% 62%, transparent 63%),
                radial-gradient(circle, rgba(255,255,255,0.88) 1.7px, transparent 1.9px),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 120' preserveAspectRatio='none'%3E%3Cg fill='none' stroke='rgba(255,255,255,0.50)' stroke-width='1'%3E%3Cpath d='M0 85 C45 62 88 62 134 85 S222 108 268 85 S354 62 400 85'/%3E%3Cpath d='M0 91 C45 68 88 68 134 91 S222 114 268 91 S354 68 400 91'/%3E%3Cpath d='M0 97 C45 74 88 74 134 97 S222 120 268 97 S354 74 400 97'/%3E%3Cpath d='M0 103 C45 80 88 80 134 103 S222 126 268 103 S354 80 400 103'/%3E%3Cpath d='M0 109 C45 86 88 86 134 109 S222 132 268 109 S354 86 400 109'/%3E%3Cpath d='M0 115 C45 92 88 92 134 115 S222 138 268 115 S354 92 400 115'/%3E%3C/g%3E%3C/svg%3E");
        }

        .overlay-left {
            transform: translateX(-20%);
            transition: transform var(--slide-time) var(--slide-ease);
            background-position: -54px -50px, 28px 20px, center bottom -6px;
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
            transition: transform var(--slide-time) var(--slide-ease);
            background-position: calc(100% + 48px) -48px, calc(100% - 25px) 20px, center bottom -6px;
        }

        .auth-container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .auth-container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .overlay-panel h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 3;
        }

        .overlay-panel p {
            font-size: 13px;
            line-height: 1.4;
            opacity: 0.9;
            position: relative;
            z-index: 3;
        }

        .form-content {
            padding: 20px 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            will-change: transform, opacity, filter;
            backface-visibility: hidden;
        }

        .overlay-panel > * {
            will-change: transform, opacity, filter;
            backface-visibility: hidden;
        }

        .auth-container.is-sliding .form-content,
        .auth-container.is-sliding .ghost-btn {
            pointer-events: none;
        }

        .auth-container.slide-to-register .sign-in-container .form-content {
            animation: authSlideOutLeft var(--content-time) var(--slide-ease) both;
        }

        .auth-container.slide-to-register .sign-up-container .form-content {
            animation: authSlideInRight var(--content-time) var(--slide-ease) 0.12s both;
        }

        .auth-container.slide-to-login .sign-up-container .form-content {
            animation: authSlideOutRight var(--content-time) var(--slide-ease) both;
        }

        .auth-container.slide-to-login .sign-in-container .form-content {
            animation: authSlideInLeft var(--content-time) var(--slide-ease) 0.12s both;
        }

        .auth-container.slide-to-register .overlay-right > * {
            animation: overlaySlideOutLeft 0.62s var(--slide-ease) both;
        }

        .auth-container.slide-to-register .overlay-left > * {
            animation: overlaySlideInRight 0.72s var(--slide-ease) 0.12s both;
        }

        .auth-container.slide-to-login .overlay-left > * {
            animation: overlaySlideOutRight 0.62s var(--slide-ease) both;
        }

        .auth-container.slide-to-login .overlay-right > * {
            animation: overlaySlideInLeft 0.72s var(--slide-ease) 0.12s both;
        }

        @keyframes authSlideInRight {
            0% { opacity: 0; transform: translateX(70px) scale(0.97); filter: blur(3px); }
            62% { opacity: 1; transform: translateX(-8px) scale(1.01); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }

        @keyframes authSlideInLeft {
            0% { opacity: 0; transform: translateX(-70px) scale(0.97); filter: blur(3px); }
            62% { opacity: 1; transform: translateX(8px) scale(1.01); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }

        @keyframes authSlideOutLeft {
            0% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
            45% { opacity: 0.55; transform: translateX(-24px) scale(0.99); filter: blur(1px); }
            100% { opacity: 0; transform: translateX(-70px) scale(0.97); filter: blur(3px); }
        }

        @keyframes authSlideOutRight {
            0% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
            45% { opacity: 0.55; transform: translateX(24px) scale(0.99); filter: blur(1px); }
            100% { opacity: 0; transform: translateX(70px) scale(0.97); filter: blur(3px); }
        }

        @keyframes overlaySlideInRight {
            0% { opacity: 0; transform: translateX(48px) scale(0.98); filter: blur(2px); }
            68% { opacity: 1; transform: translateX(-4px) scale(1); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }

        @keyframes overlaySlideInLeft {
            0% { opacity: 0; transform: translateX(-48px) scale(0.98); filter: blur(2px); }
            68% { opacity: 1; transform: translateX(4px) scale(1); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }

        @keyframes overlaySlideOutLeft {
            0% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
            100% { opacity: 0; transform: translateX(-48px) scale(0.98); filter: blur(2px); }
        }

        @keyframes overlaySlideOutRight {
            0% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
            100% { opacity: 0; transform: translateX(48px) scale(0.98); filter: blur(2px); }
        }

        .input-group {
            width: 100%;
            margin-bottom: 8px;
        }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-icon {
            position: absolute;
            left: 12px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .custom-input {
            background-color: #f0f2f5;
            border: none !important;
            padding: 10px 15px 10px 40px;
            border-radius: 8px;
            width: 100%;
            font-size: 13px;
            color: #606770;
            transition: all 0.3s;
        }

        .custom-input-pass {
            padding-right: 45px !important;
        }

        .eye-icon-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #606770;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 12px;
            color: #64748b;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .forgot-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--link-blue);
        }

        .auth-btn {
            background-color: var(--dark-black);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 0;
            border-radius: 25px;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            font-size: 11px;
            border: none;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .auth-btn:hover {
            background-color: var(--hover-black);
        }

        .ghost-btn {
            background-color: transparent;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
            padding: 10px 30px;
            border-radius: 25px;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 10px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
        }

        .ghost-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .or-divider {
            position: relative;
            width: 100%;
            margin: 12px 0;
            text-align: center;
        }

        .or-divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: #d1d5db;
            z-index: 1;
        }

        .or-divider span {
            position: relative;
            background: #fff;
            padding: 0 10px;
            font-size: 10px;
            color: #6b7280;
            font-weight: 600;
            z-index: 2;
        }

        .social-row {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-row a {
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .social-row a:hover {
            background-color: var(--social-hover);
            border-color: #ccc;
        }

        .auth-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--dark-black);
        }

        .error-text {
            color: #dc2626;
            font-size: 10px;
            width: 100%;
            text-align: left;
            margin-top: 2px;
        }

        /* Final orange split-card auth UI */
        .min-h-screen{
            background:
                radial-gradient(circle at 4% 92%, rgba(255,140,0,.42) 0 68px, transparent 69px),
                radial-gradient(circle at 96% 8%, rgba(255,140,0,.40) 0 62px, transparent 63px),
                radial-gradient(circle at 8% 12%, rgba(255,140,0,.10) 0 96px, transparent 97px),
                linear-gradient(145deg,#fff 0%,#fff8f0 42%,#ffffff 100%)!important;
        }
        .min-h-screen::before{
            opacity:1!important;
            background-image:
                radial-gradient(circle, rgba(255,122,0,.75) 1.5px, transparent 1.8px),
                radial-gradient(circle, rgba(255,122,0,.55) 1.3px, transparent 1.7px),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='700' height='260' viewBox='0 0 700 260'%3E%3Cg fill='none' stroke='rgba(255,122,0,.22)' stroke-width='1'%3E%3Cpath d='M-20 180 C120 80 210 270 360 150 S560 40 730 140'/%3E%3Cpath d='M-20 192 C120 92 210 282 360 162 S560 52 730 152'/%3E%3Cpath d='M-20 204 C120 104 210 294 360 174 S560 64 730 164'/%3E%3Cpath d='M-20 216 C120 116 210 306 360 186 S560 76 730 176'/%3E%3Cpath d='M-20 228 C120 128 210 318 360 198 S560 88 730 188'/%3E%3C/g%3E%3C/svg%3E");
            background-size:90px 90px,88px 88px,700px 260px;
            background-position:left 28px bottom 72px,right 58px center,left bottom;
            background-repeat:no-repeat;
            animation:none!important;
            z-index:1;
        }
        .min-h-screen::after{
            opacity:1!important;
            background:
                linear-gradient(153deg, transparent 0 27%, rgba(255,255,255,.82) 27.2% 56%, transparent 56.4%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='700' height='260' viewBox='0 0 700 260'%3E%3Cg fill='none' stroke='rgba(255,122,0,.18)' stroke-width='1'%3E%3Cpath d='M-10 40 C130 140 230 -50 380 70 S570 190 720 50'/%3E%3Cpath d='M-10 52 C130 152 230 -38 380 82 S570 202 720 62'/%3E%3Cpath d='M-10 64 C130 164 230 -26 380 94 S570 214 720 74'/%3E%3Cpath d='M-10 76 C130 176 230 -14 380 106 S570 226 720 86'/%3E%3C/g%3E%3C/svg%3E");
            background-size:100% 100%,700px 260px;
            background-position:center,right bottom;
            background-repeat:no-repeat;
            animation:none!important;
            z-index:1;
        }
        .auth-container{
            border-radius:18px!important;
            box-shadow:0 22px 55px rgba(17,24,39,.18)!important;
            overflow:hidden!important;
        }
        .form-container{background:#fff!important}
        .form-content{padding:25px 42px!important;align-items:stretch!important}
        .auth-title{
            margin:0!important;
            font-size:24px!important;
            line-height:1.1!important;
            color:#171717!important;
            text-align:left!important;
            font-weight:800!important;
        }
        .auth-subtitle{
            margin:7px 0 22px!important;
            color:#8b94a7!important;
            font-size:14px!important;
            line-height:1.35!important;
            font-weight:500!important;
        }
        .input-group{margin-bottom:14px!important}
        .input-label{
            margin-bottom:8px!important;
            color:#171717!important;
            font-size:12px!important;
            font-weight:700!important;
        }
        .custom-input{
            height:43px!important;
            border:1px solid #e1e5ec!important;
            border-radius:9px!important;
            background:#fff!important;
            color:#111827!important;
            font-size:13px!important;
            box-shadow:0 4px 14px rgba(15,23,42,.035)!important;
        }
        .custom-input::placeholder{color:#8b94a7!important}
        .field-icon{left:14px!important;color:#9aa3b5!important}
        .eye-icon-btn{right:14px!important;color:#7d8799!important}
        .auth-options{margin:2px 0 14px!important}
        .remember-me input{width:15px!important;height:15px!important;accent-color:#ff7a00!important}
        .forgot-link{color:#ff4f16!important;font-weight:600!important}
        .auth-btn{
            height:43px!important;
            margin:0!important;
            border-radius:999px!important;
            background:#171717!important;
            color:#fff!important;
            font-size:12px!important;
            font-weight:800!important;
            letter-spacing:0!important;
            box-shadow:0 8px 18px rgba(17,24,39,.18)!important;
        }
        .auth-btn:hover{background:#000!important;transform:none!important}
        .or-divider{margin:16px 0!important}
        .or-divider::before{background:#e5e8ef!important}
        .or-divider span{font-size:12px!important;color:#8b94a7!important}
        .social-row{gap:12px!important}
        .social-row a{
            width:calc(50% - 6px)!important;
            height:39px!important;
            border-radius:999px!important;
            background:#fff!important;
            border:1px solid #e1e5ec!important;
            color:#374151!important;
            font-size:13px!important;
            font-weight:600!important;
            gap:8px!important;
            box-shadow:0 3px 10px rgba(15,23,42,.035)!important;
        }
        .social-row a:hover{background:#fff7f1!important;border-color:#ffd0b5!important}
        .auth-switch-text{
            margin-top:18px;
            text-align:center;
            color:#8b94a7;
            font-size:12px;
            font-weight:500;
        }
        .auth-switch-text button{
            border:0;
            background:transparent;
            padding:0;
            color:#ff4f16;
            font-weight:700;
            cursor:pointer;
        }
        .overlay-container{
            border-radius:74px 0 0 74px!important;
            padding:0!important;
        }
        .auth-container.right-panel-active .overlay-container{border-radius:0 74px 74px 0!important}
        .overlay{
            background:linear-gradient(145deg,#ff9416 0%,#ff7814 48%,#ff371f 100%)!important;
        }
        .overlay-panel{
            padding:0 44px!important;
            background-size:170px 170px,74px 46px,100% 120px!important;
        }
        .overlay-panel::before{
            content:"";
            width:72px;
            height:72px;
            margin-bottom:28px;
            border-radius:50%;
            background:rgba(255,255,255,.96);
            box-shadow:0 0 0 12px rgba(255,255,255,.18),0 12px 28px rgba(98,45,0,.12);
            display:block;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='42' viewBox='0 0 24 24' fill='none' stroke='%23ff7a00' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2'/%3E%3Ccircle cx='9.5' cy='7' r='4'/%3E%3Cpath d='M19 8v6'/%3E%3Cpath d='M22 11h-6'/%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:center;
            background-size:42px 42px;
            position:relative;
            z-index:3;
        }
        .overlay-panel h1{
            font-size:26px!important;
            line-height:1.15!important;
            margin:0 0 13px!important;
            font-weight:800!important;
        }
        .overlay-panel p{
            max-width:260px;
            margin:0 auto!important;
            font-size:14px!important;
            line-height:1.55!important;
            opacity:1!important;
        }
        .ghost-btn{
            min-width:140px!important;
            height:41px!important;
            margin-top:28px!important;
            border-radius:999px!important;
            border:2px solid #fff!important;
            background:transparent!important;
            color:#fff!important;
            font-size:12px!important;
            font-weight:800!important;
            box-shadow:none!important;
        }
        .overlay-right .ghost-btn{
            background:#fff!important;
            color:#ff4f16!important;
            border-color:#fff!important;
        }
        .ghost-btn:hover{background:#171717!important;border-color:#171717!important;color:#fff!important}
        @media(max-width:720px){
            .auth-container{min-height:560px!important}
            .form-content{padding:24px!important}
            .overlay-panel{padding:0 22px!important}
        }

        /* Customer-only final sizing and closer reference background */
        .min-h-screen{
            background:
                radial-gradient(circle at 96% 9%, rgba(255,139,12,.92) 0 45px, rgba(255,139,12,.28) 46px 76px, transparent 77px),
                radial-gradient(circle at 3% 93%, rgba(255,139,12,.86) 0 58px, rgba(255,139,12,.22) 59px 94px, transparent 95px),
                linear-gradient(153deg, rgba(255,247,237,.92) 0 26%, rgba(255,255,255,.92) 26.2% 66%, rgba(255,247,237,.86) 66.2% 100%)!important;
            min-height:100vh!important;
            isolation:isolate;
        }
        .min-h-screen::before{
            opacity:1!important;
            background-image:
                radial-gradient(circle, rgba(255,122,0,.72) 1.4px, transparent 1.8px),
                radial-gradient(circle, rgba(255,122,0,.62) 1.4px, transparent 1.8px),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='760' height='320' viewBox='0 0 760 320'%3E%3Cg fill='none' stroke='rgba(255,122,0,.18)' stroke-width='1'%3E%3Cpath d='M-20 190 C80 80 190 300 330 180 S560 60 790 160'/%3E%3Cpath d='M-20 202 C80 92 190 312 330 192 S560 72 790 172'/%3E%3Cpath d='M-20 214 C80 104 190 324 330 204 S560 84 790 184'/%3E%3Cpath d='M-20 226 C80 116 190 336 330 216 S560 96 790 196'/%3E%3Cpath d='M-20 238 C80 128 190 348 330 228 S560 108 790 208'/%3E%3Cpath d='M-20 250 C80 140 190 360 330 240 S560 120 790 220'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='760' height='320' viewBox='0 0 760 320'%3E%3Cg fill='none' stroke='rgba(255,122,0,.16)' stroke-width='1'%3E%3Cpath d='M-20 70 C110 180 245 -40 395 95 S610 210 790 78'/%3E%3Cpath d='M-20 82 C110 192 245 -28 395 107 S610 222 790 90'/%3E%3Cpath d='M-20 94 C110 204 245 -16 395 119 S610 234 790 102'/%3E%3Cpath d='M-20 106 C110 216 245 -4 395 131 S610 246 790 114'/%3E%3C/g%3E%3C/svg%3E")!important;
            background-size:90px 90px,92px 92px,760px 320px,760px 320px!important;
            background-position:left 48px bottom 130px,right 78px center,left -90px top -8px,right -40px bottom -36px!important;
            background-repeat:no-repeat!important;
            animation:none!important;
            z-index:1!important;
        }
        .min-h-screen::after{
            opacity:1!important;
            background:
                radial-gradient(circle at 9% 19%, rgba(255,139,12,.11) 0 112px, transparent 113px),
                radial-gradient(circle at 98% 73%, rgba(255,122,0,.78) 1.5px, transparent 1.8px),
                radial-gradient(circle at 8% 62%, rgba(255,122,0,.65) 1.2px, transparent 1.6px),
                linear-gradient(153deg, transparent 0 25%, rgba(255,255,255,.78) 25.2% 55%, transparent 55.3%)!important;
            background-size:100% 100%,92px 92px,86px 86px,100% 100%!important;
            background-position:center,right 72px center,left 34px bottom 280px,center!important;
            background-repeat:no-repeat!important;
            animation:none!important;
            z-index:1!important;
        }
        .auth-container{
            width:760px!important;
            min-height:505px!important;
            border-radius:20px!important;
            box-shadow:0 26px 62px rgba(17,24,39,.18)!important;
        }
        .form-content{padding:34px 46px!important}
        .auth-title{font-size:27px!important}
        .auth-subtitle{font-size:15px!important;margin-bottom:24px!important}
        .custom-input{height:47px!important}
        .auth-btn{height:47px!important}
        .social-row a{height:43px!important}
        .overlay-container{border-radius:82px 0 0 82px!important}
        .auth-container.right-panel-active .overlay-container{border-radius:0 82px 82px 0!important}
        .overlay-panel::before{width:82px!important;height:82px!important;margin-bottom:31px!important}
        .overlay-panel h1{font-size:29px!important}
        .overlay-panel p{font-size:15px!important;max-width:290px!important}
        .ghost-btn{height:45px!important;min-width:158px!important}
        @media(max-width:860px){
            .auth-container{width:min(760px,calc(100vw - 28px))!important}
        }

        /* Final customer auth fit and reference-style background */
        .min-h-screen{
            background:
                linear-gradient(151deg, rgba(255,247,237,.90) 0 27%, rgba(255,255,255,.96) 27.2% 63%, rgba(255,247,237,.86) 63.2% 100%),
                linear-gradient(26deg, transparent 0 72%, rgba(255,122,0,.18) 72.2% 74%, transparent 74.2% 100%)!important;
        }
        .min-h-screen::before{
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='880' height='300' viewBox='0 0 880 300'%3E%3Cg fill='none' stroke='rgba(255,122,0,.23)' stroke-width='1'%3E%3Cpath d='M-40 145 C80 60 170 245 310 155 S535 55 720 135 S920 225 980 135'/%3E%3Cpath d='M-40 157 C80 72 170 257 310 167 S535 67 720 147 S920 237 980 147'/%3E%3Cpath d='M-40 169 C80 84 170 269 310 179 S535 79 720 159 S920 249 980 159'/%3E%3Cpath d='M-40 181 C80 96 170 281 310 191 S535 91 720 171 S920 261 980 171'/%3E%3Cpath d='M-40 193 C80 108 170 293 310 203 S535 103 720 183 S920 273 980 183'/%3E%3Cpath d='M-40 205 C80 120 170 305 310 215 S535 115 720 195 S920 285 980 195'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='760' height='260' viewBox='0 0 760 260'%3E%3Cg fill='none' stroke='rgba(255,122,0,.20)' stroke-width='1'%3E%3Cpath d='M-25 78 C95 170 215 -20 355 92 S575 188 790 62'/%3E%3Cpath d='M-25 90 C95 182 215 -8 355 104 S575 200 790 74'/%3E%3Cpath d='M-25 102 C95 194 215 4 355 116 S575 212 790 86'/%3E%3Cpath d='M-25 114 C95 206 215 16 355 128 S575 224 790 98'/%3E%3C/g%3E%3C/svg%3E"),
                radial-gradient(circle, rgba(255,122,0,.66) 1.4px, transparent 1.8px),
                radial-gradient(circle, rgba(255,122,0,.55) 1.4px, transparent 1.8px)!important;
            background-size:880px 300px,760px 260px,88px 88px,88px 88px!important;
            background-position:left -120px top 78px,right -60px bottom 34px,left 28px bottom 170px,right 70px center!important;
        }
        .min-h-screen::after{
            background:
                radial-gradient(circle at 8% 20%, rgba(255,122,0,.10) 0 86px, transparent 87px),
                radial-gradient(circle at 97% 12%, rgba(255,122,0,.13) 0 50px, transparent 51px),
                linear-gradient(151deg, transparent 0 29%, rgba(255,255,255,.72) 29.2% 58%, transparent 58.2%)!important;
        }
        .auth-container{
            width:820px!important;
            min-height:560px!important;
        }
        .form-content{
            padding:28px 42px!important;
            justify-content:center!important;
        }
        .sign-up-container .form-content{padding-top:24px!important;padding-bottom:22px!important}
        .auth-title{font-size:26px!important}
        .auth-subtitle{font-size:14px!important;margin:6px 0 16px!important}
        .input-group{margin-bottom:10px!important}
        .input-label{margin-bottom:6px!important;font-size:11.5px!important}
        .custom-input{height:43px!important;font-size:12.5px!important}
        .auth-options{margin:0 0 12px!important}
        .auth-btn{height:43px!important}
        .or-divider{margin:12px 0!important}
        .social-row a{height:38px!important;font-size:12.5px!important}
        .auth-switch-text{margin-top:12px!important;font-size:11.5px!important}
        .overlay-panel::before{width:78px!important;height:78px!important;margin-bottom:24px!important}
        .overlay-panel h1{font-size:27px!important;margin-bottom:10px!important}
        .overlay-panel p{font-size:14px!important;line-height:1.45!important;margin-bottom:28px!important}
        .ghost-btn{height:42px!important;min-width:150px!important}
        @media(max-width:860px){
            .auth-container{width:min(820px,calc(100vw - 28px))!important;min-height:560px!important}
        }

        /* Final pass: compact fit and non-circle dominant pattern */
        .min-h-screen{
            background:
                linear-gradient(152deg, rgba(255,247,237,.88) 0 28%, rgba(255,255,255,.98) 28.2% 64%, rgba(255,247,237,.82) 64.2% 100%)!important;
        }
        .min-h-screen::before{
            opacity:1!important;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='930' height='300' viewBox='0 0 930 300'%3E%3Cg fill='none' stroke='rgba(255,122,0,.22)' stroke-width='1'%3E%3Cpath d='M-60 122 C86 38 185 230 335 132 S570 36 760 118 S950 220 1010 128'/%3E%3Cpath d='M-60 134 C86 50 185 242 335 144 S570 48 760 130 S950 232 1010 140'/%3E%3Cpath d='M-60 146 C86 62 185 254 335 156 S570 60 760 142 S950 244 1010 152'/%3E%3Cpath d='M-60 158 C86 74 185 266 335 168 S570 72 760 154 S950 256 1010 164'/%3E%3Cpath d='M-60 170 C86 86 185 278 335 180 S570 84 760 166 S950 268 1010 176'/%3E%3Cpath d='M-60 182 C86 98 185 290 335 192 S570 96 760 178 S950 280 1010 188'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='900' height='300' viewBox='0 0 900 300'%3E%3Cg fill='none' stroke='rgba(255,122,0,.20)' stroke-width='1'%3E%3Cpath d='M-30 170 C95 255 220 70 370 175 S610 262 930 118'/%3E%3Cpath d='M-30 182 C95 267 220 82 370 187 S610 274 930 130'/%3E%3Cpath d='M-30 194 C95 279 220 94 370 199 S610 286 930 142'/%3E%3Cpath d='M-30 206 C95 291 220 106 370 211 S610 298 930 154'/%3E%3Cpath d='M-30 218 C95 303 220 118 370 223 S610 310 930 166'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Cg fill='rgba(255,122,0,.62)'%3E%3Ccircle cx='10' cy='10' r='1.4'/%3E%3Ccircle cx='26' cy='10' r='1.4'/%3E%3Ccircle cx='42' cy='10' r='1.4'/%3E%3Ccircle cx='58' cy='10' r='1.4'/%3E%3Ccircle cx='74' cy='10' r='1.4'/%3E%3Ccircle cx='10' cy='26' r='1.4'/%3E%3Ccircle cx='26' cy='26' r='1.4'/%3E%3Ccircle cx='42' cy='26' r='1.4'/%3E%3Ccircle cx='58' cy='26' r='1.4'/%3E%3Ccircle cx='74' cy='26' r='1.4'/%3E%3Ccircle cx='10' cy='42' r='1.4'/%3E%3Ccircle cx='26' cy='42' r='1.4'/%3E%3Ccircle cx='42' cy='42' r='1.4'/%3E%3Ccircle cx='58' cy='42' r='1.4'/%3E%3Ccircle cx='74' cy='42' r='1.4'/%3E%3Ccircle cx='10' cy='58' r='1.4'/%3E%3Ccircle cx='26' cy='58' r='1.4'/%3E%3Ccircle cx='42' cy='58' r='1.4'/%3E%3Ccircle cx='58' cy='58' r='1.4'/%3E%3Ccircle cx='74' cy='58' r='1.4'/%3E%3C/g%3E%3C/svg%3E")!important;
            background-size:930px 300px,900px 300px,120px 120px!important;
            background-position:left -150px top 82px,right -110px bottom 46px,right 70px center!important;
            background-repeat:no-repeat!important;
            animation:none!important;
        }
        .min-h-screen::after{
            opacity:1!important;
            background-image:
                linear-gradient(152deg, transparent 0 28%, rgba(255,255,255,.70) 28.2% 57%, transparent 57.2% 100%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='180' viewBox='0 0 220 180'%3E%3Cg fill='none' stroke='rgba(255,122,0,.48)' stroke-width='1.6'%3E%3Cpath d='M42 25 C50 45 58 53 78 61 C58 69 50 77 42 97 C34 77 26 69 6 61 C26 53 34 45 42 25Z'/%3E%3Cpath d='M168 118 C174 132 180 138 194 144 C180 150 174 156 168 170 C162 156 156 150 142 144 C156 138 162 132 168 118Z'/%3E%3C/g%3E%3C/svg%3E")!important;
            background-size:100% 100%,220px 180px!important;
            background-position:center,left 80px top 270px!important;
            background-repeat:no-repeat!important;
            animation:none!important;
        }
        .auth-container{
            width:835px!important;
            min-height:540px!important;
        }
        .form-content{padding:24px 40px!important}
        .sign-up-container .form-content{padding:20px 38px!important}
        .auth-title{font-size:25px!important}
        .auth-subtitle{margin:5px 0 13px!important;font-size:13.5px!important}
        .input-group{margin-bottom:8px!important}
        .input-label{margin-bottom:5px!important;font-size:11px!important}
        .custom-input{height:41px!important}
        .field-wrapper svg{width:17px!important;height:17px!important}
        .auth-options{margin:0 0 10px!important}
        .auth-btn{height:41px!important}
        .or-divider{margin:10px 0!important}
        .social-row{gap:10px!important}
        .social-row a{height:36px!important}
        .auth-switch-text{margin-top:9px!important}
        .overlay-panel::before{width:74px!important;height:74px!important;margin-bottom:21px!important}
        .overlay-panel h1{font-size:26px!important}
        .overlay-panel p{font-size:13.5px!important;margin-bottom:25px!important}
        .overlay-left,.overlay-right{
            background-size:210px 160px,92px 92px,100% 122px!important;
        }
        @media(max-width:860px){
            .auth-container{width:min(835px,calc(100vw - 28px))!important;min-height:540px!important}
        }
    </style>

    <div class="auth-container" id="auth-container">
        <div class="form-container sign-up-container">
            <div class="form-content">
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join us today and get started</p>

                <form method="POST" action="{{ route('register') }}" class="w-full">
                    @csrf

                    <div class="input-group">
                        <label class="input-label">Name</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" name="name" placeholder="Full Name" class="custom-input" value="{{ old('name') }}" required />
                        </div>
                        @error('name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required />
                        </div>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" id="reg_password" name="password" placeholder="Password" class="custom-input custom-input-pass" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('reg_password', 'eye-reg-1')">
                                <svg id="eye-reg-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="auth-btn">Sign Up</button>
                </form>

                <div class="or-divider"><span>OR</span></div>

                <div class="social-row">
                    <a href="{{ route('google.login') }}">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="16">
                        <span>Google</span>
                    </a>
                    <a href="{{ route('facebook.login') }}">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" width="16">
                        <span>Facebook</span>
                    </a>
                </div>
                <p class="auth-switch-text">Already have an account? <button type="button" data-auth-switch="login">Sign in</button></p>
            </div>
        </div>

        <div class="form-container sign-in-container">
            <div class="form-content">
                <h1 class="auth-title">Sign In</h1>
                <p class="auth-subtitle">Welcome back! Please sign in</p>

                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf

                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </span>
                            <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required autofocus />
                        </div>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </span>
                            <input type="password" id="login_password" name="password" placeholder="Password" class="custom-input custom-input-pass" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('login_password', 'eye-login-1')">
                                <svg id="eye-login-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="auth-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 accent-orange-500">
                            <span>Remember me</span>
                        </label>
                        <a class="forgot-link" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>

                    <button type="submit" class="auth-btn">Sign In</button>
                </form>

                <div class="or-divider"><span>OR</span></div>

                <div class="social-row">
                    <a href="{{ route('google.login') }}">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="16">
                        <span>Google</span>
                    </a>
                    <a href="{{ route('facebook.login') }}">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" width="16">
                        <span>Facebook</span>
                    </a>
                </div>
                <p class="auth-switch-text">Don't have an account? <button type="button" data-auth-switch="register">Sign up</button></p>
            </div>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>Ready to continue? Log in now to access your personal details to keep connected with us.</p>
                    <button class="ghost-btn" id="signIn">Sign In</button>
                </div>

                <div class="overlay-panel overlay-right">
                    <h1>Join Us, Today!</h1>
                    <p>Start your journey. Register with your personal details to begin journey with us.</p>
                    <button class="ghost-btn" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('auth-container');
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const authSlideDuration = 980;

        function playAuthSlide(direction) {
            if (container.classList.contains('is-sliding')) return;

            container.classList.remove('slide-to-register', 'slide-to-login');
            void container.offsetWidth;
            container.classList.add('is-sliding', direction);

            window.setTimeout(() => {
                container.classList.remove('is-sliding', direction);
            }, authSlideDuration);
        }

        signUpButton.addEventListener('click', () => {
            if (container.classList.contains('right-panel-active')) return;
            container.classList.add('right-panel-active');
            playAuthSlide('slide-to-register');
            window.history.pushState({}, '', "{{ route('register') }}");
        });

        signInButton.addEventListener('click', () => {
            if (!container.classList.contains('right-panel-active')) return;
            container.classList.remove('right-panel-active');
            playAuthSlide('slide-to-login');
            window.history.pushState({}, '', "{{ route('login') }}");
        });

        document.querySelectorAll('[data-auth-switch]').forEach((button) => {
            button.addEventListener('click', () => {
                if (button.dataset.authSwitch === 'register') signUpButton.click();
                if (button.dataset.authSwitch === 'login') signInButton.click();
            });
        });

        window.addEventListener('load', () => {
            const isRegisterPath = window.location.pathname.includes('register');
            const hasRegErrors = @json($errors->has('name') || ($errors->has('email') && old('name')));

            if (hasRegErrors || isRegisterPath) {
                container.classList.add('right-panel-active');
            }
        });

        function togglePassword(inputId, svgId) {
            const input = document.getElementById(inputId);
            const svg = document.getElementById(svgId);

            if (input.type === 'password') {
                input.type = 'text';
                svg.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            } else {
                input.type = 'password';
                svg.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            }
        }
    </script>
</x-guest-layout>
