@props([
    'services' => collect(),
    'limit' => null,
    'showHeader' => true,
])

@php
    $items = $limit ? $services->take($limit) : $services;
@endphp

<section class="hz-section hz-services bg-surface border-top border-bottom border-hz" id="services">
    <div class="container">
        @if($showHeader)
            <div class="row justify-content-between align-items-end mb-4 g-3">
                <div class="col-lg-7">
                    <p class="hz-eyebrow">What we do</p>
                    <h2 class="hz-title mb-0">Our services</h2>
                </div>
                <div class="col-lg-auto">
                    <a href="{{ route('services.index') }}" class="btn-hz-outline">All services</a>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @forelse($items as $index => $service)
                @php
                    $jrFallbacks = [
                        'jr-ketema' => asset('assets/images/jr/store/home-lawn-turf.jpg'),
                        'jr-mobile' => asset('assets/images/jr/store/flagship-phone.jpg'),
                        'jr-real-estate' => asset('assets/images/jr/store/apartment-2br.jpg'),
                        'ruties-hair' => asset('assets/images/jr/store/wave-wig.jpg'),
                    ];
                    $image = $service->main_image_url ?: ($jrFallbacks[$service->slug] ?? null);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <article class="hz-service-card">
                        <a href="{{ route('services.show', $service->slug) }}" class="hz-service-card-media">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $service->title }}">
                            @endif
                            <span class="hz-service-card-index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </a>
                        <div class="hz-service-card-body">
                            <h3>
                                <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                            </h3>
                            <p>{{ $service->short_description }}</p>
                            <a href="{{ route('services.show', $service->slug) }}" class="hz-link">
                                Learn more <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">Services will appear here once published in the admin panel.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
