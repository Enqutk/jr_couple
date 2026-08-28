@extends('layouts.inner')

@section('title', $entity->name)
@section('eyebrow', $entity->category ?: 'Project')
@section('page_title', $entity->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $entity->description), 160))

@php
    $image = $entity->getFirstMediaUrl('image') ?: null;
@endphp

@section('page')
<section class="hz-section hz-project-detail">
    <div class="container">
        <div class="row g-4 g-xl-5">
            <div class="col-lg-7">
                @if($image)
                    <div class="hz-project-detail-media">
                        <img src="{{ $image }}" alt="{{ $entity->name }}">
                    </div>
                @endif
            </div>
            <div class="col-lg-5">
                @if($entity->category)
                    <p class="hz-eyebrow">{{ $entity->category }}</p>
                @endif
                <h2 class="hz-title">{{ $entity->name }}</h2>
                <div class="hz-lead mb-4">{!! nl2br(e($entity->description)) !!}</div>

                <div class="d-flex flex-wrap gap-3">
                    @if($entity->link)
                        <a href="{{ $entity->link }}" class="btn-hz" target="_blank" rel="noopener">
                            Visit link <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    @endif
                    <a href="{{ route('contact') }}" class="btn-hz-outline">Discuss a similar project</a>
                </div>

                <div class="mt-4">
                    <a href="{{ route('portfolio.index') }}" class="hz-link">
                        <i class="bi bi-arrow-left"></i> Back to portfolio
                    </a>
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="mt-5 pt-4 border-top border-hz">
                <div class="row justify-content-between align-items-end mb-4">
                    <div class="col">
                        <p class="hz-eyebrow mb-1">Continue exploring</p>
                        <h3 class="h4 mb-0">More projects</h3>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($related as $project)
                        @php
                            $relatedImage = $project->getFirstMediaUrl('image') ?: null;
                        @endphp
                        <div class="col-md-4">
                            <article class="hz-project-card">
                                <a href="{{ route('portfolio.show', $project) }}" class="hz-project-card-media">
                                    @if($relatedImage)
                                        <img src="{{ $relatedImage }}" alt="{{ $project->name }}">
                                    @endif
                                    @if($project->category)
                                        <span class="hz-project-card-tag">{{ $project->category }}</span>
                                    @endif
                                </a>
                                <div class="hz-project-card-body">
                                    <h3 class="h5">
                                        <a href="{{ route('portfolio.show', $project) }}">{{ $project->name }}</a>
                                    </h3>
                                    <a href="{{ route('portfolio.show', $project) }}" class="hz-link">View project</a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
