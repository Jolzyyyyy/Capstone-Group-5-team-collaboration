<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Header Section --}}
    <div class="mb-4 text-center">
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">{{ __('Create Account') }}</h2>
    </div>

    @if ($errors->any())
        <div class="auth-note auth-note--danger mb-6">
            <strong>{{ __('Please review your registration details.') }}</strong>
            <span>{{ __('If the page returned here instead of moving to OTP verification, one or more fields still need correction below.') }}</span>
        </div>
    @endif

    @if (Route::has('google.login'))
        <a href="{{ route('google.login') }}" class="mb-6 flex min-h-[3rem] w-full items-center justify-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-black uppercase tracking-[0.14em] text-slate-800 shadow-sm transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-base font-black text-[#ff8d2a]">G</span>
            {{ __('Sign up with Google') }}
        </a>

        <div class="mb-6 flex items-center gap-3 text-xs font-black uppercase tracking-[0.18em] text-slate-400">
            <span class="h-px flex-1 bg-slate-200"></span>
            <span>{{ __('or') }}</span>
            <span class="h-px flex-1 bg-slate-200"></span>
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
                
                {{-- Eye Toggle Button - Admin Login Style --}}
                <button x-show="focused || password.length > 0" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button" onclick="togglePassword('password', 'eye-path-1', 'eye-back-path-1')" 
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;"
                        class="focus:outline-none border-none bg-transparent p-0">
                    <svg id="eye-icon-1" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="eye-path-1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268-2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
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
                
                {{-- Eye Toggle Button - Admin Login Style --}}
                <button x-show="focused || confirm_password.length > 0" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        type="button" onclick="togglePassword('password_confirmation', 'eye-path-2', 'eye-back-path-2')" 
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;"
                        class="focus:outline-none border-none bg-transparent p-0">
                    <svg id="eye-icon-2" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="eye-path-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268-2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        <path id="eye-back-path-2" d="" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Footer Buttons --}}
        <div class="flex items-center justify-between mt-6 px-1">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <p class="auth-action-hint">
            {{ __('Use the same email you can access right now because your OTP verification code will be sent there after registration.') }}
        </p>
    </form>

    {{-- Social Section --}}
    <div class="mt-6 px-1">
        <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500 uppercase tracking-widest text-xs font-semibold">
                    {{ __('OR CONTINUE WITH') }}
                </span>
            </div>
        </div>

        <div class="mt-6 flex justify-center items-center gap-6">
            {{-- Google Button --}}
            <a href="{{ route('google.login') }}" class="flex items-center justify-center bg-white border border-gray-300 rounded-full p-2 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-6 h-6" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z"></path>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                </svg>
            </a>

            {{-- Facebook Button --}}
            <a href="{{ route('facebook.login') }}" class="flex items-center justify-center bg-white border border-gray-300 rounded-full p-2 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-6 h-6" viewBox="0 0 24 24">
                    <path fill="#1877F2" d="M24 12c0-6.627-5.373-12-12-12S0 5.373 0 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12z"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function togglePassword(inputId, pathId, backPathId) {
            const passwordInput = document.getElementById(inputId);
            const eyePath = document.getElementById(pathId);
            const eyeBackPath = document.getElementById(backPathId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Eye Open (Admin Style)
                eyePath.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeBackPath.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                eyeBackPath.setAttribute('stroke-width', '2');
                eyeBackPath.setAttribute('stroke-linecap', 'round');
                eyeBackPath.setAttribute('stroke-linejoin', 'round');
            } else {
                passwordInput.type = 'password';
                // Eye Closed (Admin Style)
                eyePath.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268-2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                eyeBackPath.setAttribute('d', ''); 
            }
        }
    </script>
</x-guest-layout>
