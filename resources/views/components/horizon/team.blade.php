@props([
    'team' => collect(),
    'showBios' => false,
])

@if($team->isNotEmpty())
<section class="hz-section hz-team" id="team">
    <div class="container">
        <div class="row justify-content-between align-items-end mb-4 g-3">
            <div class="col-lg-7">
                <p class="hz-eyebrow">Our people</p>
                <h2 class="hz-title mb-0">The team behind the work</h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach($team as $member)
                @php
                    $photo = $member->getFirstMediaUrl('team-images');
                    $initials = collect(explode(' ', $member->full_name))
                        ->filter()
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->take(2)
                        ->implode('');
                @endphp
                <div class="col-6 col-lg-3">
                    <article class="hz-team-card">
                        <div class="hz-team-photo">
                            @if($photo)
                                <img src="{{ $photo }}" alt="{{ $member->full_name }}">
                            @else
                                <div class="hz-team-initials" aria-hidden="true">{{ $initials }}</div>
                            @endif
                            @if($member->founder)
                                <span class="hz-team-badge">Founder</span>
                            @endif
                        </div>
                        <h3 class="h5 mb-1">{{ $member->full_name }}</h3>
                        <div class="hz-team-role">{{ $member->title }}</div>
                        @if($showBios && $member->description)
                            <p class="hz-team-bio">{{ $member->description }}</p>
                        @endif
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
