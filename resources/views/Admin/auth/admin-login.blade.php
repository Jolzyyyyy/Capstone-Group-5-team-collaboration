<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4 text-center">
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">Staff & Developer Portal</h2>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Portal Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

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

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Portal Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyePath = document.getElementById('eye-path');
            const eyeBackPath = document.getElementById('eye-back-path');

            if (passwordInput.type === 'password') {
                // Show Password
                passwordInput.type = 'text';
                eyePath.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeBackPath.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'); 
                eyeBackPath.setAttribute('stroke-linecap', 'round');
                eyeBackPath.setAttribute('stroke-linejoin', 'round');
                eyeBackPath.setAttribute('stroke-width', '2');
            } else {
                // Hide Password
                passwordInput.type = 'password';
                eyePath.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                eyeBackPath.setAttribute('d', ''); 
            }
        }
    </script>
</x-guest-layout>
