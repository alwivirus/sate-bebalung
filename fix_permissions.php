<?php
// Script Perbaikan Permission File & Folder Otomatis untuk cPanel
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🛠️ Memperbaiki Permission File & Folder...</h2>";

$basePath = __DIR__;

function fixPermissions($path) {
    $count = 0;
    if (!is_dir($path)) return 0;
    
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            @chmod($item->getPathname(), 0755);
        } else {
            @chmod($item->getPathname(), 0644);
        }
        $count++;
    }
    return $count;
}

// 1. Fix vendor permissions
$vendorCount = fixPermissions($basePath . '/vendor');
echo "<p style='color:green;'>✅ Berhasil memperbaiki $vendorCount file & folder di dalam <b>vendor/</b>.</p>";

// 2. Fix other folders
fixPermissions($basePath . '/app');
fixPermissions($basePath . '/bootstrap');
fixPermissions($basePath . '/config');
fixPermissions($basePath . '/routes');
fixPermissions($basePath . '/database');
fixPermissions($basePath . '/resources');
fixPermissions($basePath . '/public');

// 3. Storage & cache must be writable (0777 / 0775)
@chmod($basePath . '/storage', 0777);
$storageItems = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($basePath . '/storage', RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($storageItems as $item) {
    if ($item->isDir()) {
        @chmod($item->getPathname(), 0777);
    } else {
        @chmod($item->getPathname(), 0666);
    }
}
@chmod($basePath . '/bootstrap/cache', 0777);

echo "<p style='color:green;'>✅ Semua permission file (0644) dan folder (0755 / 0777) berhasil disesuaikan!</p>";
echo "<hr><a href='/?table=1' style='font-size:18px;font-weight:bold;'>👉 Buka Website Depot Sate Bebalung</a>";
