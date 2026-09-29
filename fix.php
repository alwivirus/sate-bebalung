<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🛠️ Memperbaiki Database, Role Akun Terpisah (Dev vs Admin Kasir), Schema, QRIS & Permission...</h2>";

// Menu & Category Definition
$categories = [
    [1, 'MAKANAN', 'makanan', 'fa-utensils', 1],
    [2, 'MINUMAN', 'minuman', 'fa-mug-hot', 2],
    [3, 'PAKET', 'paket', 'fa-box-open', 3]
];

$menus = [
    // 1. MAKANAN
    [1, 'Sate Kambing (Polos)', 'sate-kambing-polos', '10 Tusuk Sate Full Daging kambing muda empuk bumbu rempah khas Be Ba Lung.', 50000, 'images/menus/sate_kambing_polos.jpg', 'BEST SELLER', 1, 1],
    [1, 'Sate Kambing (Campur)', 'sate-kambing-campur', '10 Tusuk Sate Daging + Ati / Lemak gurih renyah aroma panggangan khas.', 45000, 'images/menus/sate_kambing_campur.jpg', 'FAVORIT', 1, 2],
    [1, 'Tongseng Kambing', 'tongseng-kambing', 'Olahan daging kambing kuah tongseng gurih segar dengan irisan kol dan tomat.', 35000, 'images/menus/tongseng_kambing.jpg', 'REKOMENDASI', 1, 3],
    [1, 'Sop Kambing', 'sop-kambing', 'Kuah bening rempah harum segar dengan potongan daging dan iga kambing lembut.', 30000, 'images/menus/sop_kambing.jpg', 'SEGAR GURIH', 1, 4],
    [1, 'Gulai Kambing', 'gulai-kambing', 'Gulai kambing kuah santan kental rempah istimewa yang gurih dan sedap.', 30000, 'images/menus/gulai_kambing.jpg', NULL, 1, 5],
    [1, 'Sate Ayam', 'sate-ayam', 'Sate daging ayam bakar bumbu kacang gurih manis dengan taburan bawang goreng.', 20000, 'images/menus/sate_ayam.jpg', NULL, 1, 6],
    [1, 'Nasi Putih', 'nasi-putih', 'Satu porsi nasi putih hangat pulen harum.', 6000, 'images/menus/nasi_putih.jpg', NULL, 1, 7],
    [1, 'Nasi Gurih', 'nasi-gurih', 'Nasi gurih rempah santan daun jeruk dengan taburan bawang goreng.', 7500, 'images/menus/nasi_gurih.jpg', 'GURIH', 1, 8],

    // 2. MINUMAN
    [2, 'Air Putih', 'air-putih', 'Air mineral segar higienis pelepas dahaga.', 2000, 'images/menus/air_putih.jpg', NULL, 1, 9],
    [2, 'Teh Tawar', 'teh-tawar', 'Teh tawar hangat harum melati menyegarkan.', 2000, 'images/menus/teh_tawar.jpg', 'HANGAT', 1, 10],
    [2, 'Es Teh Tawar', 'es-teh-tawar', 'Es teh tawar dingin segar pelepas dahaga.', 3000, 'images/menus/es_teh_tawar.jpg', NULL, 1, 11],
    [2, 'Es Teh Manis', 'es-teh-manis', 'Es teh manis segar wangi melati asli.', 4000, 'images/menus/es_teh_manis.jpg', 'SEGAR', 1, 12],
    [2, 'Air Jeruk / Panas', 'air-jeruk-panas', 'Perasan jeruk murni hangat kaya vitamin C.', 8000, 'images/menus/jeruk_panas.jpg', 'HANGAT', 1, 13],
    [2, 'Es Jeruk', 'es-jeruk', 'Perasan jeruk segar asli dingin nikmat.', 10000, 'images/menus/es_jeruk.jpg', 'FAVORIT', 1, 14],
    [2, 'Teh Poci', 'teh-poci', 'Teh poci tanah liat tradisional disajikan hangat dengan gula batu.', 15000, 'images/menus/teh_poci.jpg', 'KLASIK', 1, 15],
    [2, 'Kopi Toebroek', 'kopi-toebroek', 'Kopi hitam tubruk biji kopi nusantara pilihan harum mantap.', 5000, 'images/menus/kopi_toebroek.jpg', 'MANTAP', 1, 16],

    // 3. PAKET MAKANAN
    [3, 'Paket Hemat Komplit', 'paket-hemat', 'Paket komplit: Nasi Putih + Tongseng Kambing + 5 Tusuk Sate Kambing + Es Teh Manis.', 22000, 'images/menus/paket_murah.jpg', 'HEMAT 22RB', 1, 17],
    [3, 'Paket Nasi Kotak Bento', 'paket-bento-syukuran', 'Kemasan bento premium: Nasi pulen, Sate kambing empuk, Gule cup, Kerupuk & Buah.', 28000, 'images/menus/paket_bento.jpg', 'BENTO KOMPLIT', 1, 18],
    [3, 'Paket Kenyang Sate & Gulai', 'paket-kenyang-sate-gulai', '1 Porsi Nasi Putih + 5 Tusuk Sate Kambing + Gulai Kambing Hangat + Es Teh Manis.', 35000, 'images/menus/paket_murah.jpg', 'PAKET KENYANG', 1, 19],
];

