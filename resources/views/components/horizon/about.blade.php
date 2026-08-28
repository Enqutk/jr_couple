@props([
    'about' => null,
    'features' => null,
    'showLink' => true,
])

@if($about)
@php
    $image = $about['image'] ?? null;
    $eyebrow = $about['subtitle'] ?: 'Who we are';
    $title = $about['title'] ?: ($data['siteName'] ?? config('app.name'));
    $featureItems = collect($features?->list_items ?? [])->take(4);
@endphp

<section class="hz-section hz-about" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                @if($image)
                    <div class="hz-about-media">
                        <img src="{{ $image }}" alt="{{ $title }}">
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                <p class="hz-eyebrow">{{ $eyebrow }}</p>
                <h2 class="hz-title hz-about-title">{{ $title }}</h2>
                <div class="hz-lead hz-about-copy">{!! $about['description'] !!}</div>

                @if($featureItems->isNotEmpty())
                    <div class="row g-3 mt-1">
                        @foreach($featureItems as $item)
                            <div class="col-sm-6">
                                <div class="hz-about-feature">
                                    <div class="hz-card-icon flex-shrink-0" aria-hidden="true">
                                        <i class="{{ $item['icon'] ?? 'bi bi-check-lg' }}"></i>
                                    </div>
                                    <div>
                                        <h3 class="h6 mb-1">{{ $item['title'] ?? '' }}</h3>
                                        <p class="small mb-0">{{ $item['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($showLink)
                    <div class="mt-4">
                        <a href="{{ route('about') }}" class="btn-hz">
                            More about us <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
