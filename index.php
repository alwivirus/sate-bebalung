<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto create storage subdirectories if missing on hosting
$storageDirs = [
    __DIR__.'/storage/app',
    __DIR__.'/storage/framework',
    __DIR__.'/storage/framework/cache',
    __DIR__.'/storage/framework/cache/data',
    __DIR__.'/storage/framework/sessions',
    __DIR__.'/storage/framework/views',
    __DIR__.'/storage/logs',
    __DIR__.'/bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

// Auto symlink images/uploads to root if missing
if (!file_exists(__DIR__.'/images') && is_dir(__DIR__.'/public/images')) {
    @symlink(__DIR__.'/public/images', __DIR__.'/images');
}
if (!file_exists(__DIR__.'/uploads') && is_dir(__DIR__.'/public/uploads')) {
    @symlink(__DIR__.'/public/uploads', __DIR__.'/uploads');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
