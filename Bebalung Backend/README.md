# ⚙️ TUGAS 2: BACKEND 1 (TRANSAKSI & ORDER SYSTEM)
**Projek:** Sistem Kasir & Pemesanan QR Menu Depot Sate Be Ba Lung

---

## 📌 Tanggung Jawab Peran:
1. Mengelola alur pemesanan makanan (*Order Lifecycle*): Tambah menu, simpan session meja, checkout pesanan.
2. Menangani logika kalkulasi total pembayaran, diskon, dan pembuatan kode pesanan unik.
3. Menyediakan endpoint API polling status pesanan untuk pelanggan & kasir dapur.
4. Menjaga integritas relasi tabel `orders`, `order_items`, dan `menus`.

---

## 📂 Struktur File Tugas Backend 1:
```
2_BACKEND_1/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── OrderController.php  # Alur utama order, checkout, & simpan transaksi
│   │       ├── ApiController.php    # Live polling status pesanan & data API
│   │       └── Controller.php
│   └── Models/
│       ├── Order.php                # Model data transaksi pesanan
│       ├── OrderItem.php            # Model detail rincian menu per order
│       └── Menu.php                 # Model data produk menu
└── routes/
    ├── web.php                      # Routing alur pemesanan customer
    └── api.php                      # Routing API live polling
```

---

## 🚀 Panduan Push ke GitHub:
1. Buat branch baru untuk Backend 1:
   ```bash
   git checkout -b feature/backend-order-transaction
   ```
2. Tambahkan dan commit perubahan:
   ```bash
   git add app/Http/Controllers/OrderController.php app/Http/Controllers/ApiController.php routes/web.php
   git commit -m "feat(backend): implement order calculation, cart validation and live transaction API"
   ```
3. Push branch ke repository GitHub:
   ```bash
   git push -u origin feature/backend-order-transaction
   ```
4. Buat **Pull Request (PR)** di GitHub ke branch `main`.
