<!-- Header Main Area -->
<header class="site-header pbmit-header-style-2" id="masthead">
    <div class="pbmit-sticky-header pbmit-header-sticky-yes pbmit-bg-color-white pbmit-sticky-header-mobile-yes"></div>
    <div class="pbmit-header-overlay">
        <div class="pbmit-main-header-area">
            <div class="pbmit-header-content-area">
                <div class="container-fluid">
                    <div class="pbmit-header-content d-flex align-items-center justify-content-between pbmit-header-wrapper pbmit-bg-color-blackish">
                        <!-- Header Menu Area -->
                        <div class="pbmit-header-menu-area d-flex w-100 justify-content-between align-items-center">
                            <!-- Logo Left -->
                            <div class="pbmit-logo-area">
                                <div class="site-branding">
                                    <h1 class="site-title">
                                        <a href="{{ route('home') }}">
                                            <img class="pbmit-main-logo"
                                                src="{{ asset('assets/images/logo/logo-04.png') }}" alt="Induyst">
                                            <img class="pbmit-sticky-logo"
                                                src="{{ asset('assets/images/logo/logo-02.png') }}" alt="Induyst">
                                        </a>
                                    </h1>
                                </div>
                            </div>
                            <!-- Mobile Menu Toggle -->
                            <button class="navbar-toggler d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#pbmit-top-menu" aria-controls="pbmit-top-menu" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <!-- Nav Right -->
                            <div class="pbmit-menuarea d-flex justify-content-end">
                                <div class="site-navigation">
                                    <nav class="main-navigation pbmit-navbar main-menu navbar-expand-xl navbar-light" id="site-navigation">
                                        <div class="collapse navbar-collapse" id="pbmit-top-menu">
                                            <ul class="navigation navbar-nav">
                                                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                                                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                                                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
                                                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <!-- End Header Menu Area -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-home-section />
</header>
<!-- Header Main Area End Here -->
