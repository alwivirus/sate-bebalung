@extends('layouts.admin')

@section('title', 'Pengaturan QRIS Pembayaran - Admin Depot Sate Be Ba Lung')
@section('page-title', 'Pengaturan QRIS Pembayaran Toko')

@section('styles')
<style>
    .qris-config-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 868px) {
        .qris-config-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 800;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #D1D5DB;
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.15s;
    }

    .form-control:focus {
        border-color: #F59E0B;
    }

    .preview-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 24px;
        text-align: center;
    }

    .qris-box-preview {
        max-width: 260px;
        aspect-ratio: 1/1;
        background: #F9FAFB;
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        margin: 16px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 8px;
    }

    .qris-box-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="qris-config-grid">
    <!-- Form Upload / Update QRIS -->
    <div class="form-card">
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #111827;">
                <i class="fa-solid fa-qrcode" style="color: #F59E0B;"></i> Ganti / Ubah QRIS Pembayaran
            </h3>
            <p style="font-size: 0.82rem; color: #6B7280; margin-top: 4px;">
                Jika QRIS sedang bermasalah, expired, atau ingin diganti dengan QRIS merchant baru, upload foto QRIS di sini.
            </p>
        </div>

        <form action="{{ route('admin.settings.qris.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Merchant / Usaha di QRIS</label>
                <input type="text" name="merchant_name" class="form-control" value="{{ $merchantName }}" placeholder="Contoh: SATE KAMBING BE BA LUNG" required>
            </div>

            <div class="form-group">
                <label>Nomor NMID (National Merchant ID)</label>
                <input type="text" name="nmid" class="form-control" value="{{ $nmid }}" placeholder="Contoh: ID1025428876474">
            </div>

            <div class="form-group">
                <label>Upload Gambar / Foto QRIS Baru</label>
                <input type="file" name="qris_image" class="form-control" accept="image/*" onchange="previewUploadedQris(event)">
                <small style="color: #6B7280; font-size: 0.75rem; margin-top: 4px; display: block;">
                    Format: JPG, PNG, WEBP, SVG (Maks. 3 MB). Rekomendasi gambar persegi/kotak.
                </small>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 0.95rem; margin-top: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan &amp; Terapkan QRIS
            </button>
        </form>
    </div>

    <!-- Preview QRIS Customer View -->
    <div class="preview-card">
        <h4 style="font-size: 1rem; font-weight: 800; color: #111827;">
            <i class="fa-solid fa-mobile-screen-button"></i> Tampilan QRIS pada Pelanggan
        </h4>
        <p style="font-size: 0.8rem; color: #6B7280;">Ini adalah QRIS yang akan discan oleh pelanggan saat memilih "Bayar Online".</p>

        <div class="qris-box-preview">
            @if($qrisImage)
                <img src="{{ asset($qrisImage) }}" alt="QRIS Merchant" id="previewImg">
            @else
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=QRIS_DEPOT_BEBALUNG_DEFAULT" alt="QRIS Auto Generator" id="previewImg">
            @endif
        </div>

        <div style="background: #F3F4F6; padding: 10px; border-radius: 8px; font-size: 0.8rem; text-align: left;">
            <div style="font-weight: 800; color: #111827;">{{ $merchantName }}</div>
            <div style="color: #4B5563; font-size: 0.75rem; margin-top: 2px;">NMID: {{ $nmid }}</div>
            <div style="color: #059669; font-weight: 700; font-size: 0.75rem; margin-top: 4px;">
                <i class="fa-solid fa-circle-check"></i> Status: Aktif &amp; Siap Terima Pembayaran
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewUploadedQris(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
