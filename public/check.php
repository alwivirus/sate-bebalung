<?php
// Script Diagnostik Singkat Depot Sate Bebalung
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🔧 Diagnostik Sistem Depot Sate Bebalung</h2>";
echo "<b>PHP Version:</b> " . PHP_VERSION . "<br>";

$basePath = dirname(__DIR__);
$envPath = $basePath . '/.env';

echo "<b>Base Path:</b> " . $basePath . "<br>";

// 1. Cek File .env
if (file_exists($envPath)) {
    echo "<p style='color:green;'>✅ File .env DITEMUKAN.</p>";
} else {
    echo "<p style='color:red;'>❌ File .env TIDAK DITEMUKAN di: $envPath</p>";
    echo "<p>💡 Solusi: Buat atau rename file .env di folder utama.</p>";
}

// 2. Cek Permission Storage
$storagePath = $basePath . '/storage';
if (is_writable($storagePath)) {
    echo "<p style='color:green;'>✅ Folder storage WRITABLE (Bisa ditulisi).</p>";
} else {
    echo "<p style='color:red;'>❌ Folder storage TIDAK WRITABLE. Harap chmod 775 atau 777.</p>";
}

// 3. Test Bootstrap Laravel
echo "<b>Testing Bootstrapping Laravel...</b><br>";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    echo "<p style='color:green;'>✅ Laravel App Bootstrap BERHASIL!</p>";
} catch (\Throwable $e) {
    echo "<p style='color:red;'>❌ Error Bootstrap: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
