<?php

$dir = __DIR__;

// Helper to add recursive directory to zip
function addDirToZip($zip, $sourceDir, $localPrefix = '') {
    if (!is_dir($sourceDir)) return;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($sourceDir) + 1);
        $relativePath = str_replace('\\', '/', $relativePath);
        $zipPath = ($localPrefix !== '') ? rtrim($localPrefix, '/') . '/' . $relativePath : $relativePath;
        if ($file->isDir()) {
            $zip->addEmptyDir($zipPath);
        } elseif ($file->isFile()) {
            $zip->addFile($filePath, $zipPath);
        }
    }
}

// 1. UPDATE ZIP KHUSUS PEMBARUAN LINK MEJA SECURE & BANNER & GAMBAR TERKOMPRES
$secureZipFile = $dir . '/UPDATE_QR_MEJA_DAN_SISTEM_SECURE.zip';
if (file_exists($secureZipFile)) {
    unlink($secureZipFile);
}

$zip = new ZipArchive();
if ($zip->open($secureZipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $filesToZip = [
        'app/Models/Table.php',
        'app/Models/Menu.php',
        'app/Models/Setting.php',
        'app/Http/Controllers/AdminController.php',
        'app/Http/Controllers/OrderController.php',
        'app/Http/Controllers/AuthController.php',
        'app/Http/Controllers/ChatbotController.php',
        'routes/web.php',
        'resources/views/customer/showcase.blade.php',
        'resources/views/customer/menu.blade.php',
        'resources/views/customer/payment.blade.php',
        'resources/views/customer/checkout.blade.php',
        'resources/views/customer/success.blade.php',
        'resources/views/admin/tables.blade.php',
        'resources/views/admin/tables_print_all.blade.php',
        'resources/views/admin/tables_print_single.blade.php',
        'resources/views/admin/dashboard.blade.php',
        'resources/views/admin/receipt.blade.php',
        'resources/views/layouts/app.blade.php',
        'resources/views/layouts/admin.blade.php',
        'resources/views/components/chatbot.blade.php',
        'public/images/promo_aqiqoh_sodaqoh.jpg',
        'public/images/promo_aqiqoh_sodaqoh.png',
        'images/promo_aqiqoh_sodaqoh.jpg',
        'images/promo_aqiqoh_sodaqoh.png',
        'public/images/qris_official.png',
        'public/uploads/settings/qris_official.png',
        'images/qris_official.png',
    ];

    foreach ($filesToZip as $file) {
        $fullPath = $dir . '/' . $file;
        if (file_exists($fullPath)) {
            $zip->addFile($fullPath, $file);
            echo "Added: " . $file . "\n";
        }
    }

    // Add compressed image directories
    addDirToZip($zip, $dir . '/public/images', 'public/images');
    addDirToZip($zip, $dir . '/public/uploads', 'public/uploads');
    addDirToZip($zip, $dir . '/images', 'images');
    addDirToZip($zip, $dir . '/uploads', 'uploads');

    $zip->close();
    copy($secureZipFile, $dir . '/public/UPDATE_QR_MEJA_DAN_SISTEM_SECURE.zip');
    echo "\n>>> UPDATE ZIP BERHASIL DIBUAT: UPDATE_QR_MEJA_DAN_SISTEM_SECURE.zip (" . number_format(filesize($secureZipFile) / 1024, 1) . " KB)\n\n";
}

// 2. FULL PROJECT ZIP (Untuk backup penuh / upload full ke cPanel)
$fullZipFile = $dir . '/FULL_PROJECT_CPANEL_DEPOT_BEBALUNG.zip';
if (file_exists($fullZipFile)) {
    unlink($fullZipFile);
}

$fullZip = new ZipArchive();
if ($fullZip->open($fullZipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $excludeDirs = ['.git', 'vendor', 'node_modules', '.agents'];
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($dir) + 1);
        $relativePath = str_replace('\\', '/', $relativePath);

        // Check exclusions
        $skip = false;
        if (str_ends_with($relativePath, '.zip')) {
            $skip = true;
        }
        foreach ($excludeDirs as $ex) {
            if (str_starts_with($relativePath, $ex . '/') || $relativePath === $ex) {
                $skip = true;
                break;
            }
        }

        if ($skip) continue;

        if ($file->isDir()) {
            $fullZip->addEmptyDir($relativePath);
        } elseif ($file->isFile()) {
            $fullZip->addFile($filePath, $relativePath);
        }
    }

    $fullZip->close();
    copy($fullZipFile, $dir . '/public/FULL_PROJECT_CPANEL_DEPOT_BEBALUNG.zip');
    echo ">>> FULL PROJECT ZIP BERHASIL DIBUAT: FULL_PROJECT_CPANEL_DEPOT_BEBALUNG.zip (" . number_format(filesize($fullZipFile) / (1024 * 1024), 2) . " MB)\n";
}

