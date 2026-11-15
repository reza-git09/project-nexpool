<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verifikasi OTP - NEXPOOL</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =====================================
   NEXPOOL VERIFIKASI OTP
   Ukuran dan jarak disamakan dengan halaman login
===================================== */

:root {
    --navy: #23447D;
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
    overflow-y: auto;
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

/* KARTU OTP */

.otp-card {
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

.alert-error {
    color: #B91C1C;
    background: #FEF2F2;
    border: 1px solid #FECACA;
}

.alert-ok {
    color: #047857;
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
}

/* INFORMASI EMAIL */

.otp-info {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 11px 13px;
    border-radius: 10px;
    background: #E8F0FC;
    border: 1px solid #D8E0EC;
    color: #526782;
    font-size: 11px;
    line-height: 1.6;
    margin-bottom: 16px;
}

.otp-info i {
    color: var(--navy);
    font-size: 17px;
    margin-top: 2px;
}

.otp-info strong {
    color: var(--navy);
    font-weight: 700;
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

.otp-input {
    width: 100%;
    height: 47px;
    border: 1px solid #D8E0EC;
    background: var(--input-bg);
    border-radius: 10px;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 12px;
    text-align: center;
    color: var(--navy);
    outline: none;
    transition: all .25s;
    padding-left: 12px;
}

.otp-input:focus {
    background: #E8F0FC;
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(35, 68, 125, 0.09);
}

.otp-input::placeholder {
    color: #9AA8BA;
    letter-spacing: 10px;
}

/* TOMBOL VERIFIKASI */

.btn-main {
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

.btn-main:hover {
    background: var(--blue-hover);
    transform: translateY(-2px);
    box-shadow: 0 9px 20px rgba(35, 68, 125, 0.20);
}

.btn-main:disabled {
    background: #94A3B8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* AKSI OTP */

.otp-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-top: 15px;
    font-size: 11px;
}

.btn-link {
    background: none;
    border: none;
    padding: 0;
    color: var(--navy);
    font-weight: 700;
    cursor: pointer;
    font-size: 11px;
}

.btn-link:hover {
    color: #41649C;
}

.btn-link:disabled {
    color: #9CA3AF;
    cursor: not-allowed;
}

.otp-actions a {
    color: #8999B0;
    text-decoration: none;
    font-size: 11px;
    transition: .2s;
}

.otp-actions a:hover {
    color: var(--navy);
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

/* UKURAN LAYAR PENDEK (sama persis dengan halaman login) */

@media (max-height: 760px) {
    .otp-card {
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

    .otp-info {
        padding: 9px 11px;
        margin-bottom: 12px;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .otp-input {
        height: 43px;
        font-size: 21px;
    }

    .btn-main {
        height: 44px;
    }

    .otp-actions {
        margin-top: 12px;
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
    }

    .otp-card {
        padding: 27px 23px;
    }

    .welcome h2 {
        font-size: 23px;
    }

    .otp-actions {
        flex-wrap: wrap;
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
        align-items: flex-start;
    }
}

</style>
</head>

<body>

<div class="circle circle-one"></div>
<div class="circle circle-two"></div>

<div class="otp-card">

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
            VERIFIKASI KEAMANAN
        </div>

        <h2>Verifikasi Akun</h2>

        <p>Masukkan kode verifikasi untuk melanjutkan.</p>

    </div>

    <!-- NOTIFIKASI ERROR -->
    @if (session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- NOTIFIKASI BERHASIL -->
    @if (session('status'))
        <div class="alert alert-ok">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- KESALAHAN VALIDASI -->
    @if ($errors->has('code'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first('code') }}
        </div>
    @endif

    <!-- INFORMASI EMAIL -->
    <div class="otp-info">
        <i class="fa-regular fa-envelope"></i>

        <div>
            Kode 6 digit telah dikirim ke
            <strong>{{ $maskedEmail }}</strong>.
            Berlaku selama
            <strong>{{ $ttlMinutes }} menit.</strong>
        </div>
    </div>

    <!-- FORM VERIFIKASI -->
    <form id="otpForm"
          method="POST"
          action="{{ route('login.otp.verify') }}"
          autocomplete="off">

        @csrf

        <div class="form-group">
            <label for="code">KODE VERIFIKASI OTP</label>

            <input
                class="otp-input"
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                autocomplete="one-time-code"
                placeholder="------"
                required
                autofocus
            >
        </div>

        <!-- TOMBOL VERIFIKASI -->
        <button class="btn-main" type="submit" id="verifyBtn">
            <span id="verifyText">Verifikasi dan Masuk</span>
            <i class="fa-solid fa-arrow-right" style="margin-left:8px"></i>
        </button>
    </form>

    <!-- AKSI OTP -->
    <div class="otp-actions">

        <form method="POST"
              action="{{ route('login.otp.resend') }}"
              id="resendForm">

            @csrf

            <button
                class="btn-link"
                type="submit"
                id="resendBtn"
                data-wait="{{ $resendIn }}"
                @disabled($resendIn > 0)
            >
                <i class="fa-solid fa-rotate-right"></i>
                Kirim Ulang Kode
                <span id="resendTimer"></span>
            </button>
        </form>

        <a href="{{ route('logout') }}">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Login
        </a>
    </div>

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
// HITUNG MUNDUR KIRIM ULANG OTP
(function () {
    const btn = document.getElementById('resendBtn');
    const label = document.getElementById('resendTimer');

    let wait = parseInt(btn.getAttribute('data-wait'), 10) || 0;

    function tick() {
        if (wait > 0) {
            btn.disabled = true;
            label.textContent = ' (' + wait + ' detik)';
            wait--;
            setTimeout(tick, 1000);
        } else {
            btn.disabled = false;
            label.textContent = '';
        }
    }

    tick();
})();

// INPUT OTP HANYA ANGKA
const input = document.getElementById('code');

input.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
});

// PROSES VERIFIKASI OTP
document.getElementById('otpForm').addEventListener('submit', function () {
    if (!this.checkValidity()) return;

    const btn = document.getElementById('verifyBtn');

    document.getElementById('verifyText').innerText = 'Sedang memverifikasi...';
    btn.disabled = true;
});

// PROSES KIRIM ULANG OTP
document.getElementById('resendForm').addEventListener('submit', function () {
    const btn = document.getElementById('resendBtn');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim kode...';
});

// Bila halaman dibuka lewat tombol "Kembali" browser, tombol kembali normal
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        document.getElementById('verifyBtn').disabled = false;
        document.getElementById('verifyText').innerText = 'Verifikasi dan Masuk';
    }
});
</script>

</body>
</html>
