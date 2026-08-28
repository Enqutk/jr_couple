<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') | {{ $data['siteName'] ?? config('app.name') }}</title>
    <meta name="description" content="@yield('description', $data['metaDescription'] ?? ($data['siteName'] ?? config('app.name')))">
    <meta name="robots" content="{{ config('app.env') === 'production' ? 'index, follow' : 'noindex, nofollow' }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $data['faviconUrl'] ?? $data['logoUrl'] ?? asset('images/fevicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $theme = $data['theme'] ?? \App\Models\Organization::defaultTheme();
        $theme['accent_soft'] = $theme['accent_soft']
            ?? \App\Models\Organization::hexToRgba($theme['accent'] ?? '#0f766e', 0.12);
    @endphp
    <style>
        :root {
            --hz-bg: {{ $theme['bg'] ?? '#f3f6f5' }};
            --hz-surface: {{ $theme['surface'] ?? '#ffffff' }};
            --hz-ink: {{ $theme['ink'] ?? '#10211f' }};
            --hz-muted: {{ $theme['muted'] ?? '#5a6b68' }};
            --hz-line: {{ $theme['line'] ?? '#d7e0dd' }};
            --hz-accent: {{ $theme['accent'] ?? '#0f766e' }};
            --hz-accent-dark: {{ $theme['accent_dark'] ?? '#0b5f58' }};
            --hz-accent-soft: {{ $theme['accent_soft'] }};
            --hz-dark: {{ $theme['dark'] ?? '#0a1615' }};
        }
    </style>
    @stack('styles')
</head>
<body class="@if(! request()->routeIs('store.show')) hz-has-bottom-nav @endif">
    @include('layouts.horizon.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.horizon.footer')

    <x-horizon.bottom-nav />

    @stack('scripts')
</body>
</html>
