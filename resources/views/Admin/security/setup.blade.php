<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-[calc(100vh-64px)] flex items-center justify-center p-4">
        
        <div class="bg-white shadow-xl rounded-[1.5rem] border border-gray-100 overflow-hidden" 
             style="width: 100%; max-width: 400px; margin: 0 auto;">
            
            <div class="p-8 flex flex-col items-center">
                
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-3">
                        <div class="p-2 bg-indigo-50 rounded-xl">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Authentication App</h2>
                    <p style="font-size: 12px !important;" class="text-gray-500 uppercase tracking-widest mt-1">Setup your security</p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <img src="{{ $qrCodeUrl }}" alt="QR" class="w-28 h-28 mb-4">
                    
                    <p style="font-size: 13px !important; font-weight: bold !important; color: #9ca3af !important; text-transform: uppercase !important; letter-spacing: 0.1em; margin-bottom: 2px;">
                        Manual Key
                    </p>
                    
                    <div x-data="{ copied: false }" class="mb-0 leading-none">
                        <button type="button" 
                            @click="navigator.clipboard.writeText('{{ $secretKey ?? '' }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            style="font-size: 16px !important; color: #4f46e5 !important; font-family: monospace !important; font-weight: bold !important; letter-spacing: 0.1em;"
                            class="hover:underline relative bg-transparent border-none cursor-pointer p-0">
                            {{ $secretKey ?? 'ABCD EFGH IJKL' }}
                            
                            <template x-if="copied">
                                <div class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-black text-white text-[10px] py-1 px-2 rounded shadow-lg whitespace-nowrap">
                                    Copied!
                                </div>
                            </template>
                        </button>
                    </div>
                </div>

                <div class="w-full mb-6 pt-0 mt-4"> 
                    <ul style="font-size: 14px !important; color: #4b5563 !important;" class="space-y-3 font-medium px-4">
                        <li class="flex items-start gap-2">
                            <span class="text-indigo-600 font-bold">1.</span> Open your Authenticator app
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-indigo-600 font-bold">2.</span> Scan the QR or enter manual key
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-indigo-600 font-bold">3.</span> Enter the 6-digit code below
                        </li>
                    </ul>
                </div>

                {{-- ERROR MESSAGE --}}
                @if ($errors->has('one_time_password'))
                    <div class="mb-4 text-red-500 text-[11px] font-bold uppercase tracking-tight text-center">
                        {{ $errors->first('one_time_password') }}
                    </div>
                @endif

                <form action="{{ route('admin.security.2fa.activate') }}" method="POST" class="w-full flex flex-col items-center">
                    @csrf
                    
                    <div class="text-center mb-6 w-full px-4"> 
                        <label style="font-size: 13px !important; font-weight: bold !important; color: #9ca3af !important; text-transform: uppercase !important; display: block; margin-bottom: 8px;">
                            Verification Code
                        </label>
                        
                        <input type="text" 
                               name="one_time_password" 
                               id="2fa_input"
                               maxlength="6"
                               placeholder="000000"
                               autocomplete="off"
                               style="height: 45px; max-width: 250px; margin: 0 auto; font-size: 22px !important;" 
                               class="block w-full text-center font-mono font-bold border-gray-200 rounded-lg bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               required 
                               autofocus />
                    </div>

                    <div class="w-full px-4">
                        <button type="submit" 
                                 style="background-color: #1e293b; color: #ffffff; width: 100%; max-width: 250px; height: 45px; margin: 0 auto; display: block; border-radius: 10px; font-weight: bold; font-size: 13px !important; text-transform: uppercase; letter-spacing: 0.1em; border: none; cursor: pointer; transition: all 0.2s;"
                                 onmouseover="this.style.backgroundColor='#334155'" 
                                 onmouseout="this.style.backgroundColor='#1e293b'">
                            Verify and Activate
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50/50 py-4 text-center border-t border-gray-100">
                <p style="font-size: 11px !important;" class="font-bold text-gray-400 uppercase tracking-widest">
                    Security Required
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('2fa_input');
            if(otpInput) {
                otpInput.addEventListener('input', function (e) {
                    // Manatiling numbers lang ang tinatanggap
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</x-app-layout>