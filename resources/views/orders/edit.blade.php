<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Order Status (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        .btn { padding: 10px 14px; border: 1px solid #111; background: #111; color: #fff; text-decoration: none; display:inline-block; cursor:pointer; }
        .btn-outline { background: #fff; color: #111; }
        .row { display:flex; gap: 10px; margin-top: 12px; align-items: center; flex-wrap: wrap; }
        .box { border: 1px solid #ddd; border-radius: 10px; padding: 16px; margin-top: 14px; max-width: 520px; }
        select { padding: 10px; width: 100%; }
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .error { background: #ffecec; border: 1px solid #ff9090; }
        label { display:block; margin-bottom: 8px; }
    </style>
</head>
<body>

<h1>Edit Order #{{ $order->id }} (Admin)</h1>

<div class="row">
    <a class="btn btn-outline" href="{{ route('orders.show', $order) }}">Back to Order</a>
    <a class="btn btn-outline" href="{{ route('orders.index') }}">Back to Orders</a>
</div>

<div class="box">
    <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')

        <label for="status"><strong>Status</strong></label>
        <select name="status" id="status" required>
            @php
                $statuses = [
                    'Pending',
                    'Processing',
                    'For Verification',
                    'Ready',
                    'Completed',
                    'Cancelled'
                ];
            @endphp

            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>

        @if($errors->any())
            <div class="msg error">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row" style="margin-top: 14px;">
            <button type="submit" class="btn">Save</button>
        </div>
    </form>
</div>

</body>
</html>
