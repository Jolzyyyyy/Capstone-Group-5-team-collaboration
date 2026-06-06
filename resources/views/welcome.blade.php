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

<x-storefront.product-detail-section />

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
