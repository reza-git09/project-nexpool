<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Review - NEXPOOL</title>

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
            background: rgba(255, 255, 255, 0.08);
        }

        /* MAIN */

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        .header {
            height: 75px;
            background: white;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
        }

        .content {
            padding: 30px;
        }

        /* PAGE HEADER */

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
        }

        /* INFO BOX */

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #1e3a8a;
        }

        /* TABLE CARD */

        .table-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #eef0f4;
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* PENGUNJUNG */

        .nama {
            font-weight: bold;
            color: #334155;
        }

        /* POOL */

        .pool {
            font-weight: bold;
            color: #334155;
        }

        /* RATING */

        .rating {
            color: #f59e0b;
            font-size: 15px;
            white-space: nowrap;
        }

        .rating-number {
            color: #475569;
            font-size: 12px;
            margin-left: 4px;
        }

        /* KOMENTAR */

        .komentar {
            max-width: 280px;
            line-height: 1.5;
            color: #475569;
        }

        /* STATUS */

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
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

        /* BALASAN */

        .balasan {
            max-width: 280px;
            line-height: 1.5;
            color: #475569;
        }

        .belum-dibalas {
            color: #94a3b8;
            font-style: italic;
        }

        /* ACTION */

        .actions {
            display: flex;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-reply,
        .btn-detail {
            border: none;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-reply {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-reply:hover {
            background: #bfdbfe;
        }

        .btn-detail {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-detail:hover {
            background: #e2e8f0;
        }

        /* ALERT */

        .alert {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* EMPTY */

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .empty h3 {
            margin-bottom: 8px;
            color: #64748b;
        }

        .empty p {
            font-size: 13px;
        }

        /* MOBILE */

        @media (max-width: 768px) {

            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
            }

            .content {
                padding: 20px;
            }

            .header {
                padding: 0 20px;
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


            <a href="{{ route('review.index') }}" class="active">
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


        <header class="header">

            <h3>Review</h3>

        </header>



        <section class="content">


            <!-- SUCCESS ALERT -->

            @if(session('success'))

                <div class="alert">

                    ✓ {{ session('success') }}

                </div>

            @endif



            <!-- PAGE HEADER -->

            <div class="page-header">

                <h1>Review Pengunjung</h1>

                <p>
                    Review dan penilaian yang diberikan pengunjung melalui aplikasi mobile NEXPOOL.
                </p>

            </div>



            <!-- INFO -->

            <div class="info-box">

                ⭐ <strong>Informasi Review</strong>

                <br>

                Review berasal dari
                <strong>aplikasi mobile NEXPOOL</strong>
                setelah pengunjung melakukan kunjungan.

                Admin dapat melihat rating dan komentar,
                kemudian memberikan balasan terhadap review.

                <br><br>

                <strong>Kolam:</strong>
                {{ session('admin_pool_nama', 'Kolam Renang') }}

            </div>



            <!-- TABLE -->

            <div class="table-card">


                @if($review->count() > 0)


                    <table>


                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Pengunjung</th>

                                <th>Kolam Renang</th>

                                <th>Rating</th>

                                <th>Komentar</th>

                                <th>Balasan Admin</th>

                                <th>Status</th>

                                <th>Aksi</th>

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
                                                Menunggu
                                            </span>

                                        @elseif($item->status === 'Dibalas')

                                            <span class="badge badge-dibalas">
                                                Dibalas
                                            </span>

                                        @else

                                            <span class="badge badge-menunggu">
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
                                                    💬 Balas
                                                </a>

                                            @else

                                                <a
                                                    href="{{ route('review.edit', $item->id) }}"
                                                    class="btn-reply"
                                                >
                                                    ✏️ Ubah Balasan
                                                </a>

                                            @endif


                                            <a
                                                href="{{ route('review.show', $item->id) }}"
                                                class="btn-detail"
                                            >
                                                👁️ Detail
                                            </a>


                                        </div>

                                    </td>


                                </tr>


                            @endforeach


                        </tbody>


                    </table>


                @else


                    <!-- EMPTY DATA -->

                    <div class="empty">

                        <div class="empty-icon">
                            ⭐
                        </div>

                        <h3>Belum Ada Review</h3>

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