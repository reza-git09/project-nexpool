<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Dashboard Admin - NEXPOOL</title>

    <style>

        /* =========================================================
           RESET
        ========================================================= */

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

            border-bottom:
                1px solid rgba(255,255,255,0.08);

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

            transition: 0.2s;
        }

        .menu a:hover {
            background:
                rgba(255,255,255,0.07);

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

            border-radius:
                0 5px 5px 0;

            background:
                #60a5fa;
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
            background:
                rgba(239,68,68,0.12);

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
           ADMIN PROFILE
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
           WELCOME
        ========================================================= */

        .welcome {
            margin-bottom: 28px;
        }

        .welcome h1 {
            display: flex;

            align-items: center;

            gap: 10px;

            font-size: 27px;

            color: #111827;
        }

        .welcome h1 i {
            font-size: 22px;

            color: #f59e0b;
        }

        .welcome p {
            margin-top: 7px;

            color: #7b8494;

            font-size: 13px;
        }


        /* =========================================================
           STATISTIC CARDS
        ========================================================= */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 18px;

            margin-bottom: 22px;
        }

        .card {
            position: relative;

            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            padding: 21px;

            overflow: hidden;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 14px 35px rgba(15,23,42,0.08);
        }

        .card::after {
            content: "";

            position: absolute;

            right: -25px;
            bottom: -30px;

            width: 100px;
            height: 100px;

            border-radius: 50%;

            background:
                rgba(37,99,235,0.04);
        }

        .card-top {
            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .card-title {
            color: #7b8494;

            font-size: 12px;

            font-weight: 500;
        }

        .card-icon {
            width: 43px;
            height: 43px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eef4ff;

            color: #2563eb;

            font-size: 17px;
        }

        .card h2 {
            margin-top: 17px;

            font-size: 29px;

            color: #111827;
        }

        .card-bottom {
            display: flex;

            align-items: center;

            gap: 6px;

            margin-top: 8px;
        }

        .card-bottom i {
            font-size: 10px;
        }

        .card small {
            color: #22a06b;

            font-size: 11px;
        }


        /* =========================================================
           DASHBOARD GRID
        ========================================================= */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 2fr)
                minmax(280px, 1fr);

            gap: 20px;
        }


        /* =========================================================
           PANEL
        ========================================================= */

        .panel {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            padding: 21px;

            margin-bottom: 20px;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.02);
        }

        .panel-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 18px;
        }

        .panel-title {
            display: flex;

            align-items: center;

            gap: 9px;
        }

        .panel-title-icon {
            width: 31px;
            height: 31px;

            border-radius: 9px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eef4ff;

            color: #2563eb;

            font-size: 12px;
        }

        .panel-header h3 {
            font-size: 14px;

            color: #172033;
        }

        .panel-header span {
            color: #8a94a6;

            font-size: 11px;
        }


        /* =========================================================
           CHART
        ========================================================= */

        .chart-container {
            position: relative;

            height: 255px;

            width: 100%;
        }


        /* =========================================================
           RESERVATION
        ========================================================= */

        .reservation {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 14px 4px;

            border-bottom:
                1px solid #eef1f5;
        }

        .reservation:last-child {
            border-bottom: none;
        }

        .reservation-left {
            display: flex;

            align-items: center;

            gap: 11px;
        }

        .reservation-icon {
            width: 35px;
            height: 35px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f1f5f9;

            color: #2563eb;

            font-size: 12px;
        }

        .reservation strong {
            display: block;

            font-size: 12px;

            color: #1f2937;
        }

        .reservation span {
            display: block;

            margin-top: 4px;

            font-size: 10px;

            color: #8a94a6;
        }

        .status {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 6px 9px;

            background: #ecfdf5;

            color: #16804f;

            border-radius: 20px;

            font-size: 9px;

            font-weight: 600;
        }

        .status::before {
            content: "";

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: #22c55e;
        }


        /* =========================================================
           VIEW ALL
        ========================================================= */

        .view-all {
            color: #2563eb;

            font-size: 11px;

            font-weight: 600;

            transition: 0.2s;
        }

        .view-all:hover {
            color: #1d4ed8;
        }


        /* =========================================================
           NOTIFICATION
        ========================================================= */

        .notification {
            display: flex;

            align-items: flex-start;

            gap: 11px;

            padding: 14px 2px;

            border-bottom:
                1px solid #eef1f5;
        }

        .notification:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 34px;
            height: 34px;

            flex-shrink: 0;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f1f5f9;

            font-size: 12px;
        }

        .notification strong {
            display: block;

            font-size: 11px;

            color: #1f2937;
        }

        .notification p {
            margin-top: 4px;

            font-size: 10px;

            line-height: 1.5;

            color: #8a94a6;
        }


        /* =========================================================
           POOL STATUS
        ========================================================= */

        .pool-status {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 12px;

            border-radius: 12px;

            background: #f8fafc;

            border:
                1px solid #eef1f5;
        }

        .pool-left {
            display: flex;

            align-items: center;

            gap: 11px;
        }

        .pool-icon {
            width: 38px;
            height: 38px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #e0f2fe;

            color: #0284c7;
        }

        .pool-left strong {
            display: block;

            font-size: 12px;
        }

        .pool-left span {
            display: block;

            margin-top: 3px;

            color: #8a94a6;

            font-size: 10px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }

            .content {
                padding: 25px;
            }

            .cards {
                grid-template-columns: 1fr;
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


        @media (max-width: 550px) {

            .header-left p {
                display: none;
            }

            .welcome h1 {
                font-size: 22px;
            }

            .card h2 {
                font-size: 25px;
            }

            .dashboard-grid {
                display: block;
            }

            .chart-container {
                height: 220px;
            }
        }

    </style>

</head>


<body>


    <!-- =========================================================
         SIDEBAR
    ========================================================= -->

    <aside class="sidebar">


        <!-- LOGO -->

        <div class="logo">

            <h2>NEXPOOL</h2>

            <p>ADMINISTRATOR</p>

        </div>


        <!-- MENU TITLE -->

        <div class="menu-title">
            Menu Utama
        </div>


        <!-- MENU -->

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


        <!-- =====================================================
             HEADER
        ===================================================== -->

        <header class="header">


            <div class="header-left">

                <h3>
                    Dashboard
                </h3>

                <p>
                    Ringkasan aktivitas NEXPOOL
                </p>

            </div>


            <!-- ADMIN -->

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


            <!-- WELCOME -->

            <div class="welcome">

                <h1>

                    Selamat Datang

                    <i class="fa-solid fa-hand"></i>

                </h1>

                <p>
                    Pantau aktivitas dan pengelolaan kolam renang melalui dashboard NEXPOOL.
                </p>

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
                        0
                    </h2>

                    <div class="card-bottom">

                        <i class="fa-solid fa-arrow-trend-up"></i>

                        <small>
                            +12% bulan ini
                        </small>

                    </div>

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
                        0
                    </h2>

                    <div class="card-bottom">

                        <i class="fa-solid fa-arrow-trend-up"></i>

                        <small>
                            +8% bulan ini
                        </small>

                    </div>

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

                    <div class="card-bottom">

                        <i class="fa-solid fa-circle-check"></i>

                        <small>
                            Promo sedang berjalan
                        </small>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 DASHBOARD GRID
            ================================================= -->

            <div class="dashboard-grid">


                <!-- LEFT COLUMN -->

                <div>


                    <!-- PENJUALAN TIKET -->

                    <div class="panel">

                        <div class="panel-header">

                            <div class="panel-title">

                                <div class="panel-title-icon">

                                    <i class="fa-solid fa-chart-line"></i>

                                </div>

                                <h3>
                                    Penjualan Tiket
                                </h3>

                            </div>

                            <span>
                                7 Hari Terakhir
                            </span>

                        </div>


                        <div class="chart-container">

                            <canvas id="salesChart"></canvas>

                        </div>

                    </div>


                    <!-- RESERVASI TERBARU -->

                    <div class="panel">

                        <div class="panel-header">

                            <div class="panel-title">

                                <div class="panel-title-icon">

                                    <i class="fa-solid fa-calendar-check"></i>

                                </div>

                                <h3>
                                    Reservasi Terbaru
                                </h3>

                            </div>


                            <a href="{{ route('reservasi.index') }}"
                                class="view-all">

                                Lihat Semua

                            </a>

                        </div>


                        <!-- TRX001 -->

                        <div class="reservation">

                            <div class="reservation-left">

                                <div class="reservation-icon">

                                    <i class="fa-solid fa-receipt"></i>

                                </div>

                                <div>

                                    <strong>
                                        TRX001
                                    </strong>

                                    <span>
                                        12 September 2026
                                    </span>

                                </div>

                            </div>

                            <div class="status">
                                Berhasil
                            </div>

                        </div>


                        <!-- TRX002 -->

                        <div class="reservation">

                            <div class="reservation-left">

                                <div class="reservation-icon">

                                    <i class="fa-solid fa-receipt"></i>

                                </div>

                                <div>

                                    <strong>
                                        TRX002
                                    </strong>

                                    <span>
                                        12 September 2026
                                    </span>

                                </div>

                            </div>

                            <div class="status">
                                Berhasil
                            </div>

                        </div>


                        <!-- TRX003 -->

                        <div class="reservation">

                            <div class="reservation-left">

                                <div class="reservation-icon">

                                    <i class="fa-solid fa-receipt"></i>

                                </div>

                                <div>

                                    <strong>
                                        TRX003
                                    </strong>

                                    <span>
                                        13 September 2026
                                    </span>

                                </div>

                            </div>

                            <div class="status">
                                Berhasil
                            </div>

                        </div>

                    </div>

                </div>


                <!-- RIGHT COLUMN -->

                <div>


                    <!-- NOTIFIKASI -->

                    <div class="panel">

                        <div class="panel-header">

                            <div class="panel-title">

                                <div class="panel-title-icon">

                                    <i class="fa-solid fa-bell"></i>

                                </div>

                                <h3>
                                    Notifikasi
                                </h3>

                            </div>

                            <span>
                                3 Baru
                            </span>

                        </div>


                        <!-- PAYMENT -->

                        <div class="notification">

                            <div class="notification-icon"
                                style="color:#2563eb;">

                                <i class="fa-solid fa-credit-card"></i>

                            </div>

                            <div>

                                <strong>
                                    Pembayaran baru
                                </strong>

                                <p>
                                    Pembayaran TRX003 telah diterima.
                                </p>

                            </div>

                        </div>


                        <!-- REVIEW -->

                        <div class="notification">

                            <div class="notification-icon"
                                style="color:#eab308;">

                                <i class="fa-solid fa-star"></i>

                            </div>

                            <div>

                                <strong>
                                    Review baru
                                </strong>

                                <p>
                                    Pengunjung memberikan review baru.
                                </p>

                            </div>

                        </div>


                        <!-- RESERVASI -->

                        <div class="notification">

                            <div class="notification-icon"
                                style="color:#f59e0b;">

                                <i class="fa-solid fa-ticket"></i>

                            </div>

                            <div>

                                <strong>
                                    Reservasi baru
                                </strong>

                                <p>
                                    Ada reservasi baru masuk.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- STATUS KOLAM -->

                    <div class="panel">

                        <div class="panel-header">

                            <div class="panel-title">

                                <div class="panel-title-icon">

                                    <i class="fa-solid fa-water"></i>

                                </div>

                                <h3>
                                    Status Kolam
                                </h3>

                            </div>

                        </div>


                        <div class="pool-status">


                            <div class="pool-left">

                                <div class="pool-icon">

                                    <i class="fa-solid fa-person-swimming"></i>

                                </div>


                                <div>

                                    <strong>
                                        {{ session('admin_pool_id') }}
                                    </strong>

                                    <span>
                                        Kolam yang dikelola
                                    </span>

                                </div>

                            </div>


                            <div class="status">
                                Aktif
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- =========================================================
         CHART SCRIPT
    ========================================================= -->

    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const canvas =
                document.getElementById('salesChart');

            const ctx =
                canvas.getContext('2d');


            /* GRADIENT */

            const gradient =
                ctx.createLinearGradient(
                    0,
                    0,
                    0,
                    260
                );

            gradient.addColorStop(
                0,
                'rgba(37, 99, 235, 0.20)'
            );

            gradient.addColorStop(
                1,
                'rgba(37, 99, 235, 0.00)'
            );


            /* CHART */

            new Chart(ctx, {

                type: 'line',

                data: {

                    labels: [
                        'Sen',
                        'Sel',
                        'Rab',
                        'Kam',
                        'Jum',
                        'Sab',
                        'Min'
                    ],

                    datasets: [{

                        label:
                            'Penjualan Tiket',

                        data: [
                            45,
                            60,
                            50,
                            75,
                            65,
                            90,
                            80
                        ],

                        borderColor:
                            '#2563eb',

                        borderWidth:
                            3,

                        backgroundColor:
                            gradient,

                        fill:
                            true,

                        tension:
                            0.42,

                        pointBackgroundColor:
                            '#ffffff',

                        pointBorderColor:
                            '#2563eb',

                        pointBorderWidth:
                            2.5,

                        pointRadius:
                            4,

                        pointHoverRadius:
                            7

                    }]

                },


                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,


                    interaction: {

                        mode:
                            'index',

                        intersect:
                            false

                    },


                    plugins: {

                        legend: {

                            display:
                                false

                        },


                        tooltip: {

                            backgroundColor:
                                '#14213d',

                            titleColor:
                                '#ffffff',

                            bodyColor:
                                '#ffffff',

                            padding:
                                11,

                            cornerRadius:
                                9,

                            displayColors:
                                false,

                            callbacks: {

                                label:
                                    function(context) {

                                        return ' ' +
                                            context.parsed.y +
                                            ' Tiket';

                                    }

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {

                                display:
                                    false

                            },

                            border: {

                                display:
                                    false

                            },

                            ticks: {

                                color:
                                    '#8a94a6',

                                font: {

                                    size:
                                        11

                                }

                            }

                        },


                        y: {

                            beginAtZero:
                                true,

                            max:
                                100,

                            border: {

                                display:
                                    false

                            },

                            grid: {

                                color:
                                    '#f0f2f6'

                            },

                            ticks: {

                                color:
                                    '#8a94a6',

                                font: {

                                    size:
                                        11

                                }

                            }

                        }

                    }

                }

            });

        });

    </script>

</body>

</html>