<?php

$sourceDir = 'C:/Users/sakak/.gemini/antigravity-ide/brain/2ac3b7fe-c90c-4272-85d3-e7cfd2644d93';
$targetDir = __DIR__ . '/images/menus';
$targetUploads = __DIR__ . '/uploads/menus';

$imageMap = [
    'sate_ayam_dish_1786993954600.jpg' => ['sate_ayam.jpg', 'sate_ayam.png'],
    'nasi_putih_dish_1786994032886.jpg' => ['nasi_putih.jpg', 'nasi_putih.png'],
    'nasi_gurih_dish_1786994324508.jpg' => ['nasi_gurih.jpg', 'nasi_gurih.png'],
    'teh_poci_drink_1786994678088.jpg' => ['teh_poci.jpg', 'teh_poci.png'],
];

foreach ($imageMap as $src => $targets) {
    $srcPath = $sourceDir . '/' . $src;
    if (file_exists($srcPath)) {
        foreach ($targets as $tgt) {
            copy($srcPath, $targetDir . '/' . $tgt);
            copy($srcPath, $targetUploads . '/' . $tgt);
        }
    }
}

echo "New menu images copied successfully!";
