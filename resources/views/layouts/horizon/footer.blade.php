@php
    $siteName = $data['siteName'] ?? config('app.name', 'Site');
    $tagline = $data['tagline'] ?? '';
    $navItems = $navItems ?? collect();
    $emails = $data['email'] ?? [];
    $phones = $data['phone'] ?? [];
    $hours = $data['hours'] ?? [];
    $address = $data['address'] ?? null;
    $payment = $data['payment'] ?? [];
    $primaryPhone = $phones[0] ?? null;
    $phoneDigits = $primaryPhone ? preg_replace('/\D+/', '', $primaryPhone) : null;
    $mapQuery = $address ? 'https://maps.google.com/?q='.rawurlencode($address) : null;
    $accepted = array_values(array_filter([
        ! empty($payment['telebirr_number']) ? 'Telebirr' : null,
        ! empty($payment['bank_name']) ? 'Bank transfer' : null,
    ]));
    if ($accepted) {
        $accepted[] = 'In store';
    }
@endphp

<footer class="hz-footer">
    @unless(request()->routeIs('contact'))
        <div class="hz-footer-cta">
            <div class="container">
                <div class="hz-footer-cta-inner">
                    <div>
                        <p class="hz-footer-kicker">JR family</p>
                        <h2 class="hz-footer-cta-title">Visit JR or send a message</h2>
                        @if($tagline)
                            <p class="hz-footer-cta-copy">{{ $tagline }}</p>
                        @endif
                    </div>
                    <a class="btn-hz" href="{{ route('contact') }}">
                        Get in touch <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endunless

    <div class="hz-footer-main">
        <div class="container">
            <div class="row gy-5 gx-lg-5">
                <div class="col-12 col-lg-4">
                    <div class="hz-footer-brand">
                        <x-site-brand :name="$siteName" :logo="$data['logoUrl'] ?? null" :show-text="empty($data['logoUrl'] ?? null)" />
                    </div>
                    @if($tagline)
                        <p class="hz-footer-tagline">{{ $tagline }}</p>
                    @endif
                    <div class="hz-social">
                        <x-social-media />
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="hz-footer-heading">Explore</h6>
                    <ul class="hz-footer-links">
                        @forelse($navItems as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @empty
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('store.index') }}">Store</a></li>
                            <li><a href="{{ route('services.index') }}">Services</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        @endforelse
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="hz-footer-heading">Visit</h6>
                    <ul class="hz-footer-meta">
                        @if($address)
                            <li>
                                <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                <div>
                                    <span>Studio</span>
                                    @if($mapQuery)
                                        <a href="{{ $mapQuery }}" target="_blank" rel="noopener noreferrer">{{ $address }}</a>
                                    @else
                                        <p>{{ $address }}</p>
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if($hours)
                            <li>
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <div>
                                    <span>Hours</span>
                                    @foreach($hours as $slot)
                                        <p>{{ $slot['label'] }} · {{ $slot['from'] }} – {{ $slot['to'] }}</p>
                                    @endforeach
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-12 col-lg-3">
                    <h6 class="hz-footer-heading">Contact</h6>
                    <ul class="hz-footer-meta">
                        @foreach($emails as $email)
                            <li>
                                <i class="bi bi-envelope" aria-hidden="true"></i>
                                <div>
                                    <span>Email</span>
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                                </div>
                            </li>
                        @endforeach
                        @foreach($phones as $index => $phone)
                            <li>
                                <i class="bi {{ $index === 0 ? 'bi-telephone' : 'bi-phone' }}" aria-hidden="true"></i>
                                <div>
                                    <span>{{ $index === 0 ? 'Phone' : 'Mobile' }}</span>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if($phoneDigits)
                        <a class="hz-footer-whatsapp" href="https://wa.me/{{ ltrim($phoneDigits, '+') }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-whatsapp"></i> WhatsApp JR
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="hz-footer-bottom">
        <div class="container">
            <div class="hz-footer-bottom-inner">
                <div>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</div>
                @if($accepted)
                    <div class="hz-footer-accepts">{{ implode(' · ', $accepted) }}</div>
                @endif
            </div>
        </div>
    </div>
</footer>
