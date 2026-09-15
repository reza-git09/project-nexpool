<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - NEXPOOL</title>

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
            transition: 0.2s;
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

        .logout a:hover {
            background: #7f1d1d;
            color: white;
        }

        /* MAIN */
        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            height: 75px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .admin-text strong {
            display: block;
            font-size: 14px;
        }

        .admin-text span {
            color: #8a94a6;
            font-size: 12px;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .welcome p {
            color: #7b8494;
            font-size: 14px;
        }

        /* STATISTICS */
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            border: 1px solid #e8ebf0;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            color: #7b8494;
            font-size: 13px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: #e8f0ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card h2 {
            font-size: 27px;
            margin-top: 15px;
        }

        .card small {
            color: #22a06b;
            font-size: 12px;
        }

        /* GRID */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .panel {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 20px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .panel-header h3 {
            font-size: 16px;
        }

        .panel-header span {
            color: #8a94a6;
            font-size: 12px;
        }

        /* CHART */
        .chart {
            height: 230px;
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            padding-top: 20px;
            border-bottom: 1px solid #e5e7eb;
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
            background: #2563eb;
            border-radius: 5px 5px 0 0;
        }

        .bar-wrapper span {
            font-size: 11px;
            color: #8a94a6;
        }

        /* RESERVATION */
        .reservation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 13px 0;
            border-bottom: 1px solid #eef0f3;
        }

        .reservation:last-child {
            border-bottom: none;
        }

        .reservation strong {
            display: block;
            font-size: 13px;
        }

        .reservation span {
            font-size: 11px;
            color: #8a94a6;
        }

        .status {
            padding: 5px 9px;
            background: #e8f8f0;
            color: #16804f;
            border-radius: 20px;
            font-size: 10px;
        }

        /* NOTIFICATION */
        .notification {
            padding: 13px 0;
            border-bottom: 1px solid #eef0f3;
        }

        .notification:last-child {
            border-bottom: none;
        }

        .notification strong {
            font-size: 13px;
        }

        .notification p {
            font-size: 11px;
            color: #8a94a6;
            margin-top: 4px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
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
    <a href="{{ route('dashboard') }}" class="active">
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

    <a href="{{ route('promo.index') }}">
        🏷️ <span>Promo</span>
    </a>

    <a href="{{ route('review.index') }}">

                ⭐ <span>
                    Review
                </span>

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

            <h3>Dashboard</h3>

            <div class="admin-info">

                <div class="admin-text">
                    <strong>{{ session('admin_pool_nama') }}</strong>
                    <span>{{ session('admin_pool_id') }}</span>
                </div>

                <div class="avatar">
                    {{ strtoupper(substr(session('admin_pool_nama'), 0, 1)) }}
                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <section class="content">

            <div class="welcome">
                <h1>Selamat Datang 👋</h1>

                <p>
                    Pantau aktivitas dan pengelolaan kolam renang
                    melalui dashboard NEXPOOL.
                </p>
            </div>


            <!-- STATISTICS -->
            <div class="cards">

                <div class="card">

                    <div class="card-top">
                        <div class="card-title">
                            Total Pengunjung
                        </div>

                        <div class="card-icon">
                            👥
                        </div>
                    </div>

                    <h2>245</h2>

                    <small>+12% bulan ini</small>

                </div>


                <div class="card">

                    <div class="card-top">
                        <div class="card-title">
                            Tiket Terjual
                        </div>

                        <div class="card-icon">
                            🎟
                        </div>
                    </div>

                    <h2>220</h2>

                    <small>+8% bulan ini</small>

                </div>


                <div class="card">

                    <div class="card-top">
                        <div class="card-title">
                            Promo Aktif
                        </div>

                        <div class="card-icon">
                            🏷
                        </div>
                    </div>

                    <h2>2</h2>

                    <small>Promo sedang berjalan</small>

                </div>

            </div>


            <!-- CONTENT GRID -->
            <div class="dashboard-grid">

                <!-- LEFT -->
                <div>

                    <div class="panel">

                        <div class="panel-header">
                            <h3>Penjualan Tiket</h3>
                            <span>7 Hari Terakhir</span>
                        </div>

                        <div class="chart">

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 45%;"></div>
                                <span>Sen</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 60%;"></div>
                                <span>Sel</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 50%;"></div>
                                <span>Rab</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 75%;"></div>
                                <span>Kam</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 65%;"></div>
                                <span>Jum</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 90%;"></div>
                                <span>Sab</span>
                            </div>

                            <div class="bar-wrapper">
                                <div class="bar" style="height: 80%;"></div>
                                <span>Min</span>
                            </div>

                        </div>

                    </div>


                    <!-- RESERVATION -->
                    <div class="panel">

                        <div class="panel-header">
                            <h3>Reservasi Terbaru</h3>
                            <span>Lihat Semua</span>
                        </div>

                        <div class="reservation">
                            <div>
                                <strong>TRX001</strong>
                                <span>12 September 2026</span>
                            </div>

                            <div class="status">
                                Berhasil
                            </div>
                        </div>

                        <div class="reservation">
                            <div>
                                <strong>TRX002</strong>
                                <span>12 September 2026</span>
                            </div>

                            <div class="status">
                                Berhasil
                            </div>
                        </div>

                        <div class="reservation">
                            <div>
                                <strong>TRX003</strong>
                                <span>13 September 2026</span>
                            </div>

                            <div class="status">
                                Berhasil
                            </div>
                        </div>

                    </div>

                </div>


                <!-- RIGHT -->
                <div>

                    <div class="panel">

                        <div class="panel-header">
                            <h3>Notifikasi</h3>
                            <span>3 Baru</span>
                        </div>

                        <div class="notification">

                            <strong>💳 Pembayaran baru</strong>

                            <p>
                                Pembayaran TRX003 telah diterima.
                            </p>

                        </div>

                        <div class="notification">

                            <strong>⭐ Review baru</strong>

                            <p>
                                Pengunjung memberikan review baru.
                            </p>

                        </div>

                        <div class="notification">

                            <strong>🎟 Reservasi baru</strong>

                            <p>
                                Ada reservasi baru masuk.
                            </p>

                        </div>

                    </div>


                    <div class="panel">

                        <div class="panel-header">
                            <h3>Status Kolam</h3>
                        </div>

                        <div class="reservation">

                            <div>
                                <strong>{{ session('admin_pool_id') }}</strong>
                                <span>Kolam yang dikelola</span>
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

</body>
</html>