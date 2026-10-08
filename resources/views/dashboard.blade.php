```html
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Dashboard - NEXPOOL</title>

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

        /* =========================================================
           SIDEBAR
        ========================================================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;

            background:
                linear-gradient(
                    180deg,
                    #101c36 0%,
                    #14213d 55%,
                    #101b32 100%
                );

            color: white;
            padding: 24px 16px;

            z-index: 1000;

            box-shadow:
                8px 0 30px rgba(15, 23, 42, 0.08);
        }

        /* =========================================================
           LOGO
        ========================================================= */

        .logo {
            padding: 6px 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 25px;
            text-align: center;
        }

        .logo h2 {
            font-size: 22px;
            letter-spacing: 1px;
            color: white;
        }

        .logo p {
            margin-top: 4px;
            font-size: 10px;
            color: #91a0b9;
            letter-spacing: 1.3px;
        }

        /* =========================================================
           MENU
        ========================================================= */

        .menu-title {
            padding: 0 12px;
            margin-bottom: 10px;
            font-size: 10px;
            font-weight: bold;
            color: #73819b;
            letter-spacing: 1.2px;
            text-transform: uppercase;
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
            font-weight: 500;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.07);
            color: white;
            transform: translateX(2px);
        }

        .menu a.active {
            background:
                linear-gradient(
                    90deg,
                    #2563eb,
                    #1d4ed8
                );

            color: white;

            box-shadow:
                0 8px 20px rgba(37,99,235,0.25);
        }

        .menu a.active::before {
            content: "";

            position: absolute;

            left: -16px;
            top: 8px;

            width: 3px;
            height: calc(100% - 16px);

            border-radius: 0 5px 5px 0;

            background: #60a5fa;
        }

        /* =========================================================
           LOGOUT
        ========================================================= */

        .logout {
            position: absolute;

            bottom: 20px;

            left: 16px;
            right: 16px;

            padding-top: 15px;

            border-top:
                1px solid rgba(255,255,255,0.08);
        }

        .logout a {
            display: flex;

            align-items: center;

            gap: 13px;

            padding: 12px 13px;

            border-radius: 10px;

            color: #fca5a5;

            font-size: 13px;

            transition: 0.2s;
        }

        .logout a:hover {
            background: rgba(239,68,68,0.12);
            color: #fecaca;
        }

        .logout i {
            width: 20px;
            text-align: center;
        }

        /* =========================================================
           MAIN
        ========================================================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .header {
            height: 76px;

            background:
                rgba(255,255,255,0.96);

            backdrop-filter: blur(10px);

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 32px;

            border-bottom:
                1px solid #e8ecf3;

            position: sticky;

            top: 0;

            z-index: 900;
        }

        .header-left h3 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .header-left p {
            margin-top: 3px;
            font-size: 11px;
            color: #8a94a6;
        }

        /* =========================================================
           ADMIN INFO
        ========================================================= */

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
            margin-top: 3px;
            font-size: 11px;
            color: #8a94a6;
        }

        .avatar {
            width: 42px;
            height: 42px;

            border-radius: 12px;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #38bdf8
                );

            color: white;

            font-size: 15px;
            font-weight: bold;

            box-shadow:
                0 6px 15px rgba(37,99,235,0.2);
        }

        /* =========================================================
           CONTENT
        ========================================================= */

        .content {
            padding: 32px;
            max-width: 1700px;
        }

        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .page-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;
        }

        .page-title-wrapper {
            display: flex;

            align-items: center;

            gap: 14px;
        }

        .page-icon {
            width: 48px;
            height: 48px;

            flex-shrink: 0;

            border-radius: 13px;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #dbeafe,
                    #eff6ff
                );

            color: #2563eb;

            font-size: 19px;
        }

        .page-header h1 {
            font-size: 25px;
            color: #111827;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 13px;
        }

        /* =========================================================
           STATISTICS
        ========================================================= */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 22px;
        }

        .card {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            padding: 20px;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.025);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(15,23,42,0.06);
        }

        .card-top {
            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .card-title {
            color: #8a94a6;
            font-size: 12px;
            font-weight: 500;
        }

        .card-icon {
            width: 40px;
            height: 40px;

            border-radius: 11px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #eef4ff;

            color: #2563eb;

            font-size: 16px;
        }

        .card h2 {
            font-size: 26px;

            color: #172033;

            margin-top: 15px;
        }

        .card small {
            display: inline-block;

            margin-top: 5px;

            color: #16a34a;

            font-size: 10px;

            font-weight: 600;
        }

        /* =========================================================
           DASHBOARD GRID
        ========================================================= */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                2fr 1fr;

            gap: 20px;
        }

        .panel {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            padding: 20px;

            margin-bottom: 20px;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.025);
        }

        .panel-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 20px;

            padding-bottom: 14px;

            border-bottom:
                1px solid #eef1f5;
        }

        .panel-header h3 {
            font-size: 14px;
            color: #172033;
        }

        .panel-header span {
            color: #8a94a6;
            font-size: 10px;
        }

        /* =========================================================
           CHART
        ========================================================= */

        .chart {
            height: 230px;

            display: flex;

            align-items: flex-end;

            justify-content: space-around;

            padding-top: 20px;

            border-bottom:
                1px solid #e5e7eb;
        }

        .bar-wrapper {
            height: 100%;

            display: flex;

            flex-direction: column;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;
        }

        .bar {
            width: 35px;

            background:
                linear-gradient(
                    180deg,
                    #38bdf8,
                    #2563eb
                );

            border-radius:
                6px 6px 0 0;
        }

        .bar-wrapper span {
            font-size: 10px;
            color: #8a94a6;
        }

        /* =========================================================
           RESERVATION
        ========================================================= */

        .reservation {
            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 13px 0;

            border-bottom:
                1px solid #eef1f5;
        }

        .reservation:last-child {
            border-bottom: none;
        }

        .reservation strong {
            display: block;

            font-size: 12px;

            color: #334155;
        }

        .reservation span {
            display: block;

            margin-top: 3px;

            font-size: 10px;

            color: #8a94a6;
        }

        .status {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 6px 9px;

            background: #dcfce7;

            color: #15803d;

            border-radius: 20px;

            font-size: 9px;

            font-weight: 600;
        }

        /* =========================================================
           NOTIFICATION
        ========================================================= */

        .notification {
            padding: 13px 0;

            border-bottom:
                1px solid #eef1f5;
        }

        .notification:last-child {
            border-bottom: none;
        }

        .notification strong {
            font-size: 12px;
            color: #334155;
        }

        .notification p {
            font-size: 10px;

            color: #8a94a6;

            margin-top: 4px;

            line-height: 1.5;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .cards {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 800px) {

            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo {
                padding-bottom: 20px;
            }

            .logo h2,
            .logo p,
            .menu-title,
            .menu a span,
            .logout span {
                display: none;
            }

            .menu a {
                justify-content: center;
                padding: 13px 8px;
            }

            .menu a.active::before {
                left: -10px;
            }

            .logout a {
                justify-content: center;
            }

            .main {
                margin-left: 70px;
            }

            .header {
                padding: 0 20px;
            }

            .content {
                padding: 20px;
            }

            .admin-text {
                display: none;
            }
        }

        @media (max-width: 600px) {

            .cards {
                grid-template-columns: 1fr;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .chart {
                height: 200px;
            }

            .bar {
                width: 25px;
            }
        }
    </style>
</head>

<body>

    <!-- =========================================================
         SIDEBAR
    ========================================================= -->

    <aside class="sidebar">

        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">
            Menu Utama
        </div>

        <div class="menu">

            <!-- DASHBOARD -->
            <a href="{{ route('dashboard') }}"
               class="active">

                <i class="fa-solid fa-gauge-high"
                   style="color:#bfdbfe;">
                </i>

                <span>
                    Dashboard
                </span>

            </a>

            <!-- MANAJEMEN TIKET -->
            <a href="{{ route('harga-tiket.index') }}">

                <i class="fa-solid fa-ticket"
                   style="color:#fcd34d;">
                </i>

                <span>
                    Manajemen Tiket
                </span>

            </a>

            <!-- FASILITAS -->
            <a href="{{ route('fasilitas.index') }}">

                <i class="fa-solid fa-person-swimming"
                   style="color:#67e8f9;">
                </i>

                <span>
                    Fasilitas
                </span>

            </a>

            <!-- RESERVASI -->
            <a href="{{ route('reservasi.index') }}">

                <i class="fa-solid fa-calendar-check"
                   style="color:#6ee7b7;">
                </i>

                <span>
                    Reservasi
                </span>

            </a>

            <!-- PROMO -->
            <a href="{{ route('promo.index') }}">

                <i class="fa-solid fa-tags"
                   style="color:#c4b5fd;">
                </i>

                <span>
                    Promo
                </span>

            </a>

            <!-- REVIEW -->
            <a href="{{ route('review.index') }}">

                <i class="fa-solid fa-star"
                   style="color:#fde047;">
                </i>

                <span>
                    Review
                </span>

            </a>

        </div>

        <!-- LOGOUT -->
        <div class="logout">

            <a href="{{ route('logout') }}">

                <i class="fa-solid fa-right-from-bracket"
                   style="color:#f87171;">
                </i>

                <span>
                    Logout
                </span>

            </a>

        </div>

    </aside>


    <!-- =========================================================
         MAIN
    ========================================================= -->

    <main class="main">

        <!-- HEADER -->
        <header class="header">

            <div class="header-left">

                <h3>
                    Dashboard
                </h3>

                <p>
                    Ringkasan aktivitas kolam renang
                </p>

            </div> 

            <!-- ADMIN INFO -->
            <div class="admin-info">

                <div class="admin-text">

                    <strong>
                        {{ session('admin_pool_nama') }}
                    </strong>

                    <span>
                        {{ session('admin_pool_id') }}
                    </span>

                </div>

                <div class="avatar">

                    {{ strtoupper(substr(session('admin_pool_nama'), 0, 1)) }}

                </div>

            </div>

        </header>


        <!-- =====================================================
             CONTENT
        ===================================================== -->

        <section class="content">

            <!-- PAGE HEADER -->
            <div class="page-header">

                <div class="page-title-wrapper">

                    <div class="page-icon">

                        <i class="fa-solid fa-gauge-high"></i>

                    </div>

                    <div>

                        <h1>
                            Dashboard
                        </h1>

                        <p>
                            Pantau aktivitas dan pengelolaan kolam renang melalui NEXPOOL.
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 STATISTICS
            ================================================= -->

            <div class="cards">

                <!-- PENGUNJUNG -->
                <div class="card">

                    <div class="card-top">

                        <div class="card-title">
                            Total Pengunjung
                        </div>

                        <div class="card-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>

                    </div>

                    <h2>
                        245
                    </h2>

                    <small>
                        +12% bulan ini
                    </small>

                </div>


                <!-- TIKET -->
                <div class="card">

                    <div class="card-top">

                        <div class="card-title">
                            Tiket Terjual
                        </div>

                        <div class="card-icon">
                            <i class="fa-solid fa-ticket"></i>
                        </div>

                    </div>

                    <h2>
                        220
                    </h2>

                    <small>
                        +8% bulan ini
                    </small>

                </div>


                <!-- PROMO -->
                <div class="card">

                    <div class="card-top">

                        <div class="card-title">
                            Promo Aktif
                        </div>

                        <div class="card-icon">
                            <i class="fa-solid fa-tags"></i>
                        </div>

                    </div>

                    <h2>
                        2
                    </h2>

                    <small>
                        Promo sedang berjalan
                    </small>

                </div>

            </div>


            <!-- =================================================
                 DASHBOARD GRID
            ================================================= -->

            <div class="dashboard-grid">

                <!-- =================================================
                     LEFT COLUMN
                ================================================= -->

                <div>

                    <!-- PENJUALAN TIKET -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Penjualan Tiket
                            </h3>

                            <span>
                                7 Hari Terakhir
                            </span>

                        </div>

                        <div class="chart">

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:45%;">
                                </div>

                                <span>
                                    Sen
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:60%;">
                                </div>

                                <span>
                                    Sel
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:50%;">
                                </div>

                                <span>
                                    Rab
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:75%;">
                                </div>

                                <span>
                                    Kam
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:65%;">
                                </div>

                                <span>
                                    Jum
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:90%;">
                                </div>

                                <span>
                                    Sab
                                </span>

                            </div>

                            <div class="bar-wrapper">

                                <div class="bar"
                                     style="height:80%;">
                                </div>

                                <span>
                                    Min
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- RESERVASI TERBARU -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Reservasi Terbaru
                            </h3>

                            <span>
                                3 Data
                            </span>

                        </div>


                        <div class="reservation">

                            <div>

                                <strong>
                                    TRX001
                                </strong>

                                <span>
                                    12 September 2026
                                </span>

                            </div>

                            <div class="status">

                                <i class="fa-solid fa-circle"
                                   style="font-size:6px;">
                                </i>

                                Berhasil

                            </div>

                        </div>


                        <div class="reservation">

                            <div>

                                <strong>
                                    TRX002
                                </strong>

                                <span>
                                    12 September 2026
                                </span>

                            </div>

                            <div class="status">

                                <i class="fa-solid fa-circle"
                                   style="font-size:6px;">
                                </i>

                                Berhasil

                            </div>

                        </div>


                        <div class="reservation">

                            <div>

                                <strong>
                                    TRX003
                                </strong>

                                <span>
                                    13 September 2026
                                </span>

                            </div>

                            <div class="status">

                                <i class="fa-solid fa-circle"
                                   style="font-size:6px;">
                                </i>

                                Berhasil

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     RIGHT COLUMN
                ================================================= -->

                <div>

                    <!-- NOTIFIKASI -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Notifikasi
                            </h3>

                            <span>
                                3 Baru
                            </span>

                        </div>


                        <div class="notification">

                            <strong>
                                <i class="fa-solid fa-credit-card"
                                   style="color:#2563eb; margin-right:5px;">
                                </i>

                                Pembayaran baru
                            </strong>

                            <p>
                                Pembayaran TRX003 telah diterima.
                            </p>

                        </div>


                        <div class="notification">

                            <strong>
                                <i class="fa-solid fa-star"
                                   style="color:#eab308; margin-right:5px;">
                                </i>

                                Review baru
                            </strong>

                            <p>
                                Pengunjung memberikan review baru.
                            </p>

                        </div>


                        <div class="notification">

                            <strong>
                                <i class="fa-solid fa-ticket"
                                   style="color:#2563eb; margin-right:5px;">
                                </i>

                                Reservasi baru
                            </strong>

                            <p>
                                Ada reservasi baru masuk.
                            </p>

                        </div>

                    </div>


                    <!-- STATUS KOLAM -->
                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Status Kolam
                            </h3>

                        </div>


                        <div class="reservation">

                            <div>

                                <strong>
                                    {{ session('admin_pool_id') }}
                                </strong>

                                <span>
                                    Kolam yang dikelola
                                </span>

                            </div>

                            <div class="status">

                                <i class="fa-solid fa-circle"
                                   style="font-size:6px;">
                                </i>

                                Aktif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

</body>

</html>
```
