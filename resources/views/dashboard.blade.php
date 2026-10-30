
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dashboard - NEXPOOL</title>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,Helvetica,sans-serif; }
        body { background:#f6f8fc; color:#172033; }
        a { text-decoration:none; }

        .sidebar {
            position:fixed; inset:0 auto 0 0; width:255px; height:100vh;
            background:linear-gradient(180deg,#101c36 0%,#14213d 55%,#101b32 100%);
            color:white; padding:24px 16px; z-index:1000;
            box-shadow:8px 0 30px rgba(15,23,42,.08);
        }
        .logo { padding:6px 10px 28px; border-bottom:1px solid rgba(255,255,255,.08); margin-bottom:25px; text-align:center; }
        .logo h2 { font-size:22px; letter-spacing:1px; }
        .logo p { margin-top:4px; font-size:10px; color:#91a0b9; letter-spacing:1.3px; }
        .menu-title { padding:0 12px; margin-bottom:10px; font-size:10px; font-weight:bold; color:#73819b; letter-spacing:1.2px; text-transform:uppercase; }
        .menu { display:flex; flex-direction:column; gap:5px; }
        .menu a {
            position:relative; display:flex; align-items:center; gap:13px;
            padding:12px 13px; border-radius:10px; color:#cbd5e1;
            font-size:13px; font-weight:500; transition:.2s;
        }
        .menu a i { width:20px; text-align:center; font-size:15px; }
        .menu a:hover { background:rgba(255,255,255,.07); color:white; transform:translateX(2px); }
        .menu a.active { background:linear-gradient(90deg,#2563eb,#1d4ed8); color:white; box-shadow:0 8px 20px rgba(37,99,235,.25); }
        .menu a.active::before { content:""; position:absolute; left:-16px; top:8px; width:3px; height:calc(100% - 16px); border-radius:0 5px 5px 0; background:#60a5fa; }
        .logout { position:absolute; bottom:20px; left:16px; right:16px; padding-top:15px; border-top:1px solid rgba(255,255,255,.08); }
        .logout a { display:flex; align-items:center; gap:13px; padding:12px 13px; border-radius:10px; color:#fca5a5; font-size:13px; }
        .logout a:hover { background:rgba(239,68,68,.12); color:#fecaca; }
        .logout i { width:20px; text-align:center; }

        .main { margin-left:255px; min-height:100vh; }
        .header {
            min-height:76px; background:rgba(255,255,255,.96); backdrop-filter:blur(10px);
            display:flex; align-items:center; justify-content:space-between; gap:20px;
            padding:14px 32px; border-bottom:1px solid #e8ecf3;
            position:sticky; top:0; z-index:900;
        }
        .header-left h3 { font-size:18px; color:#111827; }
        .header-left p { margin-top:3px; font-size:11px; color:#8a94a6; }
        .admin-info { display:flex; align-items:center; gap:12px; }
        .admin-text { text-align:right; }
        .admin-text strong { display:block; font-size:13px; color:#172033; }
        .admin-text span { display:block; margin-top:3px; font-size:11px; color:#8a94a6; }
        .avatar {
            width:42px; height:42px; border-radius:12px; display:flex;
            align-items:center; justify-content:center;
            background:linear-gradient(135deg,#2563eb,#38bdf8);
            color:white; font-size:15px; font-weight:bold;
        }

        .content { padding:32px; max-width:1700px; }
        .page-header { display:flex; align-items:center; gap:20px; margin-bottom:25px; }
        .page-title-wrapper { display:flex; align-items:center; gap:14px; }
        .page-icon {
            width:48px; height:48px; flex-shrink:0; border-radius:13px;
            display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#dbeafe,#eff6ff); color:#2563eb; font-size:19px;
        }
        .page-header h1 { font-size:25px; color:#111827; margin-bottom:6px; }
        .page-header p { color:#7b8494; font-size:13px; line-height:1.5; }

        .cards { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px; margin-bottom:22px; }
        .stat-card {
            background:white; border:1px solid #e8ecf3; border-radius:16px;
            padding:22px; box-shadow:0 3px 12px rgba(15,23,42,.025); transition:.2s;
        }
        .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(15,23,42,.06); }
        .card-top { display:flex; justify-content:space-between; align-items:center; gap:12px; }
        .card-title { color:#7b8494; font-size:12px; font-weight:600; }
        .card-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:16px; }
        .icon-blue { background:#dbeafe; color:#2563eb; }
        .icon-green { background:#d1fae5; color:#059669; }
        .icon-purple { background:#ede9fe; color:#7c3aed; }
        .stat-card h2 { font-size:28px; margin-top:15px; color:#172033; }
        .stat-card small { display:block; margin-top:7px; color:#16804f; font-size:11px; }

        .dashboard-grid { display:grid; grid-template-columns:minmax(0,1.7fr) minmax(270px,1fr); gap:20px; align-items:start; }
        .panel { background:white; border:1px solid #e8ecf3; border-radius:16px; padding:22px; margin-bottom:20px; box-shadow:0 3px 12px rgba(15,23,42,.025); min-width:0; }
        .panel-header { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; }
        .panel-heading { display:flex; align-items:center; gap:9px; }
        .panel-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; background:#eef4ff; color:#2563eb; font-size:12px; flex-shrink:0; }
        .panel-header h3 { font-size:14px; color:#172033; }
        .panel-header > span,.panel-link { color:#8a94a6; font-size:11px; white-space:nowrap; }
        .panel-link { color:#2563eb; font-weight:600; }

        .chart-summary { display:flex; align-items:baseline; gap:8px; margin:-5px 0 8px; }
        .chart-summary strong { font-size:24px; color:#172033; }
        .chart-summary span { font-size:11px; color:#16804f; }
        .chart { height:210px; display:flex; align-items:stretch; justify-content:space-around; gap:10px; padding:18px 5px 0; border-bottom:1px solid #e5eaf1; }
        .bar-wrapper { height:100%; flex:1; min-width:0; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; gap:9px; }
        .bar-track { width:min(100%,38px); flex:1; display:flex; align-items:flex-end; background:#f1f5fb; border-radius:7px 7px 0 0; overflow:hidden; }
        .bar { width:100%; background:linear-gradient(180deg,#60a5fa,#2563eb); border-radius:6px 6px 0 0; min-height:8px; }
        .bar-wrapper span { font-size:10px; color:#8a94a6; padding-bottom:8px; }

        .reservation { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid #eef1f5; }
        .reservation:last-child,.notification:last-child { border-bottom:none; padding-bottom:0; }
        .reservation strong { display:block; font-size:12px; color:#334155; overflow-wrap:anywhere; }
        .reservation span { display:block; margin-top:5px; font-size:10px; color:#8a94a6; }
        .status { padding:6px 10px; background:#dcfce7; color:#15803d; border-radius:20px; font-size:10px; font-weight:700; white-space:nowrap; }
        .notification { display:flex; gap:11px; padding:14px 0; border-bottom:1px solid #eef1f5; }
        .notification-icon { width:34px; height:34px; flex-shrink:0; display:flex; align-items:center; justify-content:center; border-radius:10px; background:#eff6ff; color:#2563eb; font-size:12px; }
        .notification strong { font-size:12px; color:#334155; }
        .notification p { font-size:11px; line-height:1.5; color:#8a94a6; margin-top:5px; }
        .pool-status { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px; border:1px solid #e8ecf3; border-radius:12px; background:#fbfdff; }
        .pool-status-info { display:flex; align-items:center; gap:10px; min-width:0; }
        .pool-status-icon { width:38px; height:38px; border-radius:11px; background:#dbeafe; color:#2563eb; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pool-status strong { display:block; font-size:12px; color:#334155; overflow-wrap:anywhere; }
        .pool-status p { margin-top:4px; font-size:10px; color:#8a94a6; }
        .live-dot { display:inline-block; width:7px; height:7px; background:#22c55e; border-radius:50%; margin-right:5px; }

        @media(max-width:1100px) {
            .content { padding:25px; }
            .dashboard-grid { grid-template-columns:minmax(0,1.3fr) minmax(250px,1fr); gap:15px; }
            .cards { gap:14px; }
            .stat-card,.panel { padding:18px; }
        }
        @media(max-width:900px) {
            .dashboard-grid { grid-template-columns:1fr; }
            .cards { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .cards .stat-card:last-child { grid-column:1 / -1; }
        }
        @media(max-width:800px) {
            .sidebar { width:70px; padding:20px 10px; }
            .logo { padding:6px 0 20px; }
            .logo h2,.logo p,.menu-title,.menu a span,.logout span { display:none; }
            .menu a,.logout a { justify-content:center; }
            .menu a { padding:13px 8px; }
            .menu a.active::before { left:-10px; }
            .main { margin-left:70px; }
            .header { padding:14px 20px; }
            .content { padding:20px; }
            .admin-text { display:none; }
        }
        @media(max-width:520px) {
            .header-left h3 { font-size:16px; }
            .header-left p { max-width:210px; line-height:1.4; }
            .content { padding:16px; }
            .page-header { align-items:flex-start; }
            .page-header h1 { font-size:21px; }
            .page-title-wrapper { align-items:flex-start; gap:11px; }
            .page-icon { width:42px; height:42px; border-radius:11px; }
            .cards { grid-template-columns:1fr; gap:12px; }
            .cards .stat-card:last-child { grid-column:auto; }
            .stat-card { padding:18px; }
            .panel { padding:17px; }
            .panel-header { align-items:flex-start; }
            .chart { height:185px; gap:5px; }
            .bar-wrapper span { font-size:9px; }
        }
    </style>
</head>
<body>
    @php
        $adminNama = session('admin_nama') ?? session('admin_pool_nama') ?? 'Administrator';
        $adminPoolId = session('admin_pool_id') ?? '-';
    @endphp

    <aside class="sidebar">
        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">Menu Utama</div>
        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="active">
                <i class="fa-solid fa-gauge-high" style="color:#bfdbfe;"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket" style="color:#fcd34d;"></i><span>Manajemen Tiket</span>
            </a>
            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming" style="color:#67e8f9;"></i><span>Fasilitas</span>
            </a>
            <a href="{{ route('reservasi.index') }}">
                <i class="fa-solid fa-calendar-check" style="color:#6ee7b7;"></i><span>Reservasi</span>
            </a>
            <a href="{{ route('promo.index') }}">
                <i class="fa-solid fa-tags" style="color:#c4b5fd;"></i><span>Promo</span>
            </a>
            <a href="{{ route('review.index') }}">
                <i class="fa-solid fa-star" style="color:#fde047;"></i><span>Review</span>
            </a>
        </nav>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="main">
        <header class="header">
            <div class="header-left">
                <h3>Dashboard</h3>
                <p>Ringkasan aktivitas kolam renang</p>
            </div>
            <div class="admin-info">
                <div class="admin-text">
                    <strong>{{ $adminNama }}</strong>
                    <span>{{ $adminPoolId }}</span>
                </div>
                <div class="avatar">{{ strtoupper(substr($adminNama, 0, 1)) }}</div>
            </div>
        </header>

        <section class="content">
            <div class="page-header">
                <div class="page-title-wrapper">
                    <div class="page-icon"><i class="fa-solid fa-gauge-high"></i></div>
                    <div>
                        <h1>Dashboard NEXPOOL</h1>
                        <p>Selamat datang! Pantau aktivitas dan pengelolaan kolam renang melalui ringkasan berikut.</p>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="cards">
                <div class="stat-card">
                    <div class="card-top">
                        <div class="card-title">Total Pengunjung</div>
                        <div class="card-icon icon-blue"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <h2>{{ number_format($totalPengunjung ?? 0, 0, ',', '.') }}</h2>
                    <small><i class="fa-solid fa-database"></i> Data dari database</small>
                </div>

                <div class="stat-card">
                    <div class="card-top">
                        <div class="card-title">Total Reservasi</div>
                        <div class="card-icon icon-green"><i class="fa-solid fa-ticket"></i></div>
                    </div>
                    <h2>{{ number_format($totalTiket ?? 0, 0, ',', '.') }}</h2>
                    <small><i class="fa-solid fa-database"></i> Jumlah data reservasi</small>
                </div>

                <div class="stat-card">
                    <div class="card-top">
                        <div class="card-title">Promo Aktif</div>
                        <div class="card-icon icon-purple"><i class="fa-solid fa-tags"></i></div>
                    </div>
                    <h2>{{ number_format($promoAktif ?? 0, 0, ',', '.') }}</h2>
                    <small><i class="fa-solid fa-circle-check"></i> Promo yang memenuhi filter controller</small>
                </div>
            </div>

            <div class="dashboard-grid">
                <div>
                    <!-- Grafik -->
                    <section class="panel">
                        <div class="panel-header">
                            <div class="panel-heading">
                                <div class="panel-icon"><i class="fa-solid fa-chart-column"></i></div>
                                <h3>Penjualan Tiket</h3>
                            </div>
                            <span>Ilustrasi 7 Hari</span>
                        </div>

                        <div class="chart-summary">
                            <strong>{{ number_format($totalTiket ?? 0, 0, ',', '.') }}</strong>
                            <span><i class="fa-solid fa-ticket"></i> Total reservasi</span>
                        </div>

                        <div class="chart" aria-label="Ilustrasi grafik tujuh hari terakhir">
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:45%"></div></div><span>Sen</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:60%"></div></div><span>Sel</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:50%"></div></div><span>Rab</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:75%"></div></div><span>Kam</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:65%"></div></div><span>Jum</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:90%"></div></div><span>Sab</span></div>
                            <div class="bar-wrapper"><div class="bar-track"><div class="bar" style="height:80%"></div></div><span>Min</span></div>
                        </div>
                    </section>

                    <!-- Reservasi Terbaru -->
                    <section class="panel">
                        <div class="panel-header">
                            <div class="panel-heading">
                                <div class="panel-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                <h3>Reservasi Terbaru</h3>
                            </div>
                            <a class="panel-link" href="{{ route('reservasi.index') }}">
                                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>

                        @forelse ($reservasiTerbaru ?? [] as $reservasi)
                            <div class="reservation">
                                <div>
                                    <strong>
                                        {{ data_get($reservasi, 'kode_reservasi', 'Reservasi') }}
                                    </strong>
                                    <span>
                                        @php
                                            $tanggal = data_get($reservasi, 'tanggal_kunjungan')
                                                ?? data_get($reservasi, 'created_at');
                                        @endphp
                                        {{ $tanggal ? \Illuminate\Support\Carbon::parse($tanggal)->format('d M Y') : 'Tanggal tidak tersedia' }}
                                    </span>
                                    <span>
                                        {{ data_get($reservasi, 'nama_pengunjung', 'Pengunjung') }}
                                    </span>
                                </div>
                                <div class="status">
                                    {{ data_get($reservasi, 'status_reservasi', 'Tercatat') }}
                                </div>
                            </div>
                        @empty
                            <p style="color:#8a94a6;font-size:12px;">
                                Belum ada data reservasi.
                            </p>
                        @endforelse
                    </section>
                </div>

                <div>
                    <!-- Notifikasi -->
                    <section class="panel">
                        <div class="panel-header">
                            <div class="panel-heading">
                                <div class="panel-icon"><i class="fa-solid fa-bell"></i></div>
                                <h3>Informasi</h3>
                            </div>
                            <span>NEXPOOL</span>
                        </div>

                        <div class="notification">
                            <div class="notification-icon"><i class="fa-solid fa-calendar-check"></i></div>
                            <div>
                                <strong>Reservasi terbaru</strong>
                                <p>
                                    @if (($totalTiket ?? 0) > 0)
                                        Terdapat {{ number_format($totalTiket, 0, ',', '.') }} data reservasi di dashboard.
                                    @else
                                        Belum terdapat data reservasi.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="notification">
                            <div class="notification-icon"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <strong>Data pengunjung</strong>
                                <p>
                                    Saat ini terdapat {{ number_format($totalPengunjung ?? 0, 0, ',', '.') }} data pengunjung yang dihitung controller.
                                </p>
                            </div>
                        </div>

                        <div class="notification">
                            <div class="notification-icon"><i class="fa-solid fa-tags"></i></div>
                            <div>
                                <strong>Informasi promo</strong>
                                <p>
                                    Terdapat {{ number_format($promoAktif ?? 0, 0, ',', '.') }} promo berdasarkan filter controller.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Status Kolam -->
                    <section class="panel">
                        <div class="panel-header">
                            <div class="panel-heading">
                                <div class="panel-icon"><i class="fa-solid fa-person-swimming"></i></div>
                                <h3>Status Kolam</h3>
                            </div>
                        </div>

                        <div class="pool-status">
                            <div class="pool-status-info">
                                <div class="pool-status-icon"><i class="fa-solid fa-water"></i></div>
                                <div>
                                    <strong>{{ session('admin_pool_nama') ?? $adminPoolId }}</strong>
                                    <p>Kolam yang dikelola</p>
                                </div>
                            </div>
                            <div class="status"><span class="live-dot"></span>Aktif</div>
                        </div>
                    </section>
                </div>
            </div>

            <p style="margin:2px 0 8px;color:#94a3b8;font-size:10px;">
                Statistik dan daftar reservasi ditampilkan berdasarkan variabel yang dikirim controller. Grafik batang masih berupa ilustrasi.
            </p>
        </section>
    </main>
</body>
</html>
