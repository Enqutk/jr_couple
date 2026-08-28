@extends('layouts.inner')

@section('title', 'Store')
@section('eyebrow', 'JR Store')
@section('page_title', 'Store')
@section('description', $data['metaDescription'] ?? 'Shop JR Ketema, JR Mobile, and JR Real Estate products.')

@section('page')
<section class="hz-section hz-store">
    <div class="container">
        <div class="hz-store-intro mb-4">
            <p class="hz-lead mb-0" style="max-width: 36rem;">
                A neighbourhood-store vibe — browse by JR brand, then pick what you need. Ask in person for live stock and pricing.
            </p>
        </div>

        <div class="hz-store-cats mb-4" data-store-filter>
            <a href="{{ route('store.index') }}" class="hz-store-cat {{ ! $category ? 'active' : '' }}">All</a>
            @foreach($services as $service)
                <a
                    href="{{ route('store.index', ['category' => $service->slug]) }}"
                    class="hz-store-cat {{ $category === $service->slug ? 'active' : '' }}"
                >
                    {{ $service->title }}
                </a>
            @endforeach
        </div>

        @if($activeService)
            <p class="text-muted mb-4">
                Showing <strong>{{ $activeService->title }}</strong>
                — <a href="{{ route('services.show', $activeService->slug) }}" class="hz-link">Learn about this service</a>
            </p>
        @endif

        <div class="row g-4">
            @forelse($products as $product)
                @php $image = $product->getFirstMediaUrl('image') ?: null; @endphp
                <div class="col-sm-6 col-lg-4">
                    <article class="hz-store-card">
                        <div class="hz-store-card-media">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $product->name }}">
                            @else
                                <div class="hz-store-card-placeholder" aria-hidden="true">
                                    <i class="bi bi-bag"></i>
                                </div>
                            @endif
                            @if($product->category)
                                @php
                                    $catLabel = $services->firstWhere('slug', $product->category)?->title ?? $product->category;
                                @endphp
                                <span class="hz-store-card-tag">{{ $catLabel }}</span>
                            @endif
                        </div>
                        <div class="hz-store-card-body">
                            <h3 class="h5 mb-2">{{ $product->name }}</h3>
                            <p class="mb-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 100) }}</p>
                            <a href="{{ route('contact') }}" class="btn-hz btn-hz-sm">Ask about this</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">No products in this category yet. Check back soon or contact us.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
