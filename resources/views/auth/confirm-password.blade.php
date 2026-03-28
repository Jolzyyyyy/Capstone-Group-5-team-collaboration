<x-guest-layout>
    <div class="mb-4 text-xs text-slate-500 text-center font-bold uppercase tracking-widest">
        {{ __('Confirm Security Access') }}
    </div>

    <div class="mb-6 text-sm text-slate-600 text-center bg-slate-50 p-4 rounded-xl border border-slate-100 italic leading-relaxed shadow-inner">
        {{ __('This is a secure area. Please confirm your password before continuing to the next section.') }}
    </div>

    <div x-data="{ showPass: false }">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mt-4">
                <x-input-label for="password" :value="__('Current Password')" class="text-xs text-slate-500" />
                
                <div class="relative flex items-center mt-1">
                    <input id="password" 
                        class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm pl-4 pr-12 py-3 transition-all" 
                        :type="showPass ? 'text' : 'password'" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        autofocus />
                    
                    <button type="button" @click="showPass = !showPass" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10;"
                            class="focus:outline-none border-none bg-transparent p-0 cursor-pointer text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!showPass" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M9.172 9.172L15 15M3 3l3.59 3.59m0 0A9.919 9.919 0 0112 5c4.478 0 8.268-2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            <g x-show="showPass">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </g>
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <x-primary-button class="w-full justify-center py-4 bg-slate-900 text-xs font-black tracking-widest uppercase hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                    {{ __('Confirm Identity') }}
                </x-primary-button>

                <a href="{{ route('dashboard') }}" class="text-center text-xs text-slate-400 hover:text-slate-600 underline transition-colors">
                    {{ __('Nevermind, take me back') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>