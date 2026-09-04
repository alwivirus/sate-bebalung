# LAPORAN TUGAS PRAKTIK KEJURUAN (RPL)
## Perancangan Basis Data Sistem Kasir & QR Menu Depot Sate Be Ba Lung

- **Program Keahlian:** Rekayasa Perangkat Lunak (RPL)
- **Mata Pelajaran / Tugas:** Pemodelan Perangkat Lunak & Basis Data (Tugas 5 - Database)
- **DBMS:** MySQL / MariaDB (XAMPP & cPanel Hosting)
- **Framework:** Laravel 11 / Eloquent ORM

---

## 1. Pendahuluan & Latar Belakang
Di projek Depot Sate Be Ba Lung ini, bagian database bertugas menyiapkan tempat penyimpanan data yang rapi, cepat, dan aman. Mulai dari menyimpan data menu makanan/minuman, transaksi pesanan, status meja pelanggan, sampai akun login untuk kasir, dapur, admin, dan owner.

Pengerjaan database ini kami buat bertahap dari **Versi 1 (V1)**, lanjut ke **Versi 2 (V2)**, sampai versi paling baru dan stabil yaitu **Versi 3 (V3)**.

---

## 2. Catatan Perkembangan (Versi 1 sampai Versi 3)

### 🔹 Versi 1 (V1 - Awal Bikin Projek)
Pada awal pengerjaan, kami fokus bikin tabel-tabel utama dulu biar aplikasi kasir dan katalog menu bisa jalan secara dasar.
- **Tabel yang dibuat:**
  1. `users` : Akun admin untuk login dasar.
  2. `categories` : Kategori pemisah antara Makanan dan Minuman.
  3. `menus` : Daftar nama menu, harga, deskripsi, dan foto menu.
  4. `orders` : Catatan transaksi pesanan pelanggan.
  5. `order_items` : Detail rincian pesanan (misal: 2 porsi sate kambing, 1 es teh).
- **Catatan & Kekurangan V1:**
  - Belum ada sistem nomor meja yang otomatis (masih ketik manual).
  - Belum ada setting rekening pembayaran QRIS.
  - Role user baru 1 macam (hanya admin).

---

### 🔹 Versi 2 (V2 - Tambah Fitur Meja & Multi-Role)
Setelah dites bersama tim frontend dan backend, ada kebutuhan fitur baru di restoran, jadi kami lakukan revisi:
- **Perubahan yang dikerjakan:**
  1. **Nambah tabel `tables`**: Buat nyimpen Meja 01 sampai Meja 10, status meja (kosong / sedang dipakai), dan link QR Code meja.
  2. **Nambah tabel `settings`**: Buat nyimpen nama rekening, nomor QRIS/E-Wallet, dan info resto biar gampang diubah lewat halaman admin.
  3. **Update tabel `users`**: Ditambah kolom `role` (Admin, Kasir, Dapur, Owner) dan kolom `username` biar kasir dan koki dapur bisa login gampang tanpa harus ngetik email panjang.
  4. **Nambah Seeder Menu Lengkap**: Mengisi 15 menu asli Depot Sate Be Ba Lung (Sate Kambing, Tongseng, Gulai, Sop Kambing, Nasi Gurih, Es Jeruk, Teh Poci, dll).

---

### 🔹 Versi 3 (V3 - Versi Sekarang / Final Siap Pakai)
Di versi 3 ini, struktur database disempurnakan lagi agar performanya enteng dan tidak error saat di-hosting ke internet (cPanel bebalung.my.id).
- **Penyempurnaan di V3:**
  1. **Optimasi Indexing**: Kolom yang sering dicari seperti `menus.is_available`, `orders.order_status`, dan `tables.table_number` diberi index biar pencarian data cepat.
  2. **Jumlah Meja Lengkap**: Data meja di-seed lengkap jadi 20 meja (Meja 01 s/d Meja 20).
  3. **Tipe Data Uang Akurat**: Semua kolom harga dan total bayar memakai `DECIMAL(12,2)` agar hitungan uang rupiah tidak selisih/desimal error.
  4. **Export SQL Bersih**: File SQL sudah dites di phpMyAdmin XAMPP lokal maupun phpMyAdmin cPanel dan berhasil di-import sekali klik tanpa bentrok foreign key.

---

## 3. Rangkuman Tabel Database Final (V3)

| No | Nama Tabel | Fungsi Utama | Keterangan Relasi |
|:---|:---|:---|:---|
| 1 | `users` | Akun pengguna (Admin, Kasir, Dapur, Owner) | Primary Key: `id` |
| 2 | `categories` | Kategori menu (Makanan & Minuman) | Relasi (1:N) ke `menus` |
| 3 | `menus` | Katalog menu, harga, badge, dan foto | Foreign Key: `category_id` |
| 4 | `tables` | Status ketersediaan meja 01-20 & QR | Unique: `table_number` |
| 5 | `settings` | Pengaturan dinamis QRIS & Profil depot | Unique: `key` |
| 6 | `orders` | Data transaksi nota belanja pelanggan | Relasi (1:N) ke `order_items` |
| 7 | `order_items` | Rincian porsi & menu yang dipesan | Foreign Key: `order_id` & `menu_id` |

---

## 4. Akun Pengguna Bawaan (Default Seeder)
Untuk keperluan uji coba dan login penguji:
- **Admin**: `admin` / `admin123` (atau password: `password`)
- **Kasir**: `kasir` / `password`
- **Dapur**: `dapur` / `password`
- **Owner**: `owner` / `password`

---

## 5. Kesimpulan
Perancangan database Depot Sate Be Ba Lung sudah selesai dari tahap V1 sampai V3. Database ini sudah memiliki struktur tabel normal, relasi foreign key yang aman (`cascade delete`), seeder data menu dan meja yang komplit, serta siap digunakan untuk proses transaksi kasir maupun pemesanan scan QR di meja pelanggan.
