<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .wrap { max-width: 800px; margin: 70px auto; text-align: center; }
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 8px 22px rgba(0,0,0,.06);
        }
        .icon {
            width: 70px; height: 70px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff0f0;
            color: #b00020;
            font-size: 34px;
            margin-bottom: 14px;
        }
        h1 { margin: 8px 0 6px; }
        p { margin: 0 0 18px; color: #444; }
        .btnrow { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 12px; }
        a.btn {
            display: inline-block;
            background: #111;
            color: #fff;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 6px;
        }
        a.btn.secondary {
            background: #fff;
            color: #111;
            border: 1px solid #111;
        }
        .note {
            margin-top: 14px;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="icon">×</div>
        <h1>Payment Cancelled</h1>
        <p>You cancelled the payment. Your items are still available for checkout.</p>

        <div class="btnrow">
            <a class="btn" href="{{ route('payment.checkout') }}">Try Again</a>
            <a class="btn secondary" href="{{ url('/') }}">Back to Home</a>
        </div>

        <div class="note">
            You can return to checkout anytime and choose another payment method.
        </div>
    </div>
</div>
</body>
</html>