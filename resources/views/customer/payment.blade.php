@extends('layouts.app')

@section('title', 'Menunggu Pembayaran QRIS - Depot Sate Be Ba Lung')

@section('styles')
<style>
    .payment-container {
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .main-heading {
        font-size: 1.35rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .sub-heading {
        font-size: 0.85rem;
        color: #4B5563;
        line-height: 1.4;
        max-width: 320px;
        margin-bottom: 16px;
    }

    /* Countdown Timer Badge */
    .timer-badge {
        background-color: #FEE2E2;
        border: 2px solid var(--dark-border);
        border-radius: 20px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 800;
        font-size: 0.95rem;
        color: #DC2626;
        box-shadow: 2px 2px 0px var(--dark-border);
        margin-bottom: 20px;
    }

    /* Realistic Indonesian QRIS Card */
    .qris-card {
        width: 100%;
        max-width: 310px;
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        box-shadow: var(--box-shadow-brutal);
        padding: 16px 14px;
        margin-bottom: 20px;
        position: relative;
    }

    .qris-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .qris-merchant-info h3 {
        font-size: 0.9rem;
        font-weight: 900;
        color: #111827;
        margin-top: 4px;
    }

    .qris-merchant-info p {
        font-size: 0.72rem;
        color: #374151;
        font-weight: 700;
    }

    .qris-qr-box {
        width: 100%;
        aspect-ratio: 1/1;
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        margin: 10px 0;
    }

    .qris-qr-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .qris-footer-banner {
        border-top: 1px solid #E5E7EB;
        padding-top: 8px;
        font-size: 0.65rem;
        color: #6B7280;
        line-height: 1.3;
    }

    .qris-footer-banner strong {
        color: #111827;
        display: block;
        font-size: 0.72rem;
    }

    /* Total Tagihan Box */
    .bill-section {
        margin: 10px 0 20px;
    }

    .bill-label {
        font-size: 0.85rem;
        font-weight: 900;
        color: #374151;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .bill-amount-box {
        background-color: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 12px;
        box-shadow: 3px 3px 0px var(--dark-border);
        padding: 8px 24px;
        font-size: 1.45rem;
        font-weight: 900;
        color: #111827;
        display: inline-block;
        transform: rotate(-1.5deg);
    }

    /* Selesai Bayar Button */
    .finish-pay-btn {
        width: 100%;
        max-width: 320px;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 14px;
        box-shadow: var(--box-shadow-brutal);
        padding: 12px;
        font-size: 1.05rem;
        font-weight: 900;
        color: #111827;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.1s;
    }

    .finish-pay-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }
</style>
@endsection

@section('content')
<div class="payment-container">
    <h1 class="main-heading">MENUNGGU PEMBAYARAN</h1>
    <p class="sub-heading">Scan QRIS di bawah ini menggunakan aplikasi e-wallet atau m-banking pilihan Anda.</p>

    <!-- Countdown Timer -->
    <div class="timer-badge">
        <i class="fa-solid fa-stopwatch"></i>
        <span id="countdown">05:00</span>
    </div>

    <!-- Realistic Indonesian QRIS Card -->
    <div class="qris-card">
        <div class="qris-header">
            <!-- QRIS Brand Logo Text -->
            <div style="font-weight: 900; font-size: 1.1rem; color: #DC2626; letter-spacing: -0.5px;">
                QRIS <span style="font-size: 0.55rem; color: #4B5563; font-weight: 600; display: block;">QR Code Standar Pembayaran Nasional</span>
            </div>
            <!-- GPN Logo -->
            <div style="background: #DC2626; color: white; padding: 2px 6px; border-radius: 4px; font-weight: 900; font-size: 0.75rem;">
                GPN
            </div>
        </div>

        @php
            $customQrisImage = \App\Models\Setting::get('qris_image');
            $customMerchant = \App\Models\Setting::get('qris_merchant_name', 'SATE KAMBING BE BA LUNG');
            $customNmid = \App\Models\Setting::get('qris_nmid', 'ID1025428876474');
        @endphp

        <div class="qris-merchant-info">
            <h3>{{ $customMerchant }}</h3>
            <p>NMID : {{ $customNmid }}</p>
            <p style="font-size: 0.65rem; color: #6B7280;">A01 - Meja #{{ $order->table_number }} (a.n {{ $order->customer_name }})</p>
        </div>

        <!-- Generated or Custom Uploaded QR Code Image -->
        <div class="qris-qr-box">
            @php
                $qrisPath = 'images/qris_official.png';
                if ($customQrisImage && (file_exists(public_path($customQrisImage)) || file_exists(base_path($customQrisImage)))) {
                    $qrisPath = $customQrisImage;
                }
            @endphp
            <img src="{{ asset($qrisPath) }}" alt="QRIS {{ $customMerchant }}" style="width: 100%; height: 100%; object-fit: contain; image-rendering: pixelated;">
        </div>

        <div class="qris-footer-banner">
            <strong>SATU QRIS UNTUK SEMUA</strong>
            <p>Cek aplikasi penyelenggara di: www.aspi-qris.id</p>
            <p style="font-size: 0.6rem; margin-top: 4px;">Order ID: {{ $order->order_code }}</p>
        </div>
    </div>

    <!-- Total Tagihan -->
    <div class="bill-section">
        <div class="bill-label">TOTAL TAGIHAN</div>
        <div class="bill-amount-box">{{ $order->formatted_total }}</div>
    </div>

    <!-- Opsi Bukti Pembayaran QRIS (Pilihan Pelanggan) -->
    <div style="width: 100%; max-width: 330px; background: #FFFFFF; border: 2.5px solid var(--dark-border); border-radius: 16px; padding: 14px; margin-bottom: 18px; box-shadow: var(--box-shadow-brutal); text-align: left;">
        <div style="font-size: 0.85rem; font-weight: 900; color: #111827; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-shield-halved" style="color: #EA580C;"></i> Verifikasi Pembayaran QRIS:
        </div>

        @if($order->payment_proof)
            <!-- Status jika sudah upload foto -->
            <div style="background: #ECFDF5; border: 1.5px solid #10B981; border-radius: 12px; padding: 10px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset($order->payment_proof) }}" alt="Bukti Transfer" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1.5px solid #111827;">
                <div style="flex: 1;">
                    <div style="font-size: 0.78rem; font-weight: 900; color: #065F46;">
                        <i class="fa-solid fa-circle-check"></i> Foto Bukti Terunggah!
                    </div>
                    <div style="font-size: 0.7rem; color: #047857;">Tersimpan di database kasir.</div>
                </div>
            </div>
        @endif

        <!-- Pilihan A: Upload Screenshot / Foto Bukti -->
        <div style="margin-bottom: 10px;">
            <span style="font-size: 0.72rem; font-weight: 800; color: #6B7280; text-transform: uppercase; display: block; margin-bottom: 4px;">
                Pilihan 1: Unggah Foto / Screenshot Transfer
            </span>
            <form action="{{ route('order.payment.upload-proof', ['order_code' => $order->order_code]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="width: 100%; background: #F9FAFB; border: 2px dashed #9CA3AF; border-radius: 10px; padding: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.8rem; font-weight: 800; color: #111827; cursor: pointer;">
                    <i class="fa-solid fa-cloud-arrow-up" style="color: #EA580C; font-size: 1.1rem;"></i>
                    <span>{{ $order->payment_proof ? 'Ganti Foto Bukti Transfer' : 'Pilih File / Ambil Screenshot' }}</span>
                    <input type="file" name="payment_proof" accept="image/*" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>
        </div>

        <!-- Pilihan B: Tunjukkan Layar Langsung ke Kasir -->
        <div style="background: #FEF3C7; border: 1.5px solid #FCD34D; border-radius: 10px; padding: 10px; font-size: 0.75rem; color: #92400E; font-weight: 700; line-height: 1.35;">
            <i class="fa-solid fa-store" style="color: #D97706;"></i>
            <strong>Pilihan 2: Tunjukkan Langsung ke Kasir</strong>
            <div style="font-size: 0.7rem; color: #78350F; margin-top: 2px;">
                Jika tidak ingin upload file, Anda bisa langsung menunjukkan layar HP bukti transfer berhasil ke meja kasir.
            </div>
        </div>
    </div>

    <!-- Tombol Selesai Bayar -->
    <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST" style="width: 100%; display: flex; justify-content: center;">
        @csrf
        <button type="submit" class="finish-pay-btn">
            <span>SAYA SUDAH SELESAI BAYAR</span>
            <i class="fa-solid fa-circle-check"></i>
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // 5 minutes countdown timer (300 seconds)
    let timeLeft = 300;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.innerText = "WAKTU HABIS";
        } else {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
    }, 1000);
</script>
@endsection
