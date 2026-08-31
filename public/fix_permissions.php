<?php
// Script Perbaikan Permission & Asset Linking Otomatis untuk cPanel
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🛠️ Memperbaiki Permission & Asset Gambar...</h2>";

$basePath = dirname(__DIR__);

// 1. Link images & uploads to root
if (!file_exists($basePath . '/images') && is_dir($basePath . '/public/images')) {
    @symlink($basePath . '/public/images', $basePath . '/images');
    echo "<p style='color:green;'>✅ Folder images berhasil dihubungkan ke root.</p>";
}
if (!file_exists($basePath . '/uploads') && is_dir($basePath . '/public/uploads')) {
    @symlink($basePath . '/public/uploads', $basePath . '/uploads');
    echo "<p style='color:green;'>✅ Folder uploads berhasil dihubungkan ke root.</p>";
}

// 2. Fallback copy if symlink failed
if (!file_exists($basePath . '/images')) {
    @mkdir($basePath . '/images', 0755, true);
    @mkdir($basePath . '/images/menus', 0755, true);
    foreach (glob($basePath . '/public/images/*.*') as $f) {
        @copy($f, $basePath . '/images/' . basename($f));
    }
    foreach (glob($basePath . '/public/images/menus/*.*') as $f) {
        @copy($f, $basePath . '/images/menus/' . basename($f));
    }
    echo "<p style='color:green;'>✅ Asset gambar menu berhasil disalin ke root images.</p>";
}

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

fixPermissions($basePath . '/vendor');
fixPermissions($basePath . '/app');
fixPermissions($basePath . '/public');
if (is_dir($basePath . '/images')) fixPermissions($basePath . '/images');

@chmod($basePath . '/storage', 0777);
@chmod($basePath . '/bootstrap/cache', 0777);

echo "<p style='color:green;'>✅ Semua gambar dan permission berhasil diperbaiki 100%!</p>";
echo "<hr><a href='/?table=1' style='font-size:22px;font-weight:bold;color:#F59E0B;'>👉 Buka Website Depot Sate Bebalung (Klik di Sini)</a>";
