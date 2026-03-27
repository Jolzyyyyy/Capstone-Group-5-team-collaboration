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
        }

        .sidebar-link:hover {
            background-color: #F3F4F6;
            color: #111827;
        }

        .sidebar-link.active {
            background-color: #EFF6FF;
            color: var(--primary-blue);
        }

        /* Status Dot on the right side */
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
            margin-left: 8px; /* Nilipat sa kanan ng text */
        }
    </style>

    <div class="flex overflow-hidden">
        <aside class="fixed-sidebar shadow-sm">
            <div class="sidebar-header">
                <h2 class="text-xl font-black text-gray-900 tracking-tighter italic uppercase">PRINTIFY & CO.</h2>
                <div class="h-0.5 w-8 bg-blue-600 mx-auto mt-2"></div>
            </div>
            
            <nav class="flex-1 mt-4">
                <a href="{{ route('dashboard') }}" class="sidebar-link active">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Account Profile
                </a>
                <a href="{{ route('home') }}" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7-7-7M19 10v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Home
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    Notifications
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                    Settings
                </a>
            </nav>

            <div class="p-6 border-t border-gray-100 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sidebar-link text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-10V4a3 3 0 00-6 0v1" /></svg>
                        Logout Session
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content flex-1">
            <div class="max-w-5xl mx-auto space-y-8">
                
                <div class="mb-4">
                    <h3 class="text-5xl font-black text-gray-900 tracking-tight">Hello, Our Dear Customer {{ Auth::user()->name }}! 👋</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm border-l-4 border-l-blue-600">
                    <div class="status-container mb-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Account Status: <span class="text-green-500 normal-case ml-1">Online</span><span class="online-dot"></span>
                        </p>
                    </div>
                    <p class="text-lg font-black text-green-600 uppercase tracking-tighter mt-1">Verified Account</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Total Orders</p>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic">0</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Active Services</p>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic">0</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                        <h4 class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.3em]">Recent Transactions</h4>
                    </div>
                    <div class="px-8 py-16 text-center">
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.4em] italic">No transactions recorded yet.</p>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>