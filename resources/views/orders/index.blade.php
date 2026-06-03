<x-app-layout>
    @php
        $user = Auth::user();
        $isDeveloper = $user->isDeveloper();
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="border-b border-[#eadfd2] bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
                <div>
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">Order Database</p>
                    <h1 class="mt-2 text-3xl font-black text-[#22201f]">{{ $isDeveloper ? 'All Orders' : 'Assigned Orders' }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6f675f]">
                        {{ $isDeveloper ? 'Developer view includes all customer and admin-client order records.' : 'Admin-client view is limited to assigned customer and order records.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">Dashboard</a>
                    <a href="{{ route('admin.services.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white transition hover:bg-[#ff6a00]">Services</a>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                <div class="grid border-b border-[#f0e5d8] px-5 py-3 text-xs font-black uppercase text-[#8a6d52] md:grid-cols-[0.7fr,1.5fr,1.2fr,0.9fr,1fr,1fr]">
                    <div>Order</div>
                    <div>Customer</div>
                    <div>{{ $isDeveloper ? 'Admin Client' : 'Assigned Scope' }}</div>
                    <div>Status</div>
                    <div>Total</div>
                    <div class="text-right">Actions</div>
                </div>

                <div class="divide-y divide-[#f0e5d8]">
                    @forelse ($orders as $order)
                        <div class="grid gap-4 px-5 py-4 text-sm md:grid-cols-[0.7fr,1.5fr,1.2fr,0.9fr,0.9fr,1fr,1fr] md:items-center">
                            <div class="font-black text-[#22201f]">#{{ $order->id }}</div>
                            <div class="min-w-0">
                                <p class="font-black text-[#22201f]">{{ $order->customer_name }}</p>
                                <p class="truncate text-xs text-[#6f675f]">{{ $order->customer_email ?? $order->user?->email ?? 'No email recorded' }}</p>
                                <p class="text-xs text-[#8a6d52]">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="text-[#6f675f]">{{ $order->adminClient?->name ?? ($isDeveloper ? 'Unassigned' : 'Assigned to you') }}</div>
                            <div>
                                <span class="rounded-lg bg-[#fff8ef] px-3 py-1 text-xs font-black uppercase text-[#8a6d52]">{{ $order->status }}</span>
                            </div>
                            <div>
                                <span class="rounded-lg bg-[#eef8ff] px-3 py-1 text-xs font-black uppercase text-[#2563eb]">{{ str_replace('_', ' ', $order->payment_status ?? 'unpaid') }}</span>
                            </div>
                            <div class="font-black text-[#22201f]">{{ $money($order->total_price) }}</div>
                            <div class="flex flex-wrap justify-start gap-2 md:justify-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="rounded-lg bg-[#ff8d2a] px-3 py-2 text-xs font-black uppercase text-white transition hover:bg-[#ff6a00]">View</a>
                                <a href="{{ route('admin.orders.edit', $order) }}" class="rounded-lg border border-[#eadfd2] px-3 py-2 text-xs font-black uppercase text-[#22201f] transition hover:bg-[#fff8ef]">Status</a>
                                @if ($isDeveloper)
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-black uppercase text-rose-700 transition hover:bg-rose-50">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                            No orders are available for this role scope yet.
                        </div>
                    @endforelse
                </div>
            </section>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </main>
    </div>
</x-app-layout>
