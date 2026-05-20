<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- SECTION HEADER -->
            <div class="mb-8 px-4 sm:px-0">
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Help Center</h2>
                <p class="text-sm text-slate-500 font-bold">Get support and find answers to your questions.</p>
            </div>

            <!-- HELP CENTER CONTENT -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- FAQ CARD -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:border-indigo-200 transition-all group">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-question-circle text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Common Questions</h4>
                        <p class="text-xs text-slate-500 font-bold mt-2 leading-relaxed">
                            Find quick answers about pricing, bulk orders, and shipping timelines.
                        </p>
                        <button class="mt-6 text-[10px] font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
                            Browse FAQ <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <!-- SUPPORT TICKET CARD -->
                    <div class="bg-slate-900 p-8 rounded-3xl shadow-xl shadow-slate-200 text-white relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:rotate-12 transition-transform">
                                <i class="fas fa-headset text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-black uppercase tracking-tighter">Need Direct Help?</h4>
                            <p class="text-xs text-slate-400 font-bold mt-2 leading-relaxed">
                                Our support team is available Monday to Friday, 9AM - 6PM.
                            </p>
                            <button class="mt-6 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-900/20">
                                Start a Ticket
                            </button>
                        </div>
                        <!-- Background Icon Decoration -->
                        <i class="fas fa-comments absolute -bottom-10 -right-10 text-[150px] text-slate-800 opacity-20 rotate-12 pointer-events-none"></i>
                    </div>

                </div>

                <!-- ADDITIONAL HELP INFO -->
                <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100 flex items-center justify-center">
                    <p class="text-[10px] font-black text-indigo-900 uppercase tracking-widest">
                        Average response time: <span class="text-indigo-600 ml-1">Under 2 hours</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>