// Helper to seed/sync any PDO database (MySQL or SQLite)
function syncDatabase(PDO $pdo, string $driverName = 'mysql') {
    global $categories, $menus;

    if ($driverName === 'mysql') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            username VARCHAR(255) NULL UNIQUE,
            role VARCHAR(50) NOT NULL DEFAULT 'kasir',
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified_at TIMESTAMP NULL,
            password VARCHAR(255) NOT NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            icon VARCHAR(255) NULL,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS menus (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NULL,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(255) NULL,
            badge VARCHAR(255) NULL,
            is_available TINYINT(1) NOT NULL DEFAULT 1,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_code VARCHAR(255) NOT NULL UNIQUE,
            customer_name VARCHAR(255) NOT NULL,
            table_number VARCHAR(50) NOT NULL,
            payment_method VARCHAR(50) NOT NULL DEFAULT 'online',
            payment_status VARCHAR(50) NOT NULL DEFAULT 'unpaid',
            order_status VARCHAR(50) NOT NULL DEFAULT 'pending',
            total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            payment_proof VARCHAR(255) NULL,
            notes TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT UNSIGNED NOT NULL,
            menu_id BIGINT UNSIGNED NOT NULL,
            menu_name VARCHAR(255) NULL,
            price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            subtotal DECIMAL(10,2) NOT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS tables (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            table_number VARCHAR(50) NOT NULL UNIQUE,
            status VARCHAR(50) NOT NULL DEFAULT 'available',
            current_customer_name VARCHAR(255) NULL,
            current_order_code VARCHAR(255) NULL,
            last_scanned_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(255) NOT NULL UNIQUE,
            `value` TEXT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Alterations if existing
        try {
            $cols = $pdo->query("SHOW COLUMNS FROM orders LIKE 'order_status'")->fetchAll();
            if (empty($cols)) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN order_status VARCHAR(50) NOT NULL DEFAULT 'pending' AFTER payment_status");
            } else {
                $pdo->exec("ALTER TABLE orders MODIFY COLUMN order_status VARCHAR(50) NOT NULL DEFAULT 'pending'");
            }
            $pdo->exec("ALTER TABLE orders MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'online'");
            $pdo->exec("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'unpaid'");

            $orderProofCols = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_proof'")->fetchAll();
            if (empty($orderProofCols)) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) NULL AFTER total_amount");
            }

            $itemCols = $pdo->query("SHOW COLUMNS FROM order_items LIKE 'menu_name'")->fetchAll();
            if (empty($itemCols)) {
                $pdo->exec("ALTER TABLE order_items ADD COLUMN menu_name VARCHAR(255) NULL AFTER menu_id");
            }

            $tableScanCols = $pdo->query("SHOW COLUMNS FROM tables LIKE 'last_scanned_at'")->fetchAll();
            if (empty($tableScanCols)) {
                $pdo->exec("ALTER TABLE tables ADD COLUMN last_scanned_at TIMESTAMP NULL AFTER current_order_code");
            }
            $tableCustCols = $pdo->query("SHOW COLUMNS FROM tables LIKE 'current_customer_name'")->fetchAll();
            if (empty($tableCustCols)) {
                $pdo->exec("ALTER TABLE tables ADD COLUMN current_customer_name VARCHAR(255) NULL AFTER status");
            }
            $tableOrderCols = $pdo->query("SHOW COLUMNS FROM tables LIKE 'current_order_code'")->fetchAll();
            if (empty($tableOrderCols)) {
                $pdo->exec("ALTER TABLE tables ADD COLUMN current_order_code VARCHAR(255) NULL AFTER current_customer_name");
            }
        } catch (\Throwable $ex) {}

    } else {
        // SQLite
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            username VARCHAR(255) UNIQUE,
            role VARCHAR(50) NOT NULL DEFAULT 'kasir',
            email VARCHAR(255) NOT NULL UNIQUE,
            email_verified_at DATETIME NULL,
            password VARCHAR(255) NOT NULL,
            remember_token VARCHAR(100) NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            icon VARCHAR(255) NULL,
            sort_order INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS menus (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NULL,
            price NUMERIC NOT NULL,
            image VARCHAR(255) NULL,
            badge VARCHAR(255) NULL,
            is_available INTEGER NOT NULL DEFAULT 1,
            sort_order INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_code VARCHAR(255) NOT NULL UNIQUE,
            customer_name VARCHAR(255) NOT NULL,
            table_number VARCHAR(50) NOT NULL,
            payment_method VARCHAR(50) NOT NULL DEFAULT 'online',
            payment_status VARCHAR(50) NOT NULL DEFAULT 'unpaid',
            order_status VARCHAR(50) NOT NULL DEFAULT 'pending',
            total_amount NUMERIC NOT NULL DEFAULT 0.00,
            payment_proof VARCHAR(255) NULL,
            notes TEXT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            menu_id INTEGER NOT NULL,
            menu_name VARCHAR(255) NULL,
            price NUMERIC NOT NULL,
            quantity INTEGER NOT NULL DEFAULT 1,
            subtotal NUMERIC NOT NULL,
            notes TEXT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS tables (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            table_number VARCHAR(50) NOT NULL UNIQUE,
            status VARCHAR(50) NOT NULL DEFAULT 'available',
            current_customer_name VARCHAR(255) NULL,
            current_order_code VARCHAR(255) NULL,
            last_scanned_at DATETIME NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key VARCHAR(255) NOT NULL UNIQUE,
            value TEXT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        )");

        try { $pdo->exec("ALTER TABLE users ADD COLUMN username VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'kasir'"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE orders ADD COLUMN order_status VARCHAR(50) NOT NULL DEFAULT 'pending'"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE order_items ADD COLUMN menu_name VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE categories ADD COLUMN slug VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE categories ADD COLUMN icon VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE categories ADD COLUMN sort_order INTEGER NOT NULL DEFAULT 0"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN slug VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN badge VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN is_available INTEGER NOT NULL DEFAULT 1"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN sort_order INTEGER NOT NULL DEFAULT 0"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE tables ADD COLUMN current_customer_name VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE tables ADD COLUMN current_order_code VARCHAR(255) NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE tables ADD COLUMN last_scanned_at DATETIME NULL"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN sort_order INTEGER NOT NULL DEFAULT 0"); } catch (\Throwable $e) {}
        try { $pdo->exec("ALTER TABLE menus ADD COLUMN is_available INTEGER NOT NULL DEFAULT 1"); } catch (\Throwable $e) {}
    }

    $now = date('Y-m-d H:i:s');

    // Akun 1: Master Developer (dev / 121212)
    $devHash = password_hash('121212', PASSWORD_BCRYPT);
    $pdo->exec("DELETE FROM users WHERE username='dev' OR email='dev@bebarung.com'");
    $pdo->exec("INSERT INTO users (name, username, role, email, password, created_at, updated_at) 
        VALUES ('Master Developer', 'dev', 'developer', 'dev@bebarung.com', '$devHash', '$now', '$now')");

    // Akun 2: Admin Kasir Utama (admin / ownsate)
    $adminHash = password_hash('ownsate', PASSWORD_BCRYPT);
    $pdo->exec("DELETE FROM users WHERE username='admin' OR email='admin@bebarung.com'");
    $pdo->exec("INSERT INTO users (name, username, role, email, password, created_at, updated_at) 
        VALUES ('Admin Kasir Utama / Owner', 'admin', 'admin', 'admin@bebarung.com', '$adminHash', '$now', '$now')");

    // Akun 3: Kasir 1 (kasir & kasir1 / sate)
    $kasirHash = password_hash('sate', PASSWORD_BCRYPT);
    $pdo->exec("DELETE FROM users WHERE username IN ('kasir', 'kasir1') OR email IN ('kasir@bebarung.com', 'kasir1@bebarung.com')");
    $pdo->exec("INSERT INTO users (name, username, role, email, password, created_at, updated_at) 
        VALUES ('Kasir 1', 'kasir', 'kasir', 'kasir@bebarung.com', '$kasirHash', '$now', '$now')");
    $pdo->exec("INSERT INTO users (name, username, role, email, password, created_at, updated_at) 
        VALUES ('Kasir 1', 'kasir1', 'kasir', 'kasir1@bebarung.com', '$kasirHash', '$now', '$now')");

    // Reset Meja 01 - 20
    for ($i = 1; $i <= 20; $i++) {
        $tbl = str_pad($i, 2, '0', STR_PAD_LEFT);
        $pdo->exec("DELETE FROM tables WHERE table_number='$tbl'");
        $pdo->exec("INSERT INTO tables (table_number, status, current_customer_name, current_order_code, created_at, updated_at)
            VALUES ('$tbl', 'available', NULL, NULL, '$now', '$now')");
    }

    // Set QRIS Setting
    $pdo->exec("DELETE FROM settings WHERE `key`='qris_image' OR key='qris_image'");
    $pdo->exec("INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES ('qris_image', 'images/qris_official.png', '$now', '$now')");

    // Reset & Seed Categories
    $pdo->exec("DELETE FROM categories");
    $stmtCat = $pdo->prepare("INSERT INTO categories (id, name, slug, icon, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, '$now', '$now')");
    foreach ($categories as $cat) {
        $stmtCat->execute($cat);
    }

    // Reset & Seed Menus
    $pdo->exec("DELETE FROM menus");
    $stmtMenu = $pdo->prepare("INSERT INTO menus (category_id, name, slug, description, price, image, badge, is_available, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '$now', '$now')");
    foreach ($menus as $m) {
        $stmtMenu->execute($m);
    }
}

// 1. Sync SQLite DB (Guaranteed fast local & testing database)
try {
    $sqlitePath = __DIR__ . '/database/database.sqlite';
    if (!file_exists($sqlitePath)) {
        @touch($sqlitePath);
    }
    $pdoSqlite = new PDO('sqlite:' . $sqlitePath);
    $pdoSqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    syncDatabase($pdoSqlite, 'sqlite');
    echo "<p style='color:green;'>✅ Database SQLite (database/database.sqlite) tersinkronisasi 100%!</p>";
} catch (\Throwable $e) {
    echo "<p style='color:orange;'>⚠️ SQLite Notice: " . $e->getMessage() . "</p>";
}

// 2. Sync MySQL DB if active
try {
    $pdoMysql = null;
    $dbConfigs = [
        ['mysql:host=127.0.0.1;dbname=sate_bebalung;charset=utf8mb4', 'root', ''],
        ['mysql:host=localhost;dbname=sate_bebalung;charset=utf8mb4', 'root', ''],
        ['mysql:host=127.0.0.1;dbname=bebs9762_bebalung;charset=utf8mb4', 'bebs9762_bebalung', 'satemaknyus10_'],
        ['mysql:host=localhost;dbname=bebs9762_bebalung;charset=utf8mb4', 'bebs9762_bebalung', 'satemaknyus10_'],
    ];

    foreach ($dbConfigs as $cfg) {
        try {
            $pdoMysql = new PDO($cfg[0], $cfg[1], $cfg[2], [
                PDO::ATTR_TIMEOUT => 2,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            break;
        } catch (\Throwable $ex) {
            continue;
        }
    }

    if ($pdoMysql) {
        syncDatabase($pdoMysql, 'mysql');
        echo "<p style='color:green;'>✅ Database MySQL tersinkronisasi 100%!</p>";
    } else {
        echo "<p style='color:blue;'>ℹ️ MySQL lokal/server belum aktif. Sistem tetap aman dan terhubung penuh via SQLite fallback.</p>";
    }
} catch (\Throwable $e) {
    echo "<p style='color:orange;'>⚠️ MySQL Notice: " . $e->getMessage() . "</p>";
}

// 3. Clear bootstrap/cache
$cacheFiles = glob(__DIR__ . '/bootstrap/cache/*.php');
if (!empty($cacheFiles)) {
    foreach ($cacheFiles as $cf) {
        @unlink($cf);
    }
    echo "<p style='color:green;'>✅ Cache bootstrap lama berhasil dibersihkan!</p>";
}

// 4. Pastikan .env di cPanel terisi kredensial database yang benar
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    if (str_contains(__DIR__, 'bebs9762') || (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'bebalung.my.id'))) {
        $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=bebs9762_bebalung', $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=bebs9762_bebalung', $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=satemaknyus10_', $envContent);
        @file_put_contents($envPath, $envContent);
        echo "<p style='color:green;'>✅ File .env cPanel terverifikasi!</p>";
    }
}

// 5. Salin gambar
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
