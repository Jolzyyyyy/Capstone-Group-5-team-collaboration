<x-app-layout>
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&family=Poppins:wght@500;600;700&display=swap">
@endonce

@php
    $isAdminView = request()->routeIs('admin.orders.show');
    $items = $order->items ?? collect();
    $total = (float) ($order->total_price ?? $order->total_amount ?? $order->total ?? 0);
    $status = ucwords(str_replace(['_', '-'], ' ', (string) ($order->status ?? 'Pending Payment')));
@endphp

<style>
.od-page{background:#fff;color:#111827;font-family:'Inter',system-ui,sans-serif;min-height:calc(100vh - 70px)}
.od-wrap{max-width:1180px;margin:0 auto}.od-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:18px}.od-title-wrap{display:flex;gap:10px}.od-title-wrap:before{content:'';width:18px;height:4px;margin-top:8px;border-radius:999px;background:#ff7a00}.od-title{margin:0;font-family:'Playfair Display',Georgia,serif;font-size:40px;line-height:1.15;color:#111827}.od-sub{margin:2px 0 0;color:#6b7280;font-size:12px}.od-btn{height:40px;min-width:126px;border:1px solid #ff7a00;border-radius:10px;background:#ff7a00;color:#000!important;padding:0 16px;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:.18s}.od-btn:hover,.od-btn:focus{background:#111827!important;border-color:#111827!important;color:#fff!important;outline:0}.od-btn.secondary{background:#fff;color:#111827!important;border-color:#111827}.od-grid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:18px;align-items:start}.od-stack{display:grid;gap:16px}.od-card{background:#fff;border:1px solid #111827;border-radius:14px;box-shadow:0 12px 30px rgba(15,23,42,.07);overflow:hidden}.od-body{padding:17px}.od-card-title{margin:0 0 10px;font-family:'Poppins',system-ui,sans-serif;font-size:15px;font-weight:700}.od-info{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.od-info-box{border:1px solid #e5e7eb;border-radius:12px;padding:13px;background:#fff}.od-label{display:block;color:#6b7280;font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.04em}.od-value{display:block;margin-top:5px;font-weight:800;font-size:14px}.od-pill{display:inline-flex;align-items:center;border-radius:999px;padding:6px 10px;background:#fff3e6;color:#ff7a00;font-size:10px;font-weight:900}.od-table{width:100%;border-collapse:collapse}.od-table th{height:42px;background:#fafafa;text-align:left;padding:0 14px;color:#64748b;font-size:10.5px;font-weight:900}.od-table td{padding:13px 14px;border-top:1px solid #f1f5f9;font-size:12px}.od-muted{color:#6b7280;font-size:11px;line-height:1.45}.od-total-row{display:flex;justify-content:space-between;gap:12px;border-top:1px solid #f1f5f9;padding-top:12px;margin-top:12px;font-weight:900}.od-total-row strong{color:#ff7a00;font-size:20px}.od-progress{display:grid;grid-template-columns:repeat(5,1fr);position:relative;margin-top:10px}.od-progress:before{content:'';position:absolute;left:8%;right:8%;top:14px;height:2px;background:#e5e7eb}.od-step{position:relative;z-index:1;text-align:center}.od-dot{width:28px;height:28px;margin:0 auto 6px;border-radius:50%;border:2px solid #d1d5db;background:#fff;display:grid;place-items:center;color:#9ca3af;font-size:10px;font-weight:900}.od-step.done .od-dot,.od-step.current .od-dot{background:#ff7a00;border-color:#ff7a00;color:#fff}.od-step span{font-size:9.5px;color:#6b7280;font-weight:800}.od-files a{margin-top:9px}.od-empty{padding:18px;border:1px dashed #d1d5db;border-radius:12px;color:#6b7280;font-size:12px;text-align:center}.od-actions{display:flex;gap:10px;flex-wrap:wrap}.od-summary-line{display:flex;justify-content:space-between;gap:12px;margin:9px 0;color:#374151;font-size:12px}.od-summary-line strong{color:#111827}@media(max-width:980px){.od-grid,.od-info{grid-template-columns:1fr}.od-head{display:grid}.od-btn{width:100%}}
</style>

<div class="od-page">
  <div class="od-wrap">
    <div class="od-head">
      <div class="od-title-wrap">
        <div>
          <h1 class="od-title">Order Details</h1>
          <p class="od-sub">{{ $isAdminView ? 'Admin order record' : 'My Orders / Order tracking details' }} for #ORD-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
      </div>
      <div class="od-actions">
        <a class="od-btn secondary" href="{{ $isAdminView ? route('admin.orders.index') : route('my-orders') }}"><i class="fa-solid fa-arrow-left"></i>{{ $isAdminView ? 'Back to Orders' : 'Back to My Orders' }}</a>
        @if($isAdminView && !auth()->user()?->isAdminClient())
          <a class="od-btn" href="{{ route('admin.orders.edit', $order) }}"><i class="fa-solid fa-pen"></i>Edit Status</a>
        @endif
      </div>
    </div>

    <div class="od-grid">
      <main class="od-stack">
        <section class="od-card">
          <div class="od-body">
            <h2 class="od-card-title">Order Tracking</h2>
            <div class="od-info">
              <div class="od-info-box"><span class="od-label">Order ID</span><span class="od-value">#ORD-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</span></div>
              <div class="od-info-box"><span class="od-label">Status</span><span class="od-pill">{{ $status }}</span></div>
              <div class="od-info-box"><span class="od-label">Date</span><span class="od-value">{{ optional($order->created_at)->format('M d, Y h:i A') }}</span></div>
            </div>
            @php
              $key = strtolower((string) ($order->status ?? 'pending'));
              $step = str_contains($key,'deliver') || str_contains($key,'complete') ? 5 : (str_contains($key,'ship') || str_contains($key,'transit') ? 4 : (str_contains($key,'production') || str_contains($key,'process') ? 3 : 1));
            @endphp
            <div class="od-progress">
              @foreach(['Placed','Confirmed','In Production','Shipped','Delivered'] as $label)
                <div class="od-step {{ $loop->iteration < $step ? 'done' : ($loop->iteration === $step ? 'current' : '') }}"><div class="od-dot">{{ $loop->iteration }}</div><span>{{ $label }}</span></div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="od-card">
          <div class="od-body">
            <h2 class="od-card-title">Order Items</h2>
            @if($items->count() === 0)
              <div class="od-empty">No items found for this order.</div>
            @else
              <table class="od-table">
                <thead><tr><th>Service</th><th>Price Type</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>
                @foreach($items as $item)
                  <tr>
                    <td><strong>{{ $item->service_name ?? ($item->service->name ?? 'Service') }}</strong><br><span class="od-muted">Service ID: {{ $item->service_id }}</span></td>
                    <td>{{ strtoupper($item->price_type ?? 'retail') }}</td>
                    <td>₱{{ number_format((float) ($item->unit_price ?? 0), 2) }}</td>
                    <td>{{ $item->quantity ?? 1 }}</td>
                    <td><strong>₱{{ number_format((float) ($item->subtotal ?? 0), 2) }}</strong></td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </section>

        <section class="od-card">
          <div class="od-body od-files">
            <h2 class="od-card-title">Uploaded Files</h2>
            @if(!isset($order->files) || $order->files->count() === 0)
              <div class="od-empty">No attached file found for this order.</div>
            @else
              @foreach($order->files as $file)
                <a class="od-btn secondary" href="{{ \Illuminate\Support\Facades\Storage::url($file->path) }}" target="_blank"><i class="fa-solid fa-download"></i>{{ $file->original_name }}</a>
              @endforeach
            @endif
          </div>
        </section>
      </main>

      <aside class="od-stack">
        <section class="od-card">
          <div class="od-body">
            <h2 class="od-card-title">Customer Info</h2>
            <div class="od-summary-line"><span>Name</span><strong>{{ $order->customer_name ?? auth()->user()?->name }}</strong></div>
            <div class="od-summary-line"><span>Email</span><strong>{{ $order->customer_email ?? auth()->user()?->email ?? '-' }}</strong></div>
            @if($isAdminView)
              <div class="od-summary-line"><span>User ID</span><strong>{{ $order->user_id ?? '-' }}</strong></div>
            @else
              <p class="od-muted">This order belongs to your customer account.</p>
            @endif
          </div>
        </section>
        <section class="od-card">
          <div class="od-body">
            <h2 class="od-card-title">Payment Summary</h2>
            <div class="od-summary-line"><span>Subtotal</span><strong>₱{{ number_format(max(0, $total - 250 - ($total * .12)), 2) }}</strong></div>
            <div class="od-summary-line"><span>Shipping</span><strong>₱250.00</strong></div>
            <div class="od-summary-line"><span>Tax (12%)</span><strong>₱{{ number_format($total * .12, 2) }}</strong></div>
            <div class="od-total-row"><span>Total Amount</span><strong>₱{{ number_format($total, 2) }}</strong></div>
          </div>
        </section>
      </aside>
    </div>
  </div>
</div>
</x-app-layout>
