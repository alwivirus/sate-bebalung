<?php

use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

Artisan::call('db:seed', ['--class' => 'MenuSeeder', '--force' => true]);

echo "MenuSeeder executed successfully: " . Artisan::output();
