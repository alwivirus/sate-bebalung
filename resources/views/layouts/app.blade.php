<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Depot Sate Be Ba Lung - Scan Meja')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-yellow: #FFB703;
            --primary-yellow-hover: #FB8500;
            --dark-border: #1E1E1E;
            --bg-canvas: #E2E8F0;
            --card-yellow: #FFB703;
            --card-yellow-light: #FFD166;
            --text-dark: #121212;
            --text-orange: #EA580C;
            --box-shadow-brutal: 3px 4px 0px #1E1E1E;
            --box-shadow-btn: 3px 4px 0px #1E1E1E;
            --box-shadow-btn-active: 1px 1px 0px #1E1E1E;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #D1D5DB;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            background-color: #E5E7EB;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Neo-Brutalist Common Elements */
        .brutal-card {
            background-color: var(--primary-yellow);
            border: 3px solid var(--dark-border);
            border-radius: 20px;
            box-shadow: var(--box-shadow-brutal);
            transition: transform 0.1s ease;
        }

        .brutal-btn {
            background-color: var(--primary-yellow);
            border: 3px solid var(--dark-border);
            border-radius: 14px;
            box-shadow: var(--box-shadow-btn);
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.1s ease;
            text-decoration: none;
            color: var(--text-dark);
        }

        .brutal-btn:active {
            transform: translate(2px, 2px);
            box-shadow: var(--box-shadow-btn-active);
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 1.05rem;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .category-icon-box {
            width: 32px;
            height: 32px;
            background-color: #F87171;
            border: 2px solid var(--dark-border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 0px var(--dark-border);
            color: white;
            font-size: 0.9rem;
        }

        .category-icon-box.drink {
            background-color: #FBBF24;
            color: #1E1E1E;
        }

        /* Footer Section */
        .app-footer {
            background-color: #111827;
            color: #F3F4F6;
            padding: 24px 20px;
            margin-top: auto;
            border-top: 4px solid var(--dark-border);
        }

        .footer-logo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .footer-logo-circle {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 50%;
            border: 2px solid #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .footer-info {
            font-size: 0.78rem;
            line-height: 1.4;
            color: #D1D5DB;
        }

        .footer-info strong {
            color: #F59E0B;
            display: block;
            margin-top: 6px;
            font-size: 0.82rem;
        }

        /* Alert notifications */
        .toast-msg {
            background-color: #10B981;
            color: white;
            border: 2px solid var(--dark-border);
            padding: 10px 16px;
            border-radius: 12px;
            margin: 12px 16px;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: var(--box-shadow-brutal);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-msg.error {
            background-color: #EF4444;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="mobile-container">
        @if(session('success'))
            <div class="toast-msg">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-msg error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')

        <!-- Footer Identik Screenshot -->
        <footer class="app-footer">
            <div class="footer-logo-row">
                <div class="footer-logo-circle" style="background: white; overflow: hidden;">
                    <img src="{{ asset('images/logo-goat.png') }}" alt="Depot Sate Be Ba Lung" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div>
                    <h4 style="font-size: 0.85rem; font-weight: 900; color: #FFF; letter-spacing: 0.5px;">DEPOT Sate</h4>
                    <p style="font-size: 0.7rem; color: #F59E0B; font-weight: 700;">Sop & Gulai Kambing "BE BA LUNG"</p>
                </div>
            </div>
            
            <div class="footer-info">
                <strong>Lokasi :</strong>
                <p>Jl. Supriyadi No. 40, Purwokerto</p>
                
                <strong>Jam Operasional :</strong>
                <p>Senin - Minggu (Pukul 10.00 - 21.00 WIB)</p>
                
                <strong>Info &amp; Pemesanan :</strong>
                <p><a href="https://wa.me/6287730712015" target="_blank" style="color: #FBBF24; text-decoration: none; font-weight: 800;"><i class="fa-brands fa-whatsapp"></i> 0877 3071 2015</a></p>
            </div>
        </footer>
    </div>

    <!-- Chatbot Rekomendasi Menu Pintar (HANYA tampil di halaman menu utama saat memilih makanan, TIDAK tampil di checkout, pembayaran QRIS, ataupun struk sukses) -->
    @if(request()->routeIs('customer.menu') || request()->is('/') || (request()->path() === '/'))
        @include('components.chatbot')
    @endif

    @yield('scripts')
</body>
</html>
