<x-app-layout>
    @php
        $user = Auth::user();
        $isDeveloper = $user->isDeveloper();
        $isAdminClient = $user->isAdminClient();
        $profile = $profile ?? ($isAdminClient ? $user->adminClientProfile : null);
        $stats = $stats ?? [];
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
        $heroImage = $isDeveloper ? asset('images/Homesld2.jpg') : asset('images/Homesld3.jpg');

        $portalLinks = [
            ['label' => 'Go to Home', 'href' => url('/'), 'active' => false, 'icon' => 'H'],
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'D'],
            ['label' => 'Orders', 'href' => route('admin.orders.index'), 'active' => request()->routeIs('admin.orders.*'), 'icon' => 'O'],
            ['label' => 'Customers', 'href' => route('admin.customers.index'), 'active' => request()->routeIs('admin.customers.*'), 'icon' => 'C'],
            ['label' => 'Services', 'href' => route('admin.services.index'), 'active' => request()->routeIs('admin.services.*'), 'icon' => 'S'],
            ['label' => 'Analytics', 'href' => route('admin.analytics.index'), 'active' => request()->routeIs('admin.analytics.*'), 'icon' => 'A'],
            ['label' => 'Reports', 'href' => route('admin.reports.index'), 'active' => request()->routeIs('admin.reports.*'), 'icon' => 'R'],
            ['label' => 'Settings', 'href' => route('admin.settings.index'), 'active' => request()->routeIs('admin.settings.*'), 'icon' => 'G'],
            ['label' => 'Help Center', 'href' => route('admin.help.index'), 'active' => request()->routeIs('admin.help.*'), 'icon' => '?'],
        ];

        if ($isDeveloper) {
            array_splice($portalLinks, 4, 0, [[
                'label' => 'Admin Clients',
                'href' => route('developer.admin-clients.index'),
                'active' => request()->routeIs('developer.admin-clients.*'),
                'icon' => 'AC',
            ]]);
        }

        if ($isAdminClient) {
            $portalLinks[] = [
                'label' => 'Reference Profile',
                'href' => route('admin.admin-client-profile.edit'),
                'active' => request()->routeIs('admin.admin-client-profile.*'),
                'icon' => 'P',
            ];
        }

        $primaryStats = [
            ['label' => 'Revenue Stream', 'value' => $money($stats['sales_total'] ?? 0), 'note' => 'Total recorded order value', 'href' => route('admin.reports.index'), 'tone' => 'from-[#f97316] to-[#ec4899]'],
            ['label' => 'Order Trends', 'value' => number_format((int) ($stats['orders'] ?? 0)), 'note' => ($stats['orders_this_month'] ?? 0) . ' orders this month', 'href' => route('admin.orders.index'), 'tone' => 'from-[#2563eb] to-[#14b8a6]'],
            ['label' => 'Account Status', 'value' => number_format((int) ($stats['customers'] ?? 0)), 'note' => $isDeveloper ? 'All customer accounts' : 'Assigned customers', 'href' => route('admin.customers.index'), 'tone' => 'from-[#10b981] to-[#059669]'],
            ['label' => 'Service Inventory', 'value' => number_format((int) ($stats['active_services'] ?? 0)), 'note' => ($stats['inactive_services'] ?? 0) . ' inactive records', 'href' => route('admin.services.index'), 'tone' => 'from-[#f59e0b] to-[#f97316]'],
        ];

        if ($isDeveloper) {
            $primaryStats[] = [
                'label' => 'Admin Clients',
                'value' => number_format((int) ($stats['approved_admin_clients'] ?? 0)),
                'note' => ($stats['pending_admin_clients'] ?? 0) . ' pending approval',
                'href' => route('developer.admin-clients.index'),
                'tone' => 'from-[#7c3aed] to-[#4f46e5]',
            ];
        }

        $quickActions = $isDeveloper
            ? [
                ['label' => 'Manage Admin Clients', 'href' => route('developer.admin-clients.index'), 'icon' => 'AC', 'tone' => 'bg-[#7c3aed]'],
                ['label' => 'System Status', 'href' => route('admin.analytics.index'), 'icon' => 'ST', 'tone' => 'bg-[#10b981]'],
                ['label' => 'Printer Queue', 'href' => route('admin.orders.index'), 'icon' => 'PQ', 'tone' => 'bg-[#f59e0b]'],
            ]
            : [
                ['label' => 'Assigned Orders', 'href' => route('admin.orders.index'), 'icon' => 'AO', 'tone' => 'bg-[#2563eb]'],
                ['label' => 'System Status', 'href' => route('admin.analytics.index'), 'icon' => 'ST', 'tone' => 'bg-[#10b981]'],
                ['label' => 'Service Catalog', 'href' => route('admin.services.index'), 'icon' => 'SC', 'tone' => 'bg-[#f59e0b]'],
            ];

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

        $statusClasses = [
            'Pending' => 'bg-amber-50 text-amber-800 ring-amber-200',
            'For Verification' => 'bg-blue-50 text-blue-800 ring-blue-200',
            'Processing' => 'bg-violet-50 text-violet-800 ring-violet-200',
            'Ready' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
            'Completed' => 'bg-slate-100 text-slate-800 ring-slate-200',
            'Cancelled' => 'bg-rose-50 text-rose-800 ring-rose-200',
        ];

        $paymentClasses = [
            'paid' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
            'pending' => 'bg-amber-50 text-amber-800 ring-amber-200',
            'failed' => 'bg-rose-50 text-rose-800 ring-rose-200',
            'cancelled' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'unpaid' => 'bg-slate-100 text-slate-700 ring-slate-200',
        ];
    @endphp

    <div class="min-h-screen bg-[#f4f7fb]" style="font-family: 'Poppins', sans-serif;">
        <div class="lg:grid lg:grid-cols-[232px,1fr]">
            <aside class="hidden border-r border-[#e5eaf0] bg-white lg:block">
                <div class="sticky top-16 flex min-h-[calc(100vh-4rem)] flex-col px-5 py-6">
                    <div class="mb-8 flex items-center gap-3">
                        <x-application-logo class="h-12 w-12 rounded-full shadow-sm" />
                        <div>
                            <p class="text-lg font-black tracking-tight text-[#1f2937]">Printify &amp; Co.</p>
                            <p class="mt-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#9ca3af]">
                                {{ $isDeveloper ? 'Developer Portal' : 'Admin Client Portal' }}
                            </p>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        @foreach ($portalLinks as $link)
                            <a href="{{ $link['href'] }}" class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-bold transition {{ $link['active'] ? 'bg-[#eef2ff] text-[#4f46e5] shadow-sm' : 'text-[#5f6672] hover:bg-[#f6f8fb] hover:text-[#22201f]' }}">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg border text-[11px] font-black {{ $link['active'] ? 'border-[#c7d2fe] bg-white text-[#4f46e5]' : 'border-[#e5eaf0] bg-white text-[#8a94a3] group-hover:border-[#ffcf9c] group-hover:text-[#f97316]' }}">
                                    {{ $link['icon'] }}
                                </span>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-auto rounded-lg border border-[#fee2e2] bg-[#fff7f7] p-4">
                        <p class="text-xs font-black uppercase text-[#b91c1c]">Secure Session</p>
                        <p class="mt-2 text-sm font-semibold text-[#6f675f]">Staff portal email OTP is required before this workspace opens.</p>
                    </div>
                </div>
            </aside>

            <main class="min-w-0">
                <section class="relative overflow-hidden bg-[#1f2937]">
                    <div class="absolute inset-0">
                        <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-24">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#111827] via-[#111827]/92 to-[#111827]/68"></div>
                    </div>

                    <div class="relative px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                        @if (session('success'))
                            <div class="mb-6 max-w-3xl rounded-lg border border-emerald-300/40 bg-emerald-50/95 px-4 py-3 text-sm font-semibold text-emerald-900 shadow-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="grid gap-8 xl:grid-cols-[1fr,auto] xl:items-end">
                            <div>
                                <p class="text-sm font-semibold text-white/82">{{ $isDeveloper ? 'Staff and Developer Portal' : 'Admin Client Portal' }}</p>
                                <h1 class="mt-2 max-w-3xl text-4xl font-black uppercase tracking-tight text-[#facc15] sm:text-5xl">
                                    {{ $isDeveloper ? 'Developer Dashboard' : 'Admin Client Dashboard' }}
                                </h1>
                                <p class="mt-4 max-w-3xl text-sm leading-7 text-white/75">
                                    {{ $isDeveloper
                                        ? 'Monitor sales, orders, delivery readiness, customer accounts, admin-client access, services, and audit activity from one secured workspace.'
                                        : 'Monitor assigned customer orders, sales activity, delivery readiness, service records, and your required reference profile.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                @foreach ($quickActions as $action)
                                    <a href="{{ $action['href'] }}" class="group grid min-w-[110px] justify-items-center gap-2 text-center text-white">
                                        <span class="grid h-14 w-14 place-items-center rounded-full {{ $action['tone'] }} text-xs font-black shadow-lg shadow-black/20 ring-4 ring-white/10 transition group-hover:-translate-y-0.5">
                                            {{ $action['icon'] }}
                                        </span>
                                        <span class="text-[11px] font-black uppercase tracking-[0.12em] text-white/85">{{ $action['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="px-4 py-8 sm:px-6 lg:px-10">
                    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-black uppercase tracking-[0.14em] text-[#6b7280]">Overview</p>
                            <h2 class="mt-1 text-2xl font-black text-[#22201f]">Role-Based Operations</h2>
                        </div>
                        <div class="rounded-lg border border-[#e5eaf0] bg-white px-4 py-3 text-sm font-bold text-[#4b5563] shadow-sm">
                            {{ now()->format('M d, Y') }} - Present
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-5">
                        @foreach ($primaryStats as $stat)
                            <article class="overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                                <div class="p-5">
                                    <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8a94a3]">{{ $stat['label'] }}</p>
                                    <p class="mt-4 break-words text-3xl font-black text-[#1f2937]">{{ $stat['value'] }}</p>
                                    <p class="mt-2 min-h-[2.5rem] text-sm font-semibold leading-5 text-[#667085]">{{ $stat['note'] }}</p>
                                </div>
                                <a href="{{ $stat['href'] }}" class="flex items-center justify-between bg-gradient-to-r {{ $stat['tone'] }} px-5 py-3 text-xs font-black uppercase tracking-[0.12em] text-white">
                                    <span>Open</span>
                                    <span>+</span>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-8 grid gap-6 xl:grid-cols-[1.35fr,0.65fr]">
                        <section class="overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                            <div class="flex flex-col gap-3 border-b border-[#edf1f5] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#f97316]">Sales and Orders</p>
                                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Transactions</h2>
                                    <p class="mt-1 text-sm text-[#667085]">Recent order records visible to this portal role.</p>
                                </div>
                                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#4f46e5] px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-white transition hover:bg-[#4338ca]">
                                    View Orders
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-[#f8fafc] text-xs font-black uppercase tracking-[0.12em] text-[#8a94a3]">
                                        <tr>
                                            <th class="px-5 py-3">Order ID</th>
                                            <th class="px-5 py-3">Customer</th>
                                            @if ($isDeveloper)
                                                <th class="px-5 py-3">Admin Client</th>
                                            @endif
                                            <th class="px-5 py-3">Status</th>
                                            <th class="px-5 py-3">Payment</th>
                                            <th class="px-5 py-3 text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#edf1f5]">
                                        @forelse ($recentOrders as $order)
                                            @php
                                                $paymentStatus = strtolower($order->payment_status ?? 'unpaid');
                                            @endphp
                                            <tr class="transition hover:bg-[#fff8ef]">
                                                <td class="px-5 py-4">
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-black text-[#4f46e5] underline-offset-4 hover:underline">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <p class="font-black text-[#22201f]">{{ $order->customer_name }}</p>
                                                    <p class="text-xs text-[#667085]">{{ $order->customer_email ?? $order->user?->email ?? 'No email recorded' }}</p>
                                                </td>
                                                @if ($isDeveloper)
                                                    <td class="px-5 py-4 text-sm font-semibold text-[#667085]">{{ $order->adminClient?->name ?? 'Unassigned' }}</td>
                                                @endif
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase ring-1 {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase ring-1 {{ $paymentClasses[$paymentStatus] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                                                        {{ str_replace('_', ' ', $order->payment_status ?? 'unpaid') }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4 text-right font-black text-[#22201f]">{{ $money($order->total_price) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $isDeveloper ? 6 : 5 }}" class="px-5 py-12 text-center text-sm font-semibold text-[#667085]">
                                                    No orders are available for this role scope yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="rounded-lg border border-[#e5eaf0] bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#10b981]">System Status</p>
                                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Production Queue</h2>
                                </div>
                                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-black uppercase tracking-[0.12em] text-[#4f46e5] underline">Analytics</a>
                            </div>

                            <div class="mt-5 space-y-4">
                                @foreach ($pipeline as $stage)
                                    @php
                                        $count = (int) ($stage['count'] ?? 0);
                                        $width = min(100, $count * 18);
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="text-sm font-black text-[#22201f]">{{ $stage['label'] }}</p>
                                            <span class="rounded-lg bg-[#f8fafc] px-3 py-1 text-sm font-black text-[#4f46e5]">{{ $count }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-[#667085]">{{ $stage['note'] }}</p>
                                        <div class="mt-2 h-2 rounded-full bg-[#edf1f5]">
                                            <div class="h-2 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#10b981]" style="width: {{ max(8, $width) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <div class="mt-8 grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
                        <section class="overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-[#edf1f5] px-5 py-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#4f46e5]">Customer Database</p>
                                    <h2 class="mt-1 text-lg font-black text-[#22201f]">{{ $isDeveloper ? 'Recent Customer Accounts' : 'Assigned Customer Accounts' }}</h2>
                                </div>
                                <a href="{{ route('admin.customers.index') }}" class="text-xs font-black uppercase tracking-[0.12em] text-[#4f46e5] underline">Open</a>
                            </div>

                            <div class="divide-y divide-[#edf1f5]">
                                @forelse ($recentCustomers as $customer)
                                    <div class="grid gap-3 px-5 py-4 sm:grid-cols-[1fr,auto] sm:items-center">
                                        <div class="min-w-0">
                                            <p class="font-black text-[#22201f]">{{ $customer->name }}</p>
                                            <p class="truncate text-sm text-[#667085]">{{ $customer->email }}</p>
                                            @if ($isDeveloper)
                                                <p class="mt-1 text-xs font-semibold text-[#8a6d52]">
                                                    Admin client: {{ $customer->assignedAdminClient?->name ?? 'Unassigned' }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase ring-1 {{ $customer->email_verified_at ? 'bg-emerald-50 text-emerald-800 ring-emerald-200' : 'bg-amber-50 text-amber-800 ring-amber-200' }}">
                                            {{ $customer->email_verified_at ? 'Verified' : 'Needs OTP' }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="px-5 py-12 text-center text-sm font-semibold text-[#667085]">
                                        No customer accounts are available for this role scope yet.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section class="grid gap-6 lg:grid-cols-2">
                            <div class="rounded-lg border border-[#e5eaf0] bg-white p-5 shadow-sm">
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-[#f97316]">Role Scope</p>
                                <h2 class="mt-1 text-lg font-black text-[#22201f]">Access Boundaries</h2>

                                <div class="mt-5 space-y-3">
                                    @foreach ($scopeRows as $row)
                                        <div class="rounded-lg border border-[#edf1f5] bg-[#f8fafc] px-4 py-3">
                                            <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8a94a3]">{{ $row['label'] }}</p>
                                            <p class="mt-1 text-sm font-semibold text-[#22201f]">{{ $row['value'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($isDeveloper)
                                <div class="overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                                    <div class="flex items-center justify-between border-b border-[#edf1f5] px-5 py-4">
                                        <div>
                                            <p class="text-xs font-black uppercase tracking-[0.14em] text-[#7c3aed]">Admin-Client Database</p>
                                            <h2 class="mt-1 text-lg font-black text-[#22201f]">Account Coverage</h2>
                                        </div>
                                        <a href="{{ route('developer.admin-clients.index') }}" class="text-xs font-black uppercase tracking-[0.12em] text-[#4f46e5] underline">Manage</a>
                                    </div>

                                    <div class="divide-y divide-[#edf1f5]">
                                        @forelse ($recentAdminClients as $adminClient)
                                            <div class="px-5 py-4">
                                                <p class="font-black text-[#22201f]">{{ $adminClient->name }}</p>
                                                <p class="truncate text-sm text-[#667085]">{{ $adminClient->email }}</p>
                                                <p class="mt-1 text-xs text-[#8a6d52]">{{ $adminClient->adminClientProfile?->business_name ?? 'No reference profile name' }}</p>
                                                <p class="mt-2 text-xs font-black uppercase text-[#4f46e5]">{{ $adminClient->assigned_customers_count }} customers / {{ $adminClient->managed_orders_count }} orders</p>
                                            </div>
                                        @empty
                                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#667085]">
                                                No admin-client accounts yet.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @else
                                <div class="rounded-lg border border-[#e5eaf0] bg-white p-5 shadow-sm">
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#10b981]">Admin-Client Profile</p>
                                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Access Checklist</h2>

                                    <div class="mt-5 space-y-3 text-sm">
                                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 font-bold text-emerald-800">
                                            Developer approval completed
                                        </div>
                                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 font-bold text-emerald-800">
                                            Staff portal email OTP verified
                                        </div>
                                        <a href="{{ route('admin.admin-client-profile.edit') }}" class="block rounded-lg border border-[#e5eaf0] px-4 py-3 font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                                            Reference Profile
                                        </a>
                                    </div>

                                    @if ($profile)
                                        <div class="mt-5 rounded-lg bg-[#f8fafc] p-4 text-sm text-[#667085]">
                                            <p class="font-black text-[#22201f]">{{ $profile->business_name }}</p>
                                            <p class="mt-1">{{ $profile->contact_person }}</p>
                                            <p class="mt-1">{{ $profile->contact_number }}</p>
                                            <p class="mt-1">{{ $profile->business_address }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </section>
                    </div>

                    <div class="mt-8 grid gap-6 xl:grid-cols-[1fr,0.72fr]">
                        <section class="overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-[#edf1f5] px-5 py-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-[#f97316]">Audit Trail</p>
                                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Audit Activity</h2>
                                </div>
                                <a href="{{ route('admin.help.index') }}" class="text-xs font-black uppercase tracking-[0.12em] text-[#4f46e5] underline">Help</a>
                            </div>

                            <div class="divide-y divide-[#edf1f5]">
                                @forelse ($recentAuditLogs as $log)
                                    <div class="px-5 py-4">
                                        <p class="font-black text-[#22201f]">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                                        <p class="mt-1 text-sm text-[#667085]">
                                            Actor: {{ optional($log->actor)->email ?? 'System / invite link' }}
                                        </p>
                                        @if ($log->targetUser)
                                            <p class="text-sm text-[#667085]">Target: {{ $log->targetUser->email }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <div class="px-5 py-12 text-center text-sm font-semibold text-[#667085]">
                                        No audit activity yet.
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section class="rounded-lg border border-[#e5eaf0] bg-[#111827] p-5 text-white shadow-sm">
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-[#facc15]">Admin Messages</p>
                            <h2 class="mt-1 text-lg font-black">Support Channel</h2>
                            <p class="mt-3 text-sm leading-6 text-white/72">
                                Use Help Center for role access reminders, Reports for order follow-up, and Settings for portal security status.
                            </p>

                            <div class="mt-5 space-y-3">
                                <a href="{{ route('admin.help.index') }}" class="block rounded-lg bg-white/10 px-4 py-3 text-sm font-black transition hover:bg-white/15">Help Center</a>
                                <a href="{{ route('admin.reports.index') }}" class="block rounded-lg bg-white/10 px-4 py-3 text-sm font-black transition hover:bg-white/15">Reports</a>
                                <a href="{{ route('admin.settings.index') }}" class="block rounded-lg bg-white/10 px-4 py-3 text-sm font-black transition hover:bg-white/15">Settings</a>
                            </div>
                        </section>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
