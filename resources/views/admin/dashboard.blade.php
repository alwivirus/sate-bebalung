@extends('layouts.admin')

@section('title', 'Monitoring Pesanan - Kasir & Dapur')
@section('page-title', 'Live Monitoring Pesanan (Scan Meja)')

@section('styles')
<style>
    .filter-pills {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 8px 14px;
        border-radius: 8px;
        background: white;
        border: 1px solid var(--border-color);
        text-decoration: none;
        color: #4B5563;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .filter-pill.active {
        background: #111827;
        color: white;
        border-color: #111827;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-table th {
        background: #F9FAFB;
        padding: 12px 16px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #6B7280;
        text-align: left;
        border-bottom: 2px solid #E5E7EB;
        text-transform: uppercase;
    }

    .order-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #E5E7EB;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 800;
        display: inline-block;
    }

    .badge-pending { background: #FEF3C7; color: #B45309; }
    .badge-processing { background: #DBEAFE; color: #1D4ED8; }
    .badge-completed { background: #D1FAE5; color: #065F46; }
    .badge-cancelled { background: #FEE2E2; color: #B91C1C; }

    .badge-paid { background: #D1FAE5; color: #065F46; }
    .badge-unpaid { background: #FFEDD5; color: #C2410C; }

    .action-select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #D1D5DB;
        font-size: 0.8rem;
        font-weight: 700;
        background: white;
    }
</style>
@endsection

@section('content')
<!-- Stats Grid: Omset Harian, Mingguan, Bulanan & Pesanan -->
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

<!-- Widget Monitoring Status Meja Realtime (Live Gacoan Floor Map) -->
<div class="card" style="margin-bottom: 20px; padding: 18px 20px; background: white; border: 1.5px solid var(--border-color); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #10B981; border-radius: 50%; box-shadow: 0 0 8px #10B981;"></span>
            <h3 style="font-size: 0.95rem; font-weight: 800; color: #111827;">Status Meja Realtime (Live Terhubung)</h3>
        </div>
        <div style="display: flex; gap: 12px; align-items: center; font-size: 0.75rem; font-weight: 700;">
            <span style="color: #059669; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-circle" style="font-size: 0.6rem;"></i> {{ $occupiedTablesCount }} Meja Terpakai
            </span>
            <span style="color: #6B7280; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-regular fa-circle" style="font-size: 0.6rem;"></i> {{ $liveTables->count() - $occupiedTablesCount }} Meja Kosong
            </span>
            <a href="{{ route('admin.tables.index') }}" style="color: #EA580C; text-decoration: none; font-weight: 800;">
                <i class="fa-solid fa-qrcode"></i> Kelola & Cetak QR Meja &rarr;
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px;">
        @foreach($liveTables as $t)
            <div style="border: 1.5px solid {{ $t->status === 'occupied' ? '#F59E0B' : '#E5E7EB' }}; background: {{ $t->status === 'occupied' ? '#FFFBEB' : '#F9FAFB' }}; border-radius: 10px; padding: 8px 10px; text-align: center; position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                    <strong style="font-size: 0.85rem; color: #111827;">Meja #{{ $t->table_number }}</strong>
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $t->status === 'occupied' ? '#EA580C' : '#9CA3AF' }};"></span>
                </div>

                @if($t->status === 'occupied')
                    <div style="font-size: 0.7rem; color: #B45309; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <i class="fa-solid fa-mobile-screen"></i> {{ $t->current_customer_name ?: 'Sedang Pesan' }}
                    </div>
                    <form action="{{ route('admin.tables.release', $t->table_number) }}" method="POST" style="margin-top: 4px;">
                        @csrf
                        <button type="submit" style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; cursor: pointer; width: 100%;" title="Klik jika tamu sudah selesai makan">
                            Kosongkan
                        </button>
                    </form>
                @else
                    <div style="font-size: 0.7rem; color: #9CA3AF; font-weight: 700;">
                        Tersedia
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Filters Status & Pembayaran -->
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

<!-- Orders Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 1rem; font-weight: 800;">Aktivitas Pesanan Masuk (Live POS)</h3>
            <span style="font-size: 0.78rem; color: #6B7280;">Kelola transaksi pelanggan & konfirmasi kasir</span>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.settings.qris') }}" class="btn-primary" style="background: #111827; color: white; padding: 8px 14px; font-size: 0.85rem;">
                <i class="fa-solid fa-gear"></i>
                <span>Ganti QRIS</span>
            </a>
            <a href="{{ route('admin.scan') }}" class="btn-primary" style="padding: 8px 14px; font-size: 0.85rem;">
                <i class="fa-solid fa-barcode"></i>
                <span>Scan Barcode Kasir</span>
            </a>
        </div>
    </div>

    <!-- Catatan Aktivitas Sistem Otomatis -->
    <div style="background: #EFF6FF; border-bottom: 1px solid #DBEAFE; padding: 10px 20px; font-size: 0.78rem; color: #1E40AF; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
        <div>
            <i class="fa-solid fa-circle-check" style="color: #2563EB;"></i> 
            <strong>Catatan Aktivitas Kasir:</strong> Semua pesanan yang dibayar dengan <strong>QRIS</strong> maupun <strong>Cash (Tunai)</strong> otomatis tercatat di sistem &amp; pendapatan tetap terhitung masuk ke omset Harian, Mingguan, dan Bulanan.
        </div>
        <span style="background: #DBEAFE; color: #1E40AF; font-size: 0.72rem; font-weight: 800; padding: 2px 8px; border-radius: 6px;">
            <i class="fa-solid fa-database"></i> Database Sync Active
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Kode / Meja</th>
                    <th>Detail Item Pesanan</th>
                    <th>Total</th>
                    <th>Metode & Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th>Aksi Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="{{ $order->payment_status === 'unpaid' && $order->payment_method === 'kasir' ? 'background: #FFFBEB;' : '' }}">
                        <td>
                            <strong style="color: #111827; font-family: monospace; font-size: 0.95rem;">{{ $order->order_code }}</strong>
                            <div style="margin-top: 4px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <span style="background: #111827; color: #FCD34D; padding: 2px 8px; border-radius: 6px; font-weight: 900; font-size: 0.8rem;">
                                    Meja #{{ $order->table_number }}
                                </span>
                                <strong style="font-size: 0.85rem; color: #111827;">{{ $order->customer_name }}</strong>
                            </div>
                            <div style="font-size: 0.72rem; color: #6B7280; margin-top: 4px;">
                                <i class="fa-solid fa-clock"></i> {{ $order->created_at->format('H:i:s, d M Y') }}
                            </div>
                        </td>
                        <td>
                            <ul style="list-style: none; margin: 0; padding: 0;">
                                @foreach($order->items as $item)
                                    <li style="font-size: 0.85rem; margin-bottom: 3px;">
                                        <strong>{{ $item->quantity }}x</strong> {{ $item->menu_name }}
                                        <span style="color: #6B7280; font-size: 0.78rem;">(Rp {{ number_format($item->subtotal, 0, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if($order->notes)
                                <div style="margin-top: 6px; font-size: 0.78rem; background: #FEF3C7; padding: 4px 8px; border-radius: 4px; color: #92400E;">
                                    <i class="fa-solid fa-comment-dots"></i> Catatan: {{ $order->notes }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #DC2626; font-size: 1.05rem; font-weight: 900;">{{ $order->formatted_total }}</strong>
                        </td>
                        <td>
                            <div style="margin-bottom: 6px;">
                                @if($order->payment_method === 'kasir')
                                    <span style="background: #FEE2E2; color: #991B1B; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-cash-register"></i> Bayar di Kasir (Cash)
                                    </span>
                                @else
                                    <span style="background: #E0E7FF; color: #3730A3; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-qrcode"></i> QRIS Online
                                    </span>
                                @endif
                            </div>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                                <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-check' : 'fa-hourglass-half' }}"></i>
                                {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                            </span>

                            <!-- Catatan Penegasan Pendapatan Tetap Terhitung -->
                            @if($order->payment_method === 'kasir')
                                <div style="margin-top: 6px; font-size: 0.72rem; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 6px; padding: 4px 6px; color: #92400E; font-weight: 700; line-height: 1.3;">
                                    <i class="fa-solid fa-info-circle"></i> Pesanan ini menggunakan <strong>Cash</strong>, pendapatan tetap terhitung &amp; tersimpan di database.
                                </div>
                            @else
                                <div style="margin-top: 6px; font-size: 0.72rem; background: #ECFDF5; border: 1px solid #10B981; border-radius: 6px; padding: 4px 6px; color: #065F46; font-weight: 700; line-height: 1.3;">
                                    <i class="fa-solid fa-circle-check"></i> Pesanan menggunakan <strong>QRIS Online</strong>, pendapatan otomatis terhitung.
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->order_status }}">
                                @if($order->order_status === 'pending') Menunggu
                                @elseif($order->order_status === 'processing') Sedang Dimasak
                                @elseif($order->order_status === 'completed') Selesai
                                @elseif($order->order_status === 'cancelled') Dibatalkan
                                @endif
                            </span>
                        </td>
                        <td>
                            <!-- 1-Click Cash Payment Acceptance -->
                            @if($order->payment_status === 'unpaid')
                                <form action="{{ route('admin.orders.confirm-cash', $order->id) }}" method="POST" style="margin-bottom: 6px;">
                                    @csrf
                                    <button type="submit" class="btn-primary" style="width: 100%; background: #10B981; color: white; padding: 6px 10px; font-size: 0.78rem; font-weight: 900; justify-content: center;" onclick="return confirm('Konfirmasi terima pembayaran tunai Rp {{ number_format($order->total_amount, 0, ',', '.') }} untuk Meja #{{ $order->table_number }}?')">
                                        <i class="fa-solid fa-money-bill-wave"></i> Terima Kasir (Lunas)
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 4px;">
                                @csrf
                                <select name="order_status" class="action-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Status: Pending</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Status: Dimasak</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Status: Selesai</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Status: Batalkan</option>
                                </select>
                            </form>

                            <div style="display: flex; gap: 4px; margin-top: 4px;">
                                <a href="{{ route('admin.scan', ['code' => $order->order_code]) }}" style="flex: 1; text-align: center; background: #FEF3C7; color: #92400E; border: 1px solid #F59E0B; padding: 4px 6px; border-radius: 4px; font-size: 0.72rem; font-weight: 700; text-decoration: none;">
                                    <i class="fa-solid fa-barcode"></i> Scan
                                </a>
                                <a href="{{ route('admin.orders.receipt', $order->order_code) }}" target="_blank" style="flex: 1; text-align: center; background: #E0E7FF; color: #3730A3; border: 1px solid #818CF8; padding: 4px 6px; border-radius: 4px; font-size: 0.72rem; font-weight: 700; text-decoration: none;">
                                    <i class="fa-solid fa-print"></i> Struk
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            <i class="fa-solid fa-inbox" style="font-size: 2.2rem; margin-bottom: 8px; display: block; color: #D1D5DB;"></i>
                            Belum ada aktivitas transaksi pesanan masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
