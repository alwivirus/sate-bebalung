@extends('layouts.app')

@section('title', 'Menu Depot Sate & Gulai Be Ba Lung')

@section('styles')
<style>
    /* Hero Header */
    .hero-header {
        position: relative;
        width: 100%;
        height: 240px;
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800&auto=format&fit=crop') center/cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-bottom: 4px solid var(--dark-border);
        padding-top: 10px;
    }

    .hero-logo-badge {
        width: 128px;
        height: 128px;
        background: white;
        border-radius: 50%;
        border: 4px solid var(--dark-border);
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 6px;
        overflow: hidden;
    }

    .hero-logo-badge img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-title {
        color: #FFFFFF;
        text-align: center;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    }

    .hero-title h2 {
        font-size: 1rem;
        font-weight: 900;
        letter-spacing: 0.5px;
    }

    .hero-title p {
        font-size: 0.75rem;
        color: #FBBF24;
        font-weight: 800;
    }

    /* Table Bar */
    .table-bar {
        background: #FFFFFF;
        border-bottom: 3px solid var(--dark-border);
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        font-weight: 800;
    }

    .table-badge {
        background: var(--primary-yellow);
        border: 2px solid var(--dark-border);
        padding: 4px 10px;
        border-radius: 8px;
        box-shadow: 2px 2px 0px var(--dark-border);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .menu-content {
        padding: 16px;
        padding-bottom: 90px;
    }

    /* Food Item Horizontal Card */
    .food-card {
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 20px;
        box-shadow: var(--box-shadow-brutal);
        padding: 12px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .food-info-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .food-thumb {
        width: 75px;
        height: 75px;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .food-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .food-text h3 {
        font-size: 0.95rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .food-text .price {
        font-size: 0.9rem;
        font-weight: 800;
        color: #DC2626;
    }

    /* Drink Grid 2-columns */
    .drinks-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .drink-card {
        background-color: var(--primary-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        box-shadow: var(--box-shadow-brutal);
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .drink-thumb {
        width: 100%;
        height: 90px;
        background: #E5E7EB;
        border: 2.5px solid var(--dark-border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .drink-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .drink-card h3 {
        font-size: 0.88rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 2px;
    }

    .drink-card .price {
        font-size: 0.82rem;
        font-weight: 800;
        color: #DC2626;
        margin-bottom: 8px;
    }

    /* Plus / Quantity Buttons */
    .qty-btn-box {
        width: 100%;
        height: 38px;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 10px;
        box-shadow: 2px 2px 0px var(--dark-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 900;
        cursor: pointer;
        transition: transform 0.1s, box-shadow 0.1s;
    }

    .qty-btn-box:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark-border);
    }

    .drink-qty-btn-box {
        width: 100%;
        height: 38px;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 10px;
        box-shadow: 2px 2px 0px var(--dark-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 900;
        cursor: pointer;
        transition: transform 0.1s, box-shadow 0.1s;
    }

    .drink-qty-btn-box:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark-border);
    }

    .qty-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 38px;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 10px;
        padding: 3px 6px;
        box-shadow: 2px 2px 0px var(--dark-border);
        box-sizing: border-box;
    }

    .qty-stepper button {
        width: 32px;
        height: 28px;
        background: var(--primary-yellow);
        border: 2px solid var(--dark-border);
        border-radius: 6px;
        font-weight: 900;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        flex-shrink: 0;
        transition: transform 0.1s;
    }

    .qty-stepper button:active {
        transform: scale(0.9);
    }

    .qty-stepper span {
        font-weight: 900;
        font-size: 1rem;
        color: #111827;
        flex: 1;
        text-align: center;
    }

    /* Sticky Bottom Floating Cart Bar */
    .sticky-cart-bar {
        position: fixed;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 32px);
        max-width: 448px;
        background-color: #111827;
        color: white;
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: 4px 4px 0px var(--dark-border);
        padding: 12px 16px;
        display: none;
        align-items: center;
        justify-content: space-between;
        z-index: 999;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from { transform: translate(-50%, 50px); opacity: 0; }
        to { transform: translate(-50%, 0); opacity: 1; }
    }

    .cart-summary-text h4 {
        font-size: 0.95rem;
        font-weight: 800;
        color: #FBBF24;
    }

    .cart-summary-text p {
        font-size: 0.75rem;
        color: #9CA3AF;
    }

    .checkout-btn {
        background-color: var(--primary-yellow);
        color: #111827;
        border: 2px solid var(--dark-border);
        padding: 8px 14px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        box-shadow: 2px 2px 0px #000;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<div class="hero-header">
    <div class="hero-logo-badge">
        <img src="{{ asset('images/logo-goat.png') }}" alt="Depot Sate Be Ba Lung">
    </div>
    <div class="hero-title">
        <h2 style="font-size: 1.15rem; font-weight: 900; color: #FFFFFF; letter-spacing: 0.5px; text-shadow: 2px 2px 4px #000; margin: 0;">DEPOT Sate</h2>
        <p style="font-size: 0.78rem; color: #FBBF24; font-weight: 800; text-shadow: 1px 1px 3px #000; margin: 2px 0 0 0;">Sop &amp; Gulai Kambing</p>
        <p style="font-size: 0.95rem; color: #F97316; font-weight: 900; letter-spacing: 1px; text-shadow: 1px 1px 3px #000; margin: 2px 0 0 0;">"BE BA LUNG"</p>
    </div>
</div>

<!-- Table Bar -->
<div class="table-bar">
    <div class="table-badge">
        <i class="fa-solid fa-qrcode"></i>
        <span>Meja #{{ $tableNumber }}</span>
    </div>
    <div style="font-size: 0.72rem; color: #065F46; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; background: #D1FAE5; padding: 4px 8px; border-radius: 6px; border: 1px solid #10B981;">
        <span style="width: 7px; height: 7px; background: #10B981; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #10B981;"></span>
        <span>Terhubung ke Kasir (Sedang Dipakai)</span>
    </div>
</div>

<!-- Menu Catalog Content -->
<div class="menu-content">
    @foreach($categories as $category)
        <div class="category-section" style="margin-bottom: 24px;">
            <div class="category-badge">
                <div class="category-icon-box {{ $category->slug === 'minuman' ? 'drink' : '' }}">
                    @if($category->slug === 'minuman')
                        <i class="fa-solid fa-mug-hot"></i>
                    @else
                        <i class="fa-solid fa-utensils"></i>
                    @endif
                </div>
                <span>{{ $category->name }}</span>
            </div>

            @if($category->slug === 'makanan')
                <!-- Single Column Makanan List -->
                @foreach($category->menus as $menu)
                    <div class="food-card" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                        <div class="food-info-wrapper">
                            <div class="food-thumb">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="food-text">
                                <h3>{{ $menu->name }}</h3>
                                <div class="price">{{ $menu->formatted_price }}</div>
                            </div>
                        </div>

                        <div class="food-action" id="action-{{ $menu->id }}">
                            <div class="qty-btn-box" onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- 2-Column Minuman Grid -->
                <div class="drinks-grid">
                    @foreach($category->menus as $menu)
                        <div class="drink-card" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                            <div class="drink-thumb">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h3>{{ $menu->name }}</h3>
                            <div class="price">{{ $menu->formatted_price }}</div>

                            <div class="drink-action" id="action-{{ $menu->id }}" style="width: 100%;">
                                <div class="drink-qty-btn-box" onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Floating Cart Checkout Bar -->
<div class="sticky-cart-bar" id="stickyCart">
    <div class="cart-summary-text">
        <h4 id="cartTotalDisplay">Total: Rp 0</h4>
        <p id="cartItemsCount">0 Item dipilih</p>
    </div>
    
    <form id="checkoutForm" action="{{ route('customer.checkout') }}" method="POST" style="margin: 0;" onsubmit="return prepareCheckout(event);">
        @csrf
        <input type="hidden" name="table_number" value="{{ $tableNumber }}">
        <input type="hidden" name="customer_name" value="{{ $customerName !== 'Pelanggan' ? $customerName : '' }}">
        <input type="hidden" name="cart" id="cartPayload" value="{}">
        <button type="submit" class="checkout-btn" id="btnCheckoutSubmit">
            <span>Pesan Sekarang</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let cart = {};
    const menuPrices = {};

    @foreach($categories as $category)
        @foreach($category->menus as $menu)
            menuPrices[{{ $menu->id }}] = {{ $menu->price }};
        @endforeach
    @endforeach

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    function saveCart() {
        try {
            localStorage.setItem('beba_cart', JSON.stringify(cart));
        } catch (e) {}
    }

    function addToCart(id, name, price) {
        cart[id] = (cart[id] || 0) + 1;
        saveCart();
        updateUI(id);
    }
    window.addToCart = addToCart;

    function changeQty(id, delta) {
        if (!cart[id]) return;
        cart[id] += delta;
        if (cart[id] <= 0) {
            delete cart[id];
        }
        saveCart();
        updateUI(id);
    }

    function updateUI(id) {
        const qty = cart[id] || 0;
        const actionContainer = document.getElementById(`action-${id}`);

        if (actionContainer) {
            if (qty > 0) {
                actionContainer.innerHTML = `
                    <div class="qty-stepper">
                        <button type="button" onclick="changeQty(${id}, -1)">-</button>
                        <span>${qty}</span>
                        <button type="button" onclick="changeQty(${id}, 1)">+</button>
                    </div>
                `;
            } else {
                const isDrink = actionContainer.classList.contains('drink-action');
                actionContainer.innerHTML = `
                    <div class="${isDrink ? 'drink-qty-btn-box' : 'qty-btn-box'}" onclick="addToCart(${id})">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                `;
            }
        }

        renderCartBar();
    }

    function renderCartBar() {
        let totalCount = 0;
        let totalPrice = 0;

        for (const [id, qty] of Object.entries(cart)) {
            if (qty > 0 && menuPrices[id]) {
                totalCount += qty;
                totalPrice += qty * menuPrices[id];
            }
        }

        const stickyCart = document.getElementById('stickyCart');
        const cartTotalDisplay = document.getElementById('cartTotalDisplay');
        const cartItemsCount = document.getElementById('cartItemsCount');
        const cartPayload = document.getElementById('cartPayload');

        if (totalCount > 0) {
            stickyCart.style.display = 'flex';
            cartTotalDisplay.innerText = formatRupiah(totalPrice);
            cartItemsCount.innerText = `${totalCount} Item dipilih (Meja #{{ $tableNumber }})`;
            cartPayload.value = JSON.stringify(cart);
        } else {
            stickyCart.style.display = 'none';
            cartPayload.value = '{}';
        }
    }

    function prepareCheckout(e) {
        let count = 0;
        for (const [id, qty] of Object.entries(cart)) {
            if (qty > 0) count += qty;
        }

        if (count === 0) {
            if (e) e.preventDefault();
            alert('Silakan pilih minimal 1 menu terlebih dahulu.');
            return false;
        }

        saveCart();
        const payloadInput = document.getElementById('cartPayload');
        if (payloadInput) {
            payloadInput.value = JSON.stringify(cart);
        }
        return true;
    }

    // Restore cart on page load (from localStorage or PHP session)
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const savedCart = localStorage.getItem('beba_cart');
            if (savedCart) {
                const parsed = JSON.parse(savedCart);
                if (parsed && typeof parsed === 'object' && Object.keys(parsed).length > 0) {
                    cart = parsed;
                }
            }
            
            if (Object.keys(cart).length === 0) {
                const sessionCart = @json(session('cart', []));
                if (sessionCart && typeof sessionCart === 'object' && Object.keys(sessionCart).length > 0) {
                    cart = sessionCart;
                    saveCart();
                }
            }
        } catch (e) {
            cart = {};
        }

        // Update all UI elements for items currently in cart
        for (const [id, qty] of Object.entries(cart)) {
            if (qty > 0) {
                updateUI(id);
            }
        }

        renderCartBar();
    });
</script>
@endsection
