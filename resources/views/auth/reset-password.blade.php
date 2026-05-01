<x-guest-layout :showcase="[
    'kicker' => __('New Password'),
    'title_intro' => __('Finish the recovery'),
    'title_focus' => __('with a stronger password.'),
    'text' => __('This final step lets you replace the old password, confirm the new one, and restore secure access to your account.'),
    'chips' => [
        __('Guided password rules for stronger account security'),
        __('Confirmation step before credentials are updated'),
        __('Smooth transition back to login after password reset'),
    ],
    'metric_value' => __('Secure'),
    'metric_text' => __('Focused on helping users finish recovery with a password that is stronger and easier to trust.'),
]">
    <div x-data="{ 
        password: '', 
        password_confirmation: '',
        showModal: false,
        showConfirmReset: false,
        actionType: 'auto_login',
        focusedPass: false,
        focusedConfirm: false,

        get rules() {
            return {
                length: this.password.length >= 8,
                number: /[0-9]/.test(this.password),
                symbol: /[!@#$%^&*(),.?':{}|<>]/.test(this.password),
                match: this.password === this.password_confirmation && this.password !== ''
            }
        },
        hasStartedInput() { return this.password.length > 0; },
        submitResetForm(type) {
            this.actionType = type;
            this.$nextTick(() => { document.getElementById('resetForm').submit(); });
        }
    }">
        <div class="mb-7 text-center">
            <p class="auth-eyebrow">{{ __('Password Reset') }}</p>
            <h2 class="auth-title">{{ __('Create a New Password') }}</h2>
            <p class="auth-subtitle">{{ __('Choose a strong new password and confirm it before updating your account security.') }}</p>
        </div>

        <div class="auth-note">
            {{ __('A strong password should include letters, numbers, and a symbol for better account protection.') }}
        </div>

        <form method="POST" action="{{ route('password.update') }}" x-ref="resetForm" id="resetForm" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? (request()->route('token') ?? session('password_reset_token')) }}">
            <input type="hidden" name="action_type" :value="actionType">
            <input type="text" name="reset_username_hint" class="hidden" tabindex="-1" autocomplete="username" aria-hidden="true">
            <input type="password" name="reset_password_hint" class="hidden" tabindex="-1" autocomplete="new-password" aria-hidden="true">

            <div class="auth-panel p-5">
                <div class="mb-4">
                    <p class="auth-section-title">{{ __('Account Security') }}</p>
                    <p class="auth-microcopy mt-1">{{ __('Enter your account email, create a stronger replacement password, and confirm it before we update your credentials.') }}</p>
                </div>

                <div class="mb-4 text-left">
                    <x-input-label for="email" :value="__('Account Email')" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4 text-left">
                    <x-input-label for="password" :value="__('New Password')" />
                    <div class="relative flex items-center">
                        <x-text-input id="password"
                            class="block mt-1 w-full pr-12"
                            type="password"
                            name="password"
                            x-model="password"
                            @focus="focusedPass = true"
                            @blur="focusedPass = false"
                            required
                            autocomplete="new-password"
                            autocapitalize="none"
                            spellcheck="false" />

                        <button x-show="(focusedPass || password.length > 0) && !showConfirmReset && !showModal"
                            type="button"
                            onclick="togglePassword('password', 'eye-path-1', 'eye-back-path-1')"
                            style="position: absolute; right: 12px; top: 58%; transform: translateY(-50%); z-index: 10;"
                            class="focus:outline-none border-none bg-transparent p-0">
                            <svg id="eye-icon-1" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eye-path-1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                <path id="eye-back-path-1" d="" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4 text-left">
                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                    <div class="relative flex items-center">
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full pr-12"
                            type="password"
                            name="password_confirmation"
                            x-model="password_confirmation"
                            @focus="focusedConfirm = true"
                            @blur="focusedConfirm = false"
                            required
                            autocomplete="new-password"
                            autocapitalize="none"
                            spellcheck="false" />

                        <button x-show="(focusedConfirm || password_confirmation.length > 0) && !showConfirmReset && !showModal"
                            type="button"
                            onclick="togglePassword('password_confirmation', 'eye-path-2', 'eye-back-path-2')"
                            style="position: absolute; right: 12px; top: 58%; transform: translateY(-50%); z-index: 10;"
                            class="focus:outline-none border-none bg-transparent p-0">
                            <svg id="eye-icon-2" class="h-6 w-6 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eye-path-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                <path id="eye-back-path-2" d="" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="auth-rule-list text-left">
                    <div :class="hasStartedInput() ? (rules.length ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" class="flex items-center text-[0.82rem] leading-6">
                        <span x-text="hasStartedInput() && rules.length ? '✔' : '✕'" class="mr-2"></span> {{ __('At least 8 characters') }}
                    </div>
                    <div :class="hasStartedInput() ? (rules.number ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" class="flex items-center text-[0.82rem] leading-6">
                        <span x-text="hasStartedInput() && rules.number ? '✔' : '✕'" class="mr-2"></span> {{ __('Includes a number') }}
                    </div>
                    <div :class="hasStartedInput() ? (rules.symbol ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" class="flex items-center text-[0.82rem] leading-6">
                        <span x-text="hasStartedInput() && rules.symbol ? '✔' : '✕'" class="mr-2"></span> {{ __('Includes a special character') }}
                    </div>
                    <div :class="password_confirmation.length > 0 ? (rules.match ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" class="flex items-center text-[0.82rem] leading-6 pb-3">
                        <span x-text="password_confirmation.length > 0 && rules.match ? '✔' : '✕'" class="mr-2"></span> {{ __('Passwords match') }}
                    </div>
                </div>
            </div>

            <div class="mt-7 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('login') }}" class="auth-link text-sm font-bold uppercase tracking-[0.2em]">
                    {{ __('Back to Login') }}
                </a>

                <x-primary-button type="button" @click="if(rules.length && rules.number && rules.symbol && rules.match) showConfirmReset = true" class="primary-cta w-full sm:w-auto justify-center px-7">
                    {{ __('Update Password') }}
                </x-primary-button>
            </div>
        </form>

        <div x-show="showConfirmReset" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/25 backdrop-blur-sm p-4" style="display: none;">
            <div x-show="showConfirmReset" @click.stop class="auth-card w-full max-w-md !p-8">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <p class="auth-eyebrow">{{ __('Final Check') }}</p>
                    <h3 class="auth-section-title mt-3">{{ __('Confirm Password Change') }}</h3>
                    <p class="auth-microcopy mt-2">{{ __('Make sure your email and new password are correct before we update your account security.') }}</p>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button @click="showConfirmReset = false; showModal = true" type="button" class="auth-secondary-button">
                        {{ __('Yes') }}
                    </button>
                    <button @click="showConfirmReset = false" type="button" class="auth-secondary-button">
                        {{ __('No') }}
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/25 backdrop-blur-sm p-4" style="display: none;">
            <div x-show="showModal" @click.stop class="auth-card w-full max-w-md !p-8">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="auth-eyebrow">{{ __('Password Updated') }}</p>
                    <h3 class="auth-section-title mt-3">{{ __('Choose Your Next Step') }}</h3>
                    <p class="auth-microcopy mt-2">{{ __('You can continue directly to sign in, or return to the login page and enter your new password manually.') }}</p>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button type="button" @click="submitResetForm('auto_login')" class="primary-cta !min-h-[3rem]">
                        {{ __('Continue to Login') }}
                    </button>
                    <button type="button" @click="submitResetForm('manual_login')" class="auth-secondary-button">
                        {{ __('Back to Login') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, pathId, backPathId) {
            const passwordInput = document.getElementById(inputId);
            const eyePath = document.getElementById(pathId);
            const eyeBackPath = document.getElementById(backPathId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyePath.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeBackPath.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                eyeBackPath.setAttribute('stroke-width', '2');
            } else {
                passwordInput.type = 'password';
                eyePath.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                eyeBackPath.setAttribute('d', '');
            }
        }
    </script>
</x-guest-layout>
