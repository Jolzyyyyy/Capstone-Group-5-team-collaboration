<x-guest-layout>
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
    {{-- Main Form --}}
<form method="POST" action="{{ route('password.update') }}" x-ref="resetForm" id="resetForm">
    @csrf
    <input type="hidden" name="token" value="{{ $token ?? (request()->route('token') ?? session('password_reset_token')) }}">
    <input type="hidden" name="action_type" :value="actionType">

    {{-- Email Field (MANUAL INPUT NOW) --}}
    <div class="mb-4 text-left">
        <x-input-label for="email" :value="__('Account Email')" class="text-xs font-bold text-gray-600 uppercase" />
        <x-text-input id="email" class="block mt-1 w-full border-gray-300 text-sm" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

            {{-- New Password Field --}}
            <div class="mt-4 text-left">
                <x-input-label for="password" :value="__('New Password')" class="text-xs font-bold text-gray-600 uppercase" />
                <div class="relative flex items-center">
                    <x-text-input id="password" 
                                class="block mt-1 w-full pr-12" 
                                type="password" 
                                name="password" 
                                x-model="password" 
                                @focus="focusedPass = true"
                                @blur="focusedPass = false"
                                required />
                    
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
            </div>

            {{-- Confirm Password Field --}}
            <div class="mt-4 text-left">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold text-gray-600 uppercase" />
                <div class="relative flex items-center">
                    <x-text-input id="password_confirmation" 
                                class="block mt-1 w-full pr-12" 
                                type="password" 
                                name="password_confirmation" 
                                x-model="password_confirmation" 
                                @focus="focusedConfirm = true"
                                @blur="focusedConfirm = false"
                                required />
                    
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

            {{-- Password Validation Indicators (FORCED SMALL) --}}
            <div class="mt-5 space-y-1 text-left">
                <div :class="hasStartedInput() ? (rules.length ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" 
                     class="flex items-center italic uppercase tracking-wider" style="font-size: 12px !important;">
                    <span x-text="hasStartedInput() && rules.length ? '✔' : '✘'" class="mr-2 not-italic"></span> At least 8 characters
                </div>
                <div :class="hasStartedInput() ? (rules.number ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" 
                     class="flex items-center italic uppercase tracking-wider" style="font-size: 12px !important;">
                    <span x-text="hasStartedInput() && rules.number ? '✔' : '✘'" class="mr-2 not-italic"></span> Must include a number
                </div>
                <div :class="hasStartedInput() ? (rules.symbol ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" 
                     class="flex items-center italic uppercase tracking-wider" style="font-size: 12px !important;">
                    <span x-text="hasStartedInput() && rules.symbol ? '✔' : '✘'" class="mr-2 not-italic"></span> Must include a special character
                </div>
                <div :class="password_confirmation.length > 0 ? (rules.match ? 'text-green-600 font-bold' : 'text-red-600 font-bold') : 'text-gray-400'" 
                     class="flex items-center italic uppercase tracking-wider" style="font-size: 12px !important;">
                    <span x-text="password_confirmation.length > 0 && rules.match ? '✔' : '✘'" class="mr-2 not-italic"></span> Passwords must match
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex flex-row items-center justify-between">
                {{-- Back to Login (Left Side & Forced Small) --}}
                <a href="{{ route('login') }}" class="font-bold text-gray-500 uppercase tracking-widest hover:text-gray-800 transition-colors" style="font-size: 15px !important;">
                    {{ __('Back to Login') }}
                </a>

                {{-- Update Password Button (Right Side) --}}
                <x-primary-button type="button" @click="if(rules.length && rules.number && rules.symbol && rules.match) showConfirmReset = true" 
                    class="bg-gray-800 px-6 py-3 justify-center" style="font-size: 10px !important;">
                    {{ __('Update Password') }}
                </x-primary-button>
            </div>
        </form>

        {{-- MODAL 1: CONFIRMATION (NO CHANGES) --}}
        <div x-show="showConfirmReset" x-cloak style="display: none; background-color: rgba(0, 0, 0, 0.1); backdrop-filter: blur(2px); z-index: 9999; position: fixed;" class="fixed inset-0 flex items-center justify-center p-4">
            <div x-show="showConfirmReset" @click.stop class="bg-white rounded-[2.5rem] px-10 py-11 pt-12 pb-12 shadow-2xl border border-slate-100 mx-auto relative" style="width: 100%; max-width: 448px; z-index: 10000; position: relative;">
                <button @click="showConfirmReset = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="flex flex-col items-center text-center">
                    <div class="mb-4 flex items-center justify-center w-12 h-12 bg-amber-50 text-amber-500 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Confirm Change?</h3>
                    <p class="text-[11px] text-slate-500 mb-8">Update your password now?</p>
                    <div class="flex flex-row w-full gap-3">
                        <button @click="showConfirmReset = false; showModal = true" style="background-color: #f1f5f9; color: #64748b; transition: 0.3s; border: 1px solid #e2e8f0;" onmouseover="this.style.backgroundColor='#0f172a'; this.style.color='white'" onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#64748b'" class="flex-1 py-3 rounded-xl font-bold text-[10px] uppercase cursor-pointer">YES</button>
                        <button @click="showConfirmReset = false" style="background-color: #f1f5f9; color: #64748b; transition: 0.3s; border: 1px solid #e2e8f0;" onmouseover="this.style.backgroundColor='#0f172a'; this.style.color='white'" onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#64748b'" class="flex-1 py-3 rounded-xl font-bold text-[10px] uppercase cursor-pointer">NO</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 2: SUCCESS (NO CHANGES) --}}
        <div x-show="showModal" x-cloak style="display: none; background-color: rgba(0, 0, 0, 0.1); backdrop-filter: blur(2px); z-index: 9999; position: fixed;" class="fixed inset-0 flex items-center justify-center p-4">
            <div x-show="showModal" @click.stop class="bg-white rounded-[2.5rem] px-10 py-11 pt-12 pb-11 shadow-2xl border border-slate-100 mx-auto relative" style="width: 100%; max-width: 448px; z-index: 10000; position: relative;">
                <button @click="showModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="flex flex-col items-center text-center">
                    <div class="mb-4 flex items-center justify-center w-12 h-12 bg-green-50 text-green-500 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Success!</h3>
                    <p class="text-[11px] text-slate-500 mb-8">Choose your next step:</p>
                    <div class="flex flex-row w-full gap-3">
                        <button type="button" @click="submitResetForm('auto_login')" style="background-color: #f1f5f9; color: #64748b; transition: 0.3s; border: 1px solid #e2e8f0;" onmouseover="this.style.backgroundColor='#0f172a'; this.style.color='white'" onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#64748b'" class="flex-1 py-3 rounded-xl font-black text-[8px] uppercase cursor-pointer leading-tight">CONTINUE TO LOGIN</button>
                        <button type="button" @click="submitResetForm('manual_login')" style="background-color: #f1f5f9; color: #64748b; transition: 0.3s; border: 1px solid #e2e8f0;" onmouseover="this.style.backgroundColor='#0f172a'; this.style.color='white'" onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#64748b'" class="flex-1 py-3 rounded-xl font-bold text-[8px] uppercase cursor-pointer leading-tight">BACK TO LOGIN</button>
                    </div>
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