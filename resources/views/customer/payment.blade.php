@extends('layouts.app')

@section('title', 'Pembayaran QRIS Online - Depot Sate Be Ba Lung')

@section('styles')
<style>
    /* Top App Navigation Bar */
    .payment-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #FFFFFF;
        border-bottom: 3px solid var(--dark-border, #111827);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .topbar-back-btn {
        background: #FEF3C7;
        border: 2px solid var(--dark-border, #111827);
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 900;
        color: #111827;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
        transition: transform 0.1s;
    }

    .topbar-back-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 1px 1px 0px #000;
    }

    .topbar-title {
        font-size: 0.85rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .topbar-table-badge {
        background: #111827;
        color: #FFB703;
        font-size: 0.72rem;
        font-weight: 900;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1.5px solid var(--dark-border, #111827);
    }

    /* Container */
    .payment-container {
        padding: 16px 14px 36px;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 480px;
        margin: 0 auto;
        width: 100%;
    }

    /* Top Floating Ribbon Ticker */
    .payment-ticker-bar {
        width: 100%;
        background: #111827;
        color: #FCD34D;
        border: 2.5px solid var(--dark-border, #111827);
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 0.75rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        box-shadow: 3px 3px 0px rgba(0,0,0,0.15);
    }

    /* Main Yellow Status Card (Matches Screenshot 1 & 2) */
    .order-status-card {
        width: 100%;
        background-color: var(--primary-yellow, #FFB703);
        border: 3.5px solid var(--dark-border, #111827);
        border-radius: 22px;
        box-shadow: 5px 6px 0px var(--dark-border, #111827);
        padding: 20px 14px;
        position: relative;
        text-align: center;
        overflow: hidden;
        margin-bottom: 16px;
    }

    /* Decorative Circle Top Right */
    .deco-circle {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 65px;
        height: 65px;
        background-color: #EA580C;
        border-radius: 50%;
        opacity: 0.85;
        pointer-events: none;
    }

    .card-title {
        font-size: 1.4rem;
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
        max-width: 330px;
        margin: 0 auto 12px auto;
    }

    /* Order Time Badge */
    .order-time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #FFFFFF;
        border: 2px solid var(--dark-border, #111827);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 0.76rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
    }

    /* Status Pill / Timer Badge */
    .payment-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 16px;
        border-radius: 20px;
        border: 2px solid var(--dark-border, #111827);
        font-size: 0.78rem;
        font-weight: 800;
        margin-bottom: 14px;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
    }

    .pill-timer {
        background: #FEE2E2;
        color: #DC2626;
    }

    /* White Barcode / QRIS Box */
    .barcode-box {
        background: #FFFFFF;
        border: 3px solid var(--dark-border, #111827);
        border-radius: 18px;
        padding: 16px 12px;
        margin-bottom: 14px;
        box-shadow: 4px 4px 0px var(--dark-border, #111827);
        position: relative;
    }

    .barcode-box-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.76rem;
        font-weight: 900;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #EA580C;
        margin-bottom: 12px;
    }

    /* Realistic QRIS Card Inside White Box */
    .qris-inner-card {
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        padding: 12px 10px;
        margin-bottom: 12px;
    }

    .qris-brand-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1.5px solid #F3F4F6;
    }

    .qris-logo-text {
        font-weight: 900;
        font-size: 1.15rem;
        color: #DC2626;
        letter-spacing: -0.5px;
        text-align: left;
        line-height: 1;
    }

    .qris-logo-sub {
        font-size: 0.54rem;
        color: #6B7280;
        font-weight: 700;
        display: block;
        letter-spacing: 0;
        margin-top: 2px;
    }

    .gpn-badge {
        background: #DC2626;
        color: white;
        padding: 2px 7px;
        border-radius: 4px;
        font-weight: 900;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
    }

    .qris-merchant-info {
        margin-bottom: 8px;
        text-align: center;
    }

    .qris-merchant-title {
        font-size: 0.95rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 2px;
    }

    .qris-merchant-nmid {
        font-size: 0.74rem;
        color: #4B5563;
        font-weight: 700;
        font-family: monospace;
    }

    .qris-scanner-frame {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.03);
        margin: 6px 0 10px;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .qris-scanner-frame:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.15);
    }

    /* Optical corner targeting brackets (Orange) */
    .scanner-corner {
        position: absolute;
        width: 16px;
        height: 16px;
        border-color: #EA580C;
        border-style: solid;
        pointer-events: none;
    }
    .corner-tl { top: 4px; left: 4px; border-width: 3.5px 0 0 3.5px; border-top-left-radius: 6px; }
    .corner-tr { top: 4px; right: 4px; border-width: 3.5px 3.5px 0 0; border-top-right-radius: 6px; }
    .corner-bl { bottom: 4px; left: 4px; border-width: 0 0 3.5px 3.5px; border-bottom-left-radius: 6px; }
    .corner-br { bottom: 4px; right: 4px; border-width: 0 3.5px 3.5px 0; border-bottom-right-radius: 6px; }

    .qr-image-wrapper {
        width: clamp(190px, 58vw, 240px);
        height: clamp(190px, 58vw, 240px);
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    /* Bank & E-Wallet Pills */
    .ewallet-pills-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 4px;
        margin-top: 6px;
    }

    .ewallet-pill {
        background: #F3F4F6;
        color: #374151;
        font-size: 0.62rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #D1D5DB;
    }

    .barcode-code-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .barcode-code-text {
        font-size: 0.98rem;
        font-weight: 900;
        letter-spacing: 1.2px;
        color: #111827;
        font-family: 'Courier New', Courier, monospace;
        background: #FEF3C7;
        padding: 5px 12px;
        border-radius: 8px;
        border: 1.5px solid var(--dark-border, #111827);
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy-code {
        background: #F3F4F6;
        border: 1.5px solid var(--dark-border, #111827);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.74rem;
        font-weight: 800;
        color: #374151;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.1s ease;
    }

    .btn-copy-code:active {
        transform: scale(0.95);
        background: #E5E7EB;
    }

    .btn-fullscreen-toggle {
        background: #FFFBEB;
        border: 1.5px solid #FCD34D;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #92400E;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
        text-decoration: none;
    }

    .btn-fullscreen-toggle:hover {
        background: #FEF3C7;
    }

    .scan-tips-badge {
        font-size: 0.68rem;
        color: #6B7280;
        font-weight: 700;
        margin-top: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    /* Verification & Upload Bukti Card */
    .proof-box {
        width: 100%;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border, #111827);
        border-radius: 14px;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
        padding: 14px 12px;
        margin-bottom: 14px;
        text-align: left;
    }

    .proof-box-title {
        font-size: 0.82rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .proof-tabs-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        background: #F3F4F6;
        padding: 4px;
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        margin-bottom: 12px;
    }

    .proof-tab-btn {
        padding: 8px 6px;
        font-size: 0.76rem;
        font-weight: 800;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #4B5563;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .proof-tab-btn.active {
        background: #111827;
        color: #FCD34D;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .tab-content-panel {
        display: none;
    }

    .tab-content-panel.active {
        display: block;
    }

    /* Upload Dropzone */
    .upload-dropzone {
        width: 100%;
        background: #FFFBEB;
        border: 2px dashed #D97706;
        border-radius: 12px;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.80rem;
        font-weight: 800;
        color: #92400E;
        cursor: pointer;
        margin-bottom: 10px;
        transition: all 0.2s;
        text-align: center;
    }

    .upload-dropzone:hover {
        background: #FEF3C7;
        border-color: #B45309;
    }

    .upload-preview-card {
        display: none;
        background: #F0FDF4;
        border: 2px solid #10B981;
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 10px;
        align-items: center;
        gap: 12px;
    }

    .upload-preview-card.show {
        display: flex;
    }

    .preview-thumb-img {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        border: 1.5px solid #111827;
        background: #FFF;
        flex-shrink: 0;
    }

    .preview-info {
        flex: 1;
        min-width: 0;
    }

    .preview-name {
        font-size: 0.78rem;
        font-weight: 800;
        color: #065F46;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .preview-size {
        font-size: 0.68rem;
        color: #047857;
        font-weight: 700;
        margin-top: 1px;
    }

    .btn-remove-preview {
        background: #FEE2E2;
        border: 1.5px solid #EF4444;
        color: #DC2626;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 0.70rem;
        font-weight: 800;
        cursor: pointer;
        flex-shrink: 0;
    }

    .btn-submit-action {
        width: 100%;
        background-color: var(--primary-yellow, #FFB703);
        border: 2.5px solid var(--dark-border, #111827);
        border-radius: 12px;
        box-shadow: 2.5px 2.5px 0px var(--dark-border, #111827);
        padding: 12px;
        font-size: 0.92rem;
        font-weight: 900;
        color: #111827;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.1s, box-shadow 0.1s;
        text-decoration: none;
    }

    .btn-submit-action:hover {
        background-color: #FCD34D;
    }

    .btn-submit-action:active {
        transform: translate(2px, 2px);
        box-shadow: 0.5px 0.5px 0px #000;
    }

    /* Timeline Section */
    .timeline-card {
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border, #111827);
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 14px;
        text-align: left;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
    }

    .timeline-title {
        font-size: 0.82rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .timeline-steps {
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        padding-left: 6px;
    }

    .timeline-step {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        position: relative;
    }

    .timeline-step::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 24px;
        bottom: -10px;
        width: 2px;
        background: #E5E7EB;
    }

    .timeline-step:last-child::before {
        display: none;
    }

    .step-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
        border: 1.5px solid #1E1E1E;
        z-index: 2;
    }

    .step-done {
        background: #10B981;
        color: #FFFFFF;
    }

    .step-active {
        background: #FFB703;
        color: #111827;
        animation: pulseStep 1.5s infinite;
    }

    .step-pending {
        background: #F3F4F6;
        color: #9CA3AF;
        border-color: #D1D5DB;
    }

    @keyframes pulseStep {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 183, 3, 0.7); }
        70% { transform: scale(1.08); box-shadow: 0 0 0 6px rgba(255, 183, 3, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 183, 3, 0); }
    }

    .step-content {
        flex: 1;
    }

    .step-label {
        font-size: 0.78rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1.2;
    }

    .step-time {
        font-size: 0.68rem;
        color: #6B7280;
        font-weight: 700;
        margin-top: 1px;
    }

    /* Inner Detail Pesanan Card */
    .inner-detail-card {
        background-color: #FCD34D;
        border: 2.5px solid var(--dark-border, #111827);
        border-radius: 14px;
        padding: 12px 14px;
        text-align: left;
    }

    .detail-heading {
        font-size: 0.82rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 8px;
        letter-spacing: 0.3px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 5px;
    }

    .detail-item-row .qty-tag {
        font-weight: 900;
        color: #4B5563;
    }

    .detail-total-row {
        border-top: 2px dashed #1E1E1E;
        padding-top: 8px;
        margin-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 900;
    }

    .detail-total-row .total-title {
        font-size: 0.90rem;
        color: #111827;
    }

    .detail-total-row .total-val {
        font-size: 1.05rem;
        color: #DC2626;
    }

    /* Exterior Support & Actions */
    .action-buttons-stack {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 4px;
        margin-bottom: 12px;
    }

    .return-menu-btn {
        width: 100%;
        background-color: #FFFFFF;
        border: 3px solid var(--dark-border, #111827);
        border-radius: 16px;
        box-shadow: var(--box-shadow-brutal, 4px 4px 0px #111827);
        padding: 12px 14px;
        font-size: 0.92rem;
        font-weight: 900;
        color: #111827;
        text-align: center;
        text-decoration: none;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform 0.1s;
    }

    .return-menu-btn:hover {
        background-color: #FEF3C7;
    }

    .return-menu-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }

    .help-box-exterior {
        width: 100%;
        background: #FFFFFF;
        border: 2px solid var(--dark-border, #111827);
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 2px 2px 0px var(--dark-border, #111827);
        margin-bottom: 10px;
        font-size: 0.74rem;
        color: #4B5563;
        line-height: 1.35;
    }

    .help-icon-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #FEF3C7;
        border: 1.5px solid var(--dark-border, #111827);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #D97706;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    /* Fullscreen Modal Zoom */
    .fullscreen-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(17, 24, 39, 0.92);
        backdrop-filter: blur(8px);
        padding: 20px 16px;
        align-items: center;
        justify-content: center;
    }

    .fullscreen-modal.active {
        display: flex;
    }

    .fullscreen-modal-card {
        background: #FFFFFF;
        border: 4px solid var(--dark-border, #111827);
        border-radius: 24px;
        box-shadow: 6px 6px 0px #000;
        width: 100%;
        max-width: 380px;
        padding: 24px 18px;
        text-align: center;
        position: relative;
        max-height: 92vh;
        overflow-y: auto;
        animation: modalPop 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes modalPop {
        from { transform: scale(0.85); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .fullscreen-close-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        background: #F3F4F6;
        border: 2px solid var(--dark-border, #111827);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 900;
        color: #111827;
        cursor: pointer;
        transition: all 0.1s;
    }

    .fullscreen-close-btn:hover {
        background: #EF4444;
        color: white;
    }
</style>
@endsection

@section('content')
<!-- Top Nav Bar -->
<div class="payment-topbar">
    <a href="{{ route('customer.menu', ['meja' => \App\Models\Table::getSecureCode($order->table_number)]) }}" class="topbar-back-btn">
        <i class="fa-solid fa-arrow-left"></i> Menu
    </a>
    <div class="topbar-title">
        <i class="fa-solid fa-utensils" style="color: #EA580C;"></i> SATE BE BA LUNG
    </div>
    <div class="topbar-table-badge">
        MEJA #{{ $order->table_number }}
    </div>
</div>

<div class="payment-container">
    <!-- Top Info Ticker Banner -->
    <div class="payment-ticker-bar">
        <span><i class="fa-solid fa-bolt" style="color: #FBBF24;"></i> SCAN &amp; BAYAR INSTAN</span>
        <span><i class="fa-solid fa-shield-check" style="color: #34D399;"></i> 100% TERVERIFIKASI</span>
    </div>

    <!-- Main Yellow Card Container -->
    <div class="order-status-card">
        <div class="deco-circle"></div>

        <h2 class="card-title">Pembayaran QRIS</h2>
        <p class="card-subtitle">Tunjukkan QR Code ini kepada kasir atau scan langsung via m-Banking &amp; E-Wallet Anda.</p>

        <!-- Waktu Beli Badge -->
        <div class="order-time-badge">
            <i class="fa-solid fa-clock" style="color: #EA580C;"></i>
            <span>Waktu Beli: <strong>{{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i:s') : now('Asia/Jakarta')->format('d M Y, H:i:s') }} WIB</strong></span>
        </div>

        <!-- Timer / Payment Status Pill -->
        <div>
            <div class="payment-status-pill pill-timer">
                <i class="fa-solid fa-stopwatch"></i> Batas Waktu Bayar: <span id="countdown" style="margin-left: 2px;">05:00</span>
            </div>
        </div>

        <!-- 1. Scannable Official QRIS Box -->
        <div class="barcode-box">
            <div class="barcode-box-header">
                <i class="fa-solid fa-qrcode"></i>
                <span>TUNJUKKAN KODE INI KE KASIR</span>
            </div>

            @php
                $customQrisImage = \App\Models\Setting::get('qris_image');
                $customMerchant = \App\Models\Setting::get('qris_merchant_name', 'SATE KAMBING BE BA LUNG');
                $customNmid = \App\Models\Setting::get('qris_nmid', 'ID1025428876474');

                $qrisPath = 'images/qris_official.png';
                if ($customQrisImage && (file_exists(public_path($customQrisImage)) || file_exists(base_path($customQrisImage)))) {
                    $qrisPath = $customQrisImage;
                }
            @endphp

            <!-- QRIS Inner Realistic Banner -->
            <div class="qris-inner-card">
                <div class="qris-brand-bar">
                    <div class="qris-logo-text">
                        QRIS
                        <span class="qris-logo-sub">QR Code Standar Pembayaran Nasional</span>
                    </div>
                    <div class="gpn-badge">GPN</div>
                </div>

                <div class="qris-merchant-info">
                    <div class="qris-merchant-title">{{ $customMerchant }}</div>
                    <div class="qris-merchant-nmid">NMID : {{ $customNmid }}</div>
                </div>

                <!-- QR Scanner Frame with Optical Corner Brackets -->
                <div class="qris-scanner-frame" onclick="openFullscreenModal()" title="Klik untuk memperbesar QRIS">
                    <div class="scanner-corner corner-tl"></div>
                    <div class="scanner-corner corner-tr"></div>
                    <div class="scanner-corner corner-bl"></div>
                    <div class="scanner-corner corner-br"></div>
                    <div class="qr-image-wrapper">
                        <img 
                            src="{{ asset($qrisPath) }}" 
                            alt="QRIS {{ $customMerchant }}"
                            id="orderQrisImg"
                            loading="eager"
                        >
                    </div>
                </div>

                <div style="font-size: 0.72rem; color: #4B5563; font-weight: 800; margin-bottom: 2px;">
                    <i class="fa-solid fa-camera" style="color: #EA580C;"></i> Scan langsung via Kamera HP / Tablet Kasir
                </div>

                <div class="ewallet-pills-row">
                    <span class="ewallet-pill">BCA</span>
                    <span class="ewallet-pill">Mandiri</span>
                    <span class="ewallet-pill">BRI</span>
                    <span class="ewallet-pill">GoPay</span>
                    <span class="ewallet-pill">OVO</span>
                    <span class="ewallet-pill">DANA</span>
                    <span class="ewallet-pill">ShopeePay</span>
                </div>
            </div>

            <!-- Order Code & Copy Controls -->
            <div class="barcode-code-wrapper">
                <div class="barcode-code-text">
                    <i class="fa-solid fa-hashtag" style="color: #EA580C; font-size: 0.85rem;"></i>
                    <span id="orderCodeSpan">{{ $order->order_code }}</span>
                </div>
                <button type="button" class="btn-copy-code" onclick="copyOrderCode()" id="btnCopyCode" title="Salin Kode Pesanan">
                    <i class="fa-regular fa-copy"></i>
                    <span id="copyText">Salin</span>
                </button>
            </div>

            <!-- Expand Fullscreen Button -->
            <div style="margin-top: 6px;">
                <button type="button" class="btn-fullscreen-toggle" onclick="openFullscreenModal()">
                    <i class="fa-solid fa-maximize"></i> Perbesar Layar untuk Kasir
                </button>
            </div>

            <div class="scan-tips-badge">
                <i class="fa-solid fa-sun" style="color: #F59E0B;"></i>
                <span>Tingkatkan kecerahan layar HP saat di meja kasir</span>
            </div>
        </div>

        <!-- 2. Detail Pesanan & Total Tagihan (Tepat di bawah QRIS agar pelanggan langsung tahu nominal yang harus dibayar) -->
        <div class="inner-detail-card">
            <div class="detail-heading">
                <span><i class="fa-solid fa-receipt" style="color: #EA580C;"></i> DETAIL PESANAN (Meja #{{ $order->table_number }})</span>
                <span style="font-size: 0.72rem; color: #4B5563; font-weight: 800;">a.n {{ $order->customer_name }}</span>
            </div>
            
            @foreach($order->items as $item)
                <div class="detail-item-row">
                    <span>{{ $item->menu_name }}</span>
                    <span class="qty-tag">x{{ $item->quantity }}</span>
                </div>
            @endforeach

            @if($order->notes)
                <div style="margin-top: 6px; font-size: 0.72rem; background: #FEF3C7; padding: 4px 8px; border-radius: 6px; color: #92400E;">
                    <i class="fa-solid fa-comment-dots"></i> Catatan: {{ $order->notes }}
                </div>
            @endif

            <div class="detail-total-row">
                <span class="total-title">TOTAL HARGA HARUS DIBAYAR</span>
                <span class="total-val">{{ $order->formatted_total }}</span>
            </div>
        </div>

        <!-- 3. Timeline Status Pesanan -->
        <div class="timeline-card">
            <div class="timeline-title">
                <i class="fa-solid fa-timeline" style="color: #EA580C;"></i> Timeline Pesanan
            </div>
            <div class="timeline-steps">
                <!-- Step 1: Dipesan -->
                <div class="timeline-step">
                    <div class="step-icon step-done">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="step-content">
                        <div class="step-label">1. Pesanan Diterima Dapur</div>
                        <div class="step-time">Waktu beli: {{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->format('H:i:s') : date('H:i:s') }} WIB</div>
                    </div>
                </div>

                <!-- Step 2: Pembayaran (Active / Waiting) -->
                <div class="timeline-step">
                    <div class="step-icon step-active">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div class="step-content">
                        <div class="step-label">2. Menunggu Pembayaran QRIS</div>
                        <div class="step-time">QRIS Online / Unggah Bukti</div>
                    </div>
                </div>

                <!-- Step 3: Dapur & Penyajian (Pending) -->
                <div class="timeline-step">
                    <div class="step-icon step-pending">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div class="step-content">
                        <div class="step-label">3. Sedang Dimasak / Dibuat</div>
                        <div class="step-time">Pesanan sedang diproses di dapur</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Verification / Upload Bukti Transfer Card -->
        <div class="proof-box">
            <div class="proof-box-title">
                <i class="fa-solid fa-shield-halved" style="color: #EA580C;"></i> Verifikasi Pembayaran
            </div>

            <!-- Tab Selector: Upload Foto vs Tunjukkan di Kasir -->
            <div class="proof-tabs-header">
                <button type="button" class="proof-tab-btn active" id="tabBtnUpload" onclick="switchProofTab('upload')">
                    <i class="fa-solid fa-camera"></i> Upload Foto Bukti
                </button>
                <button type="button" class="proof-tab-btn" id="tabBtnShow" onclick="switchProofTab('show')">
                    <i class="fa-solid fa-store"></i> Tunjukkan Kasir
                </button>
            </div>

            <!-- PANEL 1: Upload Foto Bukti Pembayaran -->
            <div class="tab-content-panel active" id="panelUpload">
                @if($order->payment_proof)
                    <div style="background: #ECFDF5; border: 2px solid #10B981; border-radius: 12px; padding: 10px; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset($order->payment_proof) }}" alt="Bukti" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1.5px solid #111827;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 0.8rem; font-weight: 900; color: #065F46;">
                                <i class="fa-solid fa-circle-check"></i> Foto Bukti Tersimpan!
                            </div>
                            <div style="font-size: 0.7rem; color: #047857;">Kasir akan memeriksa bukti transfer ini.</div>
                        </div>
                    </div>
                @endif

                <p style="font-size: 0.76rem; color: #4B5563; margin-bottom: 10px; line-height: 1.35;">
                    Unggah screenshot transfer QRIS dari aplikasi m-banking atau e-wallet Anda agar pesanan langsung diproses.
                </p>

                <!-- Form Upload Bukti -->
                <form id="formUploadProof" action="{{ route('order.payment.upload-proof', ['order_code' => $order->order_code]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <label class="upload-dropzone" id="dropzoneContainer" for="proofFileInput">
                        <i class="fa-solid fa-cloud-arrow-up" style="color: #EA580C; font-size: 1.4rem;"></i>
                        <span id="dropzoneText">{{ $order->payment_proof ? 'Ganti Foto / Upload Ulang Bukti' : 'Pilih Foto / Jepret Bukti Transfer' }}</span>
                        <span style="font-size: 0.68rem; color: #6B7280; font-weight: 600;">Format: JPG, PNG, WEBP (Maks 10MB)</span>
                        <input type="file" id="proofFileInput" name="payment_proof" accept="image/*" style="display: none;" onchange="handleFileSelected(this)">
                    </label>

                    <!-- Realtime Image Preview Card -->
                    <div class="upload-preview-card" id="previewCard">
                        <img src="" alt="Preview" class="preview-thumb-img" id="previewImg">
                        <div class="preview-info">
                            <div class="preview-name" id="previewFileName">bukti_transfer.jpg</div>
                            <div class="preview-size" id="previewFileSize">120 KB</div>
                        </div>
                        <button type="button" class="btn-remove-preview" onclick="removeSelectedFile()">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </button>
                    </div>

                    <button type="submit" class="btn-submit-action" id="btnUploadSubmit" style="margin-bottom: 10px;">
                        <i class="fa-solid fa-upload"></i>
                        <span>KIRIM BUKTI PEMBAYARAN</span>
                    </button>
                </form>

                <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-submit-action" style="background-color: #FFFFFF; font-size: 0.82rem; padding: 9px 12px;">
                        <i class="fa-solid fa-circle-check" style="color: #10B981;"></i>
                        <span>Saya Sudah Bayar (Konfirmasi Manual)</span>
                    </button>
                </form>
            </div>

            <!-- PANEL 2: Tunjukkan Layar Langsung ke Kasir -->
            <div class="tab-content-panel" id="panelShow">
                <div style="background: #FFFBEB; border: 2px solid #FCD34D; border-radius: 12px; padding: 12px; margin-bottom: 12px; font-size: 0.76rem; color: #92400E; line-height: 1.45;">
                    <div style="font-weight: 900; margin-bottom: 6px; display: flex; align-items: center; gap: 5px;">
                        <i class="fa-solid fa-hand-holding-dollar" style="color: #D97706;"></i> Langkah Pembayaran di Kasir:
                    </div>
                    1. Bawa smartphone Anda ke meja kasir.<br>
                    2. Tunjukkan kode pesanan atau struk di layar HP.<br>
                    3. Kasir akan memvalidasi pembayaran Anda.
                </div>

                <form action="{{ route('order.payment.confirm', ['order_code' => $order->order_code]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-submit-action">
                        <span>SAYA SUDAH BAYAR DI KASIR</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Exterior Support & Actions -->
    <div class="help-box-exterior">
        <div class="help-icon-circle">
            <i class="fa-solid fa-headset"></i>
        </div>
        <div>
            <strong style="color: #111827; display: block; font-size: 0.78rem;">Butuh Bantuan Kasir?</strong>
            <span>Jika mengalami kendala pembayaran, silakan hubungi kasir atau staf Depot Sate Be Ba Lung.</span>
        </div>
    </div>

    <div class="action-buttons-stack">
        <a href="{{ route('customer.menu', ['meja' => \App\Models\Table::getSecureCode($order->table_number)]) }}" class="return-menu-btn">
            <i class="fa-solid fa-utensils"></i> PESAN MENU LAINNYA
        </a>
    </div>
</div>

<!-- Modal Fullscreen Zoom QRIS -->
<div class="fullscreen-modal" id="fullscreenQrisModal" onclick="handleModalBackdropClick(event)">
    <div class="fullscreen-modal-card" onclick="event.stopPropagation()">
        <button type="button" class="fullscreen-close-btn" onclick="closeFullscreenModal()">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div style="font-size: 0.75rem; font-weight: 900; color: #EA580C; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
            DEPOT SATE BE BA LUNG
        </div>
        <div style="font-size: 1.15rem; font-weight: 900; color: #111827; margin-bottom: 2px;">
            MEJA #{{ $order->table_number }} &bull; {{ $order->customer_name }}
        </div>
        <div style="font-size: 0.80rem; color: #6B7280; margin-bottom: 14px;">
            Total Tagihan: <strong style="color: #DC2626; font-size: 0.95rem;">{{ $order->formatted_total }}</strong>
        </div>

        <div style="background: #FFF; border: 2.5px solid #111827; border-radius: 16px; padding: 12px; margin-bottom: 12px;">
            <img 
                src="{{ asset($qrisPath) }}" 
                alt="QRIS {{ $customMerchant }}"
                style="width: 100%; max-width: 280px; height: auto; object-fit: contain; display: block; margin: 0 auto;"
            >
        </div>

        <div style="font-size: 0.75rem; color: #4B5563; font-weight: 800;">
            <i class="fa-solid fa-sun" style="color: #F59E0B;"></i> Tingkatkan kecerahan layar HP saat di meja kasir
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switcher between Upload Proof and Show to Cashier
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

    // Client-side instant image preview
    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewFileName').innerText = file.name;
                
                // Format file size
                const sizeKb = (file.size / 1024).toFixed(1);
                const sizeStr = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB';
                document.getElementById('previewFileSize').innerText = sizeStr;

                document.getElementById('previewCard').classList.add('show');
                document.getElementById('dropzoneContainer').style.display = 'none';
            }

            reader.readAsDataURL(file);
        }
    }

    function removeSelectedFile() {
        const input = document.getElementById('proofFileInput');
        input.value = '';
        document.getElementById('previewCard').classList.remove('show');
        document.getElementById('dropzoneContainer').style.display = 'flex';
    }

    // Copy order code helper
    function copyOrderCode() {
        const orderCode = "{{ $order->order_code }}";
        const copyTextSpan = document.getElementById('copyText');
        const btnCopy = document.getElementById('btnCopyCode');

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(orderCode).then(() => {
                showCopiedFeedback();
            }).catch(() => {
                fallbackCopy(orderCode);
            });
        } else {
            fallbackCopy(orderCode);
        }

        function showCopiedFeedback() {
            copyTextSpan.innerText = 'Tersalin!';
            btnCopy.style.background = '#86EFAC';
            btnCopy.style.borderColor = '#10B981';
            btnCopy.style.color = '#065F46';
            setTimeout(() => {
                copyTextSpan.innerText = 'Salin';
                btnCopy.style.background = '#F3F4F6';
                btnCopy.style.borderColor = 'var(--dark-border, #111827)';
                btnCopy.style.color = '#374151';
            }, 2000);
        }

        function fallbackCopy(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback();
            } catch (err) {}
            textArea.remove();
        }
    }

    // Fullscreen Modal Zoom
    function openFullscreenModal() {
        const modal = document.getElementById('fullscreenQrisModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeFullscreenModal() {
        const modal = document.getElementById('fullscreenQrisModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function handleModalBackdropClick(event) {
        if (event.target === document.getElementById('fullscreenQrisModal')) {
            closeFullscreenModal();
        }
    }

    // Escape key listener for modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeFullscreenModal();
        }
    });

    // 5 minutes countdown timer (300 seconds)
    let timeLeft = 300;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            if (countdownEl) countdownEl.innerText = "00:00";
        } else {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            if (countdownEl) {
                countdownEl.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }
    }, 1000);

    // Auto-polling order status every 5 seconds in background
    const pollInterval = setInterval(() => {
        fetch("{{ route('order.status', ['order_code' => $order->order_code]) }}")
            .then(res => res.json())
            .then(data => {
                if (data.payment_status === 'paid') {
                    clearInterval(pollInterval);
                    window.location.href = "{{ route('order.success', ['order_code' => $order->order_code]) }}";
                }
            })
            .catch(() => {});
    }, 5000);
</script>
@endsection
