@php
use Illuminate\Support\Str;
@endphp
<section class="video-section-two pbmit-bg-color-blackish">
    <div class="container-fluid p-0">
        <div class="video-play-bg" style="background-image: url('{{ $videoThumbnail }}');">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 left-col mb-3 mb-md-0">
                    <div class="pbmit-custom-heading">
                        <h2 class="pbmit-title">{{ $thumbnailShortDescription }}</h2>
                    </div>
                </div>
                <div class="col-12 col-md-6 right-col">
                    <div class="text-md-end mt-md-0 mt-4">
                        <a href="{{ $videoUrl }}" class="venobox pbmit-video-play-btn" data-autoplay="true" data-vbtype="video">
                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-play" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="video-bottom-area">
            <div class="row">
                <div class="col-12 col-xl-3 mb-4 mb-xl-0">
                    <div class="pbmit-heading-subheading">
                        <h4 class="pbmit-subtitle">{{ $subtitle }}</h4>
                    </div>
                    <div class="chart-wrap">
                        <div id="chart" class="w-100"></div>
                        <p class="chart-sub-heading">{{ $chartDescription }}</p>
                    </div>
                </div>
                <div class="col-12 col-xl-9">
                    <div class="row">
                        <div class="col-12 col-xl-6 mb-4 mb-xl-0">
                            <div class="content-box h-100">
                                <div class="pbmit-heading-subheading">
                                    <h4 class="pbmit-subtitle">{{ $industriesTitle }}</h4>
                                    <div class="pbmit-heading-desc">{{ $industriesDescription }}</div>
                                </div>
                                <ul class="list-group">
                                    @foreach($industries as $industry)
                                    <li class="list-group-item">
                                        <span class="pbmit-icon-list-icon">
                                            <i class="pbmit-induyst-icon pbmit-induyst-icon-check"></i>
                                        </span>
                                        <span class="pbmit-icon-list-text">{{ $industry }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6">
                            <div class="pbmit-heading-subheading">
                                <h4 class="pbmit-subtitle">{{ $locationsTitle }}</h4>
                            </div>
                            <div class="map-img">
                                @php
                                $decodedMapUrl = html_entity_decode($mapUrl);
                                @endphp
                                @if(Str::contains($decodedMapUrl, '<iframe'))
                                    {!! preg_replace('/<iframe([^>]*)width="[^"]*"([^>]*)height="[^"]*"([^>]*)>/i', '<iframe$1width="100%"$2height="250"$3 style="max-width:100%;border:0;">', $decodedMapUrl) !!}
                                @else
                                    {{ $decodedMapUrl }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>