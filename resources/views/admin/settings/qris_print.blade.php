<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Standee QRIS Pembayaran - {{ $merchantName }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Paytone+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #0F172A;
            background-image: radial-gradient(#334155 1px, transparent 1px);
            background-size: 20px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px;
        }

        /* Top Action Bar (Hidden on print) */
        .no-print-bar {
            background: #1E293B;
            border: 1.5px solid #EA580C;
            border-radius: 16px;
            padding: 14px 22px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 20px rgba(234, 88, 12, 0.2);
            color: #FFFFFF;
        }

        .btn-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-print {
            background: #EA580C;
            color: white;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.35);
        }

        .btn-print:hover {
            background: #C2410C;
            transform: translateY(-1px);
        }

        .btn-back {
            background: #334155;
            color: white;
        }

        .btn-back:hover {
            background: #475569;
        }

        /* Standee Canvas - A5 Ratio */
        .standee-card {
            width: 420px;
            background: #FFFFFF;
            border: 4px solid #111827;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            padding: 24px 22px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Official QRIS Header Banner */
        .qris-official-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
            margin-bottom: 12px;
        }

        .qris-logo-text {
            text-align: left;
        }

        .qris-brand-title {
            font-size: 1.4rem;
            font-weight: 900;
            color: #DC2626;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .qris-brand-sub {
            font-size: 0.62rem;
            color: #4B5563;
            font-weight: 700;
            letter-spacing: 0.2px;
            margin-top: 2px;
        }

        .gpn-badge {
            background: #DC2626;
            color: white;
            font-weight: 900;
            font-size: 0.82rem;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .merchant-block {
            margin: 6px 0 12px;
            width: 100%;
        }

        .merchant-title {
            font-size: 1.1rem;
            font-weight: 900;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .merchant-nmid {
            font-size: 0.78rem;
            font-weight: 700;
            color: #374151;
            margin-top: 3px;
        }

        .merchant-addr {
            font-size: 0.68rem;
            color: #6B7280;
            margin-top: 2px;
        }

        /* QR Frame Box */
        .qr-frame {
            width: 280px;
            height: 280px;
            background: #FFFFFF;
            border: 2px solid #D1D5DB;
            border-radius: 16px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 6px 0 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .qr-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qris-footer {
            width: 100%;
            border-top: 2px solid #E5E7EB;
            padding-top: 10px;
            margin-top: 4px;
        }

        .satu-qris {
            font-size: 0.82rem;
            font-weight: 900;
            color: #111827;
            letter-spacing: 0.5px;
        }

        .bank-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
            font-size: 0.65rem;
            font-weight: 800;
            color: #4B5563;
        }

        .bank-badge-item {
            background: #F3F4F6;
            padding: 3px 6px;
            border-radius: 4px;
            border: 1px solid #E5E7EB;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 100vh !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .standee-card {
                box-shadow: none !important;
                border: 3px solid #000000 !important;
                page-break-inside: avoid !important;
                margin: auto !important;
                width: 100% !important;
                max-width: 460px !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar -->
    <div class="no-print-bar">
        <div>
            <div style="font-weight: 900; font-size: 1rem; color: #F8FAFC;">
                <i class="fa-solid fa-print" style="color: #EA580C;"></i> Standee QRIS Siap Cetak
            </div>
            <div style="font-size: 0.75rem; color: #94A3B8;">Siap dipasang di akrilik meja kasir depot</div>
        </div>

        <div class="btn-group">
            <a href="{{ route('admin.settings.qris') }}" class="btn-action btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fa-solid fa-print"></i> Cetak Standee
            </button>
        </div>
    </div>

    <!-- Standee Akrilik Card -->
    <div class="standee-card">
        <div class="qris-official-header">
            <div class="qris-logo-text">
                <div class="qris-brand-title">QRIS</div>
                <div class="qris-brand-sub">PEMBAYARAN DIGITAL INDONESIA</div>
            </div>
            <div class="gpn-badge">GPN</div>
        </div>

        <div class="merchant-block">
            <div class="merchant-title">{{ $merchantName }}</div>
            <div class="merchant-nmid">NMID: {{ $nmid }}</div>
            <div class="merchant-addr">{{ $restoAddress }}</div>
        </div>

        <div class="qr-frame">
            <img src="{{ asset($qrisImage) }}" alt="QRIS {{ $merchantName }}">
        </div>

        <div class="qris-footer">
            <div class="satu-qris">SATU QRIS UNTUK SEMUA</div>
            <div style="font-size: 0.65rem; color: #6B7280; margin-top: 2px;">
                Menerima BCA, Mandiri, BRI, BNI, BSI, GoPay, OVO, DANA, LinkAja, ShopeePay &amp; Seluruh M-Banking
            </div>

            <div class="bank-badges">
                <span class="bank-badge-item">BCA</span>
                <span class="bank-badge-item">Mandiri</span>
                <span class="bank-badge-item">BRI</span>
                <span class="bank-badge-item">BNI</span>
                <span class="bank-badge-item">GoPay</span>
                <span class="bank-badge-item">OVO</span>
                <span class="bank-badge-item">DANA</span>
                <span class="bank-badge-item">ShopeePay</span>
            </div>
        </div>
    </div>

</body>
</html>
