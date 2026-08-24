@extends('layouts.admin')

@section('title', 'Cetak QR Code Meja (Gacoan System) - Admin Be Ba Lung')
@section('page-title', 'Kelola & Cetak QR Code Meja')

@section('styles')
<style>
    .tables-toolbar {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }

    /* Table QR Card Standee */
    .table-standee-card {
        background: #FFFFFF;
        border: 3px solid #111827;
        border-radius: 18px;
        box-shadow: 4px 4px 0px #111827;
        padding: 18px 14px;
        text-align: center;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .table-standee-card:hover {
        transform: translateY(-3px);
        box-shadow: 6px 6px 0px #111827;
    }

    .standee-header {
        margin-bottom: 12px;
        width: 100%;
    }

    .standee-brand {
        font-size: 0.75rem;
        font-weight: 900;
        letter-spacing: 1px;
        color: #EA580C;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .standee-title {
        font-size: 0.95rem;
        font-weight: 900;
        color: #111827;
        line-height: 1.2;
    }

    .standee-badge {
        background: #FBBF24;
        color: #111827;
        border: 2px solid #111827;
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 900;
        font-size: 1.15rem;
        letter-spacing: 1px;
        display: inline-block;
        margin: 10px 0;
        box-shadow: 2px 2px 0px #111827;
    }

    .standee-qr-wrapper {
        width: 160px;
        height: 160px;
        background: white;
        border: 2px solid #111827;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 12px;
        box-shadow: 2px 2px 0px #E5E7EB;
    }

    .standee-qr-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standee-instruction {
        font-size: 0.75rem;
        font-weight: 800;
        color: #374151;
        line-height: 1.3;
        margin-bottom: 14px;
        background: #FEF3C7;
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px dashed #D97706;
    }

    .standee-actions {
        width: 100%;
        display: flex;
        gap: 6px;
    }

    .btn-test-table {
        flex: 1;
        background: #111827;
        color: white;
        border: 2px solid #111827;
        border-radius: 8px;
        padding: 8px 6px;
        font-size: 0.75rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: background 0.15s;
    }

    .btn-test-table:hover {
        background: #374151;
    }

    /* Print Format */
    @media print {
        body {
            background: white !important;
        }
        .sidebar, .top-navbar, .tables-toolbar, .standee-actions, .btn-primary {
            display: none !important;
        }
        .main-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }
        .table-cards-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 16px !important;
        }
        .table-standee-card {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<!-- Toolbar -->
<div class="tables-toolbar">
    <div>
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #111827; margin-bottom: 4px;">
            <i class="fa-solid fa-qrcode" style="color: #EA580C;"></i> QR Code Meja Pelanggan (Sistem Gacoan)
        </h3>
        <p style="font-size: 0.8rem; color: #6B7280; margin: 0;">
            Pelanggan men-scan QR code ini di meja untuk membuka menu, memilih pesanan, dan memesan langsung dari smartphone mereka.
        </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Filter Jumlah Meja -->
        <form action="{{ route('admin.tables.index') }}" method="GET" style="display: flex; align-items: center; gap: 6px; margin: 0;">
            <span style="font-size: 0.82rem; font-weight: 800; color: #374151;">Jumlah Meja:</span>
            <select name="count" onchange="this.form.submit()" style="padding: 6px 10px; border: 1.5px solid #D1D5DB; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">
                <option value="10" {{ $tableCount == 10 ? 'selected' : '' }}>10 Meja</option>
                <option value="20" {{ $tableCount == 20 ? 'selected' : '' }}>20 Meja</option>
                <option value="30" {{ $tableCount == 30 ? 'selected' : '' }}>30 Meja</option>
                <option value="50" {{ $tableCount == 50 ? 'selected' : '' }}>50 Meja</option>
            </select>
        </form>

        <button type="button" onclick="window.print()" class="btn-primary" style="padding: 8px 16px; font-size: 0.88rem;">
            <i class="fa-solid fa-print"></i> Cetak Stiker / Standee Meja
        </button>
    </div>
</div>

<!-- Info Alert -->
<div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #1E40AF; font-size: 0.82rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div>
        <i class="fa-solid fa-circle-info" style="color: #2563EB;"></i>
        <strong>Cara Kerja:</strong> Saat meja di-scan menggunakan Kamera HP / Google Lens / Barcode scanner, halaman menu otomatis mengunci Nomor Meja dan seluruh pesanan yang dibuat pelanggan akan terkirim dengan nomor meja tersebut.
    </div>
    <span style="background: #DBEAFE; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800;">
        Base URL: {{ $baseUrl }}
    </span>
</div>

<!-- Standee Cards Grid -->
<div class="table-cards-grid">
    @foreach($tables as $table)
        <div class="table-standee-card">
            <div class="standee-header">
                <div class="standee-brand">DEPOT SATE &amp; GULAI</div>
                <div class="standee-title">BE BA LUNG</div>
            </div>

            <div class="standee-badge">
                MEJA #{{ $table['number'] }}
            </div>

            <div class="standee-qr-wrapper">
                <img src="{{ $table['qr_image'] }}" alt="QR Meja {{ $table['number'] }}">
            </div>

            <div class="standee-instruction">
                <i class="fa-solid fa-camera"></i> SCAN UNTUK LIHAT MENU &amp; PESAN DARI HP
            </div>

            <!-- Live Status Meja Terhubung -->
            @if($table['status'] === 'occupied')
                <div style="background: #FFFBEB; border: 1.5px solid #F59E0B; color: #92400E; font-size: 0.72rem; font-weight: 800; padding: 4px 8px; border-radius: 6px; margin-bottom: 10px; width: 100%; text-align: center;">
                    <i class="fa-solid fa-circle" style="color: #EA580C; font-size: 0.6rem;"></i> 
                    SEDANG DIGUNAKAN
                    <div style="font-size: 0.68rem; color: #4B5563; font-weight: 700; margin-top: 2px;">
                        {{ $table['customer_name'] ?: 'Pelanggan Aktif' }}
                    </div>
                </div>
            @else
                <div style="background: #F0FDF4; border: 1px solid #86EFAC; color: #166534; font-size: 0.72rem; font-weight: 800; padding: 4px 8px; border-radius: 6px; margin-bottom: 10px; width: 100%; text-align: center;">
                    <i class="fa-solid fa-circle-check" style="color: #10B981; font-size: 0.6rem;"></i> 
                    MEJA KOSONG (TERSEDIA)
                </div>
            @endif

            @if($table['active_orders_count'] > 0)
                <div style="background: #FEE2E2; color: #991B1B; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 6px; margin-bottom: 10px; width: 100%;">
                    <i class="fa-solid fa-bell"></i> {{ $table['active_orders_count'] }} Pesanan Aktif
                </div>
            @endif

            <div class="standee-actions">
                <a href="{{ $table['scan_url'] }}" target="_blank" class="btn-test-table" title="Buka Simulasi Scan Meja">
                    <i class="fa-solid fa-mobile-screen"></i> Uji Meja Ini
                </a>

                @if($table['status'] === 'occupied')
                    <form action="{{ route('admin.tables.release', $table['number']) }}" method="POST" style="flex: 1; margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; height: 100%; background: #FEE2E2; color: #991B1B; border: 1.5px solid #FCA5A5; border-radius: 8px; font-size: 0.75rem; font-weight: 800; padding: 8px 4px; cursor: pointer;">
                            <i class="fa-solid fa-rotate-left"></i> Kosongkan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
