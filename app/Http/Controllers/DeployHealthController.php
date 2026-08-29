<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeployHealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [];
        $ok = true;

        if (config('app.key')) {
            $checks['app_key'] = 'ok';
        } else {
            $checks['app_key'] = 'missing — run: php artisan key:generate';
            $ok = false;
        }

        $checks['session_driver'] = config('session.driver');
        $checks['cache_store'] = config('cache.default');

        if (in_array(config('session.driver'), ['database', 'redis'], true)) {
            $checks['session_driver'] .= ' — use SESSION_DRIVER=file on cPanel unless migrated';
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
    }
}
