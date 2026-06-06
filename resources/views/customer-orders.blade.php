<x-app-layout>
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap">
@endonce

@php
    $orderRows = isset($orders)
        ? (method_exists($orders, 'getCollection') ? $orders->getCollection() : collect($orders))
        : collect();

    $statusKey = fn($status) => strtolower(str_replace([' ', '_'], '-', (string) ($status ?: 'pending-payment')));
    $moneyValue = fn($order) => (float) ($order->total_price ?? $order->total_amount ?? $order->total ?? $order->amount ?? 0);
    $displayStatus = function ($status) {
        $status = trim((string) ($status ?: 'Pending Payment'));
        return $status === '' ? 'Pending Payment' : ucwords(str_replace(['_', '-'], ' ', $status));
    };
    $statusCount = fn($keys) => $orderRows->filter(fn($o) => in_array($statusKey($o->status ?? ''), $keys, true))->count();
    $fallbackProducts = ['Custom T-Shirt','Photo Mug','Canvas Tote Bag','Embroidered Cap','Wall Poster','Custom Hoodie','Phone Case'];
    $productVisual = function ($name, $status = '') {
        $text = strtolower((string) $name . ' ' . (string) $status);
        return match (true) {
            str_contains($text, 'photo'), str_contains($text, 'id') => ['icon' => 'fa-regular fa-id-card', 'tone' => 'blue'],
            str_contains($text, 'shirt'), str_contains($text, 'hoodie') => ['icon' => 'fa-solid fa-shirt', 'tone' => 'dark'],
            str_contains($text, 'mug'), str_contains($text, 'cup') => ['icon' => 'fa-solid fa-mug-hot', 'tone' => 'purple'],
            str_contains($text, 'bag'), str_contains($text, 'tote') => ['icon' => 'fa-solid fa-bag-shopping', 'tone' => 'green'],
            str_contains($text, 'cap'), str_contains($text, 'hat') => ['icon' => 'fa-solid fa-hat-cowboy', 'tone' => 'orange'],
            str_contains($text, 'poster'), str_contains($text, 'canvas') => ['icon' => 'fa-regular fa-image', 'tone' => 'yellow'],
            str_contains($text, 'case') => ['icon' => 'fa-solid fa-mobile-screen-button', 'tone' => 'red'],
            default => ['icon' => 'fa-regular fa-file-lines', 'tone' => 'orange'],
        };
    };
    $selectedOrder = $orderRows->first();
    $safeOrderItems = function ($order) {
        if (!$order) return collect();
        try { return $order->items ?? collect(); } catch (\Throwable $e) { return collect(); }
    };
    $productName = function ($order, $index = 0) use ($safeOrderItems, $fallbackProducts) {
        $item = $safeOrderItems($order)->first();
        return $item?->service?->name
            ?? $item?->service_name
            ?? $item?->product_name
            ?? $fallbackProducts[$index % count($fallbackProducts)];
    };
    $productMeta = function ($order) use ($safeOrderItems) {
        $item = $safeOrderItems($order)->first();
        return $item?->description
            ?? $item?->variation_name
            ?? $item?->paper_size
            ?? $item?->service_option
            ?? 'Custom Print Service';
    };
    $itemCount = function ($order) use ($safeOrderItems) {
        $items = $safeOrderItems($order);
        return max(1, (int) ($order->items_count ?? $items->count() ?: 1));
    };
    $statusClass = function ($status) use ($statusKey) {
        $key = $statusKey($status);
        return match (true) {
            str_contains($key, 'production'), str_contains($key, 'processing') => 'production',
            str_contains($key, 'ship'), str_contains($key, 'transit') => 'shipped',
            str_contains($key, 'deliver'), str_contains($key, 'complete') => 'delivered',
            str_contains($key, 'cancel'), str_contains($key, 'reject') => 'cancelled',
            default => 'pending',
        };
    };
    $statusStep = function ($status) use ($statusClass) {
        return match ($statusClass($status)) {
            'pending' => 1,
            'production' => 3,
            'shipped' => 4,
            'delivered' => 5,
            'cancelled' => 1,
            default => 1,
        };
    };
    $orderPayload = $orderRows->values()->map(function ($order, $index) use ($productName, $productMeta, $itemCount, $moneyValue, $displayStatus, $statusClass, $statusStep, $productVisual) {
        $name = $productName($order, $index);
        $visual = $productVisual($name, $order->status ?? '');
        return [
            'id' => $order->id,
            'ref' => '#ORD-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
            'product' => $name,
            'meta' => $productMeta($order),
            'items' => $itemCount($order),
            'date' => optional($order->created_at)->format('M d, Y'),
            'time' => optional($order->created_at)->format('h:i A'),
            'amount' => $moneyValue($order),
            'amountText' => '₱' . number_format($moneyValue($order), 2),
            'status' => $displayStatus($order->status ?? 'Pending Payment'),
            'statusClass' => $statusClass($order->status ?? ''),
            'step' => $statusStep($order->status ?? ''),
            'icon' => $visual['icon'],
            'tone' => $visual['tone'],
            'url' => route('my-orders.show', $order->id),
        ];
    });
