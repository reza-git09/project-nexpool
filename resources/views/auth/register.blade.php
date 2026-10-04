<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Register Admin - NEXPOOL</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 440px;
            background: white;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 22px;
        }

        .logo h1 {
            color: #1e3c72;
            font-size: 30px;
            margin-bottom: 4px;
        }

        .logo p {
            color: #666;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-size: 13px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #fff;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #1e3c72;
        }

        .form-group input[readonly] {
            background-color: #f1f5f9;
            color: #475569;
            cursor: not-allowed;
            border-color: #cbd5e1;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 42px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #888;
            font-size: 15px;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #1e3c72;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .btn-register {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #1e3c72;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: #16315f;
        }

        .btn-register:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #555;
        }

        .login-link a {
            color: #1e3c72;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="register-container">

        <div class="logo">
            <h1>NEXPOOL</h1>
            <p>Registrasi Akun Admin Wisata</p>
        </div>

        <form id="registerForm" action="{{ route('register.process') }}" method="POST">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username (4-30 karakter)"
                    required
                >
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Wisata Kolam Renang -->
            <div class="form-group">
                <label for="pool_select">Nama Wisata Kolam Renang</label>
                <select id="pool_select" name="pool_id" required>
                    <option value="">Pilih wisata kolam renang</option>
                    @foreach($pools as $pool)
                        <option value="{{ $pool->pool_id }}" {{ old('pool_id') == $pool->pool_id ? 'selected' : '' }}>
                            {{ $pool->name }}
                        </option>
                    @endforeach
                </select>
                @error('pool_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pool ID (Readonly) -->
            <div class="form-group">
                <label for="pool_id_display">Pool ID</label>
                <input
                    type="text"
                    id="pool_id_display"
                    readonly
                    placeholder="Pilih wisata di atas dulu"
                    value="{{ old('pool_id') }}"
                >
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="password" aria-label="Toggle password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Toggle password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Kode Registrasi Wisata -->
            <div class="form-group">
                <label for="registration_code">Kode Registrasi Wisata</label>
                <input
                    type="text"
                    id="registration_code"
                    name="registration_code"
                    placeholder="Masukkan kode rahasia wisata"
                    required
                >
                @error('registration_code')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="btn-register">
                Daftar
            </button>

        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </div>

        <div class="footer">
            NEXPOOL &copy; 2026
        </div>

    </div>

<script>
    // 1. Auto Fill Pool ID Display on Select Change
    const poolSelect = document.getElementById('pool_select');
    const poolIdDisplay = document.getElementById('pool_id_display');

    function updatePoolIdDisplay() {
        poolIdDisplay.value = poolSelect.value || '';
    }

    poolSelect.addEventListener('change', updatePoolIdDisplay);
    updatePoolIdDisplay(); // Run initial setup for old input

    // 2. Toggle Password Visibility for both password fields
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // 3. Disable submit button on form submission to prevent double-submit
    const registerForm = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');

    registerForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerText = 'Memproses...';
    });
</script>

</body>
</html>
