<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas - NEXPOOL</title>

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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d9dee7;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .form-group textarea {
            min-height: 110px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #2563eb;
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
                ▣ <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}" class="active">
                🏊 <span>Fasilitas</span>
            </a>

            <a href="#">
                📋 <span>Reservasi</span>
            </a>

            <a href="#">
                🏷️ <span>Promo</span>
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
            <h3>Tambah Fasilitas</h3>
        </header>

        <section class="content">

            <!-- TOMBOL KEMBALI -->
            <a href="{{ route('fasilitas.index') }}" class="btn-kembali">
                ← Kembali ke Fasilitas
            </a>

            <div class="page-header">
                <h1>Tambah Fasilitas</h1>
                <p>Tambahkan fasilitas baru untuk kolam renang.</p>
            </div>


            <!-- FORM -->
            <div class="form-card">

                <form action="{{ route('fasilitas.store') }}" method="POST">

                    @csrf

                    <!-- POOL ID -->
                    <div class="form-group">

                        <label>Pool ID</label>

                        <input
                            type="text"
                            name="pool_id"
                            value="{{ old('pool_id') }}"
                            placeholder="Contoh: pool_id_01"
                            required
                        >

                        @error('pool_id')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- NAMA FASILITAS -->
                    <div class="form-group">

                        <label>Nama Fasilitas</label>

                        <input
                            type="text"
                            name="nama_fasilitas"
                            value="{{ old('nama_fasilitas') }}"
                            placeholder="Contoh: Kolam Utama"
                            required
                        >

                        @error('nama_fasilitas')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="form-group">

                        <label>Deskripsi</label>

                        <textarea
                            name="deskripsi"
                            placeholder="Masukkan deskripsi fasilitas..."
                        >{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- STATUS -->
                    <div class="form-group">

                        <label>Status</label>

                        <select name="status" required>

                            <option value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="0"
                                {{ old('status') == '0' ? 'selected' : '' }}>
                                Nonaktif
                            </option>

                        </select>

                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <!-- BUTTON -->
                    <div class="buttons">

                        <button type="submit" class="btn btn-primary">
                            💾 Simpan
                        </button>

                        <a
                            href="{{ route('fasilitas.index') }}"
                            class="btn btn-secondary"
                        >
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </section>

    </main>

</body>

</html>