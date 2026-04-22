<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printing Business Solution | Printify & Co.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('webproj.css') }}">
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
        ['key' => 'doc', 'title' => 'DOCUMENT PRINTING', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
        ['key' => 'photo', 'title' => 'PHOTOCOPY & SCANNING', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
        ['key' => 'id', 'title' => 'ID & PHOTO SERVICES', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
        ['key' => 'bind', 'title' => 'LAMINATION & BINDING', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
        ['key' => 'largeformat', 'title' => 'LARGE FORMAT PRINTING', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
        ['key' => 'special', 'title' => 'CUSTOM SPECIAL PRINTING', 'image' => $resolveImageUrl('images/Prdcts1.jpg')],
    ];
@endphp

<header class="top-nav-bar" id="mainHeader">
    <div class="nav-spacer" aria-hidden="true"></div>
    <nav class="nav-horizontal">
    <a href="#home" data-section="home" class="nav-link is-active" onclick="jumpTo('home'); return false;">Home</a>
    <a href="#about" data-section="about" class="nav-link" onclick="jumpTo('about'); return false;">About Us</a>
    <a href="#services" data-section="services" class="nav-link" onclick="jumpTo('services'); return false;">Services</a>
    <a href="#contact" data-section="contact" class="nav-link" onclick="jumpTo('contact'); return false;">Contact</a>
    </nav>

    <div class="hero-signin-container" id="authContainer">
        @if (Route::has('login'))
            @auth
                {{-- Lalabas lang ito kung naka-login na ang user --}}
                <a href="{{ url('/dashboard') }}" class="auth-btn"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <span class="auth-divider">|</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <a href="{{ route('logout') }}" class="auth-btn" 
                       onclick="event.preventDefault(); this.closest('form').submit();">
                       <i class="fa-solid fa-right-from-bracket"></i> Log Out
                    </a>
                </form>
            @else
                {{-- Lalabas ito kung hindi pa naka-login --}}
                <a href="{{ route('register') }}" class="auth-btn"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
                <span class="auth-divider">|</span>
                <a href="{{ route('login') }}" class="auth-btn"><i class="fa-regular fa-user"></i> Log In</a>
            @endauth
        @endif

        <a onclick="toggleCart()" id="navCart" style="cursor: pointer;">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </a>
    </div>
</header>

<div class="main-content" id="pageWrapper">
    <section id="home" class="section active">
        <div class="hero-container">
            <div class="hero-slide active" style="background-image:url('{{ asset('images/Homesld1.jpg') }}')"></div>
            <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld2.jpg') }}')"></div>
            <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld3.jpg') }}')"></div>
            
            <div class="slide-indicators">
                <div class="dot active" onclick="jumpToHero(0)"></div>
                <div class="dot" onclick="jumpToHero(1)"></div>
                <div class="dot" onclick="jumpToHero(2)"></div>
            </div>

            <div class="hero-text animate" id="homeText">
                <h1>Printify & Co.</h1>
                <p class="hero-tagline">
                    Crafting Your Vision into Reality
                </p>
                <p class="hero-subtitle">
                    Premium Prints | Fast Delivery | Unlimited Possibilities
                </p>
            </div>
        </div>
    </section>

    <section id="services" class="section">
        <h2 class="services-section-title">What We Print</h2>
        <p class="services-section-intro">Print solutions designed for school, business, events, and everyday document needs.</p>
        <div class="services-container">
            @foreach ($serviceCards as $service)
                <div class="service-item" onclick="openModal('{{ $service['key'] }}')">
                    <div class="service-image-wrapper">
                        <img
                            src="{{ $service['image'] }}"
                            alt="{{ $service['title'] }}"
                            onerror="this.onerror=null;this.src='{{ asset('images/Prdcts1.jpg') }}';"
                        >
                    </div>
                    <h3>{{ $service['title'] }}</h3>
                </div>
            @endforeach
        </div>
    </section>

    <section id="about" class="section">
        <h2 class="about-section-title">Who We Are</h2>
        <div class="about-grid-container">
            <div class="about-intro">
                <p>A trusted print partner focused on quality output, fast turnaround, and dependable service.</p>
            </div>

            <div class="about-main-content">
                <div class="about-col">
                    <div class="info-block">
                        <h3><i class="fa-solid fa-bullseye"></i> Mission &amp; Vision</h3>
                        <p>Clearly presenting our long-term goals to help users understand our business direction and service commitment to excellence.</p>
                    </div>
                    <div class="info-block">
                        <h3><i class="fa-solid fa-star"></i> Core Values</h3>
                        <p>Quality, reliability, customer satisfaction, and timely delivery. We reinforce trust through professional craftsmanship.</p>
                    </div>
                    <div class="info-block">
                        <h3><i class="fa-solid fa-clock-rotate-left"></i> Company History</h3>
                        <p>Established with a passion for printing, showing growth and experience through major achievements in the industry.</p>
                    </div>
                </div>

                <div class="about-col">
                    <div class="info-block">
                        <h3><i class="fa-solid fa-microchip"></i> Technology &amp; Equipment</h3>
                        <p>We showcase modern printing machines and software to emphasize quality, efficiency, and modern production standards.</p>
                    </div>
                    <div class="info-block">
                        <h3><i class="fa-solid fa-users"></i> Meet the Team</h3>
                        <p>Our key staff and management work together to humanize the business and build lasting customer trust.</p>
                    </div>
                    <div class="info-block">
                        <h3><i class="fa-solid fa-certificate"></i> Why Choose Us</h3>
                        <p>Competitive advantages including affordable pricing, fast turnaround time, and high-end quality assurance.</p>
                    </div>
                </div>
            </div>

            <div class="testimonials-preview">
                <h3>Customer Testimonials</h3>
                <p>"The best printing service in the city! Fast and very reliable." - Satisfied Client</p>
            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <h2 class="contact-section-title">Let's Connect</h2>
        <p class="contact-section-intro">Send us your inquiry, request a quote, or ask for guidance on the best print option for your project.</p>
        <div class="contact-wrapper">
            <div class="contact-info-side">
                <div class="contact-info-card">
                    <h3 class="contact-panel-title">Get in Touch</h3>
                    <div class="contact-detail-list">
                        <p><i class="fa-solid fa-location-dot"></i> 123 Printing St., Metro Manila</p>
                        <p><i class="fa-solid fa-phone"></i> +63 912 345 6789</p>
                        <p><i class="fa-solid fa-envelope"></i> hello@printify.co</p>
                        <p><i class="fa-solid fa-clock"></i> Operating Hours: Mon - Sat, 8:00 AM - 6:00 PM</p>
                    </div>

                    <div class="social-channels">
                        <p><strong>Follow Us</strong></p>
                        <div class="social-links-row">
                            <a href="#" class="social-link social-facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="social-link social-instagram" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="social-link social-viber" aria-label="Viber"><i class="fa-brands fa-viber"></i></a>
                        </div>
                    </div>

                    <div class="map-placeholder">
                        <span><i class="fa-solid fa-map-location-dot"></i> Embedded Google Map Here</span>
                    </div>
                </div>
            </div>

            <div class="contact-form-side">
                <div class="contact-form-card">
                    <h3 class="contact-panel-title">Send a Message</h3>
                    <p class="contact-form-intro">Tell us what you need and our team will get back to you with the right printing guidance.</p>
                    <form id="contactForm" class="contact-form">
                        <input type="text" placeholder="Your Name">
                        <input type="email" placeholder="Email Address">
                        <select>
                        <option value="">Inquiry Category</option>
                        <option value="general">General Inquiry</option>
                        <option value="quotation">Request a Quotation</option>
                        <option value="service">Service Support</option>
                        </select>
                        <textarea placeholder="Your Message" rows="5"></textarea>
                        <button type="button" class="contact-submit-btn">Send Message</button>
                        <p class="contact-form-note">An auto-response confirmation will be sent to your email.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
                <p>Please double-check your selected quantity, selected service option, and uploaded file before submitting your order.</p>
            </div>

            <div class="custom-option-group stacked-group" id="fileUploadGroup">
                <label>File Upload</label>
                <input type="file" id="fileUploadInput" class="custom-file-input">
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
                <p id="bulkThresholdNote" class="bulk-threshold-note">Bulk pricing is available for 100+ pages. Upload a print-ready file and confirm the final page count before checkout.</p>
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

<div class="cart-drawer-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-header">
        <h2>Your Shopping Cart</h2>
        <span class="close-cart" onclick="toggleCart()">&times;</span>
    </div>
    <div class="cart-items-list" id="cartItemsList"></div>
    <div class="cart-footer">
        <div class="voucher-container">
            <div class="voucher-input-group">
                <input type="text" id="voucherCode" placeholder="Enter Voucher Code">
                <button class="apply-voucher-btn" onclick="applyVoucher()">Apply</button>
            </div>
            <p id="voucherMsg" class="voucher-message"></p>
        </div>
        <div class="cart-total-row">
            <span>Total</span>
            <span>PHP <span id="drawerTotal">0.00</span></span>
        </div>
        <button class="cart-btn-checkout" onclick="checkoutSelected()">Checkout Now</button>
    </div>
</div>

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

<script src="{{ asset('webproj.js') }}"></script>

</body>
</html>
