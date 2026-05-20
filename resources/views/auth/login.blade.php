<x-guest-layout>
    {{-- 1. LAYOUT RESET, ANIMATED BACKGROUND & STYLES --}}
    <style>
        :root {
            --primary-orange: #FF8C00;
            --dark-black: #1a1a1a;
            --hover-black: #2a2a2a; /* Slightly lighter for button hover */
            --link-blue: #2563eb;    /* Modern blue for forgot password */
            --social-hover: #f3f4f6; /* Very light gray for social buttons */
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

        /* Animated Printing Business Geometric Patterns */
        .min-h-screen::before, .min-h-screen::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
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
            background-position: 0 0, 0 40px, 40px -40px, -40px 0px;
            animation: moveBackground1 25s linear infinite;
        }

        .min-h-screen::after {
            background-image: 
                linear-gradient(115deg, transparent 0 35%, rgba(37, 99, 235, 0.5) 48%, transparent 62%);
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
            min-height: 450px; 
            display: flex;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            /* ADJUSTED: Ultra smooth slide transition */
            transition: all 0.7s cubic-bezier(0.645, 0.045, 0.355, 1.000);
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
            animation: show 0.7s cubic-bezier(0.645, 0.045, 0.355, 1.000);
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            /* ADJUSTED: Smooth slide transition */
            transition: all 0.7s cubic-bezier(0.645, 0.045, 0.355, 1.000);
            z-index: 100;
            border-radius: 100px 0 0 100px;
        }

        .auth-container.right-panel-active .overlay-container {
            transform: translateX(-100%);
            border-radius: 0 100px 100px 0;
        }

        .overlay {
            background: linear-gradient(to right, #ff4b2b, var(--primary-orange));
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            /* ADJUSTED: Smooth overlay movement */
            transition: transform 0.7s cubic-bezier(0.645, 0.045, 0.355, 1.000);
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

        .overlay-panel h1 {
            font-size: 1.5rem; 
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .overlay-panel p {
            font-size: 13px; 
            line-height: 1.4;
            opacity: 0.9;
        }

        .overlay-left { transform: translateX(-20%); transition: transform 0.7s; }
        .auth-container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); transition: transform 0.7s; }
        .auth-container.right-panel-active .overlay-right { transform: translateX(20%); }

        .form-content {
            padding: 20px 30px;
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

        /* ADJUSTED: Added more padding-right for eye icon space */
        .custom-input-pass {
            padding-right: 45px !important;
        }

        .eye-icon-btn {
            position: absolute;
            /* ADJUSTED: More padding from edge to avoid clipping */
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

        /* UPDATED: Hover color to Blue */
        .forgot-link:hover { color: var(--link-blue); }

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

        /* UPDATED: Slightly lighter dark gray hover */
        .auth-btn:hover { background-color: var(--hover-black); }
        
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
        }

        .ghost-btn:hover { background-color: rgba(255, 255, 255, 0.15); }

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

        /* UPDATED: Social Row spacing */
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

        /* UPDATED: Social hover effect */
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
    </style>

    <div class="auth-container" id="auth-container">
        {{-- REGISTER FORM --}}
        <div class="form-container sign-up-container">
            <div class="form-content">
                <h1 class="auth-title">Create Account</h1>
                <form method="POST" action="{{ route('register') }}" class="w-full">
                    @csrf
                    
                    <div class="input-group">
                        <label class="input-label">Name</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </span>
                            <input type="text" name="name" placeholder="Full Name" class="custom-input" value="{{ old('name') }}" required />
                        </div>
                        @error('name') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </span>
                            <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required />
                        </div>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            <input type="password" id="reg_password" name="password" placeholder="Password" class="custom-input custom-input-pass" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('reg_password', 'eye-reg-1')">
                                <svg id="eye-reg-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="auth-btn">Sign Up</button>
                </form>

                <div class="or-divider"><span>OR</span></div>
                <div class="social-row">
                    <a href="{{ route('google.login') }}"><img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="16"></a>
                    <a href="{{ route('facebook.login') }}"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" width="16"></a>
                </div>
            </div>
        </div>

        {{-- LOGIN FORM --}}
        <div class="form-container sign-in-container">
            <div class="form-content">
                <h1 class="auth-title">Sign In</h1>
                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf
                    
                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </span>
                            <input type="email" name="email" placeholder="name@example.com" class="custom-input" value="{{ old('email') }}" required autofocus />
                        </div>
                        @error('email') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password</label>
                        <div class="field-wrapper">
                            <span class="field-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </span>
                            <input type="password" id="login_password" name="password" placeholder="Password" class="custom-input custom-input-pass" required />
                            <button type="button" class="eye-icon-btn" onclick="togglePassword('login_password', 'eye-login-1')">
                                <svg id="eye-login-1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
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
                    <a href="{{ route('google.login') }}"><img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="16"></a>
                    <a href="{{ route('facebook.login') }}"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" width="16"></a>
                </div>
            </div>
        </div>

        {{-- OVERLAY PANELS --}}
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
            window.history.pushState({}, '', "{{ route('register') }}");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            playAuthSlide('slide-to-login');
            window.history.pushState({}, '', "{{ route('login') }}");
        });

        window.addEventListener('load', () => {
            const isRegisterPath = window.location.pathname.includes('register');
            const hasRegErrors = @json($errors->has('name') || $errors->has('password_confirmation') || ($errors->has('email') && old('name')));
            if (hasRegErrors || isRegisterPath) {
                container.classList.add("right-panel-active");
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

