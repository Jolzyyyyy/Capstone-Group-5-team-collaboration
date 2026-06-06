
@php
  $serviceDesign = [
    'Document Printing' => [
      'key' => 'doc',
      'icon' => 'fa-solid fa-print',
      'img' => asset('images/Document PS.png'),
      'time' => 'same-day',
      'express' => true,
      'order' => 1,
      'desc' => 'High-quality black and white or color document printing for any need.',
      'options' => [
        ['slug' => 'text-only', 'name' => 'Text Only', 'desc' => 'Plain black and white document output.', 'price' => 'from <b>₱1.00/page</b>', 'icon' => 'fa-solid fa-file-lines', 'serviceId' => 'DOC-TX-001'],
        ['slug' => 'text-graphics', 'name' => 'Text + Image', 'desc' => 'Documents with images, charts, and simple graphics.', 'price' => 'from <b>₱2.00/page</b>', 'icon' => 'fa-solid fa-chart-simple', 'serviceId' => 'DOC-TWI-004'],
        ['slug' => 'full-color', 'name' => 'Full Color', 'desc' => 'Colored document printing for reports and handouts.', 'price' => 'from <b>₱5.00/page</b>', 'icon' => 'fa-solid fa-palette', 'serviceId' => 'DOC-TX-003'],
      ],
    ],
    'Photocopy & Scanning' => [
      'key' => 'photo',
      'icon' => 'fa-solid fa-copy',
      'img' => asset('images/Photocopy & ScanningS.png'),
      'time' => 'same-day',
      'express' => true,
      'order' => 2,
      'desc' => 'Clear photocopies and high-resolution scanning services.',
      'options' => [
        ['slug' => 'photocopy', 'name' => 'Photocopy', 'desc' => 'Fast and clean document copies.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-copy', 'serviceId' => 'DOC-PCPY-001'],
        ['slug' => 'scanning', 'name' => 'Scanning', 'desc' => 'Digital scans for documents and records.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-magnifying-glass', 'serviceId' => 'DOC-SCN-001'],
      ],
    ],
    'ID & Photo Services' => [
      'key' => 'id',
      'icon' => 'fa-solid fa-id-card',
      'img' => asset('images/Photo IDS.png'),
      'time' => 'same-day',
      'express' => true,
      'order' => 3,
      'desc' => 'Professional ID pictures and photo printing services.',
      'options' => [
        ['slug' => 'visa-photo', 'name' => 'Visa Photo', 'desc' => 'Visa and passport photo preparation.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-passport', 'image' => asset('images/Photo ID (cover).png'), 'serviceId' => 'IDP-PKG-004'],
        ['slug' => 'id-photo', 'name' => 'ID Photo', 'desc' => 'ID photo print package.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-id-card', 'image' => asset('images/Photo ID (cover).png'), 'serviceId' => 'IDP-PKG-001'],
        ['slug' => '2x2-photo', 'name' => '2x2 Photo', 'desc' => '2x2 print photo package.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-image', 'image' => asset('images/Photo ID (cover).png'), 'serviceId' => 'IDP-SP-003'],
      ],
    ],
    'Lamination & Binding' => [
      'key' => 'bind',
      'icon' => 'fa-solid fa-book-open',
      'img' => asset('images/Lamination & BindingS.png'),
      'time' => 'next-day',
      'express' => false,
      'order' => 4,
      'desc' => 'Durable lamination and clean document binding solutions.',
      'options' => [
        ['slug' => 'lamination', 'name' => 'Lamination', 'desc' => 'Protective film for certificates and documents.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-layer-group', 'serviceId' => 'LAM-001'],
        ['slug' => 'spiral-binding', 'name' => 'Spiral Binding', 'desc' => 'Bound reports, manuals, and booklets.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-book-open', 'serviceId' => 'BND-SPR-001'],
      ],
    ],
    'Large Format Printing' => [
      'key' => 'largeformat',
      'icon' => 'fa-solid fa-image',
      'img' => asset('images/Large FormatPS.png'),
      'time' => 'next-day',
      'express' => false,
      'order' => 5,
      'desc' => 'Banners, posters, tarpaulins, and oversized print output.',
      'options' => [
        ['slug' => 'sintra-board', 'name' => 'Sintra Board', 'desc' => 'Rigid signage and presentation board output.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-border-all', 'serviceId' => 'LF-SIN-001'],
        ['slug' => 'tarpaulin', 'name' => 'Tarpaulin', 'desc' => 'Large outdoor and indoor tarpaulin printing.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-panorama', 'serviceId' => 'LFP-TARP-001'],
      ],
    ],
    'Custom Special Printing' => [
      'key' => 'special',
      'icon' => 'fa-solid fa-star',
      'img' => asset('images/Custom Special PS.png'),
      'time' => 'custom',
      'express' => false,
      'order' => 6,
      'desc' => 'Personalized printing for custom designs and special projects.',
      'options' => [
        ['slug' => 'custom-special-printing', 'name' => 'Custom Print', 'desc' => 'Special projects that need manual review.', 'price' => 'Available for <b>inquiry</b>', 'icon' => 'fa-solid fa-star', 'serviceId' => 'CSP-001'],
      ],
    ],
  ];

  $fallbackServices = collect($serviceDesign)->map(function ($meta, $title) {
    return array_merge($meta, [
      'title' => $title,
      'price' => $meta['key'] === 'special' ? 20000 : ($meta['key'] === 'largeformat' ? 2500 : ($meta['key'] === 'bind' ? 500 : ($meta['key'] === 'id' ? 180 : ($meta['key'] === 'photo' ? 120 : 80)))),
    ]);
  })->values();

  $frontServices = isset($services) && $services->count()
    ? $services->values()->map(function ($service, $index) use ($serviceDesign) {
      $meta = $serviceDesign[$service->name] ?? [];
      $key = $meta['key'] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '-', $service->name));
      $fallbackOptions = $meta['options'] ?? [];
      $variationOptions = $service->activeVariations->values()->map(function ($variation, $variationIndex) use ($service, $meta) {
        $label = $variation->variation_label ?: ($variation->printing_category ?: $service->name);
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $label), '-'));

        return [
          'slug' => $slug ?: ('option-' . ($variationIndex + 1)),
          'name' => $label,
          'desc' => collect([$variation->printing_category, $variation->color_mode, $variation->product_size, $variation->finish_type, $variation->package_type])->filter()->implode(' / ') ?: ($service->description ?: 'Choose this printing option.'),
          'price' => 'from <b>₱' . number_format((float) $variation->retail_price, 2) . '</b>',
          'icon' => $meta['icon'] ?? 'fa-solid fa-print',
          'image' => $variation->variation_image_path ? asset($variation->variation_image_path) : ($meta['img'] ?? null),
          'serviceId' => $variation->service_item_id,
        ];
      })->all();

      return [
        'key' => $key,
        'title' => $service->name,
        'desc' => $service->description ?: ($meta['desc'] ?? 'Professional printing service.'),
        'icon' => $meta['icon'] ?? 'fa-solid fa-print',
        'img' => $meta['img'] ?? ($service->image_path ? asset($service->image_path) : null),
        'time' => $meta['time'] ?? 'custom',
        'price' => (float) ($service->retail_price ?: 0),
        'express' => $meta['express'] ?? false,
        'order' => $meta['order'] ?? ($index + 1),
        'options' => count($variationOptions) ? $variationOptions : $fallbackOptions,
      ];
    })->values()
    : $fallbackServices;
