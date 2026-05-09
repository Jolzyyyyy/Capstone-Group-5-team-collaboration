<x-app-layout>
    @php
        $user = Auth::user();
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
        $customerStats = [
            ['label' => 'Account Security', 'value' => 'Verified', 'note' => 'OTP verification is complete'],
            ['label' => 'Total Orders', 'value' => $orderCount ?? 0, 'note' => 'All orders under your account'],
            ['label' => 'Active Orders', 'value' => $activeOrderCount ?? 0, 'note' => 'Pending through ready'],
            ['label' => 'Completed', 'value' => $completedOrderCount ?? 0, 'note' => 'Finished print requests'],
            ['label' => 'Total Spent', 'value' => $money($totalSpent ?? 0), 'note' => 'Recorded order value'],
            ['label' => 'Services', 'value' => $availableServices ?? 0, 'note' => 'Available catalog entries'],
        ];

        $supportName = $assignedAdminClient?->name ?? 'Not assigned yet';
        $supportEmail = $assignedAdminClient?->email ?? 'A staff member will handle new requests after assignment.';
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="relative overflow-hidden bg-[#1f1d1c]">
            <div class="absolute inset-0">
                <img src="{{ asset('images/Homesld1.jpg') }}" alt="" class="h-full w-full object-cover opacity-35">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1f1d1c] via-[#1f1d1c]/88 to-[#1f1d1c]/45"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                @if (session('status'))
                    <div class="mb-6 max-w-3xl rounded-lg border border-emerald-300/40 bg-emerald-50/95 px-4 py-3 text-sm font-semibold text-emerald-900 shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid gap-8 lg:grid-cols-[1fr,auto] lg:items-end">
                    <div>
                        <p class="text-xs font-black uppercase text-[#ffb970]">Customer Access</p>
                        <h1 class="mt-3 max-w-3xl text-4xl font-black text-white sm:text-5xl">
                            Welcome, {{ $user->name }}
                        </h1>
                        <p class="mt-4 max-w-3xl text-sm leading-7 text-white/78">
                            Track your print requests, order status, uploaded files, checkout records, and assigned support contact from one customer workspace.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white shadow-lg shadow-orange-950/25 transition hover:bg-[#ff6a00]">
                            Browse Services
                        </a>
                        <a href="{{ route('orders.my.index') }}" class="inline-flex items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-sm font-black uppercase text-white backdrop-blur transition hover:bg-white/18">
                            My Orders
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
                @foreach ($customerStats as $stat)
                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $stat['label'] }}</p>
                        <p class="mt-3 break-words text-2xl font-black text-[#22201f]">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-sm text-[#6f675f]">{{ $stat['note'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.15fr,0.85fr]">
                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-[#f0e5d8] px-5 py-4">
                        <div>
                            <p class="text-xs font-black uppercase text-[#ff8d2a]">Print Requests</p>
                            <h2 class="mt-1 text-lg font-black text-[#22201f]">Recent Orders</h2>
                        </div>
                        <a href="{{ route('orders.my.index') }}" class="text-sm font-bold text-[#b45309] underline">View all</a>
                    </div>

                    <div class="divide-y divide-[#f0e5d8]">
                        @forelse ($recentOrders as $order)
                            <a href="{{ route('orders.my.show', $order) }}" class="block px-5 py-4 transition hover:bg-[#fff8ef]">
                                <div class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-center">
                                    <div class="min-w-0">
                                        <p class="font-black text-[#22201f]">Order #{{ $order->id }} - {{ $order->customer_name }}</p>
                                        <p class="text-sm text-[#6f675f]">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $order->status }}</p>
                                        <p class="text-sm font-black text-[#22201f]">{{ $money($order->total_price) }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                No orders yet.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">Customer Support</p>
                    <h2 class="mt-1 text-lg font-black text-[#22201f]">Assigned Admin Client</h2>

                    <div class="mt-5 rounded-lg bg-[#f7f4ef] p-4">
                        <p class="font-black text-[#22201f]">{{ $supportName }}</p>
                        <p class="mt-1 text-sm text-[#6f675f]">{{ $supportEmail }}</p>
                    </div>

                    <div class="mt-5 space-y-3">
                        <a href="{{ route('services.index') }}" class="block rounded-lg border border-[#eadfd2] px-4 py-3 text-sm font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                            Browse services
                        </a>
                        <a href="{{ route('cart.index') }}" class="block rounded-lg border border-[#eadfd2] px-4 py-3 text-sm font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                            Review cart
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg border border-[#eadfd2] px-4 py-3 text-sm font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                            Update profile
                        </a>
                    </div>

                    <div class="mt-5 rounded-lg border border-[#f0e5d8] bg-white px-4 py-3 text-sm text-[#6f675f]">
                        <p class="font-black text-[#22201f]">Order flow</p>
                        <p class="mt-1">Pending, verification, processing, ready, then completed.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>
</x-app-layout>
