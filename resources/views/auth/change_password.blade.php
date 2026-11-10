<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Ganti Password - NEXPOOL</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            min-height: 100vh; display: flex; justify-content: center; align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298); padding: 20px;
        }
        .card {
            width: 100%; max-width: 420px; background: white;
            padding: 35px 30px; border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
        }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo h1 { color: #1e3c72; font-size: 28px; margin-bottom: 4px; }
        .logo p { color: #666; font-size: 13px; }
        .warning-box {
            background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px;
            padding: 12px 15px; margin-bottom: 20px; font-size: 13px; color: #92400e;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; font-size: 13px; font-weight: bold; }
        .password-wrapper { position: relative; }
        .password-wrapper input {
            width: 100%; padding: 11px 42px 11px 12px;
            border: 1px solid #ddd; border-radius: 8px; font-size: 14px;
            outline: none; transition: border-color .2s;
        }
        .password-wrapper input:focus { border-color: #1e3c72; }
        .password-wrapper input.is-invalid { border-color: #dc2626; }
        .toggle-pw {
            position: absolute; top: 50%; right: 12px; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: #888; font-size: 15px;
        }
        .toggle-pw:hover { color: #1e3c72; }
        .error-message { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }
        .btn { width: 100%; padding: 13px; border: none; border-radius: 8px; background: #1e3c72;
               color: #fff; font-size: 15px; font-weight: bold; cursor: pointer; transition: background .2s; margin-top: 8px; }
        .btn:hover { background: #16315f; }
        .btn:disabled { background: #94a3b8; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <h1>NEXPOOL</h1>
        <p>Buat Password Baru</p>
    </div>

    <div class="warning-box">
        <i class="fa fa-shield-halved"></i>
        <strong>Keamanan Akun:</strong> Ini adalah pertama kali Anda login. Harap ganti password sementara Anda sekarang untuk melindungi akun.
    </div>

    @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #f87171;border-radius:8px;padding:12px;margin-bottom:16px;font-size:13px;color:#991b1b;">
            <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form id="cpForm" action="{{ route('admin.change-password.process') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Password Baru</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password"
                       placeholder="Minimal 8 karakter"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" class="toggle-pw" data-target="password">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <div class="password-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Ulangi password baru">
                <button type="button" class="toggle-pw" data-target="password_confirmation">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn">
            <i class="fa fa-lock"></i> Simpan Password Baru
        </button>
    </form>
</div>
<script>
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', function() {
            const id    = this.dataset.target;
            const input = document.getElementById(id);
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    document.getElementById('cpForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...';
    });
</script>
</body>
</html>

