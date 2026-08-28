@props([
    'stats' => [],
    'title' => 'Impact that compounds',
    'subtitle' => 'By the numbers',
])

@php
    $items = collect($stats)->filter(fn ($stat) => filled($stat['label'] ?? $stat['title'] ?? null))->values();
@endphp

@if($items->isNotEmpty())
<section class="hz-section hz-stats hz-section-dark" aria-label="Impact statistics">
    <div class="container">
        <div class="row justify-content-between align-items-end mb-4 g-3">
            <div class="col-lg-7">
                <p class="hz-eyebrow hz-eyebrow-light">{{ $subtitle }}</p>
                <h2 class="hz-title text-white mb-0">{{ $title }}</h2>
            </div>
        </div>

        <div class="row g-0 hz-stats-grid">
            @foreach($items as $stat)
                <div class="col-6 col-lg-3">
                    <div class="hz-stat">
                        <div
                            class="hz-stat-value"
                            data-counter="{{ (int) ($stat['value'] ?? 0) }}"
                            data-suffix="{{ $stat['suffix'] ?? '' }}"
                        >0{{ $stat['suffix'] ?? '' }}</div>
                        <div class="hz-stat-label">{{ $stat['label'] ?? ($stat['title'] ?? '') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
