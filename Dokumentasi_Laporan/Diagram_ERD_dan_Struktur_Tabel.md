# DIAGRAM ERD & STRUKTUR TABEL DATABASE
## Sistem Kasir & Pemesanan Depot Sate Be Ba Lung

---

### 1. Diagram Relasi Entitas (ERD)

```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar name "Nama Pengguna"
        varchar username UK "Username Login"
        varchar email UK "Email Login"
        varchar role "admin / kasir / dapur / owner"
        varchar password "Password Hash"
    }

    CATEGORIES {
        bigint id PK
        varchar name "Nama Kategori (Makanan/Minuman)"
        varchar slug UK "Slug URL"
        varchar icon "Icon FontAwesome"
        int sort_order "Urutan"
    }

    MENUS {
        bigint id PK
        bigint category_id FK "Relasi ke Categories"
        varchar name "Nama Menu"
        varchar slug UK "Slug Menu"
        text description "Deskripsi Menu"
        decimal price "Harga Jual (Rp)"
        varchar image "Path Gambar Menu"
        varchar badge "Badge (Best Seller/Favorit)"
        tinyint is_available "Status Tersedia (1/0)"
    }

    TABLES {
        bigint id PK
        varchar table_number UK "Nomor Meja (01-20)"
        enum status "available / occupied"
        varchar current_customer_name "Nama Pemesan Saat Ini"
        varchar current_order_code "Kode Order Aktif"
    }

    SETTINGS {
        bigint id PK
        varchar key UK "Kunci Pengaturan"
        text value "Isi Nilai Pengaturan"
    }

    ORDERS {
        bigint id PK
        varchar order_code UK "Nomor Nota Unik"
        varchar customer_name "Nama Pembeli"
        varchar table_number "Nomor Meja"
        varchar payment_method "cash / online"
        varchar payment_status "unpaid / paid"
        varchar order_status "pending / cooking / ready / completed"
        decimal total_amount "Total Belanja"
        varchar payment_proof "Bukti Transfer / QRIS"
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK "Relasi ke Orders"
        bigint menu_id FK "Relasi ke Menus"
        int quantity "Jumlah Porsi"
        decimal price "Harga Satuan"
        decimal subtotal "Subtotal (Qty x Harga)"
        varchar notes "Catatan Tambahan"
    }

    CATEGORIES ||--o{ MENUS : "memiliki"
    ORDERS ||--|{ ORDER_ITEMS : "memuat detail"
    MENUS ||--o{ ORDER_ITEMS : "dipesan"
```

---

### 2. Penjelasan Relasi Antar Tabel:
1. **`categories` ke `menus` (One to Many)**:
   - Satu kategori (misal: "Menu Makanan") bisa memiliki banyak item menu (Sate, Tongseng, Sop, dll).
2. **`orders` ke `order_items` (One to Many)**:
   - Satu nomor transaksi order bisa memiliki banyak baris item pesanan. Jika order dihapus, item rinciannya ikut terhapus (*Cascade on Delete*).
3. **`menus` ke `order_items` (One to Many)**:
   - Satu jenis menu dapat dipesan berulang kali di berbagai transaksi order pelanggan.
