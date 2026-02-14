<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 12px; border: 1px solid #111; background: #111; color: #fff; text-decoration: none; display:inline-block; }
        .btn-outline { background: #fff; color: #111; }
        .row { display:flex; gap: 10px; margin-top: 12px; align-items: center; flex-wrap: wrap; }
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .success { background: #eaffea; border: 1px solid #72d572; }
        .error { background: #ffecec; border: 1px solid #ff9090; }
        .pill { padding: 4px 10px; border-radius: 999px; border: 1px solid #ddd; display:inline-block; }
        .muted { color: #666; font-size: 12px; }
    </style>
</head>
<body>

<h1>Orders (Admin)</h1>

@if(session('success'))
    <div class="msg success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="msg error">{{ session('error') }}</div>
@endif

<div class="row">
    <a class="btn btn-outline" href="{{ route('services.index') }}">Services</a>
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
                    <a class="btn" href="{{ route('orders.show', $order) }}">View</a>
                    <a class="btn btn-outline" href="{{ route('orders.edit', $order) }}">Edit</a>

                    <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 12px;">
        {{ $orders->links() }}
    </div>
@endif

</body>
</html>
