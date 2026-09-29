@extends('layouts.admin')

@section('title', 'Pengaturan QRIS Pembayaran - Admin Depot Sate Be Ba Lung')
@section('page-title', 'Pengaturan QRIS Pembayaran Toko')

@section('styles')
<style>
    .qris-config-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Top Summary Banner */
    .qris-hero-banner {
        background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
        border-radius: 16px;
        padding: 22px 26px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.3);
    }

    .qris-hero-info h2 {
        font-size: 1.25rem;
        font-weight: 900;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qris-hero-info p {
        font-size: 0.85rem;
        color: #C7D2FE;
        margin: 0;
        max-width: 620px;
        line-height: 1.45;
    }

    .qris-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .qris-btn-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .qris-btn-pill-white {
        background: white;
        color: #1E1B4B;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .qris-btn-pill-white:hover {
        background: #F3F4F6;
        transform: translateY(-2px);
    }

    .qris-btn-pill-orange {
        background: #EA580C;
        color: white;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.4);
    }

    .qris-btn-pill-orange:hover {
        background: #C2410C;
        transform: translateY(-2px);
    }

    /* Main Grid */
    .qris-config-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 980px) {
        .qris-config-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px 26px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .card-header-block {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F3F4F6;
    }

    .card-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #FEF3C7;
        color: #D97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .card-header-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .card-header-sub {
        font-size: 0.8rem;
        color: #6B7280;
        margin: 2px 0 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 800;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #D1D5DB;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 600;
        outline: none;
        transition: all 0.15s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #EA580C;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
    }

    /* Custom File Upload Box */
    .file-dropzone {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }

    .file-dropzone:hover {
        border-color: #EA580C;
        background: #FFF7ED;
    }

    .dropzone-icon {
        font-size: 1.8rem;
        color: #EA580C;
        margin-bottom: 8px;
    }

    .dropzone-text {
        font-size: 0.88rem;
        font-weight: 800;
        color: #1E293B;
    }

    .dropzone-sub {
        font-size: 0.75rem;
        color: #64748B;
        margin-top: 4px;
    }

    /* Preview Card */
    .preview-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .preview-view-tabs {
        display: flex;
        background: #F1F5F9;
        padding: 4px;
        border-radius: 10px;
        gap: 4px;
        width: 100%;
        margin-bottom: 20px;
    }

    .preview-tab-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #64748B;
        background: transparent;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .preview-tab-btn.active {
        background: #111827;
        color: #FCD34D;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }

    /* Realistic Simulated Mobile QRIS Container */
    .simulated-qris-card {
        width: 100%;
        max-width: 310px;
        background: #FFFFFF;
        border: 2.5px solid #111827;
        border-radius: 18px;
        padding: 16px;
        box-shadow: 4px 4px 0px #111827;
        text-align: center;
        transition: all 0.3s;
    }

    .simulated-qris-card.standee-mode {
        border-color: #EA580C;
        box-shadow: 4px 4px 0px #EA580C;
    }

    .preview-qris-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 8px;
        border-bottom: 1.5px solid #E5E7EB;
        margin-bottom: 10px;
    }

    .preview-qris-logo {
        text-align: left;
    }

    .preview-qris-brand {
        font-size: 1.1rem;
        font-weight: 900;
        color: #DC2626;
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .preview-qris-sub {
        font-size: 0.52rem;
        color: #4B5563;
        font-weight: 700;
        margin-top: 1px;
    }

    .preview-gpn-tag {
        background: #DC2626;
        color: white;
        font-weight: 900;
        font-size: 0.68rem;
        padding: 2px 7px;
        border-radius: 4px;
    }

    .preview-merchant-info h4 {
        font-size: 0.92rem;
        font-weight: 900;
        color: #111827;
        margin: 4px 0 2px;
        text-transform: uppercase;
        word-break: break-word;
    }

    .preview-merchant-info p {
        font-size: 0.72rem;
        font-weight: 700;
        color: #4B5563;
        margin: 0;
    }

    /* Fixed Box Aspect Ratio Container */
    .qris-box-preview {
        width: 100%;
        max-width: 240px;
        aspect-ratio: 1 / 1;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        margin: 12px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .qris-box-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 6px;
    }

    .preview-footer-note {
        border-top: 1px solid #E5E7EB;
        padding-top: 8px;
        font-size: 0.65rem;
        color: #6B7280;
        font-weight: 700;
    }

    .preview-actions-bar {
        width: 100%;
        display: flex;
        gap: 8px;
        margin-top: 18px;
    }

    .preview-action-btn {
        flex: 1;
        padding: 10px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        border: 1.5px solid var(--border-color);
        background: #F8FAFC;
        color: #1E293B;
        transition: all 0.15s;
    }

    .preview-action-btn:hover {
        background: #F1F5F9;
        border-color: #CBD5E1;
    }

    .preview-action-btn.primary {
        background: #EA580C;
        border-color: #EA580C;
        color: white;
    }

    .preview-action-btn.primary:hover {
        background: #C2410C;
    }
</style>
@endsection

@section('content')
<div class="qris-config-wrapper">
    
    <!-- Top Hero Banner -->
    <div class="qris-hero-banner">
        <div class="qris-hero-info">
            <h2>
                <i class="fa-solid fa-qrcode" style="color: #FCD34D;"></i> Konfigurasi QRIS Pembayaran Toko
            </h2>
            <p>
                QRIS ini digunakan secara statis untuk seluruh transaksi pelanggan (Scan Meja &amp; Kasir). Pelanggan dapat langsung scan menggunakan BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay atau M-Banking apa pun.
            </p>
        </div>
        <div class="qris-hero-actions">
            <a href="{{ route('admin.settings.qris.print') }}" target="_blank" class="qris-btn-pill qris-btn-pill-orange" title="Cetak Format Akrilik Meja Kasir">
                <i class="fa-solid fa-print"></i> Cetak Standee Akrilik
            </a>
            <a href="{{ asset($qrisImage) }}" download="qris_depot_bebalung.png" class="qris-btn-pill qris-btn-pill-white" title="Unduh File Gambar QRIS Asli">
                <i class="fa-solid fa-download"></i> Unduh File QR
            </a>
        </div>
    </div>

    <!-- Main Content 2 Columns -->
    <div class="qris-config-grid">
        
        <!-- Left Column: Form Edit & Upload -->
        <div class="form-card">
            <div class="card-header-block">
                <div class="card-header-icon">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h3 class="card-header-title">Ubah Detail &amp; Foto QRIS</h3>
                    <p class="card-header-sub">Perbarui nama merchant, NMID resmi, atau ganti foto QRIS toko.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.qris.update') }}" method="POST" enctype="multipart/form-data" id="qrisForm">
                @csrf

                <div class="form-group">
                    <label>
                        <span>Nama Merchant / Usaha di QRIS</span>
                        <span style="font-size: 0.72rem; color: #6B7280; font-weight: 600;">Sesuai cetakan QRIS</span>
                    </label>
                    <input type="text" name="merchant_name" id="inputMerchantName" class="form-control" value="{{ $merchantName }}" placeholder="Contoh: SATE KAMBING BE BA LUNG" required oninput="syncMerchantName(this.value)">
                </div>

                <div class="form-group">
                    <label>
                        <span>Nomor NMID (National Merchant ID)</span>
                        <span style="font-size: 0.72rem; color: #6B7280; font-weight: 600;">ID Standar BI</span>
                    </label>
                    <input type="text" name="nmid" id="inputNmid" class="form-control" value="{{ $nmid }}" placeholder="Contoh: ID1025428876474" oninput="syncNmid(this.value)">
                </div>

                <div class="form-group">
                    <label>
                        <span>Upload Foto QR Code Baru (Opsional)</span>
                        <span style="font-size: 0.72rem; color: #059669; font-weight: 700;">Maks. 3 MB</span>
                    </label>
                    <label class="file-dropzone" for="qrisFileInput">
                        <div class="dropzone-icon">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div class="dropzone-text" id="fileChosenText">Klik untuk memilih atau drag &amp; drop gambar QRIS</div>
                        <div class="dropzone-sub">Format: PNG, JPG, JPEG, WEBP. Gambar persegi / kotak sangat disarankan.</div>
                        <input type="file" name="qris_image" id="qrisFileInput" accept="image/*" style="display: none;" onchange="handleFileSelect(event)">
                    </label>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 16px;">
                    <button type="submit" class="qris-btn-pill qris-btn-pill-orange" style="flex: 2; justify-content: center; padding: 13px; font-size: 0.92rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan &amp; Terapkan QRIS
                    </button>
                </div>
            </form>

            <!-- Reset to Default Button -->
            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px dashed #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 0.78rem; color: #6B7280;">
                    Ingin mengembalikan QRIS resmi bawaan toko?
                </div>
                <form action="{{ route('admin.settings.qris.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset QRIS ke gambar bawaan resmi?')">
                    @csrf
                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset ke QR Asli
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Live Interactive Simulation Preview -->
        <div class="preview-card">
            
            <div class="preview-view-tabs">
                <button type="button" class="preview-tab-btn active" id="tabMobile" onclick="switchPreviewMode('mobile')">
                    <i class="fa-solid fa-mobile-screen"></i> Layar HP Pelanggan
                </button>
                <button type="button" class="preview-tab-btn" id="tabStandee" onclick="switchPreviewMode('standee')">
                    <i class="fa-solid fa-store"></i> Standee Meja Kasir
                </button>
            </div>

            <!-- Simulated Card Container -->
            <div class="simulated-qris-card" id="simulatedCard">
                <div class="preview-qris-header">
                    <div class="preview-qris-logo">
                        <div class="preview-qris-brand">QRIS</div>
                        <div class="preview-qris-sub">PEMBAYARAN DIGITAL INDONESIA</div>
                    </div>
                    <div class="preview-gpn-tag">GPN</div>
                </div>

                <div class="preview-merchant-info">
                    <h4 id="previewMerchantTitle">{{ $merchantName }}</h4>
                    <p id="previewNmidText">NMID: {{ $nmid }}</p>
                </div>

                <!-- QR Image View Box -->
                <div class="qris-box-preview">
                    <img src="{{ asset($qrisImage) }}" alt="QRIS Merchant" id="previewImg">
                </div>

                <div class="preview-footer-note">
                    <div style="color: #111827; font-weight: 900;">SATU QRIS UNTUK SEMUA</div>
                    <div style="font-size: 0.62rem; color: #059669; font-weight: 800; margin-top: 3px;">
                        <i class="fa-solid fa-circle-check"></i> Status: Aktif &amp; Terverifikasi
                    </div>
                </div>
            </div>

            <!-- Action Buttons below Preview -->
            <div class="preview-actions-bar">
                <button type="button" class="preview-action-btn" onclick="copyNmid()" id="copyBtn">
                    <i class="fa-solid fa-copy"></i> <span>Salin NMID</span>
                </button>
                <a href="{{ route('admin.settings.qris.print') }}" target="_blank" class="preview-action-btn primary">
                    <i class="fa-solid fa-print"></i> <span>Cetak Akrilik</span>
                </a>
            </div>

            <!-- Info Helper -->
            <div style="margin-top: 16px; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 10px 14px; width: 100%; text-align: left; font-size: 0.76rem; color: #166534; line-height: 1.4;">
                <i class="fa-solid fa-shield-halved" style="color: #15803D;"></i>
                <strong>Tip Kasir &amp; Dev:</strong> Gambar QRIS di atas otomatis tersinkronisasi ke halaman checkout pelanggan di meja, kasir scanner, dan bukti transaksi.
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function syncMerchantName(val) {
        document.getElementById('previewMerchantTitle').innerText = val || 'NAMA MERCHANT';
    }

    function syncNmid(val) {
        document.getElementById('previewNmidText').innerText = 'NMID: ' + (val || '-');
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('fileChosenText').innerHTML = '✅ Terpilih: <strong>' + file.name + '</strong> (' + (file.size / 1024).toFixed(1) + ' KB)';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function switchPreviewMode(mode) {
        const card = document.getElementById('simulatedCard');
        const tabMobile = document.getElementById('tabMobile');
        const tabStandee = document.getElementById('tabStandee');

        if (mode === 'standee') {
            card.classList.add('standee-mode');
            tabStandee.classList.add('active');
            tabMobile.classList.remove('active');
        } else {
            card.classList.remove('standee-mode');
            tabMobile.classList.add('active');
            tabStandee.classList.remove('active');
        }
    }

    function copyNmid() {
        const nmid = document.getElementById('inputNmid').value;
        if (!nmid) return;

        navigator.clipboard.writeText(nmid).then(() => {
            const btn = document.getElementById('copyBtn');
            btn.innerHTML = '<i class="fa-solid fa-check" style="color: #059669;"></i> <span>Tersalin!</span>';
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-copy"></i> <span>Salin NMID</span>';
            }, 2000);
        });
    }
</script>
@endsection
