<x-app-layout>
    @php
        $user = Auth::user();
        $isDeveloper = $user->isDeveloper();
        $isAdminClient = $user->isAdminClient();
        $profile = $profile ?? ($isAdminClient ? $user->adminClientProfile : null);
        $stats = $stats ?? [];
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
        $heroImage = $isDeveloper ? asset('images/Homesld2.jpg') : asset('images/Homesld3.jpg');

        $primaryStats = [
            ['label' => 'Sales', 'value' => $money($stats['sales_total'] ?? 0), 'note' => 'Total recorded order value'],
            ['label' => 'This Month', 'value' => $money($stats['sales_this_month'] ?? 0), 'note' => ($stats['orders_this_month'] ?? 0) . ' orders this month'],
            ['label' => 'Active Orders', 'value' => $stats['active_orders'] ?? 0, 'note' => 'Pending through ready'],
            ['label' => $isDeveloper ? 'Customer Accounts' : 'Assigned Customers', 'value' => $stats['customers'] ?? 0, 'note' => $isDeveloper ? 'All registered customers' : 'Customers assigned to this account'],
            ['label' => 'Service Catalog', 'value' => $stats['active_services'] ?? 0, 'note' => ($stats['inactive_services'] ?? 0) . ' inactive records'],
        ];

        if ($isDeveloper) {
            $primaryStats[] = [
                'label' => 'Admin Clients',
                'value' => $stats['approved_admin_clients'] ?? 0,
                'note' => ($stats['pending_admin_clients'] ?? 0) . ' pending approval',
            ];
        }

        $scopeRows = $isDeveloper
            ? [
                ['label' => 'Developer', 'value' => 'Full system oversight'],
                ['label' => 'Admin-client accounts', 'value' => 'Invite, approve, suspend, and audit'],
                ['label' => 'Customer data', 'value' => 'All customer accounts and all orders'],
                ['label' => 'Unassigned intake', 'value' => ($stats['unassigned_orders'] ?? 0) . ' orders and ' . ($stats['unassigned_customers'] ?? 0) . ' customers need assignment'],
            ]
            : [
                ['label' => 'Admin-client', 'value' => 'Assigned operational records only'],
                ['label' => 'Customer data', 'value' => 'Assigned customers and their orders'],
                ['label' => 'Service catalog', 'value' => 'Shared service records for order handling'],
                ['label' => 'Developer controls', 'value' => 'Hidden from this role'],
            ];
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="relative overflow-hidden bg-[#1f1d1c]">
            <div class="absolute inset-0">
                <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1f1d1c] via-[#1f1d1c]/92 to-[#1f1d1c]/58"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                @if (session('success'))
                    <div class="mb-6 max-w-3xl rounded-lg border border-emerald-300/40 bg-emerald-50/95 px-4 py-3 text-sm font-semibold text-emerald-900 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid gap-8 lg:grid-cols-[1fr,auto] lg:items-end">
                    <div>
                        <p class="text-xs font-black uppercase text-[#ffb970]">
                            {{ $isDeveloper ? 'Staff & Developer Portal' : 'Admin Client Portal' }}
                        </p>
                        <h1 class="mt-3 max-w-3xl text-4xl font-black text-white sm:text-5xl">
                            {{ $isDeveloper ? 'Developer Dashboard' : 'Admin Client Dashboard' }}
                        </h1>
                        <p class="mt-4 max-w-3xl text-sm leading-7 text-white/78">
                            {{ $isDeveloper
                                ? 'Monitor sales, orders, delivery readiness, customer accounts, admin-client access, services, and audit activity from one secured workspace.'
                                : 'Monitor assigned customer orders, sales activity, delivery readiness, service records, and your required reference profile.' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($isDeveloper)
                            <a href="{{ route('developer.admin-clients.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white shadow-lg shadow-orange-950/25 transition hover:bg-[#ff6a00]">
                                Manage Admin Clients
                            </a>
                        @endif

                        @if ($isAdminClient)
                            <a href="{{ route('admin.admin-client-profile.edit') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white shadow-lg shadow-orange-950/25 transition hover:bg-[#ff6a00]">
                                Reference Profile
                            </a>
                        @endif

                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-sm font-black uppercase text-white backdrop-blur transition hover:bg-white/18">
                            Orders
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-sm font-black uppercase text-white backdrop-blur transition hover:bg-white/18">
                            Services
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 {{ $isDeveloper ? 'xl:grid-cols-6' : 'xl:grid-cols-5' }}">
                @foreach ($primaryStats as $stat)
                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $stat['label'] }}</p>
                        <p class="mt-3 break-words text-2xl font-black text-[#22201f]">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-sm text-[#6f675f]">{{ $stat['note'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.25fr,0.75fr]">
                <section class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase text-[#ff8d2a]">Order And Delivery Monitoring</p>
                            <h2 class="mt-1 text-lg font-black text-[#22201f]">Operations Pipeline</h2>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-[#b45309] underline">View orders</a>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($pipeline as $stage)
                            <div class="rounded-lg border border-[#f0e5d8] bg-[#fffaf4] p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-black text-[#22201f]">{{ $stage['label'] }}</p>
                                        <p class="mt-1 text-xs text-[#6f675f]">{{ $stage['note'] }}</p>
                                    </div>
                                    <span class="rounded-lg bg-white px-3 py-2 text-lg font-black text-[#b45309] shadow-sm">{{ $stage['count'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">Role Scope</p>
                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Access Boundaries</h2>

                    <div class="mt-5 space-y-3">
                        @foreach ($scopeRows as $row)
                            <div class="rounded-lg border border-[#f0e5d8] px-4 py-3">
                                <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $row['label'] }}</p>
                                <p class="mt-1 text-sm font-semibold text-[#22201f]">{{ $row['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.15fr,0.85fr]">
                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-[#f0e5d8] px-5 py-4">
                        <div>
                            <p class="text-xs font-black uppercase text-[#ff8d2a]">Sales And Orders</p>
                            <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Orders</h2>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-[#b45309] underline">Open order database</a>
                    </div>

                    <div class="divide-y divide-[#f0e5d8]">
                        @forelse ($recentOrders as $order)
                            <a href="{{ route('admin.orders.show', $order) }}" class="block px-5 py-4 transition hover:bg-[#fff8ef]">
                                <div class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-center">
                                    <div class="min-w-0">
                                        <p class="font-black text-[#22201f]">Order #{{ $order->id }} - {{ $order->customer_name }}</p>
                                        <p class="truncate text-sm text-[#6f675f]">{{ $order->customer_email ?? $order->user?->email ?? 'No email recorded' }}</p>
                                        @if ($isDeveloper)
                                            <p class="mt-1 text-xs font-semibold text-[#8a6d52]">
                                                Admin client: {{ $order->adminClient?->name ?? 'Unassigned' }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $order->status }}</p>
                                        <p class="text-sm font-black text-[#22201f]">{{ $money($order->total_price) }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                No orders are available for this role scope yet.
                            </div>
                        @endforelse
                    </div>
                </section>

                @if ($isDeveloper)
                    <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-[#f0e5d8] px-5 py-4">
                            <div>
                                <p class="text-xs font-black uppercase text-[#ff8d2a]">Admin-Client Database</p>
                                <h2 class="mt-1 text-lg font-black text-[#22201f]">Account Coverage</h2>
                            </div>
                            <a href="{{ route('developer.admin-clients.index') }}" class="text-sm font-bold text-[#b45309] underline">Manage</a>
                        </div>

                        <div class="divide-y divide-[#f0e5d8]">
                            @forelse ($recentAdminClients as $adminClient)
                                <div class="px-5 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="font-black text-[#22201f]">{{ $adminClient->name }}</p>
                                            <p class="truncate text-sm text-[#6f675f]">{{ $adminClient->email }}</p>
                                            <p class="mt-1 text-xs text-[#8a6d52]">{{ $adminClient->adminClientProfile?->business_name ?? 'No reference profile name' }}</p>
                                        </div>
                                        <div class="text-right text-xs font-black uppercase text-[#8a6d52]">
                                            <p>{{ $adminClient->assigned_customers_count }} customers</p>
                                            <p>{{ $adminClient->managed_orders_count }} orders</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                    No admin-client accounts yet.
                                </div>
                            @endforelse
                        </div>
                    </section>
                @else
                    <section class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Admin-Client Profile</p>
                        <h2 class="mt-1 text-lg font-black text-[#22201f]">Access Checklist</h2>

                        <div class="mt-5 space-y-3 text-sm">
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 font-bold text-emerald-800">
                                Developer approval completed
                            </div>
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 font-bold text-emerald-800">
                                Staff portal email OTP verified
                            </div>
                            <a href="{{ route('admin.admin-client-profile.edit') }}" class="block rounded-lg border border-[#eadfd2] px-4 py-3 font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                                Review reference profile
                            </a>
                        </div>

                        @if ($profile)
                            <div class="mt-5 rounded-lg bg-[#f7f4ef] p-4 text-sm text-[#6f675f]">
                                <p class="font-black text-[#22201f]">{{ $profile->business_name }}</p>
                                <p class="mt-1">{{ $profile->contact_person }}</p>
                                <p class="mt-1">{{ $profile->contact_number }}</p>
                                <p class="mt-1">{{ $profile->business_address }}</p>
                            </div>
                        @endif
                    </section>
                @endif
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="border-b border-[#f0e5d8] px-5 py-4">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Client Accounts</p>
                        <h2 class="mt-1 text-lg font-black text-[#22201f]">{{ $isDeveloper ? 'Recent Customer Accounts' : 'Assigned Customer Accounts' }}</h2>
                    </div>

                    <div class="divide-y divide-[#f0e5d8]">
                        @forelse ($recentCustomers as $customer)
                            <div class="px-5 py-4">
                                <p class="font-black text-[#22201f]">{{ $customer->name }}</p>
                                <p class="text-sm text-[#6f675f]">{{ $customer->email }}</p>
                                @if ($isDeveloper)
                                    <p class="mt-1 text-xs font-semibold text-[#8a6d52]">
                                        Admin client: {{ $customer->assignedAdminClient?->name ?? 'Unassigned' }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                No customer accounts are available for this role scope yet.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="border-b border-[#f0e5d8] px-5 py-4">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Audit Trail</p>
                        <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Audit Activity</h2>
                    </div>

                    <div class="divide-y divide-[#f0e5d8]">
                        @forelse ($recentAuditLogs as $log)
                            <div class="px-5 py-4">
                                <p class="font-black text-[#22201f]">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                                <p class="mt-1 text-sm text-[#6f675f]">
                                    Actor: {{ optional($log->actor)->email ?? 'System / invite link' }}
                                </p>
                                @if ($log->targetUser)
                                    <p class="text-sm text-[#6f675f]">Target: {{ $log->targetUser->email }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                No audit activity yet.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
