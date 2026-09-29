<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
        }

        /* -------------------------------------------------------------
           1. SIDEBAR (Desktop & Tablet Web View / Mobile Drawer)
           ------------------------------------------------------------- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: white;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 1px solid #374151;
            margin-bottom: 20px;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3px;
            flex-shrink: 0;
        }

        .brand-text h3 {
            font-size: 0.95rem;
            font-weight: 900;
            color: white;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 0.72rem;
            color: #9CA3AF;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #9CA3AF;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.86rem;
            transition: all 0.15s;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .nav-link:hover {
            background-color: #1F2937;
            color: #F59E0B;
        }

        .nav-link.active {
            background-color: #F59E0B;
            color: #111827;
            font-weight: 800;
        }

        .sidebar-user-card {
            padding: 12px;
            background: #1F2937;
            border-radius: 10px;
            margin-top: 14px;
            text-align: left;
        }

        /* Backdrop overlay for mobile drawer */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(2px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* -------------------------------------------------------------
           2. MAIN CONTENT WRAPPER
           ------------------------------------------------------------- */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            width: 100%;
            height: 100vh;
            overflow-y: auto;
        }

        .top-navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-hamburger {
            display: none;
            background: #F3F4F6;
            border: 1px solid #D1D5DB;
            color: #111827;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .top-navbar-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #111827;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .content-area {
            padding: 24px;
            flex: 1;
        }

        /* -------------------------------------------------------------
           3. COMMON REUSABLE COMPONENTS (CARDS, BUTTONS, GRIDS)
           ------------------------------------------------------------- */
        .card {
            background: white;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

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
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-val {
            font-size: 1.25rem;
            font-weight: 900;
            color: #111827;
        }

        .stat-label {
            font-size: 0.78rem;
            color: #6B7280;
            font-weight: 700;
        }

        .btn-primary {
            background: #F59E0B;
            color: #111827;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
        }

        .btn-primary:hover {
            background: #D97706;
        }

        /* -------------------------------------------------------------
           4. MOBILE BOTTOM NAVIGATION (Khusus Tipe Layar HP)
           ------------------------------------------------------------- */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 62px;
            background: #111827;
            border-top: 1px solid #1F2937;
            z-index: 998;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.25);
            padding: 0 8px;
        }

        .mobile-bottom-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-around;
            height: 100%;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 700;
            gap: 4px;
            flex: 1;
            padding: 6px 0;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: color 0.15s;
        }

        .mobile-nav-item i {
            font-size: 1.15rem;
        }

        .mobile-nav-item.active {
            color: #F59E0B;
        }

        .mobile-nav-item.qris-btn {
            color: #FBBF24;
        }

        /* -------------------------------------------------------------
           5. DUAL RESPONSIVE MODELS (TABLET & MOBILE RULES)
           ------------------------------------------------------------- */
        
        /* TABLET MODEL (768px - 1024px) */
        @media (min-width: 768px) and (max-width: 1024px) {
            :root {
                --sidebar-width: 220px;
            }

            .sidebar {
                padding: 16px 10px;
            }

            .nav-link {
                padding: 10px 10px;
                font-size: 0.82rem;
                gap: 8px;
            }

            .top-navbar {
                padding: 12px 18px;
            }

            .content-area {
                padding: 18px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* MOBILE MODEL (< 768px) */
        @media (max-width: 767px) {
            .mobile-hamburger {
                display: flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                width: 280px;
                transform: translateX(-100%);
                box-shadow: 10px 0 25px rgba(0,0,0,0.5);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            .mobile-bottom-nav {
                display: block;
            }

            .top-navbar {
                padding: 10px 14px;
            }

            .top-navbar-title {
                font-size: 0.95rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 160px;
            }

            .content-area {
                padding: 14px 12px;
                padding-bottom: 78px; /* Space for bottom nav */
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
                padding: 11px;
            }

            .filter-pills {
                overflow-x: auto;
                flex-wrap: nowrap !important;
                padding-bottom: 6px;
                -webkit-overflow-scrolling: touch;
            }

            .filter-pill {
                flex-shrink: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Overlay Backdrop for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="mainSidebar">
        <div class="brand-header">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-goat.png') }}" alt="Be Ba Lung" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text" style="flex: 1;">
                <h3>BE BA LUNG</h3>
                <p>Kasir &amp; Admin Panel</p>
            </div>
            <!-- Close icon on mobile drawer -->
            <button type="button" onclick="closeMobileSidebar()" style="display: none; background: transparent; border: none; color: #9CA3AF; font-size: 1.2rem; cursor: pointer;" id="closeDrawerBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
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
                    <span>Kelola Menu</span>
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
                    <span>QR MEJA</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.qris') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Pengaturan QRIS</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Role &amp; Akun Kasir</span>
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
            <li style="margin-top: 10px; border-top: 1px dashed rgba(255,255,255,0.15); padding-top: 8px;">
                <div style="font-size: 0.68rem; font-weight: 800; color: #9CA3AF; text-transform: uppercase; padding: 4px 16px; letter-spacing: 0.5px;">
                    Tampilan Pelanggan
                </div>
            </li>
            <li>
                <a href="{{ url('/') }}" target="_blank" class="nav-link" title="Buka Katalog Profil / Deskripsi Makanan untuk Pengunjung Umum">
                    <i class="fa-solid fa-book-open"></i>
                    <span>1. Katalog Publik (Showcase)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.menu', ['meja' => \App\Models\Table::getSecureCode('01')]) }}" target="_blank" class="nav-link" title="Buka Tampilan Pemesanan Meja Kasir untuk Pelanggan di Meja">
                    <i class="fa-solid fa-utensils"></i>
                    <span>2. Pesan di Meja (Simulasi)</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-user-card">
            <div style="font-size: 0.78rem; font-weight: 800; color: #FBBF24;">
                <i class="fa-solid fa-user-check"></i> {{ auth()->user()->name ?? 'Kasir' }}
            </div>
            <div style="font-size: 0.7rem; color: #9CA3AF; margin-top: 2px;">
                Role: {{ strtoupper(auth()->user()->role ?? 'KASIR') }} &bull; <a href="{{ route('admin.profile') }}" style="color: #FBBF24; text-decoration: underline;">Ubah</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 8px;">
                @csrf
                <button type="submit" style="width: 100%; background: #EF4444; color: white; border: none; border-radius: 6px; padding: 6px 10px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>

        <div style="font-size: 0.7rem; color: #6B7280; text-align: center; padding-top: 8px;">
            Depot Sate Be Ba Lung v1.0
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="navbar-left">
                <!-- Hamburger button for mobile -->
                <button type="button" class="mobile-hamburger" onclick="toggleMobileSidebar()" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="top-navbar-title">@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="navbar-right">
                <!-- Quick QRIS Modal Trigger for Cashier -->
                @php
                    $globalQrisImage = \App\Models\Setting::get('qris_image', 'images/qris_official.png');
                    $globalMerchantName = \App\Models\Setting::get('qris_merchant_name', 'SATE KAMBING BE BA LUNG');
                    $globalNmid = \App\Models\Setting::get('qris_nmid', 'ID1025428876474');
                @endphp
                <button type="button" onclick="openCashierQrisModal()" style="background: #FFFBEB; color: #D97706; border: 1.5px solid #FCD34D; padding: 6px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Tampilkan QRIS Kasir untuk Pelanggan">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>QRIS</span>
                </button>

                @if(auth()->user() && in_array(auth()->user()->role, ['developer', 'admin']))
                    <form action="{{ route('admin.developer.sync-db') }}" method="POST" style="margin: 0;" class="desktop-only-btn">
                        @csrf
                        <button type="submit" style="background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; padding: 6px 9px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Sinkronkan Skema Database &amp; Menu Resmi">
                            <i class="fa-solid fa-rotate"></i>
                            <span style="display: none; @media(min-width:900px){display:inline;}">Sync</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.developer.clear-cache') }}" method="POST" style="margin: 0;" class="desktop-only-btn">
                        @csrf
                        <button type="submit" style="background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; padding: 6px 9px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Bersihkan Cache &amp; Session Laravel">
                            <i class="fa-solid fa-bolt"></i>
                            <span style="display: none; @media(min-width:900px){display:inline;}">Flush</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.profile') }}" style="display: flex; align-items: center; gap: 5px; background: #F3F4F6; padding: 6px 9px; border-radius: 8px; text-decoration: none; border: 1.5px solid var(--border-color);" title="Profil Akun">
                    <i class="fa-solid fa-user-shield" style="color: #EA580C; font-size: 0.9rem;"></i>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #111827;">
                        {{ strtoupper(auth()->user()->role ?? 'KASIR') }}
                    </span>
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 6px 8px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center;" title="Logout Kasir">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @if(session('success'))
                <div style="background: #D1FAE5; border: 1px solid #10B981; color: #065F46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #FEE2E2; border: 1px solid #EF4444; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; font-size: 0.88rem;">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar (Khusus Layar HP / Mobile View) -->
    <nav class="mobile-bottom-nav">
        <div class="mobile-bottom-nav-inner">
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-bell-concierge"></i>
                <span>Pesanan</span>
            </a>
            <a href="{{ route('admin.scan') }}" class="mobile-nav-item {{ request()->routeIs('admin.scan') ? 'active' : '' }}">
                <i class="fa-solid fa-barcode"></i>
                <span>POS Scan</span>
            </a>
            <button type="button" class="mobile-nav-item qris-btn" onclick="openCashierQrisModal()">
                <i class="fa-solid fa-qrcode" style="font-size: 1.35rem; color: #F59E0B;"></i>
                <span style="color: #F59E0B; font-weight: 800;">QRIS</span>
            </button>
            <a href="{{ route('admin.activity-logs') }}" class="mobile-nav-item {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Keuangan</span>
            </a>
            <button type="button" class="mobile-nav-item" onclick="toggleMobileSidebar()">
                <i class="fa-solid fa-bars"></i>
                <span>Menu</span>
            </button>
        </div>
    </nav>

    <!-- Quick Pop-up Modal QRIS Kasir -->
    <div id="cashierQrisModal" style="display: none; position: fixed; inset: 0; background: rgba(17, 24, 39, 0.75); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: white; border-radius: 20px; max-width: 360px; width: 100%; padding: 22px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); border: 2px solid #111827; position: relative;">
            <button type="button" onclick="closeCashierQrisModal()" style="position: absolute; top: 12px; right: 12px; background: #F3F4F6; border: none; width: 30px; height: 30px; border-radius: 50%; font-size: 1rem; font-weight: 900; color: #4B5563; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                &times;
            </button>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1.5px solid #E5E7EB; padding-bottom: 8px;">
                <div style="text-align: left;">
                    <div style="font-size: 1.15rem; font-weight: 900; color: #DC2626; line-height: 1;">QRIS</div>
                    <div style="font-size: 0.55rem; font-weight: 700; color: #4B5563;">PEMBAYARAN DIGITAL INDONESIA</div>
                </div>
                <div style="background: #DC2626; color: white; font-weight: 900; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px;">GPN</div>
            </div>

            <div style="font-size: 0.95rem; font-weight: 900; color: #111827; text-transform: uppercase;">{{ $globalMerchantName }}</div>
            <div style="font-size: 0.72rem; font-weight: 700; color: #4B5563; margin-top: 2px;">NMID: {{ $globalNmid }}</div>

            <div style="width: 220px; height: 220px; margin: 12px auto; padding: 8px; border: 1.5px solid #D1D5DB; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #F9FAFB;">
                <img src="{{ asset($globalQrisImage) }}" alt="QRIS {{ $globalMerchantName }}" style="width: 100%; height: 100%; object-fit: contain;">
            </div>

            <div style="font-size: 0.72rem; color: #059669; font-weight: 800; margin-bottom: 12px;">
                <i class="fa-solid fa-circle-check"></i> Siap Scan via Seluruh E-Wallet &amp; M-Banking
            </div>

            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.settings.qris.print') }}" target="_blank" style="flex: 1; background: #EA580C; color: white; padding: 9px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 5px;">
                    <i class="fa-solid fa-print"></i> Cetak Standee
                </a>
                <button type="button" onclick="closeCashierQrisModal()" style="flex: 1; background: #111827; color: white; padding: 9px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; border: none; cursor: pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('closeDrawerBtn');

            if (sidebar.classList.contains('open')) {
                closeMobileSidebar();
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                if (closeBtn) closeBtn.style.display = 'block';
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('closeDrawerBtn');

            if (sidebar) sidebar.classList.remove('open');
            if (overlay) overlay.classList.remove('active');
            if (closeBtn) closeBtn.style.display = 'none';
        }

        function openCashierQrisModal() {
            const modal = document.getElementById('cashierQrisModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeCashierQrisModal() {
            const modal = document.getElementById('cashierQrisModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
