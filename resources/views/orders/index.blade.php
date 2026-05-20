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

@if($orders->count() === 0)
    <p>No orders found.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Email</th>
                <th>User ID</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>

                <td>
                    <strong>{{ $order->customer_name }}</strong><br>
                    <span class="muted">Placed by: {{ $order->user?->name ?? 'N/A' }}</span>
                </td>

                <td>{{ $order->customer_email ?? '-' }}</td>

                <td>{{ $order->user_id ?? '-' }}</td>

                <td><span class="pill">{{ $order->status }}</span></td>

                <td>₱{{ number_format($order->total_price, 2) }}</td>

                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>

                <td class="row" style="margin-top:0;">
                    <a class="btn" href="{{ route('admin.orders.show', $order) }}">View</a>
                    @if(!auth()->user()?->isAdminClient())
                        <a class="btn btn-outline" href="{{ route('admin.orders.edit', $order) }}">Edit</a>
                    @endif

                    @if(!auth()->user()?->isAdminClient())
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 12px;">
        {{ $orders->links() }}
    </div>
</x-app-layout>
