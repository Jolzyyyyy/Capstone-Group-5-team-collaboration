<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #f7f4ef;
            color: #22201f;
            font-family: Arial, sans-serif;
        }
        a { color: inherit; text-decoration: none; }
        .top {
            border-bottom: 1px solid #eadfd2;
            background: #fff;
        }
        .top-inner, .page {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }
        .top-inner {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            padding: 30px 0;
        }
        .eyebrow {
            margin: 0;
            color: #ff8d2a;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        h1 {
            margin: 8px 0 0;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1;
        }
        .subtitle {
            margin: 10px 0 0;
            max-width: 680px;
            color: #6f675f;
            font-size: 14px;
            line-height: 1.6;
        }
        .page {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 22px;
            padding: 30px 0 42px;
        }
        .stack { display: grid; gap: 18px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            background: #ff8d2a;
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .11em;
            padding: 12px 16px;
            text-transform: uppercase;
            transition: background .15s ease, border-color .15s ease;
        }
        .btn:hover { background: #ff6a00; }
        .btn.secondary {
            border: 1px solid #eadfd2;
            background: #fff;
            color: #22201f;
        }
        .btn.secondary:hover {
            border-color: #ffb970;
            background: #fff8ef;
        }
        .btn.danger {
            border: 1px solid #fecdd3;
            background: #fff1f2;
            color: #be123c;
        }
        .btn.danger:hover { background: #ffe4e6; }
        .message {
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 700;
        }
        .message.success {
            border: 1px solid #bbf7d0;
            background: #ecfdf3;
            color: #166534;
        }
        .message.error {
            border: 1px solid #fecdd3;
            background: #fff1f2;
            color: #be123c;
        }
        .empty, .summary, .cart-card {
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(34, 32, 31, .04);
        }
        .empty {
            padding: 42px;
            text-align: center;
        }
        .empty h2 { margin: 0; }
        .empty p { color: #6f675f; }
        .cart-card {
            overflow: hidden;
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
        }
        .image-pane {
            background: #f3eee7;
            padding: 16px;
        }
        .image-pane img, .image-placeholder {
            width: 100%;
            height: 190px;
            border-radius: 8px;
            object-fit: cover;
        }
        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #d8ccbd;
            background: #fff;
            color: #8a6d52;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .card-body { padding: 20px; }
        .card-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px;
        }
        .service-name {
            margin: 4px 0 0;
            font-size: 25px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .meta {
            margin: 8px 0 0;
            color: #6f675f;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
        }
        .file-status {
            margin-top: 12px;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.4;
        }
        .file-status.ready {
            border: 1px solid #bbf7d0;
            background: #ecfdf3;
            color: #166534;
        }
        .file-status.missing {
            border: 1px solid #fed7aa;
            background: #fff7ed;
            color: #9a3412;
        }
        .line-total {
            min-width: 142px;
            border-radius: 8px;
            background: #fff8ef;
            padding: 12px;
            text-align: right;
        }
        .line-total span {
            display: block;
            color: #8a6d52;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .line-total strong {
            display: block;
            margin-top: 3px;
            font-size: 20px;
        }
        .edit-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) .8fr .7fr auto;
            gap: 14px;
            align-items: end;
            margin-top: 20px;
        }
        label {
            display: block;
            color: #6f675f;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .11em;
            text-transform: uppercase;
        }
        select, input[type="number"] {
            width: 100%;
            margin-top: 8px;
            border: 1px solid #d8ccbd;
            border-radius: 8px;
            background: #fff;
            color: #22201f;
            font-size: 14px;
            font-weight: 700;
            padding: 12px;
        }
        input[type="number"] { text-align: center; }
        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
            border-top: 1px solid #f0e5d8;
            padding-top: 16px;
        }
        .summary {
            position: sticky;
            top: 18px;
            height: fit-content;
            padding: 20px;
        }
        .summary h2 {
            margin: 4px 0 0;
            font-size: 22px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding: 12px 0;
            color: #6f675f;
            font-size: 14px;
            font-weight: 700;
        }
        .row strong { color: #22201f; }
        .row.total {
            border-top: 1px solid #f0e5d8;
            color: #22201f;
            font-weight: 900;
        }
        .row.total strong { font-size: 22px; }
        .pay-note {
            margin: 14px 0 0;
            border: 1px solid #d9ecff;
            border-radius: 8px;
            background: #eef8ff;
            padding: 14px;
        }
        .pay-note p {
            margin: 4px 0 0;
            color: #31516f;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.45;
        }
        .full { width: 100%; }
        @media (max-width: 900px) {
            .top-inner, .page { width: min(100% - 24px, 680px); }
            .top-inner, .card-head { align-items: stretch; grid-template-columns: 1fr; }
            .top-inner, .page, .cart-card, .edit-grid {
                display: grid;
                grid-template-columns: 1fr;
            }
            .line-total { text-align: left; }
            .summary { position: static; }
        }
    </style>
</head>
<body>
@php
    $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
    $hasMissingFiles = collect($items)->contains(fn ($item) => empty($item['attached_file']['path'] ?? null));
@endphp

<header class="top">
    <div class="top-inner">
        <div>
            <p class="eyebrow">Cart</p>
            <h1>Review service selections</h1>
            <p class="subtitle">
                Update package, pricing, and quantity on the same service transaction before checkout.
            </p>
        </div>
        <a href="{{ route('services.index') }}" class="btn secondary">Continue Shopping</a>
    </div>
</header>

<main class="page">
    <section class="stack">
        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="message error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(empty($items))
            <section class="empty">
                <h2>Your cart is empty.</h2>
                <p>Browse services and add a package to start a transaction.</p>
                <a href="{{ route('services.index') }}" class="btn">Browse Services</a>
            </section>
        @else
            @foreach($items as $item)
                @php
                    $imagePath = $item['image_path'] ?? null;
                    $image = $imagePath ? asset('storage/' . $imagePath) : null;
                    $selectedVariationId = (int) ($item['variation_id'] ?? 0);
                @endphp

                <section class="cart-card">
                    <div class="image-pane">
                        @if($image)
                            <img src="{{ $image }}" alt="{{ $item['name'] }}">
                        @else
                            <div class="image-placeholder">Service</div>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="card-head">
                            <div>
                                <p class="eyebrow">{{ $item['category'] ?? 'Service' }}</p>
                                <h2 class="service-name">{{ $item['name'] }}</h2>
                                <p class="meta">
                                    {{ $item['service_item_id'] }}
                                    @if(!empty($item['variation_label']))
                                        / {{ $item['variation_label'] }}
                                    @endif
                                </p>
                                @if(!empty($item['attached_file']['path'] ?? null))
                                    <div class="file-status ready">
                                        Attached file: {{ $item['attached_file']['original_name'] ?? 'Print file' }}
                                    </div>
                                @else
                                    <div class="file-status missing">
                                        File required before checkout. Open this service and attach a print-ready file before adding it back to cart.
                                    </div>
                                @endif
                            </div>
                            <div class="line-total">
                                <span>Line Total</span>
                                <strong>{{ $money($item['line_total']) }}</strong>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('cart.update', $item['cart_key']) }}" class="edit-grid">
                            @csrf
                            <div>
                                <label>Package / Variant</label>
                                <select name="service_variation_id">
                                    @forelse($item['available_variations'] as $variation)
                                        <option value="{{ $variation['id'] }}" {{ (int) $variation['id'] === $selectedVariationId ? 'selected' : '' }}>
                                            {{ $variation['label'] }} ({{ $variation['service_item_id'] }}) - {{ $variation['description'] }}
                                        </option>
                                    @empty
                                        <option value="{{ $item['variation_id'] }}">{{ $item['variation_label'] ?? $item['service_item_id'] }}</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label>Price Type</label>
                                <select name="price_type">
                                    <option value="retail" {{ $item['price_type'] === 'retail' ? 'selected' : '' }}>Retail</option>
                                    <option value="bulk" {{ $item['price_type'] === 'bulk' ? 'selected' : '' }}>Bulk</option>
                                </select>
                            </div>

                            <div>
                                <label>Quantity</label>
                                <input type="number" name="qty" min="1" max="999" value="{{ $item['qty'] }}">
                            </div>

                            <button type="submit" class="btn">Update</button>
                        </form>

                        <div class="card-actions">
                            @if(!empty($item['service_id']))
                                <a href="{{ route('services.show', $item['service_id']) }}" class="btn secondary">Edit Like Add to Cart</a>
                            @endif

                            <form method="POST" action="{{ route('cart.remove', $item['cart_key']) }}">
                                @csrf
                                <button type="submit" class="btn danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </section>
            @endforeach
        @endif
    </section>

    <aside class="summary">
        <p class="eyebrow">Transaction Summary</p>
        <h2>Current Cart</h2>

        <div class="row">
            <span>Service transactions</span>
            <strong>{{ count($items) }}</strong>
        </div>
        <div class="row total">
            <span>Subtotal</span>
            <strong>{{ $money($total) }}</strong>
        </div>

        @if(!empty($items))
            <div class="pay-note" style="{{ $hasMissingFiles ? 'border-color:#fed7aa;background:#fff7ed;' : '' }}">
                <p class="eyebrow" style="color:{{ $hasMissingFiles ? '#9a3412' : '#2563eb' }};">{{ $hasMissingFiles ? 'File Required' : 'Next Step' }}</p>
                <p>{{ $hasMissingFiles ? 'Every service must have an attached print-ready file before checkout can continue.' : 'Checkout will save this cart as one order, then continue to PayMongo hosted checkout.' }}</p>
            </div>

            @if($hasMissingFiles)
                <button type="button" class="btn full" style="margin-top:16px; opacity:.55; cursor:not-allowed;" disabled>Attach Files First</button>
            @else
                <a href="{{ route('checkout.index') }}" class="btn full" style="margin-top:16px;">Proceed to Checkout</a>
            @endif

            <form method="POST" action="{{ route('cart.clear') }}" style="margin-top:10px;">
                @csrf
                <button type="submit" class="btn secondary full">Clear Cart</button>
            </form>
        @endif
    </aside>
</main>
</body>
</html>
