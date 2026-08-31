<?php

$src = 'C:/Users/sakak/.gemini/antigravity-ide/brain/2ac3b7fe-c90c-4272-85d3-e7cfd2644d93/.user_uploaded/media_1786993579931.png';
$targetDir = __DIR__ . '/images';

if (file_exists($src)) {
    copy($src, $targetDir . '/logo-goat.png');
    copy($src, $targetDir . '/logo-bebarung.png');
    copy($src, __DIR__ . '/uploads/menus/logo-goat.png');
    echo "Logo successfully copied from original user upload!";
} else {
    echo "Source not found.";
}
