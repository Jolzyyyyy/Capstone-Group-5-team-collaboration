<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Printing Business Solution | Printify</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('webproj.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body data-logged-in="{{ auth()->check() ? 'true' : 'false' }}">
    <header class="top-nav-bar" id="mainHeader">
    <div class="nav-left">
        <a href="{{ route('home') }}" class="brand">PRINTIFY & CO.</a>
    </div>

    <nav class="nav-horizontal">
        <a href="#" onclick="jumpTo('home'); return false;">HOME</a>
        <a href="#" onclick="jumpTo('about'); return false;">ABOUT</a>
        <a href="#" onclick="jumpTo('services'); return false;">SERVICES</a>
        <a href="#" onclick="jumpTo('contact'); return false;">CONTACT</a>
    </nav>

    <div class="hero-signin-container">
    <a href="{{ route('cart.index') }}" id="navCart" title="Cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-badge" id="cartBadge">0</span>
    </a>

    @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="auth-btn" title="Admin Panel">
                <i class="fa-solid fa-user-shield"></i>
                <span>ADMIN: {{ auth()->user()->name }}</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="auth-btn username" title="Profile">
                <i class="fa-solid fa-user"></i>
                <span>{{ auth()->user()->name }}</span>
            </a>
        @endif

        <span class="auth-divider">|</span>

        @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>LOGOUT</span>
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>LOGOUT</span>
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}" id="navUserPlus" class="auth-btn">
            <i class="fa-solid fa-user-plus"></i>
            <span>ACCOUNT</span>
        </a>
    @endauth
    </div>
    </header>

    <div class="main-content" id="pageWrapper">
        {{-- HOME --}}
        <section id="home" class="section active">
            <div class="hero-container">

                <div class="hero-slide active" style="background-image:url('{{ asset('images/Homesld1.jpg') }}')"></div>
                <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld2.jpg') }}')"></div>
                <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld3.jpg') }}')"></div>

                <!-- HERO TEXT -->
                <div class="hero-overlay">
                    <h1>PRINTIFY & CO.</h1>
                    <p class="hero-tagline">CRAFTING YOUR VISION INTO REALITY</p>
                    <p class="hero-sub">PREMIUM PRINTS • FAST DELIVERY • UNLIMITED POSSIBILITIES</p>
                </div>

                <div class="slide-indicators">
                    <div class="dot active" onclick="jumpToHero(0)"></div>
                    <div class="dot" onclick="jumpToHero(1)"></div>
                    <div class="dot" onclick="jumpToHero(2)"></div>
                </div>

            </div>
        </section>

        {{-- ABOUT --}}
        <section id="about" class="section">
            <div class="about-section-wrap">
                <h2 class="about-heading">About Us</h2>

                <p class="about-description">
                    At Printify &amp; Co., we are a leading provider of high-quality printing solutions,
                    dedicated to helping businesses and individuals bring their ideas to life with precision and care.
                </p>

                <div class="about-feature-grid">
                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-bullseye"></i> Mission &amp; Vision</h3>
                        <p>
                            Clearly presenting our long-term goals to help users understand our business
                            direction and service commitment to excellence.
                        </p>
                    </div>

                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-gears"></i> Technology &amp; Equipment</h3>
                        <p>
                            We showcase modern printing machines and software to emphasize quality,
                            efficiency, and modern production standards.
                        </p>
                    </div>

                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-star"></i> Core Values</h3>
                        <p>
                            Quality, reliability, customer satisfaction, and timely delivery.
                            We reinforce trust through professional craftsmanship.
                        </p>
                    </div>

                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-users"></i> Meet the Team</h3>
                        <p>
                            Our key staff and management work together to humanize the business
                            and build lasting customer trust.
                        </p>
                    </div>

                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-clock-rotate-left"></i> Company History</h3>
                        <p>
                            Established with a passion for printing, showing growth and experience
                            through major achievements in the industry.
                        </p>
                    </div>

                    <div class="about-feature-item">
                        <h3 class="about-feature-title"><i class="fa-solid fa-award"></i> Why Choose Us</h3>
                        <p>
                            Competitive advantages including affordable pricing, fast turnaround time,
                            and high-end quality assurance.
                        </p>
                    </div>
                </div> <!-- closes .about-feature-grid -->

                   <div class="testimonials-block">
                        
                        <h3 class="testimonials-title">Customer Testimonials</h3>

                        <p class="testimonial-line">
                            <span class="testimonial-quote">
                                The best printing service in the city! Fast and very reliable.
                            </span>
                        </p>

                            <span class="testimonial-author">- Satisfied Client</span>
                    </div>
            </div>
        </section>

        {{-- SERVICES --}}
        <section id="services" class="section">
            <h2 class="services-heading">Our Services</h2>
            <p class="services-count">Total services: {{ $services->count() }}</p>

            <div class="services-container">
                @foreach($services as $service)
                    <div class="service-item" onclick="openModal({{ $service->id }})">
                        <div class="service-image-wrapper">
                            <img
                                src="{{ asset('images/Prdcts1.jpg') }}"
                                alt="{{ $service->name }}"
                            >
                            <span class="service-heart">
                                    <i class="fa-regular fa-heart"></i>
                            </span>
                        </div>
                        <h3>{{ strtoupper($service->name) }}</h3>
                    </div>
                @endforeach
            </div>
        </section>
        {{-- CONTACT --}}
        <section id="contact" class="section">
            <h2 style="text-align: center; margin-top: 40px;">Contact Us</h2>

            <div class="contact-wrapper" style="max-width: 1100px; margin: 0 auto; padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div class="contact-info-side">
                    <p><i class="fa-solid fa-location-dot"></i> Your business address here</p>
                    <p><i class="fa-solid fa-phone"></i> Your contact number here</p>
                    <p><i class="fa-solid fa-envelope"></i> Your email here</p>
                    <p><i class="fa-regular fa-clock"></i> Operating Hours: Mon - Sat, 8:00 AM - 6:00 PM</p>

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

    {{-- PRODUCT DETAIL SECTION --}}
    <section id="productDetail" class="detail-section">
        <div class="detail-container">
            <div class="detail-sidebar" id="detailSidebar">
                <div class="sidebar-viewport">
                    <div class="sidebar-track" id="sidebarTrack"></div>
                </div>
            </div>

            <div class="detail-images-slider">
                <div class="product-title-header">
                    <h2 id="detailTitleHeader">Service Details</h2>
                </div>

                <div class="preview-viewport">
                    <button type="button" class="preview-btn" id="detailPrevBtn" onclick="movePreview(-1)">❮</button>
                    <div class="preview-track" id="previewTrack"></div>
                    <button type="button" class="preview-btn" id="detailNextBtn" onclick="movePreview(1)">❯</button>
                </div>
            </div>

            <div class="detail-info-panel">
                <div class="specs-box" id="productSpecs">
                    Select a service option to view inclusions and specifications.
                </div>

                <div class="custom-option-group">
                    <label>Printing Category</label>
                    <select id="printCategory" class="custom-select" onchange="syncPreviewFromDropdowns(); updatePrice();">
                        <option value="text_only">Text Only</option>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Color Variation</label>
                    <select id="colorMode" class="custom-select" onchange="syncPreviewFromDropdowns(); updatePrice();">
                        <option value="bw">B&W</option>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Paper Size</label>
                    <select id="paperSize" class="custom-select" onchange="syncPreviewFromDropdowns(); updatePrice();">
                        <option value="short">Short (8.5 x 11)</option>
                    </select>
                </div>

                <div class="custom-option-group">
                    <label>Quantity</label>
                    <div class="qty-box">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" id="qtyInput" value="1" min="1" onchange="updatePrice()">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="custom-option-group">
                    <label>SERVICE ID</label>
                    <div>
                        <span id="currentServiceId">DOC-TX-001</span>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">

                <div class="price-options-container">
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

                <div class="total-price-box">
                    <span style="font-size: 13px; color: #666;">Total Amount:</span>
                    <h3 class="total-price" style="color: #d35400;">₱<span id="totalAmount">0.00</span></h3>
                </div>

                <div class="btn-row">
                    <button class="btn-cart" onclick="addToCart()">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn-buy">Place Order Now</button>
                </div>

                <div class="return-container">
                    <a onclick="backToMain()" class="return-link-btn" style="cursor: pointer;">
                        <i class="fa-solid fa-arrow-left"></i> BACK TO SERVICES
                    </a>
                </div>
            </div>

            <div class="custom-option-group">
                <label>SERVICE NAME:</label>
                <div>
                    <span id="currentServiceName">Document Printing</span>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">

            <div class="price-options-container">
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

            <div class="custom-option-group">
                <label>QUANTITY:</label>
                <div class="qty-box">
                    <button type="button" onclick="changeQty(-1)">−</button>
                    <input type="number" id="quantityInput" value="1" min="1" onchange="updatePrice()">
                    <button type="button" onclick="changeQty(1)">+</button>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">

            <div class="total-price-box">
                <span style="font-size: 13px; color: #666;">Total Amount:</span>
                <h3 class="total-price" style="color: #d35400;">₱<span id="totalAmount">0.00</span></h3>
            </div>

            <div class="btn-row">
                <button class="btn-cart" onclick="addToCart()">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
                <button class="btn-buy" onclick="placeOrderNow()">Place Order Now</button>
            </div>

            <div class="return-container">
                <a onclick="backToMain()" class="return-link-btn" style="cursor: pointer;">
                    <i class="fa-solid fa-arrow-left"></i> BACK TO SERVICES
                </a>
            </div>
        </div>
        </div>
    </section>

    {{-- CART DRAWER --}}
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

    {{-- SERVICE CATEGORY MODAL --}}
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

    @php
    $resolveImageUrl = function (?string $path): ?string {
        if (empty($path)) {
            return null;
        }

        $path = trim($path);

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (\Illuminate\Support\Str::startsWith($normalizedPath, ['images/', 'img/'])) {
            return file_exists(public_path($normalizedPath))
                ? asset($normalizedPath)
                : null;
        }

        if (file_exists(public_path('images/' . $normalizedPath))) {
            return asset('images/' . $normalizedPath);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($normalizedPath)) {
            return asset('storage/' . $normalizedPath);git add resources/views/welcome.blade.php
        }

        return null;
    };

    $servicesData = $services->mapWithKeys(function ($service) use ($resolveImageUrl) {
        $serviceImage = $resolveImageUrl($service->image_path) ?? asset('images/Prdcts1.jpg');

        return [
            $service->id => [
                'id' => $service->id,
                'name' => $service->name,
                'category' => $service->category,
                'image' => $serviceImage,
                'variations' => $service->activeVariations->map(function ($variation) use ($service, $resolveImageUrl) {
                    return [
                        'id' => $variation->id,
                        'service_item_id' => $variation->service_item_id,
                        'printing_category' => $variation->printing_category,
                        'color_mode' => $variation->color_mode,
                        'product_size' => $variation->product_size,
                        'finish_type' => $variation->finish_type,
                        'package_type' => $variation->package_type,
                        'retail_price' => $variation->retail_price,
                        'bulk_price' => $variation->bulk_price,

                        'image' => $resolveImageUrl($variation->variation_image_path)
                            ?? $resolveImageUrl($service->image_path)
                            ?? asset('images/Prdcts1.jpg'),
                    ];
                })->values(),
            ],
        ];
    });
    @endphp

    <script>
    window.servicesData = @json($servicesData);
    </script>

    <script src="{{ asset('webproj.js') }}"></script>

</body>
</html>
