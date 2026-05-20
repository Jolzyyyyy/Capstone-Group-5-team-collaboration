<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Printify.co |
        @if(auth()->user()?->isDeveloper() || request()->is('p-co-2026/developer*'))
            Developer Portal
        @elseif(auth()->user()?->canAccessAdminPortal() || request()->is('p-co-2026/admin*'))
            Admin Portal
        @else
            Client Portal
        @endif
    </title>

    <!-- Dependencies -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        
        :root {
            --sidebar-wide: 250px; 
            --sidebar-mini: 80px;
            --primary-purple: #6366F1;
            --bg-light: #F8FAFC; 
            --text-main: #1E293B;
            --border-color: #E2E8F0;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            margin: 0; 
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .fixed-sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            background: #FFFFFF; border-right: 1px solid var(--border-color);
            z-index: 1000; display: flex; flex-direction: column;
            transition: var(--transition-smooth); overflow: hidden;
        }

        .sidebar-header {
            padding: 2rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 100px;
        }

        .sidebar-header h2 {
            font-weight: 900; font-style: italic; font-size: 1.15rem;
            letter-spacing: -0.05em; color: #1E293B; line-height: 1;
            margin: 0; white-space: nowrap;
        }
        .sub-logo { font-size: 9px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px; }

        .nav-list { flex: 1; margin-top: 1rem; }
        
        .sidebar-link {
            display: flex; align-items: center; 
            padding: 0.7rem 1.25rem; margin: 0.2rem 1rem;
            font-size: 10px; font-weight: 700; text-transform: uppercase; 
            letter-spacing: 0.05em; color: #64748B; text-decoration: none; 
            gap: 15px; border-radius: 8px; transition: all 0.2s ease;
        }

        .sidebar-link i, .sidebar-link svg { 
            width: 20px !important; 
            height: 20px !important; 
            min-width: 20px !important; 
            stroke-width: 2.5px; 
            opacity: 0.8; 
            flex-shrink: 0;
        }

        .sidebar-link:hover { color: var(--primary-purple); background: #F1F5F9; }
        .sidebar-link.active { color: var(--primary-purple); background: #EEF2FF; }

        /* --- HEADER --- */
        .main-header {
            height: 80px; position: fixed; top: 0; right: 0; z-index: 500;
            background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: flex-end;
            padding: 1.5rem 4rem 1.5rem 2rem;
            transition: var(--transition-smooth);
        }

        .header-right-actions { display: flex; align-items: center; gap: 1.2rem; }
        .icon-group { display: flex; align-items: center; gap: 0.3rem; color: #94A3B8; }

        .icon-btn { 
            position: relative; width: 38px; height: 38px; 
            display: flex; align-items: center; justify-content: center;
            color: #64748B; cursor: pointer; border-radius: 50%; border: none; background: none; transition: 0.3s;
        }
        .icon-btn i, .icon-btn svg { width: 20px; height: 20px; stroke-width: 2.5px; opacity: 0.8; }
        .icon-btn:hover { background: #F1F5F9; color: var(--primary-purple); }

        .toggle-btn-sidebar {
            background: transparent;
            border: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748B;
            flex-shrink: 0;
            padding: 0;
        }
        .toggle-btn-sidebar:hover { color: var(--primary-purple); }

        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 10px; height: 10px; background: #EF4444; 
            border-radius: 50%; border: 2px solid white; z-index: 10;
        }

        /* --- PROFILE SECTION --- */
        .profile-wrapper-right { 
            display: flex; align-items: center; gap: 1rem; 
            padding-left: 1.5rem; border-left: 1px solid var(--border-color);
            cursor: pointer; position: relative;
        }
        
        .avatar-circle {
            position: relative; width: 50px; height: 50px;
            background: var(--primary-purple); border-radius: 50% !important; 
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 900; font-size: 18px; flex-shrink: 0;
            overflow: hidden;
        }

        .status-dot {
            position: absolute; bottom: 2px; right: 2px;
            width: 12px; height: 12px; background: #22C55E; 
            border-radius: 50%; border: 2px solid white;
        }

        .user-text-box { display: flex; flex-direction: column; text-align: left; }
        .text-name-bold { font-size: 14px; font-weight: 900; color: #1E293B; text-transform: uppercase; line-height: 1.2; }
        .text-role-gray { font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; }

        /* --- DROPDOWN MENUS --- */
        .dropdown-panel {
            position: absolute; top: 60px; right: 0;
            background: white; border-radius: 12px; width: 260px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 1.5rem; border: 1px solid #F1F5F9; z-index: 600;
        }
        .dropdown-header { font-size: 11px; font-weight: 800; color: #94A3B8; text-transform: uppercase; margin-bottom: 1rem; display: block; }
        .dropdown-item { font-size: 12px; font-weight: 700; color: #1E293B; padding: 10px 0; border-bottom: 1px solid #F8FAFC; display: block; text-decoration: none; transition: 0.2s; }
        .dropdown-item:hover { color: var(--primary-purple); padding-left: 5px; }

        /* --- MAIN CONTENT AREA --- */
        .content-area { 
            transition: var(--transition-smooth); 
            min-height: 100vh;
        }

        /* Separate padding for customer only */
        .customer-padding { padding: 100px 4rem 4rem; }
        .developer-padding { padding: 100px 4rem 4rem; }

        .search-box-inner {
            background: #F1F5F9; border-radius: 8px; padding: 8px 12px;
            display: flex; align-items: center; gap: 8px;
        }
        .search-box-inner input {
            background: transparent; border: none; outline: none; font-size: 12px; width: 100%; color: var(--text-main);
        }
    </style>
</head>

<body x-data="{ 
    isCollapsed: false, 
    profileOpen: false, 
    notifOpen: false, 
    msgOpen: false,
    searchOpen: false
}">

    @if(!request()->is('p-co-2026/admin*') && !request()->is('p-co-2026/developer*'))
        <!-- SIDEBAR (Customer Only) -->
        <aside class="fixed-sidebar" :style="isCollapsed ? 'width: var(--sidebar-mini)' : 'width: var(--sidebar-wide)'">
            <div class="sidebar-header">
                <button @click="isCollapsed = !isCollapsed" class="toggle-btn-sidebar">
                    <i data-lucide="menu"></i>
                </button>

                <template x-if="!isCollapsed">
                    <div x-transition:enter.duration.300ms>
                        <h2>PRINTIFY & CO.</h2>
                        <p class="sub-logo">Client Portal</p>
                    </div>
                </template>
            </div>

            <nav class="nav-list">
                <a href="{{ route('customer.home') }}" class="sidebar-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">
                    <i data-lucide="home"></i>
                    <span x-show="!isCollapsed" x-transition>Home</span>
                </a>
                
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span x-show="!isCollapsed" x-transition>Dashboard</span>
                </a>
                
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i data-lucide="user"></i>
                    <span x-show="!isCollapsed" x-transition>My Profile</span>
                </a>
                
                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                    <i data-lucide="shopping-cart"></i>
                    <span x-show="!isCollapsed" x-transition>My Orders</span>
                </a>
                
                <a href="{{ route('security') }}" class="sidebar-link {{ request()->routeIs('security') ? 'active' : '' }}">
                    <i data-lucide="shield-check"></i>
                    <span x-show="!isCollapsed" x-transition>Security</span>
                </a>
                
                <a href="{{ route('notifications') }}" class="sidebar-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
                    <i data-lucide="bell"></i>
                    <span x-show="!isCollapsed" x-transition>Notification</span>
                </a>
                
                <a href="{{ route('help-center') }}" class="sidebar-link {{ request()->routeIs('help-center') ? 'active' : '' }}">
                    <i data-lucide="help-circle"></i>
                    <span x-show="!isCollapsed" x-transition>Help Center</span>
                </a>
                
                <a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span x-show="!isCollapsed" x-transition>Settings</span>
                </a>
            </nav>

            <div class="p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link text-red-500 bg-transparent border-none w-full text-left cursor-pointer m-0 p-[0.7rem_1.25rem]">
                        <i data-lucide="log-out"></i>
                        <span x-show="!isCollapsed" x-transition>Logout Session</span>
                    </button>
                </form>
            </div>
        </aside>
    @elseif(request()->is('p-co-2026/developer*'))
        <!-- SIDEBAR (Developer Only) -->
        <aside class="fixed-sidebar" :style="isCollapsed ? 'width: var(--sidebar-mini)' : 'width: var(--sidebar-wide)'">
            <div class="sidebar-header">
                <button @click="isCollapsed = !isCollapsed" class="toggle-btn-sidebar">
                    <i data-lucide="menu"></i>
                </button>

                <template x-if="!isCollapsed">
                    <div x-transition:enter.duration.300ms>
                        <h2>PRINTIFY & CO.</h2>
                        <p class="sub-logo">Developer Portal</p>
                    </div>
                </template>
            </div>

            <nav class="nav-list">
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i data-lucide="external-link"></i>
                    <span x-show="!isCollapsed" x-transition>Go to Home</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span x-show="!isCollapsed" x-transition>Dashboard</span>
                </a>

                <a href="{{ route('developer.admin-clients.index') }}" class="sidebar-link {{ request()->routeIs('developer.admin-clients.*') ? 'active' : '' }}">
                    <i data-lucide="shield-check"></i>
                    <span x-show="!isCollapsed" x-transition>Manage Admin Clients</span>
                </a>

                <a href="{{ route('developer.orders.index') }}" class="sidebar-link {{ request()->routeIs('developer.orders.*') ? 'active' : '' }}">
                    <i data-lucide="shopping-cart"></i>
                    <span x-show="!isCollapsed" x-transition>Orders</span>
                </a>

                <a href="{{ route('developer.services.index') }}" class="sidebar-link {{ request()->routeIs('developer.services.*') ? 'active' : '' }}">
                    <i data-lucide="package"></i>
                    <span x-show="!isCollapsed" x-transition>Services</span>
                </a>

                <a href="{{ route('developer.customers.index') }}" class="sidebar-link {{ request()->routeIs('developer.customers.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span x-show="!isCollapsed" x-transition>Customers</span>
                </a>

                <a href="{{ route('developer.reports.index') }}" class="sidebar-link {{ request()->routeIs('developer.reports.*') ? 'active' : '' }}">
                    <i data-lucide="file-text"></i>
                    <span x-show="!isCollapsed" x-transition>Reports</span>
                </a>

                <a href="{{ route('developer.analytics.index') }}" class="sidebar-link {{ request()->routeIs('developer.analytics.*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3"></i>
                    <span x-show="!isCollapsed" x-transition>Analytics</span>
                </a>

                <a href="{{ route('developer.settings.index') }}" class="sidebar-link {{ request()->routeIs('developer.settings.*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span x-show="!isCollapsed" x-transition>Settings</span>
                </a>
            </nav>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link text-red-500 bg-transparent border-none w-full text-left cursor-pointer m-0 p-[0.7rem_1.25rem]">
                        <i data-lucide="log-out"></i>
                        <span x-show="!isCollapsed" x-transition>Logout Session</span>
                    </button>
                </form>
            </div>
        </aside>
    @endif

    <!-- MAIN AREA -->
    <div class="content-area {{ request()->is('p-co-2026/developer*') ? 'developer-padding' : ((!request()->is('p-co-2026/admin*') && !request()->is('p-co-2026/developer*')) ? 'customer-padding' : '') }}" 
         :style="(!window.location.pathname.includes('/admin') || window.location.pathname.includes('/developer')) ? (isCollapsed ? 'margin-left: var(--sidebar-mini)' : 'margin-left: var(--sidebar-wide)') : 'margin-left: 0'">
        
        @if(!request()->is('p-co-2026/admin*') && !request()->is('p-co-2026/developer*'))
            <!-- HEADER (Customer Only) -->
            <header class="main-header" :style="isCollapsed ? 'width: calc(100% - var(--sidebar-mini))' : 'width: calc(100% - var(--sidebar-wide))'">
                <div class="header-right-actions">
                    <div class="icon-group">
                        <div class="relative">
                            <button class="icon-btn" @click="searchOpen = !searchOpen; notifOpen = false; msgOpen = false; profileOpen = false;">
                                <i data-lucide="search"></i>
                            </button>
                            <div x-show="searchOpen" @click.away="searchOpen = false" class="dropdown-panel" x-transition>
                                <span class="dropdown-header">Quick Search</span>
                                <div class="search-box-inner">
                                    <i data-lucide="search" style="width:14px; color:#94A3B8;"></i>
                                    <input type="text" placeholder="Type to search...">
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <button class="icon-btn" @click="notifOpen = !notifOpen; msgOpen = false; searchOpen = false; profileOpen = false;">
                                <span class="notif-dot"></span>
                                <i data-lucide="bell"></i>
                            </button>
                            <div x-show="notifOpen" @click.away="notifOpen = false" class="dropdown-panel" x-transition>
                                <span class="dropdown-header">Recent Notifications</span>
                                <a href="#" class="dropdown-item">Your order #1234 is now being processed.</a>
                                <a href="#" class="dropdown-item">A new promo is available for you!</a>
                                <hr class="border-slate-50 my-2">
                                <a href="{{ route('notifications') }}" class="dropdown-item text-center text-primary-purple">View All</a>
                            </div>
                        </div>

                        <div class="relative">
                            <button class="icon-btn" @click="msgOpen = !msgOpen; notifOpen = false; searchOpen = false; profileOpen = false;">
                                <span class="notif-dot"></span>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px; height:20px;">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-width="2.5"></path>
                                    <line x1="8" y1="9" x2="16" y2="9" stroke-linecap="round" stroke-width="2.5"></line>
                                    <line x1="8" y1="13" x2="14" y2="13" stroke-linecap="round" stroke-width="2.5"></line>
                                </svg>
                            </button>
                            <div x-show="msgOpen" @click.away="msgOpen = false" class="dropdown-panel" x-transition>
                                <span class="dropdown-header">Messages</span>
                                <a href="#" class="dropdown-item">Admin: Hello! How can we help?</a>
                                <a href="#" class="dropdown-item">Support: Payment confirmed.</a>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="profile-wrapper-right" @click="profileOpen = !profileOpen; notifOpen = false; msgOpen = false; searchOpen = false;">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                <div class="status-dot"></div>
                            </div>
                            <div class="user-text-box">
                                <span class="text-name-bold">{{ Auth::user()->name }}</span>
                                <span class="text-role-gray">Customer</span>
                            </div>
                        </div>

                        <div x-show="profileOpen" @click.away="profileOpen = false" class="dropdown-panel w-[200px]" x-transition>
                            <span class="dropdown-header">Account Settings</span>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">My Profile</a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">Order History</a>
                            <a href="{{ route('security') }}" class="dropdown-item">Security Settings</a>
                            <hr class="border-slate-50 my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left bg-transparent border-none p-0 font-bold text-red-500 text-[12px] cursor-pointer">
                                    Sign Out Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
        @elseif(request()->is('p-co-2026/developer*'))
            <!-- HEADER (Developer Only) -->
            <header class="main-header" :style="isCollapsed ? 'width: calc(100% - var(--sidebar-mini))' : 'width: calc(100% - var(--sidebar-wide))'">
                <div class="header-right-actions">
                    <div class="icon-group">
                        <button class="icon-btn">
                            <span class="notif-dot"></span>
                            <i data-lucide="bell"></i>
                        </button>
                        <button class="icon-btn">
                            <i data-lucide="message-square"></i>
                        </button>
                    </div>

                    <div class="profile-wrapper-right">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            <div class="status-dot"></div>
                        </div>
                        <div class="user-text-box">
                            <span class="text-name-bold">{{ Auth::user()->name }}</span>
                            <span class="text-role-gray">Developer</span>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>