<div class="pbmit-slider-area pbmit-slider-two">
    <div class="swiper-slider" data-autoplay="true" data-loop="true" data-dots="false" data-arrows="true" data-columns="1"
        data-margin="0" data-effect="fade">
        <div class="swiper-wrapper">
            @foreach ($heroes as $hero)
                <div class="swiper-slide shadow-black" id="hero-slide-{{ $hero->id }}">
                    <div class="pbmit-slider-item ">
                        <div class="pbmit-slider-bg" style="background-image: url('{{ $hero->image_url }}');"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 col-1 mx-auto">
                                    <div class="pbmit-slider-content ">
                                        <h2 class="pbmit-slider-title transform-left transform-delay-2 text-center">
                                            <span class="first">{{ $hero->title }}</span>
                                        </h2>
                                        <h5
                                            class="pbmit-slider-subtitle transform-right-1 transform-delay-1 text-center">
                                            <span class="second ">{{ $hero->subtitle }}</span>
                                        </h5>
                                    </div> <!-- pbmit-slider-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
