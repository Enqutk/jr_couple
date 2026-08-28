@props([
    'product',
    'categoryLabel' => null,
    'location' => 'Addis Ababa',
    'isNew' => false,
])

@php
    $image = $product->getFirstMediaUrl('image') ?: null;
    $tag = $categoryLabel
        ? strtoupper(\Illuminate\Support\Str::before($categoryLabel, ' '))
        : 'JR';
@endphp

<article class="hz-bny-card" data-store-item data-name="{{ strtolower($product->name) }}" data-order="{{ $product->order }}">
    <a href="{{ route('contact') }}" class="hz-bny-card-link">
        <div class="hz-bny-card-media">
            @if($image)
                <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
            @else
                <div class="hz-bny-card-placeholder" aria-hidden="true">
                    <i class="bi bi-bag"></i>
                </div>
            @endif

            <span class="hz-bny-card-badge">{{ $tag }}</span>

            @if($isNew)
                <span class="hz-bny-card-badge hz-bny-card-badge-new">New</span>
            @endif

            <button type="button" class="hz-bny-card-save" aria-label="Save listing" data-store-save>
                <i class="bi bi-heart"></i>
            </button>

            <div class="hz-bny-card-delivery">Meetup · In-store pickup</div>
        </div>

        <div class="hz-bny-card-body">
            <h3 class="hz-bny-card-title">{{ $product->name }}</h3>
            <p class="hz-bny-card-seller">{{ $data['siteName'] ?? 'JR' }}</p>
            <div class="hz-bny-card-meta">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $location }}</span>
                <span aria-hidden="true">•</span>
                <span>Recently</span>
            </div>
            <div class="hz-bny-card-price-row">
                <span class="hz-bny-card-price">Ask in store</span>
            </div>
            <div class="hz-bny-card-foot">
                <span class="hz-bny-card-chip">Negotiable</span>
                <span class="hz-bny-card-chip hz-bny-card-chip-verified">
                    <i class="bi bi-patch-check-fill"></i> Verified
                </span>
            </div>
        </div>
    </a>
</article>
