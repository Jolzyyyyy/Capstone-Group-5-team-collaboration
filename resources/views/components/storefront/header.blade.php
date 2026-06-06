<header class="top-nav-bar" id="mainHeader">
    <div class="nav-spacer">
        <a href="#home" class="site-logo-link" aria-label="Printify & Co. home" onclick="jumpTo('home'); return false;">
            <img src="{{ asset('images/printify-co-logo.png') }}" alt="Printify & Co." class="site-logo">
        </a>
    </div>

    <nav class="nav-horizontal">
        <a href="#home" data-section="home" class="nav-link is-active" onclick="jumpTo('home'); return false;">Home</a>
        <a href="#about" data-section="about" class="nav-link" onclick="jumpTo('about'); return false;">About Us</a>
        <a href="#services" data-section="services" class="nav-link" onclick="jumpTo('services'); return false;">Services</a>
        <a href="#contact" data-section="contact" class="nav-link" onclick="jumpTo('contact'); return false;">Contact</a>
    </nav>

    <div class="hero-signin-container" id="authContainer">
        @if (Route::has('login'))
            @auth
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
