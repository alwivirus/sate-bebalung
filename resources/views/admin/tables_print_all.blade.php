<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Standee Meja QR (4 per Lembar A4) - Depot Be Ba Lung</title>
    
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
            padding: 20px 12px 60px 12px;
        }

        /* ----------------------------------------------------
           TOP CONTROL PANEL (Hidden during print)
        ---------------------------------------------------- */
        .control-panel {
            background: #1E293B;
            border: 1.5px solid #EA580C;
            border-radius: 18px;
            padding: 16px 22px;
            margin-bottom: 24px;
            max-width: 960px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 20px rgba(234, 88, 12, 0.2);
            color: #FFFFFF;
            position: sticky;
            top: 14px;
            z-index: 999;
        }

        .panel-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #334155;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 900;
            font-size: 0.90rem;
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

        /* Filter Controls */
        .filter-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 800;
            color: #FCD34D;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-mode {
            background: #0F172A;
            color: #CBD5E1;
            border: 1px solid #475569;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-mode.active {
            background: #EA580C;
            border-color: #F97316;
            color: #FFFFFF;
            font-weight: 800;
        }

        .custom-select-box {
            background: #0F172A;
            color: #FFFFFF;
            border: 1.5px solid #EA580C;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.80rem;
            outline: none;
        }

        /* Table Checkbox Grid */
        .table-picker-drawer {
            background: #0F172A;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 10px 14px;
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .table-check-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #1E293B;
            border: 1px solid #475569;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #E2E8F0;
            cursor: pointer;
            user-select: none;
            transition: all 0.15s;
        }
        .table-check-item input {
            accent-color: #EA580C;
            cursor: pointer;
        }
        .table-check-item.checked {
            background: #7C2D12;
            border-color: #EA580C;
            color: #FED7AA;
        }

        /* ----------------------------------------------------
           A4 SHEET ENGINE (Strictly 4 Standees Per A4 Sheet)
        ---------------------------------------------------- */
        .sheets-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 26px;
            width: 100%;
        }

        .sheet-wrapper {
            position: relative;
        }

        .sheet-badge {
            position: absolute;
            top: -12px;
            left: 20px;
            background: #EA580C;
            color: #FFFFFF;
            font-size: 0.72rem;
            font-weight: 900;
            padding: 2px 12px;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
            z-index: 10;
        }

        .a4-sheet {
            width: 210mm;
            min-height: 297mm;
            max-height: 297mm;
            background: #FFFFFF;
            padding: 6mm 6mm;
            display: grid;
            grid-template-columns: repeat(2, 96mm);
            grid-template-rows: repeat(2, 140mm);
            gap: 4mm 4mm;
            justify-content: center;
            align-content: center;
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
            border-radius: 8px;
            box-sizing: border-box;
            position: relative;
            page-break-after: always;
            break-after: page;
        }

        /* Individual Standee Card Wrapper */
        .standee-wrapper {
            position: relative;
            padding: 1px;
            border: 1px dashed #CBD5E1;
            border-radius: 18px;
            background: #FFFFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
        }

        /* ----------------------------------------------------
           DYNAMIC & APPETIZING RESTAURANT STANDEE CARD
           (Pesan Disini Tanpa Antri - Center QR - No Kaku)
        ---------------------------------------------------- */
        .standee-card {
            width: 100%;
            height: 100%;
            border-radius: 16px;
            padding: 8px 8px 6px 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            background: linear-gradient(180deg, #FFF9F5 0%, #FFFFFF 30%, #FFF8F3 100%);
            border: 2.2px solid #EA580C;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.08);
        }

        /* Top Brand Header Bar */
        .brand-top-bar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 4px;
            border-bottom: 1.5px dashed #FDBA74;
        }

        .brand-logo-name {
            display: flex;
            align-items: center;
            gap: 5px;
            text-align: left;
        }

        .brand-icon-circle {
            width: 24px;
            height: 24px;
            background: #0F172A;
            border-radius: 6px;
            border: 1.5px solid #F59E0B;
            padding: 1.5px;
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
            font-size: 0.74rem;
            font-weight: 900;
            color: #0F172A;
            line-height: 1;
            letter-spacing: 0.3px;
        }

        .brand-text-sub {
            font-size: 0.44rem;
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
            border-radius: 7px;
            padding: 2px 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(234, 88, 12, 0.3);
            border: 1px solid #FED7AA;
            display: flex;
            align-items: baseline;
            gap: 3px;
        }

        .table-pill-label {
            font-size: 0.46rem;
            font-weight: 900;
            color: #FED7AA;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-pill-num {
            font-size: 1.05rem;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1;
        }

        /* Dynamic Headline: PESAN DISINI, TANPA ANTRI */
        .catchy-headline-box {
            width: 100%;
            text-align: center;
            margin: 3px 0 2px 0;
            background: #FFF0E6;
            border: 1px solid #FDBA74;
            border-radius: 8px;
            padding: 4px 6px;
        }

        .headline-title {
            font-family: 'Paytone One', 'Plus Jakarta Sans', sans-serif;
            font-size: 0.96rem;
            line-height: 1;
            color: #EA580C;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .headline-subtitle {
            font-size: 0.52rem;
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
            width: 172px;
            height: 172px;
            aspect-ratio: 1 / 1;
            background: #FFFFFF;
            border: 2.5px solid #0F172A;
            border-radius: 12px;
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
            margin-top: 3px;
            background: #0F172A;
            color: #FEF3C7;
            font-size: 0.52rem;
            font-weight: 900;
            padding: 2px 10px;
            border-radius: 12px;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* 3 Flow Steps (Clean & Relevant: Scan, Pilih Menu, Diantar) */
        .flow-steps-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
            margin-top: 3px;
        }

        .flow-step-card {
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            border-radius: 6px;
            padding: 3px 2px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .flow-step-num {
            width: 15px;
            height: 15px;
            background: #EA580C;
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.52rem;
            font-weight: 900;
            margin-bottom: 1px;
        }

        .flow-step-title {
            font-size: 0.54rem;
            font-weight: 900;
            color: #0F172A;
            line-height: 1.1;
        }

        .flow-step-desc {
            font-size: 0.44rem;
            font-weight: 600;
            color: #64748B;
            line-height: 1;
        }

        /* Footer Guarantee Note */
        .standee-footer-strip {
            width: 100%;
            border-top: 1px solid #FDBA74;
            padding-top: 2px;
            font-size: 0.50rem;
            font-weight: 700;
            color: #7C2D12;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .standee-footer-strip strong {
            color: #EA580C;
        }

        /* ----------------------------------------------------
           PRINT ENGINE (Strict 1-Sheet-4-QR Rules)
        ---------------------------------------------------- */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: auto !important;
            }

            .control-panel, .sheet-badge {
                display: none !important;
            }

            .sheets-container {
                gap: 0 !important;
            }

            .sheet-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }

            .a4-sheet {
                width: 210mm !important;
                height: 297mm !important;
                min-height: 297mm !important;
                max-height: 297mm !important;
                padding: 6mm 6mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-after: always !important;
                break-after: page !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .standee-wrapper {
                border-color: #94A3B8 !important;
            }

            .standee-card {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<!-- Control Panel (Hidden on Print) -->
<div class="control-panel">
    <div class="panel-top-row">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 900; color: #FEF3C7; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-qrcode" style="color: #F59E0B;"></i> Cetak Standee Meja QR (Format 4 QR / Lembar A4)
            </h2>
            <p style="font-size: 0.78rem; color: #94A3B8; margin-top: 2px;">
                Desain dinamis "Pesan Disini, Tanpa Antri" dengan QR besar di tengah. Pas 4 meja per lembar A4.
            </p>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn-action btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Cetak Sekarang (<span id="printCountDisplay">20</span> Meja)
            </button>
            <a href="{{ route('admin.tables.index') }}" class="btn-action btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter & Configuration Controls -->
    <div class="filter-row">
        <!-- Mode Cetak -->
        <div class="filter-group">
            <span class="filter-label"><i class="fa-solid fa-sliders"></i> Mode Cetak:</span>
            <button type="button" class="btn-mode active" onclick="setMode('range', this)">Urut Rentang Meja</button>
            <button type="button" class="btn-mode" onclick="setMode('custom', this)">Pilih Meja Sendiri (Centang)</button>
            <button type="button" class="btn-mode" onclick="setMode('duplicate', this)">Cetak 1 Meja Berulang (4x / 8x)</button>
        </div>

        <!-- Range Selector -->
        <div class="filter-group" id="rangeControls">
            <span class="filter-label">Rentang:</span>
            <select class="custom-select-box" id="rangeSelect" onchange="applyRangeSelection()">
                <option value="1-20" selected>Meja 01 s/d Meja 20 (5 Lembar A4)</option>
                <option value="1-4">Meja 01 s/d Meja 04 (1 Lembar A4)</option>
                <option value="1-8">Meja 01 s/d Meja 08 (2 Lembar A4)</option>
                <option value="1-12">Meja 01 s/d Meja 12 (3 Lembar A4)</option>
                <option value="1-16">Meja 01 s/d Meja 16 (4 Lembar A4)</option>
                <option value="1-10">Meja 01 s/d Meja 10 (3 Lembar A4)</option>
                <option value="11-20">Meja 11 s/d Meja 20 (3 Lembar A4)</option>
                <option value="1-30">Meja 01 s/d Meja 30 (8 Lembar A4)</option>
                <option value="1-50">Meja 01 s/d Meja 50 (13 Lembar A4)</option>
            </select>
        </div>

        <!-- Duplicate Selector -->
        <div class="filter-group" id="duplicateControls" style="display: none;">
            <span class="filter-label">Pilih Meja:</span>
            <select class="custom-select-box" id="duplicateTableNum" onchange="applyDuplicateSelection()">
                @for($i = 1; $i <= 50; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">Meja #{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <span class="filter-label">Jumlah Salinan:</span>
            <select class="custom-select-box" id="duplicateQty" onchange="applyDuplicateSelection()">
                <option value="4" selected>4 Salinan (1 Lembar A4 Penuh)</option>
                <option value="8">8 Salinan (2 Lembar A4 Penuh)</option>
                <option value="12">12 Salinan (3 Lembar A4 Penuh)</option>
            </select>
        </div>
    </div>

    <!-- Table Checkboxes -->
    <div class="table-picker-drawer" id="customPickerDrawer" style="display: none;">
        <span style="font-size: 0.74rem; font-weight: 800; color: #F59E0B; margin-right: 6px;">Centang Meja:</span>
        <button type="button" onclick="selectAllTables(true)" class="btn-mode" style="padding: 3px 8px; font-size: 0.70rem;">Pilih Semua</button>
        <button type="button" onclick="selectAllTables(false)" class="btn-mode" style="padding: 3px 8px; font-size: 0.70rem;">Batal Semua</button>
        
        <div style="display: flex; flex-wrap: wrap; gap: 6px; width: 100%; margin-top: 8px;">
            @for($i = 1; $i <= 50; $i++)
                @php $tNum = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                <label class="table-check-item checked" id="checkLabel_{{ $tNum }}">
                    <input type="checkbox" value="{{ $tNum }}" class="table-checkbox" onchange="handleCheckboxChange(this)" checked>
                    Meja {{ $tNum }}
                </label>
            @endfor
        </div>
    </div>
</div>

<!-- Dynamic Printable Sheets Container -->
<div class="sheets-container" id="sheetsContainer">
    <!-- Populated dynamically via JS to guarantee perfect 4-per-A4 pagination -->
</div>

<script>
    // Raw table data from server
    const allTables = [
        @for($i = 1; $i <= 50; $i++)
            @php 
                $tNum = str_pad($i, 2, '0', STR_PAD_LEFT); 
                $scanUrl = \App\Models\Table::getSecureScanUrl($tNum);
                $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=450x450&data=' . urlencode($scanUrl) . '&margin=0';
            @endphp
            {
                number: "{{ $tNum }}",
                scan_url: "{{ $scanUrl }}",
                qr_image: "{{ $qrApiUrl }}"
            },
        @endfor
    ];

    let currentMode = 'range';
    let selectedTableNumbers = [];

    // Initialize default range (1-20)
    for (let i = 1; i <= 20; i++) {
        selectedTableNumbers.push(String(i).padStart(2, '0'));
    }

    function setMode(mode, btn) {
        currentMode = mode;
        document.querySelectorAll('.btn-mode').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        document.getElementById('rangeControls').style.display = (mode === 'range') ? 'flex' : 'none';
        document.getElementById('duplicateControls').style.display = (mode === 'duplicate') ? 'flex' : 'none';
        document.getElementById('customPickerDrawer').style.display = (mode === 'custom') ? 'flex' : 'none';

        if (mode === 'range') {
            applyRangeSelection();
        } else if (mode === 'duplicate') {
            applyDuplicateSelection();
        } else if (mode === 'custom') {
            syncCheckboxesWithSelection();
            renderSheets();
        }
    }

    function applyRangeSelection() {
        const val = document.getElementById('rangeSelect').value;
        const [start, end] = val.split('-').map(Number);
        selectedTableNumbers = [];
        for (let i = start; i <= end; i++) {
            selectedTableNumbers.push(String(i).padStart(2, '0'));
        }
        renderSheets();
    }

    function applyDuplicateSelection() {
        const tableNum = document.getElementById('duplicateTableNum').value;
        const qty = parseInt(document.getElementById('duplicateQty').value) || 4;
        selectedTableNumbers = [];
        for (let i = 0; i < qty; i++) {
            selectedTableNumbers.push(tableNum);
        }
        renderSheets();
    }

    function handleCheckboxChange(cb) {
        const label = document.getElementById('checkLabel_' + cb.value);
        if (cb.checked) {
            label.classList.add('checked');
            if (!selectedTableNumbers.includes(cb.value)) {
                selectedTableNumbers.push(cb.value);
            }
        } else {
            label.classList.remove('checked');
            selectedTableNumbers = selectedTableNumbers.filter(num => num !== cb.value);
        }
        selectedTableNumbers.sort((a, b) => Number(a) - Number(b));
        renderSheets();
    }

    function selectAllTables(status) {
        document.querySelectorAll('.table-checkbox').forEach(cb => {
            cb.checked = status;
            const label = document.getElementById('checkLabel_' + cb.value);
            if (status) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
        if (status) {
            selectedTableNumbers = allTables.map(t => t.number);
        } else {
            selectedTableNumbers = [];
        }
        renderSheets();
    }

    function syncCheckboxesWithSelection() {
        document.querySelectorAll('.table-checkbox').forEach(cb => {
            const isChecked = selectedTableNumbers.includes(cb.value);
            cb.checked = isChecked;
            const label = document.getElementById('checkLabel_' + cb.value);
            if (isChecked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
    }

    // Render exact 4 standees per A4 sheet
    function renderSheets() {
        const container = document.getElementById('sheetsContainer');
        container.innerHTML = '';

        document.getElementById('printCountDisplay').innerText = selectedTableNumbers.length;

        if (selectedTableNumbers.length === 0) {
            container.innerHTML = '<div style="color: #F87171; font-weight: 800; padding: 40px; background: #1E293B; border-radius: 12px;">Tidak ada meja yang dipilih untuk dicetak. Silakan pilih minimal 1 meja.</div>';
            return;
        }

        // Chunk into groups of 4
        const chunks = [];
        for (let i = 0; i < selectedTableNumbers.length; i += 4) {
            chunks.push(selectedTableNumbers.slice(i, i + 4));
        }

        chunks.forEach((chunk, pageIndex) => {
            const sheetWrapper = document.createElement('div');
            sheetWrapper.className = 'sheet-wrapper';

            const startTable = chunk[0];
            const endTable = chunk[chunk.length - 1];
            const sheetBadge = document.createElement('div');
            sheetBadge.className = 'sheet-badge';
            sheetBadge.innerHTML = `<i class="fa-solid fa-file-lines"></i> Lembar A4 #${pageIndex + 1} (${chunk.length} QR • Meja ${startTable} ${chunk.length > 1 ? 's/d ' + endTable : ''})`;
            sheetWrapper.appendChild(sheetBadge);

            const a4Sheet = document.createElement('div');
            a4Sheet.className = 'a4-sheet';

            chunk.forEach(tableNum => {
                const tableData = allTables.find(t => t.number === tableNum) || {
                    number: tableNum,
                    qr_image: `https://api.qrserver.com/v1/create-qr-code/?size=450x450&data=${encodeURIComponent(window.location.origin + '/?meja=' + tableNum)}&margin=0`
                };

                const cardEl = document.createElement('div');
                cardEl.className = 'standee-wrapper';
                cardEl.innerHTML = `
                    <div class="standee-card">
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
                                <span class="table-pill-num">${tableData.number}</span>
                            </div>
                        </div>

                        <!-- Dynamic Headline: PESAN DISINI, TANPA ANTRI -->
                        <div class="catchy-headline-box">
                            <div class="headline-title">
                                <i class="fa-solid fa-bolt" style="font-size: 0.82rem;"></i> PESAN DISINI, TANPA ANTRI
                            </div>
                            <div class="headline-subtitle">
                                Buka Kamera HP &bull; Scan QR &bull; Pesanan Diantar
                            </div>
                        </div>

                        <!-- Center QR Hero Section -->
                        <div class="center-qr-section">
                            <div class="qr-frame-wrapper">
                                <img src="${tableData.qr_image}" alt="QR Meja ${tableData.number}">
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
                                <div class="flow-step-desc">Kamera HP</div>
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
                `;
                a4Sheet.appendChild(cardEl);
            });

            // If less than 4 items on last page, fill with empty placeholders to keep grid dimensions intact
            if (chunk.length < 4) {
                for (let k = chunk.length; k < 4; k++) {
                    const emptySlot = document.createElement('div');
                    emptySlot.style.visibility = 'hidden';
                    a4Sheet.appendChild(emptySlot);
                }
            }

            sheetWrapper.appendChild(a4Sheet);
            container.appendChild(sheetWrapper);
        });
    }

    // Initial render
    renderSheets();
</script>

</body>
</html>
