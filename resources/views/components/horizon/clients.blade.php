@props(['clients' => collect()])

@if($clients->isNotEmpty())
<section class="hz-section hz-clients bg-surface border-top border-bottom border-hz">
    <div class="container">
        <div class="row justify-content-between align-items-end mb-4 g-3">
            <div class="col-lg-8">
                <p class="hz-eyebrow">Trusted by</p>
                <h2 class="hz-title mb-0">Clients & partners</h2>
            </div>
        </div>

        <div class="row g-3">
            @foreach($clients as $client)
                @php
                    $logo = $client->getFirstMediaUrl('image');
                    $tag = $client->type?->value === 'partner' ? 'Partner' : 'Client';
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    @if($client->link)
                        <a href="{{ $client->link }}" target="_blank" rel="noopener" class="hz-client">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $client->name }}">
                            @else
                                <span class="hz-client-name">{{ $client->name }}</span>
                            @endif
                            <span class="hz-client-tag">{{ $tag }}</span>
                        </a>
                    @else
                        <div class="hz-client">
                            @if($logo)
                                <img src="{{ $logo }}" alt="{{ $client->name }}">
                            @else
                                <span class="hz-client-name">{{ $client->name }}</span>
                            @endif
                            <span class="hz-client-tag">{{ $tag }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
