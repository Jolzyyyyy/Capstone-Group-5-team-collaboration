<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Printing Business Solution | Printify & Co.</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('webproj.css') }}">
</head>
<body>

<header class="top-nav-bar" id="mainHeader">
    <div class="nav-left-spacer"></div>

    <nav class="nav-horizontal">
        <a href="#home" onclick="jumpTo('home')">HOME</a>
        <a href="#about" onclick="jumpTo('about')">ABOUT US</a>
        <a href="#products" onclick="jumpTo('products')">SERVICES</a>
        <a href="#contact" onclick="jumpTo('contact')">CONTACT US</a>
    </nav>

    <div class="hero-signin-container" id="authContainer">
        <div class="nav-search-box">
            <input type="text" id="navSearchInput" placeholder="Search..." autocomplete="off">
            <button type="button" class="nav-search-btn">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <a href="#" id="navHeart" class="nav-icon-link" aria-label="Wishlist">
            <img src="{{ asset('images/Heart.svg') }}" alt="Heart" class="nav-svg-icon">
        </a>

        <a href="javascript:void(0)" onclick="toggleCart()" id="navCart" class="nav-icon-link" aria-label="Cart">
            <img src="{{ asset('images/Shopping cart.svg') }}" alt="Cart" class="nav-svg-icon">
            <span class="cart-badge" id="cartBadge">0</span>
        </a>

        @auth
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="nav-icon-link" title="Admin Panel">
            <img src="{{ asset('images/user-logged.svg') }}" alt="Admin Profile" class="nav-svg-icon" style="filter: hue-rotate(200deg);"> 
            <span class="account-label text-blue-600 font-bold">ADMIN: {{ Auth::user()->name }}</span>
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="nav-icon-link">
            <img src="{{ asset('images/user-logged.svg') }}" alt="Profile" class="nav-svg-icon">
            <span class="account-label">{{ Auth::user()->name }}</span>
        </a>
    @endif
@else
    <a href="{{ route('login') }}" id="navUserPlus" class="nav-icon-link">
        <img src="{{ asset('images/User plus.svg') }}" alt="User Plus" class="nav-svg-icon">
        <span class="account-label">ACCOUNT</span>
    </a>
@endauth
    </div>
