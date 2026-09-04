# CARA PEMASANGAN / IMPORT DATABASE
## Depot Sate Be Ba Lung

Ada 2 cara yang bisa dipilih untuk memasang database ini:

---

### Cara 1: Menggunakan phpMyAdmin (Paling Cepat / Tinggal Klik)

1. Pastikan **XAMPP** sudah dibuka dan service **Apache** & **MySQL** sudah kondisi `Start`.
2. Buka browser (Chrome / Edge), lalu buka link: `http://localhost/phpmyadmin`.
3. Klik tombol **New** / **Baru** di sebelah kiri untuk membuat database baru.
4. Beri nama database: `sate_bebalung`, lalu klik tombol **Create** / **Buat**.
5. Klik nama database `sate_bebalung` yang baru dibuat di menu kiri.
6. Klik tab menu **Import** di bagian atas.
7. Klik tombol **Choose File** (Pilih Berkas), lalu pilih file SQL:
   - File rekomendasi (Versi 3): `Versi_3_Final_Siap_Pakai/database_bebalung_v3_final.sql`
8. Scroll ke bawah dan klik tombol **Import** (atau **Go**).
9. Selesai! Semua tabel dan data menu/meja/user sudah otomatis masuk.

---

### Cara 2: Menggunakan Terminal Laravel (Artisan Migrate)

Jika ingin menjalankan migrasi dan seeder langsung lewat source code Laravel:
1. Buka file `.env` di folder utama projek, pastikan konfigurasinya:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sate_bebalung
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Buka Terminal / CMD di folder projek, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```
3. Terminal akan menjalankan seluruh file migrasi dan otomatis mengisi data awal (Menu, Kategori, Meja, dan Akun User).

---

### Info Akun Default untuk Login:
- **Admin**: `username: admin` | `password: admin123` (atau `password`)
- **Kasir**: `username: kasir` | `password: password`
- **Dapur**: `username: dapur` | `password: password`
- **Owner**: `username: owner` | `password: password`
