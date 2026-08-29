<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreReviewController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/deploy-health', function () {
    $checks = [];
    $ok = true;

    if (config('app.key')) {
        $checks['app_key'] = 'ok';
    } else {
        $checks['app_key'] = 'missing — run: php artisan key:generate';
        $ok = false;
    }

    try {
        DB::connection()->getPdo();
        $checks['database'] = 'connected';
    } catch (\Throwable $e) {
        $checks['database'] = 'failed — check DB_* in .env ('.$e->getMessage().')';
        $ok = false;
    }

    if ($ok) {
        $requiredTables = ['organizations', 'entities', 'heroes', 'services', 'migrations'];
        $missing = array_values(array_filter(
            $requiredTables,
            fn (string $table): bool => ! Schema::hasTable($table),
        ));

        if ($missing === []) {
            $checks['migrations'] = 'ok';
        } else {
            $checks['migrations'] = 'missing tables: '.implode(', ', $missing).' — run: php artisan migrate --force';
            $ok = false;
        }
    } else {
        $checks['migrations'] = 'skipped (database unavailable)';
    }

    $checks['storage_writable'] = is_writable(storage_path('logs'))
        ? 'ok'
        : 'not writable — run: chmod -R 775 storage bootstrap/cache';

    return response()->json(['ok' => $ok, 'checks' => $checks], $ok ? 200 : 503);
})->name('deploy.health');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/{entity}', [StoreController::class, 'show'])->name('store.show');
Route::post('/store/{entity}/reviews', [StoreReviewController::class, 'store'])
    ->middleware('throttle:reviews')
    ->name('store.reviews.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{entity}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/our-services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{entity}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send/{recipient}', [ContactController::class, 'send'])
    ->middleware('throttle:contact')
    ->name('contact.send');

Route::get('/pages/{slug}', [PageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('pages.show');
