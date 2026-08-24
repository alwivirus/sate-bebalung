@extends('layouts.admin')

@section('title', 'Edit Profil & Kredensial Akun - Depot Sate Be Ba Lung')
@section('page-title', 'Pengaturan Akun & Password')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    
    @if($errors->any())
        <div style="background: #FEE2E2; border: 2px solid #EF4444; border-radius: 12px; padding: 14px 18px; color: #991B1B; margin-bottom: 24px; font-weight: 700; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 0.95rem;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengisian formulir:</span>
            </div>
            <ul style="margin-left: 24px; font-size: 0.85rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="border: 2.5px solid var(--border-color); border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
        <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 20px; border-bottom: 1.5px solid var(--border-color); margin-bottom: 24px;">
            <div style="width: 56px; height: 56px; background: #FEF3C7; border: 2.5px solid #F59E0B; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #D97706;">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 900; color: #111827; margin: 0;">Edit Data Akun & Password</h3>
                <p style="font-size: 0.8rem; color: #6B7280; margin: 2px 0 0 0;">Ubah nama, username, alamat email, atau perbarui password login Anda.</p>
            </div>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                        Nama Lengkap / Panggilan
                    </label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-id-card" style="position: absolute; left: 14px; top: 14px; color: #9CA3AF;"></i>
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
                    <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                        Username Login
                    </label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-user" style="position: absolute; left: 14px; top: 14px; color: #9CA3AF;"></i>
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

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #374151; margin-bottom: 6px;">
                    Alamat Email
                </label>
                <div style="position: relative;">
                    <i class="fa-solid fa-envelope" style="position: absolute; left: 14px; top: 14px; color: #9CA3AF;"></i>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        style="width: 100%; padding: 10px 14px 10px 40px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none;"
                    >
                </div>
            </div>

            <!-- Ganti Password Section -->
            <div style="background: #F9FAFB; border: 1.5px dashed #D1D5DB; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <i class="fa-solid fa-key" style="color: #EA580C; font-size: 1rem;"></i>
                    <h4 style="font-size: 0.92rem; font-weight: 800; color: #111827; margin: 0;">Ganti Password Baru</h4>
                    <span style="font-size: 0.72rem; color: #6B7280; font-weight: 600;">(Kosongkan jika tidak ingin mengubah password)</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #4B5563; margin-bottom: 6px;">
                            Password Baru
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                id="new_password" 
                                name="password" 
                                placeholder="Minimal 4 karakter..." 
                                style="width: 100%; padding: 10px 14px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none; background: white;"
                            >
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #4B5563; margin-bottom: 6px;">
                            Ulangi Password Baru
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ketik ulang password..." 
                                style="width: 100%; padding: 10px 14px; border: 2px solid var(--border-color); border-radius: 10px; font-weight: 700; font-size: 0.9rem; outline: none; background: white;"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                <button type="submit" class="btn-primary" style="padding: 12px 24px; font-size: 0.95rem;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Akun</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Info Role Card -->
    <div style="background: white; border: 1.5px solid var(--border-color); border-radius: 14px; padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: #111827; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #FCD34D;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <div style="font-size: 0.88rem; font-weight: 800; color: #111827;">Tingkat Akses Akun: <span style="color: #EA580C;">{{ strtoupper($user->role ?? 'ADMIN') }}</span></div>
                <div style="font-size: 0.75rem; color: #6B7280;">Akun ini memiliki hak akses penuh untuk mengelola pesanan, menu, dan kasir.</div>
            </div>
        </div>
        <div style="font-size: 0.75rem; font-weight: 700; color: #059669; background: #D1FAE5; padding: 4px 10px; border-radius: 6px;">
            <i class="fa-solid fa-circle-check"></i> Aktif
        </div>
    </div>

</div>
@endsection
