<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Reservasi - NEXPOOL</title>

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

        /* INFO */
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

        /* TABLE */
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
            min-width: 1050px;
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

        .kode {
            font-weight: bold;
            color: #2563eb;
        }

        .harga {
            font-weight: bold;
            white-space: nowrap;
        }

        .pool {
            font-weight: bold;
            color: #334155;
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

        .badge-dikonfirmasi {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-selesai {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-dibatalkan {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-default {
            background: #f1f5f9;
            color: #475569;
        }

        /* ACTION */
        .actions {
            display: flex;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-edit,
        .btn-detail {
            border: none;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
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
                <i class="fa-solid fa-gauge-high" style="color:#3b82f6;width:18px;text-align:center;font-size:15px;"></i> <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket" style="color:#f59e0b;width:18px;text-align:center;font-size:15px;"></i> <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming" style="color:#06b6d4;width:18px;text-align:center;font-size:15px;"></i> <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}" class="active">
                <i class="fa-solid fa-calendar-check" style="color:#10b981;width:18px;text-align:center;font-size:15px;"></i> <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}">
                <i class="fa-solid fa-tags" style="color:#8b5cf6;width:18px;text-align:center;font-size:15px;"></i> <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}">
                <i class="fa-solid fa-star" style="color:#eab308;width:18px;text-align:center;font-size:15px;"></i> <span>Review</span>
            </a>

        </div>

        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket" style="color:#ef4444;width:18px;text-align:center;font-size:15px;"></i> <span>Logout</span>
            </a>
        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <header class="header">
            <h3>Reservasi</h3>
        </header>


        <section class="content">

            <!-- SUCCESS ALERT -->
            @if(session('success'))

                <div class="alert">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>

            @endif


            <!-- PAGE HEADER -->
            <div class="page-header">

                <h1>Data Reservasi</h1>

                <p>
                    Data reservasi pengunjung yang masuk melalui aplikasi mobile NEXPOOL.
                </p>

            </div>


            <!-- INFO -->
            <div class="info-box">

                📱 <strong>Informasi Reservasi</strong>
                <br>

                Data reservasi pada halaman ini berasal dari
                <strong>aplikasi mobile NEXPOOL</strong>.
                Admin hanya bertugas memantau, memeriksa,
                dan memperbarui status reservasi yang masuk.

                <br><br>

                <strong>Kolam:</strong>
                {{ session('admin_pool_nama', 'Kolam Renang') }}

            </div>


            <!-- TABLE -->
            <div class="table-card">

                @if($reservasi->count() > 0)

                    <table>

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Kode Reservasi</th>

                                <th>Kolam Renang</th>

                                <th>Pengunjung</th>

                                <th>No. HP</th>

                                <th>Tanggal Kunjungan</th>

                                <th>Jumlah Tiket</th>

                                <th>Total Harga</th>

                                <th>Status</th>

                                <th>Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($reservasi as $item)

                                <tr>

                                    <!-- NO -->
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <!-- KODE -->
                                    <td class="kode">
                                        {{ $item->kode_reservasi }}
                                    </td>


                                    <!-- KOLAM -->
                                    <td class="pool">
                                        {{ session('admin_pool_nama', '-') }}
                                    </td>


                                    <!-- PENGUNJUNG -->
                                    <td>
                                        {{ $item->nama_pengunjung }}
                                    </td>


                                    <!-- NO HP -->
                                    <td>
                                        {{ $item->no_hp }}
                                    </td>


                                    <!-- TANGGAL -->
                                    <td>

                                        @if($item->tanggal_kunjungan)

                                            {{ $item->tanggal_kunjungan->format('d-m-Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <!-- JUMLAH TIKET -->
                                    <td>

                                        Dewasa:
                                        {{ $item->jumlah_dewasa }}

                                        <br>

                                        Anak:
                                        {{ $item->jumlah_anak }}

                                    </td>


                                    <!-- TOTAL HARGA -->
                                    <td class="harga">

                                        Rp
                                        {{ number_format(
                                            $item->total_harga,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>


                                    <!-- STATUS -->
                                    <td>

                                        @if($item->status_reservasi === 'Menunggu')

                                            <span class="badge badge-menunggu">
                                                Menunggu
                                            </span>

                                        @elseif($item->status_reservasi === 'Dikonfirmasi')

                                            <span class="badge badge-dikonfirmasi">
                                                Dikonfirmasi
                                            </span>

                                        @elseif($item->status_reservasi === 'Selesai')

                                            <span class="badge badge-selesai">
                                                Selesai
                                            </span>

                                        @elseif($item->status_reservasi === 'Dibatalkan')

                                            <span class="badge badge-dibatalkan">
                                                Dibatalkan
                                            </span>

                                        @else

                                            <span class="badge badge-default">
                                                {{ $item->status_reservasi ?? 'Tidak diketahui' }}
                                            </span>

                                        @endif

                                    </td>


                                    <!-- AKSI -->
                                    <td>

                                        <div class="actions">

                                            <a
                                                href="{{ route('reservasi.edit', $item->id) }}"
                                                class="btn-edit"
                                            >
                                                ✏️ Kelola
                                            </a>

                                            <a
                                                href="{{ route('reservasi.show', $item->id) }}"
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
                            📋
                        </div>

                        <h3>Belum Ada Reservasi</h3>

                        <p>
                            Belum ada reservasi yang masuk dari aplikasi mobile
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