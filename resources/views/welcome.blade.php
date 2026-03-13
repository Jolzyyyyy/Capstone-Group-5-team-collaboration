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

<header class="top-nav-bar" id="mainHeader">
    <div></div>

    <nav class="nav-horizontal">
    <a href="#home" onclick="jumpTo('home')">Home</a>
    <a href="#products" onclick="jumpTo('products')">Services</a>
    <a href="#about" onclick="jumpTo('about')">About Us</a>
    <a href="#contact" onclick="jumpTo('contact')">Contact</a>
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
                <p style="font-size: 18px; letter-spacing: 5px; text-transform: uppercase; font-weight: 300; margin-top: 10px;">
                    Crafting Your Vision into Reality
                </p>
                <p style="font-size: 13px; font-style: italic; color: #FF0000; margin-top: 20px; letter-spacing: 1px;">
                    Premium Prints • Fast Delivery • Unlimited Possibilities
                </p>
            </div>
        </div>
    </section>

    <section id="products" class="section">
        <h2 style="text-align: center; margin-top: 40px;">Our Services</h2>
        <div class="services-container">
            <div class="service-item" onclick="openModal('doc')">
                <div class="service-image-wrapper">
                    <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Document Printing">
                </div>
                <h3>DOCUMENT PRINTING</h3>
            </div>

            <div class="service-item" onclick="openModal('photo')">
                <div class="service-image-wrapper">
                    <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Photocopy & Scanning">
                </div>
                <h3>PHOTOCOPY & SCANNING</h3>
            </div>

            <div class="service-item" onclick="openModal('id')">
                <div class="service-image-wrapper">
                    <img src="{{ asset('images/Prdcts1.jpg') }}" alt="ID & Photo Services">
                </div>
                <h3>ID & PHOTO SERVICES</h3>
            </div>

            <div class="service-item" onclick="openModal('bind')">
                <div class="service-image-wrapper">
                    <img src="{{ asset('images/Prdcts1.jpg') }}" alt="Lamination & Binding">
                </div>
                <h3>LAMINATION & BINDING</h3>
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
        <h2 style="text-align: center; margin-top: 40px;">About Us</h2>
        <div class="about-grid-container" style="max-width: 1100px; margin: 0 auto; padding: 20px;">
            <div class="about-intro" style="text-align: center; margin-bottom: 40px;">
                <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">At Printify & Co., we are a leading provider of high-quality printing solutions, dedicated to helping businesses and individuals bring their ideas to life with precision and care.</p>
            </div>

            <div class="about-main-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; text-align: left;">
                <div class="about-col">
                    <div class="info-block">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-bullseye"></i> Mission & Vision</h3>
                        <p>Clearly presenting our long-term goals to help users understand our business direction and service commitment to excellence.</p>
                    </div>
                    <div class="info-block" style="margin-top: 20px;">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-star"></i> Core Values</h3>
                        <p>Quality, reliability, customer satisfaction, and timely delivery. We reinforce trust through professional craftsmanship.</p>
                    </div>
                    <div class="info-block" style="margin-top: 20px;">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-clock-rotate-left"></i> Company History</h3>
                        <p>Established with a passion for printing, showing growth and experience through major achievements in the industry.</p>
                    </div>
                </div>

                <div class="about-col">
                    <div class="info-block">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-microchip"></i> Technology & Equipment</h3>
                        <p>We showcase modern printing machines and software to emphasize quality, efficiency, and modern production standards.</p>
                    </div>
                    <div class="info-block" style="margin-top: 20px;">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-users"></i> Meet the Team</h3>
                        <p>Our key staff and management work together to humanize the business and build lasting customer trust.</p>
                    </div>
                    <div class="info-block" style="margin-top: 20px;">
                        <h3 style="color: #d35400;"><i class="fa-solid fa-certificate"></i> Why Choose Us</h3>
                        <p>Competitive advantages including affordable pricing, fast turnaround time, and high-end quality assurance.</p>
                    </div>
                </div>
            </div>

            <div class="testimonials-preview" style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                <h3 style="text-align: center;">Customer Testimonials</h3>
                <p style="font-style: italic; text-align: center;">"The best printing service in the city! Fast and very reliable." - Satisfied Client</p>
            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <h2 style="text-align: center; margin-top: 40px;">Contact Us</h2>
        <div class="contact-wrapper" style="max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 20px;">
            <div class="contact-info-side" style="text-align: left;">
                <h3 style="margin-bottom: 20px;">Get in Touch</h3>
                <p><i class="fa-solid fa-location-dot" style="color: #d35400;"></i> 123 Printing St., Metro Manila</p>
                <p><i class="fa-solid fa-phone" style="color: #d35400;"></i> +63 912 345 6789</p>
                <p><i class="fa-solid fa-envelope" style="color: #d35400;"></i> hello@printify.co</p>
                <p><i class="fa-solid fa-clock" style="color: #d35400;"></i> Operating Hours: Mon - Sat, 8:00 AM - 6:00 PM</p>
                
                <div class="social-channels" style="margin-top: 20px;">
                    <p><strong>Follow Us:</strong></p>
                    <i class="fa-brands fa-facebook fa-2x" style="margin-right: 15px; color: #3b5998;"></i>
                    <i class="fa-brands fa-instagram fa-2x" style="margin-right: 15px; color: #e1306c;"></i>
                    <i class="fa-brands fa-viber fa-2x" style="color: #7360f2;"></i>
                </div>

                <div class="map-placeholder" style="margin-top: 30px; background: #eee; height: 200px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                    <span><i class="fa-solid fa-map-location-dot"></i> Embedded Google Map Here</span>
                </div>
            </div>

            <div class="contact-form-side">
                <form id="contactForm" style="display: flex; flex-direction: column; gap: 15px;">
                    <input type="text" placeholder="Your Name" style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;">
                    <input type="email" placeholder="Email Address" style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;">
                    <select style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;">
                        <option value="">Inquiry Category</option>
                        <option value="general">General Inquiry</option>
                        <option value="quotation">Request a Quotation</option>
                        <option value="service">Service Support</option>
                    </select>
                    <textarea placeholder="Your Message" rows="5" style="padding: 12px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
                    <button type="button" style="padding: 15px; background: #d35400; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        SEND MESSAGE
                    </button>
                    <p style="font-size: 12px; color: #888; text-align: center;">An auto-response confirmation will be sent to your email.</p>
                </form>
            </div>
        </div>
    </section>

