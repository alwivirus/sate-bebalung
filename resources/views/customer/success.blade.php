@extends('layouts.app')

@section('title', 'Pesanan Siap - Depot Sate Be Ba Lung')

@section('styles')
<!-- JsBarcode & QRCode Libraries for instant offline rendering -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<style>
    .success-container {
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 480px;
        margin: 0 auto;
    }

    /* Main Yellow Confirmation Card */
    .order-status-card {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 20px;
        box-shadow: var(--box-shadow-brutal);
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
        font-size: 0.80rem;
        color: #374151;
        font-weight: 700;
        line-height: 1.35;
        max-width: 320px;
        margin: 0 auto 12px auto;
    }

    /* Order Time Badge */
    .order-time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #FFFFFF;
        border: 2px solid var(--dark-border);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 12px;
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    /* Barcode / QR Code Box */
    .barcode-box {
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        padding: 16px 12px;
        margin-bottom: 18px;
        box-shadow: 4px 4px 0px var(--dark-border);
        position: relative;
    }

    .barcode-box-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #EA580C;
        margin-bottom: 10px;
    }

    .code-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        background: #F3F4F6;
        padding: 4px;
        border-radius: 12px;
        border: 1.5px solid #E5E7EB;
        margin-bottom: 12px;
    }

    .code-tab-btn {
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 8px 6px;
        font-size: 0.76rem;
        font-weight: 800;
        cursor: pointer;
        color: #4B5563;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .code-tab-btn.active {
        background: #111827;
        color: #FCD34D;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4px 0;
        width: 100%;
    }

    /* Scannable QR Box (Neo-Brutalist Square Box) */
    .qr-scanner-box {
        position: relative;
        width: 190px;
        height: 190px;
        max-width: 65vw;
        max-height: 65vw;
        aspect-ratio: 1 / 1;
        background: #FFFFFF;
        border: 3.5px solid #EA580C;
        border-radius: 18px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 4px 4px 0px var(--dark-border);
        margin: 0 auto 8px auto;
        cursor: pointer;
        box-sizing: border-box;
        transition: transform 0.15s ease;
        overflow: hidden;
        flex-shrink: 0;
    }

    .qr-scanner-box:hover {
        transform: scale(1.02);
    }

    .qr-scanner-box canvas,
    .qr-scanner-box img {
        width: 170px !important;
        height: 170px !important;
        max-width: 100% !important;
        max-height: 100% !important;
        aspect-ratio: 1 / 1 !important;
        object-fit: contain !important;
        display: block !important;
        margin: auto !important;
        image-rendering: pixelated;
    }

    .barcode-svg-wrapper {
        width: 100%;
        max-width: 100%;
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 14px;
        padding: 14px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 8px;
        cursor: pointer;
        transition: transform 0.15s ease;
    }

    .barcode-svg-wrapper:hover {
        transform: scale(1.01);
    }

    .barcode-svg-wrapper svg {
        width: 100%;
        max-width: 100%;
        height: auto;
        min-height: 65px;
        max-height: 85px;
        display: block;
    }

    .barcode-code-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .barcode-code-text {
        font-size: 1rem;
        font-weight: 900;
        letter-spacing: 1.5px;
        color: #111827;
        font-family: 'Courier New', Courier, monospace;
        background: #FEF3C7;
        padding: 5px 12px;
        border-radius: 8px;
        border: 1.5px solid var(--dark-border);
        box-shadow: 2px 2px 0px var(--dark-border);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy-code {
        background: #F3F4F6;
        border: 1.5px solid var(--dark-border);
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.72rem;
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
        padding: 4px 10px;
        font-size: 0.70rem;
        font-weight: 800;
        color: #92400E;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 6px;
        text-decoration: none;
    }

    .btn-fullscreen-toggle:hover {
        background: #FEF3C7;
    }

    .scan-tips-badge {
        font-size: 0.68rem;
        color: #6B7280;
        font-weight: 700;
        margin-top: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    /* Fullscreen Code Modal */
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
        border: 4px solid var(--dark-border);
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
        border: 2px solid var(--dark-border);
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

    /* Timeline Section */
    .timeline-card {
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 14px;
        text-align: left;
        box-shadow: 2px 2px 0px var(--dark-border);
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
        animation: pulse 1.5s infinite;
    }

    .step-pending {
        background: #F3F4F6;
        color: #9CA3AF;
        border-color: #D1D5DB;
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
        border: 2.5px solid var(--dark-border);
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

    /* Action Buttons */
    .action-buttons-stack {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 12px;
    }

    .print-receipt-btn {
        width: 100%;
        background-color: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: var(--box-shadow-brutal);
        padding: 12px 14px;
        font-size: 0.95rem;
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

    .print-receipt-btn:hover {
        background-color: #FEF3C7;
    }

    .print-receipt-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }

    .return-menu-btn {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: var(--box-shadow-brutal);
        padding: 12px 14px;
        font-size: 0.95rem;
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
        font-size: 0.78rem;
        font-weight: 800;
        margin-bottom: 12px;
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
        <p class="card-subtitle">Tunjukkan QR Code / Barcode ini kepada kasir atau simpan struk pesanan Anda.</p>

        <!-- Waktu Pembelian Pelanggan (WIB) -->
        <div class="order-time-badge">
            <i class="fa-solid fa-clock" style="color: #EA580C;"></i>
            <span>Waktu Beli: <strong>{{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i:s') : now('Asia/Jakarta')->format('d M Y, H:i:s') }} WIB</strong></span>
        </div>

        @if($order->payment_status === 'paid')
            <div>
                <div class="payment-status-pill pill-paid">
                    <i class="fa-solid fa-circle-check"></i> Sudah Dibayar (QRIS Online / Kasir)
                </div>
            </div>
        @else
            <div>
                <div class="payment-status-pill pill-unpaid">
                    <i class="fa-solid fa-clock"></i> Belum Dibayar (Bayar di Kasir)
                </div>
            </div>
        @endif

        <!-- Real Scannable Barcode & QR Box -->
        <div class="barcode-box">
            <div class="barcode-box-header">
                <i class="fa-solid fa-qrcode"></i>
                <span>Tunjukkan Kode Ini ke Kasir</span>
            </div>

            <div class="code-tabs">
                <button type="button" class="code-tab-btn active" id="tabQr" onclick="switchCodeView('qr')">
                    <i class="fa-solid fa-qrcode"></i> QR Code (Kamera Kasir)
                </button>
                <button type="button" class="code-tab-btn" id="tabBarcode" onclick="switchCodeView('barcode')">
                    <i class="fa-solid fa-barcode"></i> Barcode 1D (Scanner USB)
                </button>
            </div>

            <!-- View 1: Real High-Res Responsive QR Code -->
            <div id="viewQr" class="qr-container">
                <div class="qr-scanner-box" onclick="openFullscreenModal('qr')" title="Klik untuk memperbesar QR Code">
                    <canvas id="orderQrCanvas"></canvas>
                    <img 
                        src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($order->order_code) }}&margin=2" 
                        alt="QR Code {{ $order->order_code }}"
                        id="orderQrImg"
                        style="display: none;"
                        loading="eager"
                    >
                </div>
                <div style="font-size: 0.72rem; color: #4B5563; font-weight: 800; margin-bottom: 4px;">
                    <i class="fa-solid fa-camera" style="color: #EA580C;"></i> Scan langsung via Kamera HP / Tablet Kasir
                </div>
            </div>

            <!-- View 2: Real Scannable Code128 Barcode -->
            <div id="viewBarcode" style="display: none;">
                <div class="barcode-svg-wrapper" onclick="openFullscreenModal('barcode')" title="Klik untuk memperbesar Barcode">
                    <svg id="realBarcode"></svg>
                </div>
                <div style="font-size: 0.72rem; color: #4B5563; font-weight: 800; margin-bottom: 4px;">
                    <i class="fa-solid fa-barcode" style="color: #EA580C;"></i> Format Code128 untuk Alat Scanner Kasir
                </div>
            </div>

            <!-- Order Code & Action Controls -->
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
            <div style="margin-top: 8px;">
                <button type="button" class="btn-fullscreen-toggle" onclick="openFullscreenModal()">
                    <i class="fa-solid fa-maximize"></i> Perbesar Layar untuk Kasir
                </button>
            </div>

            <div class="scan-tips-badge">
                <i class="fa-solid fa-sun" style="color: #F59E0B;"></i>
                <span>Tingkatkan kecerahan layar HP saat di meja kasir</span>
            </div>
        </div>

        <!-- Timeline Status Pesanan -->
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

                <!-- Step 2: Pembayaran -->
                <div class="timeline-step">
                    @if($order->payment_status === 'paid')
                        <div class="step-icon step-done">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">2. Pembayaran Terverifikasi (Lunas)</div>
                            <div class="step-time">{{ $order->payment_method === 'online' ? 'QRIS Online' : 'Kasir Tunai' }}</div>
                        </div>
                    @else
                        <div class="step-icon step-active">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">2. Menunggu Pembayaran di Kasir</div>
                            <div class="step-time">Tunjukkan struk/barcode ini ke kasir</div>
                        </div>
                    @endif
                </div>

                <!-- Step 3: Dapur & Penyajian -->
                <div class="timeline-step">
                    @if($order->order_status === 'completed')
                        <div class="step-icon step-done">
                            <i class="fa-solid fa-bell-concierge"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">3. Hidangan Selesai Disajikan</div>
                            <div class="step-time">Selamat menikmati hidangan!</div>
                        </div>
                    @elseif($order->order_status === 'processing')
                        <div class="step-icon step-active">
                            <i class="fa-solid fa-fire-burner"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">3. Sedang Dimasak / Dibuat</div>
                            <div class="step-time">Pesanan sedang diproses di dapur</div>
                        </div>
                    @else
                        <div class="step-icon step-pending">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-label">3. Antrean Dapur</div>
                            <div class="step-time">Segera diproses &amp; disiapkan dapur</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="inner-detail-card">
            <div class="detail-heading">
                <span>DETAIL PESANAN (Meja #{{ $order->table_number }})</span>
                <span style="font-size: 0.72rem; color: #4B5563;">a.n {{ $order->customer_name }}</span>
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
                <span class="total-title">TOTAL</span>
                <span class="total-val">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="action-buttons-stack">
        <!-- Tombol Lihat Struk Digital Pelanggan -->
        <a href="{{ route('order.receipt', $order->order_code) }}" target="_blank" class="print-receipt-btn">
            <i class="fa-solid fa-receipt" style="color: #EA580C;"></i> LIHAT STRUK PESANAN
        </a>

        <!-- Tombol Kembali Ke Menu -->
        <a href="{{ route('customer.menu', ['meja' => \App\Models\Table::getSecureCode($order->table_number)]) }}" class="return-menu-btn">
            <i class="fa-solid fa-utensils"></i> PESAN MENU LAINNYA
        </a>
    </div>
</div>

<!-- Modal Fullscreen QR / Barcode Zoom for Cashier -->
<div class="fullscreen-modal" id="fullscreenCodeModal" onclick="handleModalBackdropClick(event)">
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
        <div style="font-size: 0.75rem; color: #6B7280; margin-bottom: 16px;">
            Total: <strong>{{ $order->formatted_total }}</strong> ({{ $order->payment_status === 'paid' ? 'Lunas' : 'Bayar di Kasir' }})
        </div>

        <!-- Fullscreen Switch Tabs -->
        <div class="code-tabs" style="margin-bottom: 16px;">
            <button type="button" class="code-tab-btn active" id="modalTabQr" onclick="switchModalCodeView('qr')">
                <i class="fa-solid fa-qrcode"></i> QR Code
            </button>
            <button type="button" class="code-tab-btn" id="modalTabBarcode" onclick="switchModalCodeView('barcode')">
                <i class="fa-solid fa-barcode"></i> Barcode 1D
            </button>
        </div>

        <!-- Modal QR View -->
        <div id="modalViewQr" style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 250px; height: 250px; background: white; padding: 10px; border: 2.5px solid #111827; border-radius: 16px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center;">
                <canvas id="modalOrderQrCanvas" style="max-width: 100%; max-height: 100%; display: block; margin: 0 auto;"></canvas>
                <img 
                    src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ urlencode($order->order_code) }}&margin=2" 
                    alt="QR Code {{ $order->order_code }}"
                    id="modalOrderQrImg"
                    style="display: none; width: 100%; height: 100%; object-fit: contain;"
                >
            </div>
            <div style="font-size: 0.75rem; color: #4B5563; font-weight: 800;">
                Tunjukkan langsung ke scanner / kamera kasir
            </div>
        </div>

        <!-- Modal Barcode View -->
        <div id="modalViewBarcode" style="display: none; flex-direction: column; align-items: center;">
            <div style="width: 100%; background: white; padding: 16px 8px; border: 2.5px solid #111827; border-radius: 16px; margin-bottom: 12px; overflow: hidden;">
                <svg id="modalRealBarcode" style="width: 100%; height: 90px;"></svg>
            </div>
            <div style="font-size: 0.75rem; color: #4B5563; font-weight: 800;">
                Arahkan laser scanner USB kasir ke barcode di atas
            </div>
        </div>

        <!-- Order Code Box -->
        <div style="margin-top: 14px; background: #FEF3C7; border: 2px solid #111827; border-radius: 10px; padding: 8px 14px; font-family: 'Courier New', Courier, monospace; font-size: 1.15rem; font-weight: 900; letter-spacing: 2px; color: #111827;">
            {{ $order->order_code }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentCodeType = 'qr';

    function switchCodeView(type) {
        currentCodeType = type;
        const tabQr = document.getElementById('tabQr');
        const tabBarcode = document.getElementById('tabBarcode');
        const viewQr = document.getElementById('viewQr');
        const viewBarcode = document.getElementById('viewBarcode');

        if (type === 'qr') {
            tabQr.classList.add('active');
            tabBarcode.classList.remove('active');
            viewQr.style.display = 'flex';
            viewBarcode.style.display = 'none';
            renderQrCode();
        } else {
            tabBarcode.classList.add('active');
            tabQr.classList.remove('active');
            viewQr.style.display = 'none';
            viewBarcode.style.display = 'block';
            renderBarcode();
        }
    }

    function switchModalCodeView(type) {
        const modalTabQr = document.getElementById('modalTabQr');
        const modalTabBarcode = document.getElementById('modalTabBarcode');
        const modalViewQr = document.getElementById('modalViewQr');
        const modalViewBarcode = document.getElementById('modalViewBarcode');

        if (type === 'qr') {
            modalTabQr.classList.add('active');
            modalTabBarcode.classList.remove('active');
            modalViewQr.style.display = 'flex';
            modalViewBarcode.style.display = 'none';
            renderModalQrCode();
        } else {
            modalTabBarcode.classList.add('active');
            modalTabQr.classList.remove('active');
            modalViewQr.style.display = 'none';
            modalViewBarcode.style.display = 'flex';
            renderModalBarcode();
        }
    }

    function openFullscreenModal(type) {
        const targetType = type || currentCodeType;
        const modal = document.getElementById('fullscreenCodeModal');
        modal.classList.add('active');
        switchModalCodeView(targetType);
    }

    function closeFullscreenModal() {
        const modal = document.getElementById('fullscreenCodeModal');
        modal.classList.remove('active');
    }

    function handleModalBackdropClick(e) {
        if (e.target.id === 'fullscreenCodeModal') {
            closeFullscreenModal();
        }
    }

    function copyOrderCode() {
        const code = "{{ $order->order_code }}";
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                showCopySuccess();
            }).catch(() => {
                fallbackCopy(code);
            });
        } else {
            fallbackCopy(code);
        }
    }

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCopySuccess();
    }

    function showCopySuccess() {
        const btn = document.getElementById('btnCopyCode');
        const copyText = document.getElementById('copyText');
        const origHtml = copyText.innerText;
        btn.style.background = '#86EFAC';
        btn.style.borderColor = '#10B981';
        btn.style.color = '#065F46';
        copyText.innerText = 'Tersalin!';
        setTimeout(() => {
            btn.style.background = '#F3F4F6';
            btn.style.borderColor = 'var(--dark-border)';
            btn.style.color = '#374151';
            copyText.innerText = origHtml;
        }, 1800);
    }

    function renderQrCode() {
        const orderCode = "{{ $order->order_code }}";
        const canvas = document.getElementById('orderQrCanvas');
        const fallbackImg = document.getElementById('orderQrImg');

        if (typeof QRCode !== 'undefined' && canvas) {
            QRCode.toCanvas(canvas, orderCode, {
                width: 176,
                margin: 2,
                errorCorrectionLevel: 'M',
                color: {
                    dark: '#111827',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    console.error('QRCode canvas error:', error);
                    if (fallbackImg) fallbackImg.style.display = 'block';
                    if (canvas) canvas.style.display = 'none';
                } else {
                    if (fallbackImg) fallbackImg.style.display = 'none';
                    if (canvas) {
                        canvas.style.display = 'block';
                    }
                }
            });
        } else if (fallbackImg) {
            fallbackImg.style.display = 'block';
        }
    }

    function renderModalQrCode() {
        const orderCode = "{{ $order->order_code }}";
        const modalCanvas = document.getElementById('modalOrderQrCanvas');
        const modalFallbackImg = document.getElementById('modalOrderQrImg');

        if (typeof QRCode !== 'undefined' && modalCanvas) {
            QRCode.toCanvas(modalCanvas, orderCode, {
                width: 230,
                margin: 2,
                errorCorrectionLevel: 'M',
                color: {
                    dark: '#111827',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    if (modalFallbackImg) modalFallbackImg.style.display = 'block';
                    if (modalCanvas) modalCanvas.style.display = 'none';
                } else {
                    if (modalFallbackImg) modalFallbackImg.style.display = 'none';
                    if (modalCanvas) {
                        modalCanvas.style.display = 'block';
                    }
                }
            });
        } else if (modalFallbackImg) {
            modalFallbackImg.style.display = 'block';
        }
    }

    function renderBarcode() {
        try {
            if (typeof JsBarcode === 'function') {
                JsBarcode("#realBarcode", "{{ $order->order_code }}", {
                    format: "CODE128",
                    lineColor: "#111827",
                    width: 2,
                    height: 70,
                    displayValue: false,
                    margin: 8,
                    background: "#FFFFFF"
                });
            }
        } catch (e) {
            console.error('Barcode render error:', e);
        }
    }

    function renderModalBarcode() {
        try {
            if (typeof JsBarcode === 'function') {
                JsBarcode("#modalRealBarcode", "{{ $order->order_code }}", {
                    format: "CODE128",
                    lineColor: "#111827",
                    width: 2.2,
                    height: 80,
                    displayValue: false,
                    margin: 8,
                    background: "#FFFFFF"
                });
            }
        } catch (e) {
            console.error('Modal Barcode render error:', e);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderQrCode();
        renderBarcode();
    });

    window.addEventListener('resize', function() {
        if (currentCodeType === 'barcode') {
            renderBarcode();
        } else {
            renderQrCode();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFullscreenModal();
        }
    });
</script>
@endsection
