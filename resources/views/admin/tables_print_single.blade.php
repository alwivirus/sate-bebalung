<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standee Akrilik Meja #{{ $table['number'] }} - Depot Sate Be Ba Lung</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        /* Top Action Bar (Hidden on print) */
        .no-print-bar {
            background: #1E293B;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 12px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        }

        .btn-action {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.88rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-print {
            background: #F59E0B;
            color: #111827;
        }

        .btn-print:hover {
            background: #D97706;
        }

        .btn-back {
            background: #334155;
            color: white;
        }

        .btn-back:hover {
            background: #475569;
        }

        /* Luxury Acrylic Standee Canvas (Standard A6 / A5 Proportion) */
        .standee-acrylic-container {
            width: 100%;
            max-width: 380px;
            background: #FFFFFF;
            border: 4px solid #111827;
            border-radius: 26px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            padding: 24px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Gold Decorative Corner Borders */
        .standee-acrylic-container::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 8px;
            border: 1.5px solid #D97706;
            border-radius: 18px;
            pointer-events: none;
        }

        .standee-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .standee-logo {
            width: 44px;
            height: 44px;
            background: #111827;
            border-radius: 10px;
            border: 2px solid #F59E0B;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .standee-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .standee-title-text {
            text-align: left;
        }

        .standee-brand-name {
            font-family: 'Cinzel', serif;
            font-size: 1.15rem;
            font-weight: 900;
            color: #111827;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .standee-tagline {
            font-size: 0.68rem;
            font-weight: 800;
            color: #EA580C;
            letter-spacing: 0.5px;
        }

        /* Large Prominent Table Badge */
        .table-number-box {
            background: #111827;
            color: #FCD34D;
            border: 2.5px solid #F59E0B;
            border-radius: 14px;
            padding: 8px 18px;
            display: inline-block;
            margin: 6px auto 14px auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .table-number-title {
            font-size: 0.72rem;
            font-weight: 800;
            color: #E5E7EB;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .table-number-digit {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 2px;
        }

        /* Center QR Frame */
        .qr-frame {
            width: 210px;
            height: 210px;
            background: white;
            border: 3px solid #111827;
            border-radius: 16px;
            padding: 10px;
            margin: 0 auto 14px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            position: relative;
        }

        .qr-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* 3-Step Guide */
        .steps-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 6px;
            margin-bottom: 14px;
            background: #FFFBEB;
            border: 1.5px dashed #F59E0B;
            border-radius: 12px;
            padding: 8px 6px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .step-icon {
            width: 24px;
            height: 24px;
            background: #111827;
            color: #FCD34D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 900;
            margin-bottom: 3px;
        }

        .step-text {
            font-size: 0.65rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.15;
        }

        .standee-footer-info {
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            font-size: 0.68rem;
            font-weight: 700;
            color: #4B5563;
            line-height: 1.4;
        }

        .wifi-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #F3F4F6;
            padding: 2px 8px;
            border-radius: 6px;
            margin-top: 4px;
            font-weight: 800;
            color: #1F2937;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .standee-acrylic-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border: 2.5px solid #000 !important;
                page-break-inside: avoid !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body>

<!-- Action Bar for Printing -->
<div class="no-print-bar">
    <button type="button" class="btn-action btn-print" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Cetak Standee Akrilik (Print Meja #{{ $table['number'] }})
    </button>
    <a href="{{ route('admin.tables.index') }}" class="btn-action btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Meja
    </a>
</div>

<!-- Standee Card -->
<div class="standee-acrylic-container">
    <!-- Header -->
    <div class="standee-header">
        <div class="standee-logo">
            <img src="{{ asset('images/logo-goat.png') }}" alt="Logo Bebalung">
        </div>
        <div class="standee-title-text">
            <div class="standee-brand-name">DEPOT BE BA LUNG</div>
            <div class="standee-tagline">SATE &bull; GULAI &bull; TONGSENG &bull; SOP</div>
        </div>
    </div>

    <!-- Table Badge -->
    <div class="table-number-box">
        <div class="table-number-title">NOMOR MEJA</div>
        <div class="table-number-digit">{{ $table['number'] }}</div>
    </div>

    <!-- QR Code Box -->
    <div class="qr-frame">
        <img src="{{ $table['qr_image'] }}" alt="QR Code Meja {{ $table['number'] }}">
    </div>

    <!-- 3 Simple Steps -->
    <div class="steps-container">
        <div class="step-item">
            <div class="step-icon">1</div>
            <div class="step-text"><strong>Scan QR</strong><br>Buka Kamera HP</div>
        </div>
        <div class="step-item">
            <div class="step-icon">2</div>
            <div class="step-text"><strong>Pilih Menu</strong><br>&amp; Level Pedas</div>
        </div>
        <div class="step-item">
            <div class="step-icon">3</div>
            <div class="step-text"><strong>Duduk Manis</strong><br>Makanan Diantar</div>
        </div>
    </div>

    <!-- Footer Note & Wifi -->
    <div class="standee-footer-info">
        <div>Tidak perlu antre ke kasir, pesanan otomatis terkirim ke dapur!</div>
        <div class="wifi-badge">
            <i class="fa-solid fa-wifi" style="color: #EA580C;"></i> Free Wifi: <strong>Bebalung_Guest</strong> &bull; Pass: <strong>satemaknyus10</strong>
        </div>
    </div>
</div>

</body>
</html>