@endphp

<section id="products" class="pfsvc" aria-labelledby="pfsvcTitle">
  <div class="pfsvc-feedback" id="pfsvcFeedback" role="status" aria-live="polite">
    <i class="fa-regular fa-circle-check"></i>
    <span>Services ready.</span>
  </div>

  <div class="pfsvc-wrap">
    <aside class="pfsvc-side" aria-label="Browse services">
      <h3>Browse Services</h3>
      <button type="button" class="pfsvc-nav active" data-filter="all"><i class="fa-solid fa-table-cells-large"></i><span>All Services</span></button>
      <button type="button" class="pfsvc-nav" data-filter="doc"><i class="fa-solid fa-print"></i><span>Document Printing</span></button>
      <button type="button" class="pfsvc-nav" data-filter="photo"><i class="fa-solid fa-copy"></i><span>Photocopy &amp; Scanning</span></button>
      <button type="button" class="pfsvc-nav" data-filter="id"><i class="fa-solid fa-id-card"></i><span>ID &amp; Photo Services</span></button>
      <button type="button" class="pfsvc-nav" data-filter="bind"><i class="fa-solid fa-book-open"></i><span>Lamination &amp; Binding</span></button>
      <button type="button" class="pfsvc-nav" data-filter="largeformat"><i class="fa-solid fa-image"></i><span>Large Format Printing</span></button>
      <button type="button" class="pfsvc-nav" data-filter="special"><i class="fa-solid fa-star"></i><span>Custom Special Printing</span></button>

      <div class="pfsvc-filterbox">
        <h3>Filter By</h3>

        <label class="pfsvc-label" for="pfsvcPrice">Price Range</label>
        <div class="pfsvc-price-row"><span>₱0</span><strong id="pfsvcPriceValue">₱20,000+</strong></div>
        <input id="pfsvcPrice" type="range" min="0" max="20000" value="20000" step="100">

        <label class="pfsvc-label" for="pfsvcTime">Turnaround Time</label>
        <select id="pfsvcTime">
          <option value="all">Any Time</option>
          <option value="same-day">Same Day</option>
          <option value="next-day">Next Day</option>
          <option value="custom">Custom</option>
        </select>

        <label class="pfsvc-switch">
          <input id="pfsvcExpress" type="checkbox" checked>
          <span>Show Express Services</span>
          <b>ON</b>
        </label>
      </div>
    </aside>

    <main class="pfsvc-main">
      <div class="pfsvc-head">
        <span>What We Do</span>
        <h2 id="pfsvcTitle">Our Featured Services</h2>
        <p>Professional printing solutions tailored to your business and personal needs.</p>
      </div>

      <div class="pfsvc-tools" aria-label="Service controls">
        <button type="button" class="pfsvc-btn pfsvc-all active" onclick="pfsvcSetCategory('all')">
          <i class="fa-solid fa-table-cells-large"></i>
          <span>All Services</span>
        </button>

        <label class="pfsvc-sort">Sort by:
          <select id="pfsvcSort">
            <option value="popular">Popular</option>
            <option value="name">Name</option>
            <option value="price">Price</option>
          </select>
        </label>

        <button type="button" class="pfsvc-view active" data-view="grid" aria-label="Grid view"><i class="fa-solid fa-grip"></i></button>
        <button type="button" class="pfsvc-view" data-view="list" aria-label="List view"><i class="fa-solid fa-list"></i></button>
      </div>

      <div class="pfsvc-grid" id="pfsvcGrid"></div>

      <div class="pfsvc-empty" id="pfsvcEmpty">
        <i class="fa-solid fa-circle-info"></i>
        <h3>No service matched.</h3>
        <p>Try another filter or contact us for custom printing support.</p>
      </div>

      <div class="pfsvc-callout">
        <div class="pfsvc-callout-icon"><i class="fa-solid fa-truck-fast"></i></div>
        <div>
          <h3>Bulk Order or Regular Printing Needs?</h3>
          <p>Get support for business and bulk printing orders.</p>
        </div>
        <a class="pfsvc-btn" href="/contactus">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <section class="pfsvc-detail" id="pfsvcDetail" hidden>
        <div class="pfsvc-detail-icon" id="pfsvcDetailIcon"><i class="fa-solid fa-print"></i></div>
        <div>
          <span>Selected Service</span>
          <h3 id="pfsvcDetailTitle">Document Printing</h3>
          <p id="pfsvcDetailDesc">Choose a service option to continue.</p>
        </div>
        <button type="button" class="pfsvc-btn" onclick="pfsvcProceedSelected()">Open Details <i class="fa-solid fa-arrow-right"></i></button>
      </section>
    </main>
  </div>
