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
        <a onclick="jumpTo('home')">Home</a>
        <a onclick="jumpTo('products')">Services</a>
        <a onclick="jumpTo('about')">About</a>
        <a onclick="jumpTo('contact')">Contact</a>
    </nav>
    <div class="hero-signin-container" id="authContainer">
        <a href="#" class="auth-btn"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
        <span class="auth-divider">|</span>
        <a href="#" class="auth-btn"><i class="fa-regular fa-user"></i> Log In</a>
        <a onclick="toggleCart()" id="navCart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </a>
    </div>
</header>

<div class="main-content" id="pageWrapper">
    <section id="home">
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
        <h2>About Us</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center; line-height: 1.8;">
            <p>At Printify & Co., we are a leading provider of high-quality printing solutions, dedicated to helping businesses and individuals bring their ideas to life with precision and care.</p>
        </div>
    </section>

    <section id="contact" class="section">
        <h2>Contact Us</h2>
        <div style="text-align: center;">
            <p><i class="fa-solid fa-location-dot"></i> 123 Printing St., Metro Manila</p>
            <p><i class="fa-solid fa-phone"></i> +63 912 345 6789</p>
            <p><i class="fa-solid fa-envelope"></i> hello@printify.co</p>
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
                    <span style="color: #f1c40f;">★★★★★ <small style="color:#333">(Verified)</small></span>
                </div>
                <div class="reviews-horizontal-scroll" id="reviewsList"></div>
            </div>
        </div>
        <div class="detail-info-panel">
            <p id="productSpecs" class="specs-box">Material: 80gsm Bond Paper, Uncoated.</p>
            <div class="custom-option-group">
                <label>Printing Category</label>
                <select class="custom-select" id="printCategory" onchange="updatePrice()">
                    </select>
            </div>
            <div class="custom-option-group">
                <label>Color Mode</label>
                <select class="custom-select" id="colorMode" onchange="updatePrice()">
                    </select>
            </div>
            <div class="custom-option-group">
                <label>Paper Size / Product Size</label>
                <select class="custom-select" id="paperSize" onchange="updatePrice()">
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
                <a onclick="backToMain()" class="return-link-btn">
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
            <span>₱<span id="drawerTotal">0.00</span></span>
        </div>
        <button class="cart-btn-checkout" onclick="checkoutSelected()">Checkout Now</button>
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