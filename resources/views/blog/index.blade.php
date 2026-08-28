@extends('layouts.app')

@section('title', 'Blog')
@section('description', $data['metaDescription'] ?? 'Stories, tips, and TikToks from the JR family.')

@php
    $channelUrl = fn (?string $value) => route('blog.index', array_filter([
        'channel' => $value && $value !== 'all' ? $value : null,
        'q' => $search !== '' ? $search : null,
    ]));
@endphp

@section('content')
<div class="hz-blog-page">
    @if($featured)
        <section class="hz-blog-spotlight">
            <div class="container">
                <div class="hz-blog-spotlight-grid">
                    <a href="{{ route('blog.show', $featured) }}" class="hz-blog-spotlight-media">
                        @if($featured->firstPostMediaUrl())
                            @if($featured->firstPostMediaIsVideo())
                                <video muted playsinline preload="metadata" src="{{ $featured->firstPostMediaUrl() }}"></video>
                            @else
                                <img src="{{ $featured->firstPostMediaUrl() }}" alt="{{ $featured->name }}">
                            @endif
                        @else
                            <div class="hz-blog-spotlight-fallback"></div>
                        @endif
                        <span class="hz-blog-spotlight-shade"></span>
                        @if($featured->isSocialPost())
                            <span class="hz-blog-play hz-blog-play-lg" aria-hidden="true"><i class="bi bi-play-fill"></i></span>
                            <span class="hz-blog-media-badge">
                                <i class="bi {{ $featured->socialIconClass() }}"></i>
                                {{ $featured->socialPlatform() }}
                            </span>
                        @endif
                    </a>
                    <div class="hz-blog-spotlight-copy">
                        <p class="hz-eyebrow">JR Journal</p>
                        <p class="hz-blog-spotlight-kicker">
                            {{ $featured->isSocialPost() ? 'Watch now' : 'Featured story' }}
                            @if($featured->postedOn())
                                <span>· {{ $featured->postedOn() }}</span>
                            @endif
                        </p>
                        <h1>{{ $featured->name }}</h1>
                        @if($featured->description)
                            <p class="hz-blog-spotlight-lead">{{ \Illuminate\Support\Str::limit(strip_tags((string) $featured->description), 220) }}</p>
                        @endif
                        <div class="hz-blog-spotlight-actions">
                            <a href="{{ route('blog.show', $featured) }}" class="btn-hz">
                                {{ $featured->isSocialPost() ? 'Open on '.($featured->socialPlatform() ?: 'social') : 'Read story' }}
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#hz-blog-feed" class="btn-hz-outline">Browse all</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="hz-page-hero">
            <div class="container">
                <div class="eyebrow">JR Journal</div>
                <h1>Blog</h1>
                <p>Stories, tips, and TikToks from Ketema, Mobile, Real Estate, and Ruth’s Hair.</p>
            </div>
        </section>
    @endif

    <section class="hz-section hz-blog-feed" id="hz-blog-feed">
        <div class="container">
            <form action="{{ route('blog.index') }}" method="get" class="hz-blog-toolbar" role="search">
                @if($channel !== 'all')
                    <input type="hidden" name="channel" value="{{ $channel }}">
                @endif
                <label class="visually-hidden" for="blog-search">Search the journal</label>
                <div class="hz-blog-search">
                    <i class="bi bi-search"></i>
                    <input
                        id="blog-search"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Search stories, captions, brands..."
                        autocomplete="off"
                    >
                    <button type="submit">Search</button>
                </div>
                <div class="hz-blog-chips" role="tablist" aria-label="Blog channels">
                    <a href="{{ $channelUrl('all') }}" class="hz-blog-chip {{ $channel === 'all' ? 'is-active' : '' }}">
                        All <span>{{ $counts['all'] }}</span>
                    </a>
                    @if($counts['tiktok'] > 0)
                        <a href="{{ $channelUrl('tiktok') }}" class="hz-blog-chip {{ $channel === 'tiktok' ? 'is-active' : '' }}">
                            <i class="bi bi-tiktok"></i> TikTok <span>{{ $counts['tiktok'] }}</span>
                        </a>
                    @endif
                    @if($counts['tips'] > 0)
                        <a href="{{ $channelUrl('tips') }}" class="hz-blog-chip {{ $channel === 'tips' ? 'is-active' : '' }}">
                            Tips <span>{{ $counts['tips'] }}</span>
                        </a>
                    @endif
                </div>
            </form>

            @if($search !== '')
                <p class="hz-blog-result-note">
                    {{ $posts->count() }} {{ \Illuminate\Support\Str::plural('result', $posts->count()) }} for “{{ $search }}”
                    <a href="{{ $channelUrl($channel) }}">Clear</a>
                </p>
            @endif

            @if($grid->isNotEmpty())
                <div class="row g-4 hz-blog-grid">
                    @foreach($grid as $post)
                        <div class="col-md-6 col-lg-4">
                            <x-horizon.blog-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            @elseif($featured)
                <p class="hz-blog-empty-inline">That’s the latest in this channel — more stories coming soon.</p>
            @else
                <div class="hz-blog-empty">
                    <i class="bi bi-journal-richtext"></i>
                    <h2>Nothing here yet</h2>
                    <p>
                        @if($search !== '')
                            No stories matched that search. Try another word or browse all posts.
                        @else
                            Blog posts will appear here once published in admin (Website → Blog posts).
                        @endif
                    </p>
                    @if($search !== '' || $channel !== 'all')
                        <a href="{{ route('blog.index') }}" class="btn-hz-outline">View all stories</a>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
