<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- SECTION HEADER -->
            <div class="mb-8 px-4 sm:px-0">
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Notifications</h2>
                <p class="text-sm text-slate-500 font-bold">Stay updated with your order progress.</p>
            </div>

            <!-- NOTIFICATIONS CARD -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Recent Alerts</h3>
                    </div>
                    <button class="text-[10px] font-black text-indigo-600 uppercase hover:underline tracking-widest transition-all">
                        Mark all as read
                    </button>
                </div>

                <div class="divide-y divide-slate-50">
                    <!-- Sample Notification -->
                    <div class="p-6 flex items-start gap-5 hover:bg-slate-50/50 transition-colors cursor-pointer group">
                        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-sm font-black text-slate-800 uppercase">Order Completed</p>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">2 mins ago</span>
                            </div>
                            <p class="text-xs text-slate-500 font-bold leading-relaxed">
                                Your order <span class="text-indigo-600 font-black">#ORD-55201</span> is ready for pickup or delivery. Thank you for choosing us!
                            </p>
                        </div>
                    </div>

                    <!-- Placeholder for empty state -->
                    @if(isset($notifications) && $notifications->count() === 0)
                    <div class="py-20 text-center">
                        <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="far fa-bell-slash text-2xl text-slate-200"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 uppercase">All caught up!</h3>
                        <p class="text-xs text-slate-400 font-bold mt-1">No new notifications at the moment.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>