<div x-data="{ securityTab: 'password' }" class="w-full">
    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        <div class="w-full md:w-80 flex-shrink-0">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="px-4 py-2 text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Security & Privacy</h3>
                
                <nav class="flex flex-col gap-1 text-slate-500">
                    <button @click="securityTab = 'password'" 
                        :class="securityTab === 'password' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all">
                        <i class="fa-solid fa-key w-4"></i> Change Password
                    </button>

                    <button @click="securityTab = '2fa'" 
                        :class="securityTab === '2fa' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-shield-halved w-4"></i> Two-Factor Auth (OTP)
                    </button>

                    <button @click="securityTab = 'activity'" 
                        :class="securityTab === 'activity' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-clock-rotate-left w-4"></i> Login Activity
                    </button>

                    <button @click="securityTab = 'logout-all'" 
                        :class="securityTab === 'logout-all' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-right-from-bracket w-4"></i> Logout All Devices
                    </button>

                    <button @click="securityTab = 'payments'" 
                        :class="securityTab === 'payments' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-credit-card w-4"></i> Saved Payment Methods
                    </button>

                    <button @click="securityTab = 'privacy'" 
                        :class="securityTab === 'privacy' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-user-lock w-4"></i> Privacy Settings
                    </button>

                    <button @click="securityTab = 'download'" 
                        :class="securityTab === 'download' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[10px] font-black uppercase w-full transition-all text-left">
                        <i class="fa-solid fa-download w-4"></i> Data Download Request
                    </button>
                </nav>
            </div>
        </div>

        <div class="flex-1 w-full min-h-[500px]">
            
            <div x-show="securityTab === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-6">Change Password</h2>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div x-show="securityTab === '2fa'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-2">Two-Factor Authentication</h2>
                    <p class="text-sm text-slate-500 mb-6 font-bold">Add extra security using OTP (SMS/Email)</p>
                    <button class="bg-indigo-600 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg shadow-indigo-200">Enable / Disable OTP</button>
                </div>
            </div>

            <div x-show="securityTab === 'activity'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-4">Login Activity</h2>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-[10px] font-black text-slate-400 uppercase">Recent devices and login history will appear here.</p>
                    </div>
                </div>
            </div>

            <div x-show="securityTab === 'logout-all'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-red-500 uppercase tracking-tighter mb-4">Logout All Devices</h2>
                    <p class="text-sm text-slate-500 mb-6">This will sign you out of all other active sessions except this one.</p>
                    <button class="bg-red-500 text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg shadow-red-200">Confirm Logout All</button>
                </div>
            </div>

            <div x-show="securityTab === 'payments'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-4">Saved Payment Methods</h2>
                    <p class="text-sm text-slate-500 italic">No saved cards found.</p>
                </div>
            </div>

            <div x-show="securityTab === 'privacy'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-4">Privacy Settings</h2>
                    <p class="text-sm text-slate-500 font-bold uppercase text-[10px]">Manage who can see your profile information.</p>
                </div>
            </div>

            <div x-show="securityTab === 'download'" x-cloak x-transition>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-4">Data Download Request</h2>
                    <p class="text-sm text-slate-500 mb-6">Download a copy of your account data for your records.</p>
                    <button class="border-2 border-slate-200 text-slate-600 px-8 py-3 rounded-2xl text-[10px] font-black uppercase hover:bg-slate-50">Request Data Export</button>
                </div>
            </div>

        </div>
    </div>
</div>