</header>

    <div class="main-content" id="pageWrapper">

        <section id="home" class="section active">
            <div class="hero-container">

                <div class="hero-slider">
                    <div class="hero-slide active" style="background-image:url('{{ asset('images/Homesld1.jpg') }}')"></div>
                    <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld2.jpg') }}')"></div>
                    <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld3.jpg') }}')"></div>
                </div>

                <div class="hero-overlay"></div>

                <div class="hero-text animate" id="homeText">
                    <h1>PRINTIFY &amp; CO.</h1>
                    <p class="hero-subtitle">CRAFTING YOUR VISION INTO REALITY</p>
                    <p class="hero-tagline">PREMIUM PRINTS • FAST DELIVERY • UNLIMITED POSSIBILITIES</p>
                </div>

                <div class="slide-indicators">
                    <div class="dot active" onclick="jumpToHero(0)"></div>
                    <div class="dot" onclick="jumpToHero(1)"></div>
                    <div class="dot" onclick="jumpToHero(2)"></div>
                </div>

            </div>
        </section>

        <section id="products" class="section">
            <h2>Our Services</h2>
            <div class="services-container">
                <div class="service-item" onclick="openModal('doc')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Document Printing">
                    </div>
                    <h3>DOCUMENT PRINTING</h3>
                </div>

                <div class="service-item" onclick="openModal('photo')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Photocopy &amp; Scanning">
                    </div>
                    <h3>PHOTOCOPY &amp; SCANNING</h3>
                </div>

                <div class="service-item" onclick="openModal('id')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="ID &amp; Photo Services">
                    </div>
                    <h3>ID &amp; PHOTO SERVICES</h3>
                </div>

                <div class="service-item" onclick="openModal('bind')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Lamination &amp; Binding">
                    </div>
                    <h3>LAMINATION &amp; BINDING</h3>
                </div>

                <div class="service-item" onclick="openModal('largeformat')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Large Format Printing">
                    </div>
                    <h3>LARGE FORMAT PRINTING</h3>
                </div>

                <div class="service-item" onclick="openModal('special')">
                    <div class="service-image-wrapper">
                        <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Custom Special Printing">
                    </div>
                    <h3>CUSTOM SPECIAL PRINTING</h3>
                </div>
            </div>
        </section>

        <section id="about" class="section">
            <h2>About Us</h2>
            <div class="about-grid-container">
                <div class="about-intro">
                    <p>
                        At Printify &amp; Co., we are a leading provider of high-quality printing solutions,
                        dedicated to helping businesses and individuals bring their ideas to life with precision and care.
                    </p>
                </div>

                <div class="about-main-content">
                    <div class="about-col">
                        <div class="info-block">
                            <h3><i class="fa-solid fa-bullseye"></i> Mission &amp; Vision</h3>
                            <p>
                                Clearly presenting our long-term goals to help users understand our business direction
                                and service commitment to excellence.
                            </p>
                        </div>

                        <div class="info-block">
                            <h3><i class="fa-solid fa-star"></i> Core Values</h3>
                            <p>
                                Quality, reliability, customer satisfaction, and timely delivery.
                                We reinforce trust through professional craftsmanship.
                            </p>
                        </div>

                        <div class="info-block">
                            <h3><i class="fa-solid fa-clock-rotate-left"></i> Company History</h3>
                            <p>
                                Established with a passion for printing, showing growth and experience
                                through major achievements in the industry.
                            </p>
                        </div>
                    </div>

                    <div class="about-col">
                        <div class="info-block">
                            <h3><i class="fa-solid fa-microchip"></i> Technology &amp; Equipment</h3>
                            <p>
                                We showcase modern printing machines and software to emphasize quality,
                                efficiency, and modern production standards.
                            </p>
                        </div>

                        <div class="info-block">
                            <h3><i class="fa-solid fa-users"></i> Meet the Team</h3>
                            <p>
                                Our key staff and management work together to humanize the business
                                and build lasting customer trust.
                            </p>
                        </div>

                        <div class="info-block">
                            <h3><i class="fa-solid fa-certificate"></i> Why Choose Us</h3>
                            <p>
                                Competitive advantages including affordable pricing, fast turnaround time,
                                and high-end quality assurance.
                            </p>
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
            <h2>Contact Us</h2>
            <div class="contact-wrapper">
                <div class="contact-info-side">
                    <h3>Get in Touch</h3>
                    <p><i class="fa-solid fa-location-dot"></i> 123 Printing St., Metro Manila</p>
                    <p><i class="fa-solid fa-phone"></i> +63 912 345 6789</p>
                    <p><i class="fa-solid fa-envelope"></i> hello@printify.co</p>
                    <p><i class="fa-solid fa-clock"></i> Operating Hours: Mon - Sat, 8:00 AM - 6:00 PM</p>

                    <div class="social-channels">
                        <p><strong>Follow Us:</strong></p>
                        <i class="fa-brands fa-facebook fa-2x"></i>
                        <i class="fa-brands fa-instagram fa-2x"></i>
                        <i class="fa-brands fa-viber fa-2x"></i>
                    </div>

                    <div class="map-placeholder">
                        <span><i class="fa-solid fa-map-location-dot"></i> Embedded Google Map Here</span>
                    </div>
                </div>

                <div class="contact-form-side">
                    <form id="contactForm">
                        <input type="text" placeholder="Your Name">
                        <input type="email" placeholder="Email Address">
                        <select>
                            <option value="">Inquiry Category</option>
                            <option value="general">General Inquiry</option>
                            <option value="quotation">Request a Quotation</option>
                            <option value="service">Service Support</option>
                        </select>
                        <textarea placeholder="Your Message" rows="5"></textarea>
                        <button type="button">SEND MESSAGE</button>
                        <p>An auto-response confirmation will be sent to your email.</p>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <section id="productDetail" class="detail-section">
        <div class="detail-container">
            <div class="detail-sidebar" id="detailSidebar">
                <div class="sidebar-viewport">
                    <div class="sidebar-track" id="sidebarTrack"></div>
                </div>
            </div>

            <div class="detail-images-slider">
                <div class="product-title-header">
                    <h2 id="detailTitleHeader">DOCUMENT PRINTING</h2>

                    <div class="stock-indicator">
                        <div class="stock-dot-glow"></div>
                        <span id="availabilityText">IN STOCK</span>
                    </div>
                </div>

                <div class="preview-viewport">
                    <button class="preview-btn prev-p" id="detailPrevBtn" onclick="movePreview(-1)">‹</button>
                    <div class="preview-track" id="previewTrack"></div>
                    <button class="preview-btn next-p" id="detailNextBtn" onclick="movePreview(1)">›</button>
                </div>

                <div class="reviews-yellow-box">
                    <div class="rev-header-inline">
                        <span>Customer Reviews</span>
                        <span class="review-stars">★★★★★ <small class="review-verified">(Verified)</small></span>
                    </div>
                    <div class="reviews-horizontal-scroll" id="reviewsList"></div>
                </div>
            </div>

            <div class="detail-info-panel">
                <p id="productSpecs" class="specs-box">Premium 80gsm paper, crisp ink quality.</p>

                <div class="custom-option-group">
                    <label>Printing Category</label>
                    <select class="custom-select" id="printCategory" onchange="updatePrice()">
                        <optgroup label="TEXT ONLY">
                            <option value="DOC-TX-001">B&amp;W — DOC-TX-001</option>
                            <option value="DOC-TX-002">Partially Colored — DOC-TX-002</option>
                            <option value="DOC-TX-003">Full Colored — DOC-TX-003</option>
                        </optgroup>
                        <optgroup label="TEXT WITH IMAGE (TWI)">
                            <option value="DOC-TWI-004">B&amp;W — DOC-TWI-004</option>
                            <option value="DOC-TWI-005">Partially Colored — DOC-TWI-005</option>
                            <option value="DOC-TWI-006">Full Colored — DOC-TWI-006</option>
                        </optgroup>
                        <optgroup label="IMAGE ONLY (IM)">
                            <option value="DOC-IM-007">B&amp;W — DOC-IM-007</option>
                            <option value="DOC-IM-008">Partially Colored — DOC-IM-008</option>
                            <option value="DOC-IM-009">Full Colored — DOC-IM-009</option>
                        </optgroup>
                        <optgroup label="PHOTOCOPY &amp; SCANNING">
                            <option value="DOC-PCPY-001">B&amp;W Photocopy — DOC-PCPY-001</option>
                            <option value="DOC-PCPY-002">Partial Color Copy — DOC-PCPY-002</option>
                            <option value="DOC-PCPY-003">Full Color Copy — DOC-PCPY-003</option>
                        </optgroup>
                        <optgroup label="ID &amp; PHOTO SERVICES">
                            <option value="IDP-PKG-001">Package A (1x1 &amp; 2x2 Mixed) — IDP-PKG-001</option>
                            <option value="IDP-PKG-002">Package B (1x1 - 8pcs) — IDP-PKG-002</option>
                            <option value="IDP-PKG-003">Package C (2x2 - 8pcs) — IDP-PKG-003</option>
                            <option value="IDP-PKG-004">Package D (Passport Size - 5pcs) — IDP-PKG-004</option>
                            <option value="IDP-PKG-005">Package E (1.5 x 1.5 - 6pcs) — IDP-PKG-005</option>
                            <option value="IDP-PKG-006">Package F (Wallet Size - 5pcs) — IDP-PKG-006</option>
                        </optgroup>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Color Variation</label>
                    <select class="custom-select" id="colorMode" onchange="syncSlideWithDropdown()">
                        <option value="0">Standard Quality</option>
                        <option value="1">High Definition</option>
                        <option value="2">Draft / Economy</option>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Paper Size</label>
                    <select class="custom-select" id="paperSize" onchange="updatePrice()">
                        <optgroup label="Standard Sizes">
                            <option value="short">Short (8.5 x 11)</option>
                            <option value="a4">A4 (8.27 x 11.69)</option>
                            <option value="long">Long (8.5 x 13)</option>
                        </optgroup>
                        <optgroup label="Single Photo Sizes">
                            <option value="IDP-SP-001">2R — IDP-SP-001</option>
                            <option value="IDP-SP-002">3R — IDP-SP-002</option>
                            <option value="IDP-SP-003">4R — IDP-SP-003</option>
                            <option value="IDP-SP-004">5R — IDP-SP-004</option>
                            <option value="IDP-SP-005">6R — IDP-SP-005</option>
                            <option value="IDP-SP-006">8R — IDP-SP-006</option>
                            <option value="IDP-SP-007">A4 — IDP-SP-007</option>
                        </optgroup>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Quantity</label>
                    <div class="qty-container">
                        <button class="qty-btn" type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" id="qtyInput" value="1" min="1" class="qty-input-styled" oninput="updatePrice()">
                        <button class="qty-btn" type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="custom-option-group reminder-group">
                    <label>Reminder:</label>
                    <div class="reminder-box">
                        <ul class="reminder-list">
                            <li>Please double-check your selected Quantity, selected Service Option and uploaded file before submitting your order.</li>
                        </ul>
                    </div>
                </div>

                <div class="custom-option-group">
                    <label>FILE UPLOAD</label>
                    <div class="file-upload-row">
                        <div class="file-upload-box" id="fileUploadBox">
                            <input type="file" id="fileUpload" name="fileUpload">
                            <span class="file-upload-name" id="fileUploadName">No file chosen</span>
                            <button type="button" class="file-upload-clear" id="fileUploadClear" aria-label="Clear file" title="Remove file">×</button>
                        </div>
                    </div>
                </div>

                <div class="custom-option-group">
                    <label>Service Option:</label>
                    <select class="custom-select" id="serviceOption">
                        <option value="">Select Option</option>
                        <option value="enhancement">Enhancement</option>
                        <option value="layout">Layout</option>
                        <option value="design">Design</option>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>File Type:</label>
                    <select class="custom-select" id="fileType">
                        <option value="">Select File Type</option>
                        <option value="pdf">PDF</option>
                        <option value="doc">DOC/DOCX</option>
                        <option value="img">JPG/PNG</option>
                    </select>
                </div>

                <div class="custom-option-group note-group">
                    <label>Note:</label>
                    <div class="note-box">
                        <p>
                            Files that require editing, layout adjustments, or design enhancements may have additional
                            charges depending on the type and complexity of the service needed. For best results,
                            please upload high-resolution files.
                        </p>
                    </div>
                </div>

                <div class="custom-option-group">
                    <label>SERVICE ID:</label>
                    <div>
                        <span id="currentServiceId">DOC-TX-001</span>
                    </div>
                </div>

                <div class="price-summary">
                    <div class="price-row-flex">
                        <label class="price-option-wrapper">
                            <input type="radio" name="priceType" value="retail" checked onclick="updatePrice()">
                            <div class="price-item">
                                <p class="price-label price-type-label">Retail</p>
                                <div class="unit-price">₱<span id="retailAmount">0.00</span></div>
                            </div>
                        </label>

                        <label class="price-option-wrapper">
                            <input type="radio" name="priceType" value="bulk" onclick="updatePrice()">
                            <div class="price-item">
                                <p class="price-label price-type-label">Bulk</p>
                                <div class="unit-price">₱<span id="bulkAmount">0.00</span></div>
                            </div>
                        </label>
                    </div>

                    <hr>

                    <div class="total-price-box">
                        <span>Total Amount:</span>
                        <h3 class="total-price">₱<span id="totalAmount">0.00</span></h3>
                    </div>
                </div>

                <div class="btn-row">
                    <button class="btn-cart" id="btnAddToCart" onclick="addToCart()">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn-buy" id="btnPlaceOrderNow" onclick="placeOrderNow()">Place Order Now</button>
                </div>

                <div class="return-container">
                    <a href="javascript:void(0)" onclick="backToMain()" class="return-link-btn">
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

        <template id="cartItemTemplate">
            <div class="cart-item">
                <label class="cart-item-check-wrap">
                    <input type="checkbox" class="cart-item-check" checked>
                </label>

                <div class="cart-item-thumb">
                    <img src="" alt="Cart Item">
                </div>

                <div class="cart-item-info">
                    <div class="cart-item-title"></div>
                    <div class="cart-item-meta"></div>
                    <div class="cart-item-qty-price"></div>
                    <button type="button" class="cart-item-remove">REMOVE</button>
                </div>
            </div>
        </template>

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
                <span>₱<span id="drawerTotal">0.00</span></span>
            </div>

            <button class="cart-btn-checkout" id="btnCheckoutNow" onclick="checkoutSelected()">Checkout Now</button>
        </div>
    </div>

    <div id="productModal" class="product-modal">
        <div class="product-modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">CHOOSE TYPE</h2>

            <div class="category-slider">
                <button class="modal-btn" id="modalPrev" onclick="moveSlide(-1)">❮</button>

                <div class="slider-viewport">
                    <div class="category-track" id="categoryTrack"></div>
                </div>

                <button class="modal-btn" id="modalNext" onclick="moveSlide(1)">❯</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('webproj.js') }}"></script>
</body>
</html>