@extends('layouts.admin')

@section('title', 'Scan Barcode Kasir & POS - Depot Sate Be Ba Lung')
@section('page-title', 'Kasir & Scan Barcode (POS System)')

@section('styles')
<!-- HTML5 QR Code & Barcode Scanner Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<style>
    .pos-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .pos-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Scanner Header Card */
    .scanner-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .search-barcode-input {
        flex: 1;
        font-size: 1.25rem;
        font-weight: 800;
        font-family: monospace;
        letter-spacing: 1.5px;
        padding: 16px 20px 16px 52px;
        border: 2.5px solid #F59E0B;
        border-radius: 12px;
        outline: none;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        transition: all 0.2s ease;
        background: #FFFDF7;
    }

    .search-barcode-input:focus {
        border-color: #D97706;
        box-shadow: 0 0 0 5px rgba(217, 119, 6, 0.25);
        background: white;
    }

    .input-barcode-icon {
        position: absolute;
        left: 18px;
        font-size: 1.4rem;
        color: #D97706;
        pointer-events: none;
    }

    .btn-camera {
        background: #111827;
        color: white;
        border: none;
        padding: 16px 22px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: background 0.15s;
    }

    .btn-camera:hover {
        background: #1F2937;
    }

    /* Result Order Card */
    .order-detail-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .order-header {
        background: #111827;
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-badge-large {
        background: #F59E0B;
        color: #111827;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 900;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .order-code-display {
        font-family: monospace;
        font-size: 1.35rem;
        font-weight: 900;
        letter-spacing: 2px;
        color: #FCD34D;
    }

    .order-body {
        padding: 24px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
    }

    .items-table th {
        background: #F9FAFB;
        padding: 10px 14px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #6B7280;
        text-align: left;
        border-bottom: 2px solid #E5E7EB;
        text-transform: uppercase;
    }

    .items-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #E5E7EB;
        font-size: 0.92rem;
    }

    .total-box-summary {
        background: #FEF3C7;
        border: 2px dashed #F59E0B;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .total-val-highlight {
        font-size: 1.5rem;
        font-weight: 900;
        color: #B45309;
    }

    /* Action Buttons */
    .actions-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-pay-action {
        background: #10B981;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 900;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s;
    }

    .btn-pay-action:hover {
        background: #059669;
    }

    .btn-print-action {
        background: #3B82F6;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 900;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-print-action:hover {
        background: #2563EB;
    }

    /* Status Pills */
    .status-pill {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pill-paid { background: #D1FAE5; color: #065F46; }
    .pill-unpaid { background: #FEE2E2; color: #991B1B; }

    /* Recent Queue Card */
    .queue-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .queue-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #E5E7EB;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .queue-item:hover {
        border-color: #F59E0B;
        background: #FFFBEB;
        transform: translateX(4px);
    }

    .queue-item.active {
        border: 2px solid #F59E0B;
        background: #FEF3C7;
    }

    /* Camera Modal */
    .camera-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.75);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .camera-box {
        background: white;
        border-radius: 20px;
        padding: 24px;
        max-width: 480px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
    }

    #reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        margin: 16px 0;
    }
</style>
@endsection

@section('content')
<!-- Search & Scanner Input Card -->
<div class="scanner-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #111827;">Scan Barcode Pesanan / Ketik Kode</h3>
            <p style="font-size: 0.85rem; color: #6B7280;">Gunakan alat Barcode Scanner USB, Kamera Laptop/HP, atau ketik kode di bawah barcode.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn-camera" onclick="openCameraScanner()">
                <i class="fa-solid fa-camera"></i>
                <span>Scan Kamera</span>
            </button>
        </div>
    </div>

    <div class="search-input-wrapper">
        <i class="fa-solid fa-barcode input-barcode-icon"></i>
        <input 
            type="text" 
            id="barcodeInput" 
            class="search-barcode-input" 
            placeholder="Scan Barcode / Ketik Kode (misal: ORD-8924-XYZ atau 8924)..." 
            value="{{ $code ?? '' }}"
            autofocus
            autocomplete="off"
        >
        <button type="button" class="btn-primary" style="padding: 16px 24px;" onclick="triggerManualSearch()">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Cari</span>
        </button>
    </div>

    <!-- Quick Hints -->
    <div style="display: flex; align-items: center; gap: 8px; margin-top: 12px; font-size: 0.8rem; color: #6B7280; flex-wrap: wrap;">
        <span style="font-weight: 700;">💡 Contoh Cepat:</span>
        @foreach($recentOrders->take(4) as $ro)
            <button type="button" onclick="loadOrderCode('{{ $ro->order_code }}')" style="background: #F3F4F6; border: 1px solid #D1D5DB; padding: 3px 8px; border-radius: 6px; font-family: monospace; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                {{ $ro->order_code }} (Meja #{{ $ro->table_number }})
            </button>
        @endforeach
    </div>
