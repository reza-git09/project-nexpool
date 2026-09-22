<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Edit Harga Tiket - NEXPOOL</title>

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

        /* SIDEBAR */
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

        /* MAIN */
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
            padding: 0 35px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
            color: #1f2937;
        }

        .admin-info {
            text-align: right;
        }

        .admin-info strong {
            display: block;
            color: #111827;
            font-size: 14px;
        }

        .admin-info span {
            font-size: 12px;
            color: #6b7280;
        }

        .content {
            padding: 35px;
        }

        /* Back Button Style ala Halaman Fasilitas */
        .btn-back {
            display: inline-flex;
            align-items: center;
            background: #e5e7eb;
            color: #374151;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            font-size: 25px;
            color: #111827;
            margin-bottom: 7px;
        }

        .page-title p {
            color: #7b8494;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 700px;
            border: 1px solid #e8ebf0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }

        input:focus,
        select:focus {
            border-color: #2563eb;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
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

            <a href="{{ route('reservasi.index') }}">
                <i class="fa-solid fa-calendar-check" style="color:#10b981;width:18px;text-align:center;font-size:15px;"></i> <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}">
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
            <h3>Edit Harga Tiket</h3>

            <div class="admin-info">
                <strong>{{ session('admin_nama') }}</strong>
                <span>{{ session('admin_pool_id') }}</span>
            </div>
        </header>

        <section class="content">

            <!-- Tombol Kembali di Kiri Atas -->
            <a href="{{ route('harga-tiket.index') }}" class="btn-back">
                &larr; Kembali ke Manajemen Tiket
            </a>

            <div class="page-title">
                <h1>Edit Harga Tiket</h1>
                <p>Perbarui informasi harga tiket kolam renang.</p>
            </div>

            <div class="card">

                <form action="{{ route('harga-tiket.update', $hargaTiket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- POOL ID -->
                    <div class="form-group">
                        <label for="pool_id">Pool ID</label>
                        <input
                            type="text"
                            id="pool_id"
                            name="pool_id"
                            value="{{ old('pool_id', $hargaTiket->pool_id) }}"
                        >
                        @error('pool_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- KATEGORI -->
                    <div class="form-group">
                        <label for="kategori">Kategori Tiket</label>
                        <select id="kategori" name="kategori">
                            <option value="Dewasa" {{ old('kategori', $hargaTiket->kategori) == 'Dewasa' ? 'selected' : '' }}>
                                Dewasa
                            </option>
                            <option value="Anak" {{ old('kategori', $hargaTiket->kategori) == 'Anak' ? 'selected' : '' }}>
                                Anak
                            </option>
                        </select>
                        @error('kategori')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- JENIS HARI -->
                    <div class="form-group">
                        <label for="jenis_hari">Jenis Hari</label>
                        <select id="jenis_hari" name="jenis_hari">
                            <option value="Weekday" {{ old('jenis_hari', $hargaTiket->jenis_hari) == 'Weekday' ? 'selected' : '' }}>
                                Weekday
                            </option>
                            <option value="Weekend" {{ old('jenis_hari', $hargaTiket->jenis_hari) == 'Weekend' ? 'selected' : '' }}>
                                Weekend
                            </option>
                        </select>
                        @error('jenis_hari')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- HARGA -->
                    <div class="form-group">
                        <label for="harga">Harga Tiket</label>
                        <input
                            type="number"
                            id="harga"
                            name="harga"
                            value="{{ old('harga', $hargaTiket->harga) }}"
                            min="0"
                        >
                        @error('harga')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BUTTON UPDATE -->
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            Update Harga
                        </button>
                    </div>

                </form>

            </div>

        </section>

    </main>

</body>

</html>