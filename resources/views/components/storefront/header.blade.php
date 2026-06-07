<header class="top-nav-bar" id="mainHeader">
    <div class="nav-spacer storefront-brand">
        <a href="#home" class="site-wordmark-link" aria-label="Printify & Co. home" onclick="jumpTo('home'); return false;">
            <span class="site-wordmark">PRINTIFY &amp; CO.</span>
            <span class="site-wordmark-tagline">CRAFTING YOUR VISION INTO REALITY</span>
        </a>
    </div>

    <nav class="nav-horizontal">
        <a href="#home" data-section="home" class="nav-link is-active" onclick="jumpTo('home'); return false;">Home</a>
        <a href="#about" data-section="about" class="nav-link" onclick="jumpTo('about'); return false;">About Us</a>
        <a href="#services" data-section="services" class="nav-link" onclick="jumpTo('services'); return false;">Services</a>
        <a href="#contact" data-section="contact" class="nav-link" onclick="jumpTo('contact'); return false;">Contact Us</a>
    </nav>

    <div class="hero-signin-container storefront-actions" id="authContainer">
        <form class="storefront-search" id="storefrontSearchForm" role="search">
            <input id="storefrontSearchInput" type="search" placeholder="Search products or services..." aria-label="Search products or services">
            <button type="submit" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <button type="button" class="storefront-icon-btn" id="storefrontFavoritesToggle" aria-label="Show saved services" aria-pressed="false">
            <i class="fa-regular fa-heart"></i>
            <span class="favorites-badge" id="favoritesBadge" hidden>0</span>
        </button>

        <a onclick="toggleCart()" id="navCart" class="storefront-icon-btn storefront-cart-btn" style="cursor: pointer;" aria-label="Open cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </a>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="storefront-user-link auth-btn">
                    <i class="fa-solid fa-user-check"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <a href="{{ route('logout') }}" class="storefront-logout-link auth-btn"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                       <i class="fa-solid fa-right-from-bracket"></i> Log Out
                    </a>
                </form>
            @else
                <a href="{{ route('register') }}" class="storefront-user-link auth-btn"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
                <a href="{{ route('login') }}" class="storefront-user-link auth-btn"><i class="fa-regular fa-user"></i> Log In</a>
            @endauth
        @endif
    </div>
</header>
