@props([
    'serviceCards' => [],
])

<section id="services" class="section">
    <div class="storefront-services-shell">
        <aside class="service-browser" aria-label="Browse services">
            <p class="service-browser-title">Browse Services</p>
            <button type="button" class="service-browser-link active">
                <i class="fa-solid fa-border-all"></i>
                <span>All Services</span>
            </button>
            @foreach ($serviceCards as $service)
                <button type="button" class="service-browser-link" onclick="openModal('{{ $service['key'] }}')">
                    <i class="fa-solid fa-print"></i>
                    <span>{{ Str::title(Str::lower($service['title'])) }}</span>
                </button>
            @endforeach
        </aside>

        <div class="featured-services-panel">
            <div class="services-section-heading">
                <div>
                    <p class="services-eyebrow">What We Do</p>
                    <h2>Our Featured Services</h2>
                    <p>Professional printing solutions tailored to your business and personal needs.</p>
                </div>

                <div class="services-toolbar" aria-label="Service display controls">
                    <button type="button" class="services-chip active">
                        <i class="fa-solid fa-border-all"></i>
                        All Services
                    </button>
                    <label class="services-sort">
                        <span>Sort by:</span>
                        <select aria-label="Sort services">
                            <option>Popular</option>
                            <option>Newest</option>
                            <option>A to Z</option>
                        </select>
                    </label>
                    <button type="button" class="services-view-btn active" aria-label="Grid view">
                        <i class="fa-solid fa-grip"></i>
                    </button>
                    <button type="button" class="services-view-btn" aria-label="List view">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>
            </div>

            <div class="featured-services-grid">
                @foreach ($serviceCards as $service)
                    <article class="featured-service-card" onclick="openModal('{{ $service['key'] }}')">
                        <div class="featured-service-media">
                            <img
                                src="{{ $service['image'] }}"
                                alt="{{ $service['title'] }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/Prdcts1.jpg') }}';"
                            >
                            <button type="button" class="featured-service-heart" aria-label="Save {{ $service['title'] }}">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                        <div class="featured-service-body">
                            <p>Printify &amp; Co.</p>
                            <h3>{{ Str::title(Str::lower($service['title'])) }}</h3>
                            <button type="button">
                                View Service <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
