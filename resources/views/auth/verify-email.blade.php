<x-guest-layout>
    <div class="mb-4 text-xs text-slate-500 text-center font-bold uppercase tracking-widest">
        {{ __('Account Verification') }}
    </div>

    <div class="mb-6 text-sm text-gray-600 text-center leading-relaxed">
        {{ __('Thanks for signing up! Please verify your email address by entering the 6-digit code we just emailed to you.') }}
    </div>

    {{-- Status Message para sa bagong pinadalang code --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-3 bg-green-50 border border-green-100 rounded-lg font-medium text-sm text-green-600 text-center">
            {{ __('A new verification code has been sent to your email address.') }}
        </div>
    @endif

    {{-- Ang action ay naka-point na sa standard verification.verify route --}}
    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf
        
        <div class="space-y-4">
            <x-input-label for="otp" :value="__('Verification Code')" class="text-center text-xs text-slate-500" />
            
            <div class="relative">
                <x-text-input id="otp" 
                    class="block mt-1 w-full text-center text-3xl tracking-[0.75em] font-black py-4 border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" 
                    type="text" 
                    name="otp" 
                    maxlength="6" 
                    pattern="[0-9]*"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    required 
                    autofocus 
                    placeholder="000000" />
                
                <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center font-bold" />
            </div>
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center py-4 bg-slate-900 text-xs font-black tracking-widest uppercase hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                {{ __('Verify My Account') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
        {{-- Resend Code Route --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 focus:outline-none transition-colors">
                {{ __('Resend Code') }}
            </button>
        </form>

        {{-- Logout Route --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>