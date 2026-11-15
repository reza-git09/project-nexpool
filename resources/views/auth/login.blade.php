
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Masuk - NEXPOOL</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =====================================
   NEXPOOL ADMIN LOGIN
   TEMA NAVY
===================================== */

:root {
    --navy: #23447D;
    --blue: #23447D;
    --blue-hover: #193866;
    --background: #23447D;
    --white: #FFFFFF;
    --text: #172B4D;
    --muted: #8999B0;
    --border: #D8E0EC;
    --input-bg: #E8F0FC;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    min-height: 100vh;
    min-height: 100svh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background: var(--background);
    position: relative;
    overflow: hidden;
}

/* LATAR BELAKANG */

.circle {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

.circle-one {
    width: 390px;
    height: 390px;
    background: #2D508A;
    top: -190px;
    left: -140px;
}

.circle-two {
    width: 440px;
    height: 440px;
    background: #2D508A;
    bottom: -245px;
    right: -130px;
}

/* KARTU LOGIN */

.login-container {
    width: 100%;
    max-width: 450px;
    background: var(--white);
    padding: 30px 38px 25px;
    border-radius: 20px;
    position: relative;
    z-index: 2;
    border: 1px solid var(--border);
    box-shadow: 0 15px 40px rgba(20, 33, 61, 0.08);
}

/* LOGO */

.logo {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-bottom: 23px;
}

.logo-icon {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 21px;
    box-shadow: 0 6px 15px rgba(20, 33, 61, 0.17);
}

.logo-text h1 {
    font-size: 25px;
    color: var(--navy);
    font-weight: 800;
    letter-spacing: 2px;
}

.logo-text p {
    font-size: 9px;
    color: var(--muted);
    letter-spacing: 2px;
    font-weight: 700;
    margin-top: 3px;
}

/* BAGIAN SAMBUTAN */

.welcome {
    text-align: center;
    margin-bottom: 23px;
}

.welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #E8F0FC;
    color: var(--navy);
    padding: 7px 13px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 13px;
}

.welcome h2 {
    font-size: 25px;
    color: var(--navy);
    font-weight: 800;
    margin-bottom: 8px;
}

.welcome p {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.7;
}

/* PESAN NOTIFIKASI */

.alert {
    padding: 10px 12px;
    border-radius: 9px;
    font-size: 11px;
    margin-bottom: 14px;
    line-height: 1.5;
}

.alert-success {
    color: #047857;
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
}

.alert-danger {
    color: #B91C1C;
    background: #FEF2F2;
    border: 1px solid #FECACA;
}

/* FORM INPUT */

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    color: #263957;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 8px;
}

.input-box {
    position: relative;
}

.input-box .input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #8495AF;
    font-size: 14px;
    pointer-events: none;
}

.input-box input {
    width: 100%;
    height: 47px;
    border: 1px solid #D8E0EC;
    background: var(--input-bg);
    border-radius: 10px;
    padding: 0 43px;
    font-size: 12px;
    color: var(--text);
    outline: none;
    transition: all .25s;
}

.input-box input:focus {
    background: #E8F0FC;
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(35, 68, 125, 0.09);
}

.input-box input::placeholder {
    color: #9AA8BA;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: none;
    color: #8495AF;
    cursor: pointer;
    font-size: 14px;
    transition: .2s;
}

.toggle-password:hover {
    color: var(--navy);
}

/* TOMBOL MASUK */

.btn-login {
    width: 100%;
    height: 48px;
    border: none;
    border-radius: 9px;
    background: var(--navy);
    color: var(--white);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 5px;
    transition: all .25s;
    box-shadow: 0 6px 15px rgba(35, 68, 125, 0.15);
}

.btn-login:hover {
    background: var(--blue-hover);
    transform: translateY(-2px);
    box-shadow: 0 9px 20px rgba(35, 68, 125, 0.20);
}

