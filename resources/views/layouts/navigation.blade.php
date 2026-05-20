@php
    $currentUser = Auth::user();
    $isPortalUser = $currentUser && $currentUser->canAccessAdminPortal();
    $dashboardHref = $isPortalUser ? route('admin.dashboard') : route('dashboard');
    $dashboardActive = $isPortalUser ? request()->routeIs('admin.dashboard') : request()->routeIs('dashboard');
    $logoutHref = $isPortalUser ? route('admin.logout') : route('logout');
    $profileHref = $currentUser && $currentUser->isAdminClient()
        ? route('admin.admin-client-profile.edit')
        : route('profile.edit');

    $navLinks = $isPortalUser
        ? [
            ['label' => __('Dashboard'), 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => __('Orders'), 'href' => route('admin.orders.index'), 'active' => request()->routeIs('admin.orders.*')],
            ['label' => __('Customers'), 'href' => route('admin.customers.index'), 'active' => request()->routeIs('admin.customers.*')],
            ['label' => __('Services'), 'href' => route('admin.services.index'), 'active' => request()->routeIs('admin.services.*')],
            ['label' => __('Reports'), 'href' => route('admin.reports.index'), 'active' => request()->routeIs('admin.reports.*')],
            ['label' => __('Help'), 'href' => route('admin.help.index'), 'active' => request()->routeIs('admin.help.*')],
        ]
        : [
            ['label' => __('Dashboard'), 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => __('Services'), 'href' => route('services.index'), 'active' => request()->routeIs('services.*')],
            ['label' => __('My Orders'), 'href' => route('orders.my.index'), 'active' => request()->routeIs('orders.my.*')],
            ['label' => __('Cart'), 'href' => route('cart.index'), 'active' => request()->routeIs('cart.*')],
            ['label' => __('Updates'), 'href' => route('customer.notifications'), 'active' => request()->routeIs('customer.notifications')],
            ['label' => __('Help'), 'href' => route('customer.help'), 'active' => request()->routeIs('customer.help')],
        ];

    if ($currentUser && $currentUser->canManageAdminClients()) {
        $navLinks[] = [
            'label' => __('Admin Clients'),
            'href' => route('developer.admin-clients.index'),
            'active' => request()->routeIs('developer.admin-clients.*'),
        ];
    }

    if ($currentUser && $currentUser->isAdminClient()) {
        $navLinks[] = [
            'label' => __('Reference Profile'),
            'href' => route('admin.admin-client-profile.edit'),
            'active' => request()->routeIs('admin.admin-client-profile.*'),
        ];
    }
@endphp

<nav x-data="{ open: false }" class="border-b border-white/10 bg-[#1f1d1c] shadow-lg shadow-black/15">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $dashboardHref }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @foreach ($navLinks as $link)
                        <x-nav-link :href="$link['href']" :active="$link['active']">
                            {{ $link['label'] }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold leading-4 text-white/85 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                    {{-- 
<x-dropdown-link :href="route('profile.edit')">
    {{ __('Profile') }}
</x-dropdown-link> 
--}}

                        <!-- Authentication -->
                        @if (!$isPortalUser || $currentUser->isAdminClient())
                            <x-dropdown-link :href="$profileHref">
                                {{ $isPortalUser ? __('Reference Profile') : __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ $logoutHref }}">
                            @csrf

                            <x-dropdown-link :href="$logoutHref"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-white/70 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($navLinks as $link)
                <x-responsive-nav-link :href="$link['href']" :active="$link['active']">
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/10 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-white/60">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if (!$isPortalUser || $currentUser->isAdminClient())
                    <x-responsive-nav-link :href="$profileHref">
                        {{ $isPortalUser ? __('Reference Profile') : __('Profile') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ $logoutHref }}">
                    @csrf

                    <x-responsive-nav-link :href="$logoutHref"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
