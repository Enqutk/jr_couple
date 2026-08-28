@extends('layouts.app')

@section('title', $entity->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $entity->description), 160))

@php
    $shareUrl = url()->current();
    $shareText = $entity->name.' — JR Couple';
    $cover = $entity->firstPostMediaUrl();
@endphp

@section('content')
<article class="hz-blog-article">
    <header class="hz-blog-article-hero">
        @if($cover)
            <div class="hz-blog-article-cover">
                @if($entity->firstPostMediaIsVideo())
                    <video class="hz-blog-hero-media" controls playsinline preload="metadata" src="{{ $cover }}"></video>
                @else
                    <img src="{{ $cover }}" alt="{{ $entity->name }}">
                @endif
            </div>
        @endif
        <div class="container">
            <div class="hz-blog-article-intro">
                <a href="{{ route('blog.index') }}" class="hz-blog-back"><i class="bi bi-arrow-left"></i> JR Journal</a>
                <p class="hz-eyebrow">{{ $entity->category ?: 'Story' }}</p>
                <h1>{{ $entity->name }}</h1>
                <div class="hz-blog-article-meta">
                    <span>JR Couple</span>
                    @if($entity->postedOn())
                        <span>{{ $entity->postedOn() }}</span>
                    @endif
                    <span>{{ $entity->readingMinutes() }} min read</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php $gallery = $entity->getMedia('post_media'); @endphp
                @if($gallery->count() > 1)
                    <div class="hz-blog-hero mb-4">
                        @foreach($gallery->skip($cover ? 1 : 0) as $item)
                            @if(str_starts_with((string) $item->mime_type, 'video/'))
                                <video class="hz-blog-hero-media" controls playsinline preload="metadata">
                                    <source src="{{ $item->getUrl() }}" type="{{ $item->mime_type }}">
                                </video>
                            @else
                                <img class="hz-blog-hero-media" src="{{ $item->getUrl() }}" alt="{{ $entity->name }}">
                            @endif
                        @endforeach
                    </div>
                @endif

                @if($entity->description)
                    <div class="hz-blog-article-body">{!! nl2br(e($entity->description)) !!}</div>
                @endif

                <div class="hz-blog-share">
                    <span>Share this story</span>
                    <a
                        href="https://wa.me/?text={{ urlencode($shareText.' '.$shareUrl) }}"
                        target="_blank"
                        rel="noopener"
                        aria-label="Share on WhatsApp"
                    >
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a
                        href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareText) }}"
                        target="_blank"
                        rel="noopener"
                        aria-label="Share on Telegram"
                    >
                        <i class="bi bi-telegram"></i>
                    </a>
                    <button type="button" data-copy-text="{{ $shareUrl }}" aria-label="Copy link">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                </div>

                <a href="{{ route('blog.index') }}" class="hz-link"><i class="bi bi-arrow-left"></i> Back to blog</a>
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="hz-blog-related">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <p class="hz-eyebrow mb-1">Keep scrolling</p>
                        <h2 class="h3 mb-0">More from the JR team</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="hz-link d-none d-md-inline-flex">All stories <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="row g-4">
                    @foreach($related as $post)
                        <div class="col-md-4">
                            <x-horizon.blog-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</article>
@endsection