@endphp

<style>
:root{--co-orange:#ff7a00;--co-ink:#111827;--co-muted:#6b7280;--co-line:#111827;--co-soft:#f7f7f8;--co-shadow:0 12px 30px rgba(15,23,42,.07);--co-shadow2:0 18px 42px rgba(15,23,42,.11);--co-radius:14px;--co-green:#16a34a;--co-blue:#2563eb;--co-purple:#7c3aed;--co-red:#ef4444;--co-yellow:#f59e0b}
.co-page{background:#fff;color:var(--co-ink);font-family:'Inter',system-ui,sans-serif;font-weight:400;letter-spacing:0;min-height:calc(100vh - 70px)}
.co-wrap{max-width:1490px;margin:0 auto}.co-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin:0 0 16px}.co-title-wrap{display:flex;align-items:flex-start;gap:10px}.co-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:var(--co-orange);flex:0 0 auto}.co-title{margin:0 0 3px;font-family:'Playfair Display',Georgia,serif;font-size:40px;font-weight:700;line-height:1.2;letter-spacing:-.02em;color:#111827}.co-sub{margin:0;color:var(--co-muted);font-size:12px;font-weight:400;line-height:1.45}.co-head-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-wrap:wrap}.co-date{height:42px;min-width:178px;padding:0 15px;border:1px solid #111827;border-radius:8px;background:#fff;color:#111827;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;line-height:1;white-space:nowrap}.co-date i{font-size:15px}
.co-btn{height:40px;min-width:124px;border:1px solid var(--co-orange);border-radius:10px;background:var(--co-orange);color:#000!important;padding:0 16px;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;letter-spacing:.014em;cursor:pointer;transition:.18s;text-decoration:none;position:relative;z-index:2}.co-btn:hover,.co-btn:focus{background:#111827!important;border-color:#111827!important;color:#fff!important;box-shadow:0 12px 24px rgba(17,24,39,.20);outline:0}.co-icon-btn{width:34px;height:34px;border:1px solid #dfe3ea;border-radius:10px;background:#fff;color:#111827;display:inline-grid;place-items:center;cursor:pointer;transition:.18s}.co-icon-btn:hover,.co-icon-btn:focus{background:#111827;border-color:#111827;color:#fff}
.co-card{background:#fff;border:1px solid #111827;border-radius:var(--co-radius);box-shadow:var(--co-shadow);transition:background .18s ease,box-shadow .18s ease,border-color .18s ease;overflow:hidden}.co-card:hover{background:rgba(17,24,39,.10);box-shadow:var(--co-shadow2);border-color:#111827}.co-body{padding:15px 17px}.co-card-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:14.5px;font-weight:600;letter-spacing:.022em;line-height:1.35;color:#111827}.co-card-desc{margin:5px 0 0;color:var(--co-muted);font-size:11.5px;line-height:1.5}
.co-stats{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin-bottom:16px}.co-stat{min-height:104px;padding:15px;display:grid;align-content:start;gap:8px;text-align:left;cursor:pointer}.co-stat:hover,.co-stat.active{background:#fff7ed;transform:translateY(-1px)}button.co-stat{font:inherit;color:inherit}.co-stat-top{display:flex;align-items:center;gap:10px}.co-stat i,.co-mini-icon{width:34px;height:34px;border-radius:11px;display:grid;place-items:center}.co-stat .tone-orange,.co-mini-icon{background:#fff3e6;color:var(--co-orange)}.co-stat .tone-blue,.co-mini-icon.blue{background:#eaf2ff;color:var(--co-blue)}.co-stat .tone-purple,.co-mini-icon.purple{background:#f2ebff;color:var(--co-purple)}.co-stat .tone-green,.co-mini-icon.green{background:#eaf8ef;color:var(--co-green)}.co-stat .tone-red,.co-mini-icon.red{background:#fff0f0;color:var(--co-red)}.co-stat strong{display:block;font-family:'Poppins',system-ui,sans-serif;font-size:22px;font-weight:600;line-height:1}.co-stat span{font-size:11px;color:var(--co-muted)}
.co-layout{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:22px;align-items:start}.co-left,.co-right{display:grid;gap:12px;align-content:start}
.co-section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;margin:0 0 -1px;position:relative;z-index:3}.co-section-copy{min-width:0;padding-bottom:7px}.co-section-titleline{display:flex;align-items:center;gap:9px}.co-section-hint{display:block;margin-top:3px;color:var(--co-muted);font-size:11px}.co-section-tools{display:grid;grid-template-columns:112px 134px 92px;gap:7px;align-items:center;max-width:352px;flex:0 0 352px}.co-toolbar{display:grid;grid-template-columns:minmax(0,1fr);gap:10px;align-items:center;margin-bottom:9px}.co-input,.co-select{height:38px;border:1px solid #dfe3ea;border-radius:10px;background:#fff;color:#111827;padding:0 11px;font-size:11.5px;font-weight:500;outline:0}.co-input:focus,.co-select:focus{border-color:var(--co-orange);box-shadow:0 0 0 4px rgba(255,122,0,.11)}
.co-table{width:100%;border-collapse:collapse;background:#fff}.co-table th{height:44px;background:#fafafa;text-align:left;padding:0 14px;color:#64748b;font-size:10.5px;font-weight:800;letter-spacing:.04em}.co-table td{padding:12px 14px;border-top:1px solid #f1f5f9;color:#111827;font-size:12px;vertical-align:middle}.co-table tr{transition:.18s}.co-table tbody tr{cursor:pointer}.co-table tbody tr:hover,.co-table tbody tr.active{background:rgba(17,24,39,.10)}.co-check{width:15px;height:15px;accent-color:var(--co-orange)}
.co-product{display:flex;align-items:center;gap:12px;min-width:230px}.co-thumb{width:52px;height:52px;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:20px;flex:0 0 auto}.co-tone-dark{background:linear-gradient(135deg,#111827,#ff7a00)}.co-tone-blue{background:linear-gradient(135deg,#2563eb,#0ea5e9)}.co-tone-purple{background:linear-gradient(135deg,#7c3aed,#a855f7)}.co-tone-green{background:linear-gradient(135deg,#16a34a,#22c55e)}.co-tone-orange{background:linear-gradient(135deg,#f97316,#f59e0b)}.co-tone-yellow{background:linear-gradient(135deg,#f59e0b,#facc15)}.co-tone-red{background:linear-gradient(135deg,#ef4444,#fb7185)}.co-ref,.co-product-name{font-family:'Poppins',system-ui,sans-serif;font-weight:600}.co-product-meta,.co-small{display:block;margin-top:4px;color:#6b7280;font-size:10.5px;line-height:1.35}.co-amount{font-weight:800}
.co-pill{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 9px;background:#fff3e6;color:var(--co-orange);font-size:10px;font-weight:800;white-space:nowrap}.co-pill.production{background:#f2ebff;color:var(--co-purple)}.co-pill.shipped{background:#eaf2ff;color:var(--co-blue)}.co-pill.delivered{background:#eaf8ef;color:var(--co-green)}.co-pill.cancelled{background:#fff0f0;color:var(--co-red)}
.co-actions-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:9px}.co-action{border:1px solid #dfe3ea;border-radius:12px;background:#fff;padding:8px;min-height:58px;display:grid;grid-template-columns:28px minmax(0,1fr) 10px;gap:7px;align-items:center;text-align:left;cursor:pointer;transition:.18s}.co-action:hover,.co-action:focus{background:rgba(17,24,39,.10);border-color:#111827;outline:0}.co-action strong{font-family:'Poppins',system-ui,sans-serif;font-size:10.5px;font-weight:600}.co-action small{display:block;margin-top:1px;color:#6b7280;font-size:9px;line-height:1.25}
.co-selected{display:grid;gap:13px}.co-selected-top{display:grid;grid-template-columns:106px minmax(0,1fr);gap:13px}.co-selected-img{width:106px;height:106px;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:38px}.co-selected-title{margin:0;font-family:'Poppins',system-ui,sans-serif;font-size:15px;font-weight:600}.co-price-line{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}.co-selected-price{font-size:22px;font-weight:800}.co-progress{display:grid;grid-template-columns:repeat(5,1fr);gap:0;position:relative;margin:4px 0 2px}.co-progress:before{content:'';position:absolute;left:7%;right:7%;top:13px;height:2px;background:#e5e7eb}.co-progress-fill{position:absolute;left:7%;top:13px;height:2px;background:var(--co-orange);width:var(--progress,0);max-width:86%}.co-step{position:relative;z-index:1;text-align:center}.co-step-dot{width:26px;height:26px;border-radius:50%;border:2px solid #d1d5db;background:#fff;margin:0 auto 5px;display:grid;place-items:center;color:#9ca3af;font-size:9px;font-weight:800}.co-step.done .co-step-dot,.co-step.current .co-step-dot{border-color:var(--co-orange);background:var(--co-orange);color:#fff}.co-step-label{display:block;color:#6b7280;font-size:9px;font-weight:700}.co-filter-empty{display:none;text-align:center;padding:34px 14px;color:#6b7280}.co-filter-empty.show{display:block}.co-filter-empty i{font-size:36px;color:#cbd5e1;margin-bottom:10px}
.co-breakdown{display:grid;gap:9px}.co-break-row{display:flex;justify-content:space-between;gap:12px;color:#374151;font-size:11.5px}.co-break-row strong{color:#111827}.co-pagination{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px;color:#6b7280;font-size:11px}.co-toast{position:fixed;left:50%;top:110px;z-index:10000;transform:translate(-50%,-12px);opacity:0;background:#111827;color:#fff;border-radius:13px;padding:13px 16px;box-shadow:0 18px 50px rgba(17,24,39,.25);font-size:12px;font-weight:700;transition:.18s;pointer-events:none}.co-toast.show{opacity:1;transform:translate(-50%,0)}
.co-empty{text-align:center;padding:40px 18px}.co-empty i{font-size:46px;color:#cbd5e1;margin-bottom:12px}
.co-page{--co-radius:8px}.co-page .co-card{border:1px solid #111827;border-radius:8px;box-shadow:none;background:#fff}.co-page .co-card:hover{background:#fff;box-shadow:none}.co-page .co-card-title{letter-spacing:0}.co-page .co-btn{border:0!important;border-radius:8px;background:var(--co-orange);color:#111827!important;font-weight:600;letter-spacing:0;box-shadow:none!important}.co-page .co-btn:hover,.co-page .co-btn:focus{background:#111827!important;color:#fff!important}.co-page .co-icon-btn{border:0;border-radius:8px;box-shadow:none}.co-page .co-layout{align-items:stretch}.co-page .co-left,.co-page .co-right{height:100%}.co-page .co-stat{border-color:var(--stat-border,#111827);background:var(--stat-soft,#fff);box-shadow:none}.co-page .co-stat:hover,.co-page .co-stat.active{background:var(--stat-soft,#fff);transform:none}.co-page .co-stat[data-stat-card=all]{--stat-border:var(--co-orange);--stat-soft:#fff7ed}.co-page .co-stat[data-stat-card=pending],.co-page .co-stat[data-stat-card=shipped]{--stat-border:var(--co-blue);--stat-soft:#eff6ff}.co-page .co-stat[data-stat-card=production]{--stat-border:var(--co-purple);--stat-soft:#f5f0ff}.co-page .co-stat[data-stat-card=delivered]{--stat-border:var(--co-green);--stat-soft:#effaf3}.co-page .co-stat[data-stat-card=cancelled]{--stat-border:var(--co-red);--stat-soft:#fff1f1}.co-page .co-table tbody tr:hover,.co-page .co-table tbody tr.active{background:#fff3e6}.co-page .co-right .co-card:first-child{display:none!important}.co-page .co-right .co-card:last-child{height:100%;display:flex;flex-direction:column}.co-page #selectedOrderPanel{height:100%;display:flex;flex-direction:column}.co-page #selectedOrderPanel .co-breakdown{margin-top:auto}.co-page .co-action{border:0;border-radius:8px;background:#fff}.co-page .co-action:hover,.co-page .co-action:focus{background:#fff3e6}.co-page .co-toast{border:1px solid #111827;border-radius:10px;font-family:'Poppins',system-ui,sans-serif;font-weight:600;letter-spacing:0;box-shadow:none}
@media(max-width:1320px){.co-layout{grid-template-columns:1fr}.co-stats{grid-template-columns:repeat(3,minmax(0,1fr))}.co-right{grid-template-columns:repeat(2,minmax(0,1fr))}.co-right .co-card:last-child{grid-column:1/-1}}
@media(max-width:900px){.co-head,.co-section-head{display:grid}.co-stats,.co-toolbar,.co-section-tools,.co-right,.co-actions-grid{grid-template-columns:1fr}.co-scroll{overflow:auto}.co-table{min-width:860px}.co-btn{width:100%}}
.co-page .co-action-row{display:flex;justify-content:flex-end;margin:-2px 0 16px}
@media(max-width:900px){.co-page .co-action-row{justify-content:stretch}.co-page .co-action-row .co-btn{width:100%}}
</style>

<div class="co-page">
<div class="co-wrap">
    <div class="co-head">
        <div class="co-title-wrap"><div><h1 class="co-title">My Orders</h1><p class="co-sub">Track and manage all your custom print orders.</p></div></div>
        <div class="co-head-actions">
            <div class="co-date"><i class="fa-regular fa-calendar-days"></i><span>Today is {{ now()->format('M d, Y') }}</span></div>
        </div>
    </div>
    <div class="co-action-row">
        <a href="{{ route('services.index') }}" class="co-btn" onclick="window.location.href=this.href;return false;"><i class="fa-solid fa-plus"></i>New Order</a>
    </div>

    <div class="co-stats">
        <button type="button" class="co-card co-stat active" data-stat-card="all" onclick="applyStatFilter('all')"><div class="co-stat-top"><i class="fa-solid fa-layer-group tone-orange"></i><span>Total Orders</span></div><strong>{{ $orders?->total() ?? $orderRows->count() }}</strong><span>All time orders</span></button>
        <button type="button" class="co-card co-stat" data-stat-card="pending" onclick="applyStatFilter('pending')"><div class="co-stat-top"><i class="fa-regular fa-credit-card tone-blue"></i><span>Pending Payment</span></div><strong>{{ $statusCount(['pending','pending-payment','unpaid']) }}</strong><span>Awaiting payment</span></button>
        <button type="button" class="co-card co-stat" data-stat-card="production" onclick="applyStatFilter('production')"><div class="co-stat-top"><i class="fa-solid fa-gears tone-purple"></i><span>In Production</span></div><strong>{{ $statusCount(['processing','in-production','production','approved']) }}</strong><span>Being printed</span></button>
        <button type="button" class="co-card co-stat" data-stat-card="shipped" onclick="applyStatFilter('shipped')"><div class="co-stat-top"><i class="fa-solid fa-truck-fast tone-blue"></i><span>Shipped</span></div><strong>{{ $statusCount(['shipped','out-for-delivery','in-transit','ready']) }}</strong><span>On the way</span></button>
        <button type="button" class="co-card co-stat" data-stat-card="delivered" onclick="applyStatFilter('delivered')"><div class="co-stat-top"><i class="fa-regular fa-circle-check tone-green"></i><span>Delivered</span></div><strong>{{ $statusCount(['delivered','completed']) }}</strong><span>Completed</span></button>
        <button type="button" class="co-card co-stat" data-stat-card="cancelled" onclick="applyStatFilter('cancelled')"><div class="co-stat-top"><i class="fa-regular fa-circle-xmark tone-red"></i><span>Cancelled</span></div><strong>{{ $statusCount(['cancelled','canceled','rejected']) }}</strong><span>Cancelled orders</span></button>
    </div>

    <div class="co-layout">
        <main class="co-left">
            <div class="co-section-head">
                <div class="co-section-copy">
                    <div class="co-section-titleline"><h2 class="co-card-title" id="ordersSectionTitle">Orders</h2><span class="co-pill" id="ordersSectionCount">{{ $orders?->total() ?? $orderRows->count() }} visible</span></div>
                    <span class="co-section-hint" id="ordersSectionHint">Showing all order tracking records for your account.</span>
                </div>
                <div class="co-section-tools">
                    <select id="statusFilter" class="co-select"><option value="all">All Status</option><option value="pending">Pending Payment</option><option value="production">In Production</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option></select>
                    <input id="dateFilter" class="co-input" type="text" value="{{ now()->startOfMonth()->format('M j') }} - {{ now()->format('M j, Y') }}" aria-label="Date range">
                    <button class="co-btn" type="button" onclick="exportOrderSummary()"><i class="fa-solid fa-download"></i>Export</button>
                </div>
            </div>
            <section class="co-card">
                <div class="co-body">
                    <div class="co-toolbar">
                        <input id="orderSearch" class="co-input" type="search" placeholder="Search orders, products, or order IDs...">
                    </div>
                    @if($orderRows->count() > 0)
                    <div class="co-scroll">
                        <table class="co-table" id="ordersTable">
                            <thead><tr><th><input class="co-check" type="checkbox" onclick="toggleAllOrders(this)"></th><th>Order ID</th><th>Product</th><th>Date</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                            @foreach($orderPayload as $row)
                                <tr data-order-row data-index="{{ $loop->index }}" data-status="{{ $row['statusClass'] }}" data-search="{{ strtolower($row['ref'].' '.$row['product'].' '.$row['meta'].' '.$row['status']) }}">
                                    <td><input class="co-check" type="checkbox" onclick="event.stopPropagation()"></td>
                                    <td><span class="co-ref">{{ $row['ref'] }}</span><span class="co-small">{{ $row['items'] }} {{ \Illuminate\Support\Str::plural('item', $row['items']) }}</span></td>
                                    <td><div class="co-product"><span class="co-thumb co-tone-{{ $row['tone'] }}"><i class="{{ $row['icon'] }}"></i></span><div><span class="co-product-name">{{ $row['product'] }}</span><span class="co-product-meta">{{ $row['meta'] }}</span></div></div></td>
                                    <td>{{ $row['date'] }}<span class="co-small">{{ $row['time'] }}</span></td>
                                    <td><span class="co-amount">{{ $row['amountText'] }}</span></td>
                                    <td><span class="co-pill {{ $row['statusClass'] }}">{{ $row['status'] }}</span></td>
                                    <td><button class="co-icon-btn" type="button" onclick="event.stopPropagation();selectOrder({{ $loop->index }})"><i class="fa-regular fa-eye"></i></button> <a class="co-icon-btn" href="{{ $row['url'] }}" onclick="event.stopPropagation()"><i class="fa-solid fa-ellipsis-vertical"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="co-filter-empty" id="filterEmptyState"><i class="fa-solid fa-route"></i><h2 class="co-card-title">No matching order tracking yet.</h2><p class="co-card-desc">This status has no visible order record for your account right now.</p></div>
                    <div class="co-pagination">
                        <span>Showing {{ $orders->firstItem() ?? 1 }} to {{ $orders->lastItem() ?? $orderRows->count() }} of {{ $orders->total() ?? $orderRows->count() }} orders</span>
                        @if(method_exists($orders, 'hasPages') && $orders->hasPages())<span>{{ $orders->links() }}</span>@endif
                    </div>
                    @else
                    <div class="co-empty"><i class="fa-solid fa-box-open"></i><h2 class="co-card-title">No orders yet.</h2><p class="co-card-desc">Start a print job to see your orders, status, payment, and progress here.</p><div style="margin-top:16px"><a href="{{ route('services.index') }}" class="co-btn">Create Order</a></div></div>
                    @endif
                </div>
            </section>
        </main>

        <aside class="co-right">
            <section class="co-card">
                <div class="co-body">
                    <h2 class="co-card-title"><i class="fa-solid fa-bolt" style="color:var(--co-orange);margin-right:8px"></i>Quick Actions</h2>
                    <div class="co-actions-grid">
                        <button class="co-action" type="button" onclick="focusOrderSearch()"><span class="co-mini-icon blue"><i class="fa-solid fa-magnifying-glass"></i></span><span><strong>Track Order</strong><small>Check delivery status</small></span><i class="fa-solid fa-chevron-right"></i></button>
                        <button class="co-action" type="button" onclick="exportOrderSummary()"><span class="co-mini-icon green"><i class="fa-solid fa-download"></i></span><span><strong>Download Invoice</strong><small>Get order invoice</small></span><i class="fa-solid fa-chevron-right"></i></button>
                        <button class="co-action" type="button" onclick="location.href='{{ route('services.index') }}'"><span class="co-mini-icon purple"><i class="fa-solid fa-rotate-right"></i></span><span><strong>Reorder</strong><small>Order again quickly</small></span><i class="fa-solid fa-chevron-right"></i></button>
                        <button class="co-action" type="button" onclick="location.href='{{ route('help-center') }}#support-ticket'"><span class="co-mini-icon red"><i class="fa-solid fa-headset"></i></span><span><strong>Contact Support</strong><small>Get help from team</small></span><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </section>

            <section class="co-card">
                <div class="co-body co-selected" id="selectedOrderPanel">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px"><h2 class="co-card-title">Selected Order</h2><span class="co-pill" id="selectedRef">{{ $selectedOrder ? '#ORD-' . str_pad((string) $selectedOrder->id, 5, '0', STR_PAD_LEFT) : '#ORD-00000' }}</span></div>
                    <div class="co-selected-top">
                        <div class="co-selected-img co-tone-{{ $selectedOrder ? $productVisual($productName($selectedOrder, 0), $selectedOrder->status ?? '')['tone'] : 'orange' }}" id="selectedIconBox"><i id="selectedIcon" class="{{ $selectedOrder ? $productVisual($productName($selectedOrder, 0), $selectedOrder->status ?? '')['icon'] : 'fa-regular fa-file-lines' }}"></i></div>
                        <div><h3 class="co-selected-title" id="selectedProduct">{{ $selectedOrder ? $productName($selectedOrder, 0) : 'No selected order' }}</h3><p class="co-card-desc" id="selectedMeta">{{ $selectedOrder ? $productMeta($selectedOrder) : 'Choose an order to preview details.' }}</p><p class="co-card-desc" id="selectedDate">{{ $selectedOrder ? optional($selectedOrder->created_at)->format('M d, Y, h:i A') : '' }}</p></div>
                    </div>
                    <div class="co-price-line"><span class="co-selected-price" id="selectedAmount">{{ $selectedOrder ? '₱' . number_format($moneyValue($selectedOrder), 2) : '₱0.00' }}</span><span class="co-pill {{ $selectedOrder ? $statusClass($selectedOrder->status ?? '') : '' }}" id="selectedStatus">{{ $selectedOrder ? $displayStatus($selectedOrder->status ?? '') : 'No Order' }}</span></div>
                    <div class="co-progress" id="selectedProgress" style="--progress:{{ $selectedOrder ? (($statusStep($selectedOrder->status ?? '') - 1) * 21.5) : 0 }}%">
                        <span class="co-progress-fill"></span>
                        @foreach(['Placed','Confirmed','In Production','Shipped','Delivered'] as $step)
                        <span class="co-step {{ $selectedOrder && $loop->iteration < $statusStep($selectedOrder->status ?? '') ? 'done' : ($selectedOrder && $loop->iteration === $statusStep($selectedOrder->status ?? '') ? 'current' : '') }}"><span class="co-step-dot">{{ $loop->iteration }}</span><span class="co-step-label">{{ $step }}</span></span>
                        @endforeach
                    </div>
                    <div class="co-breakdown">
                        <h3 class="co-card-title" style="font-size:13px">Price Breakdown</h3>
                        <div class="co-break-row"><span>Subtotal</span><strong id="selectedSubtotal">{{ $selectedOrder ? '₱' . number_format(max(0, $moneyValue($selectedOrder) - 250 - ($moneyValue($selectedOrder) * .12)), 2) : '₱0.00' }}</strong></div>
                        <div class="co-break-row"><span>Shipping Fee</span><strong>₱250.00</strong></div>
                        <div class="co-break-row"><span>Tax (12%)</span><strong id="selectedTax">{{ $selectedOrder ? '₱' . number_format($moneyValue($selectedOrder) * .12, 2) : '₱0.00' }}</strong></div>
                        <div class="co-break-row"><span>Total Amount</span><strong id="selectedTotal" style="color:var(--co-orange)">{{ $selectedOrder ? '₱' . number_format($moneyValue($selectedOrder), 2) : '₱0.00' }}</strong></div>
                    </div>
                    <button id="selectedDetailsLink" type="button" data-href="{{ $selectedOrder ? route('my-orders.show', $selectedOrder->id) : '' }}" class="co-btn" style="width:100%" onclick="openSelectedDetails()"><i class="fa-regular fa-eye"></i>View Order Details</button>
                </div>
            </section>
        </aside>
    </div>
</div>
<div id="orderToast" class="co-toast">Ready.</div>
</div>

<script>
const orderData=@json($orderPayload);
function orderToast(msg){const t=document.getElementById('orderToast');if(!t)return;t.textContent=msg;t.classList.add('show');clearTimeout(window.orderToastTimer);window.orderToastTimer=setTimeout(()=>t.classList.remove('show'),2200)}
function peso(value){return '₱'+Number(value||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}
const statusMeta={all:{title:'Orders',hint:'Showing all order tracking records for your account.'},pending:{title:'Pending Payment Orders',hint:'Orders waiting for payment confirmation and next processing step.'},production:{title:'In Production Orders',hint:'Orders currently approved, queued, or being printed.'},shipped:{title:'Shipped Orders',hint:'Orders already dispatched or moving through delivery.'},delivered:{title:'Delivered Orders',hint:'Completed orders with finished delivery tracking.'},cancelled:{title:'Cancelled Orders',hint:'Cancelled or rejected order records for review.'}};
function updateSectionHeader(status,count){const meta=statusMeta[status]||statusMeta.all;setText('ordersSectionTitle',meta.title);setText('ordersSectionHint',meta.hint);setText('ordersSectionCount',count+' visible');document.querySelectorAll('[data-stat-card]').forEach(card=>card.classList.toggle('active',card.dataset.statCard===status))}
function filterOrders(selectFirst=false){const q=(document.getElementById('orderSearch')?.value||'').toLowerCase();const s=document.getElementById('statusFilter')?.value||'all';let count=0,firstVisible=null;document.querySelectorAll('[data-order-row]').forEach(row=>{const okText=row.dataset.search.includes(q);const okStatus=s==='all'||row.dataset.status===s;const show=okText&&okStatus;row.style.display=show?'':'none';if(show){count++;if(!firstVisible)firstVisible=row}});updateSectionHeader(s,count);document.getElementById('filterEmptyState')?.classList.toggle('show',count===0);if(selectFirst){firstVisible?selectOrder(Number(firstVisible.dataset.index)):clearSelectedOrder(s)}orderToast(count+' order'+(count===1?'':'s')+' visible.')}
function applyStatFilter(status){const filter=document.getElementById('statusFilter');if(filter)filter.value=status;filterOrders(true);orderToast(status==='all'?'Showing all orders.':'Showing '+(statusMeta[status]?.title||status)+' records.')}
function setText(id,value){const el=document.getElementById(id);if(el)el.textContent=value}
function selectOrder(index){const order=orderData[index];if(!order)return;document.querySelectorAll('[data-order-row]').forEach(row=>row.classList.toggle('active',Number(row.dataset.index)===Number(index)));setText('selectedRef',order.ref);setText('selectedProduct',order.product);setText('selectedMeta',order.meta+' · '+order.items+' item'+(order.items===1?'':'s'));setText('selectedDate',(order.date||'')+', '+(order.time||''));setText('selectedAmount',order.amountText);setText('selectedStatus',order.status);const status=document.getElementById('selectedStatus');if(status){status.className='co-pill '+order.statusClass}const iconBox=document.getElementById('selectedIconBox');if(iconBox){iconBox.className='co-selected-img co-tone-'+(order.tone||'orange')}const icon=document.getElementById('selectedIcon');if(icon){icon.className=order.icon||'fa-regular fa-file-lines'}const progress=document.getElementById('selectedProgress');if(progress){progress.style.setProperty('--progress',((order.step-1)*21.5)+'%');progress.querySelectorAll('.co-step').forEach((step,i)=>{step.classList.toggle('done',i+1<order.step);step.classList.toggle('current',i+1===order.step)})}setText('selectedSubtotal',peso(Math.max(0,order.amount-250-(order.amount*.12))));setText('selectedTax',peso(order.amount*.12));setText('selectedTotal',order.amountText);const link=document.getElementById('selectedDetailsLink');if(link)link.dataset.href=order.url;localStorage.setItem('printify_selected_order',JSON.stringify(order));orderToast(order.ref+' selected.')}
function clearSelectedOrder(status){const meta=statusMeta[status]||statusMeta.all;document.querySelectorAll('[data-order-row]').forEach(row=>row.classList.remove('active'));setText('selectedRef','No order');setText('selectedProduct',meta.title);setText('selectedMeta','No order tracking record is available under this status.');setText('selectedDate','');setText('selectedAmount',peso(0));setText('selectedStatus','No Match');const statusEl=document.getElementById('selectedStatus');if(statusEl)statusEl.className='co-pill';const iconBox=document.getElementById('selectedIconBox');if(iconBox)iconBox.className='co-selected-img co-tone-orange';const icon=document.getElementById('selectedIcon');if(icon)icon.className='fa-solid fa-route';const progress=document.getElementById('selectedProgress');if(progress){progress.style.setProperty('--progress','0%');progress.querySelectorAll('.co-step').forEach(step=>{step.classList.remove('done','current')})}setText('selectedSubtotal',peso(0));setText('selectedTax',peso(0));setText('selectedTotal',peso(0));const link=document.getElementById('selectedDetailsLink');if(link)link.dataset.href=''}
function openSelectedDetails(){const link=document.getElementById('selectedDetailsLink'),href=link?.dataset?.href;if(!href){orderToast('Please select an order first.');return}window.location.href=href}
function toggleAllOrders(input){document.querySelectorAll('tbody .co-check').forEach(cb=>cb.checked=input.checked);orderToast(input.checked?'Visible orders selected.':'Selection cleared.')}
function focusOrderSearch(){const el=document.getElementById('orderSearch');if(el){el.focus();el.select()}orderToast('Search an order ID or product.')}
function exportOrderSummary(){const rows=[...document.querySelectorAll('[data-order-row]')].filter(r=>r.style.display!=='none').map(r=>orderData[Number(r.dataset.index)]).filter(Boolean);localStorage.setItem('printify_last_order_export',JSON.stringify({rows,createdAt:new Date().toISOString()}));orderToast('Order summary prepared in this browser.')}
document.getElementById('orderSearch')?.addEventListener('input',()=>filterOrders(true));document.getElementById('statusFilter')?.addEventListener('change',()=>filterOrders(true));document.getElementById('dateFilter')?.addEventListener('change',()=>orderToast('Date range noted for this view.'));
document.querySelectorAll('[data-order-row]').forEach(row=>row.addEventListener('click',()=>selectOrder(Number(row.dataset.index))));
if(orderData.length){filterOrders(true)}else{updateSectionHeader('all',0);clearSelectedOrder('all')}
</script>
</x-app-layout>
