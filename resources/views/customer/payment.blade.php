@extends('layouts.app')

@section('title', 'Pembayaran QRIS - Depot Sate Be Ba Lung')

@section('styles')
<style>
    .payment-container {
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        max-width: 480px;
        margin: 0 auto;
    }

    .main-heading {
        font-size: 1.3rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .sub-heading {
        font-size: 0.82rem;
        color: #4B5563;
        line-height: 1.35;
        margin-bottom: 14px;
    }

    /* Countdown Timer Badge */
    .timer-badge {
        background-color: #FEE2E2;
        border: 2px solid var(--dark-border);
        border-radius: 20px;
        padding: 4px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 800;
        font-size: 0.85rem;
        color: #DC2626;
        box-shadow: 2px 2px 0px var(--dark-border);
        margin-bottom: 16px;
    }

    /* Realistic Indonesian QRIS Card */
    .qris-card {
        width: 100%;
        max-width: 320px;
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        box-shadow: var(--box-shadow-brutal);
        padding: 16px 14px;
        margin-bottom: 18px;
        position: relative;
    }

    .qris-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .qris-merchant-info h3 {
        font-size: 0.88rem;
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
        margin: 6px 0 16px;
    }

    .bill-label {
        font-size: 0.8rem;
        font-weight: 900;
        color: #374151;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .bill-amount-box {
        background-color: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 12px;
        box-shadow: 3px 3px 0px var(--dark-border);
        padding: 8px 22px;
        font-size: 1.35rem;
        font-weight: 900;
        color: #111827;
        display: inline-block;
        transform: rotate(-1.5deg);
    }

    /* Verifikasi Proof Tabs */
    .proof-box {
        width: 100%;
        max-width: 340px;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 18px;
        box-shadow: var(--box-shadow-brutal);
        padding: 16px 14px;
        margin-bottom: 20px;
        text-align: left;
    }

    .proof-tabs-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        background: #F3F4F6;
        padding: 4px;
        border-radius: 10px;
        margin-bottom: 14px;
    }

    .proof-tab-btn {
        padding: 8px 6px;
        font-size: 0.75rem;
        font-weight: 800;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #4B5563;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .proof-tab-btn.active {
        background: #111827;
        color: #FCD34D;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .tab-content-panel {
        display: none;
    }

    .tab-content-panel.active {
        display: block;
    }

    .btn-submit-action {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 2.5px solid var(--dark-border);
        border-radius: 12px;
        box-shadow: 2px 2px 0px var(--dark-border);
        padding: 12px;
        font-size: 0.95rem;
        font-weight: 900;
        color: #111827;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.1s;
        text-decoration: none;
    }

    .btn-submit-action:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px #000;
    }
</style>
@endsection

