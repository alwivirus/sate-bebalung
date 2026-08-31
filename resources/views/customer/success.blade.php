@extends('layouts.app')

@section('title', 'Pesanan Siap - Depot Sate Be Ba Lung')

@section('styles')
<!-- JsBarcode Library for standard Code128 rendering -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
    .success-container {
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Main Yellow Confirmation Card */
    .order-status-card {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 20px;
        box-shadow: var(--box-shadow-brutal);
        padding: 24px 16px;
        position: relative;
        text-align: center;
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* Decorative Circle Top Right */
    .deco-circle {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 60px;
        height: 60px;
        background-color: #EA580C;
        border-radius: 50%;
        opacity: 0.8;
    }

    .card-title {
        font-size: 1.35rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }

    .card-subtitle {
        font-size: 0.82rem;
        color: #374151;
        font-weight: 700;
        line-height: 1.4;
        max-width: 300px;
        margin: 0 auto 16px auto;
    }

    /* Barcode / QR Code Box */
    .barcode-box {
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        padding: 16px 12px;
        margin-bottom: 18px;
        box-shadow: 3px 3px 0px var(--dark-border);
    }

    .code-tabs {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .code-tab-btn {
        background: #F3F4F6;
        border: 2px solid var(--dark-border);
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
        color: #4B5563;
        transition: all 0.1s;
    }

    .code-tab-btn.active {
        background: #111827;
        color: #FFFFFF;
        border-color: #111827;
    }

    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .qr-image-wrapper {
        width: 170px;
        height: 170px;
        background: #FFFFFF;
        padding: 8px;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .qr-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .barcode-svg-wrapper {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px 0;
    }

    .barcode-code-text {
        font-size: 1.05rem;
        font-weight: 900;
        letter-spacing: 2px;
        color: #111827;
        margin-top: 6px;
        font-family: monospace;
        background: #FEF3C7;
        padding: 4px 12px;
        border-radius: 6px;
        display: inline-block;
        border: 1.5px solid var(--dark-border);
    }

    /* Inner Detail Pesanan Card */
    .inner-detail-card {
        background-color: #FCD34D;
        border: 2.5px solid var(--dark-border);
        border-radius: 14px;
        padding: 14px;
        text-align: left;
    }

    .detail-heading {
        font-size: 0.85rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .detail-item-row .qty-tag {
        font-weight: 900;
        color: #4B5563;
    }

    .detail-total-row {
        border-top: 2px dashed #1E1E1E;
        padding-top: 10px;
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 900;
    }

    .detail-total-row .total-title {
        font-size: 0.95rem;
        color: #111827;
    }

    .detail-total-row .total-val {
        font-size: 1.05rem;
        color: #DC2626;
    }

    /* Kembali ke Menu Button */
    .return-menu-btn {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: var(--box-shadow-brutal);
        padding: 14px;
        font-size: 1.05rem;
        font-weight: 900;
        color: #111827;
        text-align: center;
        text-decoration: none;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 12px;
        transition: transform 0.1s;
    }

    .return-menu-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }

    /* Status Badge */
    .payment-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 8px;
        border: 2px solid var(--dark-border);
        font-size: 0.8rem;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .pill-paid {
        background: #86EFAC;
        color: #064E3B;
    }

    .pill-unpaid {
        background: #FED7AA;
        color: #7C2D12;
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <!-- Main Yellow Card -->
    <div class="order-status-card">
        <div class="deco-circle"></div>

        <h2 class="card-title">Pesanan Siap!</h2>
        <p class="card-subtitle">Tunjukkan QR Code / Barcode ini kepada kasir untuk memproses pesanan Anda.</p>

        @if($order->payment_status === 'paid')
            <div class="payment-status-pill pill-paid">
                <i class="fa-solid fa-circle-check"></i> Sudah Dibayar (QRIS Online)
            </div>
        @else
            <div class="payment-status-pill pill-unpaid">
                <i class="fa-solid fa-clock"></i> Belum Dibayar (Bayar di Kasir)
            </div>
        @endif

        <!-- Real Scannable Barcode & QR Box -->
        <div class="barcode-box">
            <div class="code-tabs">
                <button type="button" class="code-tab-btn active" id="tabQr" onclick="switchCodeView('qr')">
                    <i class="fa-solid fa-qrcode"></i> Scan QR Code
                </button>
                <button type="button" class="code-tab-btn" id="tabBarcode" onclick="switchCodeView('barcode')">
                    <i class="fa-solid fa-barcode"></i> Barcode Kasir
                </button>
            </div>

            <!-- View 1: Real High-Res QR Code -->
            <div id="viewQr" class="qr-container">
                <div class="qr-image-wrapper">
                    <img 
                        src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($order->order_code) }}&margin=0" 
                        alt="QR Code {{ $order->order_code }}"
                        id="orderQrImg"
                    >
                </div>
                <div style="font-size: 0.72rem; color: #4B5563; font-weight: 700; margin-bottom: 4px;">
                    <i class="fa-solid fa-camera"></i> Dapat di-scan langsung dengan Kamera HP / Scanner Kasir
                </div>
            </div>

            <!-- View 2: Real Scannable Code128 Barcode -->
            <div id="viewBarcode" style="display: none;">
                <div class="barcode-svg-wrapper">
                    <svg id="realBarcode"></svg>
                </div>
                <div style="font-size: 0.72rem; color: #4B5563; font-weight: 700; margin-bottom: 4px;">
                    <i class="fa-solid fa-barcode"></i> Format Standar Code128 untuk Scanner Kasir USB
                </div>
            </div>

            <div class="barcode-code-text">{{ $order->order_code }}</div>
        </div>

        <!-- Detail Pesanan -->
        <div class="inner-detail-card">
            <div class="detail-heading">
                <span>DETAIL PESANAN (Meja #{{ $order->table_number }})</span>
                <span style="font-size: 0.75rem; color: #4B5563;">a.n {{ $order->customer_name }}</span>
            </div>
            
            @foreach($order->items as $item)
                <div class="detail-item-row">
                    <span>{{ $item->menu_name }}</span>
                    <span class="qty-tag">x{{ $item->quantity }}</span>
                </div>
            @endforeach

            @if($order->notes)
                <div style="margin-top: 8px; font-size: 0.75rem; background: #FEF3C7; padding: 4px 8px; border-radius: 6px; color: #92400E;">
                    <i class="fa-solid fa-comment-dots"></i> Catatan: {{ $order->notes }}
                </div>
            @endif

            <div class="detail-total-row">
                <span class="total-title">TOTAL</span>
                <span class="total-val">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali Ke Menu -->
    <a href="{{ route('customer.menu', ['meja' => $order->table_number]) }}" class="return-menu-btn">
        KEMBALI KE MENU
    </a>
</div>
@endsection

@section('scripts')
<script>
    function switchCodeView(type) {
        const tabQr = document.getElementById('tabQr');
        const tabBarcode = document.getElementById('tabBarcode');
        const viewQr = document.getElementById('viewQr');
        const viewBarcode = document.getElementById('viewBarcode');

        if (type === 'qr') {
            tabQr.classList.add('active');
            tabBarcode.classList.remove('active');
            viewQr.style.display = 'flex';
            viewBarcode.style.display = 'none';
        } else {
            tabBarcode.classList.add('active');
            tabQr.classList.remove('active');
            viewQr.style.display = 'none';
            viewBarcode.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        try {
            if (typeof JsBarcode === 'function') {
                JsBarcode("#realBarcode", "{{ $order->order_code }}", {
                    format: "CODE128",
                    lineColor: "#111827",
                    width: 2.2,
                    height: 55,
                    displayValue: false,
                    margin: 0
                });
            }
        } catch (e) {
            console.error('Barcode render error:', e);
        }
    });
</script>
@endsection
