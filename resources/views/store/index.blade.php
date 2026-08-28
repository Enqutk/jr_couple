@extends('layouts.app')

@section('title', 'Store')
@section('description', $data['metaDescription'] ?? 'Shop JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair.')

@php
    $location = $data['address'] ?? 'Addis Ababa, Ethiopia';
    $pageTitle = $activeService?->title ?? 'All products';
    $productCount = $products->count();
@endphp

@section('content')
<div class="hz-store-market">
    <div class="hz-bny-crumb">
        <div class="container">
            <nav class="hz-bny-crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hz-bny-crumb-home">
                    <i class="bi bi-arrow-left"></i> Home
                </a>
                <span class="hz-bny-crumb-sep">/</span>
                <a href="{{ route('store.index') }}" class="{{ $category ? '' : 'is-current' }}">Store</a>
                @if($activeService)
                    <span class="hz-bny-crumb-sep">/</span>
                    <span class="hz-bny-crumb-current">{{ $activeService->title }}</span>
                @endif
            </nav>
        </div>
    </div>

    <div class="container hz-store-market-body">
        <div class="hz-store-market-layout">
            <aside class="hz-bny-sidebar" id="store-sidebar">
                <div class="hz-bny-sidebar-head">
                    <h2 class="hz-bny-sidebar-title">
                        <i class="bi bi-sliders"></i> Filter
                    </h2>
                    <a href="{{ route('store.index') }}" class="hz-bny-sidebar-reset">Reset</a>
                </div>

                <div class="hz-bny-filter-block is-open" data-filter-section>
                    <button type="button" class="hz-bny-filter-toggle" data-filter-toggle>
                        Category
                        <i class="bi bi-chevron-up"></i>
                    </button>
                    <div class="hz-bny-filter-panel">
                        <label class="hz-bny-filter-option">
                            <input type="radio" name="store_category" value="" @checked(! $category) data-store-category-link="{{ route('store.index') }}">
                            <span>All brands</span>
                        </label>
                        @foreach($services as $service)
                            <label class="hz-bny-filter-option">
                                <input
                                    type="radio"
                                    name="store_category"
                                    value="{{ $service->slug }}"
                                    @checked($category === $service->slug)
                                    data-store-category-link="{{ route('store.index', ['category' => $service->slug]) }}"
                                >
                                <span>{{ $service->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="hz-bny-filter-block is-open" data-filter-section>
                    <button type="button" class="hz-bny-filter-toggle" data-filter-toggle>
                        Availability
                        <i class="bi bi-chevron-up"></i>
                    </button>
                    <div class="hz-bny-filter-panel">
                        <label class="hz-bny-filter-switch">
                            <span>In stock today</span>
                            <input type="checkbox" checked disabled>
                        </label>
                        <label class="hz-bny-filter-switch">
                            <span>Negotiable prices</span>
                            <input type="checkbox" checked disabled>
                        </label>
                    </div>
                </div>
            </aside>

            <div class="hz-store-market-main">
                <div class="hz-bny-toolbar">
                    <div>
                        <h1 class="hz-bny-toolbar-title">{{ $pageTitle }}</h1>
                        <p class="hz-bny-toolbar-count">{{ $productCount }} {{ \Illuminate\Support\Str::plural('item', $productCount) }}</p>
                    </div>
                    <div class="hz-bny-toolbar-actions">
                        <button type="button" class="hz-bny-filter-mobile" data-store-filter-open>
                            <i class="bi bi-sliders"></i> Filter
                        </button>
                        <label class="hz-bny-sort">
                            <span>Sort by</span>
                            <select data-store-sort>
                                <option value="recommended">Recommend</option>
                                <option value="newest">Newest</option>
                                <option value="name_asc">Name: A–Z</option>
                                <option value="name_desc">Name: Z–A</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="hz-bny-pills" role="tablist" aria-label="Store categories">
                    <a href="{{ route('store.index') }}" class="hz-bny-pill {{ ! $category ? 'active' : '' }}">All</a>
                    @foreach($services as $service)
                        <a
                            href="{{ route('store.index', ['category' => $service->slug]) }}"
                            class="hz-bny-pill {{ $category === $service->slug ? 'active' : '' }}"
                        >
                            {{ $service->title }}
                        </a>
                    @endforeach
                </div>

                @if($products->isNotEmpty())
                    <div class="hz-bny-grid" data-store-grid>
                        @foreach($products as $product)
                            @php
                                $catLabel = $services->firstWhere('slug', $product->category)?->title ?? $product->category;
                            @endphp
                            <x-horizon.store-product-card
                                :product="$product"
                                :category-label="$catLabel"
                                :location="$location"
                                :is-new="$product->order <= 2"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="hz-bny-empty">
                        <i class="bi bi-stars"></i>
                        <h2>No listings found</h2>
                        <p>Try another category or contact us for custom orders.</p>
                        <a href="{{ route('store.index') }}" class="btn-hz">Browse all</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="hz-bny-sheet" data-store-sheet hidden>
        <div class="hz-bny-sheet-backdrop" data-store-sheet-close></div>
        <div class="hz-bny-sheet-panel" role="dialog" aria-modal="true" aria-labelledby="store-filter-title">
            <div class="hz-bny-sheet-head">
                <h2 id="store-filter-title">Filter &amp; sort</h2>
                <button type="button" class="hz-bny-sheet-close" data-store-sheet-close aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="hz-bny-sheet-body">
                <p class="hz-bny-sheet-label">Sort by</p>
                <div class="hz-bny-sheet-sort" data-store-sheet-sort>
                    <button type="button" class="active" data-sort="recommended">Recommend</button>
                    <button type="button" data-sort="newest">Newest</button>
                    <button type="button" data-sort="name_asc">Name A–Z</button>
                    <button type="button" data-sort="name_desc">Name Z–A</button>
                </div>
                <div class="hz-bny-sidebar hz-bny-sidebar-mobile">
                    <div class="hz-bny-filter-block is-open">
                        <p class="hz-bny-sheet-label">Category</p>
                        <div class="hz-bny-filter-panel is-static">
                            <a href="{{ route('store.index') }}" class="hz-bny-sheet-link {{ ! $category ? 'active' : '' }}">All brands</a>
                            @foreach($services as $service)
                                <a
                                    href="{{ route('store.index', ['category' => $service->slug]) }}"
                                    class="hz-bny-sheet-link {{ $category === $service->slug ? 'active' : '' }}"
                                >
                                    {{ $service->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="hz-bny-sheet-foot">
                <a href="{{ route('store.index') }}" class="hz-bny-sheet-reset">Reset</a>
                <button type="button" class="hz-bny-sheet-apply" data-store-sheet-close>Show {{ $productCount }} results</button>
            </div>
        </div>
    </div>
</div>
@endsection
