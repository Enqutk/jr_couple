@extends('layouts.app')

@section('title', 'Store')
@section('description', $data['metaDescription'] ?? 'Shop JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair.')

@php
    $location = $data['address'] ?? 'Addis Ababa, Ethiopia';
    $pageTitle = match ($feed) {
        'new' => 'Newly posted',
        'trending' => 'Trending now',
        default => $activeService?->title ?? 'All products',
    };
    $productCount = $products->count();
    $storeBaseQuery = array_filter([
        'category' => $category,
        'q' => $search ?: null,
    ]);
    $feedUrl = fn (string $value) => route('store.index', array_merge(
        $storeBaseQuery,
        $value === 'all' ? [] : ['feed' => $value]
    ));
@endphp

@section('content')
<div class="hz-store-market">
    @if($featuredProduct)
        @php
            $featuredCategory = $services->firstWhere('slug', $featuredProduct->category)?->title;
        @endphp
        <x-horizon.store-hero
            :product="$featuredProduct"
            :category-label="$featuredCategory"
            badge="Promoted"
            tag="#JRFeatured"
        />
    @endif

    <div class="hz-store-topbar">
        <div class="container">
            <form action="{{ route('store.index') }}" method="get" class="hz-store-search" role="search">
                @if($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                @if($feed !== 'all')
                    <input type="hidden" name="feed" value="{{ $feed }}">
                @endif
                <label class="visually-hidden" for="store-search">Search store</label>
                <i class="bi bi-search"></i>
                <input
                    id="store-search"
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search products..."
                    autocomplete="off"
                >
                <button type="submit" class="hz-store-search-btn">Search</button>
            </form>

            <div class="hz-store-feed-tabs" role="tablist" aria-label="Store feeds">
                <a href="{{ $feedUrl('all') }}" class="hz-store-feed-tab {{ $feed === 'all' ? 'active' : '' }}">All</a>
                <a href="{{ $feedUrl('new') }}" class="hz-store-feed-tab {{ $feed === 'new' ? 'active' : '' }}">Newly posted</a>
                <a href="{{ $feedUrl('trending') }}" class="hz-store-feed-tab {{ $feed === 'trending' ? 'active' : '' }}">Trending</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="hz-store-trust-row">
            <div class="hz-store-trust-item">
                <span class="hz-store-trust-icon"><i class="bi bi-patch-check-fill"></i></span>
                <span>
                    <strong>Verified listings</strong>
                    <small>Direct JR contact & pickup</small>
                </span>
            </div>
            <a href="{{ $feedUrl('new') }}" class="hz-store-trust-item hz-store-trust-link">
                <span class="hz-store-trust-icon hz-store-trust-icon-hot"><i class="bi bi-lightning-charge-fill"></i></span>
                <span>
                    <strong>New arrivals</strong>
                    <small>Freshly posted items</small>
                </span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        @if(! $category && ! $search && $feed === 'all' && $dealProducts->isNotEmpty())
            <div class="hz-store-spotlight-grid">
                <section class="hz-store-spotlight">
                    <div class="hz-store-spotlight-head">
                        <h2>Super<span>Deals</span></h2>
                        <a href="{{ $feedUrl('trending') }}" class="hz-store-spotlight-link">View details <i class="bi bi-chevron-right"></i></a>
                    </div>
                    <div class="hz-store-spotlight-items">
                        @foreach($dealProducts as $product)
                            @php $img = $product->getFirstMediaUrl('image'); @endphp
                            <a href="{{ route('store.show', $product) }}" class="hz-store-spotlight-card">
                                <span class="hz-store-spotlight-tag">Super</span>
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $product->name }}">
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="hz-store-spotlight">
                    <div class="hz-store-spotlight-head">
                        <h2>Trends</h2>
                        <a href="#store-trending" class="hz-store-spotlight-link">View details <i class="bi bi-chevron-right"></i></a>
                    </div>
                    <div class="hz-store-spotlight-items hz-store-spotlight-items-trends">
                        @foreach($trendingProducts->take(2) as $product)
                            @php $img = $product->getFirstMediaUrl('image'); @endphp
                            <a href="{{ route('store.show', $product) }}" class="hz-store-spotlight-card hz-store-spotlight-card-tall">
                                <span class="hz-store-spotlight-tag">Trends</span>
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $product->name }}">
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        @endif
    </div>

    <div class="hz-bny-crumb">
        <div class="container">
            <nav class="hz-bny-crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hz-bny-crumb-home">
                    <i class="bi bi-arrow-left"></i> Home
                </a>
                <span class="hz-bny-crumb-sep">/</span>
                <a href="{{ route('store.index') }}" class="{{ $category || $search || $feed !== 'all' ? '' : 'is-current' }}">Store</a>
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
                            <input type="radio" name="store_category" value="" @checked(! $category) data-store-category-link="{{ route('store.index', array_filter(['feed' => $feed !== 'all' ? $feed : null, 'q' => $search ?: null])) }}">
                            <span>All brands</span>
                        </label>
                        @foreach($services as $service)
                            <label class="hz-bny-filter-option">
                                <input
                                    type="radio"
                                    name="store_category"
                                    value="{{ $service->slug }}"
                                    @checked($category === $service->slug)
                                    data-store-category-link="{{ route('store.index', array_filter(['category' => $service->slug, 'feed' => $feed !== 'all' ? $feed : null, 'q' => $search ?: null])) }}"
                                >
                                <span>{{ $service->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="hz-store-market-main">
                <div class="hz-bny-toolbar" id="store-trending">
                    <div>
                        <h1 class="hz-bny-toolbar-title">{{ $pageTitle }}</h1>
                        <p class="hz-bny-toolbar-count">
                            @if($search)
                                {{ $productCount }} results for “{{ $search }}”
                            @else
                                {{ $productCount }} {{ \Illuminate\Support\Str::plural('item', $productCount) }}
                            @endif
                        </p>
                    </div>
                    <div class="hz-bny-toolbar-actions">
                        <button type="button" class="hz-bny-filter-mobile" data-store-filter-open>
                            <i class="bi bi-sliders"></i> Filter
                        </button>
                        <label class="hz-bny-sort">
                            <span>Sort by</span>
                            <select data-store-sort>
                                <option value="recommended" @selected($feed === 'all')>Recommend</option>
                                <option value="newest" @selected($feed === 'new')>Newest</option>
                                <option value="trending" @selected($feed === 'trending')>Trending</option>
                                <option value="name_asc">Name: A–Z</option>
                                <option value="name_desc">Name: Z–A</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="hz-bny-pills" role="tablist" aria-label="Store categories">
                    <a href="{{ $feedUrl($feed) }}" class="hz-bny-pill {{ ! $category ? 'active' : '' }}">All</a>
                    @foreach($services as $service)
                        <a
                            href="{{ route('store.index', array_filter(['category' => $service->slug, 'feed' => $feed !== 'all' ? $feed : null, 'q' => $search ?: null])) }}"
                            class="hz-bny-pill {{ $category === $service->slug ? 'active' : '' }}"
                        >
                            {{ $service->title }}
                        </a>
                    @endforeach
                </div>

                @if($feed === 'all' && ! $search && $newProducts->isNotEmpty())
                    <div class="hz-store-section-label">
                        <span></span>
                        <h2>Newly posted</h2>
                        <span></span>
                    </div>
                    <div class="hz-bny-grid hz-store-mini-grid mb-4">
                        @foreach($newProducts as $product)
                            @php $catLabel = $services->firstWhere('slug', $product->category)?->title ?? $product->category; @endphp
                            <x-horizon.store-product-card
                                :product="$product"
                                :category-label="$catLabel"
                                :location="$location"
                                :is-new="true"
                            />
                        @endforeach
                    </div>
                    <div class="hz-store-section-label">
                        <span></span>
                        <h2>For you</h2>
                        <span></span>
                    </div>
                @endif

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
                                :is-new="$product->created_at?->greaterThan(now()->subDays(14))"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="hz-bny-empty">
                        <i class="bi bi-stars"></i>
                        <h2>No listings found</h2>
                        <p>Try another search, category, or feed.</p>
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
                <form action="{{ route('store.index') }}" method="get" class="hz-store-search hz-store-search-sheet">
                    <label class="hz-bny-sheet-label" for="store-search-sheet">Search</label>
                    <div class="hz-store-search-field">
                        <i class="bi bi-search"></i>
                        <input id="store-search-sheet" type="search" name="q" value="{{ $search }}" placeholder="Search products...">
                    </div>
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    @if($feed !== 'all')
                        <input type="hidden" name="feed" value="{{ $feed }}">
                    @endif
                    <button type="submit" class="hz-store-search-btn hz-store-search-btn-block">Search store</button>
                </form>

                <p class="hz-bny-sheet-label">Feed</p>
                <div class="hz-store-sheet-feeds">
                    <a href="{{ $feedUrl('all') }}" class="{{ $feed === 'all' ? 'active' : '' }}">All</a>
                    <a href="{{ $feedUrl('new') }}" class="{{ $feed === 'new' ? 'active' : '' }}">Newly posted</a>
                    <a href="{{ $feedUrl('trending') }}" class="{{ $feed === 'trending' ? 'active' : '' }}">Trending</a>
                </div>

                <p class="hz-bny-sheet-label">Sort by</p>
                <div class="hz-bny-sheet-sort" data-store-sheet-sort>
                    <button type="button" class="{{ $feed === 'all' ? 'active' : '' }}" data-sort="recommended">Recommend</button>
                    <button type="button" class="{{ $feed === 'new' ? 'active' : '' }}" data-sort="newest">Newest</button>
                    <button type="button" class="{{ $feed === 'trending' ? 'active' : '' }}" data-sort="trending">Trending</button>
                    <button type="button" data-sort="name_asc">Name A–Z</button>
                </div>

                <div class="hz-bny-sidebar hz-bny-sidebar-mobile">
                    <div class="hz-bny-filter-block is-open">
                        <p class="hz-bny-sheet-label">Category</p>
                        <div class="hz-bny-filter-panel is-static">
                            <a href="{{ $feedUrl($feed) }}" class="hz-bny-sheet-link {{ ! $category ? 'active' : '' }}">All brands</a>
                            @foreach($services as $service)
                                <a
                                    href="{{ route('store.index', array_filter(['category' => $service->slug, 'feed' => $feed !== 'all' ? $feed : null, 'q' => $search ?: null])) }}"
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
