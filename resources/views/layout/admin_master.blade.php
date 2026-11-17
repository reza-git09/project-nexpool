
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NEXPOOL Admin Master')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

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

        a { text-decoration: none; }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;
            padding: 24px 16px;
            background: linear-gradient(180deg, #101c36 0%, #14213d 55%, #101b32 100%);
            color: #fff;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(15, 23, 42, .08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .logo {
            flex: 0 0 auto;
            text-align: center;
            padding: 6px 10px 28px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .logo h2 {
            font-size: 22px;
            letter-spacing: 1px;
        }

        .logo p {
            color: #91a0b9;
            font-size: 10px;
            letter-spacing: 1.3px;
            margin-top: 5px;
        }

        .menu-title {
            flex: 0 0 auto;
            color: #73819b;
            font-size: 10px;
            font-weight: bold;
            padding: 0 12px;
            margin-bottom: 10px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .menu {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            gap: 5px;
            min-height: 0;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, .35) transparent;
        }

        .menu::-webkit-scrollbar {
            width: 5px;
        }

        .menu::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .35);
            border-radius: 8px;
        }

        .menu a, .logout a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 10px;
            font-size: 13px;
        }

        .menu a {
            position: relative;
            flex: 0 0 auto;
            color: #cbd5e1;
            font-weight: 500;
            transition: background .2s, color .2s, transform .2s;
        }

        .menu a i, .logout a i {
            flex: 0 0 24px;
            width: 24px;
            height: 20px;
            color: #9fb3d1;
            font-size: 20px;
            line-height: 1;
            text-align: center;
        }

        .menu a:hover i, .menu a.active i { color: #fff; }
        .logout a i { color: #f2a3a3; }

        .menu a:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
            transform: translateX(2px);
        }

        .menu a.active {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 8px 20px rgba(37, 99, 235, .25);
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

        .logout {
            flex: 0 0 auto;
            margin-top: 10px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .logout a {
            color: #f2a3a3;
            transition: .2s;
        }

        .logout a:hover {
            background: rgba(239, 68, 68, .12);
            color: #f2a3a3;
        }

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .header {
            height: 76px;
            background: white;
            border-bottom: 1px solid #e8ecf3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .header p {
            font-size: 11px;
            color: #8a94a6;
            margin-top: 3px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info { text-align: right; }

        .user-name {
            font-size: 13px;
            font-weight: bold;
        }

        .user-role {
            color: #8a94a6;
            font-size: 11px;
            margin-top: 3px;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg,#2563eb,#38bdf8);
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            box-shadow: 0 6px 15px rgba(37,99,235,.2);
        }

        .content {
            padding: 32px;
            max-width: 1700px;
        }

        @media (max-width: 1000px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .content { padding: 25px; }
        }

        @media (max-width: 800px) {
            .sidebar {
                width: 70px;
                padding: 20px 10px 14px;
            }

            .logo { padding-bottom: 20px; }
            .logo h2, .logo p, .menu-title, .menu a span, .logout span, .user-info {
                display: none;
            }

            .menu a, .logout a {
                justify-content: center;
            }

            .menu a {
                padding: 13px 8px;
            }

            .menu a.active::before { left: -10px; }
            .main { margin-left: 70px; }
            .header { padding: 0 20px; }
            .content { padding: 20px; }
        }
    </style>

    @stack('styles')
</head>

<body>

    @php
        $applicationRoute = 'admin.master.applications.index';

        $transactionRoute = null;

        foreach ([
            'admin.master.transaksi.index',
            'admin.master.transactions.index',
            'admin.master.transaksi'
        ] as $candidate) {
            if (\Illuminate\Support\Facades\Route::has($candidate)) {
                $transactionRoute = $candidate;
                break;
            }
        }
    @endphp

    <aside class="sidebar">

        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">MENU UTAMA</div>

        <nav class="menu">

            <a href="{{ url('/admin-master/dashboard') }}"
               class="{{ request()->is('admin-master/dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>

            @if($transactionRoute)
                <a href="{{ route($transactionRoute) }}"
                   class="{{ request()->routeIs('admin.master.transaksi*', 'admin.master.transactions*') ? 'active' : '' }}">
                    <i class="ti ti-credit-card" aria-hidden="true"></i>
                    <span>Transaksi Pembayaran</span>
                </a>
            @else
                <a href="#" style="opacity:.55; cursor:not-allowed"
                   onclick="return false;">
                    <i class="ti ti-credit-card" aria-hidden="true"></i>
                    <span>Transaksi Pembayaran</span>
                </a>
            @endif

            <a href="{{ route('admin.master.penarikan-saldo.index') }}"
               class="{{ request()->routeIs('admin.master.penarikan-saldo.*') ? 'active' : '' }}">
                <i class="ti ti-cash" aria-hidden="true"></i>
                <span>Cairkan Saldo</span>
            </a>

            @if(\Illuminate\Support\Facades\Route::has($applicationRoute))
                <a href="{{ route($applicationRoute) }}"
                   class="{{ request()->routeIs('admin.master.applications*') ? 'active' : '' }}">
                    <i class="ti ti-user-check" aria-hidden="true"></i>
                    <span>Persetujuan Admin</span>
                </a>
            @else
                <a href="#" style="opacity:.55; cursor:not-allowed"
                   onclick="return false;">
                    <i class="ti ti-user-check" aria-hidden="true"></i>
                    <span>Persetujuan Admin</span>
                </a>
            @endif

        </nav>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="ti ti-logout" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </div>

    </aside>

    <main class="main">

        <header class="header">

            <div class="header-left">
                <h3>@yield('header-title', 'Dashboard')</h3>
                <p>@yield('header-subtitle', 'Kelola dan pantau data NEXPOOL')</p>
            </div>

            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">
                        {{ session('admin_nama', 'Admin Master') }}
                    </div>
                    <div class="user-role">SUPER ADMIN</div>
                </div>

                <div class="user-avatar">
                    {{ strtoupper(substr(session('admin_nama', 'A'), 0, 1)) }}
                </div>
            </div>

        </header>

        <section class="content">
            @yield('content')
        </section>

    </main>

    @stack('scripts')

</body>
</html>
