<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - NEXPOOL</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a7a 0%, #2a5298 100%);
        }
        .otp-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 24px;
            padding: 44px 40px 36px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .25);
        }
        .otp-card h1 { text-align: center; font-size: 34px; color: #1e3a7a; letter-spacing: 1px; }
        .otp-sub { text-align: center; color: #6b7280; margin: 4px 0 24px; font-size: 15px; }
        .otp-info {
            background: #eef3ff; border-radius: 12px; padding: 14px 16px;
            font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 20px;
        }
        .otp-info strong { color: #1e3a7a; }
        label { display: block; font-weight: 700; font-size: 15px; margin-bottom: 8px; color: #1f2937; }
        .otp-input {
            width: 100%; padding: 16px; border: 1px solid #d6dffa; border-radius: 12px;
            background: #e8f0fe; font-size: 30px; font-weight: 700; letter-spacing: 12px;
            text-align: center; color: #1e3a7a; outline: none;
        }
        .otp-input:focus { border-color: #1e3a7a; box-shadow: 0 0 0 3px rgba(30, 58, 122, .15); }
        .btn-main {
            width: 100%; margin-top: 20px; padding: 16px; border: 0; border-radius: 12px;
            background: #1e3a6e; color: #fff; font-size: 18px; font-weight: 700; cursor: pointer;
        }
        .btn-main:hover { background: #17305c; }
        .alert { border-radius: 10px; padding: 12px 14px; font-size: 14px; margin-bottom: 16px; }
        .alert-error { background: #fdecec; color: #b42318; border: 1px solid #f7c5c0; }
        .alert-ok { background: #e8f8ef; color: #167a3f; border: 1px solid #bfe8cf; }
        .otp-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; font-size: 14px; }
        .btn-link { background: none; border: 0; padding: 0; color: #1e3a7a; font-weight: 700; cursor: pointer; font-size: 14px; }
        .btn-link:disabled { color: #9ca3af; cursor: not-allowed; }
        .otp-actions a { color: #6b7280; text-decoration: none; }
        .otp-actions a:hover { color: #1e3a7a; }
    </style>
</head>
<body>
    <div class="otp-card">
        <h1>NEXPOOL</h1>
        <p class="otp-sub">Verifikasi Login Admin Master</p>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="alert alert-ok">{{ session('status') }}</div>
        @endif
        @if ($errors->has('code'))
            <div class="alert alert-error">{{ $errors->first('code') }}</div>
        @endif

        <div class="otp-info">
            Kami mengirim kode 6 angka ke <strong>{{ $maskedEmail }}</strong>.
            Kode berlaku {{ $ttlMinutes }} menit.
        </div>

        <form method="POST" action="{{ route('login.otp.verify') }}" autocomplete="off">
            @csrf
            <label for="code">Kode OTP</label>
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
            <button class="btn-main" type="submit">Verifikasi</button>
        </form>

        <div class="otp-actions">
            <form method="POST" action="{{ route('login.otp.resend') }}">
                @csrf
                <button class="btn-link" type="submit" id="resendBtn" data-wait="{{ $resendIn }}"
                        @disabled($resendIn > 0)>
                    Kirim ulang kode<span id="resendTimer"></span>
                </button>
            </form>
            <a href="{{ route('logout') }}">Batal, kembali ke login</a>
        </div>
    </div>

    <script>
        (function () {
            var btn = document.getElementById('resendBtn');
            var label = document.getElementById('resendTimer');
            var wait = parseInt(btn.getAttribute('data-wait'), 10) || 0;

            function tick() {
                if (wait > 0) {
                    btn.disabled = true;
                    label.textContent = ' (' + wait + ' dtk)';
                    wait--;
                    setTimeout(tick, 1000);
                } else {
                    btn.disabled = false;
                    label.textContent = '';
                }
            }
            tick();

            var input = document.getElementById('code');
            input.addEventListener('input', function () {
                input.value = input.value.replace(/\D/g, '').slice(0, 6);
            });
        })();
    </script>
</body>
</html>