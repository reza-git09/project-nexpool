<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        .alert {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

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

        .nama-fasilitas {
            font-weight: bold;
            color: #2563eb;
        }

        .nama-kolam {
            font-weight: bold;
            color: #334155;
        }

        .deskripsi {
            max-width: 350px;
            color: #4b5563;
            line-height: 1.5;
        }

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

        /* Memperbaiki tata letak tombol aksi agar sejajar */
        .actions {
            display: flex;
            align-items: center;
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
            display: inline-flex;
            align-items: center;
            gap: 4px;
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
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="logo">
            <h2>NEXPOOL</h2>
            <p>ADMINISTRATOR</p>
        </div>

        <div class="menu-title">Menu Utama</div>

        <div class="menu">
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge-high" style="color:#3b82f6;width:18px;text-align:center;font-size:15px;"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket" style="color:#f59e0b;width:18px;text-align:center;font-size:15px;"></i> <span>Manajemen Tiket</span>
            </a>
            <a href="{{ route('fasilitas.index') }}" class="active">
                <i class="fa-solid fa-person-swimming" style="color:#06b6d4;width:18px;text-align:center;font-size:15px;"></i> <span>Fasilitas</span>
            </a>
            <a href="{{ route('reservasi.index') }}">
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

    <main class="main">
        <header class="header">
            <h3>Fasilitas</h3>
        </header>

        <section class="content">
            @if(session('success'))
                <div class="alert">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="page-header">
                <div>
                    <h1>Manajemen Fasilitas</h1>
                    <p>Kelola fasilitas dan informasi pendukung kolam renang.</p>
                </div>
                <a href="{{ route('fasilitas.create') }}" class="btn-tambah"> <i class="fa-solid fa-plus fa-xs"></i> Tambah Fasilitas
                </a>
            </div>

            <div class="info-box">
                🏊 <strong>Kolam Renang:</strong>
                {{ session('admin_pool_nama') ?? 'Semua Kolam' }}
                <br>
                Admin dapat menambahkan nama fasilitas, deskripsi, dan status fasilitas.
            </div>

            <div class="table-card">
                @if($fasilitas->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kolam</th>
                                <th>Nama Fasilitas</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fasilitas as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="nama-kolam">
                                        {{ isset($item->pool) ? $item->pool->nama_kolam : (session('admin_pool_nama') ?? '-') }}
                                    </td>
                                    <td class="nama-fasilitas">{{ $item->nama_fasilitas }}</td>
                                    <td class="deskripsi">{{ $item->deskripsi ?? '-' }}</td>
                                    <td>
                                        @if($item->status == 'Aktif' || $item->status == 1)
                                            <span class="badge badge-aktif">Aktif</span>
                                        @else
                                            <span class="badge badge-nonaktif">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('fasilitas.edit', $item->id) }}" class="btn-edit">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                            <form action="{{ route('fasilitas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas {{ $item->nama_fasilitas }}?')" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-hapus">
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
                        <h3>Belum Ada Fasilitas</h3>
                        <p>Belum ada fasilitas yang ditambahkan untuk {{ session('admin_pool_nama') ?? 'kolam ini' }}.</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

</body>
</html>