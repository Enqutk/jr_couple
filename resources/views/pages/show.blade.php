@extends('layouts.inner')

@section('title', $page->title)
@section('eyebrow', 'Page')
@section('page_title', $page->title)
@section('description', $page->short_description ?: $page->title)

@section('page')
<section class="hz-section">
    <div class="container">
        @if($page->short_description)
            <p class="hz-lead mb-5" style="max-width: 40rem;">{{ $page->short_description }}</p>
        @endif

        @forelse($page->activeSections as $section)
            <div class="mb-5 pb-4 @if(!$loop->last) border-bottom border-hz @endif">
                @if($section->title)
                    <p class="hz-eyebrow">{{ $section->subtitle ?: 'Section' }}</p>
                    <h2 class="hz-title">{{ $section->title }}</h2>
                @endif

                @foreach($section->activeContentBlocks as $block)
                    <article class="mb-4">
                        @if($block->title)
                            <h3 class="h4 mb-2">{{ $block->title }}</h3>
                        @endif
                        @if($block->subtitle)
                            <p class="text-muted mb-2">{{ $block->subtitle }}</p>
                        @endif
                        @if($block->short_description)
                            <p class="hz-lead">{{ $block->short_description }}</p>
                        @endif
                        @if($block->content)
                            <div class="hz-prose">{!! \Purifier::clean($block->content) !!}</div>
                        @endif
                        @if($block->getFirstMediaUrl('images'))
                            <img
                                src="{{ $block->getFirstMediaUrl('images') }}"
                                alt="{{ $block->title ?: $page->title }}"
                                class="w-100 border border-hz mt-3"
                                style="max-height: 420px; object-fit: cover;"
                            >
                        @endif
                        @if(is_array($block->list_items) && count($block->list_items))
                            <div class="row g-3 mt-2">
                                @foreach($block->list_items as $item)
                                    <div class="col-md-6">
                                        <div class="hz-about-feature">
                                            <div class="hz-card-icon flex-shrink-0">
                                                <i class="{{ $item['icon'] ?? 'bi bi-check-lg' }}"></i>
                                            </div>
                                            <div>
                                                <h4 class="h6 mb-1">{{ $item['title'] ?? ($item['label'] ?? '') }}</h4>
                                                <p class="small mb-0 text-muted">{{ $item['description'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @empty
            <p class="text-muted">This page has no published content yet.</p>
        @endforelse
    </div>
</section>
@endsection
