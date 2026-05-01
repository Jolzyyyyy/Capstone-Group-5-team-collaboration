<x-guest-layout :showcase="[
    'kicker' => __('Secure Access'),
    'title_intro' => __('Sign in and'),
    'title_focus' => __('pick up where you left off.'),
    'text' => __('Return to your dashboard, check order progress, and continue managing print requests with a fast and trusted sign-in flow.'),
    'chips' => [
        __('Protected customer access with OTP verification'),
        __('Quick route back to pending orders and service requests'),
        __('Smooth login experience built for repeat customers'),
    ],
    'metric_value' => __('Login'),
    'metric_text' => __('Designed for returning customers who need fast, secure access to their account.'),
]">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-7 text-center">
        <p class="auth-eyebrow">{{ __('Secure Access') }}</p>
        <h2 class="auth-title">{{ __('Welcome Back') }}</h2>
        <p class="auth-subtitle">{{ __('Sign in to continue your print requests, track orders, and securely verify your account when needed.') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL FIELD --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- PASSWORD FIELD --}}
        <div class="mt-4" x-data="{ focused: false, password: '' }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative flex items-center">
                <x-text-input id="password" class="block mt-1 w-full pr-12"
                                type="password"
                                name="password"
                                x-model="password"
                                @focus="focused = true"
                                @blur="focused = false"
                                required autocomplete="current-password" />
                
                {{-- Admin-style Eye Button (top: 58%) --}}
                <button x-show="focused || password.length > 0" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button" onclick="togglePassword()" 
                        style="position: absolute; right: 12px; top: 58%; transform: translateY(-50%); z-index: 10;"
                        class="focus:outline-none border-none bg-transparent p-0">
                    <svg id="eye-icon" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="eye-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        <path id="eye-back-path" d="" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me & Forgot Password Section --}}
        <div class="flex items-center justify-between mt-4">
            {{-- Remember Me: Nasa LEFT side --}}
            <label for="remember_me" class="remember-wrap">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            {{-- Forgot Password: Nasa RIGHT side --}}
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        {{-- Log In Button Section: Ginaya ang Admin layout at sizing --}}
        {{-- Naka-justify-end para sa kanan lumabas ang button --}}
        <div class="flex items-center justify-end mt-4">
            {{-- Tinanggal ang 'w-full' at 'py-3', ginawang katulad ng Admin size --}}
            <x-primary-button class="ms-3 primary-cta">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="auth-inline-switch">
            <p>
                {{ __("Don't have an account yet?") }}
                <a href="{{ route('register') }}" class="auth-link hover:underline">
                    {{ __('Create one here') }}
                </a>
            </p>
        </div>
    </form>

    {{-- Script: Keep consistent eye logic --}}
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyePath = document.getElementById('eye-path');
            const eyeBackPath = document.getElementById('eye-back-path');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyePath.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeBackPath.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'); 
                eyeBackPath.setAttribute('stroke-linecap', 'round');
                eyeBackPath.setAttribute('stroke-linejoin', 'round');
                eyeBackPath.setAttribute('stroke-width', '2');
            } else {
                passwordInput.type = 'password';
                eyePath.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                eyeBackPath.setAttribute('d', ''); 
            }
        }
    </script>
</x-guest-layout>
