<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
<meta charset="UTF-8">
<title>Printing Business Solution</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
/* GLOBAL */
html { scroll-behavior: smooth; }
body, html { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background: white; color: #333; overflow-x: hidden; }

/* NAVIGATION BAR */
.top-nav-bar {
    position: fixed; 
    top: 0; left: 0; right: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 60px;
    z-index: 2000;
    transition: all 0.3s ease;
    background: transparent;
}
.top-nav-bar.scrolled, .top-nav-bar.detail-active {
    background: rgba(0, 0, 0, 0.95);
    padding: 15px 60px;
}

.nav-horizontal {
    display: flex;
    gap: 130px; 
    position: absolute;
    left: 55%;
    transform: translateX(-50%);
}
.nav-horizontal a { 
    text-decoration: none; font-size: 13px; color: white; font-weight: 500;
    text-transform: uppercase; letter-spacing: 1.5px; transition: color 0.3s;
    cursor: pointer;
}
.nav-horizontal a:hover { color: #FF0000; }

.hero-signin-container { margin-left: auto; display: flex; align-items: center; gap: 15px; margin-right: 80px; }
.hero-signin-container a { text-decoration: none; color: white; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; text-transform: uppercase; }
.hero-signin-container a:hover { color: #FF0000; }
.auth-divider { color: rgba(255,255,255,0.3); font-size: 12px; }

#navCart { font-size: 18px; color: white; cursor: pointer; transition: 0.3s; margin-left: 10px; display: none; position: relative; }
#navCart:hover { color: #FF0000; }
.cart-badge { position: absolute; top: -8px; right: -10px; background: #FF0000; color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; font-weight: bold; }

/* HERO SECTION SLIDER */
.hero-container { position: relative; height: 100vh; width: 100%; overflow: hidden; background: #000; }
.hero-slide { 
    position: absolute; 
    inset: 0; 
    background-size: cover; 
    background-position: center; 
    opacity: 0; 
    transition: opacity 1.5s ease-in-out; 
    z-index: 1;
}
.hero-slide.active { opacity: 1; z-index: 2; }

/* Overlay gradient for better text readability */
.hero-container::after { 
    content: ''; 
    position: absolute; 
    inset: 0; 
    background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%); 
    z-index: 3; 
}

.slide-indicators {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 20;
    align-items: center;
}
.dot {
    width: 8px;
    height: 8px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.dot.active {
    width: 35px;
    background: #FF0000;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
}

/* PRINTIFY & CO. TEXT STYLES */
.hero-text { 
    position: absolute; 
    inset: 0; 
    z-index: 10; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: center; 
    color: white; 
    text-align: center; 
    opacity: 0; 
    transform: translateY(30px); 
    transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1); 
    pointer-events: none;
}
.hero-text.animate { opacity: 1; transform: translateY(0); }

.hero-text h1 {
    font-size: 90px;
    font-weight: 800;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: -3px;
    line-height: 0.85;
    text-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.hero-text p {
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

/* SECTIONS */
.section { 
    padding: 80px 20px; 
    opacity: 0; 
    transform: translateY(50px) scale(.95); 
    transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
}
.section.animate { opacity: 1; transform: translateY(0) scale(1); }
.section h2 { text-align: center; margin-bottom: 40px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; }

.services-container { 
    display: flex; 
    justify-content: center; 
    flex-wrap: wrap; 
    gap: 55px; 
    max-width: 1300px;
    margin: 0 auto;
}

.service-item { 
    flex: 1; 
    min-width: 250px;
    max-width: 280px;
    border: 1px solid #e3b3b3; 
    border-radius: 12px; 
    background: white; 
    text-align: center; 
    cursor: pointer; 
    padding: 20px 15px 10px 15px; 
    transition: border 0.3s ease;
}

.service-item:hover {
    border-color: #FF0000 !important;
}

/* IMAGE ZOOM ONLY */
.service-image-wrapper { height: 190px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.service-image-wrapper img { 
    width: 85%; 
    height: 95%; 
    object-fit: cover; 
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1); 
}
.service-item:hover .service-image-wrapper img { 
    transform: scale(1.15); 
}

/* TEXT: ISANG LINYA + RED LINE FIX */
.service-item h3 { 
    font-size: 13px !important; 
    margin-top: 12px !important; 
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    
    position: relative !important; 
    display: inline-block !important; 
    padding-bottom: 5px; 
    
    white-space: nowrap;
    overflow: visible !important; 
}

.service-item h3::after { 
    content: '' !important; 
    position: absolute !important; 
    left: 0; 
    bottom: 0; 
    width: 0; 
    height: 2px; 
    background-color: #FF0000 !important; 
    transition: width 0.3s ease !important; 
    display: block !important;
    z-index: 5;
}

.service-item:hover h3::after { 
    width: 100% !important; 
}

/* DETAIL INFO SECTION */
.detail-section { display: none; padding: 120px 40px; background: white; min-height: 100vh; width: 100%; box-sizing: border-box; }
.detail-container { 
    display: flex; 
    gap: 30px; 
    max-width: 1600px; 
    margin: 0 auto; 
    align-items: flex-start; 
}

/* SIDEBAR - FIXED: 3 items visible with SIDE scroll buttons (Canva-style) */
.detail-sidebar { 
    width: 250px; 
    flex-shrink: 0; 
    position: sticky; 
    top: 150px; 
    height: 570px; /* 3 items visible: (160 + 30) * 3 = 570 */
    display: flex;
    align-items: center;
    gap: 0;
}

.sidebar-scroll-btn {
    position: absolute;
    width: 28px;
    height: 28px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.sidebar-scroll-btn:hover {
    background: #FF0000;
    transform: scale(1.1);
}

.sidebar-scroll-btn:disabled {
    background: rgba(0, 0, 0, 0.2);
    cursor: not-allowed;
    opacity: 0.3;
}

.sidebar-scroll-btn.up-btn {
    left: 50%;
    transform: translateX(-50%);
    top: -14px;
}

.sidebar-scroll-btn.down-btn {
    left: 50%;
    transform: translateX(-50%);
    bottom: -14px;
}

.sidebar-scroll-btn.up-btn:hover {
    left: 50%;
    transform: translateX(-50%) scale(1.1);
}

.sidebar-scroll-btn.down-btn:hover {
    left: 50%;
    transform: translateX(-50%) scale(1.1);
}

.sidebar-viewport {
    overflow: hidden;
    width: 100%;
    height: 570px; /* Exact height for 3 items: (160 + 30) * 3 = 570, plus some padding */
    position: relative;
}

.sidebar-track {
    display: flex;
    flex-direction: column;
    gap: 30px;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-item { 
    border: 1px solid #ddd; 
    border-radius: 2px; 
    overflow: hidden; 
    cursor: pointer; 
    transition: 0.3s; 
    text-align: center; 
    background: #fff; 
    display: flex; 
    flex-direction: column; 
    flex-shrink: 0;
}
.sidebar-item:hover, .sidebar-item.active { border-color: #FF0000; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.sidebar-item img { width: 100%; height: 160px; object-fit: cover; }
.sidebar-item p { font-size: 12px; font-weight: 600; margin: 10px 0; text-transform: uppercase; color: #333; }
.sidebar-item.active p { color: #FF0000; }

/* MAIN IMAGE SLIDER CONTAINER */
.detail-images-slider { 
    width: 650px; 
    position: sticky; 
    top: 90px; 
    flex-shrink: 0; 
    margin-left: 80px;
}

.product-title-header {
    margin-bottom: 5px;
    text-align: center;
}
.product-title-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #222;
}

.preview-viewport { 
    width: 100%; 
    height: 600px; 
    border-radius: 4px; 
    overflow: hidden; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
    position: relative;
}

.preview-track { display: flex; transition: 0.5s ease; height: 100%; }
.preview-track img { min-width: 100%; height: 100%; object-fit: cover; }

/* INFO PANEL */
.detail-info-panel { 
    flex: 0 0 400px; 
    margin-left: 30px; 
    position: sticky;
    top: 150px; 
    padding-top: 55px; 
}

.custom-option-group {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.custom-option-group label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #888;
    margin: 0;
    flex-shrink: 0;
}

.custom-select {
    width: 200px;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-family: inherit;
    font-size: 13px;
    text-align: center;
    cursor: pointer;
    outline: none;
}

.stock-indicator { display: inline-flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 500; color: #27ae60; margin-bottom: 10px; }
.stock-dot { width: 7px; height: 7px; background: #27ae60; border-radius: 50%; animation: pulse 1.5s infinite; }
@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

/* PRICE SUMMARY ADJUSTMENT */
.price-summary { background: #f9f9f9; padding: 15px; border-radius: 4px; border-left: 4px solid #FF0000; margin-top: 20px; }
.price-label { margin: 0; font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
.unit-price { color: #555; font-size: 16px; font-weight: 500; margin-bottom: 5px; }
.total-price { margin: 0; color: #333; font-size: 28px; font-weight: 600; }

.qty-box { display: flex; border: 1px solid #ddd; width: fit-content; border-radius: 4px; margin: 15px 0; overflow: hidden; }
.qty-btn { 
    width: 40px; height: 40px; border: none; background: #f0f0f0; 
    cursor: pointer; font-size: 16px; transition: 0.3s; color: #333;
}
.qty-btn:hover { background: #333; color: white; }
.qty-btn:active { background: #000; transform: scale(0.95); }

.qty-input { width: 50px; text-align: center; border: none; font-weight: bold; font-size: 14px; outline: none; -moz-appearance: textfield; }
.qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.btn-row { display: flex; gap: 12px; margin-top: 25px; }
.btn-row button { flex: 1; padding: 15px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 13px; transition: 0.3s; }
.btn-cart { background: #FF0000; color: white; }
.btn-buy { background: #222; color: white; }

.preview-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; z-index: 20; font-size: 20px; transition: 0.3s; }
.prev-p { left: 10px; } .next-p { right: 10px; }

/* MODAL - FIXED: 1 card per slide */
.product-modal { position: fixed; inset: 0; background: rgba(0,0,0,.9); display: none; align-items: center; justify-content: center; z-index: 3000; }
.product-modal.active { display: flex; }
.product-modal-content { background: white; width: 450px; border-radius: 20px; padding: 40px; position: relative; overflow: hidden; }
.close-modal { position: absolute; top: 15px; right: 25px; font-size: 35px; cursor: pointer; z-index: 10; }
.category-slider { position: relative; margin-top: 20px; width: 100%; }
.slider-viewport { overflow: hidden; width: 100%; }
.category-track { display: flex; transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
.category-card { 
    flex: 0 0 100%; 
    text-align: center; 
    cursor: pointer; 
    padding: 0 10px;
    box-sizing: border-box;
}
.category-card img { 
    width: 100%; 
    height: 280px; 
    object-fit: cover; 
    border-radius: 12px; 
    margin-bottom: 15px; 
    transition: transform 0.3s ease;
}
.category-card:hover img {
    transform: scale(1.05);
}
.category-card h4 {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    color: #333;
    margin: 0 0 8px 0;
    letter-spacing: 1px;
}
.category-card p {
    font-size: 10px;
    color: #FF0000;
    font-weight: 500;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.slider-btn { 
    position: absolute; 
    top: 50%; 
    transform: translateY(-50%); 
    background: #333; 
    color: white; 
    border: none; 
    width: 40px; 
    height: 40px; 
    border-radius: 50%; 
    cursor: pointer; 
    z-index: 10; 
    display: flex; 
    align-items: center; 
    justify-content: center;
    transition: 0.3s;
    font-size: 18px;
}
.slider-btn:hover {
    background: #FF0000;
}
.slider-btn.prev { left: -15px; }
.slider-btn.next { right: -15px; }

/* SIDE CART DRAWER - PINALAPAD (550px) */
.cart-drawer-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 4000; display: none; opacity: 0; transition: 0.3s; }
.cart-drawer-overlay.active { display: block; opacity: 1; }

.cart-drawer { 
    position: fixed; top: 0; right: -550px; width: 550px; height: 100vh; 
    background: white; z-index: 4001; transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
    box-shadow: -10px 0 30px rgba(0,0,0,0.1); display: flex; flex-direction: column;
}
.cart-drawer.active { right: 0; }

.cart-header { padding: 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
.cart-header h2 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
.close-cart { cursor: pointer; font-size: 24px; color: #666; }

.cart-items-list { flex: 1; overflow-y: auto; padding: 30px; }

.cart-item { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid #f5f5f5; padding-bottom: 20px; }
.cart-item input[type="checkbox"] { accent-color: #FF0000; width: 20px; height: 20px; cursor: pointer; flex-shrink: 0; margin-top: 5px; }
.cart-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.cart-item-info { flex: 1; }
.cart-item-info h4 { margin: 0; font-size: 15px; text-transform: uppercase; font-weight: 600; color: #222; }
.cart-item-info p { margin: 5px 0; font-size: 12px; color: #777; line-height: 1.4; }
.cart-item-price { font-weight: 600; font-size: 16px; color: #FF0000; margin-top: 10px; }
.remove-item { font-size: 11px; color: #bbb; cursor: pointer; text-decoration: underline; margin-top: 10px; display: inline-block; }
.remove-item:hover { color: #FF0000; }

.cart-footer { padding: 30px; border-top: 1px solid #eee; background: #fcfcfc; }

.voucher-container { margin-bottom: 20px; border-bottom: 1px solid #f0f0f0; padding-bottom: 20px; }
.voucher-label { font-size: 12px; font-weight: 600; display: block; margin-bottom: 10px; color: #555; }
.voucher-flex { display: flex; gap: 10px; }
.voucher-input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; outline: none; }
.voucher-btn { padding: 0 20px; background: #333; color: white; border: none; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; }
.voucher-btn:hover { background: #000; }

.cart-total-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-weight: 600; font-size: 22px; color: #222; }
.discount-row { display: flex; justify-content: space-between; font-size: 14px; color: #27ae60; margin-bottom: 20px; font-weight: 500; }
.cart-btn-checkout { width: 100%; padding: 20px; background: #000; color: white; border: none; border-radius: 6px; font-weight: 600; text-transform: uppercase; cursor: pointer; transition: 0.3s; font-size: 14px; letter-spacing: 1px; }
.cart-btn-checkout:hover { background: #FF0000; box-shadow: 0 5px 15px rgba(255,0,0,0.3); }
</style>
<script src="https://sites.super.myninja.ai/_assets/ninja-daytona-script.js"></script>
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printing Business Solution | Printify & Co.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('webproj.css') }}">
>>>>>>> 053e9d6 (update ng codes para sa capstone)
</head>
<body>

<header class="top-nav-bar" id="mainHeader">
<<<<<<< HEAD
    <div></div> 
=======
    <div></div>
>>>>>>> 053e9d6 (update ng codes para sa capstone)
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
<<<<<<< HEAD
        <div class="hero-slide active" style="background-image:url('images/Homesld1.jpg')"></div>
        <div class="hero-slide" style="background-image:url('images/Homesld2.jpg')"></div>
        <div class="hero-slide" style="background-image:url('images/Homesld3.jpg')"></div>
            
=======
            <div class="hero-slide active" style="background-image:url('{{ asset('images/Homesld1.jpg') }}')"></div>
            <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld2.jpg') }}')"></div>
            <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld3.jpg') }}')"></div>
           
>>>>>>> 053e9d6 (update ng codes para sa capstone)
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
<<<<<<< HEAD
    <h2>Our Services</h2>
    <div class="services-container">
        <div class="service-item" onclick="openModal('doc')">
            <div class="service-image-wrapper">
                <img src="images/Prdcts1.jpg" alt="Document Printing">
            </div>
            <h3>DOCUMENT PRINTING</h3>
        </div>

        <div class="service-item" onclick="openModal('photo')">
            <div class="service-image-wrapper">
                <img src="images/Prdcts1.jpg" alt="Photocopy & Scanning">
            </div>
            <h3>PHOTOCOPY & SCANNING</h3>
        </div>

        <div class="service-item" onclick="openModal('id')">
            <div class="service-image-wrapper">
                <img src="images/Prdcts1.jpg" alt="ID & Photo Services">
            </div>
            <h3>ID & PHOTO SERVICES</h3>
        </div>

        <div class="service-item" onclick="openModal('bind')">
            <div class="service-image-wrapper">
                <img src="images/Prdcts1.jpg" alt="Lamination & Binding">
            </div>
            <h3>LAMINATION & BINDING</h3>
        </div>
    </div>
</section>
=======
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
>>>>>>> 053e9d6 (update ng codes para sa capstone)

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
<<<<<<< HEAD
        <div class="detail-sidebar" id="detailSidebar"></div>
        <div class="detail-images-slider">
            <div class="product-title-header"><h2 id="detailTitleHeader"></h2></div>
            <div class="preview-viewport">
                <button class="preview-btn prev-p" onclick="movePreview(-1)">‹</button>
                <div class="preview-track" id="previewTrack"></div>
                <button class="preview-btn next-p" onclick="movePreview(1)">›</button>
            </div>
        </div>
        <div class="detail-info-panel">
            <div class="stock-indicator"><div class="stock-dot"></div> <span id="availabilityText">In Stock</span></div>
            <p id="productSpecs" style="color:#666; font-size: 14px; line-height: 1.6; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 20px;"></p>
            <div class="custom-option-group">
                <label>Quality Type</label>
                <select class="custom-select" id="materialSelect" onchange="updatePrice()">
                    <option value="5">Standard Paper (₱5)</option>
                    <option value="15">Premium Glossy (₱15)</option>
                    <option value="20">Matte Finish (₱20)</option>
                </select>
            </div>
            <div class="custom-option-group">
                <label>Dimensions</label>
                <select class="custom-select" id="sizeSelect" onchange="updatePrice()">
                    <option value="1">Small / Standard (x1.0)</option>
                    <option value="2">Medium / A3 (x2.0)</option>
                    <option value="5">Large / XL (x5.0)</option>
                </select>
            </div>
            <div class="qty-box">
                <button class="qty-btn" onclick="changeQty(-1)">-</button>
                <input type="number" id="qtyInput" value="1" min="1" class="qty-input" oninput="updatePrice()">
                <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
            <div class="price-summary">
                <div class="unit-price-box">
                    <p class="price-label">Unit Price</p>
                    <div class="unit-price">₱<span id="unitAmount">0.00</span></div>
                </div>
                <div class="total-price-box" style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px;">
                    <p class="price-label">Total Price</p>
                    <h3 class="total-price">₱<span id="totalAmount">0.00</span></h3>
=======
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
>>>>>>> 053e9d6 (update ng codes para sa capstone)
                </div>
            </div>
            <div class="btn-row">
                <button class="btn-cart" onclick="addToCart()"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
<<<<<<< HEAD
                <button class="btn-buy">Place Order</button>
            </div>
            <a onclick="backToMain()" style="display:block; margin-top:35px; color:#FF0000; cursor:pointer; font-weight:600; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">← Return to Main</a>
=======
                <button class="btn-buy">Place Order Now</button>
            </div>
            <div class="return-container">
                <a onclick="backToMain()" class="return-link-btn">
                   <i class="fa-solid fa-arrow-left"></i> BACK TO SERVICES
                </a>
            </div>
>>>>>>> 053e9d6 (update ng codes para sa capstone)
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
<<<<<<< HEAD
            <label class="voucher-label"><i class="fa-solid fa-ticket"></i> SHOP VOUCHER</label>
            <div class="voucher-flex">
                <input type="text" class="voucher-input" id="voucherCodeInput" placeholder="Enter EYRAPRETTY50">
                <button class="voucher-btn" onclick="applyVoucher()">APPLY</button>
            </div>
        </div>
        <div class="discount-row" id="discountRow" style="display:none;">
            <span>Discount Applied</span>
            <span>-₱<span id="appliedDiscount">0.00</span></span>
=======
            <div class="voucher-input-group">
                <input type="text" id="voucherCode" placeholder="Enter Voucher Code">
                <button class="apply-voucher-btn" onclick="applyVoucher()">Apply</button>
            </div>
            <p id="voucherMsg" class="voucher-message"></p>
>>>>>>> 053e9d6 (update ng codes para sa capstone)
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
<<<<<<< HEAD
        <h2 id="modalTitle" style="text-align:center; font-size: 14px; color: #999; letter-spacing: 2px;">CHOOSE TYPE</h2>
        <div class="category-slider">
            <button class="slider-btn prev" onclick="moveSlide(-1)">‹</button>
            <div class="slider-viewport">
                <div class="category-track" id="categoryTrack"></div>
            </div>
            <button class="slider-btn next" onclick="moveSlide(1)">›</button>
=======
        <h2 id="modalTitle">CHOOSE TYPE</h2>
        <div class="category-slider">
            <button class="modal-btn" id="modalPrev" onclick="moveSlide(-1)">❮</button>
            <div class="slider-viewport">
                <div class="category-track" id="categoryTrack"></div>
            </div>
            <button class="modal-btn" id="modalNext" onclick="moveSlide(1)">❯</button>
>>>>>>> 053e9d6 (update ng codes para sa capstone)
        </div>
    </div>
</div>

<<<<<<< HEAD
<script>
let heroIndex = 0;
const heroSlides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.dot');
let slideInterval = setInterval(nextHeroSlide, 8000); 
let sidebarScrollIndex = 0;

function updateHero() {
    heroSlides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    heroSlides[heroIndex].classList.add('active');
    dots[heroIndex].classList.add('active');
}

function nextHeroSlide() {
    heroIndex = (heroIndex + 1) % heroSlides.length;
    updateHero();
}

function jumpToHero(index) {
    clearInterval(slideInterval);
    heroIndex = index;
    updateHero();
    slideInterval = setInterval(nextHeroSlide, 8000); 
}

const allData = {
    'doc': { name: "DOCUMENT PRINTING", categories: [
        {name: "Black & White", imgs: ["https://images.unsplash.com/photo-1562654501-a0ccc0af3fb1?w=400"], specs: "Sharp laser-quality text for documents."},
        {name: "Full Color", imgs: ["https://images.unsplash.com/photo-1586075010633-2470acfd858b?w=400"], specs: "Vivid high-res colors for flyers and reports."},
        {name: "Draft Print", imgs: ["https://images.unsplash.com/photo-1517672651691-24622a91b550?w=400"], specs: "Economical printing for school work."},
        {name: "Double Sided", imgs: ["https://images.unsplash.com/photo-1544816155-12df9643f363?w=400"], specs: "Print on both sides for efficiency."},
        {name: "Large Format", imgs: ["https://images.unsplash.com/photo-1568667256549-094345857637?w=400"], specs: "Posters and banners printing."}
    ]},
    'photo': { name: "PHOTOCOPY & SCANNING", categories: [
        {name: "Xerox Copy", imgs: ["https://images.unsplash.com/photo-1586075010633-2470acfd858b?w=400"], specs: "Clear and crisp duplication."},
        {name: "Digital Scanning", imgs: ["https://images.unsplash.com/photo-1562654501-a0ccc0af3fb1?w=400"], specs: "High quality PDF/JPG scan output."},
        {name: "Color Copy", imgs: ["https://images.unsplash.com/photo-1517672651691-24622a91b550?w=400"], specs: "Vibrant color reproduction."},
        {name: "Document Scan", imgs: ["https://images.unsplash.com/photo-1544816155-12df9643f363?w=400"], specs: "Professional document digitization."}
    ]},
    'id': { name: "ID & PHOTO SERVICES", categories: [
        {name: "Passport Size", imgs: ["https://images.unsplash.com/photo-1517672651691-24622a91b550?w=400"], specs: "Standard government requirements."},
        {name: "1x1 ID Photo", imgs: ["https://images.unsplash.com/photo-1562654501-a0ccc0af3fb1?w=400"], specs: "Professional studio quality."},
        {name: "2x2 Photo", imgs: ["https://images.unsplash.com/photo-1586075010633-2470acfd858b?w=400"], specs: "Various ID applications."}
    ]},
    'bind': { name: "LAMINATION & BINDING", categories: [
        {name: "Hot Lamination", imgs: ["https://images.unsplash.com/photo-1544816155-12df9643f363?w=400"], specs: "Waterproof and durable protection."},
        {name: "Cold Lamination", imgs: ["https://images.unsplash.com/photo-1562654501-a0ccc0af3fb1?w=400"], specs: "Heat-sensitive document safe."},
        {name: "Spiral Binding", imgs: ["https://images.unsplash.com/photo-1517672651691-24622a91b550?w=400"], specs: "Professional book binding."},
        {name: "Hard Cover", imgs: ["https://images.unsplash.com/photo-1586075010633-2470acfd858b?w=400"], specs: "Premium hard cover binding."}
    ]}
};

let currentCategorySet = [];
let currentPreviewIndex = 0;
let currentSlideIndex = 0;
let cart = JSON.parse(localStorage.getItem('printCart')) || [];
let voucherDiscount = 0;

function jumpTo(id) {
    if(document.getElementById('productDetail').style.display === 'block') {
        backToMain();
        setTimeout(() => {
            const target = document.getElementById(id);
            if(target) window.scrollTo({ top: target.offsetTop - 40, behavior: 'smooth' });
        }, 100);
    } else {
        const target = document.getElementById(id);
        if(target) window.scrollTo({ top: target.offsetTop - 40, behavior: 'smooth' });
    }
}

function openModal(key) {
    const data = allData[key] || { name: "PRINTING SERVICE", categories: [] };
    document.getElementById('modalTitle').innerText = data.name;
    const track = document.getElementById('categoryTrack');
    track.innerHTML = '';
    currentCategorySet = data.categories;
    currentCategorySet.forEach((cat, index) => {
        track.innerHTML += `<div class="category-card" onclick="openDetail(${index})"><img src="${cat.imgs[0]}"><h4>${cat.name}</h4><p>VIEW DETAILS</p></div>`;
    });
    currentSlideIndex = 0;
    track.style.transform = `translateX(0)`;
    document.getElementById('productModal').classList.add('active');
}

function openDetail(index) {
    const cat = currentCategorySet[index];
    document.getElementById('detailTitleHeader').innerText = cat.name;
    document.getElementById('productSpecs').innerText = cat.specs;
    const previewTrack = document.getElementById('previewTrack');
    previewTrack.innerHTML = '';
    cat.imgs.forEach(imgSrc => { previewTrack.innerHTML += `<img src="${imgSrc}">`; });
    currentPreviewIndex = 0;
    previewTrack.style.transform = `translateX(0)`;
    
    // Update sidebar with SIDE scroll buttons (Canva-style)
    const sidebar = document.getElementById('detailSidebar');
    sidebar.innerHTML = `
        <button class="sidebar-scroll-btn up-btn" id="sidebarUpBtn" onclick="scrollSidebar(-1)">▲</button>
        <div class="sidebar-viewport">
            <div class="sidebar-track" id="sidebarTrack"></div>
        </div>
        <button class="sidebar-scroll-btn down-btn" id="sidebarDownBtn" onclick="scrollSidebar(1)">▼</button>
    `;
    
    const sidebarTrack = document.getElementById('sidebarTrack');
    currentCategorySet.forEach((sidebarCat, idx) => {
        sidebarTrack.innerHTML += `<div class="sidebar-item ${idx === index ? 'active' : ''}" onclick="openDetail(${idx})"><p>${sidebarCat.name}</p><img src="${sidebarCat.imgs[0]}"></div>`;
    });
    
    // Set sidebar scroll position
    sidebarScrollIndex = index;
    updateSidebarScroll();

    document.getElementById('productModal').classList.remove('active');
    document.getElementById('pageWrapper').style.display = 'none'; 
    document.getElementById('productDetail').style.display = 'block'; 
    document.getElementById('mainHeader').classList.add('detail-active');
    document.getElementById('navCart').style.display = 'flex';
    document.querySelectorAll('.auth-btn').forEach(btn => btn.style.display = 'none');
    document.querySelector('.auth-divider').style.display = 'none';
    updatePrice();
    window.scrollTo(0, 0); 
}

function scrollSidebar(dir) {
    const itemsPerView = 3;
    const totalItems = currentCategorySet.length;
    const maxIndex = totalItems - itemsPerView;

    sidebarScrollIndex += dir;
    sidebarScrollIndex = Math.max(0, Math.min(sidebarScrollIndex, maxIndex));

    const itemHeight = 160 + 30; // image + gap
    document.getElementById('sidebarTrack').style.transform = `translateY(-${sidebarScrollIndex * itemHeight}px)`;

    // Enable/disable buttons
    document.getElementById('sidebarUpBtn').disabled = sidebarScrollIndex === 0;
    document.getElementById('sidebarDownBtn').disabled = sidebarScrollIndex === maxIndex;
}

function updateSidebarScroll() {
    const track = document.getElementById('sidebarTrack');
    if (!track) return;
    
    const total = currentCategorySet.length;
    const itemsPerView = 3;
    const maxScroll = total - itemsPerView;
    const itemHeight = 160 + 30; // image height + text padding + gap
    
    // Calculate scroll position - move by one item at a time
    const scrollPos = sidebarScrollIndex * itemHeight;
    track.style.transform = `translateY(-${scrollPos}px)`;
    
    // Update button states
    const upBtn = document.getElementById('sidebarUpBtn');
    const downBtn = document.getElementById('sidebarDownBtn');
    
    if (upBtn) upBtn.disabled = sidebarScrollIndex <= 0;
    if (downBtn) downBtn.disabled = sidebarScrollIndex >= maxScroll;
}

function backToMain() {
    document.getElementById('productDetail').style.display = 'none';
    document.getElementById('pageWrapper').style.display = 'block';
    document.getElementById('mainHeader').classList.remove('detail-active');
    document.querySelectorAll('.auth-btn').forEach(btn => btn.style.display = 'flex');
    document.querySelector('.auth-divider').style.display = 'inline';
    document.querySelectorAll('.section').forEach(s => s.classList.remove('animate'));
    setTimeout(() => {
        handleScrollIcons();
        observer.observe(document.getElementById('products'));
        observer.observe(document.getElementById('about'));
        observer.observe(document.getElementById('contact'));
    }, 50);
}

function handleScrollIcons() {
    const servicesSection = document.getElementById('products');
    const isDetailVisible = document.getElementById('productDetail').style.display === 'block';
    if (isDetailVisible) {
        document.getElementById('navCart').style.display = 'flex';
    } else {
        if (servicesSection && window.scrollY >= servicesSection.offsetTop - 300) {
            document.getElementById('navCart').style.display = 'flex';
        } else {
            document.getElementById('navCart').style.display = 'none';
        }
    }
}

window.addEventListener('scroll', () => {
    const isDetailVisible = document.getElementById('productDetail').style.display === 'block';
    if(!isDetailVisible) {
        document.getElementById('mainHeader').classList.toggle('scrolled', window.scrollY > 50);
    }
    handleScrollIcons();
});

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => { 
        if(entry.isIntersecting) entry.target.classList.add('animate');
    });
}, { threshold: 0.1 });

document.querySelectorAll('.section').forEach(s => observer.observe(s));

function movePreview(dir) {
    const track = document.getElementById('previewTrack');
    const totalImgs = track.querySelectorAll('img').length;
    if(totalImgs <= 1) return;
    currentPreviewIndex = (currentPreviewIndex + dir + totalImgs) % totalImgs;
    track.style.transform = `translateX(-${currentPreviewIndex * 100}%)`;
}

function updatePrice() {
    const basePrice = parseFloat(document.getElementById('materialSelect').value);
    const sizeMultiplier = parseFloat(document.getElementById('sizeSelect').value);
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const unitPrice = basePrice * sizeMultiplier;
    const total = unitPrice * qty;
    document.getElementById('unitAmount').innerText = unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('totalAmount').innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
}

function changeQty(d) {
    let q = document.getElementById('qtyInput');
    let v = parseInt(q.value) + d;
    if(v < 1) v = 1; 
    q.value = v;
    updatePrice();
}

// FIXED: Updated slide function to show 1 card per view
function moveSlide(dir) {
    const track = document.getElementById('categoryTrack');
    const total = currentCategorySet.length;
    
    if(total <= 1) return;
    
    // Update current slide index with cycling
    currentSlideIndex += dir;
    
    // Cycle through slides
    if (currentSlideIndex < 0) {
        currentSlideIndex = total - 1;
    } else if (currentSlideIndex >= total) {
        currentSlideIndex = 0;
    }
    
    // Move the track by 100% per slide (1 card at a time)
    track.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
}

function closeModal() { document.getElementById('productModal').classList.remove('active'); }

function toggleCart() {
    document.getElementById('cartOverlay').classList.toggle('active');
    document.getElementById('cartDrawer').classList.toggle('active');
    renderCart();
}

function addToCart() {
    const item = {
        id: Date.now(),
        name: document.getElementById('detailTitleHeader').innerText,
        quality: document.getElementById('materialSelect').options[document.getElementById('materialSelect').selectedIndex].text,
        size: document.getElementById('sizeSelect').options[document.getElementById('sizeSelect').selectedIndex].text,
        qty: parseInt(document.getElementById('qtyInput').value),
        price: parseFloat(document.getElementById('totalAmount').innerText.replace(/,/g, '')),
        img: document.querySelector('#previewTrack img').src,
        checked: true
    };
    cart.push(item);
    localStorage.setItem('printCart', JSON.stringify(cart));
    updateCartBadge();
    toggleCart(); 
}

function updateCartBadge() {
    document.getElementById('cartBadge').innerText = cart.length;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('printCart', JSON.stringify(cart));
    updateCartBadge();
    renderCart();
}

function renderCart() {
    const list = document.getElementById('cartItemsList');
    list.innerHTML = '';
    cart.forEach((item, index) => {
        list.innerHTML += `
            <div class="cart-item">
                <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggleItemCheck(${index})">
                <img src="${item.img}">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <p>${item.quality} | ${item.size}</p>
                    <p>Qty: ${item.qty}</p>
                    <div class="cart-item-price">₱${item.price.toLocaleString(undefined, {minimumFractionDigits: 2})}</div>
                    <span class="remove-item" onclick="removeFromCart(${index})">Remove from list</span>
                </div>
            </div>`;
    });
    if(cart.length === 0) list.innerHTML = '<p style="text-align:center; color:#999; margin-top:50px;">Your cart is empty.</p>';
    calculateCartTotal();
}

function toggleItemCheck(index) {
    cart[index].checked = !cart[index].checked;
    localStorage.setItem('printCart', JSON.stringify(cart));
    calculateCartTotal();
}

function applyVoucher() {
    const code = document.getElementById('voucherCodeInput').value.trim();
    if(code === "EYRAPRETTY50") {
        voucherDiscount = 50;
        alert("Voucher Applied! ₱50 Discount.");
    } else {
        voucherDiscount = 0;
        alert("Invalid Voucher Code.");
    }
    calculateCartTotal();
}

function calculateCartTotal() {
    let subtotal = cart.reduce((acc, item) => item.checked ? acc + item.price : acc, 0);
    let finalTotal = subtotal - voucherDiscount;
    if(finalTotal < 0) finalTotal = 0;

    document.getElementById('drawerTotal').innerText = finalTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
    
    const discRow = document.getElementById('discountRow');
    if(voucherDiscount > 0) {
        discRow.style.display = 'flex';
        document.getElementById('appliedDiscount').innerText = voucherDiscount.toFixed(2);
    } else {
        discRow.style.display = 'none';
    }
}

function checkoutSelected() {
    const selectedItems = cart.filter(i => i.checked);
    if(selectedItems.length === 0) return alert("Please select items to checkout.");
    alert("Proceeding to checkout with " + selectedItems.length + " items!");
}

// Initialize Badge
updateCartBadge();
</script>

=======
<script src="{{ asset('webproj.js') }}"></script>
>>>>>>> 053e9d6 (update ng codes para sa capstone)
</body>
</html>