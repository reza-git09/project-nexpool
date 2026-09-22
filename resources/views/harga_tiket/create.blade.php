<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Tambah Harga Tiket - NEXPOOL</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #1f2937;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #14213d;
            color: white;
            padding: 25px 15px;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h2 {
            font-size: 26px;
            letter-spacing: 1px;
        }

        .logo p {
            font-size: 12px;
            color: #aeb9cc;
            margin-top: 5px;
        }

        .menu-title {
            font-size: 11px;
            color: #8491a7;
            margin: 20px 12px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #dce3ef;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-size: 14px;
        }

        .menu a:hover,
        .menu a.active {
            background: #2563eb;
            color: white;
        }

        .logout {
            position: absolute;
            bottom: 25px;
            left: 15px;
            right: 15px;
        }

        .logout a {
            display: block;
            text-decoration: none;
            color: #ffb4b4;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        .header {
            height: 75px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-meta {
            text-align: right;
        }

        .user-meta strong {
            display: block;
            color: #111827;
            font-size: 14px;
        }

        .user-meta span {
            font-size: 12px;
            color: #6b7280;
        }

        .avatar {
            width: 38px;
            height: 38px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 15px;
        }

        .content {
            padding: 30px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
        }

        /* TOMBOL KEMBALI */
        .btn-kembali {
            display: inline-block;
            margin-bottom: 18px;
            padding: 10px 16px;
            background: #e5e7eb;
            color: #1e3c72;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-kembali:hover {
            background: #d1d5db;
        }

        .form-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 25px;
            max-width: 750px;
        }

        /* NOTIFIKASI */
        .alert {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d9dee7;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2563eb;
        }

        .form-group input.input-warning {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .form-group input.input-error {
            border-color: #dc2626;
            background: #fef2f2;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        .harga-info {
            color: #6b7280;
            font-size: 12px;
            margin-top: 6px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
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

            <a href="{{ url('/dashboard') }}">
                <i class="fa-solid fa-gauge-high" style="color:#3b82f6;width:18px;text-align:center;font-size:15px;"></i> <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}" class="active">
                <i class="fa-solid fa-ticket" style="color:#f59e0b;width:18px;text-align:center;font-size:15px;"></i> <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming" style="color:#06b6d4;width:18px;text-align:center;font-size:15px;"></i> <span>Fasilitas</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-calendar-check" style="color:#10b981;width:18px;text-align:center;font-size:15px;"></i> <span>Reservasi</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-tags" style="color:#8b5cf6;width:18px;text-align:center;font-size:15px;"></i> <span>Promo</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-star" style="color:#eab308;width:18px;text-align:center;font-size:15px;"></i> <span>Review</span>
            </a>

        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket" style="color:#ef4444;width:18px;text-align:center;font-size:15px;"></i> <span>Logout</span>
            </a>
        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <header class="header">
            <h3>Manajemen Harga Tiket</h3>

            <div class="user-profile">
                <div class="user-meta">
                    <strong>Admin Tiara Park</strong>
                    <span>pool_id_01</span>
                </div>

                <div class="avatar">
                    A
                </div>
            </div>
        </header>


        <section class="content">

            <!-- TOMBOL KEMBALI -->
            <a href="{{ route('harga-tiket.index') }}" class="btn-kembali">
                ← Kembali ke Manajemen Tiket
            </a>


            <div class="page-header">
                <h1>Tambah Harga Tiket</h1>
                <p>Tambahkan harga tiket baru untuk kolam renang.</p>
            </div>


            <!-- NOTIFIKASI VALIDASI -->
            @if ($errors->any())
                <div class="alert">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif

            <!-- FORM -->
            <div class="form-card">

                <form id="hargaForm"
                    action="{{ route('harga-tiket.store') }}"
                    method="POST">

                    @csrf


                    <!-- KATEGORI -->
                    <div class="form-group">

                        <label>Kategori Tiket</label>

                        <select name="kategori" required>

                            <option value="">-- Pilih Kategori --</option>

                            <option value="Dewasa"
                                {{ old('kategori') == 'Dewasa' ? 'selected' : '' }}>
                                Dewasa
                            </option>

                            <option value="Anak"
                                {{ old('kategori') == 'Anak' ? 'selected' : '' }}>
                                Anak
                            </option>

                        </select>

                        @error('kategori')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- JENIS HARI -->
                    <div class="form-group">

                        <label>Jenis Hari</label>

                        <select name="jenis_hari" required>

                            <option value="">-- Pilih Jenis Hari --</option>

                            <option value="Weekday"
                                {{ old('jenis_hari') == 'Weekday' ? 'selected' : '' }}>
                                Weekday
                            </option>

                            <option value="Weekend"
                                {{ old('jenis_hari') == 'Weekend' ? 'selected' : '' }}>
                                Weekend
                            </option>

                        </select>

                        @error('jenis_hari')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- HARGA -->
                    <div class="form-group">

                        <label>Harga Tiket</label>

                        <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="{{ old('harga') }}"
                        placeholder="Contoh: 5000"
                        min="0"
                        step="0.01"
                        required
                    >
                        <div class="harga-info">
                            Maksimal harga yang dapat dimasukkan: Rp999.999,99
                        </div>
                        @error('harga')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- BUTTON -->
                    <div class="buttons">

                        <button type="submit"
                            class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                        </button>

                        <a href="{{ route('harga-tiket.index') }}"
                            class="btn btn-secondary">
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </section>

    </main>




</body>

</html>