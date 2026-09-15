<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Promo - NEXPOOL</title>

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
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
        }

        .content {
            padding: 30px;
        }

        /* KEMBALI */
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
        }

        .btn-kembali:hover {
            background: #d1d5db;
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

        /* FORM */
        .form-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 25px;
            max-width: 750px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d9dee7;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
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
            font-weight: 600;
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

        @media (max-width: 800px) {
            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
            }

            .form-row {
                grid-template-columns: 1fr;
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

            <a href="{{ url('/dashboard') }}">
                ▣ <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                🏊 <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}">
                📋 <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}" class="active">
                🏷 <span>Promo</span>
            </a>

            <a href="#">
                ⭐ <span>Review</span>
            </a>

        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                ↪ <span>Logout</span>
            </a>
        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <header class="header">
            <h3>Tambah Promo</h3>
        </header>

        <section class="content">

            <!-- KEMBALI -->
            <a href="{{ route('promo.index') }}" class="btn-kembali">
                ← Kembali ke Promo
            </a>

            <div class="page-header">
                <h1>Tambah Promo</h1>
                <p>Tambahkan data promo diskon baru untuk pengunjung NEXPOOL.</p>
            </div>


            <!-- FORM -->
            <div class="form-card">

                <form action="{{ route('promo.store') }}" method="POST">

                    @csrf

                    <!-- POOL ID & NAMA PROMO -->
                    <div class="form-row">

                        <div class="form-group">
                            <label>Pool ID</label>
                            <input
                                type="text"
                                name="pool_id"
                                value="{{ old('pool_id', 'pool_id_01') }}"
                                placeholder="Contoh: pool_id_01"
                                required
                            >
                            @error('pool_id')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Promo</label>
                            <input
                                type="text"
                                name="nama_promo"
                                value="{{ old('nama_promo') }}"
                                placeholder="Contoh: Promo Liburan Hemat"
                                required
                            >
                            @error('nama_promo')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="form-group">
                        <label>Deskripsi Promo</label>
                        <textarea
                            name="deskripsi"
                            placeholder="Masukkan deskripsi promo..."
                        >{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- JENIS DISKON & NILAI DISKON -->
                    <div class="form-row">

                        <div class="form-group">
                            <label>Jenis Diskon</label>
                            <select name="jenis_diskon" required>
                                <option value="">-- Pilih Jenis Diskon --</option>
                                <option value="Persentase" {{ old('jenis_diskon') == 'Persentase' ? 'selected' : '' }}>
                                    Persentase (%)
                                </option>
                                <option value="Nominal" {{ old('jenis_diskon') == 'Nominal' ? 'selected' : '' }}>
                                    Nominal (Rp)
                                </option>
                            </select>
                            @error('jenis_diskon')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nilai Diskon</label>
                            <input
                                type="number"
                                name="nilai_diskon"
                                value="{{ old('nilai_diskon') }}"
                                min="0"
                                step="0.01"
                                placeholder="Contoh: 20 atau 10000"
                                required
                            >
                            @error('nilai_diskon')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    <!-- TANGGAL MULAI & TANGGAL SELESAI -->
                    <div class="form-row">

                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input
                                type="date"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai') }}"
                                required
                            >
                            @error('tanggal_mulai')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tanggal Selesai</label>
                            <input
                                type="date"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                required
                            >
                            @error('tanggal_selesai')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    <!-- STATUS PROMO -->
                    <div class="form-group">
                        <label>Status Promo</label>
                        <select name="status" required>
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- BUTTON -->
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            💾 Simpan Promo
                        </button>
                        <a href="{{ route('promo.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>

        </section>

    </main>

</body>

</html>