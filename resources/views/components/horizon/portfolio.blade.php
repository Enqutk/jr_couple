@props([
    'projects' => collect(),
    'categories' => collect(),
    'showFilters' => false,
    'limit' => null,
    'showHeader' => true,
])

@php
    $items = $limit ? $projects->take($limit) : $projects;
@endphp

<section class="hz-section hz-portfolio" id="portfolio">
    <div class="container">
        @if($showHeader)
            <div class="row justify-content-between align-items-end mb-4 g-3">
                <div class="col-lg-7">
                    <p class="hz-eyebrow">Selected work</p>
                    <h2 class="hz-title mb-0">Our projects</h2>
                </div>
                @if(!$showFilters)
                    <div class="col-lg-auto">
                        <a href="{{ route('portfolio.index') }}" class="btn-hz-outline">View portfolio</a>
                    </div>
                @endif
            </div>
        @endif

        @if($showFilters && $categories->isNotEmpty())
            <div class="hz-filters" data-portfolio-filter>
                <button type="button" class="hz-filter-btn active" data-filter="all">All</button>
                @foreach($categories as $category)
                    <button type="button" class="hz-filter-btn" data-filter="{{ \Illuminate\Support\Str::slug($category) }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            @forelse($items as $project)
                @php
                    $image = $project->getFirstMediaUrl('image') ?: null;
                @endphp
                <div class="col-md-6 col-lg-4 hz-portfolio-item" data-category="{{ \Illuminate\Support\Str::slug($project->category ?: 'general') }}">
                    <article class="hz-project-card">
                        <a href="{{ route('portfolio.show', $project) }}" class="hz-project-card-media">
                            @if($image)
                                <img src="{{ $image }}" alt="{{ $project->name }}">
                            @endif
                            @if($project->category)
                                <span class="hz-project-card-tag">{{ $project->category }}</span>
                            @endif
                        </a>
                        <div class="hz-project-card-body">
                            <h3>
                                <a href="{{ route('portfolio.show', $project) }}">{{ $project->name }}</a>
                            </h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $project->description), 110) }}</p>
                            <a href="{{ route('portfolio.show', $project) }}" class="hz-link">
                                View project <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">Portfolio projects will appear here once added as Entities (type: Project) in admin.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
