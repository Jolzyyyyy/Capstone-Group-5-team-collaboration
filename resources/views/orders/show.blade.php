<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 12px; border: 1px solid #111; background: #111; color: #fff; text-decoration: none; display:inline-block; }
        .btn-outline { background: #fff; color: #111; }
        .row { display:flex; gap: 10px; margin-top: 12px; align-items: center; flex-wrap: wrap; }
        .box { border: 1px solid #ddd; border-radius: 10px; padding: 16px; margin-top: 14px; }
        .pill { padding: 4px 10px; border-radius: 999px; border: 1px solid #ddd; display:inline-block; }
        .muted { color: #666; font-size: 12px; }
    </style>
</head>
<body>

@php
    $isAdminView = request()->routeIs('admin.orders.show');
@endphp

<h1>Order #{{ $order->id }} {{ $isAdminView ? '(Admin)' : '(My Order)' }}</h1>

<div class="row">
    @if($isAdminView)
        <a class="btn btn-outline" href="{{ route('admin.orders.index') }}">Back to Orders</a>
    @else
        <a class="btn btn-outline" href="{{ route('my-orders') }}">Back to My Orders</a>
    @endif

    @if($isAdminView && !auth()->user()?->isAdminClient())
        <a class="btn btn-outline" href="{{ route('admin.orders.edit', $order) }}">Edit Status</a>
    @endif
</div>

                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#ff8d2a]">Customer Info</p>
                        <div class="mt-4 space-y-2 text-sm text-[#6f675f]">
                            <p><span class="font-black text-[#22201f]">Name:</span> {{ $order->customer_name }}</p>
                            <p><span class="font-black text-[#22201f]">Email:</span> {{ $order->customer_email ?? '-' }}</p>
                            @if ($isAdminView)
                                <p><span class="font-black text-[#22201f]">Account:</span> {{ $order->user?->name ?? 'N/A' }}</p>
                                <p><span class="font-black text-[#22201f]">Admin Client:</span> {{ $order->adminClient?->name ?? 'Unassigned' }}</p>
                            @endif
                        </div>
                    </div>

<div class="box">
    <h3>Customer Info</h3>
    <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email ?? '-' }}</p>
    @if($isAdminView)
        <p><strong>User ID:</strong> {{ $order->user_id ?? '-' }}</p>
        <p><strong>Account Name:</strong> {{ $order->user?->name ?? 'N/A' }}</p>
    @else
        <p class="muted">This order belongs to your account.</p>
    @endif
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

<div class="box">
    <h3>Attached ZIP File</h3>

    @if(!isset($order->files) || $order->files->count() === 0)
        <p class="muted">No ZIP file attached.</p>
    @else
        @foreach($order->files as $file)
            <p>
                <a class="btn btn-outline" href="{{ \Illuminate\Support\Facades\Storage::url($file->path) }}" target="_blank">
                    Download ZIP: {{ $file->original_name }}
                </a>
            </p>
            <div class="muted">
                Uploaded file is required before placing the order.
            </div>
        @endforeach
    @endif
</div>

@if($isAdminView && !auth()->user()?->isAdminClient())
    <div class="box">
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline">Delete Order</button>
        </form>
    </div>
@endif

</body>
</html>
