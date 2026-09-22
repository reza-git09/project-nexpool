<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas - NEXPOOL</title>

    <style>
        /* ==========================================
            RESET
        ========================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ==========================================
            BODY
        ========================================== */
        body {
            background: #f5f7fb;
            color: #1f2937;
        }

        /* ==========================================
            SIDEBAR (Disamakan jadi 240px)
        ========================================== */
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

        /* LOGO */
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

        /* JUDUL MENU */
        .menu-title {
            font-size: 11px;
            color: #8491a7;
            margin: 20px 12px 10px;
            text-transform: uppercase;
        }

        /* MENU */
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #dce3ef;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-size: 14px;
        }

        /* MENU AKTIF */
        .menu a:hover,
        .menu a.active {
            background: #2563eb;
            color: white;
        }

        /* ==========================================
            LOGOUT
        ========================================== */
        .logout {
            position: absolute;
            bottom: 25px;
            left: 15px;
            right: 15px;
        }

        .logout a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #ffb4b4;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .logout a:hover {
            background: #7f1d1d;
            color: white;
        }

        /* ==========================================
            MAIN & HEADER
        ========================================== */
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

        /* USER PROFILE DI HEADER KANAN */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }

        .user-role {
            font-size: 12px;
            color: #7b8494;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* ==========================================
            CONTENT
        ========================================== */
        .content {
            padding: 30px;
        }

        .content-inner {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* BACK BUTTON */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #d1d5db;
        }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
        }

        /* ==========================================
            FORM CARD
        ========================================== */
        .form-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            color: #1f2937;
            outline: none;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea.form-control {
            height: 100px;
            resize: vertical;
        }

        .form-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* FILE UPLOAD BOX */
        .file-box {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 12px;
            background: #f8fafc;
        }

        .file-box input[type="file"] {
            width: 100%;
            font-size: 13px;
            cursor: pointer;
        }

        .file-info {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
        }

        /* ERROR */
        .error {
            margin-top: 5px;
            color: #dc2626;
            font-size: 12px;
        }

        /* BUTTON GROUP */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        /* ==========================================
            RESPONSIVE
        ========================================== */
        @media (max-width: 900px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo h2,
            .sidebar .logo p,
            .sidebar .menu-title,
            .sidebar .menu a span,
            .sidebar .logout span {
                display: none;
            }

            .main {
                margin-left: 70px;
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
                ▣ <span>Dashboard</span>
            </a>
            <a href="{{ route('harga-tiket.index') }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>
            <a href="{{ route('fasilitas.index') }}" class="active">
                🏊 <span>Fasilitas</span>
            </a>
            <a href="{{ route('reservasi.index') }}">
                📋 <span>Reservasi</span>
            </a>
            <a href="{{ route('promo.index') }}">
                🏷️ <span>Promo</span>
            </a>
            <a href="{{ route('review.index') }}">
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

        <!-- HEADER -->
        <header class="header">
            <h3>Tambah Fasilitas</h3>
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">Admin Tiara Park</div>
                    <div class="user-role">pool_id_01</div>
                </div>
                <div class="user-avatar">A</div>
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content">
            <div class="content-inner">

                <!-- KEMBALI -->
                <a href="{{ route('fasilitas.index') }}" class="back-button">
                    ← Kembali ke Manajemen Fasilitas
                </a>

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <h2>Tambah Fasilitas Baru</h2>
                    <p>Tambahkan fasilitas baru untuk kolam renang.</p>
                </div>

                <!-- FORM CARD -->
                <div class="form-card">
                    <form action="{{ route('fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- NAMA FASILITAS -->
                        <div class="form-group">
                            <label for="nama_fasilitas">Nama Fasilitas</label>
                            <input type="text" id="nama_fasilitas" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas') }}" placeholder="Contoh: Kolam Anak" pattern="[A-Za-z\s]+" title="Hanya boleh menggunakan huruf dan spasi (tanpa angka/simbol)" required>
                            <div class="form-hint">Catatan: Nama fasilitas harus unik dan hanya boleh menggunakan huruf (tanpa angka dan simbol).</div>
                            @error('nama_fasilitas')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan deskripsi fasilitas..." required>{{ old('deskripsi') }}</textarea>
                            <div class="form-hint">Catatan: Deskripsi wajib diisi dan tidak boleh sama dengan deskripsi fasilitas lain.</div>
                            @error('deskripsi')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GAMBAR -->
                        <div class="form-group">
                            <label for="gambar">Gambar Fasilitas</label>
                            <div class="file-box">
                                <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                                <div class="file-info">Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</div>
                            </div>
                            @error('gambar')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- STATUS -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- BUTTON -->
                        <div class="button-group">
                            <button type="submit" class="btn btn-primary">💾 Simpan</button>
                            <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Batal</a>
                        </div>

                    </form>
                </div>

            </div>
        </section>

    </main>

</body>

</html>