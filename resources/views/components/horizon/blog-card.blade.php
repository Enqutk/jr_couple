@props(['post'])

@php
    $mediaUrl = $post->firstPostMediaUrl();
    $isVideo = $post->firstPostMediaIsVideo();
    $isSocial = $post->isSocialPost();
    $platform = $isSocial ? $post->socialPlatform() : null;
    $href = route('blog.show', $post);
    $cta = $isSocial
        ? 'Open on '.($platform ?: 'social')
        : 'Read more';
@endphp

<article class="hz-blog-card">
    @if($mediaUrl)
        <a href="{{ $href }}" class="hz-blog-card-media" @if($isSocial) rel="noopener" @endif>
            @if($isVideo)
                <video muted playsinline preload="metadata" src="{{ $mediaUrl }}"></video>
            @else
                <img src="{{ $mediaUrl }}" alt="{{ $post->name }}">
            @endif
            @if($isSocial)
                <span class="hz-blog-media-badge"><i class="bi {{ $post->socialIconClass() }}"></i> {{ $platform }}</span>
            @endif
        </a>
    @elseif($isSocial)
        <a href="{{ $href }}" class="hz-blog-card-media hz-blog-card-media-social" rel="noopener">
            <i class="bi {{ $post->socialIconClass() }}"></i>
            <span>{{ $platform ?: 'Social post' }}</span>
        </a>
    @endif
    <div class="hz-blog-card-body">
        @if($post->category || $isSocial)
            <p class="hz-eyebrow mb-2">
                {{ $post->category ?: $platform }}
            </p>
        @endif
        <h3 class="h5">
            <a href="{{ $href }}">{{ $post->name }}</a>
        </h3>
        @if($post->description)
            <p class="{{ $isSocial ? 'hz-blog-caption' : '' }}">{{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), 140) }}</p>
        @endif
        <a href="{{ $href }}" class="hz-link">{{ $cta }} <i class="bi bi-arrow-right"></i></a>
    </div>
</article>
