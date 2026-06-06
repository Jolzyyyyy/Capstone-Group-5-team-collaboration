<x-guest-layout>
    {{-- 1. LAYOUT RESET --}}
    <style>
        .min-h-screen {
            background-color: #f3f4f6 !important;
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
            pointer-events: none;
            z-index: 1;
        }

        .min-h-screen::before {
            opacity: 0.07;
            background-image:
                linear-gradient(45deg, #4f46e5 25%, transparent 25%),
                linear-gradient(-45deg, #4f46e5 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #4f46e5 75%),
                linear-gradient(-45deg, transparent 75%, #4f46e5 75%);
            background-size: 80px 80px;
            background-position: 0 0, 0 40px, 40px -40px, -40px 0px;
            animation: moveBackground1 25s linear infinite;
        }

        .min-h-screen::after {
            opacity: 0;
            background-image: linear-gradient(115deg, transparent 0 35%, rgba(99, 102, 241, 0.5) 48%, transparent 62%);
            background-size: 220% 220%;
            animation: moveBackground2 9s ease-in-out infinite;
        }

        @keyframes moveBackground1 {
            0% { background-position: 0 0, 0 40px, 40px -40px, -40px 0px; }
            100% { background-position: 80px 0, 80px 40px, 120px -40px, 40px 0px; }
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

        .sm:max-w-md {
            max-width: none !important;
            width: auto !important;
            box-shadow: none !important;
            background-color: transparent !important;
            padding: 0 !important;
        }
    </style>

    {{-- 2. MAIN CSS --}}
    <style>
        .auth-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            width: 620px;
            max-width: 95vw;
            min-height: 455px; 
            display: flex;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
            width: 50%;
            background-color: white;
        }

        .sign-in-container { left: 0; z-index: 2; opacity: 1; }
        .sign-up-container { left: 0; opacity: 0; z-index: 1; }

        .auth-container.right-panel-active .sign-in-container {
            transform: translateX(100%);
            opacity: 0;
            z-index: 1;
        }

        .auth-container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        /* --- CURVED OVERLAY LOGIC --- */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
            /* Ang container mismo ang magdadala ng shape transitions */
            border-top-left-radius: 60px;
            border-bottom-left-radius: 60px;
        }

        .auth-container.right-panel-active .overlay-container { 
            transform: translateX(-100%); 
            border-radius: 0;
            border-top-right-radius: 60px;
            border-bottom-right-radius: 60px;
        }

        .overlay {
            background: linear-gradient(to right, #4f46e5, #3730a3);
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .auth-container.right-panel-active .overlay { transform: translateX(50%); }

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
        }

        /* Border Radius adjustments para sa "Curved Effect" sa loob ng overlay */
        .overlay-right { 
            right: 0; 
            transform: translateX(0); 
            border-top-left-radius: 60px;
            border-bottom-left-radius: 60px;
        }
        
        .overlay-left { 
            transform: translateX(-20%); 
            border-top-right-radius: 60px;
            border-bottom-right-radius: 60px;
        }

        .auth-container.right-panel-active .overlay-left { transform: translateX(0); }
        .auth-container.right-panel-active .overlay-right { transform: translateX(20%); }
        /* --- END OF CURVED LOGIC --- */

        .overlay-panel h1 {
            font-size: 1.8rem; 
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .overlay-panel p {
            font-size: 13px; 
            line-height: 1.4;
            opacity: 0.9;
        }

        .form-content {
            padding: 25px 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            will-change: transform, opacity, filter;
        }

        .overlay-panel > * {
            will-change: transform, opacity, filter;
        }

        .auth-container.is-sliding .form-content {
            pointer-events: none;
        }

        .auth-container.slide-to-register .sign-in-container .form-content {
            animation: authSlideOutLeft 0.74s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-register .sign-up-container .form-content {
            animation: authSlideInRight 0.74s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-login .sign-up-container .form-content {
            animation: authSlideOutRight 0.74s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-login .sign-in-container .form-content {
            animation: authSlideInLeft 0.74s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-register .overlay-right > * {
            animation: overlaySlideOutLeft 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-register .overlay-left > * {
            animation: overlaySlideInRight 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-login .overlay-left > * {
            animation: overlaySlideOutRight 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .auth-container.slide-to-login .overlay-right > * {
            animation: overlaySlideInLeft 0.68s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes authSlideInRight {
            0% { opacity: 0; transform: translateX(48px); filter: blur(2px); }
            55% { opacity: 1; transform: translateX(-6px); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0); filter: blur(0); }
        }

        @keyframes authSlideInLeft {
            0% { opacity: 0; transform: translateX(-48px); filter: blur(2px); }
            55% { opacity: 1; transform: translateX(6px); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0); filter: blur(0); }
        }

        @keyframes authSlideOutLeft {
            0% { opacity: 1; transform: translateX(0); filter: blur(0); }
            45% { opacity: 0.45; transform: translateX(-18px); filter: blur(1px); }
            100% { opacity: 0; transform: translateX(-48px); filter: blur(2px); }
        }

        @keyframes authSlideOutRight {
            0% { opacity: 1; transform: translateX(0); filter: blur(0); }
            45% { opacity: 0.45; transform: translateX(18px); filter: blur(1px); }
            100% { opacity: 0; transform: translateX(48px); filter: blur(2px); }
        }

        @keyframes overlaySlideInRight {
            0% { opacity: 0; transform: translateX(34px); filter: blur(2px); }
            100% { opacity: 1; transform: translateX(0); filter: blur(0); }
        }

        @keyframes overlaySlideInLeft {
            0% { opacity: 0; transform: translateX(-34px); filter: blur(2px); }
            100% { opacity: 1; transform: translateX(0); filter: blur(0); }
        }

        @keyframes overlaySlideOutLeft {
            0% { opacity: 1; transform: translateX(0); filter: blur(0); }
            100% { opacity: 0; transform: translateX(-34px); filter: blur(2px); }
        }

        @keyframes overlaySlideOutRight {
            0% { opacity: 1; transform: translateX(0); filter: blur(0); }
            100% { opacity: 0; transform: translateX(34px); filter: blur(2px); }
        }

        .field-label {
            width: 100%;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 2px;
            margin-left: 5px;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 8px;
        }

        .input-group .field-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            z-index: 5;
        }

        .custom-input {
            background-color: #f0f2f5;
            border: 1px solid transparent !important;
            padding: 10px 15px 10px 45px;
            border-radius: 10px;
            width: 100%;
            font-size: 14px;
            color: #1f2937;
            outline: none;
            transition: border 0.3s;
        }

        .custom-input:focus {
            border: 1px solid #4f46e5 !important;
            background-color: #fff;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .eye-icon-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 2px;
            color: #9ca3af;
            z-index: 10;
        }
        
        .error-text {
            color: #dc2626;
            font-size: 10px;
            width: 100%;
            text-align: left;
            margin-top: -6px;
            margin-bottom: 6px;
            padding-left: 5px;
        }

        .auth-options {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: 100%;
            margin-top: 2px;
            font-size: 12px; 
            color: #64748b;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .auth-btn {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 0;
            border-radius: 30px;
            width: 100%;
            margin-top: 15px;
            cursor: pointer;
            font-size: 13px;
            border: none;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }

        .auth-btn:hover { background-color: #4338ca; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .auth-btn:active { transform: scale(0.98); }
        
        .ghost-btn {
            background-color: transparent;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
            padding: 8px 40px;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }

        .ghost-btn:hover { background-color: white; color: #4f46e5; }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #1f2937;
        }
    </style>

    <div class="auth-container" id="auth-container">
        
        {{-- ADMIN/DEVELOPER REGISTER FORM --}}
        <div class="form-container sign-up-container">
            <div class="form-content">
                <h1 class="auth-title">Create Account</h1>
                <form method="POST" action="{{ route('admin.register.submit') }}" class="w-full">
                    @csrf
                    
                    {{-- 1. Name --}}
                    <label class="field-label">Name</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </span>
                        <input type="text" name="name" placeholder="Full Name" class="custom-input" value="{{ old('name') }}" required />
                    </div>
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror

                    {{-- 2. Email Address --}}
                    <label class="field-label">Email Address</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </span>
                        <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required />
                    </div>
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror

                    {{-- 3. Role Select (Admin/Developer) --}}
                    <label class="field-label">System Role</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </span>
                        <select name="role" class="custom-input" required>
                            <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected(old('role') === \App\Models\User::ROLE_ADMIN)>Administrator</option>
                            <option value="{{ \App\Models\User::ROLE_DEVELOPER }}" @selected(old('role') === \App\Models\User::ROLE_DEVELOPER)>System Developer</option>
                        </select>
                    </div>

                    {{-- 4. Password --}}
                    <label class="field-label">Password</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </span>
                        <div class="password-wrapper">
                            <input type="password" id="reg_password" name="password" placeholder="Password" class="custom-input" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('reg_password', 'eye-reg-1')">
                                <svg id="eye-reg-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                    @error('password') <p class="error-text">{{ $message }}</p> @enderror

                    <button type="submit" class="auth-btn">Sign Up</button>
                </form>
            </div>
        </div>

        {{-- ADMIN/DEVELOPER LOGIN FORM --}}
        <div class="form-container sign-in-container">
            <div class="form-content">
                <h1 class="auth-title">Sign In</h1>
                <form method="POST" action="{{ route('admin.login.submit') }}" class="w-full">
                    @csrf
                    
                    {{-- 1. Email Address --}}
                    <label class="field-label">Email Address</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </span>
                        <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required autofocus />
                    </div>
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    
                    {{-- 2. Password --}}
                    <label class="field-label">Password</label>
                    <div class="input-group">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </span>
                        <div class="password-wrapper">
                            <input type="password" id="login_password" name="password" placeholder="Password" class="custom-input" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('login_password', 'eye-login-1')">
                                <svg id="eye-login-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>

                    <div class="auth-options">
                        <label for="remember_me" class="remember-me">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">Sign In</button>
                </form>
            </div>
        </div>

        {{-- OVERLAY PANELS WITH CURVED EFFECT --}}
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Staff Portal</h1>
                    <p>Already have an admin or developer account? Sign in to manage the system.</p>
                    <button class="ghost-btn" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>New Staff?</h1>
                    <p>Create an admin or developer account to access the correct dashboard.</p>
                    <button class="ghost-btn" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('auth-container');
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');

        const loginUrl = "/p-co-2026/login-7b5e93-adm-key";
        const registerUrl = "/p-co-2026/register-7b5e93-adm-key";

        function updateUrl(path, title) {
            window.history.pushState({}, title, path);
        }

        const authSlideDuration = 760;

        function playAuthSlide(direction) {
            container.classList.remove('is-sliding', 'slide-to-register', 'slide-to-login');
            void container.offsetWidth;
            container.classList.add('is-sliding', direction);
            window.setTimeout(() => {
                container.classList.remove('is-sliding', direction);
            }, authSlideDuration);
        }

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            playAuthSlide('slide-to-register');
            updateUrl(registerUrl, "Admin Register");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            playAuthSlide('slide-to-login');
            updateUrl(loginUrl, "Admin Login");
        });

        window.addEventListener('DOMContentLoaded', () => {
            if (window.location.pathname.includes('register-7b5e93-adm-key')) {
                container.classList.add("right-panel-active");
            }
        });

        window.addEventListener('popstate', () => {
            if (window.location.pathname.includes('register-7b5e93-adm-key')) {
                container.classList.add("right-panel-active");
            } else {
                container.classList.remove("right-panel-active");
            }
        });

        function togglePassword(inputId, svgId) {
            const input = document.getElementById(inputId);
            const svg = document.getElementById(svgId);
            
            if (input.type === "password") {
                input.type = "text";
                svg.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            } else {
                input.type = "password";
                svg.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            }
        }
    </script>
</x-guest-layout>
