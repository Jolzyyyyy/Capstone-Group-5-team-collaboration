<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
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
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .success { background: #eaffea; border: 1px solid #72d572; }
        .error { background: #ffecec; border: 1px solid #ff9090; }
        .hint { font-size: 12px; color: #666; margin-top: 6px; }
    </style>
</head>
<body>

{{-- Detect if current page is admin view or customer view --}}
@php
    $isAdminView = request()->routeIs('orders.show');
@endphp

<h1>Order #{{ $order->id }} {{ $isAdminView ? '(Admin)' : '(My Order)' }}</h1>

@if(session('success'))
    <div class="msg success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="msg error">{{ session('error') }}</div>
@endif

<div class="row">
    @if($isAdminView)
        <a class="btn btn-outline" href="{{ route('orders.index') }}">Back to Orders</a>
        <a class="btn btn-outline" href="{{ route('orders.edit', $order) }}">Edit Status</a>
    @else
        <a class="btn btn-outline" href="{{ route('orders.my.index') }}">Back to My Orders</a>
    @endif
</div>

<div class="box">
    <p><strong>Status:</strong> <span class="pill">{{ $order->status }}</span></p>
    <p><strong>Total:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
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

<div class="box">
    <h3>Order Items</h3>

    @if($order->items->count() === 0)
        <p>No items found for this order.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Price Type</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->service_name ?? ($item->service->name ?? 'Service') }}</strong><br>
                        <span class="muted">Service ID: {{ $item->service_id }}</span>
                    </td>
                    <td>{{ strtoupper($item->price_type ?? 'retail') }}</td>
                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ✅ ZIP FILE ATTACHMENT SECTION --}}
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
            <div class="hint">
                Uploaded file is required before placing the order.
            </div>
        @endforeach
    @endif
</div>

{{-- Admin-only delete --}}
@if($isAdminView)
    <div class="box">
        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline">Delete Order</button>
        </form>
    </div>
@endif

</body>
</html>
