```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Edit Promo - NEXPOOL</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f6f8fc;
            color: #172033;
        }

        a {
            text-decoration: none;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;
            background: linear-gradient(180deg,#101c36 0%,#14213d 55%,#101b32 100%);
            color: white;
            padding: 24px 16px;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(15,23,42,.08);
        }

        .logo {
            padding: 6px 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 25px;
            text-align: center;
        }

        .logo h2 {
            font-size: 22px;
        }

        .logo p {
            font-size: 10px;
            color: #91a0b9;
            margin-top: 5px;
            letter-spacing: 1.3px;
        }

        .menu-title {
            padding: 0 12px;
            margin-bottom: 10px;
            font-size: 10px;
            font-weight: bold;
            color: #73819b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 13px;
            transition: .2s ease;
        }

        .menu a:hover {
            transform: translateX(2px);
            background: rgba(255,255,255,.05);
        }

        .menu a.active {
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
            color: white;
            box-shadow: 0 6px 18px rgba(37,99,235,.25);
        }

        .menu a.active::before {
            content: "";
            position: absolute;
            left: -16px;
            top: 8px;
            width: 3px;
            height: calc(100% - 16px);
            background: #60a5fa;
            border-radius: 0 4px 4px 0;
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            left: 16px;
            right: 16px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .logout a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            color: #ffb4b4;
            border-radius: 10px;
            font-size: 13px;
        }

        .logout a:hover {
            background: rgba(255,255,255,.05);
        }

        .logout a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* MAIN */
        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .header {
            height: 76px;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            border-bottom: 1px solid #e8ecf3;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header-left h3 {
            font-size: 18px;
            color: #172033;
        }

        .header-left p {
            font-size: 11px;
            color: #7b8494;
            margin-top: 3px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-text {
            text-align: right;
        }

        .admin-text strong {
            display: block;
            font-size: 13px;
            color: #172033;
        }

        .admin-text span {
            display: block;
            font-size: 11px;
            color: #7b8494;
            margin-top: 2px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg,#2563eb,#38bdf8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(37,99,235,.2);
        }

        /* CONTENT */
        .content {
            padding: 32px;
            max-width: 1700px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: linear-gradient(135deg,#dbeafe,#eff6ff);
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .page-header h1 {
            font-size: 25px;
            color: #172033;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 13px;
        }

        /* BACK BUTTON */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #e8edf5;
            color: #475569;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: .2s ease;
        }

        .btn-back:hover {
            background: #dce3ed;
        }

        /* FORM */
        .form-card {
            background: white;
            border: 1px solid #e8ecf3;
            border-radius: 16px;
            padding: 25px;
            max-width: 900px;
            box-shadow: 0 3px 12px rgba(15,23,42,.025);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: #172033;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d9dee7;
            border-radius: 9px;
            font-size: 12px;
            color: #172033;
            outline: none;
            background: white;
            transition: .2s ease;
        }

        .form-group input,
        .form-group select {
            height: 44px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.08);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
            line-height: 1.5;
        }

        .error-box {
            background: #fee2e2;
            color: #dc2626;
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #fecaca;
        }

        .error-box strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .field-error {
            color: #dc2626;
            font-size: 11px;
            margin-top: 5px;
            line-height: 1.4;
            display: none;
        }

        /* BUTTON */
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: .2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg,#2563eb,#1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(37,99,235,.18);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37,99,235,.25);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* RESPONSIVE */
        @media (max-width:1000px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }
        }

        @media (max-width:800px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo h2 {
                font-size: 15px;
            }

            .logo p,
            .menu-title,
            .menu a span,
            .logout a span {
                display: none;
            }

            .menu a,
            .logout a {
                justify-content: center;
                padding: 12px;
            }

            .menu a.active::before {
                left: -10px;
            }

            .main {
                margin-left: 70px;
            }

            .header {
                padding: 0 20px;
            }

            .admin-text {
                display: none;
            }

            .content {
                padding: 25px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }

        @media (max-width:600px) {
            .content {
                padding: 20px 15px;
            }

            .form-card {
                padding: 20px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .page-title {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">Menu Utama</div>

        <div class="menu">
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge-high" style="color:#3b82f6;"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket" style="color:#f59e0b;"></i>
                <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming" style="color:#06b6d4;"></i>
                <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}">
                <i class="fa-solid fa-calendar-check" style="color:#10b981;"></i>
                <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}" class="active">
                <i class="fa-solid fa-tags" style="color:#8b5cf6;"></i>
                <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}">
                <i class="fa-solid fa-star" style="color:#eab308;"></i>
                <span>Review</span>
            </a>
        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket" style="color:#ef4444;"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">
            <div class="header-left">
                <h3>Edit Promo</h3>
                <p>Kelola informasi promo NEXPOOL</p>
            </div>

            <div class="admin-info">
                <div class="admin-text">
                    <strong>{{ session('admin_nama', 'Admin NEXPOOL') }}</strong>
                    <span>{{ session('admin_pool_id', 'Pool ID') }}</span>
                </div>

                <div class="avatar">
                    {{ strtoupper(substr(session('admin_nama', 'A'), 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content">

            <a href="{{ route('promo.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Promo
            </a>

            <div class="page-header">
                <div class="page-title">
                    <div class="page-icon">
                        <i class="fa-solid fa-tags"></i>
                    </div>

                    <div>
                        <h1>Edit Promo</h1>
                        <p>Perbarui informasi promo dan diskon kolam renang.</p>
                    </div>
                </div>
            </div>

            <div class="form-card">

                @if ($errors->any())
                    <div class="error-box">
                        <strong>Terjadi kesalahan:</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="formPromo" action="{{ route('promo.update', $promo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">

                        <!-- NAMA PROMO -->
                        <div class="form-group full">
                            <label>Nama Promo</label>
                            <input
                                type="text"
                                name="nama_promo"
                                value="{{ old('nama_promo', $promo->nama_promo) }}"
                                placeholder="Masukkan nama promo"
                                required
                            >
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="form-group full">
                            <label>Deskripsi Promo</label>
                            <textarea
                                name="deskripsi"
                                placeholder="Tuliskan detail deskripsi promo..."
                            >{{ old('deskripsi', $promo->deskripsi) }}</textarea>
                        </div>

                        <!-- JENIS DISKON -->
                        <div class="form-group">
                            <label>Jenis Diskon</label>
                            <select name="jenis_diskon" required>
                                <option value="Persentase" selected>
                                    Persentase (%)
                                </option>
                            </select>
                        </div>

                        <!-- NILAI DISKON -->
                        <div class="form-group">
                            <label>Nilai Diskon</label>

                            <input
                                type="number"
                                id="nilai_diskon"
                                name="nilai_diskon"
                                value="{{ old('nilai_diskon', $promo->nilai_diskon) }}"
                                min="0"
                                max="100"
                                step="1"
                                placeholder="Contoh: 20 (maks. 100%)"
                                required
                                oninput="validateDiskon(this)"
                                onkeydown="blockSymbol(event)"
                            >

                            <div id="error-diskon" class="field-error"></div>
                        </div>

                        <!-- TANGGAL MULAI -->
                        <div class="form-group">
                            <label>Tanggal Mulai</label>

                            <input
                                type="date"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', optional($promo->tanggal_mulai)->format('Y-m-d')) }}"
                                required
                                onchange="validateTanggal()"
                            >
                        </div>

                        <!-- TANGGAL SELESAI -->
                        <div class="form-group">
                            <label>Tanggal Selesai</label>

                            <input
                                type="date"
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', optional($promo->tanggal_selesai)->format('Y-m-d')) }}"
                                required
                                onchange="validateTanggal()"
                            >

                            <div id="error-tanggal" class="field-error"></div>
                        </div>

                        <!-- STATUS -->
                        <div class="form-group full">
                            <label>Status Promo</label>

                            <select name="status" required>
                                <option value="1"
                                    {{ old('status', $promo->status ? '1' : '0') == '1' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="0"
                                    {{ old('status', $promo->status ? '1' : '0') == '0' ? 'selected' : '' }}>
                                    Tidak Aktif
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Promo
                        </button>

                        <a href="{{ route('promo.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </section>
    </main>

    <script>
        const nilaiDiskonEl = document.getElementById('nilai_diskon');
        const errorDiskon = document.getElementById('error-diskon');

        function blockSymbol(e) {
            const blocked = ['+', '-', 'e', 'E', '.', ','];

            if (blocked.includes(e.key)) {
                e.preventDefault();

                showDiskonError(
                    'Nilai diskon tidak boleh mengandung simbol. Masukkan angka saja.'
                );
            } else {
                hideDiskonError();
            }
        }

        function validateDiskon(input) {
            let value = input.value;

            if (value === '') {
                hideDiskonError();
                return;
            }

            value = parseInt(value);

            if (value > 100) {
                showDiskonError(
                    'Nilai diskon persentase tidak boleh melebihi 100%.'
                );

                input.value = 100;
            } else if (value < 0) {
                showDiskonError(
                    'Nilai diskon tidak boleh negatif.'
                );

                input.value = 0;
            } else {
                hideDiskonError();
            }
        }

        function showDiskonError(message) {
            errorDiskon.textContent = message;
            errorDiskon.style.display = 'block';
        }

        function hideDiskonError() {
            errorDiskon.textContent = '';
            errorDiskon.style.display = 'none';
        }

        // Validasi tanggal
        const mulaiEl = document.getElementById('tanggal_mulai');
        const selesaiEl = document.getElementById('tanggal_selesai');
        const errorTgl = document.getElementById('error-tanggal');

        function validateTanggal() {
            const mulai = mulaiEl.value;
            const selesai = selesaiEl.value;

            if (!mulai || !selesai) {
                hideTglError();
                return;
            }

            const tMulai = new Date(mulai);
            const tSelesai = new Date(selesai);

            const diffMs = tSelesai - tMulai;
            const diffDays = diffMs / (1000 * 60 * 60 * 24);

            const maxSelesai = new Date(tMulai);
            maxSelesai.setMonth(maxSelesai.getMonth() + 1);

            if (diffDays <= 0) {
                showTglError(
                    'Tanggal Selesai harus minimal 1 hari setelah Tanggal Mulai.'
                );

                selesaiEl.value = '';
            } else if (tSelesai > maxSelesai) {
                showTglError(
                    'Tanggal Selesai tidak boleh lebih dari 1 bulan setelah Tanggal Mulai.'
                );

                selesaiEl.value = '';
            } else {
                hideTglError();
            }
        }

        function showTglError(message) {
            errorTgl.textContent = message;
            errorTgl.style.display = 'block';
        }

        function hideTglError() {
            errorTgl.textContent = '';
            errorTgl.style.display = 'none';
        }

        // Validasi sebelum submit
        document.getElementById('formPromo').addEventListener('submit', function(e) {

            validateDiskon(nilaiDiskonEl);
            validateTanggal();

            if (
                errorDiskon.style.display !== 'none' ||
                errorTgl.style.display !== 'none'
            ) {
                e.preventDefault();

                alert('Harap perbaiki data promo terlebih dahulu.');
                return;
            }

            if (nilaiDiskonEl.value === '') {
                e.preventDefault();
                alert('Nilai diskon wajib diisi.');
            }

        });
    </script>

</body>
</html>
```
