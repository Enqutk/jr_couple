@extends('layouts.inner')

@section('title', $entity->name)
@section('eyebrow', $entity->category ?: 'Blog')
@section('page_title', $entity->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $entity->description), 160))

@section('page')
<section class="hz-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php $items = $entity->getMedia('post_media'); @endphp
                @if($items->isNotEmpty())
                    <div class="hz-blog-hero mb-4">
                        @foreach($items as $item)
                            @if(str_starts_with((string) $item->mime_type, 'video/'))
                                <video class="hz-blog-hero-media" controls playsinline preload="metadata">
                                    <source src="{{ $item->getUrl() }}" type="{{ $item->mime_type }}">
                                </video>
                            @else
                                <img class="hz-blog-hero-media" src="{{ $item->getUrl() }}" alt="{{ $entity->name }}">
                            @endif
                        @endforeach
                    </div>
                @elseif($entity->getFirstMediaUrl('image'))
                    <div class="hz-blog-hero mb-4">
                        <img class="hz-blog-hero-media" src="{{ $entity->getFirstMediaUrl('image') }}" alt="{{ $entity->name }}">
                    </div>
                @endif
                @if($entity->description)
                    <div class="hz-lead mb-4">{!! nl2br(e($entity->description)) !!}</div>
                @endif
                <a href="{{ route('blog.index') }}" class="hz-link"><i class="bi bi-arrow-left"></i> Back to blog</a>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="mt-5 pt-4 border-top border-hz">
                <h3 class="h4 mb-4">More from the blog</h3>
                <div class="row g-4">
                    @foreach($related as $post)
                        <div class="col-md-4">
                            <x-horizon.blog-card :post="$post" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
