@extends('layouts.admin')

@section('title', 'Monitoring Pesanan & Meja - Kasir & Admin')
@section('page-title', 'Live Monitoring Pesanan (Scan Meja)')

@section('styles')
<style>
    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Filter Pills */
    .filter-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .filter-pill {
        padding: 8px 14px;
        border-radius: 10px;
        background: white;
        border: 1.5px solid var(--border-color);
        text-decoration: none;
        color: #4B5563;
        font-weight: 800;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .filter-pill:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    .filter-pill.active {
        background: #111827;
        color: white;
        border-color: #111827;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }

    /* Table Realtime Floor Grid */
    .floor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
    }

    .floor-table-card {
        border: 2px solid #E5E7EB;
        background: #FFFFFF;
        border-radius: 14px;
        padding: 12px 10px;
        text-align: center;
        position: relative;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .floor-table-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: #EA580C;
    }

    .floor-table-card.occupied {
        border-color: #F59E0B;
        background: #FFFBEB;
    }

    .table-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .table-dot.available {
        background: #10B981;
        box-shadow: 0 0 6px #10B981;
    }

    .table-dot.occupied {
        background: #EA580C;
        box-shadow: 0 0 6px #EA580C;
    }

    /* Orders Table */
    .order-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .order-table th {
        background: #F8FAFC;
        padding: 14px 16px;
        font-size: 0.78rem;
        font-weight: 800;
        color: #475569;
        border-bottom: 2px solid #E2E8F0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .order-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    .order-table tr:hover td {
        background: #FAFAFA;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .badge-pending { background: #FEF3C7; color: #92400E; }
    .badge-processing { background: #DBEAFE; color: #1E40AF; }
    .badge-completed { background: #D1FAE5; color: #065F46; }
    .badge-cancelled { background: #FEE2E2; color: #991B1B; }

    .badge-paid { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
    .badge-unpaid { background: #FFEDD5; color: #C2410C; border: 1px solid #FED7AA; }

    .action-select {
        padding: 6px 10px;
        border-radius: 8px;
        border: 1.5px solid #D1D5DB;
        font-size: 0.8rem;
        font-weight: 700;
        background: white;
        outline: none;
        cursor: pointer;
    }

    .action-select:focus {
        border-color: #EA580C;
    }

    /* Modal */
    .dashboard-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(3px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .dashboard-modal-card {
        background: white;
        border-radius: 18px;
        max-width: 400px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.3);
        border: 2px solid #111827;
        position: relative;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper">

    <!-- 1. Stats Grid: Omset Harian, Mingguan, Bulanan & Pesanan -->
    <div class="stats-grid">
        <div class="stat-card" style="border-left: 4px solid #10B981;">
            <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div>
                <div class="stat-val" style="color: #065F46;">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div style="font-size: 0.7rem; color: #6B7280; margin-top: 2px;">
                    💵 Cash: Rp {{ number_format($stats['cash_revenue_today'], 0, ',', '.') }} | 📱 QRIS: Rp {{ number_format($stats['qris_revenue_today'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left: 4px solid #3B82F6;">
            <div class="stat-icon" style="background: #DBEAFE; color: #2563EB;">
                <i class="fa-solid fa-calendar-week"></i>
            </div>
            <div>
                <div class="stat-val" style="color: #1E40AF;">Rp {{ number_format($stats['revenue_week'], 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Minggu Ini</div>
                <div style="font-size: 0.7rem; color: #6B7280; margin-top: 2px;">
                    Akumulasi Senin - Minggu
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left: 4px solid #8B5CF6;">
            <div class="stat-icon" style="background: #EDE9FE; color: #7C3AED;">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <div class="stat-val" style="color: #5B21B6;">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Bulan Ini</div>
                <div style="font-size: 0.7rem; color: #6B7280; margin-top: 2px;">
                    Bulan {{ now()->translatedFormat('F Y') }}
                </div>
            </div>
        </div>

        <div class="stat-card" style="border-left: 4px solid #F59E0B;">
            <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="stat-val">{{ $stats['total_orders_today'] }}</div>
                <div class="stat-label">Pesanan Hari Ini</div>
                <div style="font-size: 0.7rem; color: #DC2626; font-weight: 700; margin-top: 2px;">
                    {{ $stats['unpaid_count'] }} Menunggu Pembayaran
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Widget Monitoring Status Meja Realtime (Klik Kartu Meja Mana Saja) -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="table-dot available"></span>
                <h3 style="font-size: 0.95rem; font-weight: 800; color: #111827;">Live Status Meja Pelanggan</h3>
                <span style="font-size: 0.75rem; color: #6B7280;">(Klik kartu meja untuk aksi cepat)</span>
            </div>
            <div style="display: flex; gap: 12px; align-items: center; font-size: 0.75rem; font-weight: 700;">
                <span style="color: #059669; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-circle" style="font-size: 0.55rem;"></i> {{ $occupiedTablesCount }} Terpakai
                </span>
                <span style="color: #6B7280; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fa-regular fa-circle" style="font-size: 0.55rem;"></i> {{ $liveTables->count() - $occupiedTablesCount }} Kosong
                </span>
                <a href="{{ route('admin.tables.index') }}" style="color: #EA580C; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-qrcode"></i> Kelola QR &rarr;
                </a>
            </div>
        </div>

        <div class="floor-grid">
            @foreach($liveTables as $t)
                <div class="floor-table-card {{ $t->status === 'occupied' ? 'occupied' : '' }}" onclick="openTableDetailModal('{{ $t->table_number }}', '{{ $t->status }}', '{{ addslashes($t->current_customer_name ?? '') }}', '{{ addslashes($t->current_order_code ?? '') }}')">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <strong style="font-size: 0.92rem; color: #111827;">Meja #{{ $t->table_number }}</strong>
                        <span class="table-dot {{ $t->status === 'occupied' ? 'occupied' : 'available' }}" title="{{ $t->status === 'occupied' ? 'Sedang Digunakan' : 'Tersedia' }}"></span>
                    </div>

                    @if($t->status === 'occupied')
                        <div style="font-size: 0.72rem; color: #B45309; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;">
                            <i class="fa-solid fa-user"></i> {{ $t->current_customer_name ?: 'Sedang Pesan' }}
                        </div>
                    @else
                        <div style="font-size: 0.72rem; color: #059669; font-weight: 800; margin-bottom: 4px;">
                            <i class="fa-solid fa-check"></i> Tersedia
                        </div>
                    @endif

                    <!-- Quick release button if occupied -->
                    @if($t->status === 'occupied')
                        <form action="{{ route('admin.tables.release', $t->table_number) }}" method="POST" style="margin-top: 4px;" onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit" style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; font-size: 0.68rem; font-weight: 800; padding: 4px 6px; border-radius: 6px; cursor: pointer; width: 100%;" title="Kosongkan Meja">
                                <i class="fa-solid fa-rotate-left"></i> Kosongkan
                            </button>
                        </form>
                    @else
                        <div style="font-size: 0.65rem; color: #9CA3AF; margin-top: 4px;">
                            Siap Pesan
                        </div>
                    @endif

                    <!-- Print Button ONLY visible for Developer role -->
                    @if(auth()->user() && auth()->user()->role === 'developer')
                        <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed #D1D5DB;" onclick="event.stopPropagation();">
                            <a href="{{ route('admin.tables.print-single', $t->table_number) }}" target="_blank" style="background: #111827; color: #FCD34D; padding: 2px 6px; border-radius: 4px; font-size: 0.62rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 3px; width: 100%;">
                                <i class="fa-solid fa-print"></i> Dev Print
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. Filters Status & Pembayaran -->
    <div>
        <div class="filter-pills">
            <a href="{{ route('admin.dashboard') }}" class="filter-pill {{ empty($statusFilter) && empty($paymentFilter) ? 'active' : '' }}">Semua Pesanan</a>
            <a href="{{ route('admin.dashboard', ['payment' => 'cash']) }}" class="filter-pill {{ $paymentFilter === 'cash' ? 'active' : '' }}" style="{{ $paymentFilter === 'cash' ? 'background: #DC2626; border-color: #DC2626; color: white;' : 'color: #DC2626;' }}">
                <i class="fa-solid fa-cash-register"></i> Bayar di Kasir (Cash)
            </a>
            <a href="{{ route('admin.dashboard', ['payment' => 'online']) }}" class="filter-pill {{ $paymentFilter === 'online' ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode"></i> QRIS Online
            </a>
            <a href="{{ route('admin.dashboard', ['payment' => 'unpaid']) }}" class="filter-pill {{ $paymentFilter === 'unpaid' ? 'active' : '' }}" style="{{ $paymentFilter === 'unpaid' ? 'background: #EA580C; border-color: #EA580C; color: white;' : 'color: #EA580C;' }}">
                <i class="fa-solid fa-clock"></i> Belum Lunas
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="filter-pill {{ $statusFilter === 'pending' ? 'active' : '' }}">Pesanan Baru</a>
            <a href="{{ route('admin.dashboard', ['status' => 'processing']) }}" class="filter-pill {{ $statusFilter === 'processing' ? 'active' : '' }}">Sedang Dimasak</a>
            <a href="{{ route('admin.dashboard', ['status' => 'completed']) }}" class="filter-pill {{ $statusFilter === 'completed' ? 'active' : '' }}">Selesai</a>
        </div>
    </div>

    <!-- 4. Orders Table (Live POS) -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 1rem; font-weight: 800;">Aktivitas Pesanan Masuk (Live POS)</h3>
                <span style="font-size: 0.78rem; color: #6B7280;">Kelola status pesanan &amp; konfirmasi bayar</span>
            </div>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('admin.settings.qris') }}" class="btn-primary" style="background: #111827; color: white; padding: 7px 12px; font-size: 0.8rem;">
                    <i class="fa-solid fa-gear"></i>
                    <span>Pengaturan QRIS</span>
                </a>
                <a href="{{ route('admin.scan') }}" class="btn-primary" style="padding: 7px 12px; font-size: 0.8rem;">
                    <i class="fa-solid fa-barcode"></i>
                    <span>POS Scanner</span>
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Kode Order</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Rincian Menu</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status Bayar</th>
                        <th>Status Order</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.receipt', $order->order_code) }}" target="_blank" style="font-weight: 800; color: #EA580C; text-decoration: none; font-family: monospace;">
                                    #{{ $order->order_code }}
                                </a>
                                <div style="font-size: 0.7rem; color: #9CA3AF; margin-top: 2px;">{{ $order->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <span style="background: #FEF3C7; color: #92400E; font-weight: 900; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem;">
                                    #{{ $order->table_number }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #111827;">{{ $order->customer_name }}</strong>
                            </td>
                            <td>
                                <div style="max-width: 220px; font-size: 0.82rem; color: #374151; line-height: 1.3;">
                                    @foreach($order->items as $item)
                                        <div>{{ $item->quantity }}x {{ $item->menu_name ?: ($item->menu->name ?? 'Menu') }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <strong style="color: #111827;">{{ $order->formatted_total }}</strong>
                            </td>
                            <td>
                                @if($order->payment_method === 'online')
                                    <span class="badge" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;">
                                        <i class="fa-solid fa-qrcode"></i> QRIS
                                    </span>
                                @else
                                    <span class="badge" style="background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA;">
                                        <i class="fa-solid fa-cash-register"></i> Kasir
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                                    {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <select name="order_status" class="action-select" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>🍳 Dimasak</option>
                                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>❌ Batal</option>
                                    </select>
                                </form>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 4px;">
                                    @if($order->payment_status !== 'paid')
                                        <form action="{{ route('admin.orders.confirm-cash', $order->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Konfirmasi pelunasan uang untuk pesanan ini?')">
                                            @csrf
                                            <button type="submit" style="background: #10B981; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; cursor: pointer;" title="Terima Pembayaran">
                                                <i class="fa-solid fa-check"></i> Lunas
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.receipt', $order->order_code) }}" target="_blank" style="background: #F3F4F6; color: #111827; border: 1px solid #D1D5DB; padding: 6px 9px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; text-decoration: none;" title="Cetak Struk">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 32px; color: #9CA3AF;">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; color: #D1D5DB;"></i>
                                Belum ada pesanan masuk pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Interactive Table QR & Detail Modal -->
<div class="dashboard-modal-overlay" id="tableDetailModal" onclick="closeTableDetailModal()">
    <div class="dashboard-modal-card" onclick="event.stopPropagation();">
        <button type="button" onclick="closeTableDetailModal()" style="position: absolute; top: 12px; right: 12px; background: #F3F4F6; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 1rem; font-weight: 900; color: #4B5563; cursor: pointer; display: flex; align-items: center; justify-content: center;">
            &times;
        </button>

        <div style="margin-bottom: 8px;">
            <span style="background: #111827; color: #FCD34D; font-size: 1.15rem; font-weight: 900; padding: 4px 14px; border-radius: 8px; display: inline-block;" id="modalTableNumber">
                MEJA #01
            </span>
        </div>

        <p style="font-size: 0.78rem; color: #6B7280; margin-bottom: 10px;" id="modalTableStatusText">Status: Tersedia</p>

        <!-- Large QR Code Frame for Scanning from Screen -->
        <div style="width: 190px; height: 190px; margin: 0 auto 12px auto; background: white; border: 2.5px solid #111827; border-radius: 14px; padding: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
            <img id="modalTableQrImg" src="" alt="QR Code Meja" style="width: 100%; height: 100%; object-fit: contain;">
        </div>

        <div style="font-size: 0.72rem; color: #4B5563; margin-bottom: 12px; font-weight: 700;">
            <i class="fa-solid fa-qrcode" style="color: #EA580C;"></i> Pelanggan dapat scan QR di atas langsung dari layar ini.
        </div>

        <div id="modalOccupiedDetails" style="display: none; background: #FFFBEB; border: 1.5px solid #FCD34D; border-radius: 10px; padding: 10px 12px; margin-bottom: 12px; font-size: 0.8rem; color: #92400E; text-align: left;">
            <div style="font-weight: 800;" id="modalCustomerName">Pelanggan: -</div>
            <div style="font-size: 0.72rem; color: #B45309; margin-top: 2px;" id="modalOrderCode">Order ID: -</div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px;">
            <div id="modalReleaseContainer" style="display: none;">
                <form id="modalReleaseForm" action="" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="width: 100%; background: #EF4444; color: white; padding: 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 800; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-rotate-left"></i> Kosongkan Meja Ini
                    </button>
                </form>
            </div>

            <!-- Uji Meja & Cetak Standee button ONLY visible for Developer -->
            @if(auth()->user() && auth()->user()->role === 'developer')
                <a href="#" id="modalTestLink" target="_blank" style="background: #EA580C; color: white; padding: 9px; border-radius: 10px; font-size: 0.82rem; font-weight: 800; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fa-solid fa-mobile-screen"></i> Buka Menu Pelanggan (Uji Meja - Dev)
                </a>

                <a href="#" id="modalPrintLink" target="_blank" style="background: #111827; color: #FCD34D; padding: 9px; border-radius: 10px; font-size: 0.82rem; font-weight: 800; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fa-solid fa-print"></i> Cetak Standee Akrilik Meja (Dev)
                </a>
            @endif

            <button type="button" onclick="closeTableDetailModal()" style="background: #F3F4F6; color: #4B5563; padding: 9px; border-radius: 10px; font-size: 0.82rem; font-weight: 800; border: none; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openTableDetailModal(tableNum, status, customerName, orderCode) {
        try {
            const modal = document.getElementById('tableDetailModal');
            if (!modal) return;

            const modalTableNumber = document.getElementById('modalTableNumber');
            const modalTableStatusText = document.getElementById('modalTableStatusText');
            const modalTableQrImg = document.getElementById('modalTableQrImg');
            const modalOccupiedDetails = document.getElementById('modalOccupiedDetails');
            const modalCustomerName = document.getElementById('modalCustomerName');
            const modalOrderCode = document.getElementById('modalOrderCode');
            const modalTestLink = document.getElementById('modalTestLink');
            const modalReleaseContainer = document.getElementById('modalReleaseContainer');
            const modalReleaseForm = document.getElementById('modalReleaseForm');
            const modalPrintLink = document.getElementById('modalPrintLink');

            if (modalTableNumber) {
                modalTableNumber.innerText = `MEJA #${tableNum}`;
            }

            const secureTableUrls = {
                @for($i = 1; $i <= 50; $i++)
                    @php $tNum = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    "{{ $tNum }}": "{{ \App\Models\Table::getSecureScanUrl($tNum) }}",
                @endfor
            };

            const scanUrl = secureTableUrls[tableNum] || `{{ url('/') }}`;
            if (modalTableQrImg) {
                modalTableQrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(scanUrl)}&margin=0`;
            }

            if (modalTestLink) {
                modalTestLink.href = scanUrl;
            }

            if (modalPrintLink) {
                modalPrintLink.href = `{{ url('/admin/tables') }}/${tableNum}/print`;
            }

            if (status === 'occupied') {
                if (modalTableStatusText) {
                    modalTableStatusText.innerHTML = '<span style="color: #EA580C; font-weight: 800;"><i class="fa-solid fa-circle"></i> Sedang Digunakan</span>';
                }
                if (modalOccupiedDetails) {
                    modalOccupiedDetails.style.display = 'block';
                }
                if (modalCustomerName) {
                    modalCustomerName.innerText = `Pelanggan: ${customerName || 'Aktif'}`;
                }
                if (modalOrderCode) {
                    modalOrderCode.innerText = orderCode ? `Order Code: #${orderCode}` : '';
                }
                if (modalReleaseContainer) {
                    modalReleaseContainer.style.display = 'block';
                }
                if (modalReleaseForm) {
                    modalReleaseForm.action = `{{ url('/admin/tables') }}/${tableNum}/release`;
                }
            } else {
                if (modalTableStatusText) {
                    modalTableStatusText.innerHTML = '<span style="color: #10B981; font-weight: 800;"><i class="fa-solid fa-circle-check"></i> Tersedia (Kosong)</span>';
                }
                if (modalOccupiedDetails) {
                    modalOccupiedDetails.style.display = 'none';
                }
                if (modalReleaseContainer) {
                    modalReleaseContainer.style.display = 'none';
                }
            }

            modal.style.display = 'flex';
        } catch (err) {
            console.error('Error opening table modal:', err);
        }
    }

    function closeTableDetailModal() {
        const modal = document.getElementById('tableDetailModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
