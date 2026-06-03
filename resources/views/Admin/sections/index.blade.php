<x-app-layout>
    @php
        $user = Auth::user();
        $isDeveloper = $user->isDeveloper();
        $isAdminClient = $user->isAdminClient();
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

        $quickActions = [
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'D', 'tone' => 'bg-[#4f46e5]'],
            ['label' => 'Orders', 'href' => route('admin.orders.index'), 'icon' => 'O', 'tone' => 'bg-[#2563eb]'],
            ['label' => 'System Status', 'href' => route('admin.analytics.index'), 'icon' => 'ST', 'tone' => 'bg-[#10b981]'],
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
                        <p class="mt-2 text-sm font-semibold text-[#6f675f]">Role-scoped records are shown for the signed-in portal account.</p>
                    </div>
                </div>
            </aside>

            <main class="min-w-0">
                <section class="relative overflow-hidden bg-[#111827]">
                    <div class="absolute inset-0">
                        <img src="{{ $heroImage }}" alt="" class="h-full w-full object-cover opacity-24">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#111827] via-[#111827]/92 to-[#111827]/68"></div>
                    </div>

                    <div class="relative px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                        <div class="grid gap-8 xl:grid-cols-[1fr,auto] xl:items-end">
                            <div>
                                <p class="text-sm font-semibold text-white/82">{{ $kicker }}</p>
                                <h1 class="mt-2 max-w-3xl text-4xl font-black uppercase tracking-tight text-[#facc15] sm:text-5xl">{{ $title }}</h1>
                                <p class="mt-4 max-w-3xl text-sm leading-7 text-white/75">{{ $description }}</p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                @foreach ($quickActions as $action)
                                    <a href="{{ $action['href'] }}" class="group grid min-w-[104px] justify-items-center gap-2 text-center text-white">
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
                    <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-5">
                        @foreach ($cards as $card)
                            <article class="rounded-lg border border-[#e5eaf0] bg-white p-5 shadow-sm">
                                <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8a94a3]">{{ $card['label'] }}</p>
                                <p class="mt-4 break-words text-3xl font-black text-[#1f2937]">{{ $card['value'] }}</p>
                                <p class="mt-2 text-sm font-semibold leading-5 text-[#667085]">{{ $card['note'] }}</p>
                            </article>
                        @endforeach
                    </div>

                    <section class="mt-8 overflow-hidden rounded-lg border border-[#e5eaf0] bg-white shadow-sm">
                        <div class="flex flex-col gap-3 border-b border-[#edf1f5] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-[#f97316]">{{ $kicker }}</p>
                                <h2 class="mt-1 text-lg font-black text-[#22201f]">{{ $title }} Records</h2>
                                <p class="mt-1 text-sm text-[#667085]">Records are filtered by the current portal role and assignment scope.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-[#e5eaf0] bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#4f46e5] px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-white transition hover:bg-[#4338ca]">
                                    Orders
                                </a>
                            </div>
                        </div>

                        <div class="divide-y divide-[#edf1f5]">
                            @forelse ($rows as $row)
                                <div class="grid gap-2 px-5 py-4 sm:grid-cols-[1fr,0.75fr] sm:items-center">
                                    <div class="min-w-0">
                                        <p class="font-black text-[#22201f]">{{ $row['title'] }}</p>
                                        <p class="mt-1 break-words text-sm text-[#667085]">{{ $row['meta'] }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-[#8a6d52] sm:text-right">{{ $row['note'] }}</p>
                                </div>
                            @empty
                                <div class="px-5 py-12 text-center text-sm font-semibold text-[#667085]">
                                    {{ $emptyMessage }}
                                </div>
                            @endforelse
                        </div>
                    </section>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
