<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Promo - NEXPOOL</title>

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
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .nama-promo {
            font-weight: bold;
            color: #2563eb;
        }

        .deskripsi {
            max-width: 220px;
            color: #4b5563;
        }

        .diskon {
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

        .badge-aktif {
            background: #ecfdf5;
            color: #047857;
        }

        .badge-nonaktif {
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
                <i class="fa-solid fa-gauge-high" style="color:#3b82f6;width:18px;text-align:center;font-size:15px;"></i> <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket" style="color:#f59e0b;width:18px;text-align:center;font-size:15px;"></i> <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming" style="color:#06b6d4;width:18px;text-align:center;font-size:15px;"></i> <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}">
                <i class="fa-solid fa-calendar-check" style="color:#10b981;width:18px;text-align:center;font-size:15px;"></i> <span>Reservasi</span>
            </a>

            <!-- Hanya Promo yang memiliki class="active" -->
            <a href="{{ route('promo.index') }}" class="active">
                <i class="fa-solid fa-tags" style="color:#8b5cf6;width:18px;text-align:center;font-size:15px;"></i> <span>Promo</span>
            </a>

            <!-- Class active pada Review dihapus -->
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
            <h3>Promo</h3>
        </header>

        <section class="content">

            @if(session('success'))
                <div class="alert">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="page-header">

                <div>
                    <h1>Manajemen Promo</h1>
                    <p>Kelola data promo dan diskon pengunjung NEXPOOL.</p>
                </div>

                <a href="{{ route('promo.create') }}" class="btn-tambah"> <i class="fa-solid fa-plus fa-xs"></i> Tambah Promo
                </a>

            </div>


            <div class="table-card">

                @if($promo->count() > 0)

                    <table>

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Promo</th>
                                <th>Deskripsi</th>
                                <th>Diskon</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($promo as $item)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td class="nama-promo">
                                        {{ $item->nama_promo }}
                                    </td>

                                    <td class="deskripsi">
                                        {{ $item->deskripsi ?? '-' }}
                                    </td>

                                    <td class="diskon">
                                        @if($item->jenis_diskon == 'Persentase')
                                            {{ number_format($item->nilai_diskon, 0, ',', '.') }}%
                                        @else
                                            Rp {{ number_format($item->nilai_diskon, 0, ',', '.') }}
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->tanggal_mulai->format('d-m-Y') }}<br>
                                        s/d <br>
                                        {{ $item->tanggal_selesai->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        @if($item->status)
                                            <span class="badge badge-aktif">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge badge-nonaktif">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <td>

                                        <div class="actions">

                                            <a
                                                href="{{ route('promo.edit', $item->id) }}"
                                                class="btn-edit"
                                            >
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>

                                            <form
                                                action="{{ route('promo.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus promo {{ $item->nama_promo }}?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-hapus"
                                                >
                                                    <i class="fa-solid fa-trash"></i> Hapus
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
                        <h3>Belum Ada Promo</h3>
                        <p>Data promo pengunjung belum tersedia.</p>
                    </div>

                @endif

            </div>

        </section>

    </main>

</body>

</html>