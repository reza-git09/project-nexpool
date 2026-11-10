<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Pengajuan Admin - NEXPOOL</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 30px 20px;
        }

        .register-container {
            width: 100%;
            max-width: 460px;
            background: white;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo { text-align: center; margin-bottom: 24px; }
        .logo h1 { color: #1e3c72; font-size: 30px; margin-bottom: 4px; }
        .logo p { color: #666; font-size: 13px; }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.5;
        }
        .alert-success { background: #d1fae5; border: 1px solid #34d399; color: #065f46; }
        .alert-error   { background: #fee2e2; border: 1px solid #f87171; color: #991b1b; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; font-size: 13px; font-weight: bold; }

        .form-group input,
        .form-group select {
            width: 100%; padding: 11px 12px;
            border: 1px solid #ddd; border-radius: 8px;
            font-size: 14px; outline: none; background: #fff;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus { border-color: #1e3c72; }

        .form-group input.is-invalid,
        .form-group select.is-invalid { border-color: #dc2626; }

        .form-group input[readonly] {
            background-color: #f1f5f9;
            color: #475569;
            cursor: not-allowed;
            border-color: #cbd5e1;
        }

        .error-message { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }

        .note {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 7px;
            padding: 10px 13px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 18px;
        }
        .note i { margin-right: 5px; }

        .btn-register {
            width: 100%; padding: 13px;
            border: none; border-radius: 8px;
            background: #1e3c72; color: white;
            font-size: 15px; font-weight: bold;
            cursor: pointer; transition: background 0.2s;
            margin-top: 10px;
        }
        .btn-register:hover { background: #16315f; }
        .btn-register:disabled { background: #94a3b8; cursor: not-allowed; }

        .login-link { text-align: center; margin-top: 18px; font-size: 13px; color: #555; }
        .login-link a { color: #1e3c72; font-weight: bold; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }

        .footer { text-align: center; margin-top: 20px; color: #999; font-size: 11px; }

        .form-row { display: flex; gap: 12px; }
        .form-row .form-group { flex: 1; }

        .helper-text { font-size: 11px; color: #888; margin-top: 3px; }
    </style>
</head>
<body>

<div class="register-container">

    <div class="logo">
        <h1>NEXPOOL</h1>
        <p>Pengajuan Akun Admin Wisata</p>
    </div>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Alert error global --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <i class="fa fa-exclamation-circle"></i> Mohon periksa kembali isian form.
        </div>
    @endif

    <form id="applyForm" action="{{ route('register.process') }}" method="POST" novalidate>
        @csrf

        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label for="full_name">Nama Lengkap Calon Admin <span style="color:#dc2626">*</span></label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                value="{{ old('full_name') }}"
                placeholder="Contoh: Budi Santoso"
                class="{{ $errors->has('full_name') ? 'is-invalid' : '' }}"
            >
            @error('full_name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Username --}}
        <div class="form-group">
            <label for="username">Username Calon Admin <span style="color:#dc2626">*</span></label>
            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="4–20 karakter, huruf/angka/underscore"
                class="{{ $errors->has('username') ? 'is-invalid' : '' }}"
                maxlength="20"
            >
            <span class="helper-text">Digunakan untuk login. Hanya huruf, angka, dan _ (underscore).</span>
            @error('username')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Email Aktif <span style="color:#dc2626">*</span></label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="email@contoh.com"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            >
            <span class="helper-text">Digunakan untuk menerima informasi akun setelah disetujui.</span>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nomor HP --}}
        <div class="form-group">
            <label for="phone">Nomor HP / WhatsApp <span style="color:#dc2626">*</span></label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="08xx atau 62xx (10–14 digit)"
                class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                maxlength="14"
            >
            @error('phone')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Jabatan --}}
        <div class="form-group">
            <label for="position">Jabatan / Posisi di Kolam <span style="color:#dc2626">*</span></label>
            <select id="position" name="position" class="{{ $errors->has('position') ? 'is-invalid' : '' }}">
                <option value="">Pilih Jabatan</option>
                <option value="kasir_tiket"         {{ old('position') == 'kasir_tiket'         ? 'selected' : '' }}>Kasir Tiket</option>
                <option value="pengelola_fasilitas" {{ old('position') == 'pengelola_fasilitas' ? 'selected' : '' }}>Pengelola Fasilitas</option>
                <option value="staf_admin"          {{ old('position') == 'staf_admin'          ? 'selected' : '' }}>Staf Admin</option>
            </select>
            @error('position')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Wisata Kolam Renang --}}
        <div class="form-group">
            <label for="pool_id">Wisata Kolam Renang <span style="color:#dc2626">*</span></label>
            <select id="pool_id" name="pool_id" class="{{ $errors->has('pool_id') ? 'is-invalid' : '' }}">
                <option value="">Pilih Wisata Kolam Renang</option>
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

        {{-- Kode Akses (read-only, terisi otomatis) --}}
        <div class="form-group">
            <label for="access_code">Kode Akses</label>
            <input
                type="text"
                id="access_code"
                name="access_code"
                readonly
                placeholder="Otomatis terisi setelah pilih wisata"
                value=""
            >
            <span class="helper-text">Terisi otomatis, tidak perlu diubah.</span>
            @error('access_code')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- Catatan password --}}
        <div class="note">
            <i class="fa fa-info-circle"></i>
            <strong>Tanpa password:</strong> Password sementara akan dikirim ke email Anda setelah pengajuan disetujui oleh Admin Master.
        </div>

        {{-- Submit --}}
        <button type="submit" id="submitBtn" class="btn-register">
            <i class="fa fa-paper-plane"></i> Kirim Pengajuan ke Admin Master
        </button>

    </form>

    <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
    </div>

    <div class="footer">NEXPOOL &copy; {{ date('Y') }}</div>

</div>

<script>
    // Mapping pool_id → kode akses (hanya untuk tampilan, verifikasi tetap di backend)
    const poolCodes = {
        'tiara_park':        'NXP-T01P',
        'kebon_agung':       'NXP-K02A',
        'annasya_waterpark': 'NXP-A03W',
        'dira_park':         'NXP-D04P',
        'jati_park':         'NXP-J05P',
    };

    const poolSelect   = document.getElementById('pool_id');
    const accessCode   = document.getElementById('access_code');

    function updateAccessCode() {
        const selected = poolSelect.value;
        accessCode.value = poolCodes[selected] || '';
    }

    poolSelect.addEventListener('change', updateAccessCode);

    // Set nilai awal jika old() ada
    updateAccessCode();

    // Hanya angka untuk input telepon
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Hanya karakter valid untuk username
    document.getElementById('username').addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    });

    // Disable tombol submit saat proses
    const applyForm = document.getElementById('applyForm');
    const submitBtn = document.getElementById('submitBtn');

    applyForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim...';
    });
</script>

</body>
</html>
