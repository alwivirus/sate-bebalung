@extends('layouts.admin')

@section('title', 'Kelola Role & Akun Bebalung - Admin Kasir Utama')
@section('page-title', 'Role & Akun Bebalung')

@section('styles')
<style>
    :root {
        --role-admin: #D97706;
        --role-admin-bg: #FEF3C7;
        --role-kasir: #059669;
        --role-kasir-bg: #D1FAE5;
        --role-dev: #6366F1;
        --role-dev-bg: #EEF2FF;
        --role-dapur: #0284C7;
        --role-dapur-bg: #E0F2FE;
    }

    /* Tab Styles */
    .role-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 2px;
        -webkit-overflow-scrolling: touch;
    }

    .role-tab-btn {
        background: none;
        border: none;
        padding: 12px 18px;
        font-size: 0.9rem;
        font-weight: 800;
        color: #6B7280;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s ease;
        border-radius: 8px 8px 0 0;
    }

    .role-tab-btn:hover {
        color: #111827;
        background: rgba(0,0,0,0.02);
    }

    .role-tab-btn.active {
        color: #D97706;
        border-bottom-color: #F59E0B;
        background: white;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
        animation: fadeInTab 0.25s ease;
    }

    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .role-badge-admin {
        background: var(--role-admin-bg);
        color: var(--role-admin);
        border: 1px solid #FCD34D;
    }

    .role-badge-kasir {
        background: var(--role-kasir-bg);
        color: var(--role-kasir);
        border: 1px solid #A7F3D0;
    }

    .role-badge-developer {
        background: var(--role-dev-bg);
        color: var(--role-dev);
        border: 1px solid #C7D2FE;
    }

    .role-badge-dapur {
        background: var(--role-dapur-bg);
        color: var(--role-dapur);
        border: 1px solid #BAE6FD;
    }

    /* Table Styles */
    .user-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .user-table th {
        background: #F9FAFB;
        padding: 12px 16px;
        font-size: 0.78rem;
        font-weight: 800;
        color: #4B5563;
        border-bottom: 2px solid var(--border-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.88rem;
        color: #1F2937;
        vertical-align: middle;
    }

    .user-table tr:hover td {
        background-color: #FFFDF7;
    }

    /* Avatar Pill */
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    /* Modal Backdrop & Dialog */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, 0.6);
        backdrop-filter: blur(3px);
        z-index: 1100;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-backdrop.open {
        display: flex;
        animation: modalFadeIn 0.2s ease-out;
    }

    .modal-box {
        background: white;
        border-radius: 18px;
        border: 2px solid #111827;
        box-shadow: 6px 6px 0px #111827;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 24px;
        position: relative;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Credential Copy Card */
    .cred-card {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 18px;
        transition: all 0.15s ease;
        position: relative;
    }

    .cred-card:hover {
        border-color: #F59E0B;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.12);
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">

    <!-- Flash Messages & Validation -->
    @if(session('success'))
        <div style="background: #D1FAE5; border: 2px solid #10B981; border-radius: 12px; padding: 14px 18px; color: #065F46; margin-bottom: 20px; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem; color: #059669;"></i>
            <div style="flex: 1;">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #FEE2E2; border: 2px solid #EF4444; border-radius: 12px; padding: 14px 18px; color: #991B1B; margin-bottom: 20px; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.2rem; color: #DC2626;"></i>
            <div style="flex: 1;">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #FEE2E2; border: 2px solid #EF4444; border-radius: 12px; padding: 14px 18px; color: #991B1B; margin-bottom: 20px; font-weight: 700; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 0.95rem;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul style="margin-left: 24px; font-size: 0.85rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Hero Role & Account Status Banner -->
    <div style="background: linear-gradient(135deg, #111827 0%, #1F2937 100%); border-radius: 20px; padding: 24px 28px; color: white; margin-bottom: 24px; border: 2px solid #374151; box-shadow: 0 6px 20px rgba(0,0,0,0.08); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 18px;">
            <div style="width: 64px; height: 64px; background: #FBBF24; color: #111827; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.9rem; font-weight: 900; box-shadow: 0 4px 10px rgba(251, 191, 36, 0.4);">
                @if($user->role === 'admin')
                    <i class="fa-solid fa-crown"></i>
                @elseif($user->role === 'developer')
                    <i class="fa-solid fa-terminal"></i>
                @else
                    <i class="fa-solid fa-cash-register"></i>
                @endif
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <h2 style="font-size: 1.4rem; font-weight: 900; margin: 0; color: white;">
                        {{ $user->name }}
                    </h2>
                    @if($user->role === 'admin')
                        <span class="role-badge role-badge-admin">
                            <i class="fa-solid fa-crown"></i> Admin Kasir Utama / Owner
                        </span>
                    @elseif($user->role === 'developer')
                        <span class="role-badge role-badge-developer">
                            <i class="fa-solid fa-terminal"></i> Master Developer
                        </span>
                    @elseif($user->role === 'kasir')
                        <span class="role-badge role-badge-kasir">
                            <i class="fa-solid fa-cash-register"></i> Kasir Reguler
                        </span>
                    @else
                        <span class="role-badge role-badge-dapur">
                            <i class="fa-solid fa-utensils"></i> {{ strtoupper($user->role) }}
                        </span>
                    @endif
                </div>
                <p style="font-size: 0.85rem; color: #9CA3AF; margin: 4px 0 0 0;">
                    Username: <strong style="color: #FCD34D;">{{ $user->username }}</strong> &bull; Email: <strong style="color: #E5E7EB;">{{ $user->email }}</strong>
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" onclick="switchTab('staff')" class="btn-primary" style="padding: 10px 18px; font-size: 0.85rem;">
                <i class="fa-solid fa-users-gear"></i>
                <span>Kelola Staff &amp; Kasir</span>
            </button>
            <button type="button" onclick="switchTab('credentials')" style="background: #374151; color: #F3F4F6; border: 1px solid #4B5563; padding: 10px 16px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-key"></i>
                <span>Kredensial Login</span>
            </button>
        </div>
    </div>

    <!-- Quick KPI Stats Bar -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="stat-val">{{ $roleStats['total_users'] ?? count($allUsers) }}</div>
                <div class="stat-label">Total Akun Terdaftar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div>
                <div class="stat-val">{{ $roleStats['admin_count'] ?? 1 }}</div>
                <div class="stat-label">Admin Kasir Utama / Owner</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <div class="stat-val">{{ $roleStats['kasir_count'] ?? 1 }}</div>
                <div class="stat-label">Kasir Reguler (POS)</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #EEF2FF; color: #6366F1;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <div class="stat-val">Bcrypt Active</div>
                <div class="stat-label">Keamanan Sesi Terproteksi</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="role-tabs">
        <button type="button" class="role-tab-btn active" id="tab-btn-profile" onclick="switchTab('profile')">
            <i class="fa-solid fa-user-pen"></i>
            <span>Profil Saya &amp; Password</span>
        </button>
        <button type="button" class="role-tab-btn" id="tab-btn-staff" onclick="switchTab('staff')">
            <i class="fa-solid fa-users-gear"></i>
            <span>Manajemen Akun Staff &amp; Kasir ({{ count($allUsers) }})</span>
        </button>
        <button type="button" class="role-tab-btn" id="tab-btn-roles" onclick="switchTab('roles')">
            <i class="fa-solid fa-table-list"></i>
            <span>Matriks Hak Akses &amp; Role</span>
        </button>
        <button type="button" class="role-tab-btn" id="tab-btn-credentials" onclick="switchTab('credentials')">
            <i class="fa-solid fa-key"></i>
            <span>Kredensial &amp; Autentikasi Cepat</span>
        </button>
    </div>

    <!-- =========================================================================
         TAB 1: PROFIL SAYA & GANTI PASSWORD
         ========================================================================= -->
    <div class="tab-pane active" id="pane-profile">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            
            <!-- Form Edit Profil Saya -->
            <div class="card" style="margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 14px; padding-bottom: 18px; border-bottom: 1.5px solid var(--border-color); margin-bottom: 22px;">
                    <div style="width: 48px; height: 48px; background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #D97706;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0;">Edit Data Akun &amp; Kredensial</h3>
                        <p style="font-size: 0.8rem; color: #6B7280; margin: 2px 0 0 0;">Perbarui nama, username login, email, atau buat password baru.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                                Nama Lengkap / Panggilan <span style="color: #EF4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-id-card" style="position: absolute; left: 14px; top: 13px; color: #9CA3AF;"></i>
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required 
                                    style="width: 100%; padding: 10px 14px 10px 40px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none;"
                                >
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                                Username Login <span style="color: #EF4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-user" style="position: absolute; left: 14px; top: 13px; color: #9CA3AF;"></i>
                                <input 
                                    type="text" 
                                    name="username" 
                                    value="{{ old('username', $user->username) }}" 
                                    required 
                                    style="width: 100%; padding: 10px 14px 10px 40px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none;"
                                >
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                                Alamat Email <span style="color: #EF4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-envelope" style="position: absolute; left: 14px; top: 13px; color: #9CA3AF;"></i>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required 
                                    style="width: 100%; padding: 10px 14px 10px 40px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none;"
                                >
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                                Role / Tingkat Akses Akun
                            </label>
                            @if(in_array(auth()->user()->role, ['developer', 'admin']))
                                <select name="role" style="width: 100%; padding: 10px 14px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none; background: white;">
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>👑 Admin Kasir Utama / Owner</option>
                                    <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>💼 Kasir Reguler (POS &amp; Nota)</option>
                                    @if(auth()->user()->role === 'developer')
                                        <option value="developer" {{ old('role', $user->role) === 'developer' ? 'selected' : '' }}>💻 Master Developer</option>
                                    @endif
                                </select>
                            @else
                                <input type="text" value="{{ strtoupper($user->role) }}" disabled style="width: 100%; padding: 10px 14px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 800; font-size: 0.9rem; background: #F3F4F6; color: #6B7280;">
                            @endif
                        </div>
                    </div>

                    <!-- Ganti Password Section -->
                    <div style="background: #F9FAFB; border: 1.5px dashed #D1D5DB; border-radius: 14px; padding: 18px; margin-bottom: 22px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-lock" style="color: #EA580C; font-size: 1rem;"></i>
                                <h4 style="font-size: 0.9rem; font-weight: 900; color: #111827; margin: 0;">Ganti Password Baru</h4>
                            </div>
                            <span style="font-size: 0.72rem; color: #6B7280; font-weight: 700;">(Kosongkan jika password tidak diganti)</span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div>
                                <label style="display: block; font-size: 0.78rem; font-weight: 800; color: #4B5563; margin-bottom: 5px;">
                                    Password Baru
                                </label>
                                <div style="position: relative;">
                                    <input 
                                        type="password" 
                                        id="my_new_password" 
                                        name="password" 
                                        placeholder="Min. 4 karakter..." 
                                        style="width: 100%; padding: 9px 12px; border: 2px solid var(--border-color); border-radius: 9px; font-weight: 700; font-size: 0.88rem; outline: none; background: white;"
                                    >
                                </div>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.78rem; font-weight: 800; color: #4B5563; margin-bottom: 5px;">
                                    Ulangi Password Baru
                                </label>
                                <div style="position: relative;">
                                    <input 
                                        type="password" 
                                        id="my_password_confirmation" 
                                        name="password_confirmation" 
                                        placeholder="Ketik ulang password..." 
                                        style="width: 100%; padding: 9px 12px; border: 2px solid var(--border-color); border-radius: 9px; font-weight: 700; font-size: 0.88rem; outline: none; background: white;"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary" style="padding: 12px 24px; font-size: 0.92rem;">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Perubahan Akun</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Role Summary Card -->
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <div class="card" style="border: 2px solid var(--border-color); border-radius: 16px; padding: 20px; margin-bottom: 0;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                        <div style="width: 42px; height: 42px; background: #111827; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FBBF24; font-size: 1.2rem;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 0.95rem; font-weight: 900; margin: 0; color: #111827;">Status Hak Akses</h4>
                            <span style="font-size: 0.72rem; color: #059669; font-weight: 800;">
                                <i class="fa-solid fa-circle-check"></i> Sesi Aktif &amp; Terverifikasi
                            </span>
                        </div>
                    </div>

                    <p style="font-size: 0.8rem; color: #4B5563; line-height: 1.5; margin-bottom: 14px;">
                        Akun <strong>{{ $user->username }}</strong> bertindak sebagai 
                        <strong style="color: #D97706;">{{ strtoupper($user->role === 'admin' ? 'Admin Kasir Utama / Owner' : $user->role) }}</strong>.
                    </p>

                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 0.78rem; font-weight: 700; color: #374151;">
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-check" style="color: #059669;"></i> Monitoring Pesanan &amp; Realtime Meja
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-check" style="color: #059669;"></i> POS &amp; Scan Barcode Kasir
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-check" style="color: #059669;"></i> Kelola Menu &amp; Pengaturan QRIS
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-check" style="color: #059669;"></i> Cetak Nota / Struk Pembayaran
                        </li>
                    </ul>
                </div>

                <div class="card" style="background: #FFFBEB; border: 1.5px solid #FCD34D; border-radius: 16px; padding: 18px; margin-bottom: 0;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fa-solid fa-lightbulb" style="color: #D97706; font-size: 1.2rem; margin-top: 2px;"></i>
                        <div>
                            <strong style="font-size: 0.85rem; color: #92400E; display: block; margin-bottom: 4px;">Tips Keamanan Akun</strong>
                            <p style="font-size: 0.75rem; color: #B45309; margin: 0; line-height: 1.4;">
                                Jangan bagikan password login kasir ke pihak luar. Gunakan kombinasi huruf dan angka untuk keamanan maksimal toko Bebalung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- =========================================================================
         TAB 2: MANAJEMEN AKUN STAFF & KASIR BEBALUNG
         ========================================================================= -->
    <div class="tab-pane" id="pane-staff">
        <div class="card" style="margin-bottom: 0;">
            
            <!-- Action Header & Search -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1.5px solid var(--border-color);">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0;">
                        Daftar Akun Pengguna &amp; Staff ({{ count($allUsers) }})
                    </h3>
                    <p style="font-size: 0.8rem; color: #6B7280; margin: 2px 0 0 0;">
                        Kelola akun Kasir Utama, Kasir Reguler, dan akses operasional restoran.
                    </p>
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                    <div style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 11px; color: #9CA3AF; font-size: 0.85rem;"></i>
                        <input 
                            type="text" 
                            id="searchStaffInput" 
                            onkeyup="filterStaffTable()" 
                            placeholder="Cari nama / username..." 
                            style="padding: 8px 12px 8px 34px; border: 1.5px solid var(--border-color); border-radius: 8px; font-size: 0.82rem; font-weight: 700; outline: none; width: 200px;"
                        >
                    </div>

                    @if(in_array(auth()->user()->role, ['developer', 'admin']))
                        <button type="button" onclick="openAddUserModal()" class="btn-primary" style="padding: 9px 16px; font-size: 0.85rem;">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>Tambah Akun Staff</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Staff Accounts Table -->
            <div class="table-responsive">
                <table class="user-table" id="staffTable">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Username</th>
                            <th>Role &amp; Hak Akses</th>
                            <th>Email Terdaftar</th>
                            <th>Dibuat</th>
                            <th style="text-align: right;">Aksi &amp; Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allUsers as $u)
                            <tr class="staff-row" data-name="{{ strtolower($u->name) }}" data-user="{{ strtolower($u->username) }}" data-role="{{ strtolower($u->role) }}">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="user-avatar" style="
                                            @if($u->role === 'admin') background: #FEF3C7; color: #D97706; border: 1.5px solid #FCD34D;
                                            @elseif($u->role === 'developer') background: #EEF2FF; color: #6366F1; border: 1.5px solid #C7D2FE;
                                            @else background: #D1FAE5; color: #059669; border: 1.5px solid #A7F3D0;
                                            @endif
                                        ">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; color: #111827; font-size: 0.9rem;">
                                                {{ $u->name }}
                                                @if($u->id === auth()->id())
                                                    <span style="font-size: 0.68rem; background: #111827; color: white; padding: 2px 6px; border-radius: 4px; margin-left: 4px; font-weight: 800;">ANDA</span>
                                                @endif
                                            </div>
                                            <span style="font-size: 0.72rem; color: #6B7280;">ID Akun: #{{ $u->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code style="background: #F3F4F6; padding: 3px 8px; border-radius: 6px; font-weight: 800; color: #1F2937; font-size: 0.85rem; border: 1px solid #E5E7EB;">
                                        {{ $u->username }}
                                    </code>
                                </td>
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="role-badge role-badge-admin">
                                            <i class="fa-solid fa-crown"></i> Admin Kasir Utama
                                        </span>
                                    @elseif($u->role === 'developer')
                                        <span class="role-badge role-badge-developer">
                                            <i class="fa-solid fa-terminal"></i> Master Dev
                                        </span>
                                    @elseif($u->role === 'kasir')
                                        <span class="role-badge role-badge-kasir">
                                            <i class="fa-solid fa-cash-register"></i> Kasir Reguler
                                        </span>
                                    @else
                                        <span class="role-badge role-badge-dapur">
                                            <i class="fa-solid fa-utensils"></i> {{ strtoupper($u->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size: 0.82rem; color: #4B5563;">
                                    <i class="fa-regular fa-envelope" style="color: #9CA3AF; margin-right: 4px;"></i>
                                    {{ $u->email }}
                                </td>
                                <td style="font-size: 0.78rem; color: #6B7280;">
                                    {{ $u->created_at ? $u->created_at->format('d M Y') : 'Bawaan Sistem' }}
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                                        @if(in_array(auth()->user()->role, ['developer', 'admin']))
                                            <button 
                                                type="button" 
                                                onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->username) }}', '{{ addslashes($u->email) }}', '{{ $u->role }}')"
                                                style="background: #FFFBEB; color: #D97706; border: 1px solid #FCD34D; padding: 6px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; cursor: pointer;" 
                                                title="Edit Akun &amp; Role"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </button>

                                            <button 
                                                type="button" 
                                                onclick="openResetPasswordModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->username) }}')"
                                                style="background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; padding: 6px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; cursor: pointer;" 
                                                title="Reset Password"
                                            >
                                                <i class="fa-solid fa-key"></i> Reset
                                            </button>

                                            @if($u->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} ({{ $u->username }})? Tindakan ini tidak dapat dibatalkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA; padding: 6px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; cursor: pointer;" title="Hapus Akun">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span style="font-size: 0.72rem; color: #9CA3AF; font-weight: 700;">Hanya Baca</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 24px; color: #6B7280;">
                                    Belum ada data staff tambahan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- =========================================================================
         TAB 3: MATRIKS HAK AKSES & ROLE
         ========================================================================= -->
    <div class="tab-pane" id="pane-roles">
        <div class="card" style="margin-bottom: 0;">
            <div style="padding-bottom: 16px; border-bottom: 1.5px solid var(--border-color); margin-bottom: 20px;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0;">
                    Matriks Pembagian Peran &amp; Hak Akses (Role Permissions)
                </h3>
                <p style="font-size: 0.8rem; color: #6B7280; margin: 2px 0 0 0;">
                    Struktur kewenangan tiap role dalam sistem operasional Depot Sate Be Ba Lung.
                </p>
            </div>

            <div class="table-responsive" style="margin-bottom: 24px;">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Fitur / Modul Restoran</th>
                            <th style="text-align: center;">
                                <span class="role-badge role-badge-admin"><i class="fa-solid fa-crown"></i> Admin Kasir Utama</span>
                            </th>
                            <th style="text-align: center;">
                                <span class="role-badge role-badge-kasir"><i class="fa-solid fa-cash-register"></i> Kasir Reguler</span>
                            </th>
                            <th style="text-align: center;">
                                <span class="role-badge role-badge-developer"><i class="fa-solid fa-terminal"></i> Master Dev</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Live Dashboard &amp; Monitoring Pesanan Masuk</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Melihat pesanan meja realtime, status pending/proses/selesai</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>POS Barcode Scanner &amp; Quick Pay Kasir</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Scan barcode pelanggan, konfirmasi cash &amp; upload bukti transfer</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Cetak Struk / Nota Thermal Kasir</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Print nota 58mm / 80mm dengan nama kasir yang bertugas</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Kelola Menu Resto (15 Menu Resmi)</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Tambah menu, ganti harga, ubah foto &amp; toggle stok habis</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #9CA3AF; font-size: 1.1rem;"><i class="fa-solid fa-circle-minus"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Kelola QR Meja &amp; Cetak Nomor Meja 1-20</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Download &amp; cetak standee QR meja pelanggan, kosongkan meja</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Pengaturan QRIS Toko &amp; Standee Kasir</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Update QRIS resmi Bebalung, cetak standee akrilik kasir</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #9CA3AF; font-size: 1.1rem;"><i class="fa-solid fa-circle-minus"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Manajemen Akun Staff &amp; Reset Password</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Tambah akun kasir baru, atur role, ganti password staff</div>
                            </td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                            <td style="text-align: center; color: #9CA3AF; font-size: 1.1rem;"><i class="fa-solid fa-circle-minus"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Developer Console &amp; Master Tools</strong>
                                <div style="font-size: 0.75rem; color: #6B7280;">Reset data transaksi testing, sinkronisasi skema DB, clear cache</div>
                            </td>
                            <td style="text-align: center; color: #9CA3AF; font-size: 1.1rem;"><i class="fa-solid fa-circle-minus"></i></td>
                            <td style="text-align: center; color: #9CA3AF; font-size: 1.1rem;"><i class="fa-solid fa-circle-minus"></i></td>
                            <td style="text-align: center; color: #059669; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Role Explanations Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <div style="background: #FFFBEB; border: 1.5px solid #FCD34D; border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fa-solid fa-crown" style="color: #D97706;"></i>
                        <strong style="font-size: 0.9rem; color: #92400E;">Admin Kasir Utama / Owner</strong>
                    </div>
                    <p style="font-size: 0.78rem; color: #B45309; margin: 0; line-height: 1.4;">
                        Pemilik dan kepala kasir restoran. Memiliki hak penuh untuk mengelola harga menu, laporan keuangan, pengaturan QRIS, dan mengontrol seluruh staf kasir.
                    </p>
                </div>

                <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fa-solid fa-cash-register" style="color: #059669;"></i>
                        <strong style="font-size: 0.9rem; color: #065F46;">Kasir Reguler (POS)</strong>
                    </div>
                    <p style="font-size: 0.78rem; color: #047857; margin: 0; line-height: 1.4;">
                        Petugas kasir operasional harian. Fokus pada scanning barcode order pelanggan, menerima uang tunai, mencetak struk, dan update status pesanan.
                    </p>
                </div>

                <div style="background: #EEF2FF; border: 1.5px solid #C7D2FE; border-radius: 12px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fa-solid fa-terminal" style="color: #6366F1;"></i>
                        <strong style="font-size: 0.9rem; color: #3730A3;">Master Developer</strong>
                    </div>
                    <p style="font-size: 0.78rem; color: #4338CA; margin: 0; line-height: 1.4;">
                        Pengembang sistem. Bertanggung jawab atas pemeliharaan server, sinkronisasi skema database, pembersihan cache, dan pengujian transaksi.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- =========================================================================
         TAB 4: KREDENSIAL & AUTENTIKASI CEPAT
         ========================================================================= -->
    <div class="tab-pane" id="pane-credentials">
        <div class="card" style="margin-bottom: 0;">
            <div style="padding-bottom: 16px; border-bottom: 1.5px solid var(--border-color); margin-bottom: 22px;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #111827; margin: 0;">
                    Cheatsheet Kredensial Login Resmi
                </h3>
                <p style="font-size: 0.8rem; color: #6B7280; margin: 2px 0 0 0;">
                    Gunakan kredensial berikut untuk login atau pengujian operasional kasir &amp; admin.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 18px; margin-bottom: 24px;">
                
                <!-- Card 1: Admin Kasir Utama -->
                <div class="cred-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #D97706; font-size: 1.2rem;">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 900; margin: 0; color: #111827;">Kasir Utama / Owner</h4>
                                <span class="role-badge role-badge-admin" style="font-size: 0.68rem; margin-top: 2px;">Full Admin</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; border-radius: 10px; padding: 12px; margin-bottom: 14px; font-size: 0.82rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="color: #6B7280; font-weight: 700;">Username:</span>
                            <code style="font-weight: 900; color: #111827; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">admin</code>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 700;">Password:</span>
                            <code style="font-weight: 900; color: #EA580C; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">ownsate</code>
                        </div>
                    </div>

                    <button type="button" onclick="copyCred('admin', 'ownsate')" style="width: 100%; background: #FEF3C7; color: #92400E; border: 1.5px solid #FCD34D; padding: 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-copy"></i> Salin Username &amp; Password
                    </button>
                </div>

                <!-- Card 2: Kasir Reguler -->
                <div class="cred-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: #D1FAE5; border: 2px solid #10B981; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #059669; font-size: 1.2rem;">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 900; margin: 0; color: #111827;">Kasir 1 (POS Shift)</h4>
                                <span class="role-badge role-badge-kasir" style="font-size: 0.68rem; margin-top: 2px;">Kasir Operasional</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; border-radius: 10px; padding: 12px; margin-bottom: 14px; font-size: 0.82rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="color: #6B7280; font-weight: 700;">Username:</span>
                            <code style="font-weight: 900; color: #111827; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">kasir1</code>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 700;">Password:</span>
                            <code style="font-weight: 900; color: #059669; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">sate</code>
                        </div>
                    </div>

                    <button type="button" onclick="copyCred('kasir1', 'sate')" style="width: 100%; background: #D1FAE5; color: #065F46; border: 1.5px solid #A7F3D0; padding: 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-copy"></i> Salin Username &amp; Password
                    </button>
                </div>

                <!-- Card 3: Master Developer -->
                <div class="cred-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; background: #EEF2FF; border: 2px solid #6366F1; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4F46E5; font-size: 1.2rem;">
                                <i class="fa-solid fa-terminal"></i>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 900; margin: 0; color: #111827;">Master Developer</h4>
                                <span class="role-badge role-badge-developer" style="font-size: 0.68rem; margin-top: 2px;">System Master</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; border-radius: 10px; padding: 12px; margin-bottom: 14px; font-size: 0.82rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span style="color: #6B7280; font-weight: 700;">Username:</span>
                            <code style="font-weight: 900; color: #111827; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">dev</code>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6B7280; font-weight: 700;">Password:</span>
                            <code style="font-weight: 900; color: #4F46E5; background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #E5E7EB;">121212</code>
                        </div>
                    </div>

                    <button type="button" onclick="copyCred('dev', '121212')" style="width: 100%; background: #EEF2FF; color: #3730A3; border: 1.5px solid #C7D2FE; padding: 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-copy"></i> Salin Username &amp; Password
                    </button>
                </div>

            </div>

            <div style="background: #F3F4F6; border-radius: 12px; padding: 14px 18px; font-size: 0.82rem; color: #4B5563; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-link" style="color: #EA580C;"></i>
                    <span>URL Halaman Login Kasir &amp; Admin: <strong style="color: #111827;">{{ url('/login') }}</strong></span>
                </div>
                <a href="{{ route('login') }}" target="_blank" style="color: #D97706; font-weight: 800; text-decoration: underline;">
                    Buka Halaman Login &rarr;
                </a>
            </div>

        </div>
    </div>

</div>

<!-- =========================================================================
     MODAL: TAMBAH AKUN STAFF / KASIR BARU
     ========================================================================= -->
<div class="modal-backdrop" id="addUserModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1.5px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: #FEF3C7; border: 1.5px solid #F59E0B; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #D97706;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3 style="font-size: 1.05rem; font-weight: 900; color: #111827; margin: 0;">Tambah Akun Staff Baru</h3>
            </div>
            <button type="button" onclick="closeAddUserModal()" style="background: none; border: none; font-size: 1.2rem; color: #9CA3AF; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Nama Lengkap Staff <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    placeholder="Contoh: Kasir Shift Sore (Budi)" 
                    required 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                        Username Login <span style="color: #EF4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        placeholder="Contoh: kasir2" 
                        required 
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                    >
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                        Role / Akses <span style="color: #EF4444;">*</span>
                    </label>
                    <select name="role" required style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none; background: white;">
                        <option value="kasir">💼 Kasir Reguler (POS)</option>
                        <option value="admin">👑 Admin Kasir Utama</option>
                        <option value="dapur">🍳 Tim Dapur / Pelayan</option>
                        @if(auth()->user()->role === 'developer')
                            <option value="developer">💻 Master Developer</option>
                        @endif
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Email Staff <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Contoh: kasir2@bebarung.com" 
                    required 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Password Login <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Minimal 4 karakter..." 
                    required 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAddUserModal()" style="background: #F3F4F6; color: #374151; border: 1.5px solid #D1D5DB; padding: 10px 18px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">
                    <i class="fa-solid fa-check"></i> Buat Akun Staff
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL: EDIT AKUN STAFF & ROLE
     ========================================================================= -->
<div class="modal-backdrop" id="editUserModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1.5px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: #EEF2FF; border: 1.5px solid #6366F1; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #4F46E5;">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 style="font-size: 1.05rem; font-weight: 900; color: #111827; margin: 0;">Edit Data &amp; Role Akun Staff</h3>
            </div>
            <button type="button" onclick="closeEditUserModal()" style="background: none; border: none; font-size: 1.2rem; color: #9CA3AF; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Nama Lengkap <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="text" 
                    id="edit_name" 
                    name="name" 
                    required 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                        Username Login <span style="color: #EF4444;">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="edit_username" 
                        name="username" 
                        required 
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                    >
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                        Role / Akses <span style="color: #EF4444;">*</span>
                    </label>
                    <select id="edit_role" name="role" required style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none; background: white;">
                        <option value="kasir">💼 Kasir Reguler (POS)</option>
                        <option value="admin">👑 Admin Kasir Utama</option>
                        <option value="dapur">🍳 Tim Dapur / Pelayan</option>
                        @if(auth()->user()->role === 'developer')
                            <option value="developer">💻 Master Developer</option>
                        @endif
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Email <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="email" 
                    id="edit_email" 
                    name="email" 
                    required 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Password Baru (Opsional)
                </label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Kosongkan jika tidak diubah..." 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditUserModal()" style="background: #F3F4F6; color: #374151; border: 1.5px solid #D1D5DB; padding: 10px 18px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL: RESET PASSWORD CEPAT
     ========================================================================= -->
<div class="modal-backdrop" id="resetPasswordModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1.5px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: #FEF3C7; border: 1.5px solid #F59E0B; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #D97706;">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 style="font-size: 1.05rem; font-weight: 900; color: #111827; margin: 0;">Reset Password Staff</h3>
            </div>
            <button type="button" onclick="closeResetPasswordModal()" style="background: none; border: none; font-size: 1.2rem; color: #9CA3AF; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="resetPasswordForm" method="POST">
            @csrf

            <p style="font-size: 0.85rem; color: #4B5563; margin-bottom: 14px;">
                Anda sedang mereset password untuk akun <strong id="reset_user_label" style="color: #111827;"></strong>.
            </p>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #374151; margin-bottom: 5px;">
                    Password Baru <span style="color: #EF4444;">*</span>
                </label>
                <input 
                    type="text" 
                    name="new_password" 
                    id="new_staff_password" 
                    required 
                    placeholder="Masukkan password baru..." 
                    style="width: 100%; padding: 10px 12px; border: 2px solid var(--border-color); border-radius: 8px; font-weight: 700; font-size: 0.88rem; outline: none;"
                >
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <button type="button" onclick="generateRandomPass()" style="background: #F3F4F6; color: #4B5563; border: 1px solid #D1D5DB; padding: 8px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 800; cursor: pointer;">
                    <i class="fa-solid fa-dice"></i> Buat Acak
                </button>

                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="closeResetPasswordModal()" style="background: #F3F4F6; color: #374151; border: 1.5px solid #D1D5DB; padding: 10px 16px; border-radius: 8px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary" style="padding: 10px 18px; font-size: 0.85rem;">
                        <i class="fa-solid fa-check"></i> Reset Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab switching handler
    function switchTab(tabId) {
        document.querySelectorAll('.role-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

        const targetBtn = document.getElementById('tab-btn-' + tabId);
        const targetPane = document.getElementById('pane-' + tabId);

        if (targetBtn && targetPane) {
            targetBtn.classList.add('active');
            targetPane.classList.add('active');
        }

        // Update URL hash without scroll
        if (history.pushState) {
            history.pushState(null, null, '#tab-' + tabId);
        }
    }

    // Initialize tab from URL hash or query params
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const hash = window.location.hash.replace('#tab-', '');

        if (tabParam && ['profile', 'staff', 'roles', 'credentials'].includes(tabParam)) {
            switchTab(tabParam);
        } else if (hash && ['profile', 'staff', 'roles', 'credentials'].includes(hash)) {
            switchTab(hash);
        }
    });

    // Filter staff table live
    function filterStaffTable() {
        const input = document.getElementById('searchStaffInput').value.toLowerCase();
        const rows = document.querySelectorAll('.staff-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const user = row.getAttribute('data-user') || '';
            const role = row.getAttribute('data-role') || '';

            if (name.includes(input) || user.includes(input) || role.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Modal Add User
    function openAddUserModal() {
        document.getElementById('addUserModal').classList.add('open');
    }
    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.remove('open');
    }

    // Modal Edit User
    function openEditUserModal(id, name, username, email, role) {
        const form = document.getElementById('editUserForm');
        form.action = "{{ url('/admin/users') }}/" + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('editUserModal').classList.add('open');
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.remove('open');
    }

    // Modal Reset Password
    function openResetPasswordModal(id, name, username) {
        const form = document.getElementById('resetPasswordForm');
        form.action = "{{ url('/admin/users') }}/" + id + "/reset-password";
        document.getElementById('reset_user_label').innerText = name + ' (' + username + ')';
        document.getElementById('new_staff_password').value = 'bebalung' + Math.floor(100 + Math.random() * 900);
        document.getElementById('resetPasswordModal').classList.add('open');
    }
    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.remove('open');
    }

    function generateRandomPass() {
        document.getElementById('new_staff_password').value = 'bebalung' + Math.floor(1000 + Math.random() * 9000);
    }

    // Copy Credential helper
    function copyCred(username, password) {
        const text = `Username: ${username}\nPassword: ${password}`;
        navigator.clipboard.writeText(text).then(() => {
            alert(`✅ Kredensial berhasil disalin:\nUsername: ${username}\nPassword: ${password}`);
        }).catch(() => {
            alert(`Username: ${username}\nPassword: ${password}`);
        });
    }

    // Close modals on outside click
    window.addEventListener('click', (e) => {
        ['addUserModal', 'editUserModal', 'resetPasswordModal'].forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && e.target === modal) {
                modal.classList.remove('open');
            }
        });
    });
</script>
@endsection
