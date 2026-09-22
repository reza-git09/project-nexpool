<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Tambah Review - NEXPOOL</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
            color: #111827;
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
            max-width: 1000px;
        }

        .topbar {
            margin-bottom: 25px;
        }

        .topbar h1 {
            font-size: 25px;
            color: #111827;
            margin-bottom: 15px;
        }

        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e8ebf0;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 13px;
            background: white;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #2563eb;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
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
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
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

        .info {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 22px;
            font-size: 12px;
            color: #1e40af;
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

            <a href="{{ route('harga-tiket.index') }}">
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

            <a href="{{ route('review.index') }}" class="active">
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

        <!-- HEADER -->
        <header class="header">
            <h3>Tambah Review Pengunjung</h3>

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

        <!-- CONTENT -->
        <div class="content">

            <div class="topbar">
                <h1>Tambah Review</h1>
                <!-- Tombol Kembali di Kiri persis seperti halaman Promo -->
                <a href="{{ route('review.index') }}" class="btn btn-secondary" style="margin-bottom: 20px;">
                    ← Kembali ke Review
                </a>
            </div>

            <div class="card">

                <div class="info">
                    Silakan isi data review pengunjung dengan lengkap.
                </div>

                <form action="{{ route('review.store') }}" method="POST">

                    @csrf

                    <!-- POOL ID -->
                    <div class="form-group">
                        <label for="pool_id">Pool ID</label>
                        <input
                            type="text"
                            id="pool_id"
                            name="pool_id"
                            value="{{ old('pool_id', 'pool_id_01') }}"
                            placeholder="Contoh: pool_id_01"
                        >
                        @error('pool_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA PENGUNJUNG -->
                    <div class="form-group">
                        <label for="nama_pengunjung">Nama Pengunjung</label>
                        <input
                            type="text"
                            id="nama_pengunjung"
                            name="nama_pengunjung"
                            value="{{ old('nama_pengunjung') }}"
                            placeholder="Masukkan nama pengunjung"
                        >
                        @error('nama_pengunjung')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- RATING -->
                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <select id="rating" name="rating">
                            <option value="">-- Pilih Rating --</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (5 - Sangat Baik)</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (4 - Baik)</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (3 - Cukup)</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (2 - Kurang)</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (1 - Sangat Kurang)</option>
                        </select>
                        @error('rating')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- KOMENTAR -->
                    <div class="form-group">
                        <label for="komentar">Komentar Pengunjung</label>
                        <textarea
                            id="komentar"
                            name="komentar"
                            placeholder="Tuliskan komentar atau ulasan pengunjung..."
                        >{{ old('komentar') }}</textarea>
                        @error('komentar')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BALASAN ADMIN -->
                    <div class="form-group">
                        <label for="balasan_admin">Balasan Admin</label>
                        <textarea
                            id="balasan_admin"
                            name="balasan_admin"
                            placeholder="Tuliskan balasan dari admin..."
                        >{{ old('balasan_admin') }}</textarea>
                        @error('balasan_admin')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- STATUS -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Menunggu" {{ old('status', 'Menunggu') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Dibalas" {{ old('status') == 'Dibalas' ? 'selected' : '' }}>Dibalas</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <div class="buttons">
                        <a href="{{ route('review.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Review
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </main>

</body>
</html>