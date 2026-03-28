<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        table { border-collapse: collapse; width: 100%; margin-top: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
        .row { display:flex; gap: 14px; margin-top: 16px; align-items: center; }
        .btn { padding: 10px 14px; border: 1px solid #111; background: #111; color: #fff; cursor: pointer; }
        .btn-outline { background:#fff; color:#111; }
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .success { background: #eaffea; border: 1px solid #72d572; }
        .error { background: #ffecec; border: 1px solid #ff9090; }
        input { padding: 8px; width: 100%; max-width: 420px; }
        label { display:block; margin-top: 10px; }
        .box { border:1px solid #ddd; padding:16px; border-radius:10px; margin-top: 16px; }
        .hint { font-size: 12px; color: #666; margin-top: 6px; }
    </style>
</head>
<body>

<h1>Checkout</h1>

@if(session('success'))
    <div class="msg success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="msg error">{{ session('error') }}</div>
@endif

<div class="box">
    <h3>Order Summary</h3>

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
        @foreach($cart as $item)
            <tr>
                <td>
                    <strong>{{ $item['name'] }}</strong><br>
                    <small>{{ $item['category'] }} {{ $item['unit'] ? '• '.$item['unit'] : '' }}</small>
                </td>
                <td>{{ strtoupper($item['price_type']) }}</td>
                <td>₱{{ number_format($item['unit_price'], 2) }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>₱{{ number_format($item['subtotal'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p><strong>Total Items:</strong> {{ $summary['items_count'] }}</p>
    <p><strong>Total Amount:</strong> ₱{{ number_format($summary['total'], 2) }}</p>
</div>

<div class="box">
    <h3>Customer Details</h3>

    {{-- ✅ IMPORTANT: enctype added for file upload --}}
    <form method="POST" action="{{ route('checkout.place') }}" enctype="multipart/form-data">
        @csrf

        <label for="customer_name">Full Name *</label>
        <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>

        <label for="customer_email">Email (optional)</label>
        <input id="customer_email" name="customer_email" value="{{ old('customer_email') }}">

        {{-- ✅ REQUIRED ZIP UPLOAD --}}
        <label for="print_zip">Upload Print Files (ZIP) *</label>
        <input id="print_zip" type="file" name="print_zip" required accept=".zip">

        <div class="hint">
            Upload ONE <strong>.zip</strong> file containing all print-ready files before placing your order.
        </div>

        @if($errors->any())
            <div class="msg error">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <a class="btn btn-outline" href="{{ route('cart.index') }}" style="text-decoration:none;">
                Back to Cart
            </a>

            <button class="btn" type="submit">
                Place Order
            </button>
        </div>
    </form>
</div>

</body>
</html>
