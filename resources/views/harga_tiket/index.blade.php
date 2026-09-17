<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Harga Tiket - NEXPOOL</title>

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


        /* ==========================================
            BODY
        ========================================== */

        body {
            background: #f5f7fb;
            color: #1f2937;
        }


        /* ==========================================
            SIDEBAR
            SAMA DENGAN PROMO
        ========================================== */

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


        /* LOGO */

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


        /* MENU TITLE */

        .menu-title {
            font-size: 11px;
            color: #8491a7;
            margin: 20px 12px 10px;
            text-transform: uppercase;
        }


        /* MENU */

        .menu a {
            display: block;
            text-decoration: none;
            color: #dce3ef;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-size: 14px;
        }


        /* MENU AKTIF */

        .menu a:hover,
        .menu a.active {
            background: #2563eb;
            color: white;
        }


        /* ==========================================
            LOGOUT
        ========================================== */

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
            background: #7f1d1d;
            color: white;
        }


        /* ==========================================
            MAIN
        ========================================== */

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }


        /* ==========================================
            HEADER
        ========================================== */

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


        /* ==========================================
            CONTENT
        ========================================== */

        .content {
            padding: 30px;
        }


        /* ==========================================
            PAGE HEADER
        ========================================== */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 14px;
        }


        /* ==========================================
            BUTTON TAMBAH
            SAMA DENGAN PROMO
        ========================================== */

        .btn-tambah {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background: #1d4ed8;
        }


        /* ==========================================
            ALERT
        ========================================== */

        .alert {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }


        /* ==========================================
            TABLE CARD
            SAMA DENGAN PROMO
        ========================================== */

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
            min-width: 800px;
        }


        /* TABLE HEADER */

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }


        /* TABLE DATA */

        td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #eef0f4;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }


        /* ==========================================
            BADGE
        ========================================== */

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }


        /* DEWASA */

        .badge-dewasa {
            background: #dbeafe;
            color: #1d4ed8;
        }


        /* ANAK */

        .badge-anak {
            background: #fef3c7;
            color: #b45309;
        }


        /* WEEKDAY */

        .badge-weekday {
            background: #dcfce7;
            color: #15803d;
        }


        /* WEEKEND */

        .badge-weekend {
            background: #fee2e2;
            color: #dc2626;
        }


        /* ==========================================
            HARGA
        ========================================== */

        .harga {
            font-weight: bold;
            color: #1f2937;
        }


        /* ==========================================
            ACTION
            SAMA DENGAN PROMO
        ========================================== */

        .actions {
            display: flex;
            gap: 6px;
        }


        .btn-edit,
        .btn-hapus {
            border: none;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }


        /* EDIT */

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #bfdbfe;
        }


        /* HAPUS */

        .btn-hapus {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #fecaca;
        }


        /* ==========================================
            EMPTY
        ========================================== */

        .empty {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }

        .empty h3 {
            margin-bottom: 8px;
        }


        /* ==========================================
            RESPONSIVE
        ========================================== */

        @media (max-width: 900px) {

            .sidebar {
                width: 70px;
            }

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

            .table-card {
                overflow-x: auto;
            }

        }
    </style>

</head>


<body>


    <!-- ==========================================
          SIDEBAR
    ========================================== -->

    <aside class="sidebar">


        <!-- LOGO -->

        <div class="logo">

            <h2>
                NEXPOOL
            </h2>

            <p>
                ADMINISTRATOR
            </p>

        </div>


        <!-- MENU TITLE -->

        <div class="menu-title">
            Menu Utama
        </div>


        <!-- MENU -->

        <div class="menu">


            <!-- DASHBOARD -->

            <a href="/dashboard">

                ▣ <span>
                    Dashboard
                </span>

            </a>


            <!-- MANAJEMEN TIKET AKTIF -->

            <a
                href="{{ route('harga-tiket.index') }}"
                class="active"
            >

                🎟️ <span>
                    Manajemen Tiket
                </span>

            </a>


            <!-- FASILITAS -->

            <a href="{{ route('fasilitas.index') }}">

                🏊 <span>
                    Fasilitas
                </span>

            </a>


            <!-- RESERVASI -->

            <a href="{{ route('reservasi.index') }}">

                📋 <span>
                    Reservasi
                </span>

            </a>


            <!-- PROMO -->

            <a href="{{ route('promo.index') }}">

                🏷️ <span>
                    Promo
                </span>

            </a>


            <!-- REVIEW -->

            <a href="{{ route('review.index') }}">

                ⭐ <span>
                    Review
                </span>

            </a>


        </div>


        <!-- LOGOUT -->

        <div class="logout">

            <a href="{{ route('logout') }}">

                ↪ <span>
                    Logout
                </span>

            </a>

        </div>


    </aside>



    <!-- ==========================================
          MAIN
    ========================================== -->

    <main class="main">


        <!-- HEADER -->

        <header class="header">

            <h3>
                Manajemen Harga Tiket
            </h3>

        </header>



        <!-- CONTENT -->

        <section class="content">


            <!-- PAGE HEADER -->

            <div class="page-header">


                <div>

                    <h1>
                        Harga Tiket
                    </h1>

                    <p>
                        Kelola harga tiket berdasarkan kategori dan jenis hari.
                    </p>

                </div>


                <!-- BUTTON TAMBAH -->

                <a
                    href="{{ route('harga-tiket.create') }}"
                    class="btn-tambah"
                >

                    + Tambah Harga

                </a>


            </div>



            <!-- ALERT -->

            @if(session('success'))

                <div class="alert">

                    ✓ {{ session('success') }}

                </div>

            @endif



            <!-- ==========================================
                    TABLE
            ========================================== -->

            <div class="table-card">


                @if($hargaTiket->count() > 0)


                    <table>


                        <!-- HEADER -->

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
                                    Harga
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>



                        <!-- BODY -->

                        <tbody>


                            @foreach($hargaTiket as $item)


                                <tr>


                                    <!-- NO -->

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <!-- KATEGORI -->

                                    <td>

                                        @if($item->kategori == 'Dewasa')

                                            <span class="badge badge-dewasa">

                                                Dewasa

                                            </span>

                                        @else

                                            <span class="badge badge-anak">

                                                Anak

                                            </span>

                                        @endif

                                    </td>


                                    <!-- JENIS HARI -->

                                    <td>

                                        @if($item->jenis_hari == 'Weekday')

                                            <span class="badge badge-weekday">

                                                Weekday

                                            </span>

                                        @else

                                            <span class="badge badge-weekend">

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

                                            <a
                                                href="{{ route('harga-tiket.edit', $item->id) }}"
                                                class="btn-edit"
                                            >

                                                ✏️ Edit

                                            </a>



                                            <!-- HAPUS -->

                                            <form
                                                action="{{ route('harga-tiket.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus harga tiket ini?')"
                                            >

                                                @csrf

                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="btn-hapus"
                                                >

                                                    🗑️ Hapus

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

                        <h3>
                            Belum Ada Harga Tiket
                        </h3>

                        <p>
                            Silakan tambahkan harga tiket terlebih dahulu.
                        </p>

                    </div>


                @endif


            </div>


        </section>


    </main>


</body>

</html>