</section>

<div class="pfsvc-deck" id="pfsvcDeck" aria-hidden="true">
  <button type="button" class="pfsvc-deck-close" onclick="pfsvcCloseDeck()" aria-label="Close service options">
    <i class="fa-solid fa-xmark"></i>
  </button>

  <div class="pfsvc-deck-card">
    <div class="pfsvc-deck-title">
      <span id="pfsvcDeckKicker"><i class="fa-solid fa-print"></i> Document Printing</span>
      <h3 id="pfsvcDeckHeading">Choose Service Option</h3>
      <p>Select one card to continue.</p>
    </div>

    <button type="button" class="pfsvc-arrow left" onclick="pfsvcMoveDeck(-1)" aria-label="Previous option">
      <i class="fa-solid fa-chevron-left"></i>
    </button>

    <div class="pfsvc-stage" id="pfsvcStage"></div>

    <button type="button" class="pfsvc-arrow right" onclick="pfsvcMoveDeck(1)" aria-label="Next option">
      <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="pfsvc-deck-actions">
      <div class="pfsvc-dots" id="pfsvcDots"></div>
      <button type="button" class="pfsvc-btn" id="pfsvcContinue" onclick="pfsvcProceedSelected()">Continue <i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Playfair+Display:wght@700&family=Poppins:wght@600&display=swap');

.pfsvc{
  width:100vw;
  margin-left:calc(50% - 50vw);
  padding:34px 0 64px;
  background:#fff;
  color:#111;
  font-family:'Inter',system-ui,sans-serif;
  font-weight:400;
  letter-spacing:0;
  scroll-margin-top:90px;
}

.pfsvc *{
  box-sizing:border-box;
}

.pfsvc-wrap{
  width:min(1280px,calc(100% - 104px));
  margin:0 24px 0 92px;
  display:grid;
  grid-template-columns:290px minmax(0,1fr);
  gap:42px;
  align-items:start;
}

.pfsvc-feedback{
  display:none;
  position:fixed;
  top:76px;
  left:50%;
  z-index:999999;
  transform:translate(-50%,-12px);
  min-width:250px;
  height:38px;
  padding:0 16px;
  border:1px solid #111;
  border-radius:10px;
  background:#111;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  font:600 12px 'Poppins';
  opacity:0;
  pointer-events:none;
  transition:opacity .25s ease,transform .25s ease;
}

.pfsvc-feedback.show{
  opacity:1;
  transform:translate(-50%,0);
}

.pfsvc-feedback i{
  color:#ff7a00;
}

