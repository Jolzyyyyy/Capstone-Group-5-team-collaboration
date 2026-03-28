<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-[0.2em]">Security Verification</h2>
    </div>

    <div class="mb-6 text-sm text-gray-600 text-center px-4">
        {{ __('Pakisulat ang 6-digit security code na ipinadala namin sa iyong Gmail') }}
    </div>

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
        <form method="POST" action="{{ route('admin.otp.submit') }}">
            @csrf

            <input type="hidden" name="email" value="{{ session('admin_email') }}">

            <div class="mb-6">
                <x-input-label for="otp" :value="__('6-Digit Verification Code')" class="text-center mb-2" />
                
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
                    class="block w-full text-center text-3xl tracking-[0.75rem] font-mono font-bold border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150" 
                    :class="otp.length > 0 && otp.length < 6 ? 'border-orange-400 ring-1 ring-orange-400' : ''" 
                />

                @error('otp')
                    <div class="mt-2 text-red-600 text-sm text-center font-bold bg-red-50 p-2 rounded border border-red-200">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mt-6">
                <x-primary-button 
                    class="w-full justify-center py-3 bg-gray-800 hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150 disabled:opacity-50">
                    {{ __('VERIFY CODE') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-8 flex flex-col items-center gap-4">
            <form method="POST" action="{{ route('admin.otp.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('admin_email') }}">
                
                <button type="submit" 
                        x-show="canResend"
                        class="text-sm text-indigo-600 hover:text-indigo-900 underline font-medium">
                    {{ __('Resend Code') }}
                </button>
                
                <span x-show="!canResend" class="text-sm text-gray-500 font-medium italic">
                    {{ __('Maaaring mag-resend sa loob ng ') }} 
                    <span class="text-indigo-600 font-bold"><span x-text="timer"></span>s</span>
                </span>
            </form>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="underline text-xs text-gray-500 hover:text-gray-800 uppercase tracking-widest">
                    {{ __('Cancel & Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>