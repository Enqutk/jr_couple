@props([
    'as' => 'span',
    'name' => null,
    'logo' => null,
    'showText' => true,
])

@php
    $brand = trim((string) ($name ?? ($data['siteName'] ?? config('app.name', 'Site'))));
    $logoUrl = $logo ?? ($data['logoUrl'] ?? null);
    $parts = preg_split('/\s+/', $brand, 2) ?: [$brand];
    $first = $parts[0] ?? $brand;
    $rest = $parts[1] ?? null;
@endphp

<{{ $as }} {{ $attributes->class(['hz-brand-mark' => (bool) $logoUrl]) }}>
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $brand }}" class="hz-brand-logo">
    @endif
    @if($showText || ! $logoUrl)
        <span class="hz-brand-text">
            {{ $first }}@if($rest) <span>{{ $rest }}</span>@endif
        </span>
    @endif
</{{ $as }}>
