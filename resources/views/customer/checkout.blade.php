@extends('layouts.app')

@section('title', 'Ringkasan Pesanan - Depot Sate Be Ba Lung')

@section('styles')
<style>
    .checkout-container {
        padding: 24px 18px;
        min-height: calc(100vh - 240px);
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 900;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    /* Summary Card */
    .summary-card {
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 20px;
        box-shadow: var(--box-shadow-brutal);
        padding: 18px;
        margin-bottom: 24px;
    }

    .order-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-weight: 800;
        font-size: 0.95rem;
        color: #111827;
    }

    .order-item-row .item-name {
        flex: 1;
    }

    .order-item-row .item-qty {
        font-size: 0.82rem;
        color: #374151;
        margin-left: 4px;
        font-weight: 800;
    }

    .order-item-row .item-price {
        font-size: 0.95rem;
        color: #111827;
        font-weight: 900;
    }

    .dashed-divider {
        border-top: 2px dashed #1E1E1E;
        margin: 16px 0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 1.15rem;
        font-weight: 900;
        color: #111827;
        letter-spacing: 0.5px;
    }

    .total-price-box {
        background-color: #FFAEA5;
        border: 2.5px solid var(--dark-border);
        border-radius: 10px;
        box-shadow: 2px 2px 0px var(--dark-border);
        padding: 6px 14px;
        font-size: 1.15rem;
        font-weight: 900;
        color: #111827;
    }

    /* Payment Method Selection */
    .payment-option-card {
        background-color: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        box-shadow: var(--box-shadow-brutal);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .payment-option-card.active {
        background-color: #FEF3C7;
        border-color: #1E1E1E;
        transform: translate(-1px, -1px);
        box-shadow: 4px 5px 0px #1E1E1E;
    }

    .payment-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .payment-icon-box {
        width: 46px;
        height: 46px;
        border: 2.5px solid var(--dark-border);
        border-radius: 12px;
        box-shadow: 2px 2px 0px var(--dark-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .payment-icon-box.online {
        background-color: #FBBF24;
        color: #1E1E1E;
    }

    .payment-icon-box.kasir {
        background-color: #EF4444;
        color: #FFFFFF;
    }

    .payment-title {
        font-weight: 900;
        font-size: 0.95rem;
        color: #111827;
        letter-spacing: 0.5px;
    }

    .radio-circle {
        width: 24px;
        height: 24px;
        border: 2.5px solid var(--dark-border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }

    .payment-option-card.active .radio-circle::after {
        content: '';
        width: 12px;
        height: 12px;
        background-color: #111827;
        border-radius: 50%;
    }

    /* Submit Button */
    .confirm-order-btn {
        width: 100%;
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: var(--box-shadow-brutal);
        padding: 14px;
        font-weight: 900;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 24px;
        margin-bottom: 12px;
        transition: transform 0.1s;
    }

    .confirm-order-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #000;
    }

    .confirm-order-btn .main-text {
        font-size: 1.05rem;
        color: #111827;
        letter-spacing: 0.5px;
    }

    .confirm-order-btn .sub-text {
        font-size: 0.85rem;
        color: #374151;
        margin-top: 2px;
    }

    .back-link {
        display: block;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 800;
        color: #4B5563;
        text-decoration: none;
        margin-top: 10px;
        padding: 8px;
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    @if(empty($items) || count($items) === 0)
        <!-- Empty State Card -->
        <h2 class="section-title">RINGKASAN PESANAN</h2>
        <div class="summary-card" style="text-align: center; padding: 40px 20px; background: white;">
            <div style="width: 70px; height: 70px; background: #FEF3C7; border: 2.5px solid var(--dark-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #111827; margin: 0 auto 16px auto; box-shadow: 2px 2px 0px var(--dark-border);">
                <i class="fa-solid fa-cart-plus"></i>
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 900; color: #111827; margin-bottom: 6px;">Keranjang Masih Kosong</h3>
            <p style="font-size: 0.85rem; color: #4B5563; line-height: 1.4; max-width: 280px; margin: 0 auto 20px auto;">
                Belum ada menu yang dipilih untuk Meja #{{ $tableNumber }}. Silakan pilih menu sate &amp; gulai favorit Anda terlebih dahulu.
            </p>
            <a href="{{ route('customer.menu', ['meja' => $tableNumber]) }}" class="confirm-order-btn" style="text-decoration: none; max-width: 280px; margin: 0 auto;">
                <span class="main-text">LIHAT MENU SEKARANG</span>
            </a>
        </div>
    @else
        <!-- Section 1: Ringkasan Pesanan -->
        <h2 class="section-title">RINGKASAN PESANAN</h2>
        
        <div class="summary-card">
            @foreach($items as $item)
                <div class="order-item-row">
                    <div class="item-name">
                        {{ $item['menu']->name }}
                        @if($item['quantity'] > 1)
                            <span class="item-qty">x{{ $item['quantity'] }}</span>
                        @endif
                    </div>
                    <div class="item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                </div>
            @endforeach

            <div class="dashed-divider"></div>

            <div class="total-row">
                <div class="total-label">TOTAL</div>
                <div class="total-price-box">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Section 2: Form Pesanan & Pilih Pembayaran -->
        <form action="{{ route('order.store') }}" method="POST" id="paymentForm" onsubmit="localStorage.removeItem('beba_cart');">
            @csrf
            <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="online">

            <!-- Passing Cart Items Data -->
            @foreach($items as $index => $item)
                <input type="hidden" name="cart_items[{{ $index }}][menu_id]" value="{{ $item['menu']->id }}">
                <input type="hidden" name="cart_items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
            @endforeach

            <h2 class="section-title" style="margin-top: 10px;">PILIH PEMBAYARAN</h2>

            <!-- Option 1: Bayar Online / QRIS -->
            <div class="payment-option-card active" onclick="selectPayment('online')" id="opt-online">
                <div class="payment-icon-wrapper">
                    <div class="payment-icon-box online">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div class="payment-title">BAYAR ONLINE</div>
                </div>
                <div class="radio-circle"></div>
            </div>

            <!-- Option 2: Bayar di Kasir -->
            <div class="payment-option-card" onclick="selectPayment('kasir')" id="opt-kasir">
                <div class="payment-icon-wrapper">
                    <div class="payment-icon-box kasir">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <div class="payment-title">BAYAR DI KASIR</div>
                </div>
                <div class="radio-circle"></div>
            </div>

            <!-- Form Meja & Catatan -->
            <div style="background: white; border: 3px solid var(--dark-border); border-radius: 16px; padding: 16px; margin-top: 16px; box-shadow: var(--box-shadow-brutal);">
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 105px;">
                        <label style="font-size: 0.75rem; font-weight: 900; color: #111827; display: flex; align-items: center; gap: 4px; margin-bottom: 5px;">
                            <i class="fa-solid fa-qrcode" style="color: #F59E0B;"></i> MEJA
                        </label>
                        <div style="background: #FEF3C7; border: 2.5px solid var(--dark-border); border-radius: 10px; padding: 8px 10px; font-weight: 900; font-size: 1rem; color: #111827; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 1.5px 1.5px 0px var(--dark-border);">
                            <span>#{{ $tableNumber }}</span>
                            <i class="fa-solid fa-circle-check" style="color: #059669; font-size: 0.8rem;" title="Meja Terverifikasi Hasil Scan QR"></i>
                        </div>
                        <input type="hidden" name="table_number" value="{{ $tableNumber }}">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 0.75rem; font-weight: 900; color: #111827; display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                            <span><i class="fa-solid fa-user" style="color: #4B5563;"></i> NAMA PEMESAN</span>
                            <span style="color: #DC2626; font-size: 0.7rem; font-weight: 800;">* WAJIB DIISI</span>
                        </label>
                        <input 
                            type="text" 
                            name="customer_name" 
                            id="customerNameInput"
                            value="{{ $customerName !== 'Pelanggan' ? $customerName : '' }}" 
                            style="width: 100%; padding: 9px 12px; border: 2.5px solid var(--dark-border); border-radius: 10px; font-weight: 800; font-size: 0.95rem; background: #FFFDF7;" 
                            placeholder="Ketik nama Anda (cth: Budi / Ani)..." 
                            required 
                            minlength="2"
                            autocomplete="name"
                        >
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.75rem; font-weight: 800; color: #4B5563; display: block; margin-bottom: 5px;">
                        <i class="fa-solid fa-pen-to-square"></i> CATATAN PESANAN (OPSIONAL)
                    </label>
                    <input type="text" name="notes" style="width: 100%; padding: 8px 12px; border: 2px solid var(--dark-border); border-radius: 8px; font-size: 0.85rem;" placeholder="Misal: Sate tidak pedas, kuah gulai dipisah...">
                </div>
            </div>

            <!-- Konfirmasi Pesanan Button -->
            <button type="submit" class="confirm-order-btn">
                <span class="main-text">KONFIRMASI PESANAN</span>
                <span class="sub-text">Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
            </button>

            <a href="{{ route('customer.menu', ['meja' => $tableNumber]) }}" class="back-link">
                <i class="fa-solid fa-chevron-left"></i> Ubah Pesanan / Kembali ke Menu
            </a>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function selectPayment(method) {
        document.getElementById('selectedPaymentMethod').value = method;
        
        document.getElementById('opt-online').classList.remove('active');
        document.getElementById('opt-kasir').classList.remove('active');

        if (method === 'online') {
            document.getElementById('opt-online').classList.add('active');
        } else {
            document.getElementById('opt-kasir').classList.add('active');
        }
    }

    const payForm = document.getElementById('paymentForm');
    const TABLE_NUM = "{{ $tableNumber }}";
    const CART_STORAGE_KEY = `beba_cart_meja_${TABLE_NUM}`;

    if (payForm) {
        payForm.addEventListener('submit', function(e) {
            const nameInput = document.getElementById('customerNameInput');
            if (nameInput && nameInput.value.trim().length < 2) {
                e.preventDefault();
                alert('Silakan isi Nama Pemesan terlebih dahulu agar pesanan dapat diantar dengan tepat ke Meja #{{ $tableNumber }}.');
                nameInput.focus();
                return false;
            }
            try {
                localStorage.removeItem(CART_STORAGE_KEY);
                localStorage.removeItem('beba_cart');
            } catch (err) {}
        });
    }

    // Auto-restore cart from localStorage if PHP session is empty
    document.addEventListener('DOMContentLoaded', function() {
        const hasItems = {{ !empty($items) && count($items) > 0 ? 'true' : 'false' }};
        if (!hasItems) {
            try {
                const saved = localStorage.getItem(CART_STORAGE_KEY);
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed && typeof parsed === 'object' && Object.keys(parsed).length > 0) {
                        // Auto-submit to render checkout items
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('customer.checkout') }}';

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        const cartIn = document.createElement('input');
                        cartIn.type = 'hidden';
                        cartIn.name = 'cart';
                        cartIn.value = saved;
                        form.appendChild(cartIn);

                        const tableIn = document.createElement('input');
                        tableIn.type = 'hidden';
                        tableIn.name = 'table_number';
                        tableIn.value = '{{ $tableNumber }}';
                        form.appendChild(tableIn);

                        const nameIn = document.createElement('input');
                        nameIn.type = 'hidden';
                        nameIn.name = 'customer_name';
                        nameIn.value = '{{ $customerName }}';
                        form.appendChild(nameIn);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            } catch (e) {}
        }
    });
</script>
@endsection
