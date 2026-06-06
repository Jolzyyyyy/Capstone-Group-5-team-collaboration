<x-app-layout>
    @php
        $money = fn ($value) => 'PHP ' . number_format((float) $value, 2);
        $customer = auth()->user();
        $serviceSubtotal = (float) ($summary['total'] ?? 0);
        $releaseFee = 0;
        $grandTotal = $serviceSubtotal + $releaseFee;
        $itemCount = (int) ($summary['items_count'] ?? count($cart));
        $releaseWindow = now()->addDay()->format('M d') . ' - ' . now()->addDays(2)->format('M d');
        $paymongoConfigured = $paymongoConfigured ?? filled(trim((string) config('services.paymongo.secret_key')));
    @endphp

    <style>
        .checkout-page {
            min-height: calc(100vh - 4rem);
            background: #f7f4ef;
            color: #22201f;
            font-family: Arial, sans-serif;
        }

        .checkout-header {
            border-bottom: 1px solid #eadfd2;
            background: #fff;
        }

        .checkout-shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .checkout-head-inner {
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

        .checkout-title {
            margin: 8px 0 0;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 900;
            line-height: 1;
        }

        .checkout-subtitle {
            max-width: 680px;
            margin: 10px 0 0;
            color: #6f675f;
            font-size: 14px;
            line-height: 1.6;
        }

        .checkout-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .checkout-btn {
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
            text-decoration: none;
            text-transform: uppercase;
            transition: background .15s ease, border-color .15s ease;
        }

        .checkout-btn:hover {
            background: #ff6a00;
        }

        .checkout-btn:disabled,
        .checkout-btn.is-disabled {
            background: #9ca3af;
            color: #f8fafc;
            cursor: not-allowed;
            opacity: .82;
        }

        .checkout-btn:disabled:hover,
        .checkout-btn.is-disabled:hover {
            background: #9ca3af;
        }

        .checkout-btn.secondary {
            border: 1px solid #eadfd2;
            background: #fff;
            color: #22201f;
        }

        .checkout-btn.secondary:hover {
            border-color: #ffb970;
            background: #fff8ef;
        }

        .checkout-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 22px;
            padding: 30px 0 42px;
        }

        .checkout-stack {
            display: grid;
            gap: 18px;
        }

        .checkout-card {
            overflow: hidden;
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(34, 32, 31, .04);
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 1px solid #f0e5d8;
            padding: 18px 20px;
        }

        .card-kicker {
            margin: 0;
            color: #ff8d2a;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .card-title {
            margin: 5px 0 0;
            font-size: 20px;
            font-weight: 900;
        }

        .card-body {
            padding: 20px;
        }

        .required-pill,
        .soft-pill {
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .required-pill {
            background: #fff8ef;
            color: #9a3412;
        }

        .soft-pill {
            background: #eef8ff;
            color: #2563eb;
        }

        .message {
            border-radius: 8px;
            margin-bottom: 14px;
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

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label,
        .choice-label {
            display: block;
            color: #6f675f;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .checkout-input,
        .checkout-textarea {
            width: 100%;
            margin-top: 8px;
            border: 1px solid #d8ccbd;
            border-radius: 8px;
            background: #fff;
            color: #22201f;
            font-size: 14px;
            font-weight: 700;
            min-height: 44px;
            padding: 11px 12px;
        }

        .checkout-textarea {
            min-height: 92px;
            resize: vertical;
        }

        .checkout-input:focus,
        .checkout-textarea:focus {
            border-color: #ffb970;
            box-shadow: 0 0 0 3px rgba(255, 141, 42, .14);
            outline: none;
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 8px;
        }

        .choice-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            padding: 13px;
        }

        .choice-card input {
            margin-top: 3px;
        }

        .choice-card strong {
            display: block;
            font-size: 14px;
            font-weight: 900;
        }

        .choice-card span span {
            display: block;
            margin-top: 4px;
            color: #6f675f;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.4;
        }

        .service-list {
            display: grid;
            gap: 14px;
        }

        .service-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 150px;
            gap: 16px;
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff;
            padding: 16px;
        }

        .service-name {
            margin: 4px 0 0;
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .service-meta {
            margin: 8px 0 0;
            color: #6f675f;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
        }

        .file-pill {
            display: inline-flex;
            margin-top: 12px;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            background: #ecfdf3;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.4;
            padding: 9px 11px;
        }

        .line-total {
            align-self: start;
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

        .release-box,
        .paymongo-box,
        .notice-box {
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff8ef;
            color: #6f675f;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.6;
            padding: 16px;
        }

        .release-box strong,
        .paymongo-box strong,
        .notice-box strong {
            display: block;
            color: #22201f;
            font-size: 16px;
            font-weight: 900;
        }

        .notice-box {
            margin-top: 16px;
            border-color: #fed7aa;
            background: #fff7ed;
            color: #9a3412;
        }

        .paymongo-box.is-disabled {
            border-color: #fecaca;
            background: #fff1f2;
            color: #991b1b;
        }

        .paymongo-box.is-disabled strong {
            color: #7f1d1d;
        }

        .confirmation-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 14px;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            background: #fff;
            padding: 13px;
        }

        .confirmation-card input {
            margin-top: 4px;
        }

        .confirmation-card span {
            color: #6f675f;
            display: block;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.5;
        }

        .confirmation-card strong {
            color: #22201f;
        }

        .checkout-summary {
            position: sticky;
            top: 86px;
        }

        .summary-card {
            border: 1px solid #eadfd2;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(34, 32, 31, .04);
            overflow: hidden;
        }

        .summary-body {
            display: grid;
            gap: 13px;
            padding: 18px;
        }

        .summary-row,
        .summary-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: #6f675f;
            font-size: 14px;
            font-weight: 800;
        }

        .summary-row strong {
            color: #22201f;
        }

        .summary-total {
            border-top: 1px solid #eadfd2;
            color: #22201f;
            margin-top: 5px;
            padding-top: 16px;
            font-size: 18px;
            font-weight: 900;
        }

        .summary-total strong {
            color: #ff6a00;
            font-size: 24px;
        }

        .summary-note {
            border-radius: 8px;
            background: #ecfdf3;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.45;
            padding: 12px;
        }

        .pay-note {
            margin: 0;
            color: #6f675f;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.5;
            text-align: center;
        }

        .checkout-mobile-submit {
            display: none;
        }

        @media (max-width: 980px) {
            .checkout-head-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .checkout-actions {
                justify-content: flex-start;
            }

            .checkout-body {
                grid-template-columns: 1fr;
            }

            .checkout-summary {
                position: static;
            }
        }

        @media (max-width: 720px) {
            .checkout-shell {
                width: min(100% - 24px, 1180px);
            }

            .field-grid,
            .choice-grid,
            .service-row {
                grid-template-columns: 1fr;
            }

            .line-total {
                text-align: left;
            }

            .checkout-actions {
                width: 100%;
            }

            .checkout-actions .checkout-btn {
                flex: 1 1 auto;
            }

            .checkout-body {
                padding-bottom: 104px;
            }

            .checkout-mobile-submit {
                display: block;
                position: fixed;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 30;
                border-top: 1px solid #eadfd2;
                background: #fff;
                box-shadow: 0 -10px 22px rgba(34, 32, 31, .08);
                padding: 12px;
            }
        }
    </style>

    <div class="checkout-page">
        <header class="checkout-header">
            <div class="checkout-shell checkout-head-inner">
                <div>
                    <p class="eyebrow">Secure Checkout</p>
                    <h1 class="checkout-title">Review your order</h1>
                    <p class="checkout-subtitle">
                        Confirm your service items, contact details, delivery option, and payment method in one checkout review.
                    </p>
                </div>

                <div class="checkout-actions">
                    <a href="{{ route('cart.index') }}" class="checkout-btn secondary">Back to Cart</a>
                    <a href="{{ route('services.index') }}" class="checkout-btn secondary">Add Services</a>
                </div>
            </div>
        </header>

        <div class="checkout-shell checkout-body">
            <div class="checkout-stack">
                @if (session('success'))
                    <div class="message success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="message error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="message error">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="checkoutForm" method="POST" action="{{ route('checkout.place') }}" class="checkout-stack">
                    @csrf

                    <section class="checkout-card">
                        <div class="card-head">
                            <div>
                                <p class="card-kicker">Customer Details</p>
                                <h2 class="card-title">Contact and release information</h2>
                            </div>
                            <span class="required-pill">Required</span>
                        </div>

                        <div class="card-body">
                            <div class="field-grid">
                                <div class="field">
                                    <label for="customer_name">Full Name</label>
                                    <input id="customer_name" name="customer_name" value="{{ old('customer_name', $customer?->name) }}" required class="checkout-input" autocomplete="name">
                                </div>

                                <div class="field">
                                    <label for="customer_email">Email</label>
                                    <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', $customer?->email) }}" required class="checkout-input" autocomplete="email">
                                </div>

                                <div class="field">
                                    <label for="customer_phone">Phone Number</label>
                                    <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required class="checkout-input" autocomplete="tel" placeholder="+63 9XX XXX XXXX">
                                </div>

                                <div class="field">
                                    <span class="choice-label">Release Option</span>
                                    <div class="choice-grid">
                                        <label class="choice-card">
                                            <input type="radio" name="fulfillment_method" value="pickup" @checked(old('fulfillment_method', 'pickup') === 'pickup')>
                                            <span>
                                                <strong>Pickup</strong>
                                                <span>Claim at store after staff review.</span>
                                            </span>
                                        </label>

                                        <label class="choice-card">
                                            <input type="radio" name="fulfillment_method" value="delivery" @checked(old('fulfillment_method') === 'delivery')>
                                            <span>
                                                <strong>Delivery</strong>
                                                <span>Address is required for delivery.</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="field full">
                                    <label for="delivery_address">Pickup / Delivery Address</label>
                                    <textarea id="delivery_address" name="delivery_address" class="checkout-textarea" placeholder="For delivery, enter house/building, street, barangay, city, and landmark. For pickup, you may leave release instructions.">{{ old('delivery_address') }}</textarea>
                                </div>

                                <div class="field full">
                                    <label for="customer_note">Order Note</label>
                                    <textarea id="customer_note" name="customer_note" class="checkout-textarea" placeholder="Add printing instructions, release schedule, or admin-client reminders.">{{ old('customer_note') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="checkout-card">
                        <div class="card-head">
                            <div>
                                <p class="card-kicker">Service Transaction</p>
                                <h2 class="card-title">Attached print files and selected services</h2>
                            </div>
                            <a href="#customer_note" class="checkout-btn secondary">Add Note</a>
                        </div>

                        <div class="card-body">
                            <div class="service-list">
                                @foreach ($cart as $item)
                                    @php
                                        $attachedName = $item['attached_file']['original_name'] ?? 'Print file';
                                    @endphp

                                    <article class="service-row">
                                        <div>
                                            <p class="eyebrow">{{ $item['category'] ?: 'Print Service' }}</p>
                                            <h3 class="service-name">{{ $item['name'] }}</h3>
                                            <p class="service-meta">{{ $item['variation_label'] ?: $item['service_item_id'] }}</p>
                                            <p class="service-meta">
                                                {{ strtoupper($item['price_type']) }} / {{ $money($item['unit_price']) }} per {{ $item['unit'] ?: 'item' }} / Qty {{ $item['qty'] }}
                                            </p>
                                            <p class="file-pill">Attached file: {{ $attachedName }}</p>
                                        </div>

                                        <div class="line-total">
                                            <span>Line Total</span>
                                            <strong>{{ $money($item['subtotal']) }}</strong>
                                        </div>
                                    </article>
                                @endforeach
                            </div>

                            <div class="notice-box">
                                <strong>Print file reminder</strong>
                                The attached file must be the exact final file to print. Please check spelling, size, layout, colors, and resolution before placing the order.
                            </div>

                            <label class="confirmation-card">
                                <input type="checkbox" name="print_file_confirmed" value="1" required @checked(old('print_file_confirmed'))>
                                <span>
                                    <strong>I confirm the attached file is final and print-ready.</strong>
                                    I understand Printify &amp; Co. is not liable for customer-uploaded file errors after checkout and place order.
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="checkout-card">
                        <div class="card-head">
                            <div>
                                <p class="card-kicker">Release Schedule</p>
                                <h2 class="card-title">Estimated pickup or delivery</h2>
                            </div>
                            <span class="soft-pill">No extra fee</span>
                        </div>

                        <div class="card-body">
                            <div class="release-box">
                                <strong>Ready window: {{ $releaseWindow }}</strong>
                                Final schedule depends on file verification, production queue, and payment confirmation.
                            </div>
                        </div>
                    </section>

                    <section class="checkout-card">
                        <div class="card-head">
                            <div>
                                <p class="card-kicker">Payment Method</p>
                                <h2 class="card-title">PayMongo secure checkout</h2>
                            </div>
                            <span class="soft-pill">GCash / Card</span>
                        </div>

                        <div class="card-body">
                            <div class="paymongo-box {{ $paymongoConfigured ? '' : 'is-disabled' }}">
                                @if ($paymongoConfigured)
                                    <strong>Select payment method now.</strong>
                                    After you place the order, PayMongo opens securely for the selected payment mode.
                                @else
                                    <strong>Online payment setup required.</strong>
                                    Paste your real PayMongo test secret key in .env as PAYMONGO_SECRET_KEY, then clear config cache before placing PayMongo orders.
                                @endif
                            </div>

                            <div class="choice-grid" style="margin-top:14px;">
                                <label class="choice-card">
                                    <input type="radio" name="payment_method" value="gcash" @checked(old('payment_method', 'gcash') === 'gcash') @disabled(!$paymongoConfigured)>
                                    <span>
                                        <strong>GCash</strong>
                                        <span>Pay through PayMongo hosted checkout.</span>
                                    </span>
                                </label>

                                <label class="choice-card">
                                    <input type="radio" name="payment_method" value="card" @checked(old('payment_method') === 'card') @disabled(!$paymongoConfigured)>
                                    <span>
                                        <strong>Credit / Debit Card</strong>
                                        <span>Card details are handled by PayMongo.</span>
                                    </span>
                                </label>

                                <label class="choice-card">
                                    <input type="radio" name="payment_method" value="paymaya" @checked(old('payment_method') === 'paymaya') @disabled(!$paymongoConfigured)>
                                    <span>
                                        <strong>Maya</strong>
                                        <span>Use Maya wallet through PayMongo.</span>
                                    </span>
                                </label>

                                <label class="choice-card">
                                    <input type="radio" name="payment_method" value="grab_pay" @checked(old('payment_method') === 'grab_pay') @disabled(!$paymongoConfigured)>
                                    <span>
                                        <strong>GrabPay</strong>
                                        <span>Use GrabPay through PayMongo.</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <div class="checkout-mobile-submit">
                        <button type="submit" class="checkout-btn" style="width: 100%;" @disabled(!$paymongoConfigured)>
                            {{ $paymongoConfigured ? 'Place Order & Pay with PayMongo' : 'Place Order Requires PayMongo Test Key' }}
                        </button>
                    </div>
                </form>
            </div>

            <aside class="checkout-summary">
                <div class="summary-card">
                    <div class="card-head">
                        <div>
                            <p class="card-kicker">Order Summary</p>
                            <h2 class="card-title">Total ({{ $itemCount }} item{{ $itemCount === 1 ? '' : 's' }})</h2>
                        </div>
                    </div>

                    <div class="summary-body">
                        <div class="summary-row">
                            <span>Product subtotal</span>
                            <strong>{{ $money($serviceSubtotal) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>File verification</span>
                            <strong>Included</strong>
                        </div>
                        <div class="summary-row">
                            <span>Release coordination</span>
                            <strong>{{ $money($releaseFee) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Payment</span>
                            <strong>PayMongo</strong>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <strong>{{ $money($grandTotal) }}</strong>
                        </div>

                        <button type="submit" form="checkoutForm" class="checkout-btn" @disabled(!$paymongoConfigured)>
                            {{ $paymongoConfigured ? 'Place Order & Pay with PayMongo' : 'Place Order Requires PayMongo Test Key' }}
                        </button>
                        <p class="pay-note">
                            {{ $paymongoConfigured
                                ? 'This review saves the order and immediately opens PayMongo for the selected payment method.'
                                : 'Online payment will unlock after the real PayMongo test secret key is added to .env and config cache is cleared.' }}
                        </p>
                        <div class="summary-note">All selected services already have required attached files from the service page.</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
