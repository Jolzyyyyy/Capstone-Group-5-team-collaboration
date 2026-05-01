@php
    $verificationFlow = $verificationFlow ?? 'account_verification';
    $isForgotPasswordFlow = $verificationFlow === 'forgot_password';
    $otpLockoutSeconds = $otpLockoutSeconds ?? 0;
    $resendCooldownSeconds = $resendCooldownSeconds ?? 0;
    $otpAttemptCount = $otpAttemptCount ?? (int) session('otp_attempt_count', 0);
    $otpAttemptMax = $otpAttemptMax ?? 3;
@endphp

<x-guest-layout :showcase="$isForgotPasswordFlow
    ? [
        'kicker' => __('Recovery Check'),
        'title_intro' => __('Verify the code'),
        'title_focus' => __('and continue your password reset.'),
        'text' => __('Enter the OTP from your email to confirm this recovery request before setting a new password.'),
        'chips' => [
            __('Time-limited verification designed for password recovery'),
            __('Smooth handoff from OTP verification to reset-password step'),
            __('Safer recovery flow with clear resend timing'),
        ],
        'metric_value' => __('OTP'),
        'metric_text' => __('A short-lived recovery code adds one more security check before a password can be changed.'),
    ]
    : [
        'kicker' => __('Account Protection'),
        'title_intro' => __('Verify your account'),
        'title_focus' => __('before entering the protected area.'),
        'text' => __('This one-time code confirms the account owner and unlocks secure access to customer-only pages and actions.'),
        'chips' => [
            __('Required verification before dashboard and checkout access'),
            __('Fresh OTP support for newly registered or unverified users'),
            __('Built to keep customer routes protected until verification succeeds'),
        ],
        'metric_value' => __('5 min'),
        'metric_text' => __('The code stays valid long enough for email delivery while still staying short-lived for better security.'),
    ]">
    <div class="mb-7 text-center">
        <p class="auth-eyebrow">
            {{ $isForgotPasswordFlow ? __('Recovery Check') : __('Account Protection') }}
        </p>
        <h2 class="auth-title">
            {{ $isForgotPasswordFlow ? __('Verify Password Reset') : __('Verify Your Account') }}
        </h2>
        <p class="auth-subtitle">
            {{ $isForgotPasswordFlow
                ? __('Confirm the recovery code sent to your email so you can continue setting a new password.')
                : __('Use the one-time verification code from your email to unlock your account access securely.') }}
        </p>
    </div>

    <div class="auth-note text-center">
        {{ $isForgotPasswordFlow
            ? __('Pakisulat ang 6-digit security code na ipinadala namin para maipagpatuloy ang password reset.')
            : __('Pakisulat ang 6-digit security code na ipinadala namin sa iyong Gmail para ma-verify ang iyong account.') }}
        <div class="mt-2 text-[0.8rem] text-slate-500">
            {{ $isForgotPasswordFlow
                ? __('Use the verification code within 5 minutes. After verification, you can set and confirm your new password.')
                : __('Use the verification code within 5 minutes to complete your registration or login verification. After that, request a new one to continue.') }}
        </div>
    </div>

    {{-- Status Message --}}
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 text-center bg-green-50 p-2 rounded border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <div x-data="{ 
        otp: '', 
        resendTimer: {{ (int) $resendCooldownSeconds }},
        otpLockoutTimer: {{ (int) $otpLockoutSeconds }},
        init() {
            let interval = setInterval(() => {
                if (this.resendTimer > 0) this.resendTimer--;
                if (this.otpLockoutTimer > 0) this.otpLockoutTimer--;
                if (this.resendTimer <= 0 && this.otpLockoutTimer <= 0) clearInterval(interval);
            }, 1000);
        },
        get canResend() { return this.resendTimer <= 0 && this.otpLockoutTimer <= 0; },
        get canVerify() { return this.otpLockoutTimer <= 0; },
        formatSeconds(value) {
            const minutes = Math.floor(value / 60);
            const seconds = value % 60;
            return minutes > 0 ? `${minutes}m ${String(seconds).padStart(2, '0')}s` : `${seconds}s`;
        },
    }">
        <div x-show="otpLockoutTimer > 0" class="mb-4 text-red-700 text-sm text-center font-bold bg-red-50 p-3 rounded border border-red-200">
            {{ __('Too many incorrect verification attempts. Please wait before trying again.') }}
            <div class="mt-1">
                {{ __('Verification and resend will be available again in') }}
                <strong x-text="formatSeconds(otpLockoutTimer)"></strong>.
            </div>
        </div>

        <form method="POST" action="{{ route('otp.submit') }}">
            @csrf

            {{-- Hidden email field: Kinukuha ang email sa pinaka-available na source --}}
            <input type="hidden" name="email" value="{{ $email ?? (Auth::user()->email ?? (session('otp_email') ?? request()->email)) }}">
            <input type="hidden" name="verification_flow" value="{{ $verificationFlow }}">

            <div>
                <x-input-label for="otp" :value="__('6-Digit Verification Code')" class="text-center" />
                
                <input id="otp" 
                    type="text" 
                    name="otp" 
                    x-model="otp"
                    @input="otp = otp.replace(/[^0-9]/g, '')"
                    inputmode="numeric"
                    maxlength="6" 
                    placeholder="000000"
                    required 
                    autofocus 
                    autocomplete="one-time-code"
                    :disabled="!canVerify"
                    class="auth-code-input block mt-1 w-full text-center text-3xl tracking-[0.75rem] font-mono font-bold border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150" 
                    :class="otp.length > 0 && otp.length < 6 ? 'border-orange-400 ring-1 ring-orange-400' : ''" 
                />

                {{-- Error Messages --}}
                @error('otp')
                    <div class="mt-2 text-red-600 text-sm text-center font-bold bg-red-50 p-2 rounded border border-red-200">
                        {{ $message }}
                        @if (str_contains($message, 'incorrect') && $otpLockoutSeconds <= 0 && $otpAttemptCount > 0)
                            <div class="mt-1 text-xs font-semibold text-red-500">
                                {{ __('Attempt') }} {{ $otpAttemptCount }}/{{ $otpAttemptMax }}
                            </div>
                        @endif
                    </div>
                @enderror
                
                @error('email')
                    <div class="mt-2 text-red-600 text-xs text-center">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mt-6">
                <x-primary-button 
                    class="w-full justify-center py-3 text-lg bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150 disabled:opacity-50 primary-cta"
                    x-bind:disabled="otp.length !== 6 || !canVerify">
                    {{ __('Verify Code') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 flex flex-col items-center justify-between gap-4">
            <form method="POST" action="{{ route('otp.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? (Auth::user()->email ?? (session('otp_email') ?? request()->email)) }}">
                <input type="hidden" name="verification_flow" value="{{ $verificationFlow }}">
                
                <button type="submit" 
                        x-show="canResend"
                        class="text-sm auth-link underline font-medium focus:outline-none transition duration-150 ease-in-out">
                    {{ __('Resend Code') }}
                </button>
                
                <div x-show="otpLockoutTimer <= 0 && resendTimer > 0" class="auth-countdown">
                    <span>{{ __('Resend available in') }}</span>
                    <strong><span x-text="formatSeconds(resendTimer)"></span></strong>
                </div>

            </form>

            <div class="flex items-center gap-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Back to Login') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-guest-layout>
