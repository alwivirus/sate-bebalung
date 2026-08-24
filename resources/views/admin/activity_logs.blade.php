@extends('layouts.admin')

@section('title', 'Catatan Aktivitas Uang Masuk (Cash & QRIS) - Admin Be Ba Lung')
@section('page-title', 'Catatan Aktivitas Pembayaran & Uang Masuk')

@section('styles')
<style>
    .activity-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .log-filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
    }

    .filter-group {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-input {
        padding: 8px 12px;
        border: 1.5px solid #D1D5DB;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        background: #F9FAFB;
    }

    .filter-input:focus {
        border-color: #F59E0B;
        background: white;
    }

    .activity-table {
        width: 100%;
        border-collapse: collapse;
    }

    .activity-table th {
        background: #F9FAFB;
        padding: 12px 16px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #6B7280;
        text-align: left;
        border-bottom: 2px solid #E5E7EB;
        text-transform: uppercase;
    }

    .activity-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #E5E7EB;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    .badge-cash {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FCA5A5;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-qris {
        background: #E0E7FF;
        color: #3730A3;
        border: 1px solid #A5B4FC;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-verified {
        background: #D1FAE5;
        color: #065F46;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    @media print {
        .sidebar, .top-navbar, .log-filter-card, .btn-primary, .pagination-wrapper {
            display: none !important;
        }
        .main-wrapper {
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Ringkasan Uang Masuk -->
<div class="activity-stats-grid">
    <div class="stat-card" style="border-left: 4px solid #10B981;">
        <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
            <i class="fa-solid fa-vault"></i>
        </div>
        <div>
            <div class="stat-val" style="color: #065F46;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Total Uang Masuk</div>
            <div style="font-size: 0.7rem; color: #6B7280; margin-top: 2px;">
                {{ $totalCount }} Transaksi Lunas
            </div>
        </div>
    </div>

    <div class="stat-card" style="border-left: 4px solid #EF4444;">
        <div class="stat-icon" style="background: #FEE2E2; color: #DC2626;">
            <i class="fa-solid fa-cash-register"></i>
        </div>
        <div>
            <div class="stat-val" style="color: #991B1B;">Rp {{ number_format($cashIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Uang Masuk via Cash (Kasir)</div>
            <div style="font-size: 0.7rem; color: #92400E; font-weight: 700; margin-top: 2px;">
                Tercatat &amp; Tersimpan di Database
            </div>
        </div>
    </div>

    <div class="stat-card" style="border-left: 4px solid #3B82F6;">
        <div class="stat-icon" style="background: #DBEAFE; color: #2563EB;">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div>
            <div class="stat-val" style="color: #1E40AF;">Rp {{ number_format($qrisIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Uang Masuk via QRIS</div>
            <div style="font-size: 0.7rem; color: #1E40AF; font-weight: 700; margin-top: 2px;">
                QRIS Online Terkonfirmasi
            </div>
        </div>
    </div>
</div>

<!-- Banner Penegasan Catatan Aktivitas -->
<div style="background: #FEF3C7; border: 1.5px solid #F59E0B; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; color: #92400E; font-size: 0.85rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-clipboard-check" style="font-size: 1.4rem; color: #D97706;"></i>
        <div>
            <strong>Catatan Aktivitas Keuangan:</strong> Transaksi <strong>Cash (Bayar di Kasir)</strong> maupun <strong>QRIS</strong> otomatis disimpan ke database bersama rincian Hari, Tanggal, Bulan, Tahun, Jam, dan Nominal Uang Masuk.
        </div>
    </div>
    <button type="button" onclick="window.print()" class="btn-primary" style="background: #111827; color: white; padding: 6px 12px; font-size: 0.8rem;">
        <i class="fa-solid fa-print"></i> Cetak Laporan
    </button>
</div>

<!-- Filter & Search Bar -->
<div class="log-filter-card">
    <form action="{{ route('admin.activity-logs') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; width: 100%;">
        <div class="filter-group">
            <span style="font-size: 0.8rem; font-weight: 800; color: #374151;">Periode:</span>
            <select name="period" class="filter-input" onchange="this.form.submit()">
                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </div>

        <div class="filter-group">
            <span style="font-size: 0.8rem; font-weight: 800; color: #374151;">Metode:</span>
            <select name="method" class="filter-input" onchange="this.form.submit()">
                <option value="">Semua Metode</option>
                <option value="kasir" {{ $method === 'kasir' ? 'selected' : '' }}>Cash / Tunai</option>
                <option value="online" {{ $method === 'online' ? 'selected' : '' }}>QRIS Online</option>
            </select>
        </div>

        <div class="filter-group">
            <span style="font-size: 0.8rem; font-weight: 800; color: #374151;">Dari Tanggal:</span>
            <input type="date" name="start_date" class="filter-input" value="{{ $startDate }}">
            <span style="font-size: 0.8rem; font-weight: 800; color: #374151;">Sampai:</span>
            <input type="date" name="end_date" class="filter-input" value="{{ $endDate }}">
        </div>

        <div class="filter-group" style="flex: 1; min-width: 180px;">
            <input type="text" name="search" class="filter-input" placeholder="Cari kode / nama / meja..." value="{{ $search }}" style="width: 100%;">
        </div>

        <div style="display: flex; gap: 6px;">
            <button type="submit" class="btn-primary" style="padding: 8px 14px; font-size: 0.85rem;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.activity-logs') }}" class="btn-primary" style="background: #9CA3AF; color: white; padding: 8px 12px; font-size: 0.85rem;" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Activity Log Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1rem; font-weight: 800; color: #111827;">Daftar Riwayat Aktivitas Uang Masuk</h3>
            <span style="font-size: 0.78rem; color: #6B7280;">Catatan lengkap tanggal, hari, jam, metode bayar, dan nominal</span>
        </div>
        <span style="font-size: 0.8rem; font-weight: 800; background: #FEF3C7; padding: 4px 10px; border-radius: 8px; color: #92400E;">
            Total: {{ $logs->total() }} Catatan
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Hari, Tanggal &amp; Waktu</th>
                    <th>Kode &amp; Meja Pelanggan</th>
                    <th>Detail Menu Dibeli</th>
                    <th>Metode Pembayaran</th>
                    <th>Uang Masuk (Nominal)</th>
                    <th>Status Database</th>
                    <th>Struk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        // Format tanggal & hari bahasa Indonesia
                        $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                        $dayNameEn = $log->created_at->format('l');
                        $dayNameId = $days[$dayNameEn] ?? $dayNameEn;
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: #111827; font-size: 0.9rem;">
                                {{ $dayNameId }}, {{ $log->created_at->translatedFormat('d F Y') }}
                            </strong>
                            <div style="font-size: 0.75rem; color: #4B5563; margin-top: 2px;">
                                <i class="fa-solid fa-clock" style="color: #F59E0B;"></i> Pukul {{ $log->created_at->format('H:i:s') }} WIB
                            </div>
                        </td>
                        <td>
                            <strong style="font-family: monospace; font-size: 0.95rem; color: #111827;">{{ $log->order_code }}</strong>
                            <div style="margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                                <span style="background: #111827; color: #FCD34D; padding: 1px 6px; border-radius: 4px; font-weight: 900; font-size: 0.72rem;">
                                    Meja #{{ $log->table_number }}
                                </span>
                                <span style="font-size: 0.8rem; color: #374151; font-weight: 700;">{{ $log->customer_name }}</span>
                            </div>
                        </td>
                        <td>
                            <ul style="list-style: none; margin: 0; padding: 0;">
                                @foreach($log->items as $item)
                                    <li style="font-size: 0.82rem; margin-bottom: 2px;">
                                        <strong>{{ $item->quantity }}x</strong> {{ $item->menu_name }}
                                        <span style="color: #6B7280; font-size: 0.75rem;">(Rp {{ number_format($item->subtotal, 0, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($log->payment_method === 'kasir')
                                <span class="badge-cash">
                                    <i class="fa-solid fa-cash-register"></i> Bayar di Kasir (Cash)
                                </span>
                                <div style="font-size: 0.68rem; color: #92400E; font-weight: 700; margin-top: 3px;">
                                    * Pendapatan tetap terhitung
                                </div>
                            @else
                                <span class="badge-qris">
                                    <i class="fa-solid fa-qrcode"></i> QRIS Online
                                </span>
                                <div style="font-size: 0.68rem; color: #1E40AF; font-weight: 700; margin-top: 3px;">
                                    * Otomatis terhitung
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #059669; font-size: 1.05rem; font-weight: 900;">
                                {{ $log->formatted_total }}
                            </strong>
                        </td>
                        <td>
                            <span class="badge-verified">
                                <i class="fa-solid fa-circle-check"></i> LUNAS (Tersimpan)
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.receipt', $log->order_code) }}" target="_blank" style="background: #E0E7FF; color: #3730A3; border: 1px solid #818CF8; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-print"></i> Struk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                            <i class="fa-solid fa-receipt" style="font-size: 2.2rem; margin-bottom: 8px; display: block; color: #D1D5DB;"></i>
                            Belum ada catatan aktivitas pembayaran pada filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="padding: 16px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
