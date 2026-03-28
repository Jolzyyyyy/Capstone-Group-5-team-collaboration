<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 text-center font-semibold uppercase tracking-widest">
        {{ __('Security Verification') }}
    </div>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('Pakisulat ang 6-digit security code na ipinadala namin sa iyong Gmail.') }}
    </div>

    {{-- Status Message --}}
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 text-center bg-green-50 p-2 rounded border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <div x-data="{ 
        otp: '', 
        timer: 60, 
        canResend: false,
        init() {
            let interval = setInterval(() => {
                if(this.timer > 0) this.timer--;
                else { this.canResend = true; clearInterval(interval); }
            }, 1000);
        }
    }">
        {{-- FIX: In-align ang route name sa 'customer.otp.submit' base sa web.php --}}
        <form method="POST" action="{{ route('customer.otp.submit') }}">
            @csrf

            {{-- Hidden email field: Kinukuha ang email sa pinaka-available na source --}}
            <input type="hidden" name="email" value="{{ $email ?? (Auth::user()->email ?? (session('otp_email') ?? request()->email)) }}">

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
                    class="block mt-1 w-full text-center text-3xl tracking-[0.75rem] font-mono font-bold border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150" 
                    :class="otp.length > 0 && otp.length < 6 ? 'border-orange-400 ring-1 ring-orange-400' : ''" 
                />

                {{-- Error Messages --}}
                @error('otp')
                    <div class="mt-2 text-red-600 text-sm text-center font-bold bg-red-50 p-2 rounded border border-red-200">
                        {{ $message }}
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
                    class="w-full justify-center py-3 text-lg bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150 disabled:opacity-50"
                    x-bind:disabled="otp.length !== 6">
                    {{ __('Verify Code') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6 flex flex-col items-center justify-between gap-4">
            {{-- FIX: In-align ang route name sa 'customer.otp.resend' --}}
            <form method="POST" action="{{ route('customer.otp.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? (Auth::user()->email ?? (session('otp_email') ?? request()->email)) }}">
                
                <button type="submit" 
                        x-show="canResend"
                        class="text-sm text-blue-600 hover:text-blue-900 underline font-medium focus:outline-none transition duration-150 ease-in-out">
                    {{ __('Resend Code') }}
                </button>
                
                <div x-show="!canResend" class="text-sm text-gray-500 font-medium italic">
                    {{ __('Maaaring mag-resend sa loob ng ') }} <span x-text="timer" class="font-bold text-indigo-600"></span>s
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