.pfsvc-side{
  position:sticky;
  top:95px;
  background:#fff;
  padding:0 0 18px;
  border-right:0;
}

.pfsvc-side h3{
  margin:0 0 16px;
  color:#111;
  font:600 14px 'Poppins';
  text-transform:uppercase;
}

.pfsvc-nav{
  width:calc(100% - 18px);
  height:40px;
  margin:0 18px 7px 0;
  border:0;
  border-radius:0;
  background:#fff;
  color:#111;
  display:flex;
  align-items:center;
  gap:14px;
  padding:0 16px;
  cursor:pointer;
  font:600 13px 'Poppins';
  text-align:left;
  transition:background-color .2s ease,color .2s ease;
}

.pfsvc-nav i{
  width:18px;
  text-align:center;
  color:#111;
}

.pfsvc-nav:hover,
.pfsvc-nav.active{
  background:#fff1e8;
  color:#ff5a12;
  border-left:3px solid #ff5a12;
}

.pfsvc-nav:hover i,
.pfsvc-nav.active i{
  color:#ff5a12;
}

.pfsvc-filterbox{
  margin-top:24px;
  padding-top:22px;
  border-top:1px solid #111;
}

.pfsvc-label{
  display:block;
  margin:0 0 8px;
  color:#111;
  font:600 13px 'Poppins';
}

.pfsvc-price-row{
  display:flex;
  justify-content:space-between;
  margin-bottom:8px;
  color:#111;
  font-size:11px;
}

.pfsvc-price-row strong{
  font-weight:400;
}

.pfsvc-filterbox input[type=range]{
  width:calc(100% - 18px);
  accent-color:#ff7a00;
}

.pfsvc-filterbox select{
  width:calc(100% - 18px);
  height:38px;
  margin:6px 0 18px;
  padding:0 12px;
  border:1px solid #111;
  border-radius:4px;
  background:#fff;
  color:#111;
  font:400 12px 'Inter';
  outline:none;
}

.pfsvc-switch{
  width:calc(100% - 18px);
  display:grid;
  grid-template-columns:1fr 54px;
  align-items:center;
  gap:10px;
  color:#111;
  font:400 12px 'Inter';
  cursor:pointer;
}

.pfsvc-switch input{
  display:none;
}

.pfsvc-switch b{
  height:28px;
  border-radius:999px;
  background:#111;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font:600 10px 'Poppins';
  transition:.2s;
}

.pfsvc-switch input:checked~b{
  background:#ff7a00;
  color:#111;
}

.pfsvc-main{
  min-width:0;
}

.pfsvc-head{
  margin-bottom:18px;
}

.pfsvc-head span{
  display:block;
  margin-bottom:8px;
  color:#ff5a12;
  font:600 13px 'Poppins';
  text-transform:uppercase;
}

.pfsvc-head h2{
  margin:0 0 8px;
  color:#111;
  font:700 50px/1.04 'Playfair Display',Georgia,serif;
  letter-spacing:0;
}

.pfsvc-head p{
  margin:0;
  color:#333;
  font-size:14px;
  line-height:1.55;
}

.pfsvc-tools{
  display:grid;
  grid-template-columns:auto minmax(0,1fr) auto 38px 38px;
  gap:10px;
  align-items:center;
  margin-bottom:18px;
}

.pfsvc-sort{
  height:38px;
  display:flex;
  align-items:center;
  gap:8px;
  white-space:nowrap;
  color:#111;
  font:600 12px 'Poppins';
  grid-column:3;
}

.pfsvc-sort select{
  height:38px;
  min-width:134px;
  border:1px solid #111;
  border-radius:4px;
  background:#fff;
  color:#111;
  padding:0 10px;
  font:400 12px 'Inter';
  outline:none;
}

.pfsvc-btn{
  height:38px;
  min-width:136px;
  border:0!important;
  border-radius:8px;
  background:#ff7a00;
  color:#111;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:0 16px;
  text-decoration:none;
  cursor:pointer;
  font:600 12px 'Poppins';
  letter-spacing:0;
  transition:background-color .22s ease,color .22s ease;
}

.pfsvc-btn:hover,
.pfsvc-btn.active{
  background:#111!important;
  color:#fff!important;
}

.pfsvc-view{
  width:38px;
  height:38px;
  border:0;
  background:#fff;
  color:#111;
  display:grid;
  place-items:center;
  cursor:pointer;
  font-size:17px;
}

.pfsvc-view:hover,
.pfsvc-view.active{
  color:#ff5a12;
}

.pfsvc-grid{
  display:grid;
  grid-template-columns:repeat(3,minmax(0,1fr));
  gap:20px;
}

.pfsvc-grid.list{
  grid-template-columns:1fr;
}

.pfsvc-grid.list .pfsvc-card{
  display:grid;
  grid-template-columns:300px 1fr;
}

