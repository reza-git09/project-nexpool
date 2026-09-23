<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Manajemen Tiket - NEXPOOL</title>

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
           BUTTON TAMBAH
        ========================================================= */

        .btn-tambah {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            color: white;

            padding: 11px 17px;

            border-radius: 10px;

            font-size: 12px;

            font-weight: 600;

            box-shadow:
                0 7px 16px rgba(37,99,235,0.20);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn-tambah:hover {
            transform: translateY(-2px);

            box-shadow:
                0 10px 22px rgba(37,99,235,0.28);
        }

        /* =========================================================
           ALERT
        ========================================================= */

        .alert {
            display: flex;

            align-items: center;

            gap: 9px;

            background: #ecfdf5;

            color: #166534;

            border:
                1px solid #bbf7d0;

            padding: 12px 15px;

            border-radius: 10px;

            margin-bottom: 20px;

            font-size: 12px;
        }

        /* =========================================================
           TABLE CARD
        ========================================================= */

        .table-card {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.025);
        }

        .table-top {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 20px 21px;

            border-bottom:
                1px solid #eef1f5;
        }

        .table-title {
            display: flex;

            align-items: center;

            gap: 9px;
        }

        .table-title-icon {
            width: 32px;
            height: 32px;

            border-radius: 9px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eef4ff;

            color: #2563eb;

            font-size: 12px;
        }

        .table-title h3 {
            font-size: 14px;

            color: #172033;
        }

        .table-info {
            font-size: 11px;

            color: #8a94a6;
        }

        /* =========================================================
           TABLE WRAPPER
        ========================================================= */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 760px;
        }

        /* =========================================================
           TABLE HEADER
        ========================================================= */

        th {
            background: #f8fafc;

            color: #64748b;

            font-size: 11px;

            font-weight: 600;

            text-align: left;

            padding: 13px 20px;

            border-bottom:
                1px solid #e5e7eb;

            text-transform: uppercase;

            letter-spacing: 0.3px;
        }

        /* =========================================================
           TABLE DATA
        ========================================================= */

        td {
            padding: 14px 20px;

            font-size: 12px;

            color: #475569;

            border-bottom:
                1px solid #eef1f5;

            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.15s ease;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================================================
           NUMBER
        ========================================================= */

        .number {
            width: 60px;

            color: #94a3b8;

            font-size: 11px;
        }

        /* =========================================================
           CATEGORY
        ========================================================= */

        .category-wrapper {
            display: flex;

            align-items: center;

            gap: 9px;
        }

        .category-icon {
            width: 31px;
            height: 31px;

            border-radius: 9px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 11px;
        }

        .category-icon.dewasa {
            background: #dbeafe;

            color: #2563eb;
        }

        .category-icon.anak {
            background: #fef3c7;

            color: #d97706;
        }

        .category-name {
            font-weight: 600;

            color: #334155;
        }

        /* =========================================================
           BADGES
        ========================================================= */

        .badge {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 6px 9px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 600;
        }

        .badge-weekday {
            background: #dcfce7;

            color: #15803d;
        }

        .badge-weekend {
            background: #fee2e2;

            color: #dc2626;
        }

        /* =========================================================
           PRICE
        ========================================================= */

        .harga {
            font-weight: 700;

            color: #172033;

            font-size: 13px;
        }

        /* =========================================================
           ACTION
        ========================================================= */

        .actions {
            display: flex;

            align-items: center;

            gap: 6px;
        }

        .btn-edit,
        .btn-hapus {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            border: none;

            padding: 7px 10px;

            border-radius: 7px;

            font-size: 10px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }

        /* EDIT */

        .btn-edit {
            background: #dbeafe;

            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #bfdbfe;

            transform: translateY(-1px);
        }

        /* DELETE */

        .btn-hapus {
            background: #fee2e2;

            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #fecaca;

            transform: translateY(-1px);
        }

        /* =========================================================
           EMPTY
        ========================================================= */

        .empty {
            text-align: center;

            padding: 70px 30px;

            color: #94a3b8;
        }

        .empty-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 15px;

            border-radius: 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f1f5f9;

            color: #94a3b8;

            font-size: 21px;
        }

        .empty h3 {
            margin-bottom: 7px;

            color: #475569;

            font-size: 14px;
        }

        .empty p {
            font-size: 11px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1000px) {

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }

            .content {
                padding: 25px;
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

            .page-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .btn-tambah {
                width: 100%;

                justify-content: center;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .table-top {
                padding: 16px;
            }

            th,
            td {
                padding-left: 14px;
                padding-right: 14px;
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

            <a href="{{ route('dashboard') }}">

                <i class="fa-solid fa-gauge-high"
                    style="color:#bfdbfe;">
                </i>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- MANAJEMEN TIKET -->

            <a href="{{ route('harga-tiket.index') }}"
                class="active">

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
                    Manajemen Tiket
                </h3>

                <p>
                    Kelola harga tiket kolam renang
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

                        <i class="fa-solid fa-ticket"></i>

                    </div>


                    <div>

                        <h1>
                            Harga Tiket
                        </h1>

                        <p>
                            Kelola harga tiket berdasarkan kategori dan jenis hari.
                        </p>

                    </div>

                </div>


                <!-- TAMBAH -->

                <a href="{{ route('harga-tiket.create') }}"
                    class="btn-tambah">

                    <i class="fa-solid fa-plus"></i>

                    Tambah Harga

                </a>

            </div>


            <!-- ALERT SUCCESS -->

            @if(session('success'))

                <div class="alert">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            <!-- TABLE CARD -->

            <div class="table-card">

                <!-- TABLE HEADER -->

                <div class="table-top">

                    <div class="table-title">

                        <div class="table-title-icon">

                            <i class="fa-solid fa-list"></i>

                        </div>

                        <h3>
                            Daftar Harga Tiket
                        </h3>

                    </div>


                    <div class="table-info">

                        {{ $hargaTiket->count() }} data harga

                    </div>

                </div>


                <!-- TABLE -->

                <div class="table-wrapper">

                    @if($hargaTiket->count() > 0)

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Kategori
                                    </th>

                                    <th>
                                        Jenis Hari
                                    </th>

                                    <th>
                                        Harga Tiket
                                    </th>

                                    <th>
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($hargaTiket as $item)

                                    <tr>

                                        <!-- NO -->

                                        <td class="number">

                                            {{ $loop->iteration }}

                                        </td>


                                        <!-- KATEGORI -->

                                        <td>

                                            <div class="category-wrapper">

                                                @if($item->kategori == 'Dewasa')

                                                    <div class="category-icon dewasa">

                                                        <i class="fa-solid fa-user"></i>

                                                    </div>

                                                    <span class="category-name">
                                                        Dewasa
                                                    </span>

                                                @else

                                                    <div class="category-icon anak">

                                                        <i class="fa-solid fa-child"></i>

                                                    </div>

                                                    <span class="category-name">
                                                        Anak
                                                    </span>

                                                @endif

                                            </div>

                                        </td>


                                        <!-- JENIS HARI -->

                                        <td>

                                            @if($item->jenis_hari == 'Weekday')

                                                <span class="badge badge-weekday">

                                                    <i class="fa-regular fa-calendar"></i>

                                                    Weekday

                                                </span>

                                            @else

                                                <span class="badge badge-weekend">

                                                    <i class="fa-solid fa-calendar-days"></i>

                                                    Weekend

                                                </span>

                                            @endif

                                        </td>


                                        <!-- HARGA -->

                                        <td class="harga">

                                            Rp
                                            {{ number_format($item->harga, 0, ',', '.') }}

                                        </td>


                                        <!-- AKSI -->

                                        <td>

                                            <div class="actions">

                                                <!-- EDIT -->

                                                <a href="{{ route('harga-tiket.edit', $item->id) }}"
                                                    class="btn-edit">

                                                    <i class="fa-solid fa-pen-to-square"></i>

                                                    Edit

                                                </a>


                                                <!-- HAPUS -->

                                                <form
                                                    action="{{ route('harga-tiket.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus harga tiket ini?')"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn-hapus">

                                                        <i class="fa-solid fa-trash"></i>

                                                        Hapus

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    @else

                        <!-- DATA KOSONG -->

                        <div class="empty">

                            <div class="empty-icon">

                                <i class="fa-solid fa-ticket"></i>

                            </div>

                            <h3>
                                Belum Ada Harga Tiket
                            </h3>

                            <p>
                                Silakan tambahkan harga tiket terlebih dahulu.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </section>

    </main>

</body>

</html>