</div>

<div class="pos-grid">
    <!-- Left Column: Result & Order Action -->
    <div>
        <div id="orderResultContainer">
            @if($selectedOrder)
                <!-- Server rendered initial order if code passed in URL -->
                <div class="order-detail-card">
                    <div class="order-header">
                        <div>
                            <span class="table-badge-large">
                                <i class="fa-solid fa-qrcode"></i> Meja #{{ $selectedOrder->table_number }}
                            </span>
                            <span style="margin-left: 10px; font-size: 0.9rem; color: #D1D5DB;">{{ $selectedOrder->customer_name }}</span>
                        </div>
                        <div class="order-code-display">{{ $selectedOrder->order_code }}</div>
                    </div>

                    <div class="order-body">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div>
                                <span style="font-size: 0.8rem; color: #6B7280;">Waktu Pesan:</span>
                                <strong style="font-size: 0.85rem; color: #111827; margin-left: 4px;">{{ $selectedOrder->created_at->format('d M Y, H:i:s') }}</strong>
                            </div>
                            <div>
                                @if($selectedOrder->payment_status === 'paid')
                                    <span class="status-pill pill-paid">
                                        <i class="fa-solid fa-circle-check"></i> SUDAH LUNAS ({{ strtoupper($selectedOrder->payment_method) }})
                                    </span>
                                @else
                                    <span class="status-pill pill-unpaid">
                                        <i class="fa-solid fa-circle-exclamation"></i> BELUM BAYAR ({{ strtoupper($selectedOrder->payment_method) }})
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan Pembayaran Cash/QRIS -->
                        <div style="margin-bottom: 16px; font-size: 0.78rem; border-radius: 8px; padding: 6px 12px; font-weight: 700; {{ $selectedOrder->payment_method === 'kasir' ? 'background: #FEF3C7; border: 1px solid #F59E0B; color: #92400E;' : 'background: #ECFDF5; border: 1px solid #10B981; color: #065F46;' }}">
                            <i class="fa-solid {{ $selectedOrder->payment_method === 'kasir' ? 'fa-circle-info' : 'fa-circle-check' }}"></i>
                            {{ $selectedOrder->payment_method === 'kasir' ? 'Pesanan ini menggunakan Cash (Bayar di Kasir), tapi pendapatan tetap terhitung & tersimpan di database saat lunas.' : 'Pesanan menggunakan QRIS Online, pendapatan otomatis terhitung di sistem.' }}
                        </div>

                        <!-- Barcode Representation -->
                        <div style="background: #F9FAFB; border: 1.5px dashed #D1D5DB; border-radius: 10px; padding: 10px; text-align: center; margin-bottom: 16px;">
                            <svg width="200" height="40" viewBox="0 0 200 40">
                                <rect x="0" y="0" width="3" height="40" fill="#000"/>
                                <rect x="5" y="0" width="2" height="40" fill="#000"/>
                                <rect x="9" y="0" width="5" height="40" fill="#000"/>
                                <rect x="17" y="0" width="2" height="40" fill="#000"/>
                                <rect x="21" y="0" width="4" height="40" fill="#000"/>
                                <rect x="28" y="0" width="2" height="40" fill="#000"/>
                                <rect x="33" y="0" width="6" height="40" fill="#000"/>
                                <rect x="42" y="0" width="3" height="40" fill="#000"/>
                                <rect x="48" y="0" width="2" height="40" fill="#000"/>
                                <rect x="53" y="0" width="5" height="40" fill="#000"/>
                                <rect x="61" y="0" width="2" height="40" fill="#000"/>
                                <rect x="66" y="0" width="4" height="40" fill="#000"/>
                                <rect x="73" y="0" width="7" height="40" fill="#000"/>
                                <rect x="83" y="0" width="3" height="40" fill="#000"/>
                                <rect x="89" y="0" width="2" height="40" fill="#000"/>
                                <rect x="94" y="0" width="5" height="40" fill="#000"/>
                                <rect x="102" y="0" width="3" height="40" fill="#000"/>
                                <rect x="108" y="0" width="2" height="40" fill="#000"/>
                                <rect x="113" y="0" width="6" height="40" fill="#000"/>
                                <rect x="122" y="0" width="4" height="40" fill="#000"/>
                                <rect x="129" y="0" width="2" height="40" fill="#000"/>
                                <rect x="134" y="0" width="5" height="40" fill="#000"/>
                                <rect x="142" y="0" width="5" height="40" fill="#000"/>
                                <rect x="150" y="0" width="2" height="40" fill="#000"/>
                                <rect x="155" y="0" width="6" height="40" fill="#000"/>
                                <rect x="164" y="0" width="3" height="40" fill="#000"/>
                                <rect x="170" y="0" width="4" height="40" fill="#000"/>
                                <rect x="177" y="0" width="5" height="40" fill="#000"/>
                                <rect x="185" y="0" width="2" height="40" fill="#000"/>
                                <rect x="190" y="0" width="4" height="40" fill="#000"/>
                                <rect x="197" y="0" width="3" height="40" fill="#000"/>
                            </svg>
                            <div style="font-size: 0.8rem; font-family: monospace; font-weight: 800; letter-spacing: 2px;">{{ $selectedOrder->order_code }}</div>
                        </div>

                        <!-- Item list -->
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th style="text-align: center;">Qty</th>
                                    <th style="text-align: right;">Harga</th>
                                    <th style="text-align: right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedOrder->items as $item)
                                    <tr>
                                        <td>
                                            <strong style="color: #111827;">{{ $item->menu_name }}</strong>
                                            @if($item->notes)
                                                <div style="font-size: 0.75rem; color: #B45309;">
                                                    <i class="fa-solid fa-comment-dots"></i> {{ $item->notes }}
                                                </div>
                                            @endif
                                        </td>
                                        <td style="text-align: center; font-weight: 800;">x{{ $item->quantity }}</td>
                                        <td style="text-align: right; color: #4B5563;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td style="text-align: right; font-weight: 800; color: #111827;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($selectedOrder->notes)
                            <div style="background: #FEF3C7; padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; color: #92400E; margin-bottom: 16px;">
                                <strong>Catatan Pemesan:</strong> {{ $selectedOrder->notes }}
                            </div>
                        @endif

                        <div class="total-box-summary">
                            <span style="font-size: 1.1rem; font-weight: 900; color: #111827;">TOTAL PEMBAYARAN</span>
                            <span class="total-val-highlight">{{ $selectedOrder->formatted_total }}</span>
                        </div>

                        <!-- Foto Bukti Pembayaran Kasir / QRIS -->
                        <div style="background: #F9FAFB; border: 2px solid #E5E7EB; border-radius: 12px; padding: 14px; margin-top: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <strong style="font-size: 0.85rem; color: #111827; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-camera" style="color: #EA580C;"></i> Foto Bukti Pembayaran (Kasir / QRIS)
                                </strong>
                                @if($selectedOrder->payment_proof)
                                    <span style="font-size: 0.72rem; color: #065F46; background: #D1FAE5; padding: 2px 6px; border-radius: 4px; font-weight: 800;">
                                        <i class="fa-solid fa-circle-check"></i> Foto Tersimpan
                                    </span>
                                @endif
                            </div>

                            @if($selectedOrder->payment_proof)
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <a href="{{ asset($selectedOrder->payment_proof) }}" target="_blank" title="Klik untuk memperbesar">
                                        <img src="{{ asset($selectedOrder->payment_proof) }}" alt="Bukti {{ $selectedOrder->order_code }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 2px solid #111827; box-shadow: 2px 2px 0px #111827;">
                                    </a>
                                    <div style="flex: 1;">
                                        <div style="font-size: 0.78rem; color: #374151; font-weight: 700;">Foto bukti transfer / struk fisik</div>
                                        <form action="{{ route('admin.orders.upload-proof', $selectedOrder->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 4px;">
                                            @csrf
                                            <label style="cursor: pointer; background: #E5E7EB; color: #374151; padding: 4px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa-solid fa-camera-rotate"></i> Ganti / Foto Ulang
                                                <input type="file" name="proof_image" accept="image/*" capture="environment" style="display: none;" onchange="this.form.submit()">
                                            </label>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                    <span style="font-size: 0.78rem; color: #6B7280;">Belum ada foto bukti pembayaran untuk pesanan ini.</span>
                                    <form action="{{ route('admin.orders.upload-proof', $selectedOrder->id) }}" method="POST" enctype="multipart/form-data" style="margin: 0;">
                                        @csrf
                                        <label style="cursor: pointer; background: #EA580C; color: white; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; box-shadow: 2px 2px 0px #111827;">
                                            <i class="fa-solid fa-camera"></i> Foto Bukti
                                            <input type="file" name="proof_image" accept="image/*" capture="environment" style="display: none;" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="actions-row">
                            @if($selectedOrder->payment_status === 'unpaid')
                                <form action="{{ route('admin.orders.quick-pay', $selectedOrder->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-pay-action" style="width: 100%;">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                        <span>BAYAR LUNAS (CASH)</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn-pay-action" style="background: #6B7280; cursor: default;" disabled>
                                    <i class="fa-solid fa-check-double"></i>
                                    <span>SUDAH LUNAS</span>
                                </button>
                            @endif

                            <a href="{{ route('admin.orders.receipt', $selectedOrder->order_code) }}" target="_blank" class="btn-print-action">
                                <i class="fa-solid fa-print"></i>
                                <span>CETAK STRUK</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Default Placeholder -->
                <div class="order-detail-card" style="text-align: center; padding: 60px 24px; color: #9CA3AF;">
                    <div style="width: 90px; height: 90px; background: #FEF3C7; color: #D97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 18px auto;">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin-bottom: 8px;">Menunggu Scan Barcode Kasir</h3>
                    <p style="font-size: 0.9rem; max-width: 360px; margin: 0 auto 20px auto; line-height: 1.5;">
                        Arahkan alat barcode scanner ke barcode pelanggan di meja, atau ketik kode pesanan di atas untuk memproses pembayaran dan cetak struk.
                    </p>
                    <button type="button" class="btn-primary" onclick="openCameraScanner()">
                        <i class="fa-solid fa-camera"></i> Scan Menggunakan Kamera
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Queue / Recent Orders Today -->
    <div>
        <div class="queue-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 style="font-size: 1rem; font-weight: 800; color: #111827;">
                    <i class="fa-solid fa-list-check" style="color: #F59E0B;"></i> Antrean Pesanan Hari Ini
                </h4>
                <span style="font-size: 0.75rem; background: #F3F4F6; padding: 3px 8px; border-radius: 6px; font-weight: 700;">
                    {{ $recentOrders->count() }} Pesanan
                </span>
            </div>

            <div style="max-height: 600px; overflow-y: auto; padding-right: 4px;">
                @forelse($recentOrders as $order)
                    <div class="queue-item {{ isset($selectedOrder) && $selectedOrder->id === $order->id ? 'active' : '' }}" onclick="loadOrderCode('{{ $order->order_code }}')">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <strong style="font-family: monospace; font-size: 0.92rem; color: #111827;">{{ $order->order_code }}</strong>
                                <span style="background: #111827; color: white; padding: 1px 6px; border-radius: 4px; font-weight: 800; font-size: 0.7rem;">
                                    Meja #{{ $order->table_number }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; color: #6B7280;">
                                {{ $order->customer_name }} &bull; {{ $order->created_at->format('H:i') }} WIB &bull; {{ $order->items->count() }} Item
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 900; font-size: 0.9rem; color: #DC2626;">{{ $order->formatted_total }}</div>
                            <span class="status-pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}" style="padding: 2px 6px; font-size: 0.7rem; margin-top: 4px;">
                                {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 24px; color: #9CA3AF; font-size: 0.85rem;">
                        Belum ada pesanan hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Camera Barcode Scanner Modal -->
<div class="camera-modal" id="cameraModal">
    <div class="camera-box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="font-size: 1.1rem; font-weight: 800;">Scan Barcode Kamera</h4>
            <button type="button" onclick="closeCameraScanner()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #6B7280;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p style="font-size: 0.8rem; color: #6B7280; margin-top: 4px;">Arahkan kamera ke Barcode pelanggan.</p>
        
        <div id="reader"></div>

        <button type="button" class="btn-primary" style="width: 100%; justify-content: center;" onclick="closeCameraScanner()">
            Tutup Kamera
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let html5QrCodeScanner = null;
    let searchDebounceTimer = null;

    // Web Audio Synthesizer Beep (No external audio file needed)
    function playBeep(isSuccess = true) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.connect(gain);
            gain.connect(ctx.destination);

            if (isSuccess) {
                // Crisp POS scanner high beep
                osc.frequency.setValueAtTime(1200, ctx.currentTime);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.15);
            } else {
                // Error double buzz
                osc.frequency.setValueAtTime(350, ctx.currentTime);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.25);
            }
        } catch (e) {
            console.log('Audio error:', e);
        }
    }

    const barcodeInput = document.getElementById('barcodeInput');

    // Auto-listen for USB Hardware Barcode Scanners (which append Enter at the end of scan)
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            triggerManualSearch();
        }
    });

    // Realtime debounce search while typing
    barcodeInput.addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        const val = this.value.trim();
        if (val.length >= 3) {
            searchDebounceTimer = setTimeout(() => {
                fetchOrder(val);
            }, 400);
        }
    });

    function triggerManualSearch() {
        const val = barcodeInput.value.trim();
        if (val) {
            fetchOrder(val);
        }
    }

    function loadOrderCode(code) {
        barcodeInput.value = code;
        fetchOrder(code);
    }

    // Fetch order via AJAX and render dynamic POS card
    function fetchOrder(query) {
        const container = document.getElementById('orderResultContainer');
        container.innerHTML = `
            <div class="order-detail-card" style="text-align: center; padding: 48px; color: #D97706;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 12px;"></i>
                <p style="font-weight: 800;">Mencari data pesanan '${query}'...</p>
            </div>
        `;

        fetch(`{{ route('admin.orders.search') }}?q=${encodeURIComponent(query)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.order) {
                playBeep(true);
                renderOrderCard(data.order);
            } else {
                playBeep(false);
                container.innerHTML = `
                    <div class="order-detail-card" style="text-align: center; padding: 48px; color: #EF4444;">
                        <div style="width: 70px; height: 70px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 16px auto;">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #111827; margin-bottom: 6px;">Pesanan Tidak Ditemukan</h3>
                        <p style="font-size: 0.85rem; color: #6B7280; max-width: 320px; margin: 0 auto 16px auto;">
                            ${data.message || 'Pastikan barcode atau kode pesanan sudah benar.'}
                        </p>
                        <button type="button" class="btn-primary" onclick="barcodeInput.focus(); barcodeInput.select();">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        })
        .catch(err => {
            playBeep(false);
            container.innerHTML = `
                <div class="order-detail-card" style="text-align: center; padding: 48px; color: #EF4444;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 2rem; margin-bottom: 12px;"></i>
                    <p style="font-weight: 800;">Terjadi kesalahan saat memuat data pesanan.</p>
                </div>
            `;
        });
    }

    function renderOrderCard(order) {
        const isPaid = order.payment_status === 'paid';
        const receiptUrl = `{{ url('admin/orders') }}/${order.order_code}/receipt`;
        const quickPayUrl = `{{ url('admin/orders') }}/${order.id}/quick-pay`;

        let itemsHtml = '';
        order.items.forEach(item => {
            itemsHtml += `
                <tr>
                    <td>
                        <strong style="color: #111827;">${item.menu_name}</strong>
                        ${item.notes ? `<div style="font-size: 0.75rem; color: #B45309;"><i class="fa-solid fa-comment-dots"></i> ${item.notes}</div>` : ''}
                    </td>
                    <td style="text-align: center; font-weight: 800;">x${item.quantity}</td>
                    <td style="text-align: right; color: #4B5563;">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                    <td style="text-align: right; font-weight: 800; color: #111827;">${item.formatted_subtotal}</td>
                </tr>
            `;
        });

        const cardHtml = `
            <div class="order-detail-card">
                <div class="order-header">
                    <div>
                        <span class="table-badge-large">
                            <i class="fa-solid fa-qrcode"></i> Meja #${order.table_number}
                        </span>
                        <span style="margin-left: 10px; font-size: 0.9rem; color: #D1D5DB;">${order.customer_name}</span>
                    </div>
                    <div class="order-code-display">${order.order_code}</div>
                </div>

                <div class="order-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <div>
                            <span style="font-size: 0.8rem; color: #6B7280;">Waktu Pesan:</span>
                            <strong style="font-size: 0.85rem; color: #111827; margin-left: 4px;">${order.created_at_formatted}</strong>
                        </div>
                        <div id="paymentBadgeContainer">
                            ${isPaid ? `
                                <span class="status-pill pill-paid">
                                    <i class="fa-solid fa-circle-check"></i> SUDAH LUNAS (${order.payment_method.toUpperCase()})
                                </span>
                            ` : `
                                <span class="status-pill pill-unpaid">
                                    <i class="fa-solid fa-circle-exclamation"></i> BELUM BAYAR (${order.payment_method.toUpperCase()})
                                </span>
                            `}
                        </div>
                    </div>

                    <!-- Catatan Pembayaran Cash / QRIS -->
                    <div style="margin-bottom: 16px; font-size: 0.78rem; border-radius: 8px; padding: 6px 12px; font-weight: 700; ${order.payment_method === 'kasir' ? 'background: #FEF3C7; border: 1px solid #F59E0B; color: #92400E;' : 'background: #ECFDF5; border: 1px solid #10B981; color: #065F46;'}">
                        <i class="fa-solid ${order.payment_method === 'kasir' ? 'fa-circle-info' : 'fa-circle-check'}"></i>
                        ${order.payment_method === 'kasir' ? 'Pesanan ini menggunakan <strong>Cash (Bayar di Kasir)</strong>, tapi pendapatan tetap terhitung & tersimpan di database saat lunas.' : 'Pesanan menggunakan <strong>QRIS Online</strong>, pendapatan otomatis terhitung di sistem.'}
                    </div>

                    <!-- Barcode Representation -->
                    <div style="background: #F9FAFB; border: 1.5px dashed #D1D5DB; border-radius: 10px; padding: 10px; text-align: center; margin-bottom: 16px;">
                        <svg width="200" height="40" viewBox="0 0 200 40">
                            <rect x="0" y="0" width="3" height="40" fill="#000"/>
                            <rect x="5" y="0" width="2" height="40" fill="#000"/>
                            <rect x="9" y="0" width="5" height="40" fill="#000"/>
                            <rect x="17" y="0" width="2" height="40" fill="#000"/>
                            <rect x="21" y="0" width="4" height="40" fill="#000"/>
                            <rect x="28" y="0" width="2" height="40" fill="#000"/>
                            <rect x="33" y="0" width="6" height="40" fill="#000"/>
                            <rect x="42" y="0" width="3" height="40" fill="#000"/>
                            <rect x="48" y="0" width="2" height="40" fill="#000"/>
                            <rect x="53" y="0" width="5" height="40" fill="#000"/>
                            <rect x="61" y="0" width="2" height="40" fill="#000"/>
                            <rect x="66" y="0" width="4" height="40" fill="#000"/>
                            <rect x="73" y="0" width="7" height="40" fill="#000"/>
                            <rect x="83" y="0" width="3" height="40" fill="#000"/>
                            <rect x="89" y="0" width="2" height="40" fill="#000"/>
                            <rect x="94" y="0" width="5" height="40" fill="#000"/>
                            <rect x="102" y="0" width="3" height="40" fill="#000"/>
                            <rect x="108" y="0" width="2" height="40" fill="#000"/>
                            <rect x="113" y="0" width="6" height="40" fill="#000"/>
                            <rect x="122" y="0" width="4" height="40" fill="#000"/>
                            <rect x="129" y="0" width="2" height="40" fill="#000"/>
                            <rect x="134" y="0" width="5" height="40" fill="#000"/>
                            <rect x="142" y="0" width="5" height="40" fill="#000"/>
                            <rect x="150" y="0" width="2" height="40" fill="#000"/>
                            <rect x="155" y="0" width="6" height="40" fill="#000"/>
                            <rect x="164" y="0" width="3" height="40" fill="#000"/>
                            <rect x="170" y="0" width="4" height="40" fill="#000"/>
                            <rect x="177" y="0" width="5" height="40" fill="#000"/>
                            <rect x="185" y="0" width="2" height="40" fill="#000"/>
                            <rect x="190" y="0" width="4" height="40" fill="#000"/>
                            <rect x="197" y="0" width="3" height="40" fill="#000"/>
                        </svg>
                        <div style="font-size: 0.8rem; font-family: monospace; font-weight: 800; letter-spacing: 2px;">${order.order_code}</div>
                    </div>

                    <!-- Items Table -->
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Harga</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>

                    ${order.notes ? `
                        <div style="background: #FEF3C7; padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; color: #92400E; margin-bottom: 16px;">
                            <strong>Catatan Pemesan:</strong> ${order.notes}
                        </div>
                    ` : ''}

                    <div class="total-box-summary">
                        <span style="font-size: 1.1rem; font-weight: 900; color: #111827;">TOTAL PEMBAYARAN</span>
                        <span class="total-val-highlight">${order.formatted_total}</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="actions-row" id="orderActionsRow">
                        ${!isPaid ? `
                            <button type="button" class="btn-pay-action" onclick="executeQuickPay(${order.id}, '${order.order_code}')">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <span>BAYAR LUNAS (CASH)</span>
                            </button>
                        ` : `
                            <button type="button" class="btn-pay-action" style="background: #6B7280; cursor: default;" disabled>
                                <i class="fa-solid fa-check-double"></i>
                                <span>SUDAH LUNAS</span>
                            </button>
                        `}

                        <a href="${receiptUrl}" target="_blank" class="btn-print-action">
                            <i class="fa-solid fa-print"></i>
                            <span>CETAK STRUK</span>
                        </a>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('orderResultContainer').innerHTML = cardHtml;
    }

    // Ajax Quick Pay without full page reload
    function executeQuickPay(orderId, orderCode) {
        fetch(`{{ url('admin/orders') }}/${orderId}/quick-pay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                playBeep(true);
                // Reload order card
                fetchOrder(orderCode);
            }
        })
        .catch(err => {
            alert('Gagal memproses pembayaran: ' + err);
        });
    }

    // HTML5 Camera Barcode Scanner
    function openCameraScanner() {
        document.getElementById('cameraModal').style.display = 'flex';
        
        if (!html5QrCodeScanner) {
            html5QrCodeScanner = new Html5Qrcode("reader");
        }

        const config = { fps: 15, qrbox: { width: 280, height: 180 } };

        html5QrCodeScanner.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // Successfully scanned code
                playBeep(true);
                closeCameraScanner();
                barcodeInput.value = decodedText;
                fetchOrder(decodedText);
            },
            (errorMessage) => {
                // Scanning ongoing...
            }
        ).catch(err => {
            console.error("Camera access error:", err);
            alert("Tidak dapat mengakses kamera: " + err);
            closeCameraScanner();
        });
    }

    function closeCameraScanner() {
        document.getElementById('cameraModal').style.display = 'none';
        if (html5QrCodeScanner) {
            html5QrCodeScanner.stop().catch(err => console.log(err));
        }
    }
</script>
@endsection
