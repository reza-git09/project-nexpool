<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'NEXPOOL Administrator')</title>

    <style>
        /* ==========================================
            RESET
        ========================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            background: #f5f7fb;
            color: #1f2937;
        }

        /* ==========================================
            SIDEBAR
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

            transition: transform 0.3s ease;
        }

        /* ==========================================
            LOGO
        ========================================== */
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

        /* ==========================================
            MENU TITLE
        ========================================== */
        .menu-title {
            font-size: 11px;
            color: #94a3b8;
            margin: 15px 12px 8px;
            text-transform: uppercase;
        }

        /* ==========================================
            MENU
        ========================================== */
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
            MAIN
        ========================================== */
        .main {
            margin-left: 240px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ==========================================
            HEADER
        ========================================== */
        .header {
            min-height: 75px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            border-bottom: 1px solid #e5e7eb;
            gap: 20px;
        }

        .header h3 {
            font-size: 20px;
            color: #111827;
        }

        /* ==========================================
            USER PROFILE
        ========================================== */
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
            min-width: 35px;
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
            CONTENT
        ========================================== */
        .content {
            padding: 30px;
            width: 100%;
        }

        /* ==========================================
            TOMBOL MENU MOBILE
        ========================================== */
        .mobile-menu-btn {
            display: none;
            border: none;
            background: #1d4ed8;
            color: white;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            font-size: 22px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        /* ==========================================
            OVERLAY MOBILE
        ========================================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 999;
        }

        /* ==========================================
            TABLET
        ========================================== */
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

            .sidebar .menu a {
                justify-content: center;
                padding: 12px 8px;
                font-size: 18px;
            }

            .sidebar .logout a {
                justify-content: center;
                padding: 12px 8px;
                font-size: 18px;
            }

            .main {
                margin-left: 70px;
            }

            .content {
                padding: 25px;
            }
        }

        /* ==========================================
            HP
        ========================================== */
        @media (max-width: 600px) {

            /* Sidebar disembunyikan */
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
                box-shadow: 5px 0 20px rgba(15, 23, 42, 0.15);
            }

            /* Sidebar saat dibuka */
            .sidebar.mobile-open {
                transform: translateX(0);
            }

            /* Semua isi sidebar muncul lagi */
            .sidebar.mobile-open .logo img,
            .sidebar.mobile-open .logo h2,
            .sidebar.mobile-open .logo p,
            .sidebar.mobile-open .menu-title,
            .sidebar.mobile-open .menu a span,
            .sidebar.mobile-open .logout span {
                display: block;
            }

            .sidebar.mobile-open .menu a {
                justify-content: flex-start;
                padding: 10px 15px;
                font-size: 14px;
            }

            .sidebar.mobile-open .logout a {
                justify-content: flex-start;
                padding: 12px 15px;
                font-size: 14px;
            }

            /* Main memenuhi layar */
            .main {
                margin-left: 0;
            }

            /* Header */
            .header {
                min-height: 65px;
                padding: 12px 15px;
            }

            .header h3 {
                font-size: 17px;
            }

            /* Tombol hamburger */
            .mobile-menu-btn {
                display: flex;
            }

            /* Profil */
            .user-profile {
                gap: 8px;
            }

            .user-info {
                display: none;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
            }

            /* Content */
            .content {
                padding: 15px;
            }

            /* Overlay */
            .sidebar-overlay.active {
                display: block;
            }
        }

        /* ==========================================
            HP SANGAT KECIL
        ========================================== */
        @media (max-width: 400px) {

            .header {
                padding: 10px 12px;
            }

            .header h3 {
                font-size: 15px;
            }

            .content {
                padding: 12px;
            }

            .mobile-menu-btn {
                width: 38px;
                height: 38px;
                font-size: 20px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- OVERLAY MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="logo">
            <img src="{{ asset('images/logo.png.png') }}" alt="Logo Tim">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">
            Menu Utama
        </div>

        <div class="menu">

            <a href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                ▣ <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}"
                class="{{ request()->routeIs('harga-tiket*') ? 'active' : '' }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}"
                class="{{ request()->routeIs('fasilitas*') ? 'active' : '' }}">
                🏊 <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}"
                class="{{ request()->routeIs('reservasi*') ? 'active' : '' }}">
                📋 <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}"
                class="{{ request()->routeIs('promo*') ? 'active' : '' }}">
                🏷️ <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}"
                class="{{ request()->routeIs('review*') ? 'active' : '' }}">
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

            <div style="display: flex; align-items: center; gap: 12px;">

                <button
                    type="button"
                    class="mobile-menu-btn"
                    onclick="openSidebar()">
                    ☰
                </button>

                <h3>
                    @yield('header-title', 'Dashboard')
                </h3>

            </div>

            <!-- USER PROFILE -->
            <div class="user-profile">

                <div class="user-info">

                    <div class="user-name">
                        {{ session('admin_nama', 'Administrator') }}
                    </div>

                    <div class="user-role">
                        {{ session('admin_pool_nama', session('admin_pool_id', '-')) }}
                    </div>

                </div>

                <div class="user-avatar">
                    {{ strtoupper(substr(session('admin_nama', 'A'), 0, 1)) }}
                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <section class="content">
            @yield('content')
        </section>

    </main>

    <!-- JAVASCRIPT MOBILE SIDEBAR -->
    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('mobile-open');
            document.getElementById('sidebarOverlay').classList.add('active');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }

        // Tutup sidebar setelah memilih menu di HP
        document.querySelectorAll('.sidebar .menu a, .sidebar .logout a')
            .forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 600) {
                        closeSidebar();
                    }
                });
            });
    </script>

    @stack('scripts')

</body>

</html>