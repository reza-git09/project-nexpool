<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            min-width: 1000px;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #eef0f4;
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
        }

        /* STATUS */
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
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

        /* ACTION */
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

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #bfdbfe;
        }

        .btn-hapus {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #fecaca;
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

        .empty {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
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

        <div class="menu-title">Menu Utama</div>

        <div class="menu">

            <a href="{{ url('/dashboard') }}">
                ▣ <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                🎟️ <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                🏊 <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}" class="active">
                📋 <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}">
                🏷️ <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}">
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
            <h3>Reservasi</h3>
        </header>

        <section class="content">

            @if(session('success'))
                <div class="alert">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div class="page-header">

                <div>
                    <h1>Data Reservasi</h1>
                    <p>Kelola data reservasi pengunjung NEXPOOL.</p>
                </div>

                <a href="{{ route('reservasi.create') }}" class="btn-tambah">
                    + Tambah Reservasi
                </a>

            </div>


            <div class="table-card">

                @if($reservasi->count() > 0)

                    <table>

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Reservasi</th>
                                <th>Pool ID</th>
                                <th>Pengunjung</th>
                                <th>No. HP</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($reservasi as $item)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td class="kode">
                                        {{ $item->kode_reservasi }}
                                    </td>

                                    <td>
                                        {{ $item->pool_id }}
                                    </td>

                                    <td>
                                        {{ $item->nama_pengunjung }}
                                    </td>

                                    <td>
                                        {{ $item->no_hp }}
                                    </td>

                                    <td>
                                        {{ $item->tanggal_kunjungan->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        Dewasa: {{ $item->jumlah_dewasa }}<br>
                                        Anak: {{ $item->jumlah_anak }}
                                    </td>

                                    <td class="harga">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if($item->status_reservasi == 'Menunggu')

                                            <span class="badge badge-menunggu">
                                                Menunggu
                                            </span>

                                        @elseif($item->status_reservasi == 'Dikonfirmasi')

                                            <span class="badge badge-dikonfirmasi">
                                                Dikonfirmasi
                                            </span>

                                        @elseif($item->status_reservasi == 'Selesai')

                                            <span class="badge badge-selesai">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="badge badge-dibatalkan">
                                                Dibatalkan
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a
                                                href="{{ route('reservasi.edit', $item->id) }}"
                                                class="btn-edit"
                                            >
                                                ✏️ Edit
                                            </a>

                                            <form
                                                action="{{ route('reservasi.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus reservasi {{ $item->kode_reservasi }}?')"
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

                    <div class="empty">
                        <h3>Belum Ada Reservasi</h3>
                        <p>Data reservasi pengunjung belum tersedia.</p>
                    </div>

                @endif

            </div>

        </section>

    </main>

</body>

</html>