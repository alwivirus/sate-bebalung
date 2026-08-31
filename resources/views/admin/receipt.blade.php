<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - {{ $order->order_code }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier Prime', monospace;
        }

        body {
            background-color: #E5E7EB;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 10px;
            min-height: 100vh;
        }

        /* Printable Receipt Container */
        .receipt-container {
            width: 320px;
            background: #FFFFFF;
            padding: 24px 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 4px;
            color: #000;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .receipt-logo {
            width: 54px;
            height: 54px;
            margin: 0 auto 6px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #000;
        }

        .receipt-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .store-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .store-sub {
            font-size: 0.72rem;
            line-height: 1.3;
            color: #333;
            margin-top: 2px;
        }

        .divider-double {
            border-top: 1.5px dashed #000;
            margin: 10px 0;
        }

        .divider-single {
            border-top: 1px dashed #555;
            margin: 8px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 4px;
        }

        .item-list-row {
            margin-bottom: 8px;
            font-size: 0.8rem;
        }

        .item-title-row {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
        }

        .item-calc-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.72rem;
            color: #444;
            padding-left: 6px;
        }

        .total-section {
            font-size: 0.85rem;
            font-weight: 700;
            margin-top: 8px;
        }

        .total-row-main {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 900;
            margin: 6px 0;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 0.72rem;
            line-height: 1.4;
        }

        .barcode-section {
            text-align: center;
            margin: 12px 0 6px 0;
        }

        /* Floating Action Toolbar (Hidden during print) */
        .print-toolbar {
            margin-top: 20px;
            display: flex;
            gap: 12px;
        }

        .btn-action {
            background: #111827;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-action:hover {
            background: #F59E0B;
            color: #111827;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .receipt-container {
                width: 100%;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }

            .print-toolbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="receipt-logo">
                <img src="{{ asset('images/logo-goat.png') }}" alt="Logo">
            </div>
            <div class="store-name">DEPOT SATE BE BA LUNG</div>
            <div class="store-sub">
                Sop & Gulai Kambing Khas Banyumas<br>
                Jl. Supriyadi No. 40, Purwokerto Wetan<br>
                Telp/WA: +62 812-2591-1012
            </div>
        </div>

        <div class="divider-double"></div>

        <!-- Order Meta Info -->
        <div class="info-row">
            <span>NO. PESANAN:</span>
            <strong>{{ $order->order_code }}</strong>
        </div>
        <div class="info-row">
            <span>MEJA:</span>
            <strong>MEJA #{{ $order->table_number }}</strong>
        </div>
        <div class="info-row">
            <span>PEMESAN:</span>
            <span>{{ $order->customer_name }}</span>
        </div>
        <div class="info-row">
            <span>WAKTU:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span>KASIR:</span>
            <span>KASIR UTAMA</span>
        </div>

        <div class="divider-single"></div>

        <!-- Itemized List -->
        @foreach($order->items as $item)
            <div class="item-list-row">
                <div class="item-title-row">
                    <span>{{ $item->menu_name }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="item-calc-row">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    @if($item->notes)
                        <span>({{ $item->notes }})</span>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="divider-single"></div>

        <!-- Total Breakdown -->
        <div class="total-section">
            <div class="info-row">
                <span>SUBTOTAL:</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span>PAJAK / SERVICE:</span>
                <span>Rp 0</span>
            </div>
            <div class="total-row-main">
                <span>TOTAL AKHIR:</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
            <div class="info-row">
                <span>METODE BAYAR:</span>
                <span>{{ $order->payment_method === 'online' ? 'QRIS ONLINE' : 'CASH DI KASIR' }}</span>
            </div>
            <div class="info-row">
                <span>STATUS:</span>
                <strong>{{ $order->payment_status === 'paid' ? 'LUNAS (PAID)' : 'BELUM LUNAS' }}</strong>
            </div>
        </div>

        <div class="divider-double"></div>

        <!-- Barcode Graphic -->
        <div class="barcode-section">
            <svg width="220" height="35" viewBox="0 0 220 35">
                <rect x="0" y="0" width="3" height="35" fill="#000"/>
                <rect x="5" y="0" width="2" height="35" fill="#000"/>
                <rect x="9" y="0" width="5" height="35" fill="#000"/>
                <rect x="17" y="0" width="2" height="35" fill="#000"/>
                <rect x="21" y="0" width="4" height="35" fill="#000"/>
                <rect x="28" y="0" width="2" height="35" fill="#000"/>
                <rect x="33" y="0" width="6" height="35" fill="#000"/>
                <rect x="42" y="0" width="3" height="35" fill="#000"/>
                <rect x="48" y="0" width="2" height="35" fill="#000"/>
                <rect x="53" y="0" width="5" height="35" fill="#000"/>
                <rect x="61" y="0" width="2" height="35" fill="#000"/>
                <rect x="66" y="0" width="4" height="35" fill="#000"/>
                <rect x="73" y="0" width="7" height="35" fill="#000"/>
                <rect x="83" y="0" width="3" height="35" fill="#000"/>
                <rect x="89" y="0" width="2" height="35" fill="#000"/>
                <rect x="94" y="0" width="5" height="35" fill="#000"/>
                <rect x="102" y="0" width="3" height="35" fill="#000"/>
                <rect x="108" y="0" width="2" height="35" fill="#000"/>
                <rect x="113" y="0" width="6" height="35" fill="#000"/>
                <rect x="122" y="0" width="4" height="35" fill="#000"/>
                <rect x="129" y="0" width="2" height="35" fill="#000"/>
                <rect x="134" y="0" width="5" height="35" fill="#000"/>
                <rect x="142" y="0" width="5" height="35" fill="#000"/>
                <rect x="150" y="0" width="2" height="35" fill="#000"/>
                <rect x="155" y="0" width="6" height="35" fill="#000"/>
                <rect x="164" y="0" width="3" height="35" fill="#000"/>
                <rect x="170" y="0" width="4" height="35" fill="#000"/>
                <rect x="177" y="0" width="5" height="35" fill="#000"/>
                <rect x="185" y="0" width="2" height="35" fill="#000"/>
                <rect x="190" y="0" width="4" height="35" fill="#000"/>
                <rect x="197" y="0" width="3" height="35" fill="#000"/>
                <rect x="204" y="0" width="6" height="35" fill="#000"/>
                <rect x="214" y="0" width="3" height="35" fill="#000"/>
            </svg>
            <div style="font-size: 0.75rem; letter-spacing: 2px; margin-top: 4px;">{{ $order->order_code }}</div>
        </div>

        <div class="receipt-footer">
            *** TERIMA KASIH ***<br>
            Selamat Menikmati Hidangan Kami<br>
            Kritik &amp; Saran: +62 812-2591-1012
        </div>
    </div>

    <!-- Floating Action Toolbar -->
    <div class="print-toolbar">
        <button type="button" class="btn-action" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
        <button type="button" class="btn-action" style="background: #4B5563;" onclick="window.close()">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    <script>
        // Auto trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
