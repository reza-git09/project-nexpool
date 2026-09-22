<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Edit Review - NEXPOOL</title>

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

        /* Back Button Style di Kiri Atas */
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

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
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
            background: white;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #2563eb;
        }

        .form-group textarea {
            min-height: 110px;
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
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
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

    <!-- MAIN CONTENT -->
    <main class="main">

        <header class="header">
            <h3>Edit Review</h3>
        </header>

        <section class="content">

            <!-- Tombol Kembali di Kiri Atas -->
            <a href="{{ route('review.index') }}" class="btn-back">
                &larr; Kembali ke Review
            </a>

            <div class="page-header">
                <h1>Edit Review</h1>
                <p>Perbarui data review dan balasan admin.</p>
            </div>

            <div class="form-card">
                <form action="{{ route('review.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- POOL ID -->
                    <div class="form-group">
                        <label>Pool ID</label>
                        <input
                            type="text"
                            name="pool_id"
                            value="{{ old('pool_id', $review->pool_id) }}"
                            placeholder="Contoh: pool_id_01"
                            required
                        >
                        @error('pool_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA PENGUNJUNG -->
                    <div class="form-group">
                        <label>Nama Pengunjung</label>
                        <input
                            type="text"
                            name="nama_pengunjung"
                            value="{{ old('nama_pengunjung', $review->nama_pengunjung) }}"
                            placeholder="Masukkan nama pengunjung"
                            required
                        >
                        @error('nama_pengunjung')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- RATING -->
                    <div class="form-group">
                        <label>Rating</label>
                        <select name="rating" required>
                            <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (5 - Sangat Baik)</option>
                            <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (4 - Baik)</option>
                            <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (3 - Cukup)</option>
                            <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (2 - Kurang)</option>
                            <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}><i class="fa-solid fa-star" style="color:#eab308;font-size:18px;"></i> (1 - Sangat Kurang)</option>
                        </select>
                        @error('rating')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- KOMENTAR -->
                    <div class="form-group">
                        <label>Komentar Pengunjung</label>
                        <textarea
                            name="komentar"
                            placeholder="Tuliskan komentar pengunjung..."
                        >{{ old('komentar', $review->komentar) }}</textarea>
                        @error('komentar')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BALASAN ADMIN -->
                    <div class="form-group">
                        <label>Balasan Admin</label>
                        <textarea
                            name="balasan_admin"
                            placeholder="Tuliskan balasan admin..."
                        >{{ old('balasan_admin', $review->balasan_admin) }}</textarea>
                        @error('balasan_admin')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- STATUS -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Menunggu" {{ old('status', $review->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Dibalas" {{ old('status', $review->status) == 'Dibalas' ? 'selected' : '' }}>Dibalas</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BUTTONS -->
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            💾 Update
                        </button>
                    </div>
                </form>
            </div>

        </section>

    </main>

</body>
</html>