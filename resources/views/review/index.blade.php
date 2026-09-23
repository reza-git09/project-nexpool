<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <title>Review - NEXPOOL</title>


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


        /* =========================
           SIDEBAR
        ========================= */

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

        .logo {
            padding: 6px 10px 28px;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.08);

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
            background: rgba(255, 255, 255, 0.07);
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
                0 8px 20px rgba(37, 99, 235, 0.25);
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


        /* =========================
           LOGOUT
        ========================= */

        .logout {
            position: absolute;

            bottom: 20px;
            left: 16px;
            right: 16px;

            padding-top: 15px;

            border-top:
                1px solid rgba(255, 255, 255, 0.08);
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
                rgba(239, 68, 68, 0.12);

            color: #fecaca;
        }

        .logout i {
            width: 20px;
            text-align: center;
        }


        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }


        /* =========================
           HEADER
        ========================= */

        .header {
            height: 76px;

            background:
                rgba(255, 255, 255, 0.96);

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
                0 6px 15px rgba(37, 99, 235, 0.2);
        }


        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 32px;
            max-width: 1700px;
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 25px;
        }

        .page-title {
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

        .page-title h1 {
            font-size: 25px;
            color: #111827;
            margin-bottom: 6px;
        }

        .page-title p {
            color: #7b8494;
            font-size: 13px;
        }


        /* =========================
           ALERT
        ========================= */

        .alert {
            display: flex;
            align-items: center;
            gap: 8px;

            background: #ecfdf5;
            color: #047857;

            border:
                1px solid #bbf7d0;

            padding: 12px 15px;

            border-radius: 10px;

            margin-bottom: 20px;

            font-size: 12px;
        }


        /* =========================
           INFO BOX
        ========================= */

        .info-box {
            background: #eff6ff;

            border:
                1px solid #bfdbfe;

            color: #1e40af;

            padding: 14px 16px;

            border-radius: 10px;

            margin-bottom: 20px;

            font-size: 12px;

            line-height: 1.6;
        }

        .info-box strong {
            color: #1e3a8a;
        }

        .info-title {
            display: flex;
            align-items: center;
            gap: 7px;

            margin-bottom: 4px;

            font-weight: 700;
        }


        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 3px 12px rgba(15, 23, 42, 0.025);
        }

        .table-top {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 20px 21px;

            border-bottom:
                1px solid #eef1f5;
        }

        .table-top-icon {
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

        .table-top h3 {
            font-size: 14px;
            color: #172033;
        }

        .table-top p {
            margin-top: 3px;
            font-size: 10px;
            color: #94a3b8;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th {
            background: #f8fafc;

            color: #64748b;

            font-size: 11px;
            font-weight: 700;

            text-align: left;

            padding: 13px 20px;

            border-bottom:
                1px solid #e8ecf3;

            white-space: nowrap;

            text-transform: uppercase;

            letter-spacing: 0.3px;
        }

        td {
            padding: 14px 20px;

            font-size: 12px;

            color: #475569;

            border-bottom:
                1px solid #eef1f5;

            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #fafcff;
        }


        /* =========================
           REVIEW DATA
        ========================= */

        .nama {
            font-weight: 600;
            color: #334155;
        }

        .pool {
            font-weight: 600;
            color: #334155;
        }


        /* =========================
           RATING
        ========================= */

        .rating {
            color: #f59e0b;
            font-size: 14px;
            white-space: nowrap;
        }

        .rating-number {
            color: #64748b;
            font-size: 10px;
            margin-left: 4px;
        }


        /* =========================
           KOMENTAR
        ========================= */

        .komentar {
            max-width: 260px;
            line-height: 1.5;
            color: #475569;
        }


        /* =========================
           BALASAN
        ========================= */

        .balasan {
            max-width: 260px;
            line-height: 1.5;
            color: #475569;
        }

        .belum-dibalas {
            color: #94a3b8;
            font-style: italic;
            font-size: 11px;
        }


        /* =========================
           STATUS BADGE
        ========================= */

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 6px 9px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;

            white-space: nowrap;
        }

        .badge-menunggu {
            background: #fff7ed;
            color: #c2410c;
        }

        .badge-dibalas {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-default {
            background: #f1f5f9;
            color: #475569;
        }


        /* =========================
           ACTION
        ========================= */

        .actions {
            display: flex;
            align-items: center;
            gap: 6px;

            white-space: nowrap;
        }

        .btn-reply,
        .btn-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 5px;

            padding: 7px 10px;

            border-radius: 7px;

            font-size: 10px;
            font-weight: 600;

            text-decoration: none;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .btn-reply {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-reply:hover {
            background: #bfdbfe;
            transform: translateY(-1px);
        }

        .btn-detail {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-detail:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }


        /* =========================
           EMPTY
        ========================= */

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

            font-size: 22px;
        }

        .empty h3 {
            margin-bottom: 7px;
            color: #64748b;
            font-size: 14px;
        }

        .empty p {
            font-size: 11px;
            color: #94a3b8;
        }


        /* =========================
           RESPONSIVE
        ========================= */

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
            }

            .page-title h1 {
                font-size: 22px;
            }

            .page-title p {
                line-height: 1.5;
            }

            .info-box {
                font-size: 11px;
            }

            .table-top {
                padding: 16px;
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


        <div class="menu-title">
            Menu Utama
        </div>


        <div class="menu">

            <a href="{{ route('dashboard') }}">

                <i
                    class="fa-solid fa-gauge-high"
                    style="color:#bfdbfe;"
                ></i>

                <span>
                    Dashboard
                </span>

            </a>


            <a href="{{ route('harga-tiket.index') }}">

                <i
                    class="fa-solid fa-ticket"
                    style="color:#fcd34d;"
                ></i>

                <span>
                    Manajemen Tiket
                </span>

            </a>


            <a href="{{ route('fasilitas.index') }}">

                <i
                    class="fa-solid fa-person-swimming"
                    style="color:#67e8f9;"
                ></i>

                <span>
                    Fasilitas
                </span>

            </a>


            <a href="{{ route('reservasi.index') }}">

                <i
                    class="fa-solid fa-calendar-check"
                    style="color:#6ee7b7;"
                ></i>

                <span>
                    Reservasi
                </span>

            </a>


            <a href="{{ route('promo.index') }}">

                <i
                    class="fa-solid fa-tags"
                    style="color:#c4b5fd;"
                ></i>

                <span>
                    Promo
                </span>

            </a>


            <a
                href="{{ route('review.index') }}"
                class="active"
            >

                <i
                    class="fa-solid fa-star"
                    style="color:#fde047;"
                ></i>

                <span>
                    Review
                </span>

            </a>

        </div>


        <div class="logout">

            <a href="{{ route('logout') }}">

                <i
                    class="fa-solid fa-right-from-bracket"
                    style="color:#f87171;"
                ></i>

                <span>
                    Logout
                </span>

            </a>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">


        <!-- HEADER -->

        <header class="header">

            <div class="header-left">

                <h3>
                    Review
                </h3>

                <p>
                    Kelola dan pantau review pengunjung
                </p>

            </div>


            <div class="admin-info">

                <div class="admin-text">

                    <strong>
                        {{ session('admin_pool_nama', 'Admin NEXPOOL') }}
                    </strong>

                    <span>
                        {{ session('admin_pool_id', 'pool_id_01') }}
                    </span>

                </div>


                <div class="avatar">

                    {{ strtoupper(
                        substr(
                            session('admin_pool_nama', 'Admin NEXPOOL'),
                            0,
                            1
                        )
                    ) }}

                </div>

            </div>

        </header>


        <!-- CONTENT -->

        <section class="content">


            <!-- SUCCESS ALERT -->

            @if(session('success'))

                <div class="alert">

                    <i class="fa-solid fa-circle-check"></i>

                    {{ session('success') }}

                </div>

            @endif


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="page-title">

                    <div class="page-icon">

                        <i class="fa-solid fa-star"></i>

                    </div>


                    <div>

                        <h1>
                            Review Pengunjung
                        </h1>

                        <p>
                            Review dan penilaian yang diberikan pengunjung melalui aplikasi mobile NEXPOOL.
                        </p>

                    </div>

                </div>

            </div>


            <!-- INFO -->

            <div class="info-box">

                <div class="info-title">

                    <i
                        class="fa-solid fa-star"
                        style="color:#eab308;"
                    ></i>

                    <strong>
                        Informasi Review
                    </strong>

                </div>


                Review berasal dari
                <strong>
                    aplikasi mobile NEXPOOL
                </strong>
                setelah pengunjung melakukan kunjungan.

                Admin dapat melihat rating dan komentar,
                kemudian memberikan balasan terhadap review.

                <br>

                <strong>
                    Kolam:
                </strong>

                {{ session('admin_pool_nama', 'Kolam Renang') }}

            </div>


            <!-- TABLE CARD -->

            <div class="table-card">


                <div class="table-top">

                    <div class="table-top-icon">

                        <i class="fa-solid fa-star"></i>

                    </div>


                    <div>

                        <h3>
                            Daftar Review
                        </h3>

                        <p>
                            Data review dan penilaian pengunjung yang masuk
                        </p>

                    </div>

                </div>


                @if($review->count() > 0)


                    <div class="table-wrapper">

                        <table>


                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Pengunjung
                                    </th>

                                    <th>
                                        Kolam Renang
                                    </th>

                                    <th>
                                        Rating
                                    </th>

                                    <th>
                                        Komentar
                                    </th>

                                    <th>
                                        Balasan Admin
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @foreach($review as $item)

                                    <tr>


                                        <!-- NO -->

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        <!-- PENGUNJUNG -->

                                        <td class="nama">

                                            {{ $item->nama_pengunjung }}

                                        </td>


                                        <!-- KOLAM -->

                                        <td class="pool">

                                            {{ session('admin_pool_nama', '-') }}

                                        </td>


                                        <!-- RATING -->

                                        <td>

                                            <div class="rating">

                                                @for($i = 1; $i <= 5; $i++)

                                                    @if($i <= $item->rating)

                                                        ★

                                                    @else

                                                        ☆

                                                    @endif

                                                @endfor


                                                <span class="rating-number">

                                                    ({{ $item->rating }}/5)

                                                </span>

                                            </div>

                                        </td>


                                        <!-- KOMENTAR -->

                                        <td>

                                            @if($item->komentar)

                                                <div class="komentar">

                                                    {{ $item->komentar }}

                                                </div>

                                            @else

                                                <span class="belum-dibalas">

                                                    Tidak ada komentar.

                                                </span>

                                            @endif

                                        </td>


                                        <!-- BALASAN -->

                                        <td>

                                            @if($item->balasan_admin)

                                                <div class="balasan">

                                                    {{ $item->balasan_admin }}

                                                </div>

                                            @else

                                                <span class="belum-dibalas">

                                                    Belum ada balasan.

                                                </span>

                                            @endif

                                        </td>


                                        <!-- STATUS -->

                                        <td>

                                            @if($item->status === 'Menunggu')

                                                <span class="badge badge-menunggu">

                                                    <i class="fa-solid fa-clock"></i>

                                                    Menunggu

                                                </span>

                                            @elseif($item->status === 'Dibalas')

                                                <span class="badge badge-dibalas">

                                                    <i class="fa-solid fa-check"></i>

                                                    Dibalas

                                                </span>

                                            @else

                                                <span class="badge badge-default">

                                                    {{ $item->status ?? 'Menunggu' }}

                                                </span>

                                            @endif

                                        </td>


                                        <!-- AKSI -->

                                        <td>

                                            <div class="actions">


                                                @if($item->status === 'Menunggu')

                                                    <a
                                                        href="{{ route('review.edit', $item->id) }}"
                                                        class="btn-reply"
                                                    >

                                                        <i class="fa-solid fa-reply"></i>

                                                        Balas

                                                    </a>

                                                @else

                                                    <a
                                                        href="{{ route('review.edit', $item->id) }}"
                                                        class="btn-reply"
                                                    >

                                                        <i class="fa-solid fa-pen-to-square"></i>

                                                        Ubah Balasan

                                                    </a>

                                                @endif


                                                <a
                                                    href="{{ route('review.show', $item->id) }}"
                                                    class="btn-detail"
                                                >

                                                    <i class="fa-solid fa-eye"></i>

                                                    Detail

                                                </a>


                                            </div>

                                        </td>

                                    </tr>

                                @endforeach


                            </tbody>


                        </table>

                    </div>


                @else


                    <!-- EMPTY -->

                    <div class="empty">

                        <div class="empty-icon">

                            <i class="fa-solid fa-star"></i>

                        </div>


                        <h3>
                            Belum Ada Review
                        </h3>


                        <p>

                            Belum ada review yang diberikan pengunjung
                            untuk

                            <strong>
                                {{ session('admin_pool_nama', 'kolam ini') }}
                            </strong>.

                        </p>

                    </div>

                @endif


            </div>


        </section>


    </main>


</body>

</html>