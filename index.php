<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Auto create storage directories
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
        @mkdir($dir, 0777, true);
    }
}

// 2. Auto sync database columns and admin account on boot (silent self-heal)
try {
    if (file_exists(__DIR__.'/.env')) {
        $env = parse_ini_file(__DIR__.'/.env');
        if (!empty($env['DB_DATABASE']) && !empty($env['DB_USERNAME'])) {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname={$env['DB_DATABASE']};charset=utf8mb4", $env['DB_USERNAME'], $env['DB_PASSWORD'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_TIMEOUT => 2,
            ]);
            
            // Auto add order_status column if missing
            $checkCol = $pdo->query("SHOW COLUMNS FROM orders LIKE 'order_status'");
            if ($checkCol && $checkCol->rowCount() === 0) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN order_status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending' AFTER payment_status");
            }

            // Auto ensure admin user exists with password admin123
            $passHash = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
                VALUES (1, 'Administrator', 'admin', 'admin', 'admin@bebarung.com', '$passHash', NOW(), NOW())
                ON DUPLICATE KEY UPDATE password='$passHash', role='admin'");

            // Auto ensure kasir user exists with password password
            $kasirHash = password_hash('password', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
                VALUES (2, 'Kasir 1', 'kasir', 'kasir', 'kasir@bebarung.com', '$kasirHash', NOW(), NOW())
                ON DUPLICATE KEY UPDATE password='$kasirHash', role='kasir'");
        }
    }
} catch (\Throwable $e) {
    // Ignore db connect errors on boot
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
