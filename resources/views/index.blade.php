@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <x-horizon.hero :heroes="$heroes" />
    <x-horizon.services :services="$services" />

    <section class="hz-section hz-store-teaser bg-surface border-top border-bottom border-hz">
        <div class="container">
            <div class="row justify-content-between align-items-end mb-4 g-3">
                <div class="col-lg-7">
                    <p class="hz-eyebrow">JR Store</p>
                    <h2 class="hz-title mb-0">Shop by brand category</h2>
                </div>
                <div class="col-lg-auto">
                    <a href="{{ route('store.index') }}" class="btn-hz-outline">Open store</a>
                </div>
            </div>
            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-md-4">
                        <a href="{{ route('store.index', ['category' => $service->slug]) }}" class="hz-store-cat-tile">
                            <span class="hz-store-cat-tile-label">Category</span>
                            <strong>{{ $service->title }}</strong>
                            <span class="hz-link mt-2 d-inline-flex">Browse <i class="bi bi-arrow-right"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
            @if($storeProducts->isNotEmpty())
                <div class="row g-4 mt-2">
                    @foreach($storeProducts->take(3) as $product)
                        <div class="col-md-4">
                            <a href="{{ route('store.show', $product) }}" class="d-block text-decoration-none">
                                <article class="hz-store-card hz-store-card-compact">
                                    @if($product->getFirstMediaUrl('image'))
                                        <div class="hz-store-card-media" style="aspect-ratio: 16/10;">
                                            <img src="{{ $product->getFirstMediaUrl('image') }}" alt="{{ $product->name }}">
                                        </div>
                                    @endif
                                    <div class="hz-store-card-body">
                                        <h3 class="h6 mb-1">{{ $product->name }}</h3>
                                        <p class="small mb-0 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags((string) $product->description), 70) }}</p>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($posts->isNotEmpty())
        <section class="hz-section">
            <div class="container">
                <div class="row justify-content-between align-items-end mb-4 g-3">
                    <div class="col-lg-7">
                        <p class="hz-eyebrow">Blog</p>
                        <h2 class="hz-title mb-0">Tips from the JR team</h2>
                    </div>
                    <div class="col-lg-auto">
                        <a href="{{ route('blog.index') }}" class="btn-hz-outline">All posts</a>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-md-4">
                            <x-horizon.blog-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-horizon.cta title="Visit JR or message us" :text="$data['tagline'] ?? null" button="Contact JR" />
@endsection
