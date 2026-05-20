<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- HEADER NG SECTION (Base sa design mo sa SS2-3) -->
            <div class="mb-8">
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Security Settings</h2>
                <p class="text-sm text-slate-500 font-bold">Update your password and secure your account.</p>
            </div>

            <!-- ANG IYONG SECURITY FORM -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">Current Password</label>
                            <input type="password" name="current_password" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">New Password EYRA</label>
                            <input type="password" name="password" class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <!-- 2FA SECTION -->
                    <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100 flex items-center justify-between mt-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-indigo-900 uppercase">Two-Factor Authentication</p>
                                <p class="text-[10px] text-indigo-700 font-bold">Add an extra layer of security to your account.</p>
                            </div>
                        </div>
                        <button type="button" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                            Enable
                        </button>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-slate-200">
                            Save Security Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>