</div>

{{-- ✅ PRODUCT DETAIL SECTION (restores add-to-cart UI) --}}
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
                <h2 id="detailTitleHeader">DOCUMENT PRINTING</h2>
                <div class="stock-indicator">
                    <div class="stock-dot-glow"></div>
                    <span id="availabilityText">IN STOCK</span>
                </div>
            </div>

            
            <div class="preview-viewport">
                <button class="preview-btn prev-p" id="detailPrevBtn" onclick="movePreview(-1)">‹</button>
                <div class="preview-track" id="previewTrack">
                    </div>
                <button class="preview-btn next-p" id="detailNextBtn" onclick="movePreview(1)">›</button>
            </div>


            <div class="reviews-yellow-box">
                <div class="rev-header-inline">
                    <span>Customer Reviews</span>
                    <span style="color: #f1c40f;">★★★★★ <small style="color:#333">(Verified)</small></span>
                </div>
                <div class="reviews-horizontal-scroll" id="reviewsList">
                    </div>
            </div>
        </div>


        <div class="detail-info-panel">
            <p id="productSpecs" class="specs-box">Premium 80gsm paper, crisp ink quality.</p>
            
            <div class="custom-option-group">
                <label>Printing Category</label>
                <select class="custom-select" id="printCategory" onchange="updatePrice()">
                    <optgroup label="TEXT ONLY">
                        <option value="DOC-TX-001">B&W — DOC-TX-001</option>
                        <option value="DOC-TX-002">Partially Colored — DOC-TX-002</option>
                        <option value="DOC-TX-003">Full Colored — DOC-TX-003</option>
                    </optgroup>
                    <optgroup label="TEXT WITH IMAGE (TWI)">
                        <option value="DOC-TWI-004">B&W — DOC-TWI-004</option>
                        <option value="DOC-TWI-005">Partially Colored — DOC-TWI-005</option>
                        <option value="DOC-TWI-006">Full Colored — DOC-TWI-006</option>
                    </optgroup>
                    <optgroup label="IMAGE ONLY (IM)">
                        <option value="DOC-IM-007">B&W — DOC-IM-007</option>
                        <option value="DOC-IM-008">Partially Colored — DOC-IM-008</option>
                        <option value="DOC-IM-009">Full Colored — DOC-IM-009</option>
                    </optgroup>
                    <optgroup label="PHOTOCOPY & SCANNING">
                        <option value="DOC-PCPY-001">B&W Photocopy — DOC-PCPY-001</option>
                        <option value="DOC-PCPY-002">Partial Color Copy — DOC-PCPY-002</option>
                        <option value="DOC-PCPY-003">Full Color Copy — DOC-PCPY-003</option>
                    </optgroup>
                    <optgroup label="ID & PHOTO SERVICES">
                        <option value="IDP-PKG-001">Package A (1x1 & 2x2 Mixed) — IDP-PKG-001</option>
                        <option value="IDP-PKG-002">Package B (1x1 - 8pcs) — IDP-PKG-002</option>
                        <option value="IDP-PKG-003">Package C (2x2 - 8pcs) — IDP-PKG-003</option>
                        <option value="IDP-PKG-004">Package D (Passport Size - 5pcs) — IDP-PKG-004</option>
                        <option value="IDP-PKG-005">Package E (1.5 x 1.5 - 6pcs) — IDP-PKG-005</option>
                        <option value="IDP-PKG-006">Package F (Wallet Size - 5pcs) — IDP-PKG-006</option>
                    </optgroup>
                </select>
            </div>

            <div class="custom-option-group">
                <label>Color Mode / Variation</label>
                <select class="custom-select" id="colorMode" onchange="syncSlideWithDropdown()">
                    <option value="0">Standard Quality</option>
                    <option value="1">High Definition</option>
                    <option value="2">Draft / Economy</option>
                </select>
            </div>


            <div class="custom-option-group">
                <label>Paper Size / Product Size</label>
                <select class="custom-select" id="paperSize" onchange="updatePrice()">
                    <option value="short">Short (8.5 x 11)</option>
                    <option value="a4">A4 (8.27 x 11.69)</option>
                    <option value="long">Long (8.5 x 13)</option>
                </select>
            </div>


            <div class="custom-option-group">
                <label>Quantity</label>
                <div class="qty-container">
                    <button class="qty-btn" onclick="changeQty(-1)">-</button>
                    <input type="number" id="qtyInput" value="1" min="1" class="qty-input-styled" oninput="updatePrice()">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
            </div>

            <div class="custom-option-group" style="margin-top: 15px; margin-bottom: 15px;">
                <label style="font-size: 11px; color: #888; text-transform: uppercase; font-weight: 600;">Service Item ID:</label>
                <div style="background: #fdf2e9; border: 1px solid #d35400; padding: 8px 15px; border-radius: 6px; display: inline-block; width: 100%;">
                    <span id="currentServiceId" style="font-family: 'Courier New', Courier, monospace; font-weight: bold; color: #d35400; font-size: 16px;">DOC-TX-001</span>
                </div>
            </div>

            <div class="price-summary">
                <div class="price-row-flex">
                    <label class="price-option-wrapper">
                        <input type="radio" name="priceType" value="retail" checked onclick="updatePrice()">
                        <div class="price-item">
                            <p class="price-label">Retail</p>
                            <div class="unit-price">₱<span id="retailAmount">0.00</span></div>
                        </div>
                    </label>

                    <label class="price-option-wrapper">
                        <input type="radio" name="priceType" value="bulk" onclick="updatePrice()">
                        <div class="price-item">
                            <p class="price-label" style="color:#27ae60;">Bulk</p>
                            <div class="unit-price" style="color:#27ae60;">₱<span id="bulkAmount">0.00</span></div>
                        </div>
                    </label>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">

                <div class="total-price-box">
                    <span style="font-size: 13px; color: #666;">Total Amount:</span>
                    <h3 class="total-price" style="color: #d35400;">₱<span id="totalAmount">0.00</span></h3>
                </div>
            </div>

            <div class="btn-row">
                <button class="btn-cart" onclick="addToCart()"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                <button class="btn-buy">Place Order Now</button>
            </div>


            <div class="return-container">
                <a onclick="backToMain()" class="return-link-btn" style="cursor: pointer;">
                   <i class="fa-solid fa-arrow-left"></i> BACK TO SERVICES
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ✅ CART DRAWER --}}
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
            <span>₱<span id="drawerTotal">0.00</span></span>
        </div>

        <button class="cart-btn-checkout" onclick="checkoutSelected()">Checkout Now</button>
    </div>
</div>

{{-- ✅ SERVICE CATEGORY MODAL --}}
<div id="productModal" class="product-modal">
    <div class="product-modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">CHOOSE TYPE</h2>

        <div class="category-slider">
            <button class="modal-btn" id="modalPrev" onclick="moveSlide(-1)">❮</button>

            <div class="slider-viewport">
                <div class="category-track" id="categoryTrack">
                    </div>
            </div>

            <button class="modal-btn" id="modalNext" onclick="moveSlide(1)">❯</button>
        </div>
    </div>
</div>

<script src="{{ asset('webproj.js') }}"></script>

</body>
</html>
