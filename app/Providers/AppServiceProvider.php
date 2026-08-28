<?php

namespace App\Providers;

use App\MediaLibrary\ModelNamePathGenerator;
use App\Models\MenuItem;
use App\Models\MenuLocation;
use App\Observers\MenuCacheObserver;
use App\Services\HomeContentService;
use App\Services\NavigationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        app()->bind(PathGenerator::class, function () {
            return new ModelNamePathGenerator;
        });

        MenuItem::observe(MenuCacheObserver::class);
        MenuLocation::observe(MenuCacheObserver::class);

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('reviews', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        View::composer([
            'index',
            'about',
            'contact',
            'services.*',
            'portfolio.*',
            'store.*',
            'blog.*',
            'pages.*',
            'emails.contact',
            'layouts.*',
            'components.*',
        ], function ($view) {
            static $payload = null;

            if ($payload === null) {
                $payload = [
                    'data' => app(HomeContentService::class)->getHomeContent(),
                    'navItems' => app(NavigationService::class)->navbarItems(),
                ];
            }

            $view->with($payload);
        });
    }
}
