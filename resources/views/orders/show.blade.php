<x-app-layout>
    @php
        $isAdminView = request()->routeIs('admin.orders.show');
        $currentUser = Auth::user();
        $canDelete = $isAdminView && $currentUser?->isDeveloper();
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="border-b border-[#eadfd2] bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
                <div>
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">{{ $isAdminView ? 'Order Database' : 'My Order' }}</p>
                    <h1 class="mt-2 text-3xl font-black text-[#22201f]">Order #{{ $order->id }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6f675f]">
                        {{ $isAdminView ? 'Review customer information, order items, files, and fulfillment status.' : 'Review your order details and current status.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($isAdminView)
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">Back</a>
                        <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white transition hover:bg-[#ff6a00]">Update Status</a>
                    @else
                        <a href="{{ route('orders.my.index') }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">Back</a>
                    @endif
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[0.85fr,1.15fr]">
                <section class="space-y-6">
                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Order Summary</p>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-[#6f675f]">Status</span>
                                <span class="font-black text-[#22201f]">{{ $order->status }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-[#6f675f]">Payment</span>
                                <span class="font-black uppercase text-[#22201f]">{{ str_replace('_', ' ', $order->payment_status ?? 'unpaid') }}</span>
                            </div>
                            @if ($order->payment_method)
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#6f675f]">Method</span>
                                    <span class="font-black uppercase text-[#22201f]">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                </div>
                            @endif
                            @if ($order->paid_at)
                                <div class="flex justify-between gap-4">
                                    <span class="text-[#6f675f]">Paid</span>
                                    <span class="font-black text-[#22201f]">{{ $order->paid_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between gap-4">
                                <span class="text-[#6f675f]">Total</span>
                                <span class="font-black text-[#22201f]">{{ $money($order->total_price) }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-[#6f675f]">Date</span>
                                <span class="font-black text-[#22201f]">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Customer Info</p>
                        <div class="mt-4 space-y-2 text-sm text-[#6f675f]">
                            <p><span class="font-black text-[#22201f]">Name:</span> {{ $order->customer_name }}</p>
                            <p><span class="font-black text-[#22201f]">Email:</span> {{ $order->customer_email ?? '-' }}</p>
                            <p><span class="font-black text-[#22201f]">Phone:</span> {{ $order->customer_phone ?? '-' }}</p>
                            <p><span class="font-black text-[#22201f]">Release:</span> {{ ucfirst($order->fulfillment_method ?? 'pickup') }}</p>
                            @if ($order->delivery_address)
                                <p><span class="font-black text-[#22201f]">Address:</span> {{ $order->delivery_address }}</p>
                            @endif
                            @if ($order->customer_note)
                                <p><span class="font-black text-[#22201f]">Note:</span> {{ $order->customer_note }}</p>
                            @endif
                            @if ($isAdminView)
                                <p><span class="font-black text-[#22201f]">Account:</span> {{ $order->user?->name ?? 'N/A' }}</p>
                                <p><span class="font-black text-[#22201f]">Admin Client:</span> {{ $order->adminClient?->name ?? 'Unassigned' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Files</p>
                        <div class="mt-4 space-y-3">
                            @forelse ($order->files as $file)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($file->path) }}" target="_blank" class="block rounded-lg border border-[#eadfd2] px-4 py-3 text-sm font-black text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">
                                    {{ $file->original_name }}
                                </a>
                            @empty
                                <p class="text-sm font-semibold text-[#6f675f]">No file attached.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                    <div class="border-b border-[#f0e5d8] px-5 py-4">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Order Items</p>
                        <h2 class="mt-1 text-lg font-black text-[#22201f]">Service Details</h2>
                    </div>

                    <div class="divide-y divide-[#f0e5d8]">
                        @forelse ($order->items as $item)
                            <div class="grid gap-3 px-5 py-4 text-sm sm:grid-cols-[1fr,auto] sm:items-center">
                                <div>
                                    <p class="font-black text-[#22201f]">{{ $item->service_name ?? ($item->service->name ?? 'Service') }}</p>
                                    <p class="text-xs text-[#6f675f]">{{ $item->variation_label ?? 'Standard variation' }}</p>
                                    <p class="text-xs text-[#8a6d52]">Qty {{ $item->quantity }} at {{ $money($item->unit_price) }}</p>
                                </div>
                                <p class="font-black text-[#22201f]">{{ $money($item->subtotal) }}</p>
                            </div>
                        @empty
                            <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                                No items found for this order.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            @if ($canDelete)
                <section class="mt-6 rounded-lg border border-rose-200 bg-white p-5 shadow-sm">
                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-rose-200 px-4 py-3 text-sm font-black uppercase text-rose-700 transition hover:bg-rose-50">
                            Delete Order
                        </button>
                    </form>
                </section>
            @endif
        </main>
    </div>
</x-app-layout>
