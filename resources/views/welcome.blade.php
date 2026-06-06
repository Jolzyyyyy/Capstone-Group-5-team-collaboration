<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cart-sync-url" content="{{ route('cart.sync') }}">
    <meta name="checkout-url" content="{{ route('checkout.index') }}">
    <title>Printing Business Solution | Printify & Co.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preload" as="image" href="{{ asset('images/optimized/Document PrintingS.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('images/optimized/PhotocopyS.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('images/optimized/Photo IDS.webp') }}" type="image/webp">
    <link rel="stylesheet" href="{{ asset('webproj.css') }}?v={{ filemtime(public_path('webproj.css')) }}">
</head>
<body>

@php
    $resolveImageUrl = function (?string $path): string {
        $fallback = asset('images/Prdcts1.jpg');

        if (empty($path)) {
            return $fallback;
        }

        $normalizedPath = ltrim(trim($path), '/');

        if (\Illuminate\Support\Str::startsWith($normalizedPath, ['http://', 'https://'])) {
            return $normalizedPath;
        }

        if (\Illuminate\Support\Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = \Illuminate\Support\Str::after($normalizedPath, 'public/');
        }

        if (\Illuminate\Support\Str::startsWith($normalizedPath, ['images/', 'img/'])) {
            return file_exists(public_path($normalizedPath)) ? asset($normalizedPath) : $fallback;
        }

        if (file_exists(public_path('images/' . $normalizedPath))) {
            return asset('images/' . $normalizedPath);
        }

        if (file_exists(public_path($normalizedPath))) {
            return asset($normalizedPath);
        }

        return $fallback;
    };

    $serviceCards = [
        ['key' => 'doc', 'title' => 'DOCUMENT PRINTING', 'image' => $resolveImageUrl('images/optimized/Document PrintingS.webp')],
        ['key' => 'photo', 'title' => 'PHOTOCOPY & SCANNING', 'image' => $resolveImageUrl('images/optimized/PhotocopyS.webp')],
        ['key' => 'id', 'title' => 'ID & PHOTO SERVICES', 'image' => $resolveImageUrl('images/optimized/Photo IDS.webp')],
        ['key' => 'bind', 'title' => 'LAMINATION & BINDING', 'image' => $resolveImageUrl('images/optimized/Lamination & BindingS.webp')],
        ['key' => 'largeformat', 'title' => 'LARGE FORMAT PRINTING', 'image' => $resolveImageUrl('images/optimized/Large FormatingS.webp')],
        ['key' => 'special', 'title' => 'CUSTOM SPECIAL PRINTING', 'image' => $resolveImageUrl('images/optimized/Custom SpecialS.webp')],
    ];
@endphp

<x-storefront.header />

<div class="main-content" id="pageWrapper">
    <x-storefront.hero-section />
    <x-storefront.services-section :service-cards="$serviceCards" />
    <x-storefront.about-section />
    <x-storefront.contact-section />
</div>

<section id="productDetail" class="detail-section">
    <div class="detail-container">
        <div class="detail-sidebar" id="detailSidebar">
            <div class="sidebar-viewport">
                <div class="sidebar-track" id="sidebarTrack">
                    </div>
            </div>
        </div>

        <div class="detail-images-slider">
            <div class="product-title-header">
                <p id="detailCategoryHeader" class="detail-category-header">DOCUMENT PRINTING</p>
                <h2 id="detailTitleHeader">DOCUMENT PRINTING</h2>
                <div class="stock-indicator">
                    <div class="stock-dot-glow"></div>
                    <span id="availabilityText">IN STOCK</span>
                </div>
            </div>
            
            <div class="preview-viewport">
                <button class="preview-btn prev-p" id="detailPrevBtn" onclick="movePreview(-1)">&lt;</button>
                <div class="preview-track" id="previewTrack">
                    </div>
                <button class="preview-btn next-p" id="detailNextBtn" onclick="movePreview(1)">&gt;</button>
            </div>

            <div class="reviews-yellow-box">
                <div class="rev-header-inline">
                    <span>Customer Reviews</span>
                    <span style="color: #f1c40f;">5/5 <small style="color:#333">(Verified)</small></span>
                </div>
                <div class="reviews-horizontal-scroll" id="reviewsList">
                    </div>
            </div>
        </div>

        <div class="detail-info-panel" id="detailInfoPanel">
            <div class="detail-meta-card">
                <p id="serviceMaterial" class="service-material">Premium 80gsm Bond Paper</p>
                <div id="serviceInclusions" class="service-inclusions">Select a variation to view inclusions and service details.</div>
            </div>

            <div class="detail-options-grid" id="primaryOptionsGrid">
            
            <div class="custom-option-group" id="printCategoryGroup">
                <label id="printCategoryLabel">Printing Category</label>
                <select class="custom-select" id="printCategory" onchange="syncPreviewFromDropdowns(); updatePrice()">
                    <optgroup label="TEXT ONLY">
                        <option value="DOC-TX-001">B&W - DOC-TX-001</option>
                        <option value="DOC-TX-002">Partially Colored - DOC-TX-002</option>
                        <option value="DOC-TX-003">Full Colored - DOC-TX-003</option>
                    </optgroup>
                    <optgroup label="TEXT WITH IMAGE (TWI)">
                        <option value="DOC-TWI-004">B&W - DOC-TWI-004</option>
                        <option value="DOC-TWI-005">Partially Colored - DOC-TWI-005</option>
                        <option value="DOC-TWI-006">Full Colored - DOC-TWI-006</option>
                    </optgroup>
                    <optgroup label="IMAGE ONLY (IM)">
                        <option value="DOC-IM-007">B&W - DOC-IM-007</option>
                        <option value="DOC-IM-008">Partially Colored - DOC-IM-008</option>
                        <option value="DOC-IM-009">Full Colored - DOC-IM-009</option>
                    </optgroup>
                    <optgroup label="PHOTOCOPY & SCANNING">
                        <option value="DOC-PCPY-001">B&W Photocopy - DOC-PCPY-001</option>
                        <option value="DOC-PCPY-002">Partial Color Copy - DOC-PCPY-002</option>
                        <option value="DOC-PCPY-003">Full Color Copy - DOC-PCPY-003</option>
                    </optgroup>
                    <optgroup label="ID & PHOTO SERVICES">
                        <option value="IDP-PKG-001">Package A (1x1 & 2x2 Mixed) - IDP-PKG-001</option>
                        <option value="IDP-PKG-002">Package B (1x1 - 8pcs) - IDP-PKG-002</option>
                        <option value="IDP-PKG-003">Package C (2x2 - 8pcs) - IDP-PKG-003</option>
                        <option value="IDP-PKG-004">Package D (Passport Size - 5pcs) - IDP-PKG-004</option>
                        <option value="IDP-PKG-005">Package E (1.5 x 1.5 - 6pcs) - IDP-PKG-005</option>
                        <option value="IDP-PKG-006">Package F (Wallet Size - 5pcs) - IDP-PKG-006</option>
                    </optgroup>
                </select>
            </div>

            <div class="custom-option-group" id="contentTypeGroup" style="display:none;">
                <label id="contentTypeLabel">Content Type</label>
                <select class="custom-select" id="contentTypeSelect" onchange="syncPreviewFromDropdowns(); updatePrice()">
                    <option value="">Select Content Type</option>
                </select>
            </div>

            <div class="custom-option-group" id="colorVariationGroup">
                <label id="colorModeLabel">Color Variation</label>
                <select class="custom-select" id="colorMode" onchange="syncPreviewFromDropdowns(); updatePrice()">
                    <option value="0">Standard Quality</option>
                    <option value="1">High Definition</option>
                    <option value="2">Draft / Economy</option>
                </select>
            </div>

            <div class="custom-option-group" id="paperSizeGroup">
                <label id="paperSizeLabel">Paper Size</label>
                <select class="custom-select" id="paperSize" onchange="syncPreviewFromDropdowns(); updatePrice()">
                    <option value="short">Short (8.5 x 11)</option>
                    <option value="a4">A4 (8.27 x 11.69)</option>
                    <option value="long">Long (8.5 x 13)</option>
                </select>
            </div>

            <div class="custom-option-group quantity-row" id="quantityGroup">
                <label>Quantity</label>
                <div class="qty-container">
                    <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                    <input type="number" id="qtyInput" value="1" min="1" class="qty-input-styled" oninput="updatePrice()">
                    <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
            </div>
            </div>

            <div class="detail-inline-note">
                <span class="detail-inline-label">Reminder:</span>
                <p>Please double-check your selected quantity and service option before submitting your order.</p>
            </div>

            <div class="detail-options-grid detail-secondary-grid">
                <div class="custom-option-group" id="serviceOptionGroup">
                    <label id="serviceOptionLabel">Service Option</label>
                    <select class="custom-select" id="serviceOptionSelect" onchange="syncPreviewFromDropdowns(); updatePrice()">
                        <option value="">Select Option</option>
                    </select>
                </div>

                <div class="custom-option-group" id="fileTypeGroup">
                    <label>File Type</label>
                    <select class="custom-select" id="fileTypeSelect">
                        <option value="">Select File Type</option>
                        <option value="jpg">JPG / JPEG</option>
                        <option value="png">PNG</option>
                        <option value="pdf">PDF</option>
                        <option value="docx">DOCX</option>
                        <option value="psd">PSD</option>
                    </select>
                </div>

                <div class="custom-option-group" id="fileUploadGroup">
                    <label>Upload File</label>
                    <input type="file" class="custom-file-input" id="fileUploadInput" required>
                    <p class="file-upload-note">Required before Add to Cart or Place Order Now. Upload the exact final file to print; saved cart items keep their attachment.</p>
                </div>
            </div>

            <div class="detail-inline-note detail-note-block">
                <span class="detail-inline-label">Note:</span>
                <p id="serviceNotes">Files that require editing, layout adjustments, or design enhancement may have additional charges depending on the type and complexity of the service needed. For best results, please upload high-resolution files.</p>
            </div>

            <div class="service-id-row">
                <span class="service-id-label">SERVICE ID:</span>
                <span id="currentServiceId" class="service-id-value">DOC-TX-001</span>
            </div>

            <p id="productSpecs" class="specs-box">Premium 80gsm paper, crisp ink quality.</p>

            <div class="price-summary">
                <div class="price-row-flex">
                    <label class="price-option-wrapper" id="retailPriceOption">
                        <input type="radio" name="priceType" value="retail" checked onclick="updatePrice()">
                        <div class="price-item">
                            <p class="price-label">Retail</p>
                            <div class="unit-price">PHP <span id="retailAmount">0.00</span></div>
                        </div>
                    </label>
                    <label class="price-option-wrapper" id="bulkPriceOption">
                        <input type="radio" name="priceType" value="bulk" onclick="updatePrice()">
                        <div class="price-item">
                            <p class="price-label" style="color:#27ae60;">Bulk</p>
                            <div class="unit-price" style="color:#27ae60;">PHP <span id="bulkAmount">0.00</span></div>
                        </div>
                    </label>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
                <div class="total-price-box">
                    <span style="font-size: 13px; color: #666;">Total Amount:</span>
                    <h3 class="total-price" style="color: #d35400;">PHP <span id="totalAmount">0.00</span></h3>
                </div>
                <p id="bulkThresholdNote" class="bulk-threshold-note">Bulk pricing is available for 100+ pages. Confirm the final quantity before checkout.</p>
            </div>

            <div class="btn-row">
                <button class="btn-cart" onclick="addToCart()"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                <button type="button" class="btn-buy" onclick="placeOrderNow()">Place Order Now</button>
            </div>

            <div class="return-container">
                <a onclick="backToMain()" class="return-link-btn" style="cursor: pointer;">
                   <i class="fa-solid fa-arrow-left"></i> BACK TO SERVICES
                </a>
            </div>
        </div>
    </div>
</section>

<x-storefront.cart-drawer />

<div id="productModal" class="product-modal">
    <div class="product-modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">CHOOSE TYPE</h2>
        <div class="category-slider">
            <button class="modal-btn" id="modalPrev" onclick="moveSlide(-1)">&lt;</button>
            <div class="slider-viewport">
                <div class="category-track" id="categoryTrack">
                    </div>
            </div>
            <button class="modal-btn" id="modalNext" onclick="moveSlide(1)">&gt;</button>
        </div>
    </div>
</div>

<script src="{{ asset('webproj.js') }}?v={{ filemtime(public_path('webproj.js')) }}"></script>

</body>
</html>
