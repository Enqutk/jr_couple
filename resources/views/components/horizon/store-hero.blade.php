@props([
    'product',
    'categoryLabel' => null,
    'badge' => 'Featured',
    'tag' => '#JRStore',
])

@php
    $image = $product->getFirstMediaUrl('image') ?: null;
    $category = $categoryLabel ?? $product->category;
@endphp

<section class="hz-store-hero">
    <div class="container">
        <div class="hz-store-hero-card">
            <div class="hz-store-hero-copy">
                <span class="hz-store-hero-badge">{{ $badge }}</span>
                <p class="hz-store-hero-tag">{{ $tag }}</p>
                <h2 class="hz-store-hero-title">{{ $product->name }}</h2>
                <p class="hz-store-hero-text">
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 140) }}
                </p>
                @if($category)
                    <p class="hz-store-hero-meta">{{ $category }}</p>
                @endif
                <a href="{{ route('store.show', $product) }}" class="btn-hz hz-store-hero-btn">
                    View promotion <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <a href="{{ route('store.show', $product) }}" class="hz-store-hero-media">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $product->name }}">
                @else
                    <div class="hz-bny-card-placeholder" aria-hidden="true">
                        <i class="bi bi-bag"></i>
                    </div>
                @endif
                <span class="hz-store-hero-price">{{ $product->formattedPrice() }}</span>
            </a>
        </div>
    </div>
</section>
