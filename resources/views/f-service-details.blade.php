<?php ?>
<!-- =========================================================
  PRINTIFY & CO. - SERVICE DETAIL + CART PANEL
  Clean full code version (kept under 800 lines)
  UI FIX MAP:
  1) Sidebar images gap/position: .pdv-left-card, .pdv-layout
  2) Equal card endpoints: --pdv-panel-h and .pdv-preview-card/.pdv-summary-card
  3) Only Preview and Order Summary have main frames
  4) Printing Details outer frame removed; fields/upload remain boxed
  5) Order Summary image enlarged: .pdv-summary-thumb
  6) Button widths equalized: .pdv-action-row button
========================================================= -->
<div class="pfy-cart-backdrop" id="pfyCartBackdrop"></div>
<aside class="pfy-cart-panel" id="pfyCartPanel" aria-label="Shopping cart">
  <div class="pfy-cart-head">
    <h2>Your Cart <span id="pfyCartCount">(0)</span></h2>
    <button type="button" class="pfy-cart-close" onclick="closePrintifyCart()" aria-label="Close cart">&times;</button>
  </div>
  <div class="pfy-free-shipping">
    <div class="pfy-free-copy"><span id="pfyCartFreeText">Add <strong>₱25.00</strong> more to unlock <strong>FREE SHIPPING</strong></span><i class="fa-solid fa-truck-fast"></i></div>
    <div class="pfy-free-track"><i id="pfyFreeProgress"></i></div>
  </div>
  <div class="pfy-select-bar">
    <label class="pfy-select-all"><input type="checkbox" id="pfyCartSelectAll" checked onchange="togglePrintifyCartSelectAll(this.checked)"><span id="pfyCartSelectedLabel">Select All</span></label>
    <button type="button" class="pfy-remove-all" id="pfyRemoveAllBtn" onclick="clearPrintifyCart()"><i class="fa-regular fa-trash-can"></i> Remove All</button>
  </div>
  <div class="pfy-items" id="pfyCartItems"></div>
  <div class="pfy-summary" id="pfyCartSummary">
    <div class="pfy-promo"><i class="fa-solid fa-tag"></i><input type="text" id="pfyPromoCode" placeholder="Enter promo code"><button type="button" onclick="applyPrintifyPromo()">Apply</button></div>
    <div class="pfy-row"><span id="pfySubtotalLabel">Subtotal</span><strong id="pfySubtotal">₱0.00</strong></div>
    <div class="pfy-row"><span>Shipping</span><span id="pfyShippingLabel">Calculated at checkout</span></div>
    <div class="pfy-row" id="pfyDiscountRow" style="display:none"><span>Discount</span><strong id="pfyDiscount">-₱0.00</strong></div>
    <div class="pfy-row pfy-total"><span>Total</span><strong id="pfyTotal">₱0.00</strong></div>
    <div class="pfy-upload-alert" id="pfyCartUploadAlert" style="display:none"><i class="fa-solid fa-file-circle-exclamation"></i><span>Upload file is required before cart checkout.</span></div>
    <div class="pfy-secure-checkout">
      <div class="pfy-secure-copy"><i class="fa-solid fa-lock"></i><span><strong>Secure checkout</strong><small>Your files are safe with us</small></span></div>
      <button type="button" class="pfy-check" id="pfyCartCheckoutBtn" onclick="checkoutPrintifyCart()">Proceed to Checkout <i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
</aside>

<section id="serviceDetail" class="pdv-product-detail" aria-label="Service detail and checkout panel">
  <div class="pdv-shell">
    <div class="pdv-topbar">
      <nav class="pdv-breadcrumb" aria-label="Breadcrumb">
        <a href="/">Home</a><span><i class="fa-solid fa-chevron-right"></i></span>
        <a href="/services">Services</a><span><i class="fa-solid fa-chevron-right"></i></span>
        <a href="/services" id="pdvCrumbCategory">Document Printing</a><span><i class="fa-solid fa-chevron-right"></i></span>
        <strong id="pdvCrumbService">Text Only</strong>
      </nav>
    </div>
    <div class="pdv-hero-row">
      <div class="pdv-service-intro"><h1 id="pdvTitle">Text Only Printing</h1><p id="pdvSubtitle">High-quality print services for clean, ready-to-submit output.</p></div>
      <div class="pdv-stepper" aria-label="Checkout progress" role="list">
        <div class="pdv-step is-active" data-step="1" role="listitem" aria-current="step"><span>1</span><p>Select Service</p></div><i></i>
        <div class="pdv-step" data-step="2" role="listitem"><span>2</span><p>Customize</p></div><i></i>
        <div class="pdv-step" data-step="3" role="listitem"><span>3</span><p>Upload File</p></div><i></i>
        <div class="pdv-step" data-step="4" role="listitem"><span>4</span><p>Checkout</p></div>
      </div>
    </div>
    <div class="pdv-layout">
      <aside class="pdv-left-card" data-pdv-section="select">
        <div class="pdv-thumb-stack" id="pdvThumbStack"></div>
        <button type="button" class="pdv-download-guide" onclick="downloadSampleGuide()"><i class="fa-solid fa-download"></i><span><strong>Download Print Guide</strong><small>Accepted file checklist</small></span></button>
      </aside>
      <main class="pdv-preview-card" data-pdv-section="preview">
        <header class="pdv-product-head"><h2>Preview</h2></header>
        <div class="pdv-preview-window"><button type="button" class="pdv-side-next" onclick="pdvNextPreview()"><i class="fa-solid fa-chevron-right"></i></button><article class="pdv-document-preview" id="pdvPreviewDocument"></article></div>
        <div class="pdv-product-review-block"><div class="pdv-review-main-score"><span id="pdvStars">★★★★★</span><strong id="pdvRatingText">4.9</strong><em>/ 5</em></div><button type="button" class="pdv-review-link" id="pdvRatingBtn" onclick="pdvOpenReviews()"><span id="pdvReviewText">128 Reviews</span><small>View customer feedback</small></button></div>
      </main>
      <section class="pdv-options-card" aria-label="Printing options" data-pdv-section="customize">
        <div class="pdv-card-section">
          <h2><span>1.</span> Printing Details</h2>
          <div class="pdv-field-grid">
            <div class="pdv-field"><label>Printing Category</label><select id="pdvPrintingCategory" onchange="pdvHandleCategoryChange()"></select></div>
            <div class="pdv-field"><label>Color Variation</label><select id="pdvColorVariation" onchange="pdvUpdateOrder()"></select></div>
            <div class="pdv-field"><label>Paper Size</label><select id="pdvPaperSize" onchange="pdvUpdateOrder()"></select></div>
            <div class="pdv-field"><label>Quantity</label><div class="pdv-qty-box"><button type="button" onclick="pdvChangeQty(-1)">-</button><input type="text" id="pdvQty" value="" inputmode="numeric" maxlength="3" aria-label="Quantity" oninput="pdvHandleQtyInput()"><button type="button" onclick="pdvChangeQty(1)">+</button></div></div>
            <div class="pdv-field"><label>Service Option</label><select id="pdvServiceOption" onchange="pdvUpdateOrder()"><option value="standard">Standard Service</option><option value="priority">Priority Handling +₱15.00</option><option value="counter">Counter Pick-up Preparation +₱5.00</option></select></div>
            <div class="pdv-field"><label>File Type</label><select id="pdvFileType" onchange="pdvUpdateOrder()"><option value="pdf">PDF</option><option value="docx">DOC / DOCX</option><option value="image">JPG / PNG</option><option value="txt">TXT</option></select></div>
          </div>
        </div>
        <div class="pdv-divider"></div>
        <div class="pdv-card-section" data-pdv-section="upload">
          <h2><span>2.</span> Upload File</h2>
          <div class="pdv-upload-box" id="pdvUploadBox"><input type="file" id="pdvFileInput" hidden accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"><button type="button" class="pdv-upload-icon" onclick="document.getElementById('pdvFileInput').click()"><i class="fa-solid fa-cloud-arrow-up"></i></button><div class="pdv-upload-copy"><strong id="pdvUploadTitle">Upload your print file</strong><span>PDF, DOC, DOCX, TXT, JPG, PNG only</span></div><button type="button" class="pdv-choose-file" onclick="document.getElementById('pdvFileInput').click()">Choose File</button><small>Required before checkout • Max 50MB</small></div>
          <div class="pdv-file-result" id="pdvFileResult"><i class="fa-solid fa-file-circle-check"></i><span id="pdvFileName">No file selected</span><button type="button" onclick="pdvClearFile()">Remove</button></div>
          <div class="pdv-warning"><i class="fa-regular fa-hand"></i><span><strong>Reminder/Note</strong><small>Please review your uploaded file, layout, margins, and page order before checkout.</small></span></div>
        </div>
        <div class="pdv-divider"></div>
        <div class="pdv-card-section">
          <h2><span>3.</span> Service Add-ons <em>(Optional)</em></h2>
          <label class="pdv-addon"><input type="checkbox" id="pdvAddonDuplex" onchange="pdvUpdateOrder()"><span>Double-sided Printing</span><strong>+₱0.50 / sheet</strong><i class="fa-solid fa-circle-info"></i></label>
          <label class="pdv-addon"><input type="checkbox" id="pdvAddonStaple" onchange="pdvUpdateOrder()"><span>Collated & Stapled</span><strong>+₱1.00 / order</strong><i class="fa-solid fa-circle-info"></i></label>
          <label class="pdv-addon"><input type="checkbox" id="pdvAddonEnvelope" onchange="pdvUpdateOrder()"><span>Document Envelope</span><strong>+₱3.00 / order</strong><i class="fa-solid fa-circle-info"></i></label>
        </div>
      </section>
      <aside class="pdv-summary-card" aria-label="Order summary" data-pdv-section="checkout">
        <div class="pdv-summary-head"><h2>Order Summary</h2><button type="button" onclick="pdvFocusOptions()">Edit</button></div>
        <div class="pdv-summary-product"><div class="pdv-summary-thumb" id="pdvSummaryThumb"><span></span></div><div><h3 id="pdvSummaryTitle">Text Only Printing</h3><p id="pdvSummaryMeta">Short - Black and White</p><small id="pdvSummaryQty">1 sheet</small></div></div>
        <div class="pdv-summary-line"></div>
        <div class="pdv-price-grid">
          <button type="button" class="pdv-price-card is-active" id="pdvRetailPriceCard" onclick="pdvSelectPriceMode('retail')"><span class="pdv-price-label">Retail Price</span><strong class="pdv-price-medallion">₱<b id="pdvRetailPrice">2.00</b></strong><small>Best for regular quantity</small></button>
          <button type="button" class="pdv-price-card" id="pdvBulkPriceCard" onclick="pdvSelectPriceMode('bulk')"><span class="pdv-price-label">Bulk Price</span><strong class="pdv-price-medallion">₱<b id="pdvBulkPrice">1.50</b></strong><small id="pdvBulkHint">Click for 50+ sheets</small></button>
        </div>
        <div class="pdv-price-note" id="pdvPriceMode">Retail pricing applies below 50 sheets.</div>
        <div class="pdv-estimated-total"><span>Total Amount:</span><strong>₱<b id="pdvEstimatedTotal">2.00</b></strong></div>
        <div class="pdv-checkout-note" id="pdvCheckoutNote"><strong>Reminder/Note</strong><small>Checkout will be allowed only after a file is uploaded.</small></div>
        <div class="pdv-action-row"><button type="button" class="pdv-cart" id="pdvCartBtn" onclick="addToCart()">ADD TO CART</button><button type="button" class="pdv-checkout is-disabled" id="pdvCheckoutBtn" onclick="placeOrderNow()">CHECK OUT NOW</button></div>
      </aside>
    </div>
  </div>
  <aside class="pdv-review-panel" id="pdvReviewDrawer"><button type="button" class="pdv-review-close" onclick="pdvCloseReviews()"><i class="fa-solid fa-xmark"></i></button><small>Customer Reviews</small><h3 id="pdvReviewTitle">Text Only Printing</h3><div class="pdv-review-score"><span>★★★★★</span><strong id="pdvReviewAvg">4.9</strong><em id="pdvReviewCount">128 reviews</em></div><div class="pdv-review-bars" id="pdvReviewBars"></div><p>Ratings are used to help customers compare service quality before uploading and checking out.</p></aside>
  <div class="pdv-toast" id="pdvToast"></div>
