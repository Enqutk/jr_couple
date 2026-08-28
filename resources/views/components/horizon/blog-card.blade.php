@props([
    'post',
    'featured' => false,
])

@php
    $mediaUrl = $post->firstPostMediaUrl();
    $isVideo = $post->firstPostMediaIsVideo();
    $isSocial = $post->isSocialPost();
    $platform = $isSocial ? $post->socialPlatform() : null;
    $href = route('blog.show', $post);
    $cta = $isSocial
        ? 'Open on '.($platform ?: 'social')
        : 'Read story';
@endphp

<article @class([
    'hz-blog-card',
    'is-featured' => $featured,
    'is-social' => $isSocial,
])>
    @if($mediaUrl)
        <a href="{{ $href }}" class="hz-blog-card-media{{ $isSocial ? ' is-tiktok-cover' : '' }}" @if($isSocial) rel="noopener" @endif>
            @if($isVideo)
                <video muted playsinline preload="metadata" src="{{ $mediaUrl }}"></video>
            @else
                <img src="{{ $mediaUrl }}" alt="{{ $post->name }}">
            @endif
            <span class="hz-blog-card-shade"></span>
            @if($isSocial)
                <span class="hz-blog-play" aria-hidden="true"><i class="bi bi-play-fill"></i></span>
                <span class="hz-blog-media-badge"><i class="bi {{ $post->socialIconClass() }}"></i> {{ $platform }}</span>
            @endif
        </a>
    @elseif($isSocial)
        <a href="{{ $href }}" class="hz-blog-card-media hz-blog-card-media-social" rel="noopener">
            <span class="hz-blog-play" aria-hidden="true"><i class="bi bi-play-fill"></i></span>
            <i class="bi {{ $post->socialIconClass() }}"></i>
            <span>{{ $platform ?: 'Social post' }}</span>
        </a>
    @endif
    <div class="hz-blog-card-body">
        <div class="hz-blog-card-meta">
            @if($post->category || $isSocial)
                <p class="hz-eyebrow mb-0">{{ $post->category ?: $platform }}</p>
            @endif
            @if($post->postedOn())
                <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->postedOn() }}</time>
            @endif
        </div>
        <h3 class="{{ $featured ? 'hz-blog-card-title-lg' : 'h5' }}">
            <a href="{{ $href }}">{{ $post->name }}</a>
        </h3>
        @if($post->description)
            <p class="{{ $isSocial ? 'hz-blog-caption' : '' }}">{{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), $featured ? 220 : 140) }}</p>
        @endif
        <a href="{{ $href }}" class="hz-link">{{ $cta }} <i class="bi bi-arrow-right"></i></a>
    </div>
</article>
