@extends('layouts.app')

@section('title', $service->title)
@section('description', $service->short_description)

@php
    $jrImages = [
        'jr-ketema' => asset('assets/images/jr/store/home-lawn-turf.jpg'),
        'jr-mobile' => asset('assets/images/jr/store/flagship-phone.jpg'),
        'jr-real-estate' => asset('assets/images/jr/store/apartment-2br.jpg'),
        'ruties-hair' => asset('assets/images/jr/store/wave-wig.jpg'),
    ];

    $jrSecondaryImages = [
        'jr-ketema' => asset('assets/images/jr/store/sports-pitch-turf.jpg'),
        'jr-mobile' => asset('assets/images/jr/store/android-phone.jpg'),
        'jr-real-estate' => asset('assets/images/jr/store/investment-plot.jpg'),
        'ruties-hair' => asset('assets/images/jr/store/hair-care-kit.jpg'),
    ];

    $brandAccents = [
        'jr-ketema' => '#15803d',
        'jr-mobile' => '#1d4ed8',
        'jr-real-estate' => '#b45309',
        'ruties-hair' => '#be185d',
    ];

    $heroImage = $service->secondary_image_url
        ?: $service->main_image_url
        ?: ($jrImages[$service->slug] ?? null);

    $accentImage = $service->main_image_url
        ?: ($jrSecondaryImages[$service->slug] ?? $heroImage);

    $accent = $brandAccents[$service->slug] ?? 'var(--hz-accent)';
    $otherServices = $allServices->where('id', '!=', $service->id);
@endphp

@section('content')
<div class="hz-service-detail-page" style="--brand-accent: {{ $accent }}">
    <section class="hz-service-detail-hero">
        @if($heroImage)
            <div class="hz-service-detail-hero-bg" style="background-image: url('{{ $heroImage }}')"></div>
        @endif
        <div class="hz-service-detail-hero-overlay"></div>
        <div class="container hz-service-detail-hero-content">
            <nav class="hz-service-detail-crumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('services.index') }}">Services</a>
                <span>/</span>
                <span aria-current="page">{{ $service->title }}</span>
            </nav>
            <p class="hz-service-detail-eyebrow">JR Couple brand</p>
            <h1 class="hz-service-detail-title">{{ $service->title }}</h1>
            @if($service->quote)
                <p class="hz-service-detail-quote">{{ $service->quote }}</p>
            @endif
            @if($service->short_description)
                <p class="hz-service-detail-lead">{{ $service->short_description }}</p>
            @endif
            <div class="hz-service-detail-hero-actions">
                <a href="{{ route('store.index', ['category' => $service->slug]) }}" class="btn-hz">
                    Shop {{ $service->title }} <i class="bi bi-bag"></i>
                </a>
                <a href="{{ route('contact') }}" class="btn-hz-outline hz-service-detail-hero-outline">
                    Get in touch
                </a>
            </div>
        </div>
    </section>

    <nav class="hz-service-detail-tabs d-lg-none" aria-label="Other services">
        @foreach($allServices as $item)
            <a
                href="{{ route('services.show', $item->slug) }}"
                class="hz-service-detail-tab {{ $item->id === $service->id ? 'is-active' : '' }}"
                @if($item->id === $service->id) aria-current="page" @endif
            >
                {{ $item->title }}
            </a>
        @endforeach
    </nav>

    <section class="hz-service-detail-body">
        <div class="container">
            <div class="hz-service-detail-layout">
                <aside class="hz-service-detail-sidebar d-none d-lg-block">
                    <p class="hz-service-detail-sidebar-label">All brands</p>
                    <div class="hz-service-detail-sidebar-nav">
                        @foreach($allServices as $item)
                            <a
                                href="{{ route('services.show', $item->slug) }}"
                                class="{{ $item->id === $service->id ? 'is-active' : '' }}"
                                @if($item->id === $service->id) aria-current="page" @endif
                            >
                                <span>{{ $item->title }}</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('services.index') }}" class="hz-service-detail-sidebar-back">
                        <i class="bi bi-grid"></i> View all services
                    </a>
                </aside>

                <div class="hz-service-detail-main">
                    @if($accentImage)
                        <div class="hz-service-detail-featured-media">
                            <img src="{{ $accentImage }}" alt="{{ $service->title }}">
                        </div>
                    @endif

                    @if($service->description)
                        <div class="hz-service-detail-block">
                            <h2>Overview</h2>
                            <div class="hz-service-detail-prose">
                                {!! \Purifier::clean($service->description) !!}
                            </div>
                        </div>
                    @endif

                    @if($service->features)
                        <div class="hz-service-detail-block">
                            <h2>What you get</h2>
                            <div class="hz-service-detail-features">
                                {!! \Purifier::clean($service->features) !!}
                            </div>
                        </div>
                    @endif

                    <div class="hz-service-detail-cta-card">
                        <div>
                            <p class="hz-service-detail-eyebrow mb-2">Ready to start?</p>
                            <h3>Talk to the {{ $service->title }} team</h3>
                            <p class="mb-0">Get advice, pricing, or book a visit — we respond quickly on phone and WhatsApp.</p>
                        </div>
                        <div class="hz-service-detail-cta-actions">
                            <a href="{{ route('contact') }}" class="btn-hz">Contact us</a>
                            <a href="{{ route('store.index', ['category' => $service->slug]) }}" class="hz-services-shop-link">
                                Browse store listings <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($otherServices->isNotEmpty())
        <section class="hz-service-detail-related">
            <div class="container">
                <div class="hz-service-detail-related-head">
                    <h2>Explore other brands</h2>
                    <a href="{{ route('services.index') }}">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="hz-service-detail-related-grid">
                    @foreach($otherServices as $item)
                        @php
                            $thumb = $item->main_image_url ?: ($jrImages[$item->slug] ?? null);
                            $itemAccent = $brandAccents[$item->slug] ?? 'var(--hz-accent)';
                        @endphp
                        <a
                            href="{{ route('services.show', $item->slug) }}"
                            class="hz-service-detail-related-card"
                            style="--brand-accent: {{ $itemAccent }}"
                        >
                            @if($thumb)
                                <img src="{{ $thumb }}" alt="{{ $item->title }}">
                            @endif
                            <span>{{ $item->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-horizon.cta
        title="Not sure which brand fits?"
        text="Tell us what you need — turf, a phone, property, or hair — and we will connect you to the right JR team."
        button="Start a conversation"
    />
</div>
@endsection
