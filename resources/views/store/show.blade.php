@extends('layouts.app')

@section('title', $entity->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $entity->description), 160))

@php
    $image = $entity->getFirstMediaUrl('image') ?: null;
    $location = $data['address'] ?? 'Addis Ababa, Ethiopia';
    $phones = $data['phone'] ?? [];
    $emails = $data['email'] ?? [];
    $primaryPhone = $phones[0] ?? null;
    $phoneDigits = $primaryPhone ? preg_replace('/\D+/', '', $primaryPhone) : null;
@endphp

@section('content')
<div class="hz-store-market hz-store-detail hz-store-detail-page">
    <div class="hz-bny-crumb hz-bny-crumb-mobile">
        <div class="container">
            <nav class="hz-bny-crumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('store.index') }}" class="hz-bny-crumb-home">
                    <i class="bi bi-arrow-left"></i> Store
                </a>
                <span class="hz-bny-crumb-sep">/</span>
                <span class="hz-bny-crumb-current">{{ \Illuminate\Support\Str::limit($entity->name, 42) }}</span>
            </nav>
        </div>
    </div>

    <div class="container hz-store-detail-body">
        <div class="hz-store-detail-layout">
            <div class="hz-store-detail-media">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $entity->name }}">
                @else
                    <div class="hz-bny-card-placeholder" aria-hidden="true">
                        <i class="bi bi-bag"></i>
                    </div>
                @endif
                <span class="hz-bny-card-badge">{{ strtoupper(\Illuminate\Support\Str::before($categoryLabel ?? 'JR', ' ')) }}</span>
            </div>

            <div class="hz-store-detail-main">
                <p class="hz-store-detail-eyebrow">{{ $categoryLabel }}</p>
                <h1 class="hz-store-detail-title">{{ $entity->name }}</h1>
                <p class="hz-store-detail-price">
                    {{ $entity->formattedPrice() }}
                    @if($entity->is_negotiable)
                        <span class="hz-store-detail-price-note">· Negotiable</span>
                    @endif
                </p>

                @if($reviewCount > 0)
                    <div class="hz-store-detail-rating">
                        <x-horizon.star-rating :rating="$averageRating" :count="$reviewCount" size="md" />
                        <a href="#reviews" class="hz-store-detail-rating-link">{{ $reviewCount }} {{ \Illuminate\Support\Str::plural('review', $reviewCount) }}</a>
                    </div>
                @endif

                @if($entity->description)
                    <div class="hz-store-detail-copy">
                        @foreach(preg_split('/\n\s*\n/', trim((string) $entity->description)) ?: [] as $paragraph)
                            @if(trim($paragraph) !== '')
                                <p>{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="hz-store-contact-card hz-store-contact-card-mobile">
                    <h2 class="hz-store-contact-title">Contact JR about this item</h2>

                    <div class="hz-store-contact-row">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <span class="hz-store-contact-label">Address</span>
                            <p>{{ $location }}</p>
                        </div>
                    </div>

                    @if($phones)
                        <div class="hz-store-contact-row">
                            <i class="bi bi-telephone-fill"></i>
                            <div>
                                <span class="hz-store-contact-label">Phone</span>
                                @foreach($phones as $phone)
                                    <p>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($emails)
                        <div class="hz-store-contact-row">
                            <i class="bi bi-envelope-fill"></i>
                            <div>
                                <span class="hz-store-contact-label">Email</span>
                                @foreach($emails as $email)
                                    <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="hz-store-contact-actions hz-store-contact-actions-desktop">
                        @if($primaryPhone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $primaryPhone) }}" class="btn-hz">
                                <i class="bi bi-telephone"></i> Call now
                            </a>
                            @if($phoneDigits)
                                <a href="https://wa.me/{{ ltrim($phoneDigits, '+') }}" class="btn-hz-outline" target="_blank" rel="noopener">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            @endif
                        @endif
                        <a href="#write-review" class="btn-hz-outline">Write review</a>
                    </div>
                </div>
            </div>
        </div>

        <x-horizon.store-payment
            :product="$entity"
            :payment="$data['payment'] ?? []"
            :phones="$phones"
        />

        <section class="hz-store-reviews" id="reviews">
            <div class="hz-store-reviews-head">
                <div>
                    <h2>Customer reviews</h2>
                    @if($reviewCount > 0)
                        <p>{{ $averageRating }} average from {{ $reviewCount }} {{ \Illuminate\Support\Str::plural('review', $reviewCount) }}</p>
                    @else
                        <p>No reviews yet — be the first.</p>
                    @endif
                </div>
                <a href="#write-review" class="btn-hz btn-hz-sm hz-store-review-btn">Write review</a>
            </div>

            @if(session('review_success'))
                <div class="hz-store-review-alert" role="status">
                    <i class="bi bi-check-circle-fill"></i> {{ session('review_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="hz-store-review-alert hz-store-review-alert-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Please fix the errors below and try again.
                </div>
            @endif

            <div class="hz-store-reviews-layout">
                <div class="hz-store-reviews-list">
                    @forelse($reviews as $review)
                        <article class="hz-store-review-card">
                            <div class="hz-store-review-card-head">
                                <strong>{{ $review->author_name }}</strong>
                                <x-horizon.star-rating :rating="$review->rating" size="sm" />
                            </div>
                            <p>{{ $review->body }}</p>
                            <time datetime="{{ $review->created_at?->toDateString() }}">
                                {{ $review->created_at?->diffForHumans() }}
                            </time>
                        </article>
                    @empty
                        <div class="hz-store-review-empty">
                            <i class="bi bi-chat-heart"></i>
                            <p>Share your experience with this product.</p>
                        </div>
                    @endforelse
                </div>

                <div class="hz-store-review-form-wrap" id="write-review">
                    <h3>Write a review</h3>
                    <form action="{{ route('store.reviews.store', $entity) }}" method="post" class="hz-store-review-form" data-review-form>
                        @csrf
                        <label class="hz-store-field">
                            <span>Your name</span>
                            <input type="text" name="author_name" value="{{ old('author_name') }}" required maxlength="120" placeholder="e.g. Sara M.">
                            @error('author_name')<small class="hz-store-field-error">{{ $message }}</small>@enderror
                        </label>

                        <div class="hz-store-field">
                            <span>Your rating</span>
                            <div class="hz-star-input" data-star-input>
                                @for($i = 1; $i <= 5; $i++)
                                    <label>
                                        <input type="radio" name="rating" value="{{ $i }}" @checked((int) old('rating', 5) === $i) required>
                                        <i class="bi bi-star-fill"></i>
                                        <span class="visually-hidden">{{ $i }} stars</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')<small class="hz-store-field-error">{{ $message }}</small>@enderror
                        </div>

                        <label class="hz-store-field">
                            <span>Your review</span>
                            <textarea name="body" rows="4" required minlength="10" maxlength="2000" placeholder="What did you like? How was the quality and service?">{{ old('body') }}</textarea>
                            @error('body')<small class="hz-store-field-error">{{ $message }}</small>@enderror
                        </label>

                        <button type="submit" class="btn-hz hz-store-review-submit">Post review</button>
                    </form>
                </div>
            </div>
        </section>

        @if($related->isNotEmpty())
            <section class="hz-store-related">
                <div class="hz-bny-toolbar hz-store-related-head">
                    <div>
                        <h2 class="hz-bny-toolbar-title">More in {{ $categoryLabel }}</h2>
                        <p class="hz-bny-toolbar-count">{{ $related->count() }} related items</p>
                    </div>
                    <a href="{{ route('store.index', ['category' => $entity->category]) }}" class="hz-link">View all</a>
                </div>
                <div class="hz-bny-grid">
                    @foreach($related as $product)
                        <x-horizon.store-product-card
                            :product="$product"
                            :category-label="$categoryLabel"
                            :location="$location"
                            :is-new="$product->order <= 2"
                            :average-rating="$product->reviews_avg_rating"
                            :review-count="$product->reviews_count"
                        />
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <div class="hz-store-mobile-bar">
        @if($primaryPhone)
            <a href="tel:{{ preg_replace('/\s+/', '', $primaryPhone) }}" class="hz-store-mobile-bar-btn">
                <i class="bi bi-telephone-fill"></i>
                <span>Call</span>
            </a>
        @endif
        <a href="#payment" class="hz-store-mobile-bar-btn hz-store-mobile-bar-btn-pay">
            <i class="bi bi-cash-coin"></i>
            <span>Pay</span>
        </a>
        @if($phoneDigits)
            <a href="https://wa.me/{{ ltrim($phoneDigits, '+') }}?text={{ rawurlencode('Hi JR, I want to pay for: '.$entity->name.($entity->priceAmount() ? ' (ETB '.number_format($entity->priceAmount(), 0).')' : '')) }}" class="hz-store-mobile-bar-btn" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp"></i>
                <span>WhatsApp</span>
            </a>
        @endif
    </div>
</div>
@endsection
