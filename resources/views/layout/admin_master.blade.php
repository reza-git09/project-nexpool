
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NEXPOOL Admin Master')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f4f7fc;
            color: #172033;
        }

        a { text-decoration: none; }

        .sidebar {
            position: fixed;
            width: 255px;
            height: 100vh;
            left: 0;
            top: 0;
            padding: 25px 16px;
            background: linear-gradient(180deg, #101c36, #14213d, #101b32);
            color: white;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(15,23,42,.08);
        }

        .logo {
            text-align: center;
            padding: 8px 10px 28px;
            margin-bottom: 28px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .logo h2 {
            font-size: 24px;
            letter-spacing: 2px;
        }

        .logo p {
            color: #93a4c0;
            font-size: 10px;
            letter-spacing: 2px;
            margin-top: 6px;
        }

        .menu-title {
            color: #8191ad;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 12px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 13px;
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 13px;
            transition: .2s;
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .menu a:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }

        .menu a.active {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 8px 20px rgba(37,99,235,.25);
        }

        .logout {
            position: absolute;
            bottom: 22px;
            left: 16px;
            right: 16px;
            border-top: 1px solid rgba(255,255,255,.1);
            padding-top: 15px;
        }

        .logout a {
            display: flex;
            gap: 13px;
            align-items: center;
            padding: 12px;
            color: #fca5a5;
            border-radius: 9px;
            font-size: 13px;
        }

        .logout a:hover {
            background: rgba(239,68,68,.12);
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
            color: #172033;
        }

        .header p {
            font-size: 11px;
            color: #8a94a6;
            margin-top: 4px;
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
            margin-top: 4px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            background: linear-gradient(135deg,#2563eb,#38bdf8);
        }

        .content {
            padding: 32px;
            max-width: 1700px;
        }

        @media(max-width: 800px) {
            .sidebar {
                width: 70px;
                padding: 20px 9px;
            }

            .logo h2, .logo p, .menu-title,
            .menu a span, .logout span, .user-info {
                display: none;
            }

            .menu a, .logout a {
                justify-content: center;
                padding: 14px 5px;
            }

            .main { margin-left: 70px; }
            .header { padding: 0 16px; }
            .content { padding: 20px 15px; }
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
            <p>ADMIN MASTER</p>
        </div>

        <div class="menu-title">MENU UTAMA</div>

        <nav class="menu">

            <a href="{{ url('/admin-master/dashboard') }}"
               class="{{ request()->is('admin-master/dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high" style="color:#bfdbfe;"></i>
                <span>Dashboard Master</span>
            </a>

            @if($transactionRoute)
                <a href="{{ route($transactionRoute) }}"
                   class="{{ request()->routeIs('admin.master.transaksi*', 'admin.master.transactions*') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket" style="color:#fcd34d;"></i>
                    <span>Transaksi Pembayaran</span>
                </a>
            @else
                <a href="#" style="opacity:.55; cursor:not-allowed"
                   onclick="return false;">
                    <i class="fa-solid fa-ticket" style="color:#fcd34d;"></i>
                    <span>Transaksi Pembayaran</span>
                </a>
            @endif

            <a href="{{ route('admin.master.penarikan-saldo.index') }}"
               class="{{ request()->routeIs('admin.master.penarikan-saldo.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer" style="color:#86efac;"></i>
                <span>Cairkan Saldo</span>
            </a>

            @if(\Illuminate\Support\Facades\Route::has($applicationRoute))
                <a href="{{ route($applicationRoute) }}"
                   class="{{ request()->routeIs('admin.master.applications*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-plus" style="color:#86efac;"></i>
                    <span>Persetujuan Admin</span>
                </a>
            @else
                <a href="#" style="opacity:.55; cursor:not-allowed"
                   onclick="return false;">
                    <i class="fa-solid fa-user-plus" style="color:#86efac;"></i>
                    <span>Persetujuan Admin</span>
                </a>
            @endif

        </nav>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>

    </aside>

    <main class="main">

        <header class="header">

            <div>
                <h3>@yield('header-title', 'Dashboard Master')</h3>
                <p>@yield('header-subtitle', 'Pusat pengawasan sistem NEXPOOL')</p>
            </div>

            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">
                        {{ session('admin_nama', 'Admin Master') }}
                    </div>
                    <div class="user-role">SUPER ADMIN</div>
                </div>

                <div class="avatar">
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