.pfsvc-grid.list .pfsvc-img,
.pfsvc-grid.list .pfsvc-img img{
  height:180px;
}

.pfsvc-card{
  border:1px solid #111;
  border-radius:8px;
  background:#fff;
  overflow:hidden;
  cursor:pointer;
  transition:transform .22s ease,background-color .22s ease;
  height:306px;
}

.pfsvc-card:hover{
  transform:translateY(-4px);
  background:#fff7f1;
}

.pfsvc-img{
  position:relative;
  height:180px;
  background:#f7f7f7;
  border-bottom:1px solid #ececec;
}

.pfsvc-img img{
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center;
  display:block;
}

.pfsvc-img.no-image{
  background:linear-gradient(135deg,#fafafa,#f1f1f1);
}

.pfsvc-icon{
  position:absolute;
  left:14px;
  bottom:-15px;
  width:34px;
  height:34px;
  border:1px solid #ffd8bd;
  border-radius:8px;
  background:#fff4ec;
  color:#ff7a00;
  display:grid;
  place-items:center;
  font-size:13px;
  box-shadow:0 8px 18px rgba(255,122,0,.12);
}

.pfsvc-body{
  height:126px;
  padding:22px 16px 10px;
  display:flex;
  flex-direction:column;
  align-items:flex-start;
  justify-content:flex-end;
}

.pfsvc-body h3{
  min-height:0;
  margin:0 0 2px;
  color:#111;
  font:600 13px/1.2 'Poppins';
  letter-spacing:0;
}

.pfsvc-body p{
  min-height:0;
  margin:0 0 5px;
  color:#333;
  font-size:10.5px;
  line-height:1.22;
  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;
  overflow:hidden;
}

.pfsvc-meta{
  display:none;
}

.pfsvc-card button{
  height:30px;
  min-width:106px;
  border:0;
  border-radius:8px;
  background:#ff7a00;
  color:#111;
  font:600 10.5px 'Poppins';
  cursor:pointer;
  margin-top:0;
}

.pfsvc-card button:hover{
  background:#111;
  color:#fff;
}

.pfsvc-empty{
  display:none;
  margin-top:14px;
  border:1px solid #111;
  border-radius:8px;
  padding:28px;
  text-align:center;
}

.pfsvc-empty i{
  color:#ff7a00;
  font-size:25px;
}

.pfsvc-empty h3{
  font:600 18px 'Poppins';
  margin:10px 0 4px;
}

.pfsvc-empty p{
  margin:0;
  color:#333;
  font-size:13px;
}

.pfsvc-callout{
  margin-top:22px;
  padding:0 14px;
  display:flex;
  align-items:center;
  gap:14px;
  background:transparent;
}

.pfsvc-detail{
  display:none!important;
}

.pfsvc-callout-icon{
  width:44px;
  height:44px;
  border-radius:50%;
  display:grid;
  place-items:center;
  color:#ff7a00;
  border:1px solid #ff7a00;
}

.pfsvc-callout h3{
  margin:0 0 3px;
  font:600 16px 'Poppins';
}

.pfsvc-callout p{
  margin:0;
  color:#333;
  font-size:13px;
}

.pfsvc-callout a{
  margin-left:auto;
}

.pfsvc-deck{
  position:fixed;
  inset:0;
  z-index:99999;
  display:none;
  align-items:center;
  justify-content:center;
  background:rgba(0,0,0,.72);
  padding:20px;
}

.pfsvc-deck.active{
  display:flex;
}

.pfsvc-deck-card{
  position:relative;
  width:min(840px,94vw);
  min-height:500px;
  color:#fff;
  text-align:center;
}

.pfsvc-deck-close{
  position:absolute;
  right:0;
  top:0;
  width:44px;
  height:44px;
  border:0;
  border-radius:50%;
  background:#fff;
  color:#111;
  cursor:pointer;
  font-size:18px;
  z-index:20;
}

.pfsvc-deck-close:hover{
  background:#111;
  color:#fff;
}

.pfsvc-deck-title{
  padding-top:32px;
}

.pfsvc-deck-title span{
  color:#ff7a00;
  font:600 12px 'Poppins';
  text-transform:uppercase;
}

.pfsvc-deck-title h3{
  margin:8px 0 3px;
  color:#fff;
  font:700 31px/1 'Playfair Display';
}

.pfsvc-deck-title p{
  margin:0;
  color:#fff;
  font-size:12px;
}

.pfsvc-stage{
  position:relative;
  height:316px;
  margin:14px auto 0;
  width:min(560px,90vw);
  perspective:1200px;
}

.pfsvc-option{
  position:absolute;
  left:50%;
  top:50%;
  width:250px;
  height:300px;
  border:0;
  border-radius:16px;
  background:#fff;
  color:#111;
  padding:0;
  box-shadow:0 25px 55px rgba(0,0,0,.32);
  cursor:pointer;
  transform-origin:center 112%;
  transition:transform 1s cubic-bezier(.16,1,.3,1),opacity .35s ease,filter .35s ease,border-color .35s ease;
  will-change:transform;
  overflow:hidden;
}

.pfsvc-option.active{
  z-index:5;
}

.pfsvc-option.left,
.pfsvc-option.right{
  z-index:3;
  filter:none;
}

.pfsvc-option.hidden{
  opacity:0;
  pointer-events:none;
}

.pfsvc-option-img{
  width:100%;
  height:100%;
  border-radius:16px;
  background:#fff7f1;
  display:grid;
  place-items:center;
  overflow:hidden;
}

.pfsvc-option-img img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.pfsvc-option-img i{
  font-size:82px;
  color:#ff7a00;
}

.pfsvc-option h4{
  display:none;
}

.pfsvc-option p{
  display:none;
}

.pfsvc-option small{
  display:none;
}

.pfsvc-arrow{
  position:absolute;
  top:57%;
  width:42px;
  height:42px;
  border:0;
  border-radius:50%;
  background:#fff;
  color:#111;
  cursor:pointer;
  z-index:15;
}

.pfsvc-arrow:hover{
  background:#ff7a00;
  color:#111;
}

.pfsvc-arrow.left{
  left:150px;
}

.pfsvc-arrow.right{
  right:150px;
}

.pfsvc-deck-actions{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:8px;
  position:relative;
  z-index:8;
  margin-top:2px;
}

.pfsvc-dots{
  display:flex;
  gap:6px;
}

.pfsvc-dots button{
  width:8px;
  height:8px;
  border:0;
  border-radius:50%;
  background:#fff;
  opacity:.45;
  cursor:pointer;
}

.pfsvc-dots button.active{
  width:22px;
  border-radius:999px;
  background:#ff7a00;
  opacity:1;
}

@media(max-width:1120px){
  .pfsvc-wrap{
    width:calc(100% - 36px);
    margin:0 18px;
    grid-template-columns:1fr;
  }

  .pfsvc-side{
    position:static;
    border-right:0;
    border-bottom:1px solid #111;
    padding-bottom:20px;
  }

  .pfsvc-grid{
    grid-template-columns:repeat(2,1fr);
  }
}

@media(max-width:720px){
  .pfsvc-head h2{
    font-size:38px;
  }

  .pfsvc-tools{
    grid-template-columns:1fr 1fr;
  }

  .pfsvc-tools input,
  .pfsvc-sort{
    grid-column:1/-1;
  }

  .pfsvc-grid,
  .pfsvc-grid.list{
    grid-template-columns:1fr;
  }

  .pfsvc-grid.list .pfsvc-card{
    display:block;
  }

  .pfsvc-callout,
  .pfsvc-detail{
    align-items:flex-start;
    flex-direction:column;
  }

  .pfsvc-callout a,
  .pfsvc-detail button{
    margin-left:0;
  }

  .pfsvc-arrow.left{
    left:6px;
  }

  .pfsvc-arrow.right{
    right:6px;
  }
}

</style>

<script>
const pfsvcServices=@json($frontServices);

let pfsvcState={
  category:"all",
  view:"grid",
  activeKey:pfsvcServices[0]?.key||"doc",
  activeIndex:0,
  selected:null
};

function pfsvcToast(message){
  if(typeof window.showFrontFeedback==="function"){
    window.showFrontFeedback(message);
    return;
  }

  window.dispatchEvent(new CustomEvent("printify-front-feedback",{detail:{message}}));
}

function pfsvcSafe(text){
  return String(text||"").replace(/[&<>"']/g,ch=>({
    "&":"&amp;",
    "<":"&lt;",
    ">":"&gt;",
    "\"":"&quot;",
    "'":"&#039;"
  }[ch]));
}

