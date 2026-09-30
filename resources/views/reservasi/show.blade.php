<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <title>Detail Reservasi - NEXPOOL</title>

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

        /* =====================================================
           SIDEBAR
        ===================================================== */

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

        .menu i {
            width: 18px;
            text-align: center;
            margin-right: 5px;
            font-size: 15px;
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

        .logout a:hover {
            background: rgba(239, 68, 68, 0.12);
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* =====================================================
           HEADER
        ===================================================== */

        .header {
            height: 75px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-left h3 {
            font-size: 20px;
            color: #1f2937;
        }

        .header-left p {
            font-size: 12px;
            color: #8a94a6;
            margin-top: 4px;
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
            font-size: 14px;
            color: #1f2937;
        }

        .admin-text span {
            display: block;
            font-size: 12px;
            color: #8a94a6;
            margin-top: 3px;
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
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.25);
        }

        /* =====================================================
           CONTENT
        ===================================================== */

        .content {
            padding: 30px;
        }

        /* =====================================================
           BACK BUTTON
        ===================================================== */

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #e5e7eb;
            color: #374151;
            padding: 9px 16px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: 0.2s;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 26px;
            color: #14213d;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
        }

        /* =====================================================
           DETAIL CARD
        ===================================================== */

        .detail-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 14px;
            padding: 28px;
            max-width: 950px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 22px;
            margin-bottom: 25px;
            border-bottom: 1px solid #edf0f4;
        }

        .detail-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #e8f1ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .detail-header h2 {
            font-size: 20px;
            color: #14213d;
            margin-bottom: 5px;
        }

        .detail-header p {
            font-size: 13px;
            color: #8a94a6;
        }

        /* =====================================================
           DETAIL GRID
        ===================================================== */

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .detail-item {
            background: #f8fafc;
            border: 1px solid #edf0f4;
            border-radius: 10px;
            padding: 16px;
        }

        .detail-item.full {
            grid-column: span 2;
        }

        .detail-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7b8494;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .detail-label i {
            color: #2563eb;
            width: 15px;
            text-align: center;
        }

        .detail-value {
            color: #1f2937;
            font-size: 15px;
            font-weight: 700;
            word-break: break-word;
        }

        /* =====================================================
           JUMLAH TIKET
        ===================================================== */

        .ticket-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .ticket-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            padding: 12px;
        }

        .ticket-box span {
            display: block;
            font-size: 11px;
            color: #8a94a6;
            margin-bottom: 5px;
        }

        .ticket-box strong {
            font-size: 16px;
            color: #14213d;
        }

        /* =====================================================
           TOTAL
        ===================================================== */

        .total-box {
            background: linear-gradient(
                135deg,
                #eff6ff,
                #f0f9ff
            );
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 18px;
        }

        .total-box .detail-label i {
            color: #0ea5e9;
        }

        .total-price {
            color: #0f6fc0;
            font-size: 23px;
            font-weight: 800;
        }

        /* =====================================================
           STATUS
        ===================================================== */

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-menunggu {
            color: #b45309;
            background: #fff7ed;
        }

        .status-dikonfirmasi {
            color: #047857;
            background: #ecfdf5;
        }

        .status-selesai {
            color: #1d4ed8;
            background: #eff6ff;
        }

        .status-dibatalkan {
            color: #dc2626;
            background: #fef2f2;
        }

        .status-default {
            color: #475569;
            background: #f1f5f9;
        }

        /* =====================================================
           INFO
        ===================================================== */

        .info-box {
            margin-top: 22px;
            padding: 16px 18px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            color: #1e40af;
            font-size: 13px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #1e3a8a;
        }

        /* =====================================================
           BUTTON
        ===================================================== */

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 850px) {

            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-item.full {
                grid-column: span 1;
            }
        }

        @media (max-width: 650px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .logout {
                position: relative;
                left: auto;
                right: auto;
                bottom: auto;
                margin-top: 20px;
            }

            .main {
                margin-left: 0;
            }

            .header {
                padding: 0 18px;
            }

            .content {
                padding: 20px;
            }

            .admin-info {
                display: none;
            }

            .ticket-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         SIDEBAR
    ===================================================== -->

    <aside class="sidebar">

        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">
            Menu Utama
        </div>

        <div class="menu">

            <a href="{{ route('dashboard') }}">
                <i
                    class="fa-solid fa-gauge-high"
                    style="color:#3b82f6;"
                ></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                <i
                    class="fa-solid fa-ticket"
                    style="color:#f59e0b;"
                ></i>
                <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i
                    class="fa-solid fa-person-swimming"
                    style="color:#06b6d4;"
                ></i>
                <span>Fasilitas</span>
            </a>

            <a
                href="{{ route('reservasi.index') }}"
                class="active"
            >
                <i
                    class="fa-solid fa-calendar-check"
                    style="color:#10b981;"
                ></i>
                <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}">
                <i
                    class="fa-solid fa-tags"
                    style="color:#8b5cf6;"
                ></i>
                <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}">
                <i
                    class="fa-solid fa-star"
                    style="color:#eab308;"
                ></i>
                <span>Review</span>
            </a>

        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i
                    class="fa-solid fa-right-from-bracket"
                    style="color:#ef4444;"
                ></i>
                <span>Logout</span>
            </a>
        </div>

    </aside>


    <!-- =====================================================
         MAIN
    ===================================================== -->

    <main class="main">

        <!-- HEADER -->

        <header class="header">

            <div class="header-left">

                <h3>
                    Detail Reservasi
                </h3>

                <p>
                    Lihat informasi reservasi pengunjung
                </p>

            </div>


            <div class="admin-info">

                <div class="admin-text">

                    <strong>
                        {{ session('admin_pool_nama', 'Admin NEXPOOL') }}
                    </strong>

                    <span>
                        {{ session('admin_pool_id', 'pool_id_01') }}
                    </span>

                </div>

                <div class="avatar">

                    {{ strtoupper(
                        substr(
                            session(
                                'admin_pool_nama',
                                'Admin NEXPOOL'
                            ),
                            0,
                            1
                        )
                    ) }}

                </div>

            </div>

        </header>


        <!-- CONTENT -->

        <section class="content">

            <!-- KEMBALI -->

            <a
                href="{{ route('reservasi.index') }}"
                class="btn-back"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Reservasi
            </a>


            <!-- PAGE HEADER -->

            <div class="page-header">

                <h1>
                    Detail Reservasi
                </h1>

                <p>
                    Informasi lengkap reservasi tiket pengunjung NEXPOOL.
                </p>

            </div>


            <!-- DETAIL CARD -->

            <div class="detail-card">


                <!-- DETAIL HEADER -->

                <div class="detail-header">

                    <div class="detail-icon">

                        <i class="fa-solid fa-ticket"></i>

                    </div>

                    <div>

                        <h2>
                            Informasi Tiket Reservasi
                        </h2>

                        <p>
                            Data reservasi yang dikirim dari aplikasi mobile NEXPOOL.
                        </p>

                    </div>

                </div>


                <!-- DETAIL GRID -->

                <div class="detail-grid">


                    <!-- KODE RESERVASI -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-hashtag"></i>

                            Kode Reservasi

                        </div>

                        <div class="detail-value">

                            {{ $reservasi->kode_reservasi ?? '-' }}

                        </div>

                    </div>


                    <!-- NAMA PENGUNJUNG -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-user"></i>

                            Nama Pengunjung

                        </div>

                        <div class="detail-value">

                            {{ $reservasi->nama_pengunjung ?? '-' }}

                        </div>

                    </div>


                    <!-- NO HP -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-phone"></i>

                            No. HP

                        </div>

                        <div class="detail-value">

                            {{ $reservasi->no_hp ?? '-' }}

                        </div>

                    </div>


                    <!-- KOLAM -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-person-swimming"></i>

                            Kolam Renang

                        </div>

                        <div class="detail-value">

                            {{ session('admin_pool_nama', 'Kolam Renang') }}

                        </div>

                    </div>


                    <!-- TANGGAL -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-calendar-days"></i>

                            Tanggal Kunjungan

                        </div>

                        <div class="detail-value">

                            @if($reservasi->tanggal_kunjungan)

                                {{ \Carbon\Carbon::parse(
                                    $reservasi->tanggal_kunjungan
                                )->format('d-m-Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    <!-- JUMLAH TIKET -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-users"></i>

                            Jumlah Tiket

                        </div>


                        <div class="ticket-summary">

                            <div class="ticket-box">

                                <span>
                                    Dewasa
                                </span>

                                <strong>
                                    {{ $reservasi->jumlah_dewasa ?? 0 }}
                                    Tiket
                                </strong>

                            </div>


                            <div class="ticket-box">

                                <span>
                                    Anak
                                </span>

                                <strong>
                                    {{ $reservasi->jumlah_anak ?? 0 }}
                                    Tiket
                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- TOTAL HARGA -->

                    <div class="detail-item total-box">

                        <div class="detail-label">

                            <i class="fa-solid fa-money-bill-wave"></i>

                            Total Pembayaran

                        </div>

                        <div class="total-price">

                            Rp{{ number_format(
                                $reservasi->total_harga ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </div>

                    </div>


                    <!-- STATUS -->

                    <div class="detail-item">

                        <div class="detail-label">

                            <i class="fa-solid fa-circle-check"></i>

                            Status Reservasi

                        </div>


                        @php

                            $status =
                                $reservasi->status_reservasi
                                ?? 'Menunggu';

                        @endphp


                        @if($status === 'Menunggu')

                            <span class="status-badge status-menunggu">

                                <i class="fa-solid fa-clock"></i>

                                Menunggu

                            </span>

                        @elseif($status === 'Dikonfirmasi')

                            <span class="status-badge status-dikonfirmasi">

                                <i class="fa-solid fa-check"></i>

                                Dikonfirmasi

                            </span>

                        @elseif($status === 'Selesai')

                            <span class="status-badge status-selesai">

                                <i class="fa-solid fa-circle-check"></i>

                                Selesai

                            </span>

                        @elseif($status === 'Dibatalkan')

                            <span class="status-badge status-dibatalkan">

                                <i class="fa-solid fa-xmark"></i>

                                Dibatalkan

                            </span>

                        @else

                            <span class="status-badge status-default">

                                <i class="fa-solid fa-circle-info"></i>

                                {{ $status }}

                            </span>

                        @endif

                    </div>


                </div>


                <!-- INFO -->

                <div class="info-box">

                    <strong>
                        <i class="fa-solid fa-circle-info"></i>
                        Informasi Reservasi
                    </strong>

                    <br>

                    Data reservasi pada halaman ini berasal dari
                    <strong>aplikasi mobile NEXPOOL</strong>.
                    Admin hanya bertugas memantau, memeriksa,
                    dan memperbarui status reservasi yang masuk.

                    <br><br>

                    <strong>
                        Kolam:
                    </strong>

                    {{ session(
                        'admin_pool_nama',
                        'Kolam Renang'
                    ) }}

                </div>


                <!-- BUTTON -->

                <div class="buttons">

                    <a
                        href="{{ route('reservasi.edit', $reservasi->id) }}"
                        class="btn btn-primary"
                    >

                        <i class="fa-solid fa-pen-to-square"></i>

                        Kelola Status

                    </a>

                </div>


            </div>

        </section>

    </main>

</body>
</html>