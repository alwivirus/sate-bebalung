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

        <div class="qris-merchant-info">
            <h3>SATE KAMBING BE BA LUNG</h3>
            <p>NMID : ID1025428876474</p>
            <p style="font-size: 0.65rem; color: #6B7280;">A01 - Meja #{{ $order->table_number }}</p>
        </div>

        <!-- Generated QR Code Image -->
        <div class="qris-qr-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=QRIS_DEPOT_BEBALUNG_{{ $order->order_code }}_TOTAL_{{ $order->total_amount }}" alt="QRIS Code">
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

    <!-- Tombol Selesai Bayar -->
    <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST" style="width: 100%; display: flex; justify-content: center;">
        @csrf
        <button type="submit" class="finish-pay-btn">
            <span>SELESAI BAYAR</span>
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