function pfsvcServiceByKey(key){
  return pfsvcServices.find(item=>item.key===key)||pfsvcServices[0];
}

function pfsvcRenderCards(){
  const grid=document.getElementById("pfsvcGrid");
  const empty=document.getElementById("pfsvcEmpty");

  if(!grid)return;

  const time=document.getElementById("pfsvcTime")?.value||"all";
  const max=Number(document.getElementById("pfsvcPrice")?.value||20000);
  const showExpress=document.getElementById("pfsvcExpress")?.checked;

  let list=pfsvcServices.filter(s=>
    (pfsvcState.category==="all"||s.key===pfsvcState.category)&&
    (time==="all"||s.time===time)&&
    s.price<=max&&
    (showExpress||!s.express)
  );

  const sort=document.getElementById("pfsvcSort")?.value||"popular";

  list.sort((a,b)=>
    sort==="name"
      ? a.title.localeCompare(b.title)
      : sort==="price"
        ? a.price-b.price
        : a.order-b.order
  );

  grid.classList.toggle("list",pfsvcState.view==="list");

  grid.innerHTML=list.map(s=>`
    <article class="pfsvc-card" data-key="${s.key}" onclick="pfsvcOpenDeck('${s.key}')">
      <div class="pfsvc-img ${s.img ? "" : "no-image"}">
        ${s.img ? `<img src="${s.img}" alt="${pfsvcSafe(s.title)}" loading="eager" decoding="sync" fetchpriority="high" onerror="this.closest('.pfsvc-img')?.classList.add('no-image');this.remove();">` : ""}
        <span class="pfsvc-icon"><i class="${s.icon}"></i></span>
      </div>

      <div class="pfsvc-body">
        <h3>${pfsvcSafe(s.title)}</h3>
        <p>${pfsvcSafe(s.desc)}</p>
        <button type="button">Learn More <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </article>
  `).join("");

  empty.style.display=list.length?"none":"block";
}

