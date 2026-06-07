<section id="home" class="section active">
    <div class="hero-container">
        <div class="hero-slide active" style="background-image:url('{{ asset('images/Homesld1.jpg') }}')"></div>
        <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld2.jpg') }}')"></div>
        <div class="hero-slide" style="background-image:url('{{ asset('images/Homesld3.jpg') }}')"></div>

        <div class="hero-text animate" id="homeText">
            <div class="hero-copy">
                <p class="hero-kicker">High-quality printing solutions</p>
                <h1>
                    <span>Premium Prints.</span>
                    <span>Fast Delivery.</span>
                    <span class="accent">Unlimited</span>
                    <span>Possibilities.</span>
                </h1>
                <p class="hero-description">
                    High-quality printing solutions for every need. From documents to large formats,
                    we deliver precision, color, and impact.
                </p>
                <div class="hero-actions">
                    <button type="button" class="hero-primary-btn" onclick="jumpTo('services')">
                        Explore Services <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <a href="#contact" class="hero-secondary-btn" onclick="prefillQuoteRequest(); return false;">
                        Get a Quote <i class="fa-regular fa-file-lines"></i>
                    </a>
                </div>
            </div>

            <aside class="hero-benefits-card" aria-label="Printify service benefits">
                <div class="hero-benefit">
                    <span class="hero-benefit-icon"><i class="fa-solid fa-award"></i></span>
                    <div>
                        <strong>Premium Quality</strong>
                        <span>Vibrant colors, sharp detail, and lasting prints.</span>
                    </div>
                </div>
                <div class="hero-benefit">
                    <span class="hero-benefit-icon"><i class="fa-solid fa-stopwatch"></i></span>
                    <div>
                        <strong>Fast Turnaround</strong>
                        <span>Quick production and reliable on-time delivery.</span>
                    </div>
                </div>
                <div class="hero-benefit">
                    <span class="hero-benefit-icon"><i class="fa-solid fa-book-open"></i></span>
                    <div>
                        <strong>Wide Range</strong>
                        <span>From documents to large format and custom prints.</span>
                    </div>
                </div>
                <div class="hero-benefit">
                    <span class="hero-benefit-icon"><i class="fa-solid fa-coins"></i></span>
                    <div>
                        <strong>Affordable Pricing</strong>
                        <span>Competitive rates without compromising quality.</span>
                    </div>
                </div>
            </aside>
        </div>

        <div class="slide-indicators">
            <div class="dot active" onclick="jumpToHero(0)"></div>
            <div class="dot" onclick="jumpToHero(1)"></div>
            <div class="dot" onclick="jumpToHero(2)"></div>
        </div>
    </div>
</section>
