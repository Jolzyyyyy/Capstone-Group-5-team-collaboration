<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Submitted</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f4ef; color: #22201f; }
        .wrap { max-width: 820px; margin: 70px auto; padding: 0 18px; text-align: center; }
        .card {
            background: #fff;
            border: 1px solid #eadfd2;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 22px rgba(0,0,0,.06);
        }
        .icon {
            width: 70px; height: 70px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e8f7ee;
            color: #1b7f3a;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 14px;
        }
        h1 { margin: 8px 0 6px; }
        p { margin: 0 0 18px; color: #6f675f; }
        .status {
            display: inline-flex;
            margin: 4px 0 18px;
            border-radius: 999px;
            background: #fff8ef;
            padding: 8px 14px;
            color: #8a6d52;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .btnrow { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 12px; }
        a.btn {
            display: inline-block;
            background: #ff8d2a;
            color: #fff;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 8px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 12px;
        }
        a.btn.secondary {
            background: #fff;
            color: #22201f;
            border: 1px solid #eadfd2;
        }
        .note {
            margin-top: 16px;
            color: #6f675f;
            font-size: 13px;
        }
    </style>
</head>
<body>
@php
    $isPaid = ($order?->payment_status ?? null) === 'paid';
@endphp
<div class="wrap">
    <div class="card">
        <div class="icon">OK</div>
        <h1>{{ $isPaid ? 'Payment Successful' : 'Payment Submitted' }}</h1>
        <p>
            Order #{{ $order?->id }} was returned from PayMongo.
            {{ $isPaid ? 'The order is now marked as paid.' : 'The order is waiting for PayMongo confirmation.' }}
        </p>

        @if($order)
            <div class="status">
                Payment: {{ str_replace('_', ' ', $order->payment_status ?? 'pending') }}
            </div>
        @endif

        <div class="btnrow">
            @if($order)
                <a class="btn" href="{{ route('orders.my.show', $order) }}">View Order</a>
            @endif
            <a class="btn secondary" href="{{ route('services.index') }}">Back to Services</a>
        </div>

        <div class="note">
            PayMongo webhook confirmation updates the final paid status on the order.
        </div>
    </div>
</div>
</body>
</html>
