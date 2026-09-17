<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NEXPOOL Administrator')</title>

    <style>
        /* ==========================================
            RESET & BODY
        ========================================== */
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

        /* ==========================================
            SIDEBAR UTAMA
        ========================================== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #ffffff;
            color: #0f172a;
            padding: 15px;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 14px rgba(15, 23, 42, 0.05);
        }

        /* LOGO */
        .logo {
            text-align: center;
            margin-bottom: 15px;
            padding-top: 5px;
        }

        .logo img {
            width: 75px;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 5px auto;
        }

        .logo h2 {
            font-size: 22px;
            letter-spacing: 1px;
            line-height: 1.2;
            color: #0f172a;
        }

        .logo p {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* JUDUL MENU */
        .menu-title {
            font-size: 11px;
            color: #94a3b8;
            margin: 15px 12px 8px;
            text-transform: uppercase;
        }

        /* MENU */
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #334155;
            padding: 10px 15px;
            margin-bottom: 4px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        /* MENU AKTIF OTOMATIS */
        .menu a:hover,
        .menu a.active {
            background: #edf6ff;
            color: #1d4ed8;
            box-shadow: inset 0 0 0 1px #dbeafe;
        }

        /* ==========================================
            LOGOUT
        ========================================== */
        .logout {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
        }

        .logout a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .logout a:hover {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* ==========================================
            MAIN & HEADER
        ========================================== */
        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        .header {
            height: 75px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
        }

        /* USER PROFILE DI HEADER KANAN */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }

        .user-role {
            font-size: 12px;
            color: #7b8494;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #006D6F;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* ==========================================
            CONTENT AREA
        ========================================== */
        .content {
            padding: 30px;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .logo img,
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
    @stack('styles')
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo.png.png') }}" alt="Logo Tim">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">Menu Utama</div>

        <div class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                ▣ <span>Dashboard</span>
            </a>
            <a href="{{ route('harga-tiket.index') }}" class="{{ request()->routeIs('harga-tiket*') ? 'active' : '' }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>
            <a href="{{ route('fasilitas.index') }}" class="{{ request()->routeIs('fasilitas*') ? 'active' : '' }}">
                🏊 <span>Fasilitas</span>
            </a>
            <a href="{{ route('reservasi.index') }}" class="{{ request()->routeIs('reservasi*') ? 'active' : '' }}">
                📋 <span>Reservasi</span>
            </a>
            <a href="{{ route('promo.index') }}" class="{{ request()->routeIs('promo*') ? 'active' : '' }}">
                🏷️ <span>Promo</span>
            </a>
            <a href="{{ route('review.index') }}" class="{{ request()->routeIs('review*') ? 'active' : '' }}">
                ⭐ <span>Review</span>
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
            <h3>@yield('header-title', 'Dashboard')</h3>
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">Admin Tiara Park</div>
                    <div class="user-role">pool_id_01</div>
                </div>
                <div class="user-avatar">A</div>
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