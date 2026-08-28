@props(['heroes' => collect()])

@php
    $slides = $heroes instanceof \Illuminate\Support\Collection ? $heroes : collect($heroes);
    $hasMultiple = $slides->count() > 1;
    $siteName = $data['siteName'] ?? config('app.name', 'Site');
    $tagline = $data['tagline'] ?? '';
@endphp

<section class="hz-hero" aria-label="Homepage hero">
    @if($slides->isEmpty())
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5 hz-hero-slide">
                <div class="col-lg-6 hz-hero-copy-col">
                    @if($tagline)
                        <p class="hz-eyebrow">{{ \Illuminate\Support\Str::limit($tagline, 60) }}</p>
                    @endif
                    <x-site-brand as="h1" class="hz-hero-brand" :name="$siteName" />
                    @if($tagline)
                        <p class="hz-hero-copy">{{ $tagline }}</p>
                    @endif
                    <div class="hz-hero-actions">
                        <a href="{{ route('services.index') }}" class="btn-hz">
                            Explore services <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('contact') }}" class="btn-hz-outline">Talk to us</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div
            id="hzHeroCarousel"
            class="carousel slide hz-hero-carousel"
            @if($hasMultiple) data-bs-ride="carousel" data-bs-interval="4500" data-bs-pause="hover" data-bs-touch="true" data-bs-wrap="true" @endif
        >
            @if($hasMultiple)
                <div class="carousel-indicators hz-hero-indicators">
                    @foreach($slides as $index => $slide)
                        <button
                            type="button"
                            data-bs-target="#hzHeroCarousel"
                            data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @foreach($slides as $index => $hero)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="container hz-hero-container">
                            <div class="row align-items-center g-0 g-lg-5 hz-hero-slide">
                                <div class="col-lg-6 hz-hero-copy-col">
                                    @if($hero->subtitle)
                                        <p class="hz-eyebrow">{{ $hero->subtitle }}</p>
                                    @endif

                                    <x-site-brand
                                        :as="$index === 0 ? 'h1' : 'p'"
                                        class="hz-hero-brand"
                                        :name="$siteName"
                                    />

                                    <h2 class="hz-hero-title">{{ $hero->title }}</h2>

                                    @if($hero->description)
                                        <p class="hz-hero-copy">{{ $hero->description }}</p>
                                    @endif

                                    <div class="hz-hero-actions">
                                        <a href="{{ $hero->button_link ?: route('services.index') }}" class="btn-hz">
                                            {{ $hero->text_link ?: 'Explore services' }}
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                        <a href="{{ route('contact') }}" class="btn-hz-outline">Talk to us</a>
                                    </div>
                                </div>
                                <div class="col-lg-6 hz-hero-media-col">
                                    <div class="hz-hero-media">
                                        @if($hero->image_url)
                                            <img
                                                src="{{ $hero->image_url }}"
                                                alt="{{ $hero->title }}"
                                                @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                                decoding="async"
                                            >
                                        @else
                                            <div class="hz-hero-media-fallback" aria-hidden="true"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($hasMultiple)
                <button class="carousel-control-prev hz-hero-control" type="button" data-bs-target="#hzHeroCarousel" data-bs-slide="prev">
                    <span class="hz-hero-control-icon" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next hz-hero-control" type="button" data-bs-target="#hzHeroCarousel" data-bs-slide="next">
                    <span class="hz-hero-control-icon" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @endif
</section>
