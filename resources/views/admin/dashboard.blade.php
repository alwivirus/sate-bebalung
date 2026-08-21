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
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['total_orders_today'] }}</div>
            <div class="stat-label">Pesanan Hari Ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div>
            <div class="stat-val">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Hari Ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #FFEDD5; color: #EA580C;">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['pending_count'] }}</div>
            <div class="stat-label">Pesanan Baru (Pending)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #DBEAFE; color: #2563EB;">
            <i class="fa-solid fa-fire-burner"></i>
        </div>
        <div>
            <div class="stat-val">{{ $stats['processing_count'] }}</div>
            <div class="stat-label">Sedang Dimasak (Dapur)</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-pills">
    <a href="{{ route('admin.dashboard') }}" class="filter-pill {{ empty($statusFilter) ? 'active' : '' }}">Semua Pesanan</a>
    <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="filter-pill {{ $statusFilter === 'pending' ? 'active' : '' }}">Pesanan Baru</a>
    <a href="{{ route('admin.dashboard', ['status' => 'processing']) }}" class="filter-pill {{ $statusFilter === 'processing' ? 'active' : '' }}">Sedang Dimasak</a>
    <a href="{{ route('admin.dashboard', ['status' => 'completed']) }}" class="filter-pill {{ $statusFilter === 'completed' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('admin.dashboard', ['status' => 'cancelled']) }}" class="filter-pill {{ $statusFilter === 'cancelled' ? 'active' : '' }}">Dibatalkan</a>
</div>

<!-- Orders Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1rem; font-weight: 800;">Daftar Pesanan Meja</h3>
            <span style="font-size: 0.78rem; color: #6B7280;">Monitoring & kelola transaksi pesanan</span>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.scan') }}" class="btn-primary" style="padding: 8px 14px; font-size: 0.85rem;">
                <i class="fa-solid fa-barcode"></i>
                <span>Scan Barcode Kasir (POS)</span>
            </a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Kode / Meja</th>
                    <th>Detail Item Pesanan</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong style="color: #111827; font-family: monospace; font-size: 0.95rem;">{{ $order->order_code }}</strong>
                            <div style="margin-top: 4px;">
                                <span style="background: #111827; color: white; padding: 2px 6px; border-radius: 4px; font-weight: 800; font-size: 0.75rem;">
                                    Meja #{{ $order->table_number }}
                                </span>
                                <span style="font-size: 0.8rem; color: #4B5563; margin-left: 4px;">{{ $order->customer_name }}</span>
                            </div>
                            <div style="font-size: 0.72rem; color: #9CA3AF; margin-top: 4px;">
                                {{ $order->created_at->format('H:i, d M Y') }}
                            </div>
                        </td>
                        <td>
                            <ul style="list-style: none; margin: 0; padding: 0;">
                                @foreach($order->items as $item)
                                    <li style="font-size: 0.85rem; margin-bottom: 2px;">
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
                            <strong style="color: #DC2626; font-size: 0.95rem;">{{ $order->formatted_total }}</strong>
                        </td>
                        <td>
                            <div style="margin-bottom: 4px;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: #374151;">
                                    {{ $order->payment_method === 'online' ? 'QRIS Online' : 'Bayar di Kasir' }}
                                </span>
                            </div>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                                {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                            </span>
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
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 6px;">
                                @csrf
                                <select name="order_status" class="action-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Dimasak</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Batalkan</option>
                                </select>

                                <select name="payment_status" class="action-select" onchange="this.form.submit()">
                                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </form>

                            <div style="display: flex; gap: 6px; margin-top: 6px;">
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
                        <td colspan="6" style="text-align: center; padding: 32px; color: #9CA3AF;">
                            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                            Belum ada pesanan masuk saat ini.
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
