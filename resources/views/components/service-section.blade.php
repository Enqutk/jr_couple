<section class="section-xxl fade-section">
    <div class="container">
        <div class="d-md-flex justify-content-between">
            <div class="pbmit-heading-subheading">
             
                <h2 class="pbmit-title">Our Services </h2>
            </div>
            <div class="service-arrow d-inline-flex"></div>
        </div>

        @if(isset($services) && $services && $services->count() > 0)
            <div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="false" data-arrows="true"
                data-arrows-class="service-arrow" data-columns="2" data-margin="30" data-effect="slide">
                <div class="swiper-wrapper">
                    @foreach ($services as $service)
                        <!-- Slide {{ $loop->iteration }} -->
                        <article class="pbmit-service-style-1 swiper-slide">
                            <div class="pbminfotech-post-item">
                                <div class="pbmit-box-content-wrap">
                                    <div class="pbmit-image-wrap">
                                        <div class="pbmit-featured-img-wrapper">
                                            <div class="pbmit-featured-wrapper">
                                                <img src="{{ $service->main_image_url }}" class="img-fluid"
                                                    alt="{{ $service->title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pbmit-service-content-wrap">
                                        <h3 class="pbmit-service-title">
                                            {{ $service->title }}
                                        </h3>
                                        <div class="pbmit-service-description">
                                            <p>{{ $service->short_description }}</p>
                                        </div>
                                        <div class="pbmit-service-btn-wrapper">
                                            <div class="pbmit-service-btn">
                                                <a class="pbmit-button-inner"
                                                    href="{{ route('services.show', $service->slug) }}">
                                                    <span class="pbmit-button-text">Read More</span>
                                                    <i class="pbmit-base-icon-right-arrow"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="pbmit-heading-subheading">
                    <h4 class="pbmit-subtitle">Coming Soon</h4>
                    <h3 class="pbmit-title">Our Services</h3>
                </div>
                <p class="lead">We're currently preparing our service offerings. Please check back soon!</p>
            </div>
        @endif
    </div>
</section>
<!-- Service end -->
