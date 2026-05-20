<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- SECTION HEADER -->
            <div class="mb-8 px-4 sm:px-0">
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Settings</h2>
                <p class="text-sm text-slate-500 font-bold">Manage your account preferences and portal experience.</p>
            </div>

            <!-- GENERAL SETTINGS CARD -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <h3 class="text-xl font-black text-slate-800 mb-8 uppercase tracking-tighter">General Settings</h3>
                
                <div class="space-y-8">
                    <!-- Setting Item: Emails -->
                    <div class="flex items-center justify-between group">
                        <div>
                            <p class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">Order Status Emails</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Receive real-time updates via your registered email address.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                        </label>
                    </div>

                    <!-- Setting Item: Language -->
                    <div class="flex items-center justify-between border-t border-slate-50 pt-8 group">
                        <div>
                            <p class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">Interface Language</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Choose your preferred language for the portal.</p>
                        </div>
                        <div class="relative">
                            <select class="bg-slate-50 border-none rounded-xl text-[10px] font-black uppercase px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none pr-8">
                                <option>English (US)</option>
                                <option>Tagalog</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-[8px]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Setting Item: Danger Zone -->
                    <div class="flex items-center justify-between border-t border-slate-50 pt-8 group">
                        <div>
                            <p class="text-sm font-black text-red-600 uppercase tracking-tight group-hover:scale-105 origin-left transition-transform">Danger Zone</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Once you deactivate, your order history will be hidden.</p>
                        </div>
                        <button class="bg-red-50 text-red-600 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-200">
                            Deactivate
                        </button>
                    </div>
                </div>
            </div>

            <!-- FOOTER INFO -->
            <div class="mt-8 text-center">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Printify & Co. Client Portal v1.0</p>
            </div>

        </div>
    </div>
</x-app-layout>