function pfsvcSetCategory(category){
  pfsvcState.category=category||"all";

  document.querySelectorAll(".pfsvc-nav").forEach(btn=>{
    btn.classList.toggle("active",btn.dataset.filter===pfsvcState.category);
  });

  document.querySelector(".pfsvc-all")?.classList.toggle("active",pfsvcState.category==="all");

  pfsvcRenderCards();

  pfsvcToast(
    pfsvcState.category==="all"
      ? "All services shown."
      : `${pfsvcServiceByKey(category).title} selected.`
  );
}

function pfsvcBuildDeck(){
  const service=pfsvcServiceByKey(pfsvcState.activeKey);
  const stage=document.getElementById("pfsvcStage");

  stage.innerHTML=service.options.map((o,i)=>{
    const image=o.image||service.img;
    const img=image
      ? `<img src="${image}" alt="${pfsvcSafe(o.name)}" loading="eager" decoding="sync">`
      : `<i class="${o.icon||service.icon}"></i>`;

    return `
      <article class="pfsvc-option hidden" data-option-index="${i}" onclick="pfsvcChooseOption(${i})" aria-label="${pfsvcSafe(o.name)}">
        <div class="pfsvc-option-img">${img}</div>
      </article>
    `;
  }).join("");
}

function pfsvcOpenDeck(key){
  const service=pfsvcServiceByKey(key);

  pfsvcState.activeKey=service.key;
  pfsvcState.activeIndex=0;

  document.getElementById("pfsvcDeckKicker").innerHTML=`<i class="${service.icon}"></i> ${pfsvcSafe(service.title)}`;
  document.getElementById("pfsvcDeckHeading").textContent="Choose Service Option";
  document.getElementById("pfsvcDeck").classList.add("active");
  document.getElementById("pfsvcDeck").setAttribute("aria-hidden","false");

  document.body.style.overflow="hidden";

  pfsvcBuildDeck();
  requestAnimationFrame(pfsvcRenderDeck);
}

function pfsvcRenderDeck(){
  const service=pfsvcServiceByKey(pfsvcState.activeKey);
  const stage=document.getElementById("pfsvcStage");
  const dots=document.getElementById("pfsvcDots");

  let cards=Array.from(stage.querySelectorAll(".pfsvc-option"));

  if(cards.length!==service.options.length){
    pfsvcBuildDeck();
    cards=Array.from(stage.querySelectorAll(".pfsvc-option"));
  }

  cards.forEach((card,i)=>{
    const pos=(i-pfsvcState.activeIndex+service.options.length)%service.options.length;
    const cls=pos===0?"active":pos===1?"right":pos===service.options.length-1?"left":"hidden";

    const twoCards=service.options.length===2;
    let transform="translate(-50%,-50%) translateX(0) translateY(-2px) rotate(0deg) scale(1.04)";
    let opacity="1";

    if(twoCards && cls==="active"){
      transform="translate(-50%,-50%) translateX(-70px) translateY(0) rotate(0deg) scale(1)";
    }

    if(cls==="left"){
      transform=`translate(-50%,-50%) translateX(${twoCards?-96:-118}px) translateY(16px) rotate(-6deg) scale(.9)`;
      opacity="1";
    }

    if(cls==="right"){
      transform=`translate(-50%,-50%) translateX(${twoCards?110:118}px) translateY(${twoCards?0:16}px) rotate(${twoCards?0:6}deg) scale(${twoCards?.94:.9})`;
      opacity="1";
    }

    if(cls==="hidden"){
      transform="translate(-50%,-50%) translateY(22px) scale(.72)";
      opacity="0";
    }

    card.className=`pfsvc-option ${cls}`;
    card.style.transform=transform;
    card.style.opacity=opacity;
  });

  dots.innerHTML=service.options.map((_,i)=>`
    <button type="button" class="${i===pfsvcState.activeIndex?'active':''}" onclick="pfsvcPickOption(${i})" aria-label="Choose option ${i+1}"></button>
  `).join("");

  document.getElementById("pfsvcContinue").innerHTML=`Continue with ${pfsvcSafe(service.options[pfsvcState.activeIndex].name)} <i class="fa-solid fa-arrow-right"></i>`;
}

