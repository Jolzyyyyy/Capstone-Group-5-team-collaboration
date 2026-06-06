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
            <img src="{{ asset('images/printify-co-logo.png') }}" alt="Printify & Co." class="hero-brand-logo">
            <p class="hero-tagline">
                Crafting Your Vision into Reality
            </p>
            <p class="hero-subtitle">
                Premium Prints | Fast Delivery | Unlimited Possibilities
            </p>
        </div>
    </div>
</section>
