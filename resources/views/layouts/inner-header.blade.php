<!-- Header Main Area -->
<header class="site-header pbmit-header-style-1" id="masthead">
    <div class="pbmit-sticky-header pbmit-header-sticky-yes pbmit-bg-color-white pbmit-sticky-header-mobile-yes"></div>
    <div class="pbmit-header-overlay">
        <div class="pbmit-main-header-area pbmit-infostack-header pbmit-bg-color-blackish">
            <div class="pbmit-top-area">
                <div class="container-fluid">
                    <div class="pbmit-logo-area-inner d-flex align-items-center justify-content-between">
                        <div class="site-branding">
                            <h1 class="site-title">
                                <a href="{{ route('home') }}">
                                    <img class="pbmit-main-logo" src="{{ asset('assets/images/logo/logo-04.png') }}"
                                        alt="Induyst">
                                    <img class="pbmit-sticky-logo" src="{{ asset('assets/images/logo/logo-02.png') }}"
                                        alt="Induyst">
                                </a>
                            </h1>
                        </div>


                        <div class="pbmit-right-box d-flex align-items-center">
                            <div class="pbmit-header-search-btn">
                                <a href="#" title="Search">
                                    <i class="pbmit-base-icon-search-2"></i>
                                </a>
                            </div>
                            <div class="pbmit-burger-menu-wrapper">
                                <div class="pbmit-mobile-menu-bg"></div>
                                <button id="menu-toggle" class="nav-menu-toggle">
                                    <i class="pbmit-base-icon-menu-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="pbmit-main-header-area" style="max-width: 900px; margin: 0 0 0 700px;">
                        <div class="container-fluid" style="max-width: 700px;">
                            <div
                                class="pbmit-header-content d-flex align-items-center justify-content-center pbmit-bg-color-white">
                                <div class="pbmit-menuarea d-flex align-items-center justify-content-center"
                                    style="width: 100%;">
                                    <div class="site-navigation" style="width: 100%;">
                                        <nav class="main-navigation pbmit-navbar main-menu navbar-expand-xl navbar-light"
                                            id="site-navigation">
                                            <div style="display: flex; justify-content: center; width: 100%;">
                                                <ul class="navigation clearfix main-navigation-list" id="pbmit-top-menu">
                                                   
                                                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                                        <a href="{{ route('home') }}">Home</a>
                                                    </li>
                                                    <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                                                        <a href="{{ route('about') }}">About Us</a>
                                                    </li>
                                                    <li
                                                        class="{{ request()->routeIs('services.index') ? 'active' : '' }}">
                                                        <a href="{{ route('services.index') }}">Service</a>
                                                    </li>
                                                    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                                        <a href="{{ route('contact') }}">Contact Us</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</header>
<!-- Header Main Area End Here -->
