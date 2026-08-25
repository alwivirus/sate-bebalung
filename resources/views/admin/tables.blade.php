@extends('layouts.admin')

@section('title', 'Cetak QR Code Standee Meja - Admin Be Ba Lung')
@section('page-title', 'Kelola & Cetak QR Code Meja (Sistem Gacoan)')

@section('styles')
<style>
    .tables-toolbar {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 18px 22px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .table-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 22px;
    }

    /* Luxury Standee Meja Card */
    .table-standee-card {
        background: #FFFFFF;
        border: 2.5px solid #111827;
        border-radius: 20px;
        box-shadow: 4px 4px 0px #111827;
        padding: 20px 16px;
        text-align: center;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        overflow: hidden;
    }

    .table-standee-card::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        right: 6px;
        bottom: 6px;
        border: 1.5px solid #D97706;
        border-radius: 14px;
        pointer-events: none;
    }

    .table-standee-card:hover {
        transform: translateY(-4px);
        box-shadow: 7px 7px 0px #EA580C;
    }

    .standee-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 10px;
        width: 100%;
    }

    .standee-logo-mini {
        width: 32px;
        height: 32px;
        background: #111827;
        border-radius: 8px;
        border: 1.5px solid #F59E0B;
        padding: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .standee-logo-mini img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standee-title {
        font-size: 0.95rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: 0.5px;
        line-height: 1.1;
    }

    .standee-sub {
        font-size: 0.65rem;
        font-weight: 800;
        color: #EA580C;
        text-transform: uppercase;
    }

    .standee-badge {
        background: #111827;
        color: #FCD34D;
        border: 2px solid #F59E0B;
        border-radius: 10px;
        padding: 6px 16px;
        font-weight: 900;
        font-size: 1.25rem;
        letter-spacing: 1.5px;
        display: inline-block;
        margin: 6px 0 10px 0;
        box-shadow: 2px 2px 0px rgba(0,0,0,0.15);
    }

    .standee-qr-wrapper {
        width: 160px;
        height: 160px;
        background: white;
        border: 2px solid #111827;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 10px;
        box-shadow: 2px 2px 0px #E5E7EB;
    }

    .standee-qr-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standee-click-hint {
        font-size: 0.72rem;
        font-weight: 800;
        color: #4F46E5;
        background: #EEF2FF;
        padding: 4px 10px;
        border-radius: 6px;
        margin-bottom: 10px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .standee-actions {
        width: 100%;
        display: flex;
        gap: 6px;
        margin-top: auto;
    }

    .btn-action-small {
        flex: 1;
        background: #111827;
        color: white;
        border: 1.5px solid #111827;
        border-radius: 8px;
        padding: 8px 4px;
        font-size: 0.75rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: background 0.15s;
    }

    .btn-action-small:hover {
        background: #374151;
    }

    /* Modal Backdrop */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(17, 24, 39, 0.85);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-card {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 440px;
        padding: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        position: relative;
        text-align: center;
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Print Formatting */
    @media print {
        body {
            background: white !important;
        }
        .sidebar, .top-navbar, .tables-toolbar, .standee-actions, .standee-click-hint, .modal-overlay, .btn-primary, .info-alert-box {
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
            page-break-inside: avoid !important;
        }
        .table-standee-card::before {
            border-color: #000 !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Toolbar -->
<div class="tables-toolbar">
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-qrcode" style="color: #EA580C;"></i> Standee Meja QR Code (Sistem Gacoan)
        </h3>
        <p style="font-size: 0.82rem; color: #6B7280; margin: 0;">
            Klik kartu meja untuk melihat preview standee akrilik, mencetak kartu per meja, atau menguji simulasi pelanggan.
        </p>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Filter Jumlah Meja -->
        <form action="{{ route('admin.tables.index') }}" method="GET" style="display: flex; align-items: center; gap: 6px; margin: 0;">
            <span style="font-size: 0.82rem; font-weight: 800; color: #374151;">Jumlah Meja:</span>
            <select name="count" onchange="this.form.submit()" style="padding: 7px 12px; border: 1.5px solid #D1D5DB; border-radius: 8px; font-weight: 800; font-size: 0.85rem; background: #F9FAFB;">
                <option value="10" {{ $tableCount == 10 ? 'selected' : '' }}>10 Meja</option>
                <option value="20" {{ $tableCount == 20 ? 'selected' : '' }}>20 Meja (Standar)</option>
                <option value="30" {{ $tableCount == 30 ? 'selected' : '' }}>30 Meja</option>
                <option value="50" {{ $tableCount == 50 ? 'selected' : '' }}>50 Meja</option>
            </select>
        </form>

        <button type="button" onclick="window.print()" class="btn-primary" style="padding: 9px 18px; font-size: 0.88rem; background: #111827;">
            <i class="fa-solid fa-print"></i> Cetak Semua Meja (Batch Print)
        </button>
    </div>
</div>

<!-- Info Alert -->
<div class="info-alert-box" style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #1E40AF; font-size: 0.82rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div>
        <i class="fa-solid fa-lightbulb" style="color: #2563EB;"></i>
        <strong>Petunjuk Kasir / Owner:</strong> Anda bisa <strong>mengklik langsung kartu meja</strong> mana saja untuk mencetak 1 standee akrilik spesifik atau mencoba pesan makanan sebagai meja tersebut.
    </div>
    <span style="background: #DBEAFE; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800;">
        {{ $occupiedCount }} Meja Sedang Digunakan
    </span>
</div>

<!-- Standee Cards Grid -->
<div class="table-cards-grid">
    @foreach($tables as $table)
        <div class="table-standee-card" onclick="openTableModal('{{ $table['number'] }}', '{{ $table['scan_url'] }}', '{{ $table['qr_image'] }}', '{{ $table['status'] }}', '{{ addslashes($table['customer_name'] ?? '') }}')">
            <div class="standee-header">
                <div class="standee-logo-mini">
                    <img src="{{ asset('images/logo-goat.png') }}" alt="Logo">
                </div>
                <div>
                    <div class="standee-title">BE BA LUNG</div>
                    <div class="standee-sub">SATE &bull; GULAI &bull; TONGSENG</div>
                </div>
            </div>

            <div class="standee-badge">
                MEJA #{{ $table['number'] }}
            </div>

            <div class="standee-qr-wrapper">
                <img src="{{ $table['qr_image'] }}" alt="QR Meja {{ $table['number'] }}">
            </div>

            <div class="standee-click-hint">
                <i class="fa-solid fa-hand-pointer"></i> Klik Untuk Opsi / Cetak HD
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
                    TERSEDIA (KOSONG)
                </div>
            @endif

            <div class="standee-actions" onclick="event.stopPropagation();">
                <a href="{{ route('admin.tables.print-single', $table['number']) }}" target="_blank" class="btn-action-small" style="background: #F59E0B; color: #111827; border-color: #D97706;" title="Cetak Standee Akrilik Meja Ini">
                    <i class="fa-solid fa-print"></i> Cetak HD
                </a>
                <a href="{{ $table['scan_url'] }}" target="_blank" class="btn-action-small" title="Uji Coba Pesan Sebagai Meja Ini">
                    <i class="fa-solid fa-mobile-screen"></i> Uji Meja
                </a>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal Detail Standee Meja Interaktif -->
<div class="modal-overlay" id="tableModal" onclick="closeTableModal(event)">
    <div class="modal-card" onclick="event.stopPropagation();">
        <button type="button" onclick="closeTableModal()" style="position: absolute; top: 16px; right: 16px; background: #F3F4F6; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div style="margin-bottom: 16px;">
            <span style="background: #111827; color: #FCD34D; font-size: 1.3rem; font-weight: 900; padding: 6px 18px; border-radius: 10px; display: inline-block;" id="modalTableNumber">
                MEJA #01
            </span>
        </div>

        <div style="width: 180px; height: 180px; margin: 0 auto 16px auto; background: white; border: 2.5px solid #111827; border-radius: 14px; padding: 8px;">
            <img id="modalQrImage" src="" alt="QR Code" style="width: 100%; height: 100%; object-fit: contain;">
        </div>

        <div style="background: #F9FAFB; border: 1.5px solid #E5E7EB; border-radius: 12px; padding: 12px; margin-bottom: 18px; font-size: 0.82rem; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span style="color: #6B7280;">Status Meja:</span>
                <strong id="modalTableStatus">Tersedia</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span style="color: #6B7280;">Pelanggan:</span>
                <strong id="modalCustomerName">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6B7280;">Link Scan:</span>
                <a id="modalScanLink" href="#" target="_blank" style="color: #EA580C; font-weight: 700; text-decoration: underline; font-size: 0.75rem;">Buka Link</a>
            </div>
        </div>

        <!-- Tombol Aksi Modal -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <a id="modalBtnPrint" href="#" target="_blank" class="btn-primary" style="background: #F59E0B; color: #111827; justify-content: center; font-weight: 900; font-size: 0.95rem; padding: 12px;">
                <i class="fa-solid fa-print"></i> Cetak Standee Akrilik Meja Ini (Print HD)
            </a>

            <a id="modalBtnTest" href="#" target="_blank" class="btn-primary" style="background: #111827; color: white; justify-content: center; font-size: 0.9rem; padding: 10px;">
                <i class="fa-solid fa-mobile-screen"></i> Buka Menu Pelanggan (Uji Meja Ini)
            </a>

            <div id="modalReleaseFormWrapper"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTableModal(number, scanUrl, qrImage, status, customerName) {
        document.getElementById('modalTableNumber').innerText = 'MEJA #' + number;
        document.getElementById('modalQrImage').src = qrImage;
        
        const statusEl = document.getElementById('modalTableStatus');
        const customerEl = document.getElementById('modalCustomerName');
        const releaseWrapper = document.getElementById('modalReleaseFormWrapper');

        if (status === 'occupied') {
            statusEl.innerHTML = '<span style="color: #D97706;"><i class="fa-solid fa-circle"></i> Sedang Digunakan</span>';
            customerEl.innerText = customerName || 'Pelanggan Aktif';
            releaseWrapper.innerHTML = `
                <form action="/admin/tables/${number}/release" method="POST" style="margin-top: 4px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" style="width: 100%; background: #FEE2E2; color: #991B1B; border: 1.5px solid #FCA5A5; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                        <i class="fa-solid fa-rotate-left"></i> Kosongkan Meja Ini
                    </button>
                </form>
            `;
        } else {
            statusEl.innerHTML = '<span style="color: #059669;"><i class="fa-solid fa-circle-check"></i> Kosong / Tersedia</span>';
            customerEl.innerText = 'Belum Ada';
            releaseWrapper.innerHTML = '';
        }

        document.getElementById('modalScanLink').href = scanUrl;
        document.getElementById('modalScanLink').innerText = scanUrl;
        document.getElementById('modalBtnPrint').href = '/admin/tables/' + number + '/print';
        document.getElementById('modalBtnTest').href = scanUrl;

        document.getElementById('tableModal').classList.add('active');
    }

    function closeTableModal(event) {
        if (!event || event.target.id === 'tableModal' || event.target.closest('button')) {
            document.getElementById('tableModal').classList.remove('active');
        }
    }
</script>
@endsection
