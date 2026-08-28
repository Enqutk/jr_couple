@php
    $navItems = $navItems ?? collect();
    $siteName = $data['siteName'] ?? config('app.name', 'Site');
    $navIcons = [
        'Home' => 'bi-house-door',
        'Store' => 'bi-bag',
        'Blog' => 'bi-journal-text',
        'Services' => 'bi-grid',
        'Contact' => 'bi-envelope',
    ];
@endphp

<header class="hz-header" data-hz-header>
    <nav class="navbar navbar-expand-lg hz-navbar" aria-label="Primary">
        <div class="container hz-navbar-inner">
            <a class="navbar-brand hz-brand" href="{{ route('home') }}">
                <x-site-brand :name="$siteName" :logo="$data['logoUrl'] ?? null" :show-text="empty($data['logoUrl'] ?? null)" />
            </a>

            <button
                class="hz-toggler d-lg-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#hzMainNav"
                aria-controls="hzMainNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="collapse navbar-collapse hz-nav-collapse" id="hzMainNav">
                <div class="hz-mobile-menu-backdrop d-lg-none" data-hz-menu-close aria-hidden="true"></div>

                <div class="hz-mobile-menu-panel">
                    <div class="hz-mobile-menu-head d-lg-none">
                        <div>
                            <p class="hz-mobile-menu-eyebrow">Navigation</p>
                            <p class="hz-mobile-menu-title">Explore JR Couple</p>
                        </div>
                        <button
                            type="button"
                            class="hz-mobile-menu-close"
                            data-bs-toggle="collapse"
                            data-bs-target="#hzMainNav"
                            aria-label="Close menu"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="hz-mobile-menu-body">
                        <ul class="navbar-nav ms-auto align-items-lg-center hz-nav">
                            @foreach ($navItems as $link)
                                @php $icon = $navIcons[$link['label']] ?? 'bi-arrow-right'; @endphp
                                @if(!empty($link['children']))
                                    <li class="nav-item dropdown hz-mobile-dropdown">
                                        <a
                                            class="nav-link dropdown-toggle {{ !empty($link['active']) || collect($link['children'])->contains(fn ($c) => !empty($c['active'])) ? 'active' : '' }}"
                                            href="{{ $link['url'] }}"
                                            id="nav-{{ \Illuminate\Support\Str::slug($link['label']) }}"
                                            role="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >
                                            <span class="hz-mobile-nav-link">
                                                <i class="bi {{ $icon }}"></i>
                                                <span>{{ $link['label'] }}</span>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu hz-dropdown" aria-labelledby="nav-{{ \Illuminate\Support\Str::slug($link['label']) }}">
                                            <li>
                                                <a class="dropdown-item {{ !empty($link['active']) ? 'active' : '' }}" href="{{ $link['url'] }}">
                                                    Overview
                                                </a>
                                            </li>
                                            @foreach ($link['children'] as $child)
                                                <li>
                                                    <a
                                                        class="dropdown-item {{ !empty($child['active']) ? 'active' : '' }}"
                                                        href="{{ $child['url'] }}"
                                                        target="{{ $child['target'] ?? '_self' }}"
                                                    >
                                                        {{ $child['label'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a
                                            class="nav-link {{ !empty($link['active']) ? 'active' : '' }}"
                                            href="{{ $link['url'] }}"
                                            target="{{ $link['target'] ?? '_self' }}"
                                            @if(!empty($link['active'])) aria-current="page" @endif
                                        >
                                            <span class="hz-mobile-nav-link">
                                                <i class="bi {{ $icon }}"></i>
                                                <span>{{ $link['label'] }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                            <li class="nav-item hz-nav-cta">
                                <a class="btn-hz btn-hz-sm" href="{{ route('contact') }}">
                                    Get in touch <i class="bi bi-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="hz-mobile-menu-foot d-lg-none">
                        <p>Artificial grass, mobiles, real estate &amp; hair — one trusted family of brands.</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
