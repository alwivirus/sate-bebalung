<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Depot Sate Be Ba Lung')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #F59E0B;
            --primary-dark: #D97706;
            --sidebar-bg: #111827;
            --main-bg: #F3F4F6;
            --card-bg: #FFFFFF;
            --border-color: #E5E7EB;
            --text-dark: #1F2937;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 20px;
            border-bottom: 1px solid #374151;
            margin-bottom: 24px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background: #F59E0B;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #111827;
        }

        .brand-text h3 {
            font-size: 0.95rem;
            font-weight: 800;
            color: white;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: #9CA3AF;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #9CA3AF;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #1F2937;
            color: #F59E0B;
        }

        .nav-link.active {
            background-color: #F59E0B;
            color: #111827;
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 28px;
            flex: 1;
            overflow-y: auto;
        }

        .btn-primary {
            background: #F59E0B;
            color: #111827;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #D97706;
        }

        .card {
            background: white;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-val {
            font-size: 1.35rem;
            font-weight: 900;
            color: #111827;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #6B7280;
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand-header">
            <div class="brand-logo" style="background: white; padding: 2px;">
                <img src="{{ asset('images/logo-goat.png') }}" alt="Be Ba Lung" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text">
                <h3>BE BA LUNG</h3>
                <p>Kasir & Admin Panel</p>
            </div>
        </div>

        <ul class="nav-links">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-bell-concierge"></i>
                    <span>Pesanan Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.scan') }}" class="nav-link {{ request()->routeIs('admin.scan') ? 'active' : '' }}">
                    <i class="fa-solid fa-barcode"></i>
                    <span>Scan Barcode Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Kelola Menu (CRUD)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.activity-logs') }}" class="nav-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Catatan Aktivitas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tables.index') }}" class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-cells"></i>
                    <span>Cetak QR Meja (1-20)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.qris') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Pengaturan QRIS</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>Edit Profil &amp; Password</span>
                </a>
            </li>
            @if(auth()->user() && auth()->user()->role === 'developer')
            <li>
                <a href="{{ route('admin.developer.index') }}" class="nav-link {{ request()->routeIs('admin.developer.*') ? 'active' : '' }}" style="background: rgba(99, 102, 241, 0.15); border-left: 3px solid #6366F1;">
                    <i class="fa-solid fa-terminal" style="color: #6366F1;"></i>
                    <span style="font-weight: 900; color: #818CF8;">Developer Console</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('customer.menu') }}" target="_blank" class="nav-link">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Tampilan Pelanggan</span>
                </a>
            </li>
        </ul>

        <div style="padding: 12px 14px; background: #1F2937; border-radius: 10px; margin-top: 14px; text-align: left;">
            <div style="font-size: 0.78rem; font-weight: 800; color: #FBBF24;">
                <i class="fa-solid fa-user-check"></i> {{ auth()->user()->name ?? 'Kasir' }}
            </div>
            <div style="font-size: 0.7rem; color: #9CA3AF; margin-top: 2px;">
                Role: {{ strtoupper(auth()->user()->role ?? 'KASIR') }} &bull; <a href="{{ route('admin.profile') }}" style="color: #FBBF24; text-decoration: underline;">Ubah Akun</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 8px;">
                @csrf
                <button type="submit" style="width: 100%; background: #EF4444; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar (Logout)
                </button>
            </form>
        </div>

        <div style="font-size: 0.72rem; color: #6B7280; text-align: center; padding-top: 10px;">
            Depot Sate Be Ba Lung v1.0
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <h2 style="font-size: 1.15rem; font-weight: 800;">@yield('page-title', 'Dashboard')</h2>
            <div style="display: flex; align-items: center; gap: 14px;">
                <a href="{{ route('admin.profile') }}" style="display: flex; align-items: center; gap: 8px; background: #F3F4F6; padding: 6px 12px; border-radius: 8px; text-decoration: none; border: 1.5px solid var(--border-color);" title="Klik untuk edit profil akun">
                    <i class="fa-solid fa-user-shield" style="color: #EA580C;"></i>
                    <div style="font-size: 0.82rem; font-weight: 800; color: #111827;">
                        {{ auth()->user()->name ?? 'Kasir' }}
                        <span style="font-size: 0.7rem; background: #111827; color: #FCD34D; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">
                            {{ strtoupper(auth()->user()->role ?? 'KASIR') }}
                        </span>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px;" title="Logout Kasir">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @if(session('success'))
                <div style="background: #D1FAE5; border: 1px solid #10B981; color: #065F46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; font-size: 0.9rem;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
