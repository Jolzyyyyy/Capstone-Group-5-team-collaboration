@props([
    'serviceCards' => [],
])

<section id="services" class="section">
    <h2 class="services-section-title">What We Print</h2>
    <p class="services-section-intro">Print solutions designed for school, business, events, and everyday document needs.</p>
    <div class="services-container">
        @foreach ($serviceCards as $service)
            <div class="service-item" onclick="openModal('{{ $service['key'] }}')">
                <div class="service-image-wrapper">
                    <img
                        src="{{ $service['image'] }}"
                        alt="{{ $service['title'] }}"
                        onerror="this.onerror=null;this.src='{{ asset('images/Prdcts1.jpg') }}';"
                    >
                </div>
                <h3>{{ $service['title'] }}</h3>
            </div>
        @endforeach
    </div>
</section>
