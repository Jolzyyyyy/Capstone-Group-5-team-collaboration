<x-guest-layout :showcase="[
    'kicker' => __('New Account'),
    'title_intro' => __('Create your profile'),
    'title_focus' => __('and start printing with confidence.'),
    'text' => __('Set up your customer account once, verify your email securely, and unlock a smoother path to browsing services and placing orders.'),
    'chips' => [
        __('Simple account creation for new customers'),
        __('Secure email verification before protected access'),
        __('Ready for service browsing, checkout, and order tracking'),
    ],
    'metric_value' => __('Register'),
    'metric_text' => __('Best for first-time users who want a smooth start and verified access to the platform.'),
]">
    <div class="mb-7 text-center">
        <p class="auth-eyebrow">{{ __('New Customer') }}</p>
        <h2 class="auth-title">{{ __('Create Your Account') }}</h2>
        <p class="auth-subtitle">{{ __('Register once to browse services, place orders, and manage your account through secure email verification.') }}</p>
    </div>

    @if ($errors->any())
        <div class="auth-note auth-note--danger mb-6">
            <strong>{{ __('Please review your registration details.') }}</strong>
            <span>{{ __('If the page returned here instead of moving to OTP verification, one or more fields still need correction below.') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- First and Last Name Fields --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        {{-- Email Field --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password Field --}}
        <div class="mt-4" x-data="{ focused: false, password: '' }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative flex items-center">
                <x-text-input id="password" class="block mt-1 w-full pr-12"
                                type="password"
                                name="password"
                                x-model="password"
                                @focus="focused = true"
                                @blur="focused = false"
                                required autocomplete="new-password" />
                
                {{-- Admin-style button placement (top: 58%) --}}
                <button x-show="focused || password.length > 0" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button" onclick="togglePassword('password', 'eye-path-1', 'eye-back-path-1')" 
                        style="position: absolute; right: 12px; top: 58%; transform: translateY(-50%); z-index: 10;"
                        class="focus:outline-none border-none bg-transparent p-0">
                    <svg id="eye-icon-1" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        {{-- Eto yung specific "Slash" path mula sa Admin --}}
                        <path id="eye-path-1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        <path id="eye-back-path-1" d="" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password Field --}}
        <div class="mt-4" x-data="{ focused: false, confirm_password: '' }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative flex items-center">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-12"
                                type="password"
                                name="password_confirmation" 
                                x-model="confirm_password"
                                @focus="focused = true"
                                @blur="focused = false"
                                required autocomplete="new-password" />
                
                <button x-show="focused || confirm_password.length > 0" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button" onclick="togglePassword('password_confirmation', 'eye-path-2', 'eye-back-path-2')" 
                        style="position: absolute; right: 12px; top: 58%; transform: translateY(-50%); z-index: 10;"
                        class="focus:outline-none border-none bg-transparent p-0">
                    <svg id="eye-icon-2" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="eye-path-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        <path id="eye-back-path-2" d="" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Footer Buttons --}}
        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 auth-link" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 primary-cta">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <p class="auth-action-hint">
            {{ __('Use the same email you can access right now because your OTP verification code will be sent there after registration.') }}
        </p>
    </form>

    {{-- Script: Identical to Admin for consistent SVG behavior --}}
    <script>
        function togglePassword(inputId, pathId, backPathId) {
            const passwordInput = document.getElementById(inputId);
            const eyePath = document.getElementById(pathId);
            const eyeBackPath = document.getElementById(backPathId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Switch to OPEN EYE
                eyePath.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeBackPath.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                eyeBackPath.setAttribute('stroke-linecap', 'round');
                eyeBackPath.setAttribute('stroke-linejoin', 'round');
                eyeBackPath.setAttribute('stroke-width', '2');
            } else {
                passwordInput.type = 'password';
                // Switch back to SLASHED EYE (Admin Path)
                eyePath.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                eyeBackPath.setAttribute('d', ''); 
            }
        }
    </script>
</x-guest-layout>
