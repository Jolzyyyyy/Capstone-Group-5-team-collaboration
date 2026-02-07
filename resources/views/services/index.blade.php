<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 6px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .card h3 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        .category {
            font-size: 13px;
            color: #777;
            margin-bottom: 10px;
        }

        .price {
            font-weight: bold;
            margin: 5px 0;
        }

        .controls {
            margin-top: 10px;
        }

        .controls select,
        .controls input {
            width: 100%;
            padding: 6px;
            margin-bottom: 8px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-cart {
            background: #e60023;
            color: #fff;
        }

        .btn-cart:hover {
            background: #c4001d;
        }

        .top-links {
            margin-bottom: 20px;
        }

        .top-links a {
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Printing & Services</h1>

<div class="top-links">
    <a href="{{ route('cart.index') }}">🛒 View Cart</a>
</div>

@if($services->count() === 0)
    <p>No services available.</p>
@else

<div class="grid">

@foreach($services as $service)

    <div class="card">

        {{-- Service Image --}}
        @if($service->image_path)
            <img src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->name }}">
        @else
            <img src="https://via.placeholder.com/300x160?text=Service" alt="No image">
        @endif

        {{-- Service Info --}}
        <h3>{{ $service->name }}</h3>
        <div class="category">{{ $service->category }}</div>

        <div class="price">
            Retail: ₱{{ number_format($service->retail_price, 2) }}
        </div>
        <div class="price">
            Bulk: ₱{{ number_format($service->bulk_price, 2) }}
        </div>

        {{-- ADD TO CART FORM --}}
        <form method="POST" action="{{ route('cart.add', $service->id) }}">
            @csrf

            <div class="controls">
                {{-- Price Type --}}
                <label>
                    Price Type
                    <select name="price_type">
                        <option value="retail">Retail</option>
                        <option value="bulk">Bulk</option>
                    </select>
                </label>

                {{-- Quantity --}}
                <label>
                    Quantity
                    <input type="number" name="qty" min="1" value="1">
                </label>
            </div>

            <button type="submit" class="btn btn-cart">
                ➕ Add to Cart
            </button>
        </form>

    </div>

@endforeach

</div>

{{-- Pagination --}}
<div style="margin-top:20px;">
    {{ $services->links() }}
</div>

@endif

</body>
</html>
