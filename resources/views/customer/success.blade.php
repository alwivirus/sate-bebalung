@extends('layouts.app')

@section('title', 'Pesanan Siap - Depot Sate Be Ba Lung')

@section('styles')
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
        font-size: 1.25rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 6px;
    }

    .card-subtitle {
        font-size: 0.82rem;
        color: #374151;
        font-weight: 600;
        line-height: 1.4;
        max-width: 280px;
        margin: 0 auto 18px auto;
    }

    /* Barcode Box */
    .barcode-box {
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 14px;
        padding: 16px 12px;
        margin-bottom: 18px;
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    .barcode-svg-wrapper {
        width: 100%;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .barcode-code-text {
        font-size: 0.95rem;
        font-weight: 900;
        letter-spacing: 2px;
        color: #111827;
        margin-top: 8px;
        font-family: monospace;
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
        font-size: 0.95rem;
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
        padding: 4px 10px;
        border-radius: 8px;
        border: 2px solid var(--dark-border);
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 14px;
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
        <p class="card-subtitle">Tunjukkan barcode ini kepada kasir untuk memproses pesanan Anda.</p>

        @if($order->payment_status === 'paid')
            <div class="payment-status-pill pill-paid">
                <i class="fa-solid fa-circle-check"></i> Sudah Dibayar (QRIS Online)
            </div>
        @else
            <div class="payment-status-pill pill-unpaid">
                <i class="fa-solid fa-clock"></i> Belum Dibayar (Bayar di Kasir)
            </div>
        @endif

        <!-- Barcode Box -->
        <div class="barcode-box">
            <div class="barcode-svg-wrapper">
                <!-- High Contrast Barcode SVG -->
                <svg width="240" height="60" viewBox="0 0 240 60">
                    <rect x="0" y="0" width="4" height="60" fill="#000"/>
                    <rect x="6" y="0" width="2" height="60" fill="#000"/>
                    <rect x="11" y="0" width="6" height="60" fill="#000"/>
                    <rect x="20" y="0" width="2" height="60" fill="#000"/>
                    <rect x="25" y="0" width="5" height="60" fill="#000"/>
                    <rect x="33" y="0" width="2" height="60" fill="#000"/>
                    <rect x="38" y="0" width="7" height="60" fill="#000"/>
                    <rect x="48" y="0" width="3" height="60" fill="#000"/>
                    <rect x="54" y="0" width="2" height="60" fill="#000"/>
                    <rect x="59" y="0" width="6" height="60" fill="#000"/>
                    <rect x="68" y="0" width="2" height="60" fill="#000"/>
                    <rect x="73" y="0" width="5" height="60" fill="#000"/>
                    <rect x="81" y="0" width="8" height="60" fill="#000"/>
                    <rect x="92" y="0" width="3" height="60" fill="#000"/>
                    <rect x="98" y="0" width="2" height="60" fill="#000"/>
                    <rect x="103" y="0" width="6" height="60" fill="#000"/>
                    <rect x="112" y="0" width="3" height="60" fill="#000"/>
                    <rect x="118" y="0" width="2" height="60" fill="#000"/>
                    <rect x="123" y="0" width="7" height="60" fill="#000"/>
                    <rect x="133" y="0" width="4" height="60" fill="#000"/>
                    <rect x="140" y="0" width="2" height="60" fill="#000"/>
                    <rect x="145" y="0" width="5" height="60" fill="#000"/>
                    <rect x="153" y="0" width="6" height="60" fill="#000"/>
                    <rect x="162" y="0" width="2" height="60" fill="#000"/>
                    <rect x="167" y="0" width="7" height="60" fill="#000"/>
                    <rect x="177" y="0" width="3" height="60" fill="#000"/>
                    <rect x="183" y="0" width="4" height="60" fill="#000"/>
                    <rect x="190" y="0" width="6" height="60" fill="#000"/>
                    <rect x="199" y="0" width="2" height="60" fill="#000"/>
                    <rect x="204" y="0" width="5" height="60" fill="#000"/>
                    <rect x="212" y="0" width="3" height="60" fill="#000"/>
                    <rect x="218" y="0" width="6" height="60" fill="#000"/>
                    <rect x="227" y="0" width="4" height="60" fill="#000"/>
                    <rect x="234" y="0" width="2" height="60" fill="#000"/>
                </svg>
            </div>
            <div class="barcode-code-text">{{ $order->order_code }}</div>
        </div>

        <!-- Detail Pesanan -->
        <div class="inner-detail-card">
            <div class="detail-heading">DETAIL PESANAN (Meja #{{ $order->table_number }})</div>
            
            @foreach($order->items as $item)
                <div class="detail-item-row">
                    <span>{{ $item->menu_name }}</span>
                    <span class="qty-tag">x{{ $item->quantity }}</span>
                </div>
            @endforeach

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
