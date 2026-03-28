<x-app-layout>
    <style>
        :root {
            --sidebar-width: 250px;
            --bg-light: #F9FAFB;
            --primary-blue: #1D4ED8;
            --success-green: #10B981;
        }

        /* Fixed Sidebar */
        .fixed-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #FFFFFF;
            border-right: 1px solid #E5E7EB;
            z-index: 50;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 3.5rem 1.5rem 2.5rem 1.5rem;
            text-align: center;
        }

        /* Main Content Spacing */
        .main-content {
            margin-left: var(--sidebar-width);
            background-color: var(--bg-light);
            min-height: 100vh;
            padding: 4rem 4.5rem; 
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.25rem;
            margin: 0.3rem 1rem;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .sidebar-link:hover {
            background-color: #F3F4F6;
            color: #111827;
        }

        .sidebar-link.active {
            background-color: #EFF6FF;
            color: var(--primary-blue);
        }

        .status-container {
            display: flex;
            align-items: center;
        }

        .online-dot {
            height: 8px;
            width: 8px;
            background-color: var(--success-green);
            border-radius: 50%;
            display: inline-block;
            margin-left: 8px;
        }
    </style>

    <div class="flex overflow-hidden">
        <aside class="fixed-sidebar shadow-sm">
            <div class="sidebar-header">
                <h2 class="text-xl font-black text-gray-900 tracking-tighter italic uppercase">PRINTIFY & CO.</h2>
                <div class="h-0.5 w-8 bg-blue-600 mx-auto mt-2"></div>
                <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em] font-bold mt-2">Admin Portal</p>
            </div>
            
            <nav class="flex-1 mt-4 overflow-y-auto">
                <a href="{{ route('home') }}" class="sidebar-link hover:bg-blue-50 hover:text-blue-700 border border-transparent hover:border-blue-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Go to Home
                </a>

                <div class="my-4 border-t border-gray-100 mx-4"></div>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-9.87 1.57l1.51-1.5a1.13 1.13 0 011.59 0l3.01 3a1.13 1.13 0 010 1.6l-3.01 3.01a1.13 1.13 0 01-1.59 0l-1.51-1.51"/></svg>
                    Orders
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Products
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/></svg>
                    Rates
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Customer Records
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reports
                </a>

                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
            </nav>

            <div class="p-6 border-t border-gray-100 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sidebar-link text-red-500 hover:bg-red-50 transition-colors uppercase border-none bg-transparent cursor-pointer text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout Session
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content flex-1">
            <div class="max-w-6xl mx-auto space-y-10">
                
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-green-800 uppercase tracking-tighter italic">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <h3 class="text-5xl font-black text-gray-900 tracking-tighter italic">Hello, Administrator {{ Auth::user()->name }}! 🛡️</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm border-l-4 border-l-blue-600">
                    <div class="status-container mb-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            System Status: <span class="text-green-500 normal-case ml-1 font-black uppercase italic">Online & Secured</span><span class="online-dot"></span>
                        </p>
                    </div>
                    <p class="text-lg font-black text-blue-600 uppercase tracking-tighter mt-1">Verified System Controller</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Daily Revenue</p>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic">₱ 0.00</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Active Orders</p>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic">0</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Total Customers</p>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic">0</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <h4 class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.3em]">Recent Business Transactions</h4>
                    </div>
                    <div class="px-8 py-20 text-center">
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.4em] italic">No business records to display.</p>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>