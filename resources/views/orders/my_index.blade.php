<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        table { border-collapse: collapse; width: 100%; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 12px; border: 1px solid #111; background: #111; color: #fff; text-decoration: none; }
        .btn-outline { background: #fff; color: #111; }
        .row { display:flex; gap: 10px; margin-top: 12px; }
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .success { background: #eaffea; border: 1px solid #72d572; }
    </style>
</head>
<body>

<h1>My Orders</h1>

@if(session('success'))
    <div class="msg success">{{ session('success') }}</div>
@endif

<div class="row">
    <a class="btn btn-outline" href="{{ route('services.index') }}">Back to Services</a>
</div>

@if($orders->count() === 0)
    <p>You have no orders yet.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Order #</th>
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
                <td>{{ $order->status }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                <td>
                    <a class="btn" href="{{ route('orders.my.show', $order) }}">View</a>
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

