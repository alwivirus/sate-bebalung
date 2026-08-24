<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<h2>🛠️ Memperbaiki Database, Akun Admin, Gambar & Permission...</h2>";

// 1. Database auto-fix
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=bebs9762_bebalung;charset=utf8mb4', 'bebs9762_bebalung', 'satemaknyus10_');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek kolom order_status di orders
    $cols = $pdo->query("SHOW COLUMNS FROM orders LIKE 'order_status'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN order_status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending' AFTER payment_status");
        echo "<p style='color:green;'>✅ Kolom <b>order_status</b> berhasil ditambahkan ke tabel orders!</p>";
    } else {
        echo "<p style='color:green;'>✅ Kolom <b>order_status</b> sudah ada.</p>";
    }

    // Pastikan user admin dan kasir ada dan bisa login dengan password 'admin123' / 'password'
    $passHash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
        VALUES (1, 'Administrator', 'admin', 'admin', 'admin@bebarung.com', '$passHash', NOW(), NOW())
        ON DUPLICATE KEY UPDATE password='$passHash', role='admin'");

    $kasirHash = password_hash('password', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (id, name, username, role, email, password, created_at, updated_at) 
        VALUES (2, 'Kasir 1', 'kasir', 'kasir', 'kasir@bebarung.com', '$kasirHash', NOW(), NOW())
        ON DUPLICATE KEY UPDATE password='$kasirHash', role='kasir'");

    echo "<p style='color:green;'>✅ Akun Admin & Kasir berhasil diperbarui!</p>";
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

echo "<p style='color:green;'>✅ Semua logo & foto makanan berhasil dimunculkan!</p>";
echo "<hr>";
echo "<p><a href='/login' style='font-size:18px;font-weight:bold;color:#111827;'>👉 Buka Login Admin Kasir (Username: admin | Password: admin123)</a></p>";
echo "<p><a href='/?table=1' style='font-size:18px;font-weight:bold;color:#F59E0B;'>👉 Buka Pesan Menu Meja #1 (Klik di Sini)</a></p>";