.btn-login:disabled {
    background: #94A3B8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* INFORMASI KEAMANAN */

.security {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
    color: #8999B0;
    font-size: 10px;
    margin-top: 17px;
}

.security i {
    color: #059669;
}

/* BAGIAN FOOTER */

.footer {
    text-align: center;
    margin-top: 19px;
    padding-top: 15px;
    border-top: 1px solid #EDF0F5;
    color: #8999B0;
    font-size: 10px;
}

/* UKURAN LAYAR PENDEK */

@media (max-height: 760px) {
    .login-container {
        padding: 22px 35px 18px;
    }

    .logo {
        margin-bottom: 15px;
    }

    .logo-icon {
        width: 42px;
        height: 42px;
    }

    .logo-text h1 {
        font-size: 22px;
    }

    .welcome {
        margin-bottom: 16px;
    }

    .welcome h2 {
        font-size: 22px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .input-box input {
        height: 43px;
    }

    .btn-login {
        height: 44px;
    }

    .security {
        margin-top: 12px;
    }

    .footer {
        margin-top: 13px;
        padding-top: 11px;
    }
}

/* TAMPILAN MOBILE */

@media (max-width: 520px) {
    body {
        padding: 12px;
        overflow-y: auto;
    }

    .login-container {
        padding: 27px 23px;
    }

    .welcome h2 {
        font-size: 23px;
    }

    .circle-one {
        width: 250px;
        height: 250px;
    }

    .circle-two {
        width: 280px;
        height: 280px;
    }
}

@media (max-height: 620px) {
    body {
        overflow-y: auto;
        align-items: flex-start;
    }
}

</style>
</head>

<body>

<div class="circle circle-one"></div>
<div class="circle circle-two"></div>

<div class="login-container">

    <!-- LOGO -->
    <div class="logo">

        <div class="logo-icon">
            <i class="fa-solid fa-water"></i>
        </div>

        <div class="logo-text">
            <h1>NEXPOOL</h1>
            <p>SMART TOURISM SYSTEM</p>
        </div>

    </div>

    <!-- SAMBUTAN -->
    <div class="welcome">

        <div class="welcome-badge">
            <i class="fa-solid fa-shield-halved"></i>
            AKSES ADMINISTRATOR
        </div>

        <h2>Selamat Datang!</h2>

        <p>
            Silakan masuk ke akun administrator untuk mengelola
            seluruh aktivitas operasional NEXPOOL.
        </p>

    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- NOTIFIKASI GAGAL -->
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- KESALAHAN VALIDASI -->
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <!-- FORM LOGIN -->
    <form id="loginForm"
          action="{{ route('login.process') }}"
          method="POST">

        @csrf

        <!-- EMAIL -->
        <div class="form-group">

            <label for="email">EMAIL ADMINISTRATOR</label>

            <div class="input-box">

                <i class="fa-regular fa-envelope input-icon"></i>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan alamat email"
                    autocomplete="username"
                    required
                >

            </div>

        </div>

        <!-- PASSWORD -->
        <div class="form-group">

            <label for="password">KATA SANDI</label>

            <div class="input-box">

                <i class="fa-solid fa-lock input-icon"></i>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    autocomplete="current-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    id="togglePassword"
                    aria-label="Tampilkan kata sandi"
                >
                    <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                </button>

            </div>

        </div>

        <!-- TOMBOL MASUK -->
        <button type="submit" id="loginBtn" class="btn-login">

            <span id="loginText">Masuk ke Dashboard</span>

            <i class="fa-solid fa-arrow-right"
               style="margin-left:8px"></i>

        </button>

    </form>

    <!-- INFORMASI KEAMANAN -->
    <div class="security">
        <i class="fa-solid fa-lock"></i>
        Autentikasi Administrator Terlindungi
    </div>

    <!-- FOOTER -->
    <div class="footer">
        © 2026 NEXPOOL. Hak Cipta Dilindungi.
    </div>

</div>

<script>

// =====================================
// TAMPILKAN / SEMBUNYIKAN KATA SANDI
// =====================================

const toggleBtn = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const toggleIcon = document.getElementById('togglePasswordIcon');

toggleBtn.addEventListener('click', function() {

    const isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';

    toggleIcon.classList.toggle('fa-eye', !isPassword);
    toggleIcon.classList.toggle('fa-eye-slash', isPassword);

    toggleBtn.setAttribute(
        'aria-label',
        isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
    );

});

// =====================================
// PROSES PENGIRIMAN FORM LOGIN
// =====================================

document.getElementById('loginForm').addEventListener('submit', function() {

    if (!this.checkValidity()) return;

    const btn = document.getElementById('loginBtn');

    document.getElementById('loginText').innerText = 'Sedang memproses...';

    btn.disabled = true;

});

</script>

</body>
</html>
