@props([
    'rating' => 0,
    'count' => null,
    'size' => 'sm',
])

@php
    $value = max(0, min(5, (float) $rating));
    $full = (int) floor($value);
    $half = ($value - $full) >= 0.5;
@endphp

<div class="hz-stars hz-stars-{{ $size }}" aria-label="{{ number_format($value, 1) }} out of 5 stars">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $full)
            <i class="bi bi-star-fill"></i>
        @elseif($half && $i === $full + 1)
            <i class="bi bi-star-half"></i>
        @else
            <i class="bi bi-star"></i>
        @endif
    @endfor
    @if($count !== null)
        <span class="hz-stars-count">({{ $count }})</span>
    @endif
</div>
