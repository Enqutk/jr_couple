<?php

namespace App\Observers;

use App\Models\MenuItem;
use App\Models\MenuLocation;
use App\Services\NavigationService;

class MenuCacheObserver
{
    public function saved(MenuItem|MenuLocation $model): void
    {
        app(NavigationService::class)->clearCache();
    }

    public function deleted(MenuItem|MenuLocation $model): void
    {
        app(NavigationService::class)->clearCache();
    }

    public function restored(MenuItem|MenuLocation $model): void
    {
        app(NavigationService::class)->clearCache();
    }
}