@section('content')
<div class="payment-container">
    <h1 class="main-heading">PEMBAYARAN QRIS ONLINE</h1>
    <p class="sub-heading">Scan QRIS menggunakan BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay atau M-Banking Anda.</p>

    <!-- Countdown Timer -->
    <div class="timer-badge">
        <i class="fa-solid fa-stopwatch"></i>
        <span id="countdown">05:00</span>
    </div>

    <!-- Realistic Indonesian QRIS Card -->
    <div class="qris-card">
        <div class="qris-header">
            <div style="font-weight: 900; font-size: 1.05rem; color: #DC2626; letter-spacing: -0.5px;">
                QRIS <span style="font-size: 0.52rem; color: #4B5563; font-weight: 600; display: block;">Pembayaran Digital Indonesia</span>
            </div>
            <div style="background: #DC2626; color: white; padding: 2px 6px; border-radius: 4px; font-weight: 900; font-size: 0.72rem;">
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
            <img src="{{ asset($qrisPath) }}" alt="QRIS {{ $customMerchant }}" style="width: 100%; height: 100%; object-fit: contain;">
        </div>

        <div class="qris-footer-banner">
            <strong>SATU QRIS UNTUK SEMUA</strong>
            <p style="font-size: 0.6rem; margin-top: 2px;">Order ID: {{ $order->order_code }}</p>
        </div>
    </div>

    <!-- Total Tagihan -->
    <div class="bill-section">
        <div class="bill-label">TOTAL PEMBAYARAN</div>
        <div class="bill-amount-box">{{ $order->formatted_total }}</div>
    </div>

    <!-- Kotak Verifikasi Bukti Pembayaran -->
    <div class="proof-box">
        <div style="font-size: 0.85rem; font-weight: 900; color: #111827; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-receipt" style="color: #EA580C;"></i> Pilih Cara Verifikasi Bukti Bayar:
        </div>

        <!-- Tab Selector: Upload Foto vs Tunjukkan di Kasir -->
        <div class="proof-tabs-header">
            <button type="button" class="proof-tab-btn active" id="tabBtnUpload" onclick="switchProofTab('upload')">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Foto
            </button>
            <button type="button" class="proof-tab-btn" id="tabBtnShow" onclick="switchProofTab('show')">
                <i class="fa-solid fa-store"></i> Tunjukkan Kasir
            </button>
        </div>

        <!-- PANEL 1: Upload Foto Bukti Pembayaran -->
        <div class="tab-content-panel active" id="panelUpload">
            @if($order->payment_proof)
                <div style="background: #ECFDF5; border: 1.5px solid #10B981; border-radius: 10px; padding: 10px; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset($order->payment_proof) }}" alt="Bukti" style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid #111827;">
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 900; color: #065F46;">
                            <i class="fa-solid fa-circle-check"></i> Foto Bukti Tersimpan!
                        </div>
                        <div style="font-size: 0.68rem; color: #047857;">Kasir dapat memeriksa bukti ini di dashboard.</div>
                    </div>
                </div>
            @endif

            <p style="font-size: 0.75rem; color: #4B5563; margin-bottom: 10px; line-height: 1.35;">
                Unggah screenshot transfer QRIS dari m-banking Anda agar kasir dapat langsung memverifikasi uang masuk di rekening.
            </p>

            <form action="{{ route('order.payment.upload-proof', ['order_code' => $order->order_code]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label style="width: 100%; background: #F9FAFB; border: 2px dashed #9CA3AF; border-radius: 10px; padding: 12px 10px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.82rem; font-weight: 800; color: #111827; cursor: pointer; margin-bottom: 12px;">
                    <i class="fa-solid fa-camera" style="color: #EA580C; font-size: 1.1rem;"></i>
                    <span>{{ $order->payment_proof ? 'Ganti Foto / Upload Ulang' : 'Pilih File Screenshot Transfer' }}</span>
                    <input type="file" name="payment_proof" accept="image/*" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>

            @if($order->payment_proof)
                <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-submit-action" style="background: #10B981; color: white;">
                        <span>KONFIRMASI SELESAI BAYAR</span>
                        <i class="fa-solid fa-circle-check"></i>
                    </button>
                </form>
            @endif
        </div>

        <!-- PANEL 2: Tunjukkan Layar Langsung ke Kasir -->
        <div class="tab-content-panel" id="panelShow">
            <div style="background: #FFFBEB; border: 1.5px solid #FCD34D; border-radius: 10px; padding: 12px; margin-bottom: 14px; font-size: 0.76rem; color: #92400E; line-height: 1.4;">
                <div style="font-weight: 900; margin-bottom: 4px; display: flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-hand-holding-dollar" style="color: #D97706;"></i> Langkah Tunjukkan ke Kasir:
                </div>
                1. Bawa smartphone Anda ke meja kasir.<br>
                2. Tunjukkan layar transfer berhasil ke kasir.<br>
                3. Kasir akan memfoto layar Anda sebagai arsip bukti pembayaran.
            </div>

            <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST">
                @csrf
                <button type="submit" class="btn-submit-action">
                    <span>SAYA SUDAH BAYAR &amp; KE KASIR</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchProofTab(type) {
        const btnUpload = document.getElementById('tabBtnUpload');
        const btnShow = document.getElementById('tabBtnShow');
        const panelUpload = document.getElementById('panelUpload');
        const panelShow = document.getElementById('panelShow');

        if (type === 'upload') {
            btnUpload.classList.add('active');
            btnShow.classList.remove('active');
            panelUpload.classList.add('active');
            panelShow.classList.remove('active');
        } else {
            btnShow.classList.add('active');
            btnUpload.classList.remove('active');
            panelShow.classList.add('active');
            panelUpload.classList.remove('active');
        }
    }

    // 5 minutes countdown timer
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