</section>

<style>
:root{--pdv-orange:#ff4f16;--pdv-border:#e8e8e8;--pdv-text:#111;--pdv-muted:#676767;--pdv-soft:#fff6f0;--pdv-panel-h:540px;--pfy-orange:#ff5a12;--pfy-red:#ff2b1a}
*{box-sizing:border-box}
.cart-badge{position:absolute;top:13px;right:-8px;min-width:15px;height:15px;padding:0 4px;color:#fff;background:var(--pfy-orange);border-radius:999px;font:900 9px/1 Poppins,sans-serif;display:grid;place-items:center;box-shadow:0 5px 12px rgba(255,90,18,.28)}

.pfy-cart-backdrop{position:fixed!important;inset:78px 0 0!important;z-index:1001!important;background:rgba(0,0,0,.035)!important;opacity:0;pointer-events:none;transition:opacity .22s ease}
.pfy-cart-backdrop.show{opacity:1;pointer-events:auto}
.pfy-cart-panel{--pfy-pointer-right:142px;position:fixed!important;top:82px!important;right:clamp(16px,2.2vw,38px)!important;z-index:1002!important;width:378px!important;max-width:calc(100vw - 32px)!important;max-height:calc(100vh - 102px)!important;display:flex;flex-direction:column;overflow:visible;background:#fff;border:1px solid #ececec;border-radius:16px;box-shadow:0 22px 55px rgba(0,0,0,.14);font-family:Poppins,Arial,sans-serif;transform:translateY(-16px) scale(.985);transform-origin:top right;opacity:0;pointer-events:none;will-change:transform,opacity;transition:opacity .22s ease,transform .22s cubic-bezier(.22,.8,.24,1)}
.pfy-cart-panel.show{opacity:1;pointer-events:auto;transform:translateY(0) scale(1)}
.pfy-cart-panel:before{content:"";position:absolute;top:-8px;right:var(--pfy-pointer-right);width:15px;height:15px;background:#fff;border-left:1px solid #ececec;border-top:1px solid #ececec;border-radius:3px 0 0 0;transform:rotate(45deg);transition:right .18s ease}

.pfy-cart-head{display:flex;align-items:center;justify-content:space-between;padding:15px 17px 6px;background:#fff;border-radius:16px 16px 0 0}
.pfy-cart-head h2{margin:0;color:#111;font-size:16.5px;line-height:1.15;font-weight:700}
.pfy-cart-head span{color:var(--pfy-orange)}
.pfy-cart-close{width:25px;height:25px;border:0;border-radius:50%;background:#fff;color:#222;font-size:20px;line-height:25px;display:grid;place-items:center;cursor:pointer}
.pfy-cart-close:hover{background:#f7f7f7;color:var(--pfy-orange)}

.pfy-free-shipping{padding:0 17px 11px}
.pfy-free-copy{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 7px;color:#6f6f6f;font-size:10px;line-height:1.35}
.pfy-free-copy strong{color:var(--pfy-orange);font-weight:600}
.pfy-free-copy i{color:#333;font-size:13px}
.pfy-free-track{height:5px;border-radius:999px;background:#eee;overflow:hidden}
.pfy-free-track i{display:block;height:100%;width:0%;border-radius:999px;background:#ff5a12;transition:.24s}
.pfy-select-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:8px 17px;border-top:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0;background:#fff}
.pfy-select-all{display:inline-flex;align-items:center;gap:6px;color:#222;font:400 10.5px/1.2 Poppins,sans-serif;cursor:pointer}
.pfy-remove-all{border:0;background:transparent;color:#4e4e4e;font-size:10.2px;font-weight:400;display:inline-flex;align-items:center;gap:5px;cursor:pointer;padding:3px 0}
.pfy-remove-all:hover{color:var(--pfy-orange)}

.pfy-select-all input,.pfy-check-wrap input{appearance:none;-webkit-appearance:none;position:relative;width:10px;height:10px;min-width:10px;border:1.1px solid #d7d7d7;border-radius:3px;background:#fff;cursor:pointer;display:block;transition:border-color .15s,background .15s,box-shadow .15s}.pfy-select-all input:focus-visible,.pfy-check-wrap input:focus-visible{outline:0;box-shadow:0 0 0 3px rgba(255,90,18,.14)}
.pfy-select-all input:checked,.pfy-check-wrap input:checked,.pfy-select-all input:indeterminate{border-color:#ff5a12;background:#ff5a12}
.pfy-select-all input:checked:before,.pfy-check-wrap input:checked:before{content:"";position:absolute;left:50%;top:47%;width:3px;height:6px;border-right:1.35px solid #fff;border-bottom:1.35px solid #fff;transform:translate(-50%,-55%) rotate(45deg)}
.pfy-select-all input:indeterminate:before{content:"";position:absolute;left:50%;top:50%;width:6px;height:1.4px;background:#fff;transform:translate(-50%,-50%)}

.pfy-items{flex:1 1 auto;max-height:min(286px,37vh);overflow:auto;padding:0 17px;background:#fff;scrollbar-width:thin;scrollbar-color:#ffbd9e #f7f7f7}
.pfy-items::-webkit-scrollbar{width:6px}
.pfy-items::-webkit-scrollbar-thumb{background:#ffbd9e;border-radius:999px}
.pfy-items.is-empty{min-height:178px;max-height:178px;display:flex;align-items:center;justify-content:center;padding:0 17px;overflow:hidden!important}
.pfy-item{display:grid;grid-template-columns:20px 44px minmax(0,1fr) 62px 18px;align-items:start;column-gap:8px;padding:12px 0;border-bottom:1px solid #eee;background:#fff}
.pfy-check-wrap{display:flex;align-items:flex-start;justify-content:center;padding-top:15px;width:20px;height:44px;cursor:pointer}
.pfy-img,.pfy-noimg{width:44px;height:44px;border:0;border-radius:8px;background:#fff1ea;object-fit:cover;box-shadow:inset 0 0 0 1px rgba(255,90,18,.08)}
.pfy-noimg{display:grid;place-items:center;color:#4a4a4a;font-size:17px}
.pfy-info{min-width:0}
.pfy-info h3{margin:0 0 2px;color:#111;font-size:11px;line-height:1.22;font-weight:600}
.pfy-info p{margin:0 0 1px;color:#666;font-size:9.4px;line-height:1.28;font-weight:400;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pfy-price{display:none}
.pfy-file-required{display:inline-flex!important;gap:4px;margin-top:2px;color:var(--pfy-orange)!important;font-size:8.8px!important;font-weight:500!important}
.pfy-bottom{display:flex;margin-top:7px}
.pfy-qty{width:76px;height:24px;display:grid;grid-template-columns:23px 30px 23px;border:1px solid #e7e7e7;border-radius:7px;background:#fff;overflow:hidden}
.pfy-qty button{width:23px;height:24px;border:0;background:#fff;color:#111;font-size:11px;cursor:pointer}
.pfy-qty button:hover{background:#fff4ee;color:var(--pfy-orange)}
.pfy-qty span{width:30px;height:24px;display:grid;place-items:center;color:#111;font-size:10.7px;font-weight:600;border-left:1px solid #f1f1f1;border-right:1px solid #f1f1f1}
.pfy-line{min-width:62px;width:62px;align-self:end;justify-self:end;margin:0 0 3px;color:#111;font-size:10.8px;font-weight:600;text-align:right;white-space:nowrap}
.pfy-remove{justify-self:end;width:18px;height:18px;border:0;border-radius:50%;background:#fff;color:#777;font-size:10px;display:grid;place-items:center;cursor:pointer}
.pfy-remove:hover{background:#fff3ec;color:var(--pfy-orange)}

.pfy-summary{flex:0 0 auto;padding:12px 17px 16px;background:#fff;border-top:1px solid #f0f0f0;border-radius:0 0 16px 16px}
.pfy-promo{display:grid!important;grid-template-columns:18px minmax(0,1fr) 70px!important;gap:7px;align-items:center;margin:0 0 10px;width:100%}
.pfy-promo>i{width:18px;height:34px;display:grid;place-items:center;font-size:12px}
.pfy-promo input,.pfy-promo button{height:34px;border-radius:7px;line-height:34px}
.pfy-promo input{min-width:0;width:100%;border:1px solid #e4e4e4;padding:0 10px;font:400 10.2px Poppins,sans-serif;color:#333;background:#fff}
.pfy-promo input:focus{border-color:#ff9a73;box-shadow:0 0 0 3px rgba(255,90,18,.10);outline:0}
.pfy-promo button{width:70px;border:1px solid #ffc8b1;background:#fff7f1;color:var(--pfy-orange);font-size:9.5px;font-weight:600;display:grid;place-items:center;cursor:pointer;transition:background .18s,color .18s,border-color .18s}
.pfy-promo button:hover{background:var(--pfy-orange);border-color:var(--pfy-orange);color:#fff}
.pfy-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 5px;color:#222;font-size:10.6px;font-weight:400;line-height:1.35}
.pfy-row strong{color:#111;font-weight:600}
.pfy-row #pfyShippingLabel,#pfyShippingLabel.is-free{color:#13a352;font-weight:500}
.pfy-total{margin-top:10px;padding-top:11px;border-top:1px solid #eee;font-size:15.5px;font-weight:600}
.pfy-total strong{color:var(--pfy-orange);font-size:18px;font-weight:700}
.pfy-upload-alert{align-items:flex-start;gap:7px;margin:9px 0;padding:8px 10px;border:1px dashed #ffc6aa;background:#fff8f4;border-radius:9px;color:#d84a15;font-size:10px}
.pfy-secure-checkout{display:flex;align-items:center;justify-content:space-between;gap:9px;margin-top:13px}
.pfy-secure-copy{display:flex;align-items:center;gap:8px;color:#111}
.pfy-secure-copy i{font-size:14px}
.pfy-secure-copy span{display:flex;flex-direction:column}
.pfy-secure-copy strong{font-size:9.8px;font-weight:600}
.pfy-secure-copy small{color:#777;font-size:8.5px}
.pfy-check{width:156px;min-width:156px;height:38px;border:0;border-radius:9px;background:#ff5a12;color:#fff;font:600 10.5px Poppins,sans-serif;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 10px 20px rgba(255,90,18,.18);cursor:pointer;transition:background .18s}
.pfy-check:hover{background:var(--pfy-red)}
.pfy-check.is-disabled{background:#cfcfcf;box-shadow:none;cursor:not-allowed}
.pfy-check.is-disabled:hover{background:#cfcfcf}
.pfy-empty{width:100%;padding:28px 18px;text-align:center;color:#606060;border:1px dashed #ffcdb9;border-radius:12px;background:#fff}
.pfy-empty i{font-size:34px;color:var(--pfy-orange);margin-bottom:9px}
.pfy-empty h3{margin:0 0 5px;color:#111;font-size:15px;font-weight:600}
.pfy-empty p{margin:0;font-size:11px;color:#777}

#serviceDetail,#serviceDetail *{box-sizing:border-box}
#serviceDetail{display:none;width:100%;background:#fff;color:var(--pdv-text);font-family:Poppins,Arial,sans-serif;padding:18px 0 32px;scroll-margin-top:82px;overflow-x:hidden}
#serviceDetail.pdv-is-open{display:block}
body.front-route-service-details #serviceDetail{display:block}
.pdv-shell{width:min(1450px,calc(100% - 46px));margin:0 auto}
.pdv-topbar{margin:0 0 12px}
.pdv-breadcrumb{display:flex;align-items:center;gap:12px;flex-wrap:wrap;color:#6d6d6d;font-size:13px;font-weight:500;line-height:1.2}
.pdv-breadcrumb a{color:#5f6670;text-decoration:none}
.pdv-breadcrumb span{color:#9ca1a7;font-size:9px}
.pdv-breadcrumb strong{color:#111;font-weight:700}
.pdv-hero-row{display:grid;grid-template-columns:minmax(280px,1fr) minmax(560px,760px);align-items:end;gap:18px;margin:7px 0 23px}
.pdv-service-intro h1{margin:0 0 7px;color:#111;font:800 31px/1.08 Poppins,Arial,sans-serif;letter-spacing:-.035em}
.pdv-service-intro p{margin:0;color:#5f6368;font-size:14px;line-height:1.45}
.pdv-stepper{display:grid;grid-template-columns:auto 1fr auto 1fr auto 1fr auto;gap:10px;align-items:center;width:100%;margin-bottom:4px}
.pdv-step{display:flex;align-items:center;gap:10px;color:#787b80;font-size:12px;font-weight:500;white-space:nowrap}
.pdv-step span{width:32px;height:32px;border-radius:50%;background:#6f7176;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 16px rgba(0,0,0,.12);font-size:12px;font-weight:800}
.pdv-step p{margin:0;line-height:32px}
.pdv-stepper>i{height:1px;border-top:2px dotted #cfcfcf}
.pdv-step.is-active,.pdv-step.is-done{color:var(--pdv-orange);font-weight:700}
.pdv-step.is-active span,.pdv-step.is-done span{background:var(--pdv-orange)}

.pdv-layout{display:grid;grid-template-columns:130px minmax(455px,1.28fr) 355px 300px;column-gap:18px;row-gap:16px;align-items:stretch}
.pdv-left-card,.pdv-options-card{min-height:var(--pdv-panel-h);background:transparent!important;border:0!important;box-shadow:none!important;border-radius:0!important}
.pdv-preview-card,.pdv-summary-card{min-height:var(--pdv-panel-h);border:1px solid var(--pdv-border);border-radius:10px;background:#fff;box-shadow:0 15px 34px rgba(0,0,0,.035)}
.pdv-left-card{padding:0;margin-left:-14px;margin-right:27px;display:flex;flex-direction:column}
.pdv-thumb-stack{display:flex;flex-direction:column;gap:12px}
.pdv-thumb{width:100%;min-height:102px;border:1px solid #e2e2e2;background:#fff;border-radius:9px;cursor:pointer;padding:10px 8px;display:flex;flex-direction:column;align-items:center;justify-content:space-between;color:#111;transition:.2s;overflow:hidden}
.pdv-thumb:hover{transform:translateY(-1px);border-color:#ffb89b;box-shadow:0 12px 22px rgba(255,79,22,.10)}
.pdv-thumb.is-active{border:2px solid var(--pdv-orange);background:#fffaf7;box-shadow:none}
.pdv-thumb strong{font-size:10.5px;line-height:1.15;font-weight:800;text-align:center}
.pdv-thumb.is-active strong{color:var(--pdv-orange)}
.pdv-thumb-paper{width:48px;height:66px;background:#fff;border:1px solid #dedede;box-shadow:0 8px 18px rgba(0,0,0,.10);padding:7px 5px;display:block;overflow:hidden}
.pdv-thumb-paper b{display:block;width:100%;height:5px;border-radius:9px;background:#232323;margin-bottom:5px}
.pdv-thumb-paper i,.pdv-thumb-paper em{display:block;height:4px;border-radius:8px;background:#b7b7b7;margin-bottom:4px}
.pdv-thumb.has-real-thumb{padding:0;background:transparent}
.pdv-thumb.has-real-thumb strong{width:100%;padding:7px 3px 8px;background:#fff}
.pdv-thumb-paper.is-real-thumb{width:100%;height:auto;padding:0;background:transparent;border:0;box-shadow:none;line-height:0}
.pdv-thumb-real-img{width:100%;height:auto;max-height:96px;object-fit:contain;display:block}
.pdv-download-guide{height:54px;margin-top:14px;border:1px solid #f5ddcf;background:#fff7f1;border-radius:9px;display:flex;align-items:center;gap:9px;padding:0 10px;cursor:pointer;color:#111;text-align:left}
.pdv-download-guide i{font-size:18px;color:var(--pdv-orange)}
.pdv-download-guide strong{display:block;font-size:10.5px}
.pdv-download-guide small{display:block;font-size:9px;color:#6a6a6a;line-height:1.35}

.pdv-preview-card{padding:18px 18px 24px;display:flex;flex-direction:column;align-items:stretch;justify-content:flex-start;overflow:hidden}
.pdv-product-head{margin:0 0 12px}
.pdv-product-head h2{margin:0;color:#111;font-size:18px;font-weight:800}
.pdv-preview-window{flex:1;min-height:385px;display:flex;align-items:center;justify-content:center;position:relative}
.pdv-side-next{display:none}
.pdv-document-preview{width:min(390px,92%);aspect-ratio:8.5/11;background:#fff;box-shadow:0 15px 38px rgba(0,0,0,.15);padding:34px;color:#111;transition:.18s}
.pdv-document-preview h4{font-family:Georgia,serif;text-align:center;margin:0 0 26px;font-size:13px;line-height:1.25}
.pdv-document-preview p{font-family:Georgia,serif;font-size:11px;line-height:1.55;margin:0 0 19px}
.pdv-document-preview.is-real-image-preview{width:auto;max-width:95%;aspect-ratio:auto;padding:0;background:transparent;box-shadow:0 15px 38px rgba(0,0,0,.12);line-height:0}
.pdv-real-preview-img{max-height:420px;max-width:100%;object-fit:contain;display:block}
.pdv-image-missing{width:100%;height:100%;padding:26px 24px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;border:1px dashed #ff9f79;background:#fff8f3;color:#111}
.pdv-image-missing i{font-size:28px;color:var(--pdv-orange);margin-bottom:12px}
.pdv-product-review-block{width:100%;margin:16px auto 0;border:1px solid #ffe3d5;background:#fffaf7;border-radius:12px;padding:10px 13px;display:flex;align-items:center;justify-content:space-between;gap:10px}
.pdv-review-main-score{display:flex;align-items:baseline;gap:6px;white-space:nowrap}
.pdv-review-main-score span{color:#ffb400;font-size:12px;letter-spacing:1px}
.pdv-review-main-score strong{font-size:24px;font-weight:900}
.pdv-review-main-score em{font-style:normal;color:#8f6c20;font-size:10px;font-weight:800}
.pdv-review-link{border:0;background:transparent;padding:0;text-align:right;cursor:pointer;font-family:Poppins,sans-serif}
.pdv-review-link span{display:block;color:#111;font-size:11px;font-weight:900;text-decoration:underline}
.pdv-review-link small{display:block;margin-top:3px;color:var(--pdv-orange);font-size:10px;font-weight:700}

.pdv-options-card{padding:0 2px}
.pdv-card-section h2{margin:0 0 11px;color:#111;font-size:16px;line-height:1.1;font-weight:700}
.pdv-card-section h2 span{margin-right:10px}
.pdv-card-section h2 em{color:#999;font-size:11px;font-style:normal;font-weight:500}
.pdv-field-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));column-gap:8px;row-gap:9px}
.pdv-field label{display:block;margin:0 0 5px;color:#111;font-size:10px;line-height:1.15;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pdv-field select,.pdv-qty-box{width:100%;height:34px;border:1px solid #e4e4e4;border-radius:8px;background:#fff;color:#111;outline:none;font-family:Poppins,sans-serif;font-size:10px;font-weight:400;transition:border-color .18s,box-shadow .18s,background .18s}
.pdv-field select{padding:0 10px;cursor:pointer}
.pdv-field select:hover,.pdv-qty-box:hover{border-color:#ffbea5}.pdv-field select:focus,.pdv-qty-box:focus-within{border-color:#ff8c63;box-shadow:0 0 0 3px rgba(255,79,22,.10)}
.pdv-qty-box{display:grid;grid-template-columns:30px 1fr 30px;overflow:hidden}
.pdv-qty-box button{border:0;background:#fff;color:#111;cursor:pointer;font-size:11px;font-weight:700;transition:background .18s,color .18s}
.pdv-qty-box button:hover{background:var(--pdv-orange);color:#fff}
.pdv-qty-box input{width:100%;border:0;border-left:1px solid #eee;border-right:1px solid #eee;text-align:center;color:#111;font-size:11px;font-weight:600;outline:none;font-family:Poppins,sans-serif;background:#fff}
.pdv-qty-box input::-webkit-outer-spin-button,.pdv-qty-box input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.pdv-qty-box input[type=number]{-moz-appearance:textfield}
.pdv-divider{height:1px;background:#e8e8e8;margin:12px 0}
.pdv-upload-box{width:100%;max-width:348px;min-height:58px;border:1px solid #e4e4e4;border-radius:8px;background:#fff;display:grid;grid-template-columns:32px minmax(0,1fr) auto;align-items:center;gap:8px;text-align:left;padding:8px 9px;transition:border-color .18s,box-shadow .18s,background .18s}
.pdv-upload-box:hover,.pdv-upload-box.is-dragging{border-color:#ff9a73;background:#fff;box-shadow:0 0 0 3px rgba(255,79,22,.08)}
.pdv-upload-icon{width:30px;height:30px;border:0;background:#fff6f0;border-radius:50%;color:var(--pdv-orange);font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center}
.pdv-upload-copy{min-width:0}
.pdv-upload-box strong{display:block;color:#111;font-size:10.5px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pdv-upload-box span{display:block;color:#777;font-size:8.8px;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pdv-choose-file{height:28px;min-width:86px;border:0;border-radius:7px;background:var(--pdv-orange);color:#fff;cursor:pointer;font-family:Poppins,sans-serif;font-size:9.3px;font-weight:700;transition:background .18s}
.pdv-choose-file:hover{background:var(--pdv-red,#ff2b1a)}
.pdv-upload-box small{grid-column:2/4;margin-top:-2px;color:#9a9a9a;font-size:8.4px}
.pdv-file-result{display:none;align-items:center;gap:8px;min-height:32px;margin-top:8px;padding:7px 9px;background:#f7fff9;border:1px solid #ccefd5;border-radius:8px;color:#1c8543;font-size:10px;font-weight:800}
.pdv-file-result.is-visible{display:flex}
.pdv-file-result span{min-width:0;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pdv-file-result button{border:0;background:transparent;color:#d23d19;cursor:pointer;font-size:9.8px;font-weight:900}
.pdv-warning{margin-top:8px;display:flex;gap:8px;align-items:flex-start;background:#fff6f0;border-radius:7px;padding:8px 10px;color:#111}
.pdv-warning i{width:21px;height:21px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--pdv-orange);background:#fff;flex:0 0 auto}
.pdv-warning strong{display:block;font-size:10px;font-weight:700}
.pdv-warning small{display:block;color:#777;font-size:8.9px;line-height:1.3}
.pdv-addon{display:grid;grid-template-columns:16px 1fr auto 15px;align-items:center;gap:7px;margin-top:8px;color:#444;cursor:pointer;font-size:10.5px}
.pdv-addon input{width:14px;height:14px;accent-color:var(--pdv-orange)}
.pdv-addon span{font-weight:500}
.pdv-addon strong{color:#111;font-size:9.5px;font-weight:700}
.pdv-addon i{color:#bbb;font-size:11px}

.pdv-summary-card{padding:17px 16px;display:flex;flex-direction:column;justify-content:flex-start}
.pdv-summary-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.pdv-summary-head h2{margin:0;color:#111;font-size:16px;font-weight:700}
.pdv-summary-head button{width:44px;height:25px;border:1px solid #ffb89b;border-radius:7px;background:#fff;color:var(--pdv-orange);cursor:pointer;font-size:10px;font-weight:600}
.pdv-summary-product{display:grid;grid-template-columns:64px minmax(0,1fr);gap:12px;align-items:start}
.pdv-summary-thumb{width:64px;height:82px;border:1px solid #ededed;background:#fbfbfb;box-shadow:0 8px 18px rgba(0,0,0,.06);padding:7px;overflow:hidden}
.pdv-summary-thumb.is-real-summary{padding:0;background:transparent;line-height:0}
.pdv-summary-real-img{width:100%;height:100%;max-height:82px;object-fit:contain;display:block}
.pdv-summary-thumb span,.pdv-summary-thumb span:before,.pdv-summary-thumb span:after{display:block;content:"";border-radius:9px;background:#bdbdbd}
.pdv-summary-thumb span{height:5px;width:72%;margin-bottom:7px}
.pdv-summary-thumb span:before{height:4px;width:120%;margin-top:11px}
.pdv-summary-thumb span:after{height:4px;width:100%;margin-top:8px}
.pdv-summary-product h3{margin:1px 0 5px;color:#111;font-size:11.8px;line-height:1.25;font-weight:600}
.pdv-summary-product p,.pdv-summary-product small{display:block;margin:0 0 3px;color:#555;font-size:9.7px;line-height:1.25}
.pdv-summary-line{height:1px;background:#e8e8e8;margin:11px 0}
.pdv-price-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:0 0 9px}
.pdv-price-card{min-height:66px;border:1px solid #e6e6e6;border-radius:8px;background:#fff;padding:9px 10px;text-align:left;cursor:pointer;font-family:Poppins,sans-serif}
.pdv-price-card.is-active{border-color:#ffc8b1;background:#fff7f1}
.pdv-price-label{display:block;color:#111;font-size:9.2px;font-weight:600}
.pdv-price-card.is-active .pdv-price-label{color:var(--pdv-orange)}
.pdv-price-medallion{display:block;margin:4px 0 3px;color:#111;font-size:13px;line-height:1;font-weight:700}
.pdv-price-medallion b{font-size:14px}
.pdv-price-card.is-active .pdv-price-medallion{color:var(--pdv-orange)}
.pdv-price-card small{display:block;color:#666;font-size:8.4px;line-height:1.18}
.pdv-price-note{background:#f7f7f7;border:0;border-radius:7px;padding:8px 9px;color:#6a6a6a;font-size:9.5px;line-height:1.32;margin-bottom:9px}
.pdv-estimated-total{display:flex;align-items:center;justify-content:space-between;background:#fff6f0;border:0;border-radius:0;padding:9px 10px;color:#111;font-size:11px;font-weight:600;margin-bottom:10px}
.pdv-estimated-total strong{color:var(--pdv-orange);font-size:16px}
.pdv-checkout-note{background:#fff6f0;border-radius:8px;padding:8px 9px 8px 34px;margin-bottom:10px;position:relative}
.pdv-checkout-note:before{content:"\f133";font-family:"Font Awesome 6 Free";font-weight:900;position:absolute;left:11px;top:10px;color:var(--pdv-orange)}
.pdv-checkout-note strong{display:block;color:#111;font-size:10px;font-weight:700;margin-bottom:2px}
.pdv-checkout-note small{display:block;color:#777;font-size:8.8px;line-height:1.3}
.pdv-action-row{display:grid;grid-template-columns:1fr;gap:8px;margin-top:auto;justify-items:center}
.pdv-cart,.pdv-checkout{width:232px!important;max-width:100%;height:37px;border:0;border-radius:7px;cursor:pointer;font-family:Poppins,sans-serif;font-size:11px;font-weight:700;background:var(--pdv-orange);color:#fff;box-shadow:none;transition:background .18s,opacity .18s}
.pdv-cart:hover,.pdv-checkout:hover{background:#ff2b1a}
.pdv-checkout:disabled,.pdv-checkout.is-disabled{background:#cfcfcf;color:#fff;opacity:1;cursor:not-allowed}
.pdv-checkout:disabled:hover,.pdv-checkout.is-disabled:hover{background:#cfcfcf}

.pdv-review-panel{display:none;width:min(520px,calc(100% - 56px));margin:16px auto 0;background:#fff;border:1px solid #ffe0b5;border-radius:16px;box-shadow:0 18px 48px rgba(0,0,0,.10);padding:18px;position:relative}
.pdv-review-panel.is-visible{display:block}
.pdv-review-close{position:absolute;right:12px;top:12px;width:30px;height:30px;border:0;border-radius:50%;background:#fff3ec;color:#d74814;cursor:pointer}
.pdv-review-panel>small{display:block;color:var(--pdv-orange);font-size:10px;font-weight:900;text-transform:uppercase}
.pdv-review-panel h3{margin:4px 34px 9px 0;font-size:16px}
.pdv-review-score{display:flex;align-items:center;gap:8px;background:#fff9e9;border:1px solid #ffe3a6;border-radius:11px;padding:10px;margin-bottom:12px}
.pdv-review-score span{color:#ffb400;letter-spacing:1px;font-size:12px}
.pdv-review-score strong{font-size:22px}
.pdv-review-score em{font-style:normal;color:#805f12;font-size:11px;font-weight:800}
.pdv-review-bar{display:grid;grid-template-columns:44px 1fr 36px;align-items:center;gap:8px;margin:8px 0;font-size:10px;color:#555;font-weight:800}
.pdv-review-track{height:7px;border-radius:99px;background:#f0f0f0;overflow:hidden}
.pdv-review-fill{height:100%;border-radius:99px;background:#ffb400}
.pdv-review-panel p{margin:12px 0 0;color:#777;font-size:10.5px;line-height:1.45}
.pdv-toast{position:fixed;right:28px;bottom:28px;min-width:260px;max-width:360px;transform:translateY(24px);opacity:0;pointer-events:none;border-radius:12px;background:#151515;color:#fff;padding:14px 16px;font-size:12px;line-height:1.45;box-shadow:0 18px 55px rgba(0,0,0,.22);z-index:999999;transition:.25s}
.pdv-toast.is-visible{transform:translateY(0);opacity:1}

@media(max-width:1500px){:root{--pdv-panel-h:520px}
.pdv-shell{width:calc(100% - 30px)}
.pdv-layout{grid-template-columns:126px minmax(420px,1.25fr) 325px 280px;column-gap:12px}
.pdv-left-card{margin-left:-12px;margin-right:22px}
.pdv-preview-window{min-height:370px}
.pdv-real-preview-img{max-height:405px}
.pdv-field-grid{column-gap:8px;row-gap:8px}
.pdv-summary-product{grid-template-columns:60px minmax(0,1fr)}
.pdv-summary-thumb{width:60px;height:78px}
.pdv-summary-real-img{max-height:78px}
}

@media(max-width:1250px){.pdv-hero-row{grid-template-columns:1fr}
.pdv-stepper{max-width:900px}
.pdv-layout{grid-template-columns:170px minmax(0,1fr);gap:14px}
.pdv-left-card{margin:0}
.pdv-options-card,.pdv-summary-card{min-height:auto}
.pdv-preview-card{min-height:520px}
.pdv-summary-card{padding-bottom:18px}
}

@media(max-width:850px){.pdv-shell{width:calc(100% - 22px)}
.pdv-stepper{grid-template-columns:1fr 1fr;gap:9px}
.pdv-stepper>i{display:none}
.pdv-step{justify-content:flex-start;border:1px solid #eee;border-radius:999px;padding:8px 10px;background:#fff}
.pdv-step span{width:28px;height:28px;font-size:12px}
.pdv-step p{line-height:28px;font-size:12px}
.pdv-layout{grid-template-columns:1fr;gap:12px}
.pdv-thumb-stack{display:grid;grid-template-columns:repeat(3,1fr)}
.pdv-field-grid{grid-template-columns:1fr}
.pdv-upload-box{grid-template-columns:32px 1fr}
.pdv-choose-file{grid-column:1/3;width:100%}
.pdv-upload-box small{grid-column:1/3}
.pdv-price-grid{grid-template-columns:1fr}
.pfy-cart-panel{left:12px!important;right:12px!important;width:auto!important;--pfy-pointer-right:72px}
.pfy-secure-checkout{display:grid;grid-template-columns:1fr}
}

@media(max-width:430px){.pdv-thumb-stack{grid-template-columns:1fr}
.pfy-item{grid-template-columns:10px 44px minmax(0,1fr) 18px;column-gap:8px}
.pfy-img,.pfy-noimg{width:44px;height:44px}
.pfy-line{grid-column:3;grid-row:1;width:auto;min-width:0;margin:40px 0 0;justify-self:start;text-align:left}
.pfy-remove{grid-column:4;grid-row:1}
}

.pfy-cart-panel .pfy-promo>*{min-width:0!important;max-width:100%}
.pfy-cart-panel .pfy-promo>i{grid-column:1!important;grid-row:1!important}
.pfy-cart-panel .pfy-promo input{grid-column:2!important;grid-row:1!important}
.pfy-cart-panel .pfy-promo button{grid-column:3!important;grid-row:1!important;white-space:nowrap!important}
.pfy-cart-panel .pfy-summary,.pfy-cart-panel .pfy-items{width:100%}

</style>

<script>
(function(){"use strict";

const CART_KEY="printifyCartItems",LEGACY_KEY="printifyCart",LEGACY_KEY_2="cartItems",PROMO_KEY="printifyPromoCode",FREE_SHIPPING_TARGET=25;

const $=s=>document.querySelector(s),$$=s=>Array.from(document.querySelectorAll(s));

const readJson=(k,f="[]")=>{try{return JSON.parse(localStorage.getItem(k)||f)}catch(e){return JSON.parse(f)}};

const writeJson=(k,v)=>localStorage.setItem(k,JSON.stringify(v));

const toNumber=v=>{if(v==null||v==="")return 0;const n=Number(String(v).replace(/[^0-9.-]/g,""));return Number.isFinite(n)?n:0};

const roundMoney=v=>Math.round((Number(v)||0)*100)/100,peso=v=>"₱"+roundMoney(v).toLocaleString("en-PH",{minimumFractionDigits:2,maximumFractionDigits:2});

const esc=v=>String(v??"").replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[c]));

const slug=v=>String(v||"print-item").toLowerCase().trim().replace(/[^a-z0-9]+/g,"-").replace(/^-+|-+$/g,"");

const cleanQty=v=>Math.max(1,Math.min(9999,parseInt(v,10)||1));

const stableId=item=>{const r=item?.raw&&typeof item.raw==="object"?item.raw:(item||{});return String(item?.id||item?.cartId||r.cartId||r.id||[r.serviceId||item?.serviceId||item?.serviceKey||r.serviceKey||item?.name||r.serviceName||"item",r.createdAt||r.addedAt||r.total||item?.total||"0",r.quantity||item?.qty||"1"].map(slug).join("-"))};

const selectedItems=items=>items.filter(i=>i.selected!==false),countQty=items=>(items||[]).reduce((s,i)=>s+cleanQty(i.qty||i.quantity),0);

const hasRequiredFile=i=>{const r=i?.raw||{};return !!(i?.fileName||r.fileName||r.fileMeta?.name||(Array.isArray(i?.meta)?i.meta:[]).some(m=>/^File:\s*(?!$|no file|null|undefined)/i.test(String(m||""))))};

const lineTotal=i=>{const q=cleanQty(i?.qty||i?.quantity),unit=toNumber(i?.unitPrice??i?.price??i?.raw?.unitPrice),flat=toNumber(i?.flatFee??i?.raw?.flatFee),direct=toNumber(i?.lineTotal??i?.raw?.total??i?.total);return roundMoney(unit>0||flat>0?unit*q+flat:direct)};

const syncRawOrder=i=>{const r=i.raw&&typeof i.raw==="object"?{...i.raw}:{};r.cartId=i.id;r.status=r.status||"cart";r.serviceName=r.serviceName||i.name;r.quantity=cleanQty(i.qty);r.unitPrice=roundMoney(i.unitPrice||i.price);r.flatFee=roundMoney(i.flatFee);r.total=lineTotal(i);r.selected=i.selected!==false;r.updatedAt=new Date().toISOString();return r};

function normalizeItem(item){if(!item)return null;const raw=item.raw&&typeof item.raw==="object"?{...item.raw}:{...item};const qty=cleanQty(item.qty??item.quantity??raw.quantity??1);const name=item.name||item.title||item.serviceName||raw.serviceName||"Print Item";const direct=toNumber(item.lineTotal??raw.total??item.total??item.amountTotal);let unit=toNumber(raw.unitPrice)||toNumber(item.unitPrice)||toNumber(item.price??item.amount??item.unit_price);if(unit&&direct&&qty>1&&Math.abs(unit-direct)<.01)unit=roundMoney(direct/qty);if(!unit&&direct)unit=roundMoney(direct/qty);let flat=toNumber(item.flatFee??raw.flatFee??raw.orderFee??raw.serviceFee);if(!flat&&direct&&unit)flat=Math.max(0,roundMoney(direct-unit*qty));const meta=[];if(Array.isArray(item.meta||item.details))meta.push(...(item.meta||item.details));else if(item.meta||item.details)meta.push(item.meta||item.details);if(raw.category||item.category)meta.push(raw.category||item.category);const paperColor=[raw.paperSize||item.paperSize,raw.colorVariation||item.colorVariation].filter(Boolean).join(" - ");if(paperColor)meta.push(paperColor);if(raw.serviceOption||item.serviceOption)meta.push(raw.serviceOption||item.serviceOption);if(raw.fileName||item.fileName)meta.push("File: "+(raw.fileName||item.fileName));const n={id:stableId({...item,raw}),name:String(name),unitPrice:roundMoney(unit),price:roundMoney(unit),flatFee:roundMoney(flat),qty,selected:item.selected!==false&&raw.selected!==false,image:item.image||item.img||item.thumbnail||item.previewImage||raw.image||raw.previewImage||"",meta:[...new Set(meta.map(v=>String(v||"").trim()).filter(Boolean))],raw};n.raw=syncRawOrder(n);return n}

const readCartItems=()=>{const p=readJson(CART_KEY);if(Array.isArray(p)&&p.length)return p.map(normalizeItem).filter(Boolean);const s=readJson(LEGACY_KEY_2);if(Array.isArray(s)&&s.length)return s.map(normalizeItem).filter(Boolean);const l=readJson(LEGACY_KEY);return Array.isArray(l)?l.map(normalizeItem).filter(Boolean):[]};

const totals=items=>{const subtotal=roundMoney((items||[]).reduce((s,i)=>s+lineTotal(i),0));const code=(localStorage.getItem(PROMO_KEY)||"").trim().toUpperCase();let discount=0;if(code==="SAVE10")discount=roundMoney(subtotal*.1);if(code==="PRINTIFY50")discount=50;discount=Math.min(discount,subtotal);return{subtotal,discount,total:roundMoney(subtotal-discount)}};

const updateCartBadges=count=>{$$("#cartBadge,#cartCount,#printifyCartCount,.cart-count,[data-cart-count]").forEach(n=>{n.textContent=String(count);if(n.classList.contains("cart-badge"))n.style.display=count?"grid":"none"})};

const visibleRect=n=>{if(!n)return null;const r=n.getBoundingClientRect();return r.width&&r.height&&r.bottom>0&&r.right>0?r:null};
const findCartAnchor=()=>{let selectors=["[onclick*=\"toggleCart\"]","[onclick*=\"openPrintifyCart\"]","#cartIcon","#cartBtn",".cart-icon",".cart-btn",".cart-link",".nav-cart",".shopping-cart","[data-cart-toggle]","[aria-label*=\"cart\" i]"],nodes=[];selectors.forEach(sel=>{try{nodes.push(...$$(sel))}catch(e){}});nodes.push(...$$("i.fa-cart-shopping,i.fa-shopping-cart,svg[data-icon*=cart],.fa-bag-shopping"));return nodes.map(n=>n.closest("a,button,[role=button],li,div")||n).find(n=>!n.closest("#pfyCartPanel")&&visibleRect(n))||null};
function alignCartPanel(){const panel=$("#pfyCartPanel"),anchor=findCartAnchor();if(!panel||!anchor)return;const ar=visibleRect(anchor);if(!ar)return;const center=ar.left+ar.width/2,pr=panel.getBoundingClientRect();let pointer=pr.right-center-7.5;pointer=Math.max(28,Math.min(pr.width-28,pointer));panel.style.setProperty("--pfy-pointer-right",pointer+"px")}

function saveCartItems(items){const n=(items||[]).map(normalizeItem).filter(Boolean);writeJson(CART_KEY,n);writeJson(LEGACY_KEY_2,n);writeJson(LEGACY_KEY,n.map(syncRawOrder));localStorage.setItem("printifyCartCount",String(countQty(n)));renderCart(n);return n}

function renderCart(cartItems=readCartItems()){const items=(cartItems||[]).map(normalizeItem).filter(Boolean),selected=selectedItems(items),t=totals(selected),allQty=countQty(items),selQty=countQty(selected),missing=selected.length>0&&selected.some(i=>!hasRequiredFile(i)),free=t.subtotal>=FREE_SHIPPING_TARGET,remaining=Math.max(0,FREE_SHIPPING_TARGET-t.subtotal),progress=FREE_SHIPPING_TARGET?Math.min(100,t.subtotal/FREE_SHIPPING_TARGET*100):0;const list=$("#pfyCartItems"),summary=$("#pfyCartSummary");if($("#pfyCartCount"))$("#pfyCartCount").textContent=`(${allQty})`;if($("#pfyCartFreeText"))$("#pfyCartFreeText").innerHTML=free?"You unlocked <strong>FREE SHIPPING</strong>":`Add <strong>${peso(remaining)}</strong> more to unlock <strong>FREE SHIPPING</strong>`;if($("#pfyFreeProgress"))$("#pfyFreeProgress").style.width=progress+"%";if($("#pfyShippingLabel")){const s=$("#pfyShippingLabel");s.textContent=selected.length?(free?"FREE":"Calculated at checkout"):"—";s.classList.toggle("is-free",free&&selected.length>0)}if($("#pfyCartSelectedLabel"))$("#pfyCartSelectedLabel").textContent=selected.length&&selected.length<items.length?`Selected (${selQty})`:"Select All";if($("#pfySubtotalLabel"))$("#pfySubtotalLabel").textContent=`Subtotal (${selQty} item${selQty===1?"":"s"})`;if($("#pfyRemoveAllBtn"))$("#pfyRemoveAllBtn").disabled=!items.length;if($("#pfyCartSelectAll")){const c=$("#pfyCartSelectAll");c.checked=!!items.length&&selected.length===items.length;c.indeterminate=!!items.length&&!!selected.length&&selected.length<items.length;c.disabled=!items.length}if($("#pfySubtotal"))$("#pfySubtotal").textContent=peso(t.subtotal);if($("#pfyTotal"))$("#pfyTotal").textContent=peso(t.total);if($("#pfyDiscountRow"))$("#pfyDiscountRow").style.display=t.discount?"flex":"none";if($("#pfyDiscount"))$("#pfyDiscount").textContent="-"+peso(t.discount);if($("#pfyCartUploadAlert"))$("#pfyCartUploadAlert").style.display=missing?"flex":"none";if($("#pfyCartCheckoutBtn"))$("#pfyCartCheckoutBtn").classList.toggle("is-disabled",missing||!selected.length);updateCartBadges(allQty);if(!list||!summary)return;summary.style.display="block";if(!items.length){list.classList.add("is-empty");list.innerHTML='<div class="pfy-empty"><i class="fa-solid fa-cart-shopping"></i><h3>Your cart is empty</h3><p>Add a printing service to start your order.</p></div>';return}list.classList.remove("is-empty");list.innerHTML=items.map(i=>{const q=cleanQty(i.qty),ok=hasRequiredFile(i),checked=i.selected!==false?" checked":"",meta=i.meta.slice(0,3).map(v=>`<p>${esc(v)}</p>`).join("")+(ok?"":'<p class="pfy-file-required"><i class="fa-solid fa-file-circle-exclamation"></i> File required</p>'),img=i.image?`<img class="pfy-img" src="${esc(i.image)}" alt="${esc(i.name)}">`:'<div class="pfy-noimg"><i class="fa-regular fa-file-lines"></i></div>';return `<article class="pfy-item ${ok?"":"pfy-needs-file"}" data-id="${esc(i.id)}"><label class="pfy-check-wrap"><input type="checkbox" data-act="select"${checked}></label>${img}<div class="pfy-info"><h3>${esc(i.name)}</h3>${meta}<div class="pfy-price">${peso(i.unitPrice)}</div><div class="pfy-bottom"><div class="pfy-qty"><button type="button" data-act="minus">−</button><span>${q}</span><button type="button" data-act="plus">+</button></div></div></div><div class="pfy-line">${peso(lineTotal(i))}</div><button type="button" class="pfy-remove" data-act="remove"><i class="fa-regular fa-trash-can"></i></button></article>`}).join("")}

window.getCartItems=readCartItems;
window.saveCartItems=saveCartItems;
window.renderPrintifyCart=renderCart;
window.openPrintifyCart=()=>{renderCart();alignCartPanel();$("#pfyCartPanel")?.classList.add("show");$("#pfyCartBackdrop")?.classList.add("show");requestAnimationFrame(alignCartPanel)};
window.closePrintifyCart=()=>{$("#pfyCartPanel")?.classList.remove("show");$("#pfyCartBackdrop")?.classList.remove("show")};
window.toggleCart=()=>{$("#pfyCartPanel")?.classList.contains("show")?closePrintifyCart():openPrintifyCart();return false};
window.addEventListener("resize",()=>{$("#pfyCartPanel")?.classList.contains("show")&&alignCartPanel()});
window.addToPrintifyCart=(payload,opt={})=>{if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return null;const item=normalizeItem(payload);if(!item)return null;let cart=readCartItems();const ex=cart.find(c=>c.id===item.id);if(opt.merge===false||!ex)cart.push(item);else{ex.qty=cleanQty(ex.qty)+cleanQty(item.qty);ex.unitPrice=item.unitPrice||ex.unitPrice;ex.price=ex.unitPrice;ex.flatFee=roundMoney((ex.flatFee||0)+(item.flatFee||0));ex.raw={...ex.raw,...item.raw};ex.raw.quantity=ex.qty;ex.raw.total=lineTotal(ex)}saveCartItems(cart);if(opt.rawOrder)localStorage.setItem("printifyLatestCartItem",JSON.stringify(opt.rawOrder));if(opt.open!==false)openPrintifyCart();return item};
window.clearPrintifyCart=()=>{if(readCartItems().length&&confirm("Remove all items from your cart?"))saveCartItems([])};
window.applyPrintifyPromo=()=>{const code=($("#pfyPromoCode")?.value||"").trim().toUpperCase();if(!code)return;if(!["SAVE10","PRINTIFY50"].includes(code))return alert("Invalid promo code.");localStorage.setItem(PROMO_KEY,code);renderCart()};
window.checkoutPrintifyCart=()=>{if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return false;const cart=readCartItems(),sel=selectedItems(cart),t=totals(sel);if(!cart.length)return alert("Your cart is empty.");if(!sel.length)return alert("Please select at least one cart item before checkout.");if(sel.some(i=>!hasRequiredFile(i))){renderCart(cart);return alert("Please upload a file for every selected cart item before checkout.")}const checkoutItems=sel.map(syncRawOrder);writeJson("printifyCheckoutItems",checkoutItems);writeJson("printifyActiveCheckout",checkoutItems[0]||null);writeJson("printifyCheckoutTotals",{itemCount:countQty(sel),subtotal:t.subtotal,discount:t.discount,total:t.total,freeShipping:t.subtotal>=FREE_SHIPPING_TARGET,createdAt:new Date().toISOString()});localStorage.setItem("printifyCheckoutSource","cart");closePrintifyCart();if(typeof window.openCheckoutSection==="function")return window.openCheckoutSection();window.location.href="/checkout"};
window.togglePrintifyCartSelectAll=checked=>{const c=readCartItems();c.forEach(i=>i.selected=!!checked);saveCartItems(c)};
document.addEventListener("change",e=>{const cb=e.target.closest('#pfyCartItems input[data-act="select"]');if(!cb)return;const row=cb.closest(".pfy-item"),cart=readCartItems(),item=cart.find(i=>i.id===row?.dataset.id);if(item){item.selected=!!cb.checked;saveCartItems(cart)}});
document.addEventListener("click",e=>{const b=e.target.closest('#pfyCartItems button[data-act]');if(!b)return;const row=b.closest(".pfy-item");let cart=readCartItems();const item=cart.find(i=>i.id===row?.dataset.id);if(!item)return;if(b.dataset.act==="plus")item.qty=cleanQty(item.qty)+1;if(b.dataset.act==="minus")item.qty=Math.max(1,cleanQty(item.qty)-1);if(b.dataset.act==="remove")cart=cart.filter(i=>i.id!==item.id);else item.raw=syncRawOrder(item);saveCartItems(cart)});
document.addEventListener("click",e=>{if(e.target?.id==="pfyCartBackdrop")closePrintifyCart()});
document.addEventListener("keydown",e=>{if(e.key==="Escape")closePrintifyCart()});
document.readyState==="loading"?
document.addEventListener("DOMContentLoaded",()=>renderCart()):renderCart();})();

(function(){"use strict";

const d=document,$=s=>d.querySelector(s),products={"text-only":{categoryKey:"doc",categoryTitle:"Document Printing",serviceName:"Text Only",title:"Text Only Printing",summaryTitle:"Text Only Printing",serviceId:"DOC-TX-001",retailPrice:2,bulkPrice:1.5,bulkAt:50,unit:"sheets",previewType:"text",previewImage:"TXTONLY (B&W).png",previewImageCandidates:["TXTONLY (B&W).png","Text Only Printing.png","TEXT ONLY.png"],rating:"4.9",reviews:128},
  "text-image":{categoryKey:"doc",categoryTitle:"Document Printing",serviceName:"Text With Image",title:"Text With Image Printing",summaryTitle:"Text With Image Printing",serviceId:"DOC-TWI-004",retailPrice:4,bulkPrice:3.25,bulkAt:50,unit:"sheets",previewType:"text-image",previewImage:"TEXT WITH IMAGE.png",rating:"4.8",reviews:96},
  "image-only":{categoryKey:"doc",categoryTitle:"Document Printing",serviceName:"Image Only",title:"Image Only Printing",summaryTitle:"Image Only Printing",serviceId:"DOC-IMG-007",retailPrice:5,bulkPrice:4.25,bulkAt:40,unit:"sheets",previewType:"image",previewImage:"IMAGE ONLY.png",rating:"4.7",reviews:84},
  "photocopy":{categoryKey:"photo",categoryTitle:"Photocopy & Scanning",serviceName:"Photocopy",title:"Photocopy Service",summaryTitle:"Photocopy Service",serviceId:"DOC-PCPY-001",retailPrice:1.5,bulkPrice:1,bulkAt:60,unit:"copies",previewType:"text",rating:"4.8",reviews:144},
  "scanning":{categoryKey:"photo",categoryTitle:"Photocopy & Scanning",serviceName:"Scanning",title:"Document Scanning",summaryTitle:"Document Scanning",serviceId:"SCAN-001",retailPrice:10,bulkPrice:8,bulkAt:30,unit:"pages",previewType:"text",rating:"4.8",reviews:73},
  "id-photo":{categoryKey:"id",categoryTitle:"ID & Photo Services",serviceName:"ID Photo",title:"ID Photo Package",summaryTitle:"ID Photo Package",serviceId:"IDP-PKG-001",retailPrice:80,bulkPrice:70,bulkAt:5,unit:"sets",previewType:"id",rating:"4.9",reviews:102},
  "passport-visa":{categoryKey:"id",categoryTitle:"ID & Photo Services",serviceName:"Passport/Visa",title:"Passport / Visa Photo",summaryTitle:"Passport / Visa Photo",serviceId:"IDP-PKG-004",retailPrice:90,bulkPrice:80,bulkAt:5,unit:"sets",previewType:"id",rating:"4.8",reviews:77},
  "single-photo-print":{categoryKey:"id",categoryTitle:"ID & Photo Services",serviceName:"Single Photo Print",title:"Single Photo Print",summaryTitle:"Single Photo Print",serviceId:"IDP-SP-003",retailPrice:15,bulkPrice:12,bulkAt:20,unit:"prints",previewType:"image",rating:"4.7",reviews:65},
  "lamination":{categoryKey:"bind",categoryTitle:"Lamination & Binding",serviceName:"Lamination",title:"Document Lamination",summaryTitle:"Document Lamination",serviceId:"LAM-001",retailPrice:20,bulkPrice:17,bulkAt:10,unit:"pieces",previewType:"text",rating:"4.8",reviews:91},
  "spiral-binding":{categoryKey:"bind",categoryTitle:"Lamination & Binding",serviceName:"Spiral Binding",title:"Spiral Binding",summaryTitle:"Spiral Binding",serviceId:"BND-001",retailPrice:35,bulkPrice:30,bulkAt:10,unit:"sets",previewType:"text",rating:"4.7",reviews:58},
  "sintra-board":{categoryKey:"largeformat",categoryTitle:"Large Format Printing",serviceName:"Sintra Board",title:"Sintra Board Printing",summaryTitle:"Sintra Board Printing",serviceId:"LFP-SINTRA-001",retailPrice:150,bulkPrice:135,bulkAt:5,unit:"pieces",previewType:"image",rating:"4.8",reviews:39},
  "custom-special-printing":{categoryKey:"special",categoryTitle:"Custom Special Printing",serviceName:"Custom Special Printing",title:"Custom Special Printing",summaryTitle:"Custom Special Printing",serviceId:"CSP-001",retailPrice:50,bulkPrice:45,bulkAt:10,unit:"orders",previewType:"image",rating:"4.7",reviews:42}};

let state={productKey:"text-only",product:products["text-only"],selectedFile:null},cache={};

const el={root:$("#serviceDetail"),crumbCategory:$("#pdvCrumbCategory"),crumbService:$("#pdvCrumbService"),title:$("#pdvTitle"),subtitle:$("#pdvSubtitle"),preview:$("#pdvPreviewDocument"),cat:$("#pdvPrintingCategory"),color:$("#pdvColorVariation"),paper:$("#pdvPaperSize"),qty:$("#pdvQty"),svc:$("#pdvServiceOption"),ftype:$("#pdvFileType"),sumTitle:$("#pdvSummaryTitle"),sumMeta:$("#pdvSummaryMeta"),sumQty:$("#pdvSummaryQty"),sumThumb:$("#pdvSummaryThumb"),retail:$("#pdvRetailPrice"),bulk:$("#pdvBulkPrice"),retailCard:$("#pdvRetailPriceCard"),bulkCard:$("#pdvBulkPriceCard"),bulkHint:$("#pdvBulkHint"),mode:$("#pdvPriceMode"),total:$("#pdvEstimatedTotal"),ratingText:$("#pdvRatingText"),reviewText:$("#pdvReviewText"),duplex:$("#pdvAddonDuplex"),staple:$("#pdvAddonStaple"),env:$("#pdvAddonEnvelope"),file:$("#pdvFileInput"),upload:$("#pdvUploadBox"),fileResult:$("#pdvFileResult"),fileName:$("#pdvFileName"),checkout:$("#pdvCheckoutBtn"),note:$("#pdvCheckoutNote"),toast:$("#pdvToast"),drawer:$("#pdvReviewDrawer"),rTitle:$("#pdvReviewTitle"),rAvg:$("#pdvReviewAvg"),rCount:$("#pdvReviewCount"),bars:$("#pdvReviewBars")};

const money=v=>Number(v||0).toFixed(2),key=v=>String(v||"").toLowerCase().trim().replace(/&/g,"and").replace(/[^a-z0-9]+/g,"-").replace(/^-+|-+$/g,""),esc=v=>String(v||"").replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[m]));

const json=(k,f="[]")=>{try{return JSON.parse(localStorage.getItem(k)||f)}catch(e){return JSON.parse(f)}},save=(k,v)=>localStorage.setItem(k,JSON.stringify(v));

function toast(m){el.toast.textContent=m;el.toast.classList.add("is-visible");clearTimeout(toast.t);toast.t=setTimeout(()=>el.toast.classList.remove("is-visible"),2600)}

function txt(s){return s?.options?.[s.selectedIndex]?.text||""}
function q(forceOne=false){let raw=String(el.qty.value||"").replace(/\D/g,"");if(raw!==el.qty.value)el.qty.value=raw;if(raw===""){if(forceOne){el.qty.value="1";return 1}return 0}let v=Math.max(1,Math.min(999,parseInt(raw,10)||0));el.qty.value=String(v);return v}

function step(n){d.querySelectorAll("#serviceDetail .pdv-step").forEach(i=>{let x=+i.dataset.step;i.classList.toggle("is-active",x===n);i.classList.toggle("is-done",x<n);x===n?i.setAttribute("aria-current","step"):i.removeAttribute("aria-current")})}

function selectOptions(p){let cat={doc:[["text-only","Text Only"],["text-image","Text With Image"],["image-only","Image Only"]],photo:[["photocopy","Photocopy"],["scanning","Scanning"]],id:[["id-photo","ID Photo Package"],["passport-visa","Passport / Visa Photo"],["single-photo-print","Single Photo Print"]],bind:[["lamination","Lamination"],["spiral-binding","Spiral Binding"]],largeformat:[["sintra-board","Sintra Board"]],special:[["custom-special-printing","Custom Special Printing"]]}[p.categoryKey]||[];el.cat.innerHTML=cat.map(o=>`<option value="${o[0]}">${o[1]}</option>`).join("");el.cat.value=state.productKey;let colors=p.categoryKey==="id"?[["standard","Standard Photo Print"],["hd","High Definition"],["matte","Matte Finish"]]:["largeformat","special"].includes(p.categoryKey)?[["standard","Standard Quality"],["hd","High Definition"],["premium","Premium Output"]]:[["bw","Black and White"],["partial","Partially Colored"],["full","Full Colored"]];el.color.innerHTML=colors.map(o=>`<option value="${o[0]}">${o[1]}</option>`).join("");let papers=p.categoryKey==="id"?[["pkg-a","Package A - Mixed Sizes"],["pkg-b","1x1 - 8 pcs"],["pkg-c","2x2 - 8 pcs"],["passport","Passport Size - 5 pcs"],["wallet","Wallet Size - 5 pcs"]]:p.categoryKey==="largeformat"?[["a3","A3"],["a2","A2"],["a1","A1"],["custom-size","Custom Size"]]:[["short","Short - 8.5 x 11 in"],["a4","A4 - 8.27 x 11.69 in"],["long","Long - 8.5 x 13 in"]];el.paper.innerHTML=papers.map(o=>`<option value="${o[0]}">${o[1]}</option>`).join("")}

function imgPaths(p){let list=[p.previewImage,...(p.previewImageCandidates||[]),p.title+".png",p.serviceName+".png",key(p.title)+".png"].filter(Boolean),folders=["","./","images/","images/services/","assets/images/","uploads/services/"];return[...new Set(list.flatMap(f=>folders.map(x=>x+f).concat(folders.map(x=>(x+f).split("/").map(encodeURIComponent).join("/")))))]}

function setImg(img,p,fail){let ck=p.serviceId+p.previewImage;if(cache[ck]){img.src=cache[ck];return}let arr=imgPaths(p),i=0,probe=new Image();probe.onload=()=>{cache[ck]=arr[i-1];img.src=cache[ck]};probe.onerror=()=>{i<arr.length?probe.src=arr[i++]:fail&&fail()};probe.src=arr[i++]}

function thumbs(p){let map={doc:[["text-only","TEXT ONLY","pdv-thumb-text"],["text-image","TEXT WITH IMAGE","pdv-thumb-image"],["image-only","IMAGE ONLY","pdv-thumb-grid"]],photo:[["photocopy","PHOTOCOPY","pdv-thumb-text"],["scanning","SCANNING","pdv-thumb-image"]],id:[["id-photo","ID PHOTO","pdv-thumb-grid"],["passport-visa","PASSPORT/VISA","pdv-thumb-image"],["single-photo-print","PHOTO PRINT","pdv-thumb-grid"]],bind:[["lamination","LAMINATION","pdv-thumb-text"],["spiral-binding","SPIRAL BINDING","pdv-thumb-image"]],largeformat:[["sintra-board","SINTRA BOARD","pdv-thumb-grid"]],special:[["custom-special-printing","CUSTOM PRINT","pdv-thumb-grid"]]}[p.categoryKey]||[];$("#pdvThumbStack").innerHTML=map.map(x=>{let pr=products[x[0]],real=pr?.previewImage;return`<button type="button" class="pdv-thumb ${real?"has-real-thumb":""} ${state.productKey===x[0]?"is-active":""}" data-option="${x[0]}"><span class="pdv-thumb-paper ${x[2]} ${real?"is-real-thumb":""}">${real?`<img class="pdv-thumb-real-img" alt="${esc(x[1])}">`:`<b></b><em></em><em></em><em></em><i></i><i></i><i></i>`}</span><strong>${x[1]}</strong></button>`}).join("");d.querySelectorAll("#pdvThumbStack .pdv-thumb").forEach(btn=>{let pr=products[btn.dataset.option],img=btn.querySelector("img");if(img)setImg(img,pr,()=>{btn.classList.remove("has-real-thumb");btn.querySelector("span").classList.remove("is-real-thumb");btn.querySelector("span").innerHTML="<b></b><em></em><em></em><em></em><i></i><i></i><i></i>"})})}

function preview(p){el.preview.className="pdv-document-preview";if(p.previewImage){el.preview.className="pdv-document-preview is-real-image-preview";el.preview.innerHTML=`<img class="pdv-real-preview-img" alt="${esc(p.title)} preview">`;setImg(el.preview.querySelector("img"),p,()=>{el.preview.className="pdv-document-preview";el.preview.innerHTML=`<div class="pdv-image-missing"><i class="fa-regular fa-image"></i><strong>Preview image not found</strong><small>Place <b>${esc(p.previewImage)}</b> inside your root or images folder.</small></div>`});return}el.preview.innerHTML=`<h4>${p.serviceName} Sample Layout</h4><p>This preview represents a clean black and white document print with readable margins and sharp text output.</p><p>Please check the file before uploading. The final print will follow the uploaded file layout, selected paper size, color option, and quantity.</p><p>For best results, upload a ready-to-print PDF or document file with the correct page setup.</p><p>Make sure names, page numbers, images, and margins are already reviewed before checkout.</p>`}

function summaryThumb(p){if(p.previewImage){el.sumThumb.classList.add("is-real-summary");el.sumThumb.innerHTML=`<img class="pdv-summary-real-img" alt="${esc(p.title)} preview">`;setImg(el.sumThumb.querySelector("img"),p,()=>{el.sumThumb.classList.remove("is-real-summary");el.sumThumb.innerHTML="<span></span>"})}else{el.sumThumb.classList.remove("is-real-summary");el.sumThumb.innerHTML="<span></span>"}}

function open(k,payload={},opt={}){let p=products[key(k)]||products["text-only"];state.productKey=products[key(k)]?key(k):"text-only";state.product=p;if(!opt.keepQty)el.qty.value="";el.root.classList.add("pdv-is-open");el.crumbCategory.textContent=p.categoryTitle;el.crumbService.textContent=p.serviceName;el.title.textContent=p.title;el.subtitle.textContent=p.serviceName==="Photocopy"?"High-quality black & white photocopies for documents of all sizes.":p.serviceName==="Scanning"?"Clean document scanning with organized digital output.":`High-quality ${p.serviceName.toLowerCase()} service with clean, ready-to-submit output.`;el.ratingText.textContent=p.rating;el.reviewText.textContent=p.reviews+" Reviews";selectOptions(p);thumbs(p);preview(p);summaryThumb(p);step(opt.step||1);pdvUpdateOrder();if(opt.scroll)requestAnimationFrame(()=>el.root.scrollIntoView({behavior:"smooth",block:"start"}))}

function cExtra(){let v=el.color.value,p=state.product;if(p.categoryKey==="id")return v==="hd"?10:v==="matte"?5:0;if(["largeformat","special"].includes(p.categoryKey))return v==="hd"?15:v==="premium"?30:0;return v==="partial"?1:v==="full"?3:0}
function pExtra(){let v=el.paper.value,p=state.product;if(p.categoryKey==="id")return v==="passport"?10:v==="wallet"?5:0;if(p.categoryKey==="largeformat")return v==="a2"?80:v==="a1"?160:v==="custom-size"?220:0;return v==="a4"?1:v==="long"?1.5:0}
function calc(){let n=q(false),p=state.product,base=n>=p.bulkAt?p.bulkPrice:p.retailPrice,unit=base+cExtra()+pExtra()+(el.duplex.checked?.5:0),flat=n?((el.svc.value==="priority"?15:el.svc.value==="counter"?5:0)+(el.staple.checked?1:0)+(el.env.checked?3:0)):0;return{qty:n,base,unitPrice:unit,total:unit*n+flat,isBulk:n>=p.bulkAt}}

window.pdvUpdateOrder=function(){let o=calc(),p=state.product,unit=o.qty>1?p.unit:p.unit.replace(/s$/," ").trim();el.sumTitle.textContent=p.summaryTitle;el.sumMeta.textContent=txt(el.paper)+" - "+txt(el.color);el.sumQty.textContent=o.qty?o.qty+" "+unit+" • "+txt(el.svc):"Quantity not selected • "+txt(el.svc);el.retail.textContent=money(p.retailPrice);el.bulk.textContent=money(p.bulkPrice);el.total.textContent=money(o.total);el.mode.textContent=!o.qty?"Select quantity using the − / + buttons or type the number to calculate total amount.":o.isBulk?`Bulk pricing is active because quantity reached ${p.bulkAt} ${p.unit}.`:`Retail pricing applies below ${p.bulkAt} ${p.unit}. Click Bulk Price to jump to bulk quantity.`;el.retailCard.classList.toggle("is-active",!o.isBulk);el.bulkCard.classList.toggle("is-active",o.isBulk);el.bulkHint.textContent=`Click for ${p.bulkAt}+ ${p.unit.replace(/s$/,"")}`;el.checkout.disabled=false;el.checkout.classList.toggle("is-disabled",!state.selectedFile||!o.qty);el.note.querySelector("small").textContent=state.selectedFile?"File uploaded. Please confirm that this is the correct file before checkout.":"Checkout will be allowed only after a file is uploaded."};

window.pdvHandleCategoryChange=()=>open(el.cat.value,null,{step:2});
window.pdvHandleQtyInput=()=>{q(false);step(2);pdvUpdateOrder()};
window.pdvChangeQty=n=>{let current=q(false),next=current?current+n:1;el.qty.value=String(Math.max(1,Math.min(999,next)));step(2);pdvUpdateOrder()};
window.pdvSelectPriceMode=m=>{let p=state.product,current=q(false);if(m==="bulk"&&current<p.bulkAt){el.qty.value=p.bulkAt;toast(`Bulk price selected. Quantity set to ${p.bulkAt} ${p.unit}.`)}else if(m==="retail"&&current>=p.bulkAt){el.qty.value=Math.max(1,p.bulkAt-1);toast("Retail price selected. Quantity adjusted below bulk level.")}else if(m==="retail"&&!current){el.qty.value="1";toast("Retail price selected. Quantity set to 1.")}step(2);pdvUpdateOrder()};
window.pdvNextPreview=()=>{let b=[...d.querySelectorAll("#pdvThumbStack .pdv-thumb")],i=b.findIndex(x=>x.classList.contains("is-active"));if(b.length)open(b[(i+1)%b.length].dataset.option,null,{step:1})};
window.pdvFocusOptions=()=>{$("#serviceDetail .pdv-options-card").scrollIntoView({behavior:"smooth",block:"center"});step(2)};
function setFile(f){if(!f)return;let ext=(f.name.split(".").pop()||"").toLowerCase();if(f.size>50*1024*1024)return toast("File is too large. Maximum allowed file size is 50MB.");if(!["pdf","doc","docx","txt","jpg","jpeg","png"].includes(ext))return toast("Invalid file type. Please upload PDF, DOC, DOCX, TXT, JPG, or PNG.");state.selectedFile={name:f.name,size:f.size,type:f.type||ext,extension:ext,lastModified:f.lastModified||Date.now()};el.fileName.textContent=f.name;el.fileResult.classList.add("is-visible");el.upload.classList.remove("is-dragging");step(3);pdvUpdateOrder();toast("File uploaded: "+f.name)}

window.pdvClearFile=()=>{state.selectedFile=null;el.file.value="";el.fileResult.classList.remove("is-visible");el.fileName.textContent="No file selected";step(2);pdvUpdateOrder();toast("File removed. Upload is required before checkout.")};

function order(status){let o=calc(),p=state.product,f=state.selectedFile;return{id:(status==="checkout"?"CHK":"CRT")+"-"+Date.now().toString(36).toUpperCase()+"-"+Math.random().toString(36).slice(2,7).toUpperCase(),status,serviceKey:state.productKey,category:p.categoryTitle,serviceName:p.serviceName,serviceId:p.serviceId,printingCategory:txt(el.cat),colorVariation:txt(el.color),paperSize:txt(el.paper),quantity:o.qty,unit:p.unit,serviceOption:txt(el.svc),fileType:txt(el.ftype),fileName:f?f.name:null,fileMeta:f,retailPrice:p.retailPrice,bulkPrice:p.bulkPrice,priceMode:o.isBulk?"Bulk":"Retail",unitPrice:+money(o.unitPrice),addons:{doubleSided:el.duplex.checked,collatedStapled:el.staple.checked,documentEnvelope:el.env.checked},total:+money(o.total),createdAt:new Date().toISOString()}}

function currentImg(){let img=$("#pdvSummaryThumb img")||$("#pdvPreviewDocument img")||$("#pdvThumbStack .pdv-thumb.is-active img");return img?.getAttribute("src")||""}

window.addToCart=()=>{if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return false;let o=order("cart");if(!o.quantity){el.qty.focus();return toast("Please set the quantity first using the − / + buttons.")}let qty=Math.max(1,parseInt(o.quantity)||1),flat=+(money(o.total-o.unitPrice*qty)),item={id:o.id,name:o.serviceName,unitPrice:+money(o.unitPrice),price:+money(o.unitPrice),flatFee:Math.max(0,flat),qty,lineTotal:+money(o.total),image:currentImg(),meta:[o.category,o.paperSize+(o.colorVariation?" - "+o.colorVariation:""),o.serviceOption,o.fileName?"File: "+o.fileName:""].filter(Boolean),raw:o};if(window.addToPrintifyCart)window.addToPrintifyCart(item,{rawOrder:o,merge:false,open:true});toast(`Added to cart: ${o.serviceName} • ₱${money(o.total)}`)};

window.placeOrderNow=()=>{if(typeof requireSignedInForOrder==="function"&&!requireSignedInForOrder())return false;if(!state.selectedFile){step(3);el.file.click();toast("Please upload a file first before checkout.");return}let o=order("checkout"),list=json("printifyCheckout");list.push(o);save("printifyCheckout",list);save("printifyActiveCheckout",o);save("printifyCheckoutItems",[o]);save("printifyCheckoutSource","direct");step(4);toast(`Proceeding to checkout: ${o.serviceName} • ₱${money(o.total)}`);if(typeof window.openCheckoutSection==="function")return window.openCheckoutSection(o);window.location.href="/checkout"};

window.downloadSampleGuide=()=>{let blob=new Blob([["PRINTIFY & CO. PRINT GUIDE","Service: "+state.product.summaryTitle,"Accepted files: PDF, DOC, DOCX, TXT, JPG, PNG","Maximum file size: 50MB","Reminder: Review layout, margins, and page order before checkout."].join("\n")],{type:"text/plain"}),url=URL.createObjectURL(blob),a=d.createElement("a");a.href=url;a.download="printify-print-guide.txt";d.body.appendChild(a);a.click();a.remove();URL.revokeObjectURL(url);toast("Print guide downloaded.")};

window.pdvOpenReviews=()=>{let p=state.product,avg=parseFloat(p.rating)||4.7,base=Math.round(avg*20),bars=[Math.min(96,base),Math.round(base*.18),Math.round(base*.08),Math.round(base*.04),Math.round(base*.02)];el.rTitle.textContent=p.title;el.rAvg.textContent=p.rating;el.rCount.textContent=p.reviews+" reviews";el.bars.innerHTML=bars.map((v,i)=>`<div class="pdv-review-bar"><span>${5-i} stars</span><div class="pdv-review-track"><div class="pdv-review-fill" style="width:${v}%"></div></div><strong>${v}%</strong></div>`).join("");el.drawer.classList.add("is-visible");save("printifyLastViewedReviews",{serviceKey:state.productKey,serviceId:p.serviceId,rating:p.rating,reviews:p.reviews,viewedAt:new Date().toISOString()})};
window.pdvCloseReviews=()=>el.drawer.classList.remove("is-visible");

function bind(){d.addEventListener("click",e=>{let t=e.target.closest("#pdvThumbStack .pdv-thumb");if(t?.dataset.option)open(t.dataset.option,null,{step:1})});
["dragenter","dragover"].forEach(x=>el.upload.addEventListener(x,e=>{e.preventDefault();el.upload.classList.add("is-dragging");step(3)}));
["dragleave","drop"].forEach(x=>el.upload.addEventListener(x,e=>{e.preventDefault();if(x==="drop"&&e.dataTransfer.files?.[0])setFile(e.dataTransfer.files[0]);else el.upload.classList.remove("is-dragging")}));
el.file.addEventListener("change",function(){if(this.files?.[0])setFile(this.files[0])});
[el.color,el.paper,el.qty,el.svc,el.ftype,el.duplex,el.staple,el.env].forEach(x=>["change","input","focus"].forEach(ev=>x.addEventListener(ev,()=>{step(2);pdvUpdateOrder()})));
window.addEventListener("printifyServiceSelected",e=>
window.openPrintifyServiceDetail(e.detail||"text-only",true))}

window.openPrintifyServiceDetail=(payload,scroll)=>{let k=typeof payload==="string"?payload:(payload?.serviceSlug||payload?.slug||payload?.serviceName||payload?.categoryKey||"text-only");open(k,payload,{step:1,scroll:scroll!==false})};
bind();
let payload=null;
try{payload=JSON.parse(sessionStorage.getItem("selectedPrintifyService")||"null")}catch(e){}
const isServiceDetailRoute=/\/service-details?\/?$/i.test(location.pathname);
const queryService=new URLSearchParams(window.location.search).get("service");
if(queryService)
window.openPrintifyServiceDetail(queryService,true);else if(payload)
{if(isServiceDetailRoute)window.openPrintifyServiceDetail(payload,true)}else if(location.hash==="#serviceDetail"||isServiceDetailRoute)open("text-only",null,{step:1,scroll:false})})();
</script>
