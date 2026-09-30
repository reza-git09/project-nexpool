```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Pantau Reservasi - NEXPOOL</title>

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

        /* BACK BUTTON */
        .btn-back {
            display: inline-flex;
            align-items: center;
            background: #e5e7eb;
            color: #374151;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        /* PAGE HEADER */
        .page-header {
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

        /* FORM CARD */
        .form-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 12px;
            padding: 25px;
            max-width: 850px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d9dee7;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        .form-group input[readonly] {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-group select:focus {
            border-color: #2563eb;
        }

        /* INFO */
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .info-box i {
            margin-right: 7px;
        }

        /* ERROR */
        .error-box {
            background: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .error-box strong {
            display: block;
            margin-bottom: 5px;
        }

        /* SUCCESS */
        .success-box {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        /* BUTTON */
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .status-note {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
        }

        /* RESPONSIVE */
        @media (max-width: 800px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
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

            <!-- DASHBOARD -->
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge-high"
                    style="color:#3b82f6;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Dashboard</span>
            </a>

            <!-- MANAJEMEN TIKET -->
            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket"
                    style="color:#f59e0b;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Manajemen Tiket</span>
            </a>

            <!-- FASILITAS -->
            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming"
                    style="color:#06b6d4;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Fasilitas</span>
            </a>

            <!-- RESERVASI -->
            <a href="{{ route('reservasi.index') }}" class="active">
                <i class="fa-solid fa-calendar-check"
                    style="color:#10b981;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Reservasi</span>
            </a>

            <!-- PROMO -->
            <a href="{{ route('promo.index') }}">
                <i class="fa-solid fa-tags"
                    style="color:#8b5cf6;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Promo</span>
            </a>

            <!-- REVIEW -->
            <a href="{{ route('review.index') }}">
                <i class="fa-solid fa-star"
                    style="color:#eab308;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Review</span>
            </a>

        </div>

        <!-- LOGOUT -->
        <div class="logout">
            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket"
                    style="color:#ef4444;width:18px;text-align:center;font-size:15px;">
                </i>
                <span>Logout</span>
            </a>
        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">
            <h3>Pantau Reservasi</h3>
        </header>


        <!-- CONTENT -->
        <section class="content">

            <!-- BACK -->
            <a href="{{ route('reservasi.index') }}" class="btn-back">
                &larr; Kembali ke Reservasi
            </a>


            <!-- PAGE HEADER -->
            <div class="page-header">

                <h1>Pantau Reservasi</h1>

                <p>
                    Pantau data reservasi pengunjung dan ubah status reservasi.
                </p>

            </div>


            <!-- FORM CARD -->
            <div class="form-card">

                <!-- INFO -->
                <div class="info-box">

                    <i class="fa-solid fa-circle-info"></i>

                    Data reservasi hanya dapat dipantau.
                    Admin hanya dapat mengubah
                    <strong>status reservasi</strong>.

                </div>


                <!-- ERROR -->
                @if ($errors->any())

                    <div class="error-box">

                        <strong>Terjadi kesalahan:</strong>

                        @foreach ($errors->all() as $error)

                            <div>{{ $error }}</div>

                        @endforeach

                    </div>

                @endif


                <!-- SUCCESS -->
                @if (session('success'))

                    <div class="success-box">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>

                @endif


                <!-- FORM -->
                <form
                    action="{{ route('reservasi.update', $reservasi->id) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    <div class="form-grid">

                        <!-- KODE RESERVASI -->
                        <div class="form-group">

                            <label>Kode Reservasi</label>

                            <input
                                type="text"
                                value="{{ $reservasi->kode_reservasi }}"
                                readonly
                            >

                        </div>


                        <!-- NAMA -->
                        <div class="form-group">

                            <label>Nama Pengunjung</label>

                            <input
                                type="text"
                                value="{{ $reservasi->nama_pengunjung }}"
                                readonly
                            >

                        </div>


                        <!-- NO HP -->
                        <div class="form-group">

                            <label>No. HP</label>

                            <input
                                type="text"
                                value="{{ $reservasi->no_hp }}"
                                readonly
                            >

                        </div>


                        <!-- TANGGAL -->
                        <div class="form-group">

                            <label>Tanggal Kunjungan</label>

                            <input
                                type="text"
                                value="{{ optional($reservasi->tanggal_kunjungan)->format('d/m/Y') }}"
                                readonly
                            >

                        </div>


                        <!-- TOTAL -->
                        <div class="form-group">

                            <label>Total Harga</label>

                            <input
                                type="text"
                                value="Rp{{ number_format($reservasi->total_harga, 0, ',', '.') }}"
                                readonly
                            >

                        </div>


                        <!-- DEWASA -->
                        <div class="form-group">

                            <label>Jumlah Dewasa</label>

                            <input
                                type="text"
                                value="{{ $reservasi->jumlah_dewasa }}"
                                readonly
                            >

                        </div>


                        <!-- ANAK -->
                        <div class="form-group">

                            <label>Jumlah Anak</label>

                            <input
                                type="text"
                                value="{{ $reservasi->jumlah_anak }}"
                                readonly
                            >

                        </div>


                        <!-- STATUS -->
                        <div class="form-group full">

                            <label>Status Reservasi</label>

                            <select
                                name="status_reservasi"
                                required
                            >

                                <option
                                    value="Menunggu"
                                    {{ old('status_reservasi', $reservasi->status_reservasi) == 'Menunggu' ? 'selected' : '' }}
                                >
                                    Menunggu
                                </option>

                                <option
                                    value="Dikonfirmasi"
                                    {{ old('status_reservasi', $reservasi->status_reservasi) == 'Dikonfirmasi' ? 'selected' : '' }}
                                >
                                    Dikonfirmasi
                                </option>

                                

                                
                            </select>

                            <div class="status-note">
                                Admin hanya dapat mengubah status reservasi.
                            </div>

                        </div>

                    </div>


                    <!-- BUTTON -->
                    <div class="buttons">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i
                                class="fa-solid fa-floppy-disk"
                                style="margin-right:8px;">
                            </i>

                            Update Status Reservasi

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</body>

</html>
```
