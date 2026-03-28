<x-guest-layout>
    {{-- Header Section: Pantay ang font size at tracking sa Admin Login --}}
    <div class="mb-4 text-center">
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">
            {{ __('Reset Your Password') }}
        </h2>
    </div>

    {{-- Instruction Text: Centered at malinis ang font --}}
    <div class="mb-6 text-sm text-gray-600 text-center leading-relaxed">
        {{ __('Enter your email account and we will send a 6-digit verification code to reset your password.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

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
            <x-primary-button class="ms-3">
                {{ __('Send Verification Code') }}
            </x-primary-button>
        </div>

        {{-- Back to Login Link --}}
        <div class="mt-8 text-center">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>