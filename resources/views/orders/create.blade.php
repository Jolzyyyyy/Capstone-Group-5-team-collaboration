<h1>Create New Order</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('orders.store') }}">
    @csrf

    <div>
        <label>Customer Name</label><br>
        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
    </div>
    <br>

    <div>
        <label>Customer Email (optional)</label><br>
        <input type="email" name="customer_email" value="{{ old('customer_email') }}">
    </div>
    <br>

    <div>
        <label>Service Type</label><br>
        <input type="text" name="service_type" value="{{ old('service_type') }}" required>
    </div>
    <br>

    <div>
        <label>Quantity</label><br>
        <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required>
    </div>
    <br>

    <button type="submit">Save Order</button>
</form>

<br>
<a href="{{ route('orders.index') }}">Back to Orders</a>
