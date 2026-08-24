@extends('layouts.admin')

@section('title', 'Developer & Master Tools - Be Ba Lung')
@section('page-title', 'Developer & Master Tools Panel')

@section('styles')
<style>
    .dev-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .dev-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .dev-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #F3F4F6;
    }

    .dev-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .dev-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .dev-stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #E5E7EB;
        font-size: 0.85rem;
    }

    .dev-stat-row:last-child {
        border-bottom: none;
    }

    .dev-badge-green {
        background: #D1FAE5;
        color: #065F46;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .dev-badge-red {
        background: #FEE2E2;
        color: #991B1B;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .dev-btn {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.88rem;
        cursor: pointer;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s;
        text-decoration: none;
        margin-top: 10px;
    }

    .dev-btn-danger {
        background: #DC2626;
        color: white;
    }

    .dev-btn-danger:hover {
        background: #B91C1C;
    }

    .dev-btn-primary {
        background: #111827;
        color: white;
    }

    .dev-btn-primary:hover {
        background: #374151;
    }

    .dev-btn-warning {
        background: #D97706;
        color: white;
    }

    .dev-btn-warning:hover {
        background: #B45309;
    }

    .form-group-dev {
        margin-bottom: 14px;
    }

    .form-group-dev label {
        display: block;
        font-size: 0.8rem;
        font-weight: 800;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-group-dev input, .form-group-dev textarea {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid #D1D5DB;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<!-- Notice Banner -->
<div style="background: #312E81; color: white; border-radius: 14px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; box-shadow: 0 4px 14px rgba(49, 46, 129, 0.2);">
    <div>
        <h3 style="font-size: 1.15rem; font-weight: 900; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-terminal" style="color: #A5B4FC;"></i> Developer Master Console &amp; Testing Mode
        </h3>
        <p style="font-size: 0.82rem; color: #C7D2FE; margin: 0; max-width: 600px;">
            Panel kontrol khusus developer / pemilik untuk membersihkan data testing, menghapus riwayat transaksi uji coba, sinkronisasi database, dan mengelola konfigurasi aplikasi.
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <span style="background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; font-family: monospace;">
            PHP {{ $serverInfo['php_version'] }} &bull; Laravel {{ $serverInfo['laravel_version'] }}
        </span>
    </div>
</div>

<div class="dev-grid">
    <!-- Card 1: Manajemen Data Testing (Reset & Hapus Aktivitas) -->
    <div class="dev-card" style="border-top: 4px solid #DC2626;">
        <div class="dev-card-header">
            <div class="dev-card-icon" style="background: #FEE2E2; color: #DC2626;">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div>
                <h4 class="dev-card-title">Reset &amp; Hapus Data Uji Coba</h4>
                <span style="font-size: 0.72rem; color: #6B7280;">Hapus semua riwayat transaksi untuk persiapan client</span>
            </div>
        </div>

        <div style="flex: 1;">
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Total Pesanan di Database:</span>
                <strong>{{ $stats['total_orders'] }} Pesanan</strong>
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Total Transaksi Lunas:</span>
                <strong style="color: #059669;">{{ $stats['paid_orders'] }} Transaksi</strong>
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Total Omset Tersimpan:</span>
                <strong style="color: #059669;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</strong>
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Status 20 Meja Makan:</span>
                <strong>{{ $stats['occupied_tables'] }} Terpakai / {{ $stats['total_tables'] }} Total</strong>
            </div>

            <p style="font-size: 0.75rem; color: #6B7280; line-height: 1.4; margin-top: 12px; background: #FFFBEB; padding: 8px 10px; border-radius: 8px; border: 1px solid #FCD34D;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #D97706;"></i>
                Tombol di bawah akan mengosongkan semua riwayat pesanan testing dan mereset status 20 meja menjadi kosong bersih. (Daftar Menu &amp; Akun Admin tetap aman).
            </p>
        </div>

        <form action="{{ route('admin.developer.clear-orders') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA data transaksi & aktivitas testing? Seluruh pesanan akan terhapus dan 20 meja akan dikosongkan.');">
            @csrf
            <button type="submit" class="dev-btn dev-btn-danger">
                <i class="fa-solid fa-broom"></i> Bersihkan Semua Transaksi Testing
            </button>
        </form>
    </div>

    <!-- Card 2: Sinkronisasi Database & Cache Tools -->
    <div class="dev-card" style="border-top: 4px solid #4F46E5;">
        <div class="dev-card-header">
            <div class="dev-card-icon" style="background: #EEF2FF; color: #4F46E5;">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <h4 class="dev-card-title">Database &amp; System Cache</h4>
                <span style="font-size: 0.72rem; color: #6B7280;">Auto-repair tabel &amp; flush memory</span>
            </div>
        </div>

        <div style="flex: 1;">
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Koneksi Database:</span>
                <span class="dev-badge-green"><i class="fa-solid fa-check"></i> MySQL Terhubung</span>
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Database Name:</span>
                <strong style="font-family: monospace; font-size: 0.8rem;">{{ $serverInfo['db_name'] }}</strong>
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Storage Writable:</span>
                @if($serverInfo['storage_writable'])
                    <span class="dev-badge-green"><i class="fa-solid fa-check"></i> OK (Writable)</span>
                @else
                    <span class="dev-badge-red">Read Only</span>
                @endif
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Uploads Folder:</span>
                @if($serverInfo['uploads_writable'])
                    <span class="dev-badge-green"><i class="fa-solid fa-check"></i> OK (Writable)</span>
                @else
                    <span class="dev-badge-red">Read Only</span>
                @endif
            </div>
            <div class="dev-stat-row">
                <span style="color: #4B5563;">Total Menu Aktif:</span>
                <strong>{{ $stats['total_menus'] }} Menu (2 Kategori)</strong>
            </div>
        </div>

        <div style="display: flex; gap: 8px; margin-top: 10px;">
            <form action="{{ route('admin.developer.sync-db') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="dev-btn dev-btn-primary" style="margin-top: 0;">
                    <i class="fa-solid fa-rotate"></i> Sync Database
                </button>
            </form>

            <form action="{{ route('admin.developer.clear-cache') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="dev-btn dev-btn-warning" style="margin-top: 0;">
                    <i class="fa-solid fa-bolt"></i> Flush Cache
                </button>
            </form>
        </div>
    </div>

    <!-- Card 3: Konfigurasi Global & Identitas Toko -->
    <div class="dev-card" style="border-top: 4px solid #10B981;">
        <div class="dev-card-header">
            <div class="dev-card-icon" style="background: #D1FAE5; color: #10B981;">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div>
                <h4 class="dev-card-title">Pengaturan Cepat Toko</h4>
                <span style="font-size: 0.72rem; color: #6B7280;">Nama resto, alamat, dan kontak</span>
            </div>
        </div>

        <form action="{{ route('admin.developer.update-settings') }}" method="POST">
            @csrf
            <div class="form-group-dev">
                <label>Nama Depot / Resto</label>
                <input type="text" name="resto_name" value="{{ $settings['resto_name'] }}" required>
            </div>

            <div class="form-group-dev">
                <label>Alamat Lengkap</label>
                <input type="text" name="resto_address" value="{{ $settings['resto_address'] }}" required>
            </div>

            <div class="form-group-dev">
                <label>Nomor WhatsApp Toko</label>
                <input type="text" name="resto_phone" value="{{ $settings['resto_phone'] }}" required>
            </div>

            <div class="form-group-dev">
                <label>Nama Merchant QRIS</label>
                <input type="text" name="qris_merchant_name" value="{{ $settings['qris_merchant_name'] }}" required>
            </div>

            <button type="submit" class="dev-btn dev-btn-primary" style="background: #059669;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>

<!-- Akses Cepat Navigasi -->
<div style="background: white; border: 1px solid var(--border-color); border-radius: 14px; padding: 20px;">
    <h4 style="font-size: 0.95rem; font-weight: 800; color: #111827; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-compass" style="color: #4F46E5;"></i> Pintasan Uji Coba Cepat (Testing Links)
    </h4>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.activity-logs') }}" class="dev-btn dev-btn-primary" style="width: auto; padding: 8px 14px; font-size: 0.8rem; margin: 0;">
            <i class="fa-solid fa-list-check"></i> Buka Catatan Aktivitas &amp; Hapus Satuan
        </a>
        <a href="{{ route('admin.tables.index') }}" class="dev-btn dev-btn-primary" style="width: auto; padding: 8px 14px; font-size: 0.8rem; margin: 0; background: #4F46E5;">
            <i class="fa-solid fa-qrcode"></i> Panel Uji Seluruh Meja (1-20)
        </a>
        <a href="{{ route('customer.menu', ['meja' => '01']) }}" target="_blank" class="dev-btn dev-btn-primary" style="width: auto; padding: 8px 14px; font-size: 0.8rem; margin: 0; background: #D97706;">
            <i class="fa-solid fa-mobile-screen"></i> Buka Layar Pelanggan Meja 1
        </a>
        <a href="{{ route('admin.settings.qris') }}" class="dev-btn dev-btn-primary" style="width: auto; padding: 8px 14px; font-size: 0.8rem; margin: 0; background: #059669;">
            <i class="fa-solid fa-image"></i> Kelola Foto QRIS Toko
        </a>
    </div>
</div>
@endsection
