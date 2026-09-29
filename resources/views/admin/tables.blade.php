@extends('layouts.admin')

@section('title', 'QR MEJA - Admin Be Ba Lung')
@section('page-title', 'QR MEJA')

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

    /* Dynamic Bebalung Standee Meja Card */
    .table-standee-card {
        background: linear-gradient(180deg, #FFF9F5 0%, #FFFFFF 30%, #FFF8F3 100%);
        border: 2px solid #EA580C;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.08);
        padding: 10px 10px 8px 10px;
        text-align: left;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
        color: #0F172A;
    }

    .table-standee-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(234, 88, 12, 0.22);
        border-color: #C2410C;
    }

    .card-brand-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 4px;
        border-bottom: 1px dashed #FDBA74;
        margin-bottom: 4px;
    }

    .card-brand-title {
        font-size: 0.74rem;
        font-weight: 900;
        color: #0F172A;
        line-height: 1;
    }

    .card-brand-sub {
        font-size: 0.42rem;
        font-weight: 800;
        color: #EA580C;
        text-transform: uppercase;
    }

    .card-table-badge {
        background: linear-gradient(135deg, #EA580C, #C2410C);
        color: #FFFFFF;
        border-radius: 6px;
        padding: 1px 6px;
        font-size: 0.85rem;
        font-weight: 900;
        display: flex;
        align-items: baseline;
        gap: 3px;
    }

    .card-headline-box {
        background: #FFF0E6;
        border: 1px solid #FDBA74;
        border-radius: 6px;
        padding: 3px;
        text-align: center;
        margin-bottom: 4px;
    }

    .card-headline-title {
        font-size: 0.72rem;
        font-weight: 900;
        color: #EA580C;
        text-transform: uppercase;
        line-height: 1;
    }

    .card-qr-center {
        width: 125px;
        height: 125px;
        aspect-ratio: 1 / 1;
        margin: 2px auto 4px auto;
        background: #FFFFFF;
        border: 2px solid #0F172A;
        border-radius: 10px;
        padding: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        flex-shrink: 0;
    }

    .card-qr-center img {
        width: 100%;
        height: 100%;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        display: block;
    }

    .card-steps-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2px;
        margin-bottom: 6px;
    }

    .card-step-pill {
        background: #FFFFFF;
        border: 1px solid #FED7AA;
        border-radius: 4px;
        padding: 2px;
        text-align: center;
        font-size: 0.46rem;
        font-weight: 800;
        color: #0F172A;
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
        .sidebar, .top-navbar, .tables-toolbar, .standee-actions, .modal-overlay, .btn-primary, .info-alert-box {
            display: none !important;
        }
        .main-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }
        .table-cards-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 16px !important;
        }
        .table-standee-card {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            page-break-inside: avoid !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Toolbar -->
<div class="tables-toolbar">
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-qrcode" style="color: #EA580C;"></i> Standee Meja QR Code - Depot Be Ba Lung
        </h3>
        <p style="font-size: 0.82rem; color: #6B7280; margin: 0;">
            Format dinamis "Pesan Disini, Tanpa Antri". Siap cetak 4 per lembar A4 secara berurutan.
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

        @if(auth()->user() && auth()->user()->role === 'developer')
        <a href="{{ route('admin.tables.print-all', ['count' => $tableCount]) }}" target="_blank" class="btn-primary" style="padding: 10px 20px; font-size: 0.92rem; font-weight: 900; background: #EA580C; color: white; border: 2.5px solid #111827; box-shadow: 3px 3px 0px #111827; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <i class="fa-solid fa-print"></i> Cetak Semua Meja (Dev)
        </a>
        @endif
    </div>
</div>

<!-- Info Alert -->
<div class="info-alert-box" style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #1E40AF; font-size: 0.82rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div>
        <i class="fa-solid fa-lightbulb" style="color: #2563EB;"></i>
        <strong>Petunjuk Kasir / Owner:</strong> Klik kartu meja mana saja untuk melihat detail status meja atau membuka link pemesanan pelanggan.
    </div>
    <span style="background: #DBEAFE; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800;">
        {{ $occupiedCount }} Meja Sedang Digunakan
    </span>
</div>

<!-- Standee Cards Grid (Dynamic Bebalung Style) -->
<div class="table-cards-grid">
    @foreach($tables as $table)
        <div class="table-standee-card" onclick="openTableModal('{{ $table['number'] }}', '{{ $table['scan_url'] }}', '{{ $table['qr_image'] }}', '{{ $table['status'] }}', '{{ addslashes($table['customer_name'] ?? '') }}')">
            <!-- Brand Top Bar -->
            <div class="card-brand-top">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 20px; height: 20px; background: #0F172A; border-radius: 4px; border: 1px solid #F59E0B; display: flex; align-items: center; justify-content: center; padding: 1.5px;">
                        <img src="{{ asset('images/logo-goat.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <div class="card-brand-title">BE BA LUNG</div>
                        <div class="card-brand-sub">SATE &bull; GULAI &bull; SOP</div>
                    </div>
                </div>
                <div class="card-table-badge">
                    <span style="font-size: 0.40rem; color: #FED7AA;">MEJA</span>
                    <span>{{ $table['number'] }}</span>
                </div>
            </div>

            <!-- Dynamic Headline -->
            <div class="card-headline-box">
                <div class="card-headline-title">
                    <i class="fa-solid fa-bolt" style="font-size: 0.65rem;"></i> PESAN DISINI, TANPA ANTRI
                </div>
            </div>

            <!-- QR Center Frame -->
            <div class="card-qr-center">
                <img src="{{ $table['qr_image'] }}" alt="QR Meja {{ $table['number'] }}">
            </div>

            <!-- Steps Row -->
            <div class="card-steps-row">
                <div class="card-step-pill">1. Scan QR</div>
                <div class="card-step-pill">2. Pilih Menu</div>
                <div class="card-step-pill">3. Pesanan Diantar</div>
            </div>

            <!-- Live Status Meja -->
            @if($table['status'] === 'occupied')
                <div style="background: #FFFBEB; border: 1.5px solid #F59E0B; color: #92400E; font-size: 0.70rem; font-weight: 800; padding: 4px 6px; border-radius: 6px; margin-bottom: 8px; width: 100%; text-align: center;">
                    <i class="fa-solid fa-circle" style="color: #EA580C; font-size: 0.55rem;"></i> 
                    DIGUNAKAN: {{ $table['customer_name'] ?: 'Pelanggan Aktif' }}
                </div>
            @else
                <div style="background: #F0FDF4; border: 1px solid #86EFAC; color: #166534; font-size: 0.70rem; font-weight: 800; padding: 4px 6px; border-radius: 6px; margin-bottom: 8px; width: 100%; text-align: center;">
                    <i class="fa-solid fa-circle-check" style="color: #10B981; font-size: 0.55rem;"></i> 
                    TERSEDIA (KOSONG)
                </div>
            @endif

            <div class="standee-actions" onclick="event.stopPropagation();">
                @if(auth()->user() && auth()->user()->role === 'developer')
                    <a href="{{ route('admin.tables.print-single', $table['number']) }}" target="_blank" class="btn-action-small" style="background: #F59E0B; color: #111827; border-color: #D97706;" title="Cetak Standee Akrilik Meja Ini">
                        <i class="fa-solid fa-print"></i> Cetak HD
                    </a>
                    <a href="{{ $table['scan_url'] }}" target="_blank" class="btn-action-small" title="Uji Coba Pesan Sebagai Meja Ini">
                        <i class="fa-solid fa-mobile-screen"></i> Uji Meja
                    </a>
                @else
                    <button type="button" class="btn-action-small" style="width: 100%; justify-content: center; background: #111827; cursor: pointer; border: none;" onclick="openTableModal('{{ $table['number'] }}', '{{ $table['scan_url'] }}', '{{ $table['qr_image'] }}', '{{ $table['status'] }}', '{{ addslashes($table['customer_name'] ?? '') }}')">
                        <i class="fa-solid fa-expand"></i> Tampilkan QR Meja
                    </button>
                @endif
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

        <div style="width: 200px; height: 200px; margin: 0 auto 16px auto; background: white; border: 2.5px solid #111827; border-radius: 14px; padding: 8px;">
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
            @if(auth()->user() && auth()->user()->role === 'developer')
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6B7280;">Link Scan:</span>
                <a id="modalScanLink" href="#" target="_blank" style="color: #EA580C; font-weight: 700; text-decoration: underline; font-size: 0.75rem;">Buka Link (Dev)</a>
            </div>
            @endif
        </div>

        <!-- Tombol Aksi Modal -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @if(auth()->user() && auth()->user()->role === 'developer')
                <a id="modalBtnPrint" href="#" target="_blank" class="btn-primary" style="background: #F59E0B; color: #111827; justify-content: center; font-weight: 900; font-size: 0.95rem; padding: 12px;">
                    <i class="fa-solid fa-print"></i> Cetak Standee Akrilik Meja Ini (Dev Print)
                </a>

                <a id="modalBtnTest" href="#" target="_blank" class="btn-primary" style="background: #111827; color: white; justify-content: center; font-size: 0.9rem; padding: 10px;">
                    <i class="fa-solid fa-mobile-screen"></i> Buka Menu Pelanggan (Uji Meja - Dev)
                </a>
            @endif

            <div id="modalReleaseFormWrapper"></div>

            <button type="button" onclick="closeTableModal()" style="background: #F3F4F6; color: #4B5563; padding: 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 800; border: none; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTableModal(number, scanUrl, qrImage, status, customerName) {
        try {
            const modal = document.getElementById('tableModal');
            if (!modal) return;

            const numEl = document.getElementById('modalTableNumber');
            if (numEl) numEl.innerText = 'MEJA #' + number;

            const qrEl = document.getElementById('modalQrImage');
            if (qrEl) qrEl.src = qrImage;
            
            const statusEl = document.getElementById('modalTableStatus');
            const customerEl = document.getElementById('modalCustomerName');
            const releaseWrapper = document.getElementById('modalReleaseFormWrapper');

            if (status === 'occupied') {
                if (statusEl) statusEl.innerHTML = '<span style="color: #D97706; font-weight: 800;"><i class="fa-solid fa-circle"></i> Sedang Digunakan</span>';
                if (customerEl) customerEl.innerText = customerName || 'Pelanggan Aktif';
                if (releaseWrapper) {
                    releaseWrapper.innerHTML = `
                        <form action="/admin/tables/${number}/release" method="POST" style="margin-top: 4px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" style="width: 100%; background: #FEE2E2; color: #991B1B; border: 1.5px solid #FCA5A5; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                                <i class="fa-solid fa-rotate-left"></i> Kosongkan Meja Ini
                            </button>
                        </form>
                    `;
                }
            } else {
                if (statusEl) statusEl.innerHTML = '<span style="color: #059669; font-weight: 800;"><i class="fa-solid fa-circle-check"></i> Kosong / Tersedia</span>';
                if (customerEl) customerEl.innerText = '- (Kosong)';
                if (releaseWrapper) releaseWrapper.innerHTML = '';
            }

            const scanLink = document.getElementById('modalScanLink');
            if (scanLink) {
                scanLink.href = scanUrl;
                scanLink.innerText = scanUrl;
            }

            const btnPrint = document.getElementById('modalBtnPrint');
            if (btnPrint) {
                btnPrint.href = '/admin/tables/' + number + '/print';
            }

            const btnTest = document.getElementById('modalBtnTest');
            if (btnTest) {
                btnTest.href = scanUrl;
            }

            modal.classList.add('active');
        } catch (err) {
            console.error('Error opening table standee modal:', err);
        }
    }

    function closeTableModal(event) {
        if (!event || event.target.id === 'tableModal' || event.target.closest('button')) {
            const modal = document.getElementById('tableModal');
            if (modal) modal.classList.remove('active');
        }
    }
</script>
@endsection
