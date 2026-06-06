<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial; padding: 20px;">

<h1>My Cart</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

@if(empty($items))
    <p>Your cart is empty.</p>
    <p><a href="{{ route('services.index') }}">Browse Services</a></p>
@else

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Service</th>
            <th>Price Type</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Line Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>
                <strong>{{ $item['name'] }}</strong><br>
                <small>{{ $item['category'] }} {{ $item['unit'] ? '• '.$item['unit'] : '' }}</small>
            </td>

            <td>
                <form method="POST" action="{{ route('cart.update', $item['service_id']) }}">
                    @csrf
                    <select name="price_type">
                        <option value="retail" {{ $item['price_type']=='retail' ? 'selected' : '' }}>Retail</option>
                        <option value="bulk" {{ $item['price_type']=='bulk' ? 'selected' : '' }}>Bulk</option>
                    </select>
            </td>

            <td>₱{{ number_format($item['price'], 2) }}</td>

            <td>
                    <input type="number" name="qty" min="1" value="{{ $item['qty'] }}" style="width:70px;">
            </td>

            <td>₱{{ number_format($item['line_total'], 2) }}</td>

            <td>
                    <button type="submit">Update</button>
                </form>

                <form method="POST" action="{{ route('cart.remove', $item['service_id']) }}" style="margin-top:5px;">
                    @csrf
                    <button type="submit">Remove</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<h2>Total: ₱{{ number_format($total, 2) }}</h2>

<form method="POST" action="{{ route('cart.clear') }}">
    @csrf
    <button type="submit">Clear Cart</button>
</form>

<p style="margin-top: 15px;">
    <a href="{{ route('services.index') }}">Continue Shopping</a>
</p>

<p style="margin-top: 15px;">
    <a href="{{ route('checkout.index') }}">Proceed to Checkout</a>
</p>

@endif

</body>
</html>
