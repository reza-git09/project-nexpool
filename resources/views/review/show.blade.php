<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Detail Review - NEXPOOL</title>

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

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;
            background: linear-gradient(
                180deg,
                #101c36 0%,
                #14213d 55%,
                #101b32 100%
            );
            color: white;
            padding: 24px 16px;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(15, 23, 42, .08);
        }

        .logo {
            padding: 6px 10px 28px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
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
            background: rgba(255, 255, 255, .05);
        }

        .menu a.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 6px 18px rgba(37, 99, 235, .25);
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
            border-top: 1px solid rgba(255, 255, 255, .08);
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
            background: rgba(255, 255, 255, .05);
        }

        .logout a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* ================= MAIN ================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .header {
            height: 76px;
            background: rgba(255, 255, 255, .96);
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
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(37, 99, 235, .2);
        }

        /* ================= CONTENT ================= */

        .content {
            padding: 32px;
            max-width: 1700px;
        }

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

        .page-header {
            display: flex;
            align-items: center;
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
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
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

        /* ================= DETAIL CARD ================= */

        .detail-card {
            background: white;
            border: 1px solid #e8ecf3;
            border-radius: 16px;
            padding: 25px;
            max-width: 900px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #edf0f5;
        }

        .detail-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .detail-header h3 {
            font-size: 15px;
            color: #172033;
        }

        .detail-header p {
            font-size: 11px;
            color: #7b8494;
            margin-top: 3px;
        }

        /* ================= DETAIL ROW ================= */

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 20px;
        }

        .detail-item {
            background: #f8fafc;
            border: 1px solid #edf0f5;
            border-radius: 10px;
            padding: 14px;
        }

        .detail-item.full {
            grid-column: span 2;
        }

        .detail-label {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #64748b;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .detail-label i {
            color: #2563eb;
            font-size: 12px;
        }

        .detail-value {
            color: #172033;
            font-size: 13px;
            line-height: 1.6;
            word-break: break-word;
        }

        .empty-text {
            color: #94a3b8;
            font-style: italic;
        }

        /* ================= RATING ================= */

        .rating {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .rating i {
            color: #f59e0b;
            font-size: 15px;
        }

        .rating-number {
            margin-left: 7px;
            color: #64748b;
            font-size: 11px;
        }

        /* ================= STATUS ================= */

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-menunggu {
            background: #fff7ed;
            color: #c2410c;
        }

        .status-dibalas {
            background: #ecfdf5;
            color: #047857;
        }

        /* ================= PHOTO ================= */

        .photo-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #edf0f5;
        }

        .photo-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: bold;
            color: #172033;
            margin-bottom: 12px;
        }

        .photo-title i {
            color: #2563eb;
        }

        .photo-empty {
            background: #f8fafc;
            border: 1px dashed #d9dee7;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }

        .photo-empty i {
            display: block;
            font-size: 25px;
            margin-bottom: 8px;
            color: #cbd5e1;
        }

        /* ================= BUTTON ================= */

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
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .18);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, .25);
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 1000px) {
            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }
        }

        @media (max-width: 800px) {
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

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-item.full {
                grid-column: span 1;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }

        @media (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }

            .detail-card {
                padding: 20px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
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

            <a href="{{ route('promo.index') }}">
                <i class="fa-solid fa-tags" style="color:#8b5cf6;"></i>
                <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}" class="active">
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
                <h3>Detail Review</h3>
                <p>Melihat detail review pengunjung NEXPOOL</p>
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

            <!-- KEMBALI -->
            <a href="{{ route('review.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Review
            </a>

            <!-- PAGE HEADER -->
            <div class="page-header">

                <div class="page-title">

                    <div class="page-icon">
                        <i class="fa-solid fa-star"></i>
                    </div>

                    <div>
                        <h1>Detail Review</h1>
                        <p>Informasi lengkap review dan penilaian dari pengunjung.</p>
                    </div>

                </div>

            </div>

            <!-- DETAIL CARD -->
            <div class="detail-card">

                <div class="detail-header">

                    <div class="detail-icon">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>

                    <div>
                        <h3>Informasi Review</h3>
                        <p>Detail data review yang diberikan oleh pengunjung.</p>
                    </div>

                </div>

                <div class="detail-grid">

                    <!-- NAMA PENGUNJUNG -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-user"></i>
                            Nama Pengunjung
                        </div>

                        <div class="detail-value">
                            {{ $review->nama_pengunjung }}
                        </div>

                    </div>

                    <!-- POOL -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-person-swimming"></i>
                            Kolam Renang
                        </div>

                        <div class="detail-value">
                            {{ $review->pool_id }}
                        </div>

                    </div>

                    <!-- RATING -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-star"></i>
                            Rating
                        </div>

                        <div class="rating">

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor

                            <span class="rating-number">
                                ({{ $review->rating }}/5)
                            </span>

                        </div>

                    </div>

                    <!-- STATUS -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-circle-check"></i>
                            Status
                        </div>

                        @if ($review->status === 'Dibalas')

                            <span class="status status-dibalas">
                                <i class="fa-solid fa-check"></i>
                                Dibalas
                            </span>

                        @else

                            <span class="status status-menunggu">
                                <i class="fa-solid fa-clock"></i>
                                Menunggu
                            </span>

                        @endif

                    </div>

                    <!-- KOMENTAR -->
                    <div class="detail-item full">

                        <div class="detail-label">
                            <i class="fa-solid fa-comment"></i>
                            Komentar Pengunjung
                        </div>

                        <div class="detail-value">

                            @if ($review->komentar)
                                {{ $review->komentar }}
                            @else
                                <span class="empty-text">
                                    Tidak ada komentar dari pengunjung.
                                </span>
                            @endif

                        </div>

                    </div>

                    <!-- BALASAN ADMIN -->
                    <div class="detail-item full">

                        <div class="detail-label">
                            <i class="fa-solid fa-reply"></i>
                            Balasan Admin
                        </div>

                        <div class="detail-value">

                            @if ($review->balasan_admin)
                                {{ $review->balasan_admin }}
                            @else
                                <span class="empty-text">
                                    Belum ada balasan dari admin.
                                </span>
                            @endif

                        </div>

                    </div>

                    <!-- TANGGAL -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-calendar"></i>
                            Tanggal Review
                        </div>

                        <div class="detail-value">
                            {{ $review->created_at ? $review->created_at->format('d M Y, H:i') : '-' }}
                        </div>

                    </div>

                    <!-- ID REVIEW -->
                    <div class="detail-item">

                        <div class="detail-label">
                            <i class="fa-solid fa-hashtag"></i>
                            ID Review
                        </div>

                        <div class="detail-value">
                            #{{ $review->id }}
                        </div>

                    </div>

                </div>

                <!-- FOTO REVIEW -->
                <div class="photo-section">

                    <div class="photo-title">
                        <i class="fa-solid fa-image"></i>
                        Foto Review
                    </div>

                    <div class="photo-empty">
                        <i class="fa-regular fa-image"></i>
                        Belum ada foto yang diunggah pada review ini.
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="buttons">

                    <a href="{{ route('review.edit', $review->id) }}"
                        class="btn btn-primary">
                        <i class="fa-solid fa-reply"></i>
                        Balas / Ubah Balasan
                    </a>

                </div>

            </div>

        </section>

    </main>

</body>

</html>