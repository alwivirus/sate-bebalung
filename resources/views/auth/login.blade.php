<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir &amp; Admin - Depot Sate Be Ba Lung</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-yellow: #FBBF24;
            --primary-orange: #EA580C;
            --dark-border: #111827;
            --bg-page: #FFFBEB;
            --box-shadow-brutal: 5px 5px 0px #111827;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-page);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #FFFFFF;
            border: 3.5px solid var(--dark-border);
            border-radius: 22px;
            box-shadow: var(--box-shadow-brutal);
            padding: 32px 26px;
            position: relative;
        }

        .brand-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            background: #111827;
            border-radius: 14px;
            border: 2.5px solid var(--dark-border);
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .brand-title {
            text-align: left;
        }

        .brand-title h1 {
            font-size: 1.25rem;
            font-weight: 900;
            color: #111827;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }

        .brand-title span {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--primary-orange);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .security-badge {
            background: #FEF3C7;
            border: 1.5px solid #D97706;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #92400E;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #6B7280;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #111827;
            outline: none;
            background: #F9FAFB;
            transition: all 0.15s;
        }

        .form-input:focus {
            border-color: var(--primary-orange);
            background: #FFFFFF;
            box-shadow: 2px 2px 0px var(--dark-border);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-login {
            width: 100%;
            background: var(--primary-yellow);
            color: #111827;
            border: 2.5px solid var(--dark-border);
            border-radius: 12px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 3px 3px 0px var(--dark-border);
            transition: transform 0.1s, box-shadow 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #F59E0B;
        }

        .btn-login:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #000;
        }

        .error-alert {
            background: #FEE2E2;
            border: 2px solid #DC2626;
            border-radius: 10px;
            padding: 10px 14px;
            color: #991B1B;
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .success-alert {
            background: #D1FAE5;
            border: 2px solid #059669;
            border-radius: 10px;
            padding: 10px 14px;
            color: #065F46;
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-note {
            margin-top: 22px;
            font-size: 0.72rem;
            color: #6B7280;
            text-align: center;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-badge">
        <div class="brand-logo">
            <img src="{{ asset('images/logo-goat.png') }}" alt="Logo">
        </div>
        <div class="brand-title">
            <h1>DEPOT BE BA LUNG</h1>
            <span>Akses Kasir &amp; Admin Rahasia</span>
        </div>
    </div>

    <div class="security-badge">
        <i class="fa-solid fa-lock" style="font-size: 1.1rem; color: #D97706;"></i>
        <div>
            <strong>Area Terproteksi:</strong> Hanya kasir &amp; pemilik toko yang berwenang. Pelanggan umum tidak memiliki akses ke halaman ini.
        </div>
    </div>

    @if($errors->any())
        <div class="error-alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    @if(session('success'))
        <div class="success-alert">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label" for="loginInput">Username atau Email Kasir</label>
            <div class="input-wrapper">
                <i class="fa-solid fa-user input-icon"></i>
                <input 
                    type="text" 
                    id="loginInput" 
                    name="login" 
                    class="form-input" 
                    placeholder="Contoh: admin atau kasir1" 
                    value="{{ old('login') }}" 
                    required 
                    autofocus
                >
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="passwordInput">Password Rahasia</label>
            <div class="input-wrapper">
                <i class="fa-solid fa-key input-icon"></i>
                <input 
                    type="password" 
                    id="passwordInput" 
                    name="password" 
                    class="form-input" 
                    placeholder="Masukkan password kasir..." 
                    required
                >
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-size: 0.8rem;">
            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-weight: 700; color: #374151;">
                <input type="checkbox" name="remember" style="accent-color: #EA580C; width: 16px; height: 16px;">
                Ingat Saya di Perangkat Ini
            </label>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>MASUK KE PANEL KASIR</span>
        </button>
    </form>

    <div class="footer-note">
        <i class="fa-solid fa-shield-halved"></i> Dilindungi Enkripsi Sesi Aman &bull; Depot Sate Be Ba Lung
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>
