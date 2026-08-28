@php
    $siteName = $data['siteName'] ?? config('app.name', 'Site');
    $tagline = $data['tagline'] ?? '';
    $navItems = $navItems ?? collect();
@endphp

<footer class="hz-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="hz-footer-brand">
                    <x-site-brand :name="$siteName" :logo="$data['logoUrl'] ?? null" :show-text="empty($data['logoUrl'] ?? null)" />
                </div>
                @if($tagline)
                    <p class="mb-3">{{ $tagline }}</p>
                @endif
                <div class="hz-social">
                    <x-social-media />
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white mb-3">Explore</h6>
                <ul class="list-unstyled d-grid gap-2">
                    @forelse($navItems as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @empty
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('services.index') }}">Services</a></li>
                        <li><a href="{{ route('portfolio.index') }}">Portfolio</a></li>
                    @endforelse
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white mb-3">Connect</h6>
                <ul class="list-unstyled d-grid gap-2">
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ url('/mgt') }}">Admin</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white mb-3">Contact</h6>
                @if(!empty($data['address'] ?? null))
                    <p class="mb-2">{{ $data['address'] }}</p>
                @endif
                @foreach(($data['email'] ?? []) as $email)
                    <p class="mb-1"><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                @endforeach
                @foreach(($data['phone'] ?? []) as $phone)
                    <p class="mb-1"><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></p>
                @endforeach
            </div>
        </div>

        <div class="hz-footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
            <div>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
            <div>Developed by <a href="https://tetercreatives.com" target="_blank" rel="noopener">Teter PLC</a></div>
        </div>
    </div>
</footer>
