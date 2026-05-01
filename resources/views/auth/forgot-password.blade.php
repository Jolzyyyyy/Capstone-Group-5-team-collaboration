<x-guest-layout :showcase="[
    'kicker' => __('Reset Access'),
    'title_intro' => __('Recover your account'),
    'title_focus' => __('without losing momentum.'),
    'text' => __('Request a secure verification code, confirm your identity, and move directly into a guided password reset flow.'),
    'chips' => [
        __('Password recovery with time-limited OTP protection'),
        __('Clear next-step guidance from email to reset form'),
        __('Built to get customers back into their account safely'),
    ],
    'metric_value' => __('Recover'),
    'metric_text' => __('Made for users who need a safe path back into their account after forgetting their password.'),
]">
    <div class="mb-7 text-center">
        <p class="auth-eyebrow">{{ __('Reset Access') }}</p>
        <h2 class="auth-title">{{ __('Forgot Password?') }}</h2>
        <p class="auth-subtitle">{{ __('Enter your registered email and we will send a verification code so you can securely reset your password.') }}</p>
    </div>

    <div class="auth-note">
        {{ __('For security, the code is time-limited and can only be used once. If the first code expires, request a new one from the verification page.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->has('email'))
        <div class="auth-feedback auth-feedback--error mb-5" role="alert">
            <strong>{{ __('We could not continue to verification.') }}</strong>
            <span>{{ __('Make sure you enter the same email address that was used when the account was registered.') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- EMAIL FIELD: Same width (w-full) as the Login Email field --}}
        <div>
            <x-input-label for="email" :value="__('Account Email')" />
            <x-text-input id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" />
            
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Button Section: Justify-end para sa kanan din ang button katulad sa Admin --}}
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3 primary-cta">
                {{ __('Send Verification Code') }}
            </x-primary-button>
        </div>

        {{-- Back to Login Link --}}
        <div class="mt-8 text-center">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none auth-link" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
