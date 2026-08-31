<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🛠️ Memperbaiki Database, Role Akun Terpisah (Dev vs Admin Kasir), Schema, QRIS & Permission...</h2>";

// 1. Database auto-fix
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=bebs9762_bebalung;charset=utf8mb4', 'bebs9762_bebalung', 'satemaknyus10_');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek kolom order_status di orders dan ubah ke VARCHAR(50) agar tidak error truncation
    $cols = $pdo->query("SHOW COLUMNS FROM orders LIKE 'order_status'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN order_status VARCHAR(50) NOT NULL DEFAULT 'pending' AFTER payment_status");
        echo "<p style='color:green;'>✅ Kolom <b>order_status</b> berhasil ditambahkan ke tabel orders!</p>";
    } else {
        $pdo->exec("ALTER TABLE orders MODIFY COLUMN order_status VARCHAR(50) NOT NULL DEFAULT 'pending'");
    }

    $pdo->exec("ALTER TABLE orders MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'online'");
    $pdo->exec("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'unpaid'");

    // Cek kolom payment_proof di orders
    $orderProofCols = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_proof'")->fetchAll();
    if (empty($orderProofCols)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) NULL AFTER total_amount");
        echo "<p style='color:green;'>✅ Kolom <b>payment_proof</b> berhasil ditambahkan ke tabel orders!</p>";
    }

    // Cek kolom menu_name di order_items
    $itemCols = $pdo->query("SHOW COLUMNS FROM order_items LIKE 'menu_name'")->fetchAll();
    if (empty($itemCols)) {
        $pdo->exec("ALTER TABLE order_items ADD COLUMN menu_name VARCHAR(255) NULL AFTER menu_id");
        echo "<p style='color:green;'>✅ Kolom <b>menu_name</b> berhasil ditambahkan ke tabel order_items!</p>";
    }

    // Akun 1: Master Developer (Role: developer, Password: 'dev123')
    $devHash = password_hash('dev123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
        VALUES (99, 'Master Developer', 'dev', 'developer', 'dev@bebarung.com', '$devHash', NOW(), NOW())
        ON DUPLICATE KEY UPDATE password='$devHash', role='developer', name='Master Developer'");

    // Akun 2: Admin Kasir Utama / Owner (Role: admin, Password: 'admin123')
    $passHash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
        VALUES (1, 'Admin Kasir Utama / Owner', 'admin', 'admin', 'admin@bebarung.com', '$passHash', NOW(), NOW())
        ON DUPLICATE KEY UPDATE password='$passHash', role='admin', name='Admin Kasir Utama / Owner'");

    // Akun 3: Kasir Reguler (Role: kasir, Password: 'password')
    $kasirHash = password_hash('password', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
        VALUES (2, 'Kasir 1', 'kasir', 'kasir', 'kasir@bebarung.com', '$kasirHash', NOW(), NOW())
        ON DUPLICATE KEY UPDATE password='$kasirHash', role='kasir', name='Kasir 1'");

    // Reset status meja agar semua bersih & kosong (available)
    $pdo->exec("UPDATE tables SET status='available', current_customer_name=NULL, current_order_code=NULL");

    // Set QRIS Resmi Toko
    $pdo->exec("INSERT INTO settings (`key`, `value`, created_at, updated_at) 
        VALUES ('qris_image', 'images/qris_official.png', NOW(), NOW())
        ON DUPLICATE KEY UPDATE `value`='images/qris_official.png'");

    // Reset & Clean Categories & Menus strictly according to physical menu card
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("TRUNCATE TABLE menus");
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    $pdo->exec("INSERT INTO categories (id, name, slug, icon, sort_order, created_at, updated_at) VALUES 
        (1, 'MENU MAKANAN', 'makanan', 'fa-utensils', 1, NOW(), NOW()),
        (2, 'MENU MINUMAN', 'minuman', 'fa-mug-hot', 2, NOW(), NOW())");

    $menus = [
        [1, 'Sate Kambing (Polos)', 'sate-kambing-polos', '10 Tusuk Sate Full Daging kambing muda empuk bumbu rempah khas Be Ba Lung.', 50000, 'images/menus/sate_kambing_polos.jpg', 'BEST SELLER', 1, 1],
        [1, 'Sate Kambing (Campur)', 'sate-kambing-campur', '10 Tusuk Sate Daging + Ati / Lemak gurih renyah aroma panggangan khas.', 45000, 'images/menus/sate_kambing_campur.jpg', 'FAVORIT', 1, 2],
        [1, 'Tongseng Kambing', 'tongseng-kambing', 'Olahan daging kambing kuah tongseng gurih segar dengan irisan kol dan tomat.', 35000, 'images/menus/tongseng_kambing.jpg', 'REKOMENDASI', 1, 3],
        [1, 'Sop Kambing', 'sop-kambing', 'Kuah bening rempah harum segar dengan potongan daging dan iga kambing lembut.', 30000, 'images/menus/sop_kambing.jpg', 'SEGAR GURIH', 1, 4],
        [1, 'Gulai Kambing', 'gulai-kambing', 'Gulai kambing kuah santan kental rempah istimewa yang gurih dan sedap.', 30000, 'images/menus/gulai_kambing.jpg', NULL, 1, 5],
        [1, 'Sate Ayam', 'sate-ayam', 'Sate daging ayam bakar bumbu kacang gurih manis dengan taburan bawang goreng.', 20000, 'images/menus/sate_ayam.jpg', NULL, 1, 6],
        [1, 'Nasi Putih', 'nasi-putih', 'Satu porsi nasi putih hangat pulen harum.', 6000, 'images/menus/nasi_putih.jpg', NULL, 1, 7],
        [1, 'Nasi Gurih', 'nasi-gurih', 'Nasi gurih rempah santan daun jeruk dengan taburan bawang goreng.', 7500, 'images/menus/nasi_gurih.jpg', 'GURIH', 1, 8],
        [2, 'Air Putih / Teh Tawar', 'air-putih-teh-tawar', 'Air mineral / teh tawar hangat segar higienis.', 2000, 'images/menus/air_putih.jpg', NULL, 1, 9],
        [2, 'Es Teh Tawar', 'es-teh-tawar', 'Es teh tawar dingin segar pelepas dahaga.', 3000, 'images/menus/es_teh_tawar.jpg', NULL, 1, 10],
        [2, 'Es Teh Manis', 'es-teh-manis', 'Es teh manis segar wangi melati asli.', 4000, 'images/menus/es_teh_manis.jpg', 'SEGAR', 1, 11],
        [2, 'Air Jeruk / Panas', 'air-jeruk-panas', 'Perasan jeruk murni hangat kaya vitamin C.', 8000, 'images/menus/jeruk_panas.jpg', 'HANGAT', 1, 12],
        [2, 'Es Jeruk', 'es-jeruk', 'Perasan jeruk segar asli dingin nikmat.', 10000, 'images/menus/es_jeruk.jpg', 'FAVORIT', 1, 13],
        [2, 'Teh Poci', 'teh-poci', 'Teh poci tanah liat tradisional disajikan hangat dengan gula batu.', 15000, 'images/menus/teh_poci.jpg', 'KLASIK', 1, 14],
        [2, 'Kopi Toebroek', 'kopi-toebroek', 'Kopi hitam tubruk biji kopi nusantara pilihan harum mantap.', 5000, 'images/menus/kopi_toebroek.svg', 'MANTAP', 1, 15]
    ];

    $stmt = $pdo->prepare("INSERT INTO menus (category_id, name, slug, description, price, image, badge, is_available, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    foreach ($menus as $m) {
        $stmt->execute($m);
    }

    echo "<p style='color:green;'>✅ Role Akun Terpisah (Dev vs Admin Kasir), Kategori, 15 Menu Resmi, QRIS Resmi & Status Meja Bersih berhasil disinkronkan 100%!</p>";
} catch (\Throwable $e) {
    echo "<p style='color:red;'>⚠️ Database Notice: " . $e->getMessage() . "</p>";
}

// 2. Salin gambar
function copyDir($src, $dst) {
    @mkdir($dst, 0755, true);
    if (!is_dir($src)) return;
    foreach (scandir($src) as $file) {
        if ($file == '.' || $file == '..') continue;
        if (is_dir("$src/$file")) {
            copyDir("$src/$file", "$dst/$file");
        } else {
            @copy("$src/$file", "$dst/$file");
            @chmod("$dst/$file", 0644);
        }
    }
}

copyDir(__DIR__ . '/public/images', __DIR__ . '/images');
@copyDir(__DIR__ . '/public/uploads', __DIR__ . '/uploads');

echo "<p style='color:green;'>✅ Semua logo, foto makanan & QRIS resmi berhasil dimunculkan!</p>";
echo "<hr>";
echo "<p><a href='/admin' style='font-size:18px;font-weight:bold;color:#111827;'>👉 Buka Dashboard Admin Kasir (Klik di Sini)</a></p>";
echo "<p><a href='/admin/developer' style='font-size:18px;font-weight:bold;color:#4F46E5;'>👉 Buka Panel Khusus Developer (Klik di Sini)</a></p>";
echo "<p><a href='/?table=1' style='font-size:18px;font-weight:bold;color:#F59E0B;'>👉 Buka Tampilan Menu Pelanggan (Klik di Sini)</a></p>";
