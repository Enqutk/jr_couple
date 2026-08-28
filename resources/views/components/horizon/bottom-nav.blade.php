@php
    $hideOnProduct = request()->routeIs('store.show');

    $activeTab = match (true) {
        request()->routeIs('store.*') => 'store',
        request()->routeIs('services.*') => 'services',
        request()->routeIs('blog.*') => 'blog',
        request()->routeIs('contact') => 'contact',
        request()->routeIs('home') => 'home',
        default => null,
    };

    $tabs = [
        ['id' => 'home', 'label' => 'Home', 'icon' => 'bi-house-door', 'url' => route('home')],
        ['id' => 'store', 'label' => 'Store', 'icon' => 'bi-bag', 'url' => route('store.index')],
        ['id' => 'services', 'label' => 'Services', 'icon' => 'bi-grid', 'url' => route('services.index')],
        ['id' => 'blog', 'label' => 'Blog', 'icon' => 'bi-journal-text', 'url' => route('blog.index')],
        ['id' => 'contact', 'label' => 'Contact', 'icon' => 'bi-chat-dots', 'url' => route('contact')],
    ];
@endphp

@if(! $hideOnProduct)
    <nav class="hz-bottom-nav d-lg-none" aria-label="Mobile navigation">
        <div class="hz-bottom-nav-inner">
            @foreach($tabs as $tab)
                <a
                    href="{{ $tab['url'] }}"
                    class="hz-bottom-nav-item {{ $activeTab === $tab['id'] ? 'is-active' : '' }}"
                    @if($activeTab === $tab['id']) aria-current="page" @endif
                >
                    <i class="bi {{ $tab['icon'] }}"></i>
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
@endif
