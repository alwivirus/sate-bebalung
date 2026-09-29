<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Standee Meja #{{ $table['number'] }} - Depot Be Ba Lung</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800;900&family=Paytone+One&display=swap" rel="stylesheet">
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
            max-width: 820px;
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
            padding: 9px 18px;
            border-radius: 9px;
            font-weight: 800;
            font-size: 0.85rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-print {
            background: linear-gradient(135deg, #F59E0B, #EA580C);
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.4);
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.6);
        }

        .btn-secondary {
            background: #334155;
            color: #E2E8F0;
            border: 1px solid #475569;
        }
        .btn-secondary:hover {
            background: #475569;
            color: #FFFFFF;
        }

        .btn-pill {
            background: #0F172A;
            color: #CBD5E1;
            border: 1px solid #475569;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-pill.active {
            background: #EA580C;
            border-color: #F97316;
            color: #FFFFFF;
            font-weight: 800;
        }

        /* Container & Cut Guides Wrapper */
        .print-page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cut-guide-container {
            position: relative;
            padding: 2px;
            border: 1.2px dashed #CBD5E1;
            border-radius: 20px;
            background: #FFFFFF;
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
        }

        /* ----------------------------------------------------
           DYNAMIC & APPETIZING RESTAURANT STANDEE CARD
        ---------------------------------------------------- */
        .standee-card {
            width: 105mm;
            min-height: 148mm;
            max-width: 100%;
            background: linear-gradient(180deg, #FFF9F5 0%, #FFFFFF 30%, #FFF8F3 100%);
            border: 2.5px solid #EA580C;
            border-radius: 18px;
            padding: 12px 12px 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        /* Size Variants */
        .standee-card.size-a5 {
            width: 148mm;
            min-height: 210mm;
            padding: 20px 18px 16px 18px;
        }
        .standee-card.size-compact {
            width: 95mm;
            min-height: 138mm;
            padding: 10px 10px 8px 10px;
        }

        /* Top Brand Bar */
        .brand-top-bar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 5px;
            border-bottom: 1.5px dashed #FDBA74;
        }

        .brand-logo-name {
            display: flex;
            align-items: center;
            gap: 6px;
            text-align: left;
        }

        .brand-icon-circle {
            width: 28px;
            height: 28px;
            background: #0F172A;
            border-radius: 6px;
            border: 1.5px solid #F59E0B;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text-main {
            font-size: 0.84rem;
            font-weight: 900;
            color: #0F172A;
            line-height: 1;
            letter-spacing: 0.3px;
        }

        .brand-text-sub {
            font-size: 0.50rem;
            font-weight: 800;
            color: #EA580C;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 1px;
        }

        /* Dynamic Table Badge in Header */
        .table-pill-badge {
            background: linear-gradient(135deg, #EA580C, #C2410C);
            color: #FFFFFF;
            border-radius: 8px;
            padding: 3px 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(234, 88, 12, 0.3);
            border: 1px solid #FED7AA;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .table-pill-label {
            font-size: 0.50rem;
            font-weight: 900;
            color: #FED7AA;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-pill-num {
            font-size: 1.25rem;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1;
        }

        /* Dynamic Headline: PESAN DISINI, TANPA ANTRI */
        .catchy-headline-box {
            width: 100%;
            text-align: center;
            margin: 4px 0 2px 0;
            background: #FFF0E6;
            border: 1px solid #FDBA74;
            border-radius: 9px;
            padding: 5px 8px;
        }

        .headline-title {
            font-family: 'Paytone One', 'Plus Jakarta Sans', sans-serif;
            font-size: 1.10rem;
            line-height: 1;
            color: #EA580C;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .headline-subtitle {
            font-size: 0.58rem;
            font-weight: 800;
            color: #0284C7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Large Center QR Code Section */
        .center-qr-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: auto 0;
        }

        .qr-frame-wrapper {
            width: 195px;
            height: 195px;
            aspect-ratio: 1 / 1;
            background: #FFFFFF;
            border: 2.5px solid #0F172A;
            border-radius: 14px;
            padding: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .qr-frame-wrapper img {
            width: 100%;
            height: 100%;
            aspect-ratio: 1 / 1;
            object-fit: contain;
            image-rendering: -webkit-optimize-contrast;
            display: block;
        }

        .qr-sub-instruction {
            margin-top: 4px;
            background: #0F172A;
            color: #FEF3C7;
            font-size: 0.60rem;
            font-weight: 900;
            padding: 3px 12px;
            border-radius: 14px;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* 3 Flow Steps */
        .flow-steps-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            margin-top: 4px;
        }

        .flow-step-card {
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            border-radius: 7px;
            padding: 4px 3px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .flow-step-num {
            width: 16px;
            height: 16px;
            background: #EA580C;
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.58rem;
            font-weight: 900;
            margin-bottom: 2px;
        }

        .flow-step-title {
            font-size: 0.60rem;
            font-weight: 900;
            color: #0F172A;
            line-height: 1.1;
        }

        .flow-step-desc {
            font-size: 0.48rem;
            font-weight: 600;
            color: #64748B;
            line-height: 1;
        }

        /* Footer Guarantee Note */
        .standee-footer-strip {
            width: 100%;
            border-top: 1px solid #FDBA74;
            padding-top: 3px;
            font-size: 0.54rem;
            font-weight: 700;
            color: #7C2D12;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .standee-footer-strip strong {
            color: #EA580C;
        }

        /* Print Specifics */
        @media print {
            @page {
                size: auto;
                margin: 4mm;
            }

            body {
                background: white !important;
                padding: 0 !important;
                min-height: auto !important;
                display: block !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .print-page-wrapper {
                display: flex !important;
                justify-content: center !important;
                align-items: flex-start !important;
                padding: 6mm 0 !important;
            }

            .cut-guide-container {
                box-shadow: none !important;
                border: 1px dashed #94A3B8 !important;
                padding: 3px !important;
            }

            .standee-card {
                box-shadow: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin: 0 auto !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<!-- Top Action Bar (Hidden on print) -->
<div class="no-print-bar">
    <div class="btn-group">
        <button type="button" class="btn-action btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Cetak Standee Meja #{{ $table['number'] }}
        </button>
        <a href="{{ route('admin.tables.print-all') }}" class="btn-action btn-secondary" title="Cetak Semua Meja Sekaligus (4 per Lembar)">
            <i class="fa-solid fa-layer-group"></i> Cetak Semua Meja (4 per Lembar)
        </a>
        <a href="{{ route('admin.tables.index') }}" class="btn-action btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Size Switcher -->
    <div class="btn-group">
        <span style="color: #FCD34D; font-size: 0.78rem; font-weight: 800;"><i class="fa-solid fa-up-right-and-down-left-from-center"></i> Ukuran:</span>
        <button type="button" class="btn-pill active" onclick="changeSize('a6', this)">A6 (10x15cm)</button>
        <button type="button" class="btn-pill" onclick="changeSize('a5', this)">A5 (15x21cm)</button>
        <button type="button" class="btn-pill" onclick="changeSize('compact', this)">Compact</button>
    </div>
</div>

<!-- Print Canvas Wrapper with Cut Lines -->
<div class="print-page-wrapper">
    <div class="cut-guide-container">
        <div class="standee-card" id="standeeCard">
            <!-- Top Brand Bar -->
            <div class="brand-top-bar">
                <div class="brand-logo-name">
                    <div class="brand-icon-circle">
                        <img src="{{ asset('images/logo-goat.png') }}" alt="Logo">
                    </div>
                    <div>
                        <div class="brand-text-main">DEPOT BE BA LUNG</div>
                        <div class="brand-text-sub">SATE &bull; GULAI &bull; TONGSENG</div>
                    </div>
                </div>
                <div class="table-pill-badge">
                    <span class="table-pill-label">MEJA</span>
                    <span class="table-pill-num">{{ $table['number'] }}</span>
                </div>
            </div>

            <!-- Dynamic Headline: PESAN DISINI, TANPA ANTRI -->
            <div class="catchy-headline-box">
                <div class="headline-title">
                    <i class="fa-solid fa-bolt" style="font-size: 0.90rem;"></i> PESAN DISINI, TANPA ANTRI
                </div>
                <div class="headline-subtitle">
                    Buka Kamera HP &bull; Scan QR &bull; Pesanan Diantar
                </div>
            </div>

            <!-- Center QR Hero Section -->
            <div class="center-qr-section">
                <div class="qr-frame-wrapper">
                    <img src="{{ $table['qr_image'] }}" alt="QR Meja {{ $table['number'] }}">
                </div>
                <div class="qr-sub-instruction">
                    <i class="fa-solid fa-camera"></i> Arahkan Kamera HP ke Sini
                </div>
            </div>

            <!-- 3 Flow Steps -->
            <div class="flow-steps-grid">
                <div class="flow-step-card">
                    <div class="flow-step-num">1</div>
                    <div class="flow-step-title">Scan QR</div>
                    <div class="flow-step-desc">Buka Kamera HP</div>
                </div>
                <div class="flow-step-card">
                    <div class="flow-step-num">2</div>
                    <div class="flow-step-title">Pilih Menu</div>
                    <div class="flow-step-desc">Makanan &amp; Minum</div>
                </div>
                <div class="flow-step-card">
                    <div class="flow-step-num">3</div>
                    <div class="flow-step-title">Pesanan</div>
                    <div class="flow-step-desc">Akan Diantar</div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="standee-footer-strip">
                <span>⚡ Pesanan otomatis masuk dapur</span>
                <strong>Praktis &amp; Cepat</strong>
            </div>
        </div>
    </div>
</div>

<script>
    function changeSize(size, btn) {
        const card = document.getElementById('standeeCard');
        card.classList.remove('size-a5', 'size-compact');
        
        if (size === 'a5') {
            card.classList.add('size-a5');
        } else if (size === 'compact') {
            card.classList.add('size-compact');
        }

        btn.parentElement.querySelectorAll('.btn-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
    }
</script>

</body>
</html>
