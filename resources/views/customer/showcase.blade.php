@extends('layouts.app')

@section('title', 'Katalog Menu - Depot Sate & Bebalung Be Ba Lung')

@section('styles')
<style>
    /* Hero Header */
    .hero-header {
        position: relative;
        width: 100%;
        height: 240px;
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800&auto=format&fit=crop') center/cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-bottom: 4px solid var(--dark-border);
        padding-top: 10px;
    }

    .hero-logo-badge {
        width: 110px;
        height: 110px;
        background: white;
        border-radius: 50%;
        border: 3.5px solid var(--dark-border);
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

    /* Location & Dine-In Notice Card */
    .showcase-notice-card {
        margin: 14px 16px 8px 16px;
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: 4px 4px 0px var(--dark-border);
        padding: 16px;
        text-align: center;
    }

    .notice-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EA580C;
        color: #FFFFFF;
        font-size: 0.72rem;
        font-weight: 900;
        padding: 3px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        border: 2px solid var(--dark-border);
        margin-bottom: 8px;
    }

    .notice-title {
        font-size: 1rem;
        font-weight: 900;
        color: #111827;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .notice-desc {
        font-size: 0.80rem;
        color: #4B5563;
        font-weight: 600;
        line-height: 1.45;
        margin-bottom: 12px;
    }

    .btn-maps-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary-yellow);
        color: #111827;
        font-size: 0.88rem;
        font-weight: 900;
        padding: 10px 18px;
        border-radius: 12px;
        border: 2.5px solid var(--dark-border);
        box-shadow: 3px 3px 0px var(--dark-border);
        text-decoration: none;
        transition: transform 0.1s, box-shadow 0.1s;
    }

    .btn-maps-cta:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px var(--dark-border);
    }

    /* Special Promo / Aqiqoh Card */
    .promo-banner-card {
        margin: 14px 16px;
        background: #FFFFFF;
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        box-shadow: 4px 4px 0px var(--dark-border);
        padding: 14px;
        overflow: hidden;
    }

    .promo-header {
        margin-bottom: 10px;
    }

    .promo-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #059669;
        color: #FFFFFF;
        font-size: 0.68rem;
        font-weight: 900;
        padding: 2px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        border: 1.5px solid var(--dark-border);
        margin-bottom: 6px;
    }

    .promo-title {
        font-size: 1.05rem;
        font-weight: 900;
        color: #111827;
        margin: 0 0 2px 0;
        line-height: 1.25;
    }

    .promo-subtitle {
        font-size: 0.76rem;
        color: #4B5563;
        font-weight: 700;
        margin: 0;
    }

    .promo-image-wrapper {
        width: 100%;
        border-radius: 12px;
        border: 2px solid var(--dark-border);
        overflow: hidden;
        background: #F3F4F6;
        box-shadow: 2px 2px 0px var(--dark-border);
        margin-bottom: 10px;
        cursor: pointer;
    }

    .promo-img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    /* Elegant Inline Divider Notice (1-Line Sejajar) */
    .promo-notice-inline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 6px 0 10px 0;
        width: 100%;
    }

    .promo-notice-line {
        flex: 1;
        height: 1.5px;
        background: #E2E8F0;
        border-radius: 2px;
    }

    .promo-notice-inline-text {
        font-size: 0.67rem;
        color: #64748B;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        letter-spacing: -0.1px;
    }

    .promo-notice-inline-text i {
        color: #F59E0B;
        font-size: 0.7rem;
    }

    .btn-wa-promo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #22C55E;
        color: #FFFFFF;
        font-size: 0.82rem;
        font-weight: 900;
        padding: 9px 14px;
        border-radius: 10px;
        border: 2px solid var(--dark-border);
        box-shadow: 2px 2px 0px var(--dark-border);
        text-decoration: none;
        transition: transform 0.1s, box-shadow 0.1s;
    }

    .btn-wa-promo:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark-border);
    }

    /* Lightbox Modal for Brochure Image */
    .brochure-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: fadeIn 0.2s ease;
    }

    .brochure-modal-content {
        position: relative;
        max-width: 95vw;
        max-height: 90vh;
        background: #FFFFFF;
        border: 3px solid #111827;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .brochure-modal-img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        object-fit: contain;
    }

    .brochure-modal-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        background: #111827;
        color: #FFFFFF;
        border: 2px solid #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Sticky Top Menu Navigation (Search & Categories) */
    .sticky-menu-nav {
        position: sticky;
        top: 0;
        z-index: 90;
        background: #F3F4F6;
        border-bottom: 3px solid var(--dark-border);
        padding: 10px 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .menu-search-container {
        margin-bottom: 8px;
    }

    .menu-search-box {
        position: relative;
        display: flex;
        align-items: center;
        background: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 12px;
        box-shadow: 2px 2px 0px var(--dark-border);
        padding: 0 12px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .menu-search-box:focus-within {
        border-color: #EA580C;
        box-shadow: 3px 3px 0px #EA580C;
    }

    .menu-search-box .search-icon {
        color: #EA580C;
        font-size: 0.95rem;
        margin-right: 8px;
    }

    .menu-search-box input {
        width: 100%;
        border: none;
        outline: none;
        padding: 8px 0;
        font-size: 0.85rem;
        font-weight: 700;
        color: #111827;
        background: transparent;
    }

    .menu-search-box input::placeholder {
        color: #9CA3AF;
        font-weight: 600;
    }

    .clear-search-btn {
        background: none;
        border: none;
        color: #9CA3AF;
        font-size: 1rem;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }

    .clear-search-btn:hover {
        color: #EF4444;
    }

    .category-tabs-wrapper {
        position: relative;
        width: 100%;
    }

    .category-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .category-tabs::-webkit-scrollbar {
        display: none;
    }

    .category-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #FFFFFF;
        color: #1F2937;
        border: 2px solid var(--dark-border);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 800;
        white-space: nowrap;
        cursor: pointer;
        box-shadow: 2px 2px 0px var(--dark-border);
        transition: all 0.15s ease;
        flex-shrink: 0;
    }

    .category-tab-btn:hover {
        background: #FEF3C7;
        transform: translateY(-1px);
    }

    .category-tab-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark-border);
    }

    .category-tab-btn.active {
        background: var(--primary-yellow);
        color: #111827;
        border-color: var(--dark-border);
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    .category-tab-btn.active i {
        color: #EA580C;
    }

    .category-section {
        scroll-margin-top: 140px;
    }

    .no-search-results {
        display: none;
        text-align: center;
        padding: 35px 20px;
        background: #FFFFFF;
        border: 2.5px dashed #9CA3AF;
        border-radius: 16px;
        margin: 10px 0 20px 0;
    }

    .no-search-results i {
        font-size: 2.5rem;
        color: #EA580C;
        margin-bottom: 8px;
    }

    .no-search-results h4 {
        font-size: 1rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 4px;
    }

    .no-search-results p {
        font-size: 0.78rem;
        color: #6B7280;
        font-weight: 600;
    }

    /* Menu Catalog Content */
    .menu-content {
        padding: 14px 16px 40px 16px;
    }

    .category-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        font-size: 1rem;
        font-weight: 900;
        color: var(--text-dark);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .category-icon-box {
        width: 34px;
        height: 34px;
        background: #F87171;
        border: 2.5px solid var(--dark-border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    .category-icon-box.drink {
        background: #60A5FA;
    }

    /* 2-Column Grid */
    .drinks-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .drink-card {
        background-color: var(--card-yellow);
        border: 3px solid var(--dark-border);
        border-radius: 16px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        box-shadow: var(--box-shadow-brutal);
        position: relative;
    }

    .drink-thumb {
        width: 100%;
        height: 100px;
        background-color: #FFFFFF;
        border: 2.5px solid var(--dark-border);
        border-radius: 10px;
        margin-bottom: 8px;
        overflow: hidden;
        position: relative;
    }

    .drink-card h3 {
        font-size: 0.88rem;
        font-weight: 900;
        color: var(--text-dark);
        line-height: 1.2;
        margin-bottom: 4px;
        text-align: center;
    }

    .drink-card p {
        font-size: 0.70rem;
        color: #374151;
        font-weight: 700;
        line-height: 1.35;
        text-align: center;
    }

    .drink-badge-pill {
        position: absolute;
        top: 6px;
        left: 6px;
        background: #EA580C;
        color: #FFFFFF;
        font-size: 0.55rem;
        font-weight: 900;
        padding: 2px 6px;
        border-radius: 6px;
        border: 1px solid var(--dark-border);
        text-transform: uppercase;
        z-index: 2;
    }

    /* Spotlight Banner Promo: Paket Hemat Rp 22.000 */
    .paket-hemat-card {
        margin: 14px 14px 18px 14px;
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        border: 3px solid var(--dark-border);
        border-radius: 18px;
        padding: 14px 14px 16px 14px;
        box-shadow: 4px 4px 0px var(--dark-border);
        position: relative;
        overflow: hidden;
    }

    .paket-hemat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #DC2626;
        color: #FFFFFF;
        font-size: 0.70rem;
        font-weight: 900;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1.5px solid var(--dark-border);
        box-shadow: 1.5px 1.5px 0px var(--dark-border);
        margin-bottom: 8px;
    }

    .paket-hemat-title {
        font-size: 1.15rem;
        font-weight: 900;
        color: #111827;
        margin: 0 0 4px 0;
        line-height: 1.25;
    }

    .paket-hemat-subtitle {
        font-size: 0.78rem;
        color: #78350F;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .paket-hemat-grid {
        display: grid;
        grid-template-columns: 100px 1fr;
        gap: 12px;
        align-items: center;
        margin-bottom: 12px;
    }

    .paket-hemat-thumb {
        width: 100px;
        height: 90px;
        border-radius: 12px;
        border: 2px solid var(--dark-border);
        overflow: hidden;
        background: #FFFFFF;
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    .paket-hemat-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .paket-hemat-items {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .paket-item-pill {
        font-size: 0.73rem;
        font-weight: 800;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .paket-item-pill i {
        color: #EA580C;
        font-size: 0.75rem;
    }

    .paket-hemat-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        border: 2px solid var(--dark-border);
        border-radius: 12px;
        padding: 8px 12px;
        box-shadow: 2px 2px 0px var(--dark-border);
    }

    .paket-hemat-price-box .old-price {
        font-size: 0.70rem;
        color: #9CA3AF;
        text-decoration: line-through;
        font-weight: 700;
    }

    .paket-hemat-price-box .current-price {
        font-size: 1.15rem;
        font-weight: 900;
        color: #DC2626;
        line-height: 1;
    }

    /* Aqiqah Catalog Modal & Dynamic Configurator */
    .aqiqah-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(6px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 14px;
        animation: fadeIn 0.2s ease;
    }

    .aqiqah-modal-card {
        background: #FFFFFF;
        border: 3.5px solid var(--dark-border);
        border-radius: 22px;
        width: 100%;
        max-width: 500px;
        max-height: 92vh;
        overflow-y: auto;
        padding: 16px;
        box-shadow: 8px 8px 0px var(--dark-border);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .aqiqah-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        border-bottom: 2.5px solid var(--dark-border);
        padding-bottom: 10px;
    }

    /* Top Package Switcher Pills */
    .pkg-selector-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-bottom: 14px;
    }

    .pkg-selector-btn {
        background: #F3F4F6;
        border: 2.5px solid #D1D5DB;
        border-radius: 12px;
        padding: 8px 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.18s ease;
        text-align: left;
        position: relative;
    }

    .pkg-selector-btn:hover {
        border-color: #EA580C;
        background: #FFF7ED;
    }

    .pkg-selector-btn.active {
        background: #FBBF24;
        border-color: var(--dark-border);
        box-shadow: 2.5px 2.5px 0px var(--dark-border);
        transform: translateY(-1px);
    }

    .pkg-selector-btn .pkg-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #FFFFFF;
        border: 1.5px solid var(--dark-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .pkg-selector-btn.active .pkg-btn-icon {
        background: #111827;
        color: #FCD34D;
    }

    .pkg-selector-btn .pkg-btn-text {
        display: flex;
        flex-direction: column;
    }

    .pkg-selector-btn .pkg-btn-title {
        font-size: 0.80rem;
        font-weight: 900;
        color: #111827;
        line-height: 1.1;
    }

    .pkg-selector-btn .pkg-btn-sub {
        font-size: 0.65rem;
        font-weight: 700;
        color: #6B7280;
        margin-top: 2px;
    }

    .pkg-selector-btn.active .pkg-btn-sub {
        color: #78350F;
    }

    /* Selected Package Display Wrapper */
    .pkg-details-wrapper {
        animation: aqiqahFadeSlide 0.25s ease-out;
    }

    @keyframes aqiqahFadeSlide {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Package Hero Header Banner */
    .pkg-hero-banner {
        position: relative;
        background: #111827;
        border: 2.5px solid var(--dark-border);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 12px;
        box-shadow: 3px 3px 0px var(--dark-border);
    }

    .pkg-hero-banner img {
        width: 100%;
        height: 130px;
        object-fit: cover;
        opacity: 0.85;
    }

    .pkg-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(17, 24, 39, 0.95), rgba(17, 24, 39, 0.4), transparent);
        padding: 10px 12px 8px 12px;
        color: #FFFFFF;
    }

    .pkg-hero-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #EA580C;
        color: #FFFFFF;
        font-size: 0.65rem;
        font-weight: 900;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1.5px solid var(--dark-border);
        box-shadow: 2px 2px 0px var(--dark-border);
        text-transform: uppercase;
    }

    .pkg-hero-title {
        font-size: 1rem;
        font-weight: 900;
        color: #FCD34D;
        margin: 0;
        line-height: 1.2;
    }

    .pkg-hero-desc {
        font-size: 0.68rem;
        color: #E5E7EB;
        font-weight: 600;
        margin: 2px 0 0 0;
        line-height: 1.2;
    }

    /* Section Subheadings */
    .config-sec-title {
        font-size: 0.78rem;
        font-weight: 900;
        color: #111827;
        text-transform: uppercase;
        margin: 10px 0 6px 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .config-sec-badge {
        background: #EA580C;
        color: #FFFFFF;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.68rem;
        font-weight: 900;
        flex-shrink: 0;
    }

    /* Item Barang / Isi Paket Grid */
    .pkg-items-box {
        background: #F9FAFB;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 8px 10px;
        margin-bottom: 10px;
    }

    .pkg-items-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px 8px;
    }

    .pkg-item-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.68rem;
        font-weight: 800;
        color: #1F2937;
    }

    .pkg-item-pill i {
        color: #EA580C;
        font-size: 0.72rem;
        width: 14px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Animal & Method Selector Cards */
    .option-cards-grid {
        display: grid;
        gap: 7px;
        margin-bottom: 8px;
    }

    .option-card-single {
        border: 2px solid #D1D5DB;
        border-radius: 12px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        background: #F9FAFB;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .option-card-single:hover {
        border-color: #EA580C;
        background: #FFF7ED;
    }

    .option-card-single.selected {
        border-color: #EA580C;
        background: #FFF7ED;
        box-shadow: 2px 2px 0px #EA580C;
    }

    .option-card-single.selected::after {
        content: '✓';
        position: absolute;
        top: 6px;
        right: 6px;
        background: #EA580C;
        color: #FFFFFF;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        font-size: 0.60rem;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .option-card-single .opt-img {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        overflow: hidden;
        border: 1.5px solid #111827;
        flex-shrink: 0;
    }

    .option-card-single .opt-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .option-card-single .opt-info {
        flex: 1;
        min-width: 0;
    }

    .option-card-single .opt-title {
        font-size: 0.78rem;
        font-weight: 900;
        color: #111827;
        line-height: 1.15;
    }

    .option-card-single .opt-desc {
        font-size: 0.65rem;
        color: #6B7280;
        font-weight: 600;
        line-height: 1.2;
        margin-top: 1px;
    }

    .option-card-single .opt-price {
        font-size: 0.78rem;
        font-weight: 900;
        color: #DC2626;
        text-align: right;
        flex-shrink: 0;
        padding-right: 14px;
    }

    /* Live Summary Box */
    .aqiqah-summary-box {
        background: #FEF3C7;
        border: 2.5px solid var(--dark-border);
        border-radius: 14px;
        padding: 10px 12px;
        margin: 10px 0 8px 0;
        font-size: 0.73rem;
        color: #92400E;
        line-height: 1.4;
        box-shadow: 2.5px 2.5px 0px var(--dark-border);
    }

    .btn-wa-aqiqah-submit {
        width: 100%;
        background: #22C55E;
        color: #FFFFFF;
        font-size: 0.88rem;
        font-weight: 900;
        padding: 11px 14px;
        border-radius: 12px;
        border: 2.5px solid var(--dark-border);
        box-shadow: 3px 3px 0px var(--dark-border);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: transform 0.1s;
    }

    .btn-wa-aqiqah-submit:active {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark-border);
    }

    /* Footer */
    .showcase-footer {
        text-align: center;
        padding: 20px 16px 40px 16px;
        border-top: 2px dashed #9CA3AF;
        margin-top: 10px;
        color: #4B5563;
        font-size: 0.75rem;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<!-- Hero Header -->
<div class="hero-header">
    <div class="hero-logo-badge">
        <img src="{{ asset('images/logo-goat.png') }}" alt="Logo Be Ba Lung">
    </div>
    <div style="text-align: center; margin-top: 2px;">
        <h2 style="font-size: 1.15rem; color: #FFFFFF; font-weight: 900; letter-spacing: 0.5px; text-shadow: 1px 1px 3px #000; margin: 0;">DEPOT Sate</h2>
        <p style="font-size: 0.78rem; color: #FBBF24; font-weight: 800; text-shadow: 1px 1px 3px #000; margin: 2px 0 0 0;">Sop &amp; Gulai Kambing</p>
        <p style="font-size: 0.95rem; color: #F97316; font-weight: 900; letter-spacing: 1px; text-shadow: 1px 1px 3px #000; margin: 2px 0 0 0;">"BE BA LUNG"</p>
    </div>
</div>

@if(!empty($scanWarning) || session('warning') || session('error'))
<div style="margin: 14px 16px -4px 16px; background: #FEF2F2; border: 2.5px solid #DC2626; border-radius: 14px; padding: 12px 14px; box-shadow: 3px 3px 0px #991B1B; display: flex; align-items: center; gap: 10px;">
    <div style="width: 32px; height: 32px; background: #FEE2E2; border: 2px solid #DC2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #DC2626; font-size: 1rem; flex-shrink: 0;">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <div style="flex: 1; font-size: 0.78rem; font-weight: 800; color: #991B1B; line-height: 1.35;">
        {{ $scanWarning ?? session('warning') ?? session('error') }}
    </div>
</div>
@endif

<!-- Informasi Cara Pesan (Duduk & Scan di Meja) -->
<div class="showcase-notice-card">
    <div class="notice-pill">
        <i class="fa-solid fa-location-dot"></i> Info Lokasi &amp; Pemesanan
    </div>
    <h3 class="notice-title">Ingin Menikmati Hidangan Kami?</h3>
    <p class="notice-desc">
        Untuk memesan hidangan di tempat, silakan datang langsung ke <strong>Depot Sate Be Ba Lung</strong>. Duduk di meja pilihan Anda dan <strong>scan QR Code di meja</strong> untuk mulai memilih menu &amp; memesan secara otomatis.
    </p>
    <a href="https://maps.google.com/?q=DEPOT+Sate,+Sop+%26+Gulai+Kambing+%22BE+BA+LUNG%22" target="_blank" class="btn-maps-cta">
        <i class="fa-solid fa-map-location-dot" style="color: #EA580C;"></i>
        <span>Buka Lokasi di Google Maps</span>
    </a>
</div>

<!-- Sticky Top Nav: Search & Category Navigation -->
<div class="sticky-menu-nav" id="stickyMenuNav">
    <!-- Search Bar -->
    <div class="menu-search-container">
        <div class="menu-search-box">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="menuSearchInput" placeholder="Cari sate, gulai, tongseng, es teh..." autocomplete="off" />
            <button type="button" id="clearSearchBtn" class="clear-search-btn" onclick="clearSearch()" style="display: none;" title="Hapus pencarian">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Category Pills Tabs -->
    <div class="category-tabs-wrapper">
        <div class="category-tabs" id="categoryTabs">
            <button type="button" class="category-tab-btn active" data-category="all" onclick="scrollToCategory('all')">
                <i class="fa-solid fa-border-all"></i>
                <span>Semua</span>
            </button>
            @foreach($categories->where('slug', '!=', 'paket') as $category)
                <button type="button" class="category-tab-btn" data-category="{{ $category->slug }}" onclick="scrollToCategory('{{ $category->slug }}')">
                    @if($category->slug === 'minuman')
                        <i class="fa-solid fa-mug-hot"></i>
                    @elseif($category->slug === 'paket')
                        <i class="fa-solid fa-box-open"></i>
                    @else
                        <i class="fa-solid fa-utensils"></i>
                    @endif
                    <span>{{ strtoupper(trim(str_ireplace('menu ', '', $category->name))) }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Menu Catalog Content -->
<div class="menu-content">
    <!-- Empty Search State -->
    <div class="no-search-results" id="noSearchResults">
        <i class="fa-solid fa-utensils"></i>
        <h4>Menu Tidak Ditemukan</h4>
        <p>Maaf, kami tidak menemukan hidangan dengan kata kunci tersebut. Coba kata kunci lainnya.</p>
    </div>

    @foreach($categories->where('slug', '!=', 'paket') as $category)
        <div class="category-section" id="category-{{ $category->slug }}" style="margin-bottom: 24px;">
            <div class="category-badge">
                <div class="category-icon-box {{ $category->slug === 'minuman' ? 'drink' : ($category->slug === 'paket' ? 'paket' : '') }}">
                    @if($category->slug === 'minuman')
                        <i class="fa-solid fa-mug-hot"></i>
                    @elseif($category->slug === 'paket')
                        <i class="fa-solid fa-box-open"></i>
                    @else
                        <i class="fa-solid fa-utensils"></i>
                    @endif
                </div>
                <span>{{ strtoupper(trim(str_ireplace('menu ', '', $category->name))) }}</span>
            </div>

            <div class="drinks-grid">
                @foreach($category->menus as $menu)
                    <div class="drink-card" data-name="{{ strtolower($menu->name) }}">
                        <div class="drink-thumb">
                            @if($menu->badge)
                                <div class="drink-badge-pill">
                                    {{ $menu->badge }}
                                </div>
                            @endif
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3>{{ $menu->name }}</h3>
                        @if($menu->description)
                            <p>{{ $menu->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <!-- 1. SPOTLIGHT PROMO: Paket Hemat Rp 22.000 (Diletakkan Tepat di Atas Banner Aqiqah) -->
    <div class="paket-hemat-card" style="margin-top: 24px; margin-bottom: 12px;">
        <div class="paket-hemat-badge">
            <i class="fa-solid fa-fire"></i> PROMO SPESIAL
        </div>
        <h3 class="paket-hemat-title">Paket Hemat Komplit</h3>
        <p class="paket-hemat-subtitle">Porsi Kenyang Nikmat &bull; Hemat Maksimal</p>

        <div class="paket-hemat-grid">
            <div class="paket-hemat-thumb">
                <img src="{{ asset('images/menus/paket_murah.jpg') }}" alt="Paket Hemat 22rb" onerror="this.src='{{ asset('images/menus/tongseng_kambing.jpg') }}'">
            </div>
            <div class="paket-hemat-items">
                <div class="paket-item-pill"><i class="fa-solid fa-bowl-rice"></i> <span>Nasi Putih Pulen</span></div>
                <div class="paket-item-pill"><i class="fa-solid fa-utensils"></i> <span>Tongseng Kambing Gurih</span></div>
                <div class="paket-item-pill"><i class="fa-solid fa-drumstick-bite"></i> <span>5 Tusuk Sate Kambing</span></div>
                <div class="paket-item-pill"><i class="fa-solid fa-glass-water"></i> <span>Es Teh Manis Segar</span></div>
            </div>
        </div>

        <div class="paket-hemat-bottom">
            <div class="paket-hemat-price-box">
                <div class="old-price">Rp 35.000</div>
                <div class="current-price">Rp 22.000</div>
            </div>
            <div style="font-size: 0.78rem; font-weight: 800; color: #78350F; background: #FEF3C7; padding: 6px 12px; border-radius: 8px; border: 1.5px solid #F59E0B;">
                <i class="fa-solid fa-tag" style="color: #EA580C;"></i> Paket Lengkap
            </div>
        </div>
    </div>

    <!-- 2. Banner Layanan Khusus: Paket Aqiqoh & Sodaqoh (Di Bagian Bawah Menu) -->
    <div class="promo-banner-card" style="margin-top: 10px; margin-bottom: 24px;">
        <div class="promo-header">
            <span class="promo-tag"><i class="fa-solid fa-gift"></i> Layanan Khusus</span>
            <h3 class="promo-title">Paket Aqiqoh &amp; Paket Sodaqoh</h3>
            <p class="promo-subtitle">Melayani Aqiqoh, Syukuran, Sedekah Jum'at Berkah &amp; Catering</p>
        </div>

        <!-- Image with Click to Zoom -->
        <div class="promo-image-wrapper" onclick="openBrochureModal('{{ asset('images/promo_aqiqoh_sodaqoh.jpg') }}')">
            <img src="{{ asset('images/promo_aqiqoh_sodaqoh.jpg') }}" alt="Paket Aqiqoh & Sodaqoh Depot Be Ba Lung" class="promo-img" loading="lazy">
        </div>

        <!-- Highlights Pills -->
        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin: 10px 0 6px 0; justify-content: center;">
            <span style="font-size: 0.72rem; font-weight: 800; color: #1E3A8A; background: #DBEAFE; padding: 4px 10px; border-radius: 8px; border: 1px solid #93C5FD;">🐐 1. Paket A (2 Ekor) 120 Porsi Rp 4,4 JT</span>
            <span style="font-size: 0.72rem; font-weight: 800; color: #78350F; background: #FEF3C7; padding: 4px 10px; border-radius: 8px; border: 1px solid #FCD34D;">🐐 2. Paket B (1 Ekor) 60 Porsi Rp 2,3 JT</span>
            <span style="font-size: 0.72rem; font-weight: 800; color: #065F46; background: #D1FAE5; padding: 4px 10px; border-radius: 8px; border: 1px solid #6EE7B7;">💚 Paket Sodaqoh Rp 12.000/Paket</span>
        </div>

        <!-- Inline Notice -->
        <div class="promo-notice-inline">
            <span class="promo-notice-line"></span>
            <span class="promo-notice-inline-text">
                <i class="fa-solid fa-circle-info"></i>
                *Depot Sate, Sop, Gulai &amp; Tongseng Kambing (Jl. Supriyadi No.40, Sokayasa, Purwokerto Wetan, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53146)
            </span>
            <span class="promo-notice-line"></span>
        </div>

        <!-- Action Buttons: Open Food List & Direct WA -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <button type="button" class="btn-wa-promo" onclick="openAqiqahModal('cards')" style="background: #111827; color: #FCD34D; box-shadow: 3px 3px 0px #F59E0B;">
                <i class="fa-solid fa-utensils" style="color: #FCD34D; font-size: 1.1rem;"></i>
                <span>📋 Buka Detail Paket &amp; Pilihan Masakan</span>
            </button>

            <a href="https://wa.me/6287730712015?text=Halo%20Depot%20Sate%20Bebalung,%20saya%20ingin%20tanya%20informasi%20Paket%20Aqiqoh%20/%20Sodaqoh" target="_blank" class="btn-wa-promo">
                <i class="fa-brands fa-whatsapp"></i>
                <span>Konsultasi Cepat via WhatsApp (0877 3071 2015)</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal Interaktif: Daftar Menu & Konfigurator Paket Aqiqah / Sodaqoh -->
<div id="aqiqahModal" class="aqiqah-modal-overlay" onclick="closeAqiqahModal(event)">
    <div class="aqiqah-modal-card" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="aqiqah-modal-header">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-gift" style="color: #EA580C;"></i> Paket Aqiqoh &amp; Sodaqoh
                </h3>
                <p style="font-size: 0.73rem; color: #6B7280; margin: 2px 0 0 0; font-weight: 700;">Rincian resmi sesuai paket Depot Sate, Sop, Gulai &amp; Tongseng Kambing</p>
            </div>
            <button type="button" class="brochure-modal-close" style="position: static;" onclick="closeAqiqahModal()" title="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- 1. Pilihan Tab / Paket di Atas -->
        <div class="pkg-selector-grid">
            <button type="button" id="btnPkgA" class="pkg-selector-btn active" onclick="switchPackage('paket_a')">
                <div class="pkg-btn-icon"><i class="fa-solid fa-crown" style="color: #EA580C;"></i></div>
                <div class="pkg-btn-text">
                    <span class="pkg-btn-title">1. Paket A</span>
                    <span class="pkg-btn-sub">2 Ekor (120 Porsi) &bull; Rp 4,4 JT</span>
                </div>
            </button>
            <button type="button" id="btnPkgB" class="pkg-selector-btn" onclick="switchPackage('paket_b')">
                <div class="pkg-btn-icon"><i class="fa-solid fa-fire" style="color: #D97706;"></i></div>
                <div class="pkg-btn-text">
                    <span class="pkg-btn-title">2. Paket B</span>
                    <span class="pkg-btn-sub">1 Ekor (60 Porsi) &bull; Rp 2,3 JT</span>
                </div>
            </button>
            <button type="button" id="btnPkgSodaqoh" class="pkg-selector-btn" onclick="switchPackage('sodaqoh')">
                <div class="pkg-btn-icon"><i class="fa-solid fa-hand-holding-heart" style="color: #059669;"></i></div>
                <div class="pkg-btn-text">
                    <span class="pkg-btn-title">Paket Sodaqoh</span>
                    <span class="pkg-btn-sub">Rp 12.000 / Paket</span>
                </div>
            </button>
        </div>

        <!-- 2. Dynamic Container for Selected Package Details -->
        <div id="pkgDetailsContainer" class="pkg-details-wrapper">
            <!-- Will be dynamically populated by renderPackage() -->
        </div>

    </div>
</div>

<!-- Footer -->
<div class="showcase-footer">
    <strong style="color: #111827; font-size: 0.85rem;">DEPOT BE BA LUNG</strong>
    <p style="margin-top: 4px;">Spesialis Sate Kambing Muda, Tongseng, Gulai &amp; Sop Bebalung.</p>
    <p style="margin-top: 8px; font-size: 0.70rem; color: #6B7280;">&copy; {{ date('Y') }} Depot Be Ba Lung. All rights reserved.</p>
</div>
@endsection

@section('scripts')
<script>
    // Smooth Auto-Scroll to Category
    function scrollToCategory(slug) {
        document.querySelectorAll('.category-tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === slug);
        });

        if (slug === 'all') {
            const menuContent = document.querySelector('.menu-content');
            if (menuContent) {
                const headerOffset = 135;
                const elementPosition = menuContent.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({ top: Math.max(0, offsetPosition), behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            return;
        }

        const targetSection = document.getElementById(`category-${slug}`);
        if (targetSection) {
            const headerOffset = 135;
            const elementPosition = targetSection.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            window.scrollTo({
                top: Math.max(0, offsetPosition),
                behavior: 'smooth'
            });
        }
    }

    // Real-Time Search Filter
    const searchInput = document.getElementById('menuSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const noResultsDiv = document.getElementById('noSearchResults');

    function filterMenu() {
        const query = searchInput.value.toLowerCase().trim();
        if (clearBtn) {
            clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        }

        let totalVisibleCards = 0;
        const categorySections = document.querySelectorAll('.category-section');

        categorySections.forEach(section => {
            let sectionVisibleCards = 0;
            const cards = section.querySelectorAll('.drink-card');

            cards.forEach(card => {
                const name = (card.getAttribute('data-name') || card.querySelector('h3')?.innerText || '').toLowerCase();
                const desc = (card.querySelector('p')?.innerText || '').toLowerCase();

                if (!query || name.includes(query) || desc.includes(query)) {
                    card.style.display = 'flex';
                    sectionVisibleCards++;
                    totalVisibleCards++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (query && sectionVisibleCards === 0) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });

        if (noResultsDiv) {
            noResultsDiv.style.display = (totalVisibleCards === 0 && query.length > 0) ? 'block' : 'none';
        }
    }

    function clearSearch() {
        if (searchInput) {
            searchInput.value = '';
            filterMenu();
            searchInput.focus();
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterMenu);
    }

    // Scroll-Spy to automatically highlight active category tab on scroll
    window.addEventListener('scroll', function() {
        if (searchInput && searchInput.value.trim().length > 0) return;

        const sections = document.querySelectorAll('.category-section');
        const scrollPos = window.pageYOffset + 160;
        let currentSlug = 'all';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const slug = section.id.replace('category-', '');

            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                currentSlug = slug;
            }
        });

        const firstSection = sections[0];
        if (firstSection && window.pageYOffset < firstSection.offsetTop - 160) {
            currentSlug = 'all';
        }

        document.querySelectorAll('.category-tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === currentSlug);
        });
    }, { passive: true });

    // Lightbox Modal Functions
    function openBrochureModal(imgUrl) {
        const modal = document.getElementById('brochureModal');
        const modalImg = document.getElementById('brochureModalImg');
        if (modal && modalImg) {
            modalImg.src = imgUrl;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeBrochureModal(e) {
        const modal = document.getElementById('brochureModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Aqiqah Modal & Tab Switcher
    function switchAqiqahTab(tabName) {
        const tabCards = document.getElementById('aqiqahTabCards');
        const tabCalc = document.getElementById('aqiqahTabCalc');
        const btnCards = document.getElementById('tabBtnCards');
        const btnCalc = document.getElementById('tabBtnCalc');

        if (tabName === 'cards') {
            if (tabCards) tabCards.style.display = 'block';
            if (tabCalc) tabCalc.style.display = 'none';
            if (btnCards) btnCards.classList.add('active');
            if (btnCalc) btnCalc.classList.remove('active');
    // Aqiqah & Sodaqoh Data directly matching the official depot brochure
    const PACKAGES_DATA = {
        'paket_a': {
            key: 'paket_a',
            badge: '🐐 2 EKOR KAMBING • 120 PORSI/BOX',
            title: '1. PAKET A (2 EKOR KAMBING)',
            subtitle: '120 Porsi/box • Rp 4,4 JT (Rp 4.400.000)',
            priceText: 'Rp 4.400.000 (Rp 4,4 JT)',
            porsi: '120 Porsi/box',
            image: "{{ asset('images/promo_aqiqoh_sodaqoh.jpg') }}",
            fallbackImage: "{{ asset('images/menus/sate_kambing_polos.jpg') }}",
            items: [
                { icon: 'fa-bowl-rice', text: 'Nasi' },
                { icon: 'fa-drumstick-bite', text: 'Sate 5 Tusuk' },
                { icon: 'fa-bowl-food', text: 'Kari / Sop / Gulai' },
                { icon: 'fa-utensils', text: 'Bihun' },
                { icon: 'fa-carrot', text: 'Sayur Buncis / Kentang' },
                { icon: 'fa-cookie', text: 'Kerupuk & Buah Pisang' }
            ],
            meatOptions: [
                { id: 'gulai', title: 'Gulai Kambing', desc: 'Kuah santan kental rempah gurih khas', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" },
                { id: 'sop', title: 'Sop Kambing', desc: 'Kuah kaldu bening segar rempah jahe', img: "{{ asset('images/menus/sop_kambing.jpg') }}" },
                { id: 'kari', title: 'Kari Kambing', desc: 'Kuah kari kental rempah aroma sedap', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" }
            ],
            veggieOptions: [
                { id: 'buncis', title: 'Sayur Buncis', desc: 'Tumis buncis gurih renyah lezat' },
                { id: 'kentang', title: 'Sayur Kentang', desc: 'Sambal goreng kentang balado' }
            ]
        },
        'paket_b': {
            key: 'paket_b',
            badge: '🐐 1 EKOR KAMBING • 60 PORSI/BOX',
            title: '2. PAKET B (1 EKOR KAMBING)',
            subtitle: '60 Porsi/box • Rp 2,3 JT (Rp 2.300.000)',
            priceText: 'Rp 2.300.000 (Rp 2,3 JT)',
            porsi: '60 Porsi/box',
            image: "{{ asset('images/promo_aqiqoh_sodaqoh.jpg') }}",
            fallbackImage: "{{ asset('images/menus/sate_kambing_campur.jpg') }}",
            items: [
                { icon: 'fa-bowl-rice', text: 'Nasi' },
                { icon: 'fa-drumstick-bite', text: 'Sate 5 Tusuk' },
                { icon: 'fa-bowl-food', text: 'Kari / Sop / Gulai' },
                { icon: 'fa-utensils', text: 'Bihun' },
                { icon: 'fa-carrot', text: 'Sayur Buncis / Kentang' },
                { icon: 'fa-cookie', text: 'Kerupuk & Buah Pisang' }
            ],
            meatOptions: [
                { id: 'gulai', title: 'Gulai Kambing', desc: 'Kuah santan kental rempah gurih khas', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" },
                { id: 'sop', title: 'Sop Kambing', desc: 'Kuah kaldu bening segar rempah jahe', img: "{{ asset('images/menus/sop_kambing.jpg') }}" },
                { id: 'kari', title: 'Kari Kambing', desc: 'Kuah kari kental rempah aroma sedap', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" }
            ],
            veggieOptions: [
                { id: 'buncis', title: 'Sayur Buncis', desc: 'Tumis buncis gurih renyah lezat' },
                { id: 'kentang', title: 'Sayur Kentang', desc: 'Sambal goreng kentang balado' }
            ]
        },
        'sodaqoh': {
            key: 'sodaqoh',
            badge: '💚 UNTUK JUM\'AT BERKAH DLL',
            title: 'PAKET SODAQOH',
            subtitle: 'Untuk Jum\'at Berkah Dll • Rp 12.000 / Paket',
            priceText: 'Rp 12.000 / Paket',
            porsi: 'Paket Nasi Kotak Sedekah',
            image: "{{ asset('images/promo_aqiqoh_sodaqoh.jpg') }}",
            fallbackImage: "{{ asset('images/menus/paket_bento.jpg') }}",
            items: [
                { icon: 'fa-bowl-rice', text: 'Nasi' },
                { icon: 'fa-drumstick-bite', text: 'Sate 3 Tusuk' },
                { icon: 'fa-bowl-food', text: 'Sop / Gulai / Kari Kambing' }
            ],
            meatOptions: [
                { id: 'sop', title: 'Sop Kambing', desc: 'Kuah kaldu bening gurih hangat', img: "{{ asset('images/menus/sop_kambing.jpg') }}" },
                { id: 'gulai', title: 'Gulai Kambing', desc: 'Kuah santan rempah gurih khas', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" },
                { id: 'kari', title: 'Kari Kambing', desc: 'Kuah kari rempah aroma mantap', img: "{{ asset('images/menus/gulai_kambing.jpg') }}" }
            ],
            veggieOptions: []
        }
    };

    let currentSelectedPkg = 'paket_a';
    let currentSelectedMeatIndex = 0;
    let currentSelectedVeggieIndex = 0;

    function openAqiqahModal(pkgKey = 'paket_a') {
        const modal = document.getElementById('aqiqahModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            switchPackage(pkgKey);
        }
    }

    function closeAqiqahModal(e) {
        const modal = document.getElementById('aqiqahModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function switchPackage(pkgKey) {
        currentSelectedPkg = (pkgKey === 'cards' || !PACKAGES_DATA[pkgKey]) ? 'paket_a' : pkgKey;
        currentSelectedMeatIndex = 0;
        currentSelectedVeggieIndex = 0;

        // Update active tab buttons
        document.querySelectorAll('.pkg-selector-btn').forEach(btn => btn.classList.remove('active'));
        if (currentSelectedPkg === 'paket_a') document.getElementById('btnPkgA')?.classList.add('active');
        if (currentSelectedPkg === 'paket_b') document.getElementById('btnPkgB')?.classList.add('active');
        if (currentSelectedPkg === 'sodaqoh') document.getElementById('btnPkgSodaqoh')?.classList.add('active');

        renderPackageDetails();
    }

    function selectMeatOption(index) {
        currentSelectedMeatIndex = index;
        renderPackageDetails();
    }

    function selectVeggieOption(index) {
        currentSelectedVeggieIndex = index;
        renderPackageDetails();
    }

    function renderPackageDetails() {
        const container = document.getElementById('pkgDetailsContainer');
        if (!container) return;

        const pkg = PACKAGES_DATA[currentSelectedPkg];
        if (!pkg) return;

        const meat = pkg.meatOptions[currentSelectedMeatIndex] || pkg.meatOptions[0];
        const veggie = pkg.veggieOptions.length > 0 ? (pkg.veggieOptions[currentSelectedVeggieIndex] || pkg.veggieOptions[0]) : null;

        // Format WA message
        let waMsg = `Halo Depot Sate Bebalung (Purwokerto), saya ingin memesan:\n\n` +
                    `📌 *Paket:* ${pkg.title}\n` +
                    `💰 *Harga:* ${pkg.priceText} (${pkg.porsi})\n` +
                    `🍲 *Pilihan Kuah/Olahan:* ${meat.title}\n`;
        
        if (veggie) {
            waMsg += `🥗 *Pilihan Sayur:* ${veggie.title}\n`;
        }
        
        if (pkg.key === 'sodaqoh') {
            waMsg += `🍱 *Kelengkapan:* Nasi + Sate 3 Tusuk + ${meat.title}\n`;
        } else {
            waMsg += `🍱 *Kelengkapan:* Nasi + Sate (5 Tusuk) + ${meat.title} + Bihun + ${veggie ? veggie.title : 'Sayur'} + Kerupuk & Buah Pisang\n`;
        }

        waMsg += `📍 *Info/Lokasi:* Purwokerto\n\n` +
                 `Mohon info ketersediaan jadwal & reservasi tanggal pesanannya. Terima kasih!`;

        const waUrl = `https://wa.me/6287730712015?text=${encodeURIComponent(waMsg)}`;

        // Build Items HTML
        let itemsHtml = '';
        pkg.items.forEach(item => {
            itemsHtml += `
                <div class="pkg-item-pill">
                    <i class="fa-solid ${item.icon}"></i>
                    <span>${item.text}</span>
                </div>
            `;
        });

        // Build Meat Options HTML
        let meatHtml = '';
        pkg.meatOptions.forEach((m, idx) => {
            const isSelected = idx === currentSelectedMeatIndex;
            meatHtml += `
                <div class="option-card-single ${isSelected ? 'selected' : ''}" onclick="selectMeatOption(${idx})">
                    <div class="opt-img">
                        <img src="${m.img}" alt="${m.title}" onerror="this.src='{{ asset('images/menus/gulai_kambing.jpg') }}'">
                    </div>
                    <div class="opt-info">
                        <div class="opt-title">${m.title}</div>
                        <div class="opt-desc">${m.desc}</div>
                    </div>
                </div>
            `;
        });

        // Build Veggie Options HTML (For Paket A & B)
        let veggieSecHtml = '';
        if (pkg.veggieOptions.length > 0) {
            let veggieCardsHtml = '';
            pkg.veggieOptions.forEach((v, idx) => {
                const isSelected = idx === currentSelectedVeggieIndex;
                veggieCardsHtml += `
                    <div class="option-card-single ${isSelected ? 'selected' : ''}" onclick="selectVeggieOption(${idx})">
                        <div class="opt-info" style="padding-left: 8px;">
                            <div class="opt-title">${v.title}</div>
                            <div class="opt-desc">${v.desc}</div>
                        </div>
                    </div>
                `;
            });

            veggieSecHtml = `
                <div class="config-sec-title">
                    <span class="config-sec-badge">3</span>
                    <span>Pilihan Olahan Sayur (Sayur Buncis / Kentang)</span>
                </div>
                <div class="option-cards-grid">
                    ${veggieCardsHtml}
                </div>
            `;
        }

        container.innerHTML = `
            <!-- Hero Banner -->
            <div class="pkg-hero-banner">
                <span class="pkg-hero-badge">${pkg.badge}</span>
                <img src="${pkg.image}" alt="${pkg.title}" onerror="this.src='${pkg.fallbackImage}'">
                <div class="pkg-hero-overlay">
                    <h4 class="pkg-hero-title">${pkg.title}</h4>
                    <p class="pkg-hero-desc">${pkg.subtitle}</p>
                </div>
            </div>

            <!-- 1. Isi Menu Paket -->
            <div class="config-sec-title">
                <span class="config-sec-badge">1</span>
                <span>Isi Paket (Sesuai Brosur Resmi)</span>
            </div>
            <div class="pkg-items-box">
                <div class="pkg-items-grid">
                    ${itemsHtml}
                </div>
            </div>

            <!-- 2. Pilihan Kuah (Kari / Sop / Gulai) -->
            <div class="config-sec-title">
                <span class="config-sec-badge">2</span>
                <span>Pilihan Olahan Daging (Kari / Sop / Gulai)</span>
            </div>
            <div class="option-cards-grid">
                ${meatHtml}
            </div>

            <!-- 3. Pilihan Sayur (Jika Paket A/B) -->
            ${veggieSecHtml}

            <!-- 4. Ringkasan & Tombol WA -->
            <div class="aqiqah-summary-box">
                <div style="font-weight: 900; font-size: 0.82rem; margin-bottom: 4px; color: #78350F; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-receipt"></i> Ringkasan Paket:</span>
                    <span style="background: #EA580C; color: white; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 900;">${pkg.priceText}</span>
                </div>
                <div style="line-height: 1.5; font-size: 0.75rem;">
                    &bull; <strong>Paket:</strong> <span style="color: #111827; font-weight: 800;">${pkg.title}</span> (${pkg.porsi})<br>
                    &bull; <strong>Pilihan Kuah:</strong> <span style="color: #111827; font-weight: 800;">${meat.title}</span><br>
                    ${veggie ? `&bull; <strong>Pilihan Sayur:</strong> <span style="color: #111827; font-weight: 800;">${veggie.title}</span><br>` : ''}
                    &bull; <strong>Alamat Depot:</strong> Jl. Supriyadi No.40, Sokayasa, Purwokerto Wetan, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53146
                </div>
            </div>

            <a href="${waUrl}" target="_blank" class="btn-wa-aqiqah-submit">
                <i class="fa-brands fa-whatsapp" style="font-size: 1.25rem;"></i>
                <span>Pesan ${pkg.title.split('(')[0].trim()} via WhatsApp</span>
            </a>
        `;
    }
</script>
@endsection