function pfsvcMoveDeck(step){
  const service=pfsvcServiceByKey(pfsvcState.activeKey);
  pfsvcState.activeIndex=(pfsvcState.activeIndex+step+service.options.length)%service.options.length;
  pfsvcRenderDeck();
}

function pfsvcPickOption(index){
  pfsvcState.activeIndex=index;
  pfsvcRenderDeck();
}

function pfsvcChooseOption(index){
  if(typeof window.requireSignedInForOrder==="function"&&!window.requireSignedInForOrder())return false;
  pfsvcState.activeIndex=index;
  pfsvcRenderDeck();
  setTimeout(pfsvcProceedSelected,180);
  return true;
}

function pfsvcCloseDeck(){
  document.getElementById("pfsvcDeck").classList.remove("active");
  document.getElementById("pfsvcDeck").setAttribute("aria-hidden","true");
  document.body.style.overflow="";
}

function pfsvcDetailSlug(slug){
  return ({
    "text-graphics":"text-image",
    "full-color":"image-only",
    "visa-photo":"passport-visa",
    "2x2-photo":"single-photo-print",
    "tarpaulin":"sintra-board"
  }[slug]||slug);
}

function pfsvcPayload(){
  const service=pfsvcServiceByKey(pfsvcState.activeKey);
  const option=service.options[pfsvcState.activeIndex];
  const detailSlug=pfsvcDetailSlug(option.slug);

  return {
    categoryKey:service.key,
    categoryTitle:service.title,
    categorySlug:service.key,
    serviceName:option.name,
    serviceSlug:detailSlug,
    optionSlug:option.slug,
    detailSlug,
    serviceDescription:option.desc,
    serviceIcon:option.icon||service.icon,
    serviceImage:option.image||service.img,
    serviceId:option.serviceId,
    servicePriceText:String(option.price).replace(/<[^>]+>/g,"")
  };
}

function pfsvcProceedSelected(){
  if(typeof window.requireSignedInForOrder==="function"&&!window.requireSignedInForOrder())return false;
  const payload=pfsvcPayload();

  pfsvcState.selected=payload;
  sessionStorage.setItem("selectedPrintifyService",JSON.stringify(payload));

  document.getElementById("pfsvcDetail").hidden=false;
  document.getElementById("pfsvcDetailIcon").innerHTML=`<i class="${payload.serviceIcon}"></i>`;
  document.getElementById("pfsvcDetailTitle").textContent=`${payload.categoryTitle} - ${payload.serviceName}`;
  document.getElementById("pfsvcDetailDesc").textContent=payload.serviceDescription;

  pfsvcCloseDeck();

  if(typeof window.openPrintifyServiceDetail==="function"){
    window.openPrintifyServiceDetail(payload,true);
    pfsvcToast(`${payload.serviceName} details opened.`);
    return;
  }

  const target=document.getElementById("serviceDetail")||document.getElementById("pfsvcDetail");

  target?.classList?.add("pdv-is-open","active","show");
  pfsvcToast(`${payload.serviceName} selected.`);

  setTimeout(()=>target?.scrollIntoView({behavior:"smooth",block:"start"}),120);
  return true;
}

document.querySelectorAll(".pfsvc-nav").forEach(btn=>{
  btn.addEventListener("click",()=>pfsvcSetCategory(btn.dataset.filter));
});

document.querySelectorAll(".pfsvc-view").forEach(btn=>{
  btn.addEventListener("click",()=>{
    pfsvcState.view=btn.dataset.view;

    document.querySelectorAll(".pfsvc-view").forEach(item=>{
      item.classList.toggle("active",item===btn);
    });

    pfsvcRenderCards();
    pfsvcToast(`${pfsvcState.view==="grid"?"Grid":"List"} view applied.`);
  });
});

["pfsvcSort","pfsvcTime","pfsvcExpress"].forEach(id=>{
  document.getElementById(id)?.addEventListener("change",pfsvcRenderCards);
});

document.getElementById("pfsvcPrice")?.addEventListener("input",e=>{
  document.getElementById("pfsvcPriceValue").textContent=
    Number(e.target.value)>=20000
      ? "₱20,000+"
      : `₱${Number(e.target.value).toLocaleString()}`;

  pfsvcRenderCards();
});

window.addEventListener("keydown",e=>{
  if(!document.getElementById("pfsvcDeck").classList.contains("active"))return;

  if(e.key==="Escape")pfsvcCloseDeck();
  if(e.key==="ArrowLeft")pfsvcMoveDeck(-1);
  if(e.key==="ArrowRight")pfsvcMoveDeck(1);
  if(e.key==="Enter")pfsvcProceedSelected();
});

window.addEventListener("click",e=>{
  if(e.target.id==="pfsvcDeck")pfsvcCloseDeck();
});

pfsvcRenderCards();
</script>
