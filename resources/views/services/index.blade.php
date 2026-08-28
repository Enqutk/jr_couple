@extends('layouts.app')

@section('title', 'Our Services')
@section('description', $data['metaDescription'] ?? 'JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair — one trusted family of brands in Addis Ababa.')

@php
    $jrImages = [
        'jr-ketema' => asset('assets/images/jr/covers/ketema.jpg'),
        'jr-mobile' => asset('assets/images/jr/covers/mobile-1.jpg'),
        'jr-real-estate' => asset('assets/images/jr/covers/real-estate.jpg'),
        'ruties-hair' => asset('assets/images/jr/covers/hair-avatar.jpg'),
    ];

    $brandAccents = [
        'jr-ketema' => '#15803d',
        'jr-mobile' => '#1d4ed8',
        'jr-real-estate' => '#b45309',
        'ruties-hair' => '#be185d',
    ];
@endphp

@section('content')
<div class="hz-services-page">
    <section class="hz-services-hero">
        <div class="container">
            <div class="hz-services-hero-inner">
                <div>
                    <p class="hz-services-eyebrow">JR Couple family of brands</p>
                    <h1 class="hz-services-title">Four brands.<br>One trusted name.</h1>
                    <p class="hz-services-lead">
                        From artificial grass and phones to property and hair — explore what each JR brand offers, then shop listings in our store.
                    </p>
                </div>
                <div class="hz-services-hero-stats">
                    <div class="hz-services-stat">
                        <strong>{{ $services->count() }}</strong>
                        <span>Active brands</span>
                    </div>
                    <div class="hz-services-stat">
                        <strong>12+</strong>
                        <span>Store listings</span>
                    </div>
                    <div class="hz-services-stat">
                        <strong>ETB</strong>
                        <span>Local pricing</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hz-services-brands">
        <div class="container">
            <div class="hz-services-brand-grid">
                @foreach($services as $index => $service)
                    @php
                        $image = $service->main_image_url ?: ($jrImages[$service->slug] ?? null);
                        $accent = $brandAccents[$service->slug] ?? 'var(--hz-accent)';
                    @endphp
                    <article class="hz-services-brand-card" style="--brand-accent: {{ $accent }}">
                        <a href="{{ route('services.show', $service->slug) }}" class="hz-services-brand-media">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $service->title }}">
                            @endif
                            <div class="hz-services-brand-overlay">
                                <span class="hz-services-brand-index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="hz-services-brand-tag">{{ $service->quote }}</span>
                            </div>
                        </a>
                        <div class="hz-services-brand-body">
                            <div class="hz-services-brand-head">
                                <h2>
                                    <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                                </h2>
                                <span class="hz-services-brand-dot" aria-hidden="true"></span>
                            </div>
                            <p>{{ $service->short_description }}</p>
                            <div class="hz-services-brand-actions">
                                <a href="{{ route('services.show', $service->slug) }}" class="btn-hz btn-hz-sm">
                                    Learn more <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ route('store.index', ['category' => $service->slug]) }}" class="hz-services-shop-link">
                                    Shop store <i class="bi bi-bag"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="hz-services-strip">
        <div class="container">
            <div class="hz-services-strip-card">
                <div>
                    <p class="hz-services-eyebrow mb-2">Ready to buy?</p>
                    <h2 class="hz-services-strip-title">Browse verified listings in the JR store</h2>
                    <p class="mb-0">Phones, turf, property, and hair — with direct contact and in-store pickup.</p>
                </div>
                <a href="{{ route('store.index') }}" class="btn-hz">
                    Open store <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
