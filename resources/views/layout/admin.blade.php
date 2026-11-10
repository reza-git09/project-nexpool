<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>@yield('title', 'NEXPOOL Administrator')</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { background: #f6f8fc; color: #172033; }
        a { text-decoration: none; }
        .sidebar {
            position: fixed; left: 0; top: 0; width: 255px; height: 100vh;
            background: linear-gradient(180deg, #101c36 0%, #14213d 55%, #101b32 100%);
            color: #fff; padding: 24px 16px; z-index: 1000;
            box-shadow: 8px 0 30px rgba(15, 23, 42, .08);
        }
        .logo { padding: 6px 10px 28px; border-bottom: 1px solid rgba(255,255,255,.08); margin-bottom: 25px; text-align: center; }
        .logo h2 { font-size: 22px; letter-spacing: 1px; }
        .logo p { margin-top: 5px; font-size: 10px; color: #91a0b9; letter-spacing: 1.3px; }
        .menu-title { padding: 0 12px; margin-bottom: 10px; font-size: 10px; font-weight: bold; color: #73819b; letter-spacing: 1.2px; text-transform: uppercase; }
        .menu { display: flex; flex-direction: column; gap: 5px; }
        .menu a, .logout a { display: flex; align-items: center; gap: 13px; padding: 12px 13px; border-radius: 10px; font-size: 13px; }
        .menu a { position: relative; color: #cbd5e1; font-weight: 500; transition: background .2s, color .2s, transform .2s; }
        .menu a i, .logout a i { width: 20px; text-align: center; }
        .menu a i { font-size: 15px; }
        .menu a:hover { background: rgba(255,255,255,.07); color: #fff; transform: translateX(2px); }
        .menu a.active { background: linear-gradient(90deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 8px 20px rgba(37,99,235,.25); }
        .menu a.active::before { content: ""; position: absolute; left: -16px; top: 8px; width: 3px; height: calc(100% - 16px); border-radius: 0 5px 5px 0; background: #60a5fa; }
        .logout { position: absolute; bottom: 20px; left: 16px; right: 16px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,.08); }
        .logout a { color: #fca5a5; transition: .2s; }
        .logout a:hover { background: rgba(239,68,68,.12); color: #fecaca; }
        .main { margin-left: 255px; min-height: 100vh; }
        .header {
            height: 76px; background: rgba(255,255,255,.96); backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: space-between; padding: 0 32px;
            border-bottom: 1px solid #e8ecf3; position: sticky; top: 0; z-index: 900;
        }
        .header-left h3 { font-size: 18px; font-weight: 700; color: #111827; }
        .header-left p { margin-top: 3px; font-size: 11px; color: #8a94a6; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-info { text-align: right; }
        .user-name { display: block; font-size: 13px; font-weight: bold; }
        .user-role { display: block; margin-top: 3px; font-size: 11px; color: #8a94a6; }
        .user-avatar { display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg,#2563eb,#38bdf8); color: #fff; font-size: 15px; font-weight: bold; box-shadow: 0 6px 15px rgba(37,99,235,.2); }
        .content { padding: 32px; max-width: 1700px; }
        @media (max-width: 1000px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .content { padding: 25px; }
        }
        @media (max-width: 800px) {
            .sidebar { width: 70px; padding: 20px 10px; }
            .logo { padding-bottom: 20px; }
            .logo h2, .logo p, .menu-title, .menu a span, .logout span, .user-info { display: none; }
            .menu a, .logout a { justify-content: center; }
            .menu a { padding: 13px 8px; }
            .menu a.active::before { left: -10px; }
            .main { margin-left: 70px; }
            .header { padding: 0 20px; }
            .content { padding: 20px; }
        }
    </style>
    @stack('styles')
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
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high" style="color:#bfdbfe;"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('harga-tiket.index') }}" class="{{ request()->routeIs('harga-tiket*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket" style="color:#fcd34d;"></i><span>Manajemen Tiket</span>
            </a>
            <a href="{{ route('fasilitas.index') }}" class="{{ request()->routeIs('fasilitas*') ? 'active' : '' }}">
                <i class="fa-solid fa-person-swimming" style="color:#67e8f9;"></i><span>Fasilitas</span>
            </a>
            <a href="{{ route('reservasi.index') }}" class="{{ request()->routeIs('reservasi*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check" style="color:#6ee7b7;"></i><span>Reservasi</span>
            </a>
            <a href="{{ route('promo.index') }}" class="{{ request()->routeIs('promo*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags" style="color:#c4b5fd;"></i><span>Promo</span>
            </a>
            <a href="{{ route('review.index') }}" class="{{ request()->routeIs('review*') ? 'active' : '' }}">
                <i class="fa-solid fa-star" style="color:#fde047;"></i><span>Review</span>
            </a>
            <a href="{{ route('admin.laporan.penjualan') }}" class="{{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-column" style="color:#60a5fa;"></i><span>Laporan Penjualan Tiket</span>
            </a>

            @php
                $isAdminMaster = session('admin_role') === 'admin_master' || in_array(session('admin_username'), ['admin_master', 'admin', 'admin_tiara']);
                $pendingAppsCount = \Illuminate\Support\Facades\Schema::hasTable('admin_applications')
                    ? \App\Models\AdminApplication::where('status', 'pending')->count()
                    : 0;
            @endphp

            @if($isAdminMaster)
                <a href="{{ route('admin.master.applications.index') }}" class="{{ request()->routeIs('admin.master.applications*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield" style="color:#f472b6;"></i>
                    <span style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                        Pengajuan Admin
                        @if($pendingAppsCount > 0)
                            <span style="background:#ef4444; color:#fff; border-radius:10px; padding:2px 7px; font-size:10px; font-weight:bold; margin-left:8px;">{{ $pendingAppsCount }}</span>
                        @endif
                    </span>
                </a>
            @endif
        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <!-- HEADER -->
        <header class="header">
            <div class="header-left">
                <h3>@yield('header-title', 'Dashboard')</h3>
                <p>@yield('header-subtitle', 'Kelola dan pantau data NEXPOOL')</p>
            </div>
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">{{ session('admin_pool_nama', 'Admin NEXPOOL') }}</div>
                    <div class="user-role">{{ session('admin_pool_id', 'pool_id_01') }}</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(session('admin_pool_nama', 'Admin NEXPOOL'), 0, 1)) }}</div>
            </div>
        </header>

        <!-- CONTENT DINAMIS -->
        <section class="content">
            @yield('content')
        </section>
    </main>

    @stack('scripts')
</body>

</html>
