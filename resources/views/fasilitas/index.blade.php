<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fasilitas - NEXPOOL</title>

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


        /* JUDUL MENU */

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


        /* ==========================================
           BUTTON TAMBAH
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
           INFO BOX
        ========================================== */

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
           TABLE
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
            min-width: 1000px;
        }


        /* HEADER TABLE */

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }


        /* DATA TABLE */

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
           GAMBAR
        ========================================== */

        .gambar-fasilitas {
            width: 90px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            display: block;
        }

        .no-gambar {
            width: 90px;
            height: 65px;
            background: #f1f5f9;
            color: #94a3b8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 11px;
            border: 1px solid #e5e7eb;
        }


        /* ==========================================
           NAMA FASILITAS
        ========================================== */

        .nama-fasilitas {
            font-weight: bold;
            color: #2563eb;
        }


        /* ==========================================
           NAMA KOLAM
        ========================================== */

        .nama-kolam {
            font-weight: bold;
            color: #334155;
        }


        /* ==========================================
           DESKRIPSI
        ========================================== */

        .deskripsi {
            max-width: 300px;
            color: #4b5563;
            line-height: 1.5;
        }


        /* ==========================================
           STATUS
        ========================================== */

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }


        .badge-aktif {
            background: #ecfdf5;
            color: #047857;
        }


        .badge-nonaktif {
            background: #fef2f2;
            color: #dc2626;
        }


        /* ==========================================
           ACTION
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
            padding: 50px 20px;
            color: #94a3b8;
        }

        .empty h3 {
            color: #64748b;
            margin-bottom: 8px;
        }

        .empty p {
            font-size: 13px;
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

            <a href="{{ route('dashboard') }}">

                ▣ <span>
                    Dashboard
                </span>

            </a>


            <!-- MANAJEMEN TIKET -->

            <a href="{{ route('harga-tiket.index') }}">

                🎟️ <span>
                    Manajemen Tiket
                </span>

            </a>


            <!-- FASILITAS -->

            <a
                href="{{ route('fasilitas.index') }}"
                class="active"
            >

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
                Fasilitas
            </h3>

        </header>



        <!-- CONTENT -->

        <section class="content">


            <!-- ALERT -->

            @if(session('success'))

                <div class="alert">

                    ✓ {{ session('success') }}

                </div>

            @endif



            <!-- PAGE HEADER -->

            <div class="page-header">


                <div>

                    <h1>
                        Manajemen Fasilitas
                    </h1>

                    <p>
                        Kelola fasilitas dan informasi pendukung kolam renang.
                    </p>

                </div>


                <!-- BUTTON TAMBAH -->

                <a
                    href="{{ route('fasilitas.create') }}"
                    class="btn-tambah"
                >

                    + Tambah Fasilitas

                </a>


            </div>



            <!-- ==========================================
                 INFO BOX
            ========================================== -->

            <div class="info-box">

                🏊 <strong>Kolam Renang:</strong>

                {{ session('admin_pool_nama') }}

                <br>

                Admin dapat menambahkan nama fasilitas,
                deskripsi, gambar, dan status fasilitas.
                Informasi fasilitas ini nantinya dapat ditampilkan
                pada aplikasi mobile NEXPOOL.

            </div>



            <!-- ==========================================
                 TABLE CARD
            ========================================== -->

            <div class="table-card">


                @if($fasilitas->count() > 0)


                    <table>


                        <!-- TABLE HEADER -->

                        <thead>

                            <tr>

                                <th>
                                    No
                                </th>

                                <th>
                                    Gambar
                                </th>

                                <th>
                                    Nama Kolam
                                </th>

                                <th>
                                    Nama Fasilitas
                                </th>

                                <th>
                                    Deskripsi
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>



                        <!-- TABLE BODY -->

                        <tbody>


                            @foreach($fasilitas as $item)


                                <tr>


                                    <!-- NO -->

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>



                                    <!-- GAMBAR -->

                                    <td>

                                        @if($item->gambar)

                                            <img
                                                src="{{ asset('storage/fasilitas/' . $item->gambar) }}"
                                                alt="{{ $item->nama_fasilitas }}"
                                                class="gambar-fasilitas"
                                            >

                                        @else

                                            <div class="no-gambar">
                                                Tidak ada gambar
                                            </div>

                                        @endif

                                    </td>



                                    <!-- NAMA KOLAM -->

                                    <td class="nama-kolam">

                                        {{ session('admin_pool_nama') }}

                                    </td>



                                    <!-- NAMA FASILITAS -->

                                    <td class="nama-fasilitas">

                                        {{ $item->nama_fasilitas }}

                                    </td>



                                    <!-- DESKRIPSI -->

                                    <td class="deskripsi">

                                        {{ $item->deskripsi ?? '-' }}

                                    </td>



                                    <!-- STATUS -->

                                    <td>


                                        @if($item->status == 'Aktif' || $item->status == 1)

                                            <span class="badge badge-aktif">

                                                Aktif

                                            </span>

                                        @else

                                            <span class="badge badge-nonaktif">

                                                Tidak Aktif

                                            </span>

                                        @endif


                                    </td>



                                    <!-- AKSI -->

                                    <td>


                                        <div class="actions">


                                            <!-- EDIT -->

                                            <a
                                                href="{{ route('fasilitas.edit', $item->id) }}"
                                                class="btn-edit"
                                            >

                                                ✏️ Edit

                                            </a>



                                            <!-- HAPUS -->

                                            <form
                                                action="{{ route('fasilitas.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus fasilitas {{ $item->nama_fasilitas }}?')"
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
                            Belum Ada Fasilitas
                        </h3>

                        <p>
                            Belum ada fasilitas yang ditambahkan
                            untuk {{ session('admin_pool_nama') }}.
                        </p>

                    </div>


                @endif


            </div>


        </section>


    </main>


</body>

</html>