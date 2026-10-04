<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Login - NEXPOOL</title>

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

        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            color: #1e3c72;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #777;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: bold;
            font-size: 13px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: white;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #1e3c72;
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
            color: #999;
            font-size: 15px;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #1e3c72;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #1e3c72;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 5px;
        }

        .btn-login:hover {
            background: #16315f;
        }

        .btn-login:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #555;
        }

        .register-link a {
            color: #1e3c72;
            font-weight: bold;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="logo">
            <h1>NEXPOOL</h1>
            <p>Website Management System</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.process') }}" method="POST">
             @csrf

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ session('registered_username', old('username')) }}"
                    placeholder="Masukkan username"
                    required
                >
            </div>

            <!-- Pool ID -->
            <div class="form-group">
                <label for="pool_id">Wisata / Pool ID</label>
                <select id="pool_id" name="pool_id" required>
                    <option value="">Pilih Wisata Kolam Renang</option>
                    @foreach($pools as $pool)
                        <option value="{{ $pool->pool_id }}"
                            {{ (session('registered_pool_id', old('pool_id')) == $pool->pool_id) ? 'selected' : '' }}>
                            {{ $pool->name }} ({{ $pool->pool_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                        <i class="fa fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" id="loginBtn" class="btn-login">
                Login
            </button>

        </form>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </div>

        <div class="footer">
            NEXPOOL &copy; 2026
        </div>

    </div>

<script>
    // 1. Password Visibility Toggle
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    toggleBtn.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleIcon.classList.toggle('fa-eye', !isPassword);
        toggleIcon.classList.toggle('fa-eye-slash', isPassword);
    });

    // 2. Disable Submit Button on Submission
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', function () {
        loginBtn.disabled = true;
        loginBtn.innerText = 'Memproses...';
    });
</script>

</body>
</html>