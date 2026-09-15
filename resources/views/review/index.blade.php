<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Review Pengunjung - NEXPOOL</title>

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

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        .header {
            height: 75px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h3 {
            font-size: 20px;
            color: #111827;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-meta {
            text-align: right;
        }

        .user-meta strong {
            display: block;
            color: #111827;
            font-size: 14px;
        }

        .user-meta span {
            font-size: 12px;
            color: #6b7280;
        }

        .avatar {
            width: 38px;
            height: 38px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 15px;
        }

        .content {
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .topbar h1 {
            font-size: 25px;
            margin-bottom: 7px;
        }

        .topbar p {
            color: #7b8494;
            font-size: 14px;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e8ebf0;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .alert {
            background: #d1fae5;
            color: #065f46;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
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
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #eef0f3;
            font-size: 14px;
            vertical-align: top;
            color: #374151;
        }

        .rating {
            color: #f59e0b;
            font-weight: 600;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .status-dibalas {
            background: #dcfce7;
            color: #166534;
        }

        .action {
            display: flex;
            gap: 7px;
        }

        .btn-edit {
            background: #e0f2fe;
            color: #0369a1;
            padding: 7px 11px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-delete {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
        }

        .empty h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #374151;
        }

        .comment,
        .reply {
            max-width: 250px;
            line-height: 1.5;
        }

        .reply {
            color: #475569;
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

        <!-- HEADER -->
        <header class="header">

            <h3>
                Review Pengunjung
            </h3>

            <div class="user-profile">

                <div class="user-meta">

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


        <!-- CONTENT -->
        <section class="content">

            <div class="topbar">

                <div>

                    <h1>
                        Review Pengunjung
                    </h1>

                    <p>
                        Kelola ulasan dan tanggapan pengunjung NEXPOOL.
                    </p>

                </div>

                <a href="{{ route('review.create') }}" class="btn">
                    + Tambah Review
                </a>

            </div>


            @if(session('success'))

                <div class="alert">
                    {{ session('success') }}
                </div>

            @endif


            <div class="card">

                @if(isset($review) && $review->count() > 0)

                    <table>

                        <thead>

                            <tr>
                                <th>No</th>
                                <th>Nama Kolam</th>
                                <th>Pengunjung</th>
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

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ session('admin_pool_nama') }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $item->nama_pengunjung }}
                                        </strong>
                                    </td>

                                    <td>

                                        <div class="rating">
                                            {{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', 5 - $item->rating) }}
                                        </div>

                                    </td>

                                    <td>

                                        <div class="comment">
                                            {{ $item->komentar ?? '-' }}
                                        </div>

                                    </td>

                                    <td>

                                        <div class="reply">
                                            {{ $item->balasan_admin ?? 'Belum ada balasan' }}
                                        </div>

                                    </td>

                                    <td>

                                        @if($item->status === 'Dibalas')

                                            <span class="status status-dibalas">
                                                Dibalas
                                            </span>

                                        @else

                                            <span class="status status-menunggu">
                                                Menunggu
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div class="action">

                                            <a
                                                href="{{ route('review.edit', $item->id) }}"
                                                class="btn-edit"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('review.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus review ini?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-delete"
                                                >
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

                    <div class="empty">

                        <h3>
                            Belum ada review
                        </h3>

                        <p>
                            Data review pengunjung akan muncul di halaman ini.
                        </p>

                    </div>

                @endif

            </div>

        </section>

    </main>

</body>

</html>