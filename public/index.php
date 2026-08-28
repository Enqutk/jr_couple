<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine the path to the Laravel application
$laravelPath = __DIR__ . '/../';
if (!file_exists($laravelPath . 'vendor/autoload.php')) {
    // Try alternative paths for different deployment scenarios
    $laravelPath = __DIR__ . '/../../';
    if (!file_exists($laravelPath . 'vendor/autoload.php')) {
        $laravelPath = __DIR__ . '/../../../';
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelPath . 'storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelPath . 'vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $laravelPath . 'bootstrap/app.php';

$app->handleRequest(Request::capture());
