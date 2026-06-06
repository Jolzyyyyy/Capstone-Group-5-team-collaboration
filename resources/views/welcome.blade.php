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

<x-storefront.header />

<div class="main-content" id="pageWrapper">
    <x-storefront.hero-section />
    <x-storefront.services-section :service-cards="$serviceCards" />
    <x-storefront.about-section />
    <x-storefront.contact-section />
</div>

<x-storefront.product-detail-section />

<x-storefront.cart-drawer />

<x-storefront.product-modal />

<script src="{{ asset('webproj.js') }}?v={{ filemtime(public_path('webproj.js')) }}"></script>

</body>
</html>
