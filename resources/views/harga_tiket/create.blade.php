<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Tambah Harga Tiket - NEXPOOL</title>

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

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;
            background: linear-gradient(180deg, #14213d 0%, #0f1b33 100%);
            color: white;
            padding: 30px 15px;
            box-shadow: 4px 0 18px rgba(15, 27, 51, 0.08);
            z-index: 1000;
        }

        .logo {
            text-align: center;
            margin-bottom: 38px;
        }

        .logo h2 {
            font-size: 25px;
            letter-spacing: 1px;
        }

        .logo p {
            font-size: 11px;
            color: #9eabc1;
            margin-top: 5px;
            letter-spacing: 1px;
        }

        .menu-title {
            font-size: 10px;
            color: #71809a;
            margin: 20px 12px 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #d7deeb;
            padding: 12px 14px;
            margin-bottom: 6px;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.07);
            color: white;
            transform: translateX(2px);
        }

        .menu a.active {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 7px 16px rgba(37, 99, 235, 0.22);
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* ================= LOGOUT ================= */

        .logout {
            position: absolute;
            bottom: 25px;
            left: 15px;
            right: 15px;
        }

        .logout a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffb4b4;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.2s;
        }

        .logout a:hover {
            background: rgba(255, 255, 255, 0.07);
        }

        .logout a i {
            width: 20px;
            text-align: center;
        }

        /* ================= MAIN ================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        /* ================= HEADER ================= */

        .header {
            height: 75px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px;
            border-bottom: 1px solid #e8ebf0;
        }

        .header-left h3 {
            font-size: 19px;
            color: #111827;
        }

        .header-left p {
            margin-top: 4px;
            font-size: 12px;
            color: #8a94a6;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .user-meta {
            text-align: right;
        }

        .user-meta strong {
            display: block;
            color: #111827;
            font-size: 13px;
        }

        .user-meta span {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            color: #7b8494;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 15px;
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.2);
        }

        /* ================= CONTENT ================= */

        .content {
            padding: 32px;
            max-width: 1050px;
        }

        /* ================= BACK BUTTON ================= */

        .btn-kembali {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            padding: 9px 14px;
            background: white;
            color: #475569;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-kembali:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #f8fbff;
        }

        /* ================= PAGE HEADER ================= */

        .page-header {
            margin-bottom: 22px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .title-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .page-header h1 {
            font-size: 24px;
            color: #111827;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 13px;
        }

        /* ================= ALERT ================= */

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        /* ================= FORM CARD ================= */

        .form-card {
            background: white;
            border: 1px solid #e8ebf0;
            border-radius: 14px;
            overflow: hidden;
            max-width: 760px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.04);
        }

        .form-header {
            padding: 19px 24px;
            border-bottom: 1px solid #edf0f4;
            background: #fcfdff;
        }

        .form-header h3 {
            font-size: 15px;
            color: #111827;
        }

        .form-header p {
            margin-top: 4px;
            font-size: 12px;
            color: #8a94a6;
        }

        .form-body {
            padding: 25px;
        }

        /* ================= FORM ================= */

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #dce2ea;
            border-radius: 9px;
            font-size: 13px;
            color: #1f2937;
            outline: none;
            background: white;
            transition: 0.2s;
        }

        .form-group input::placeholder {
            color: #a8b0bd;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        .harga-info {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #7b8494;
            font-size: 11px;
            margin-top: 7px;
        }

        .harga-info i {
            color: #2563eb;
        }

        /* ================= BUTTON ================= */

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf0f4;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            padding: 11px 19px;
            border-radius: 9px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            transition: 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.18);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 16px rgba(37, 99, 235, 0.25);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

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

        @media (max-width: 700px) {

            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo h2,
            .logo p,
            .menu-title,
            .menu span,
            .logout span {
                display: none;
            }

            .menu a,
            .logout a {
                justify-content: center;
                padding: 12px;
            }

            .main {
                margin-left: 70px;
            }

            .header {
                padding: 0 20px;
            }

            .header-left p {
                display: none;
            }

            .user-meta {
                display: none;
            }

            .content {
                padding: 20px;
            }

            .form-body {
                padding: 20px;
            }
        }

        @media (max-width: 500px) {

            .header-left h3 {
                font-size: 16px;
            }

            .page-header h1 {
                font-size: 21px;
            }

            .title-icon {
                width: 42px;
                height: 42px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- ================= SIDEBAR ================= -->

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

            <a href="{{ url('/dashboard') }}">

                <i class="fa-solid fa-gauge-high"
                    style="color:#3b82f6;">
                </i>

                <span>Dashboard</span>

            </a>


            <!-- MANAJEMEN TIKET -->

            <a href="{{ route('harga-tiket.index') }}"
                class="active">

                <i class="fa-solid fa-ticket"
                    style="color:#f59e0b;">
                </i>

                <span>Manajemen Tiket</span>

            </a>


            <!-- FASILITAS -->

            <a href="{{ route('fasilitas.index') }}">

                <i class="fa-solid fa-person-swimming"
                    style="color:#06b6d4;">
                </i>

                <span>Fasilitas</span>

            </a>


            <!-- RESERVASI -->

            <a href="{{ url('/reservasi') }}">

                <i class="fa-solid fa-calendar-check"
                    style="color:#10b981;">
                </i>

                <span>Reservasi</span>

            </a>


            <!-- PROMO -->

            <a href="{{ url('/promo') }}">

                <i class="fa-solid fa-tags"
                    style="color:#8b5cf6;">
                </i>

                <span>Promo</span>

            </a>


            <!-- REVIEW -->

            <a href="{{ url('/review') }}">

                <i class="fa-solid fa-star"
                    style="color:#eab308;">
                </i>

                <span>Review</span>

            </a>

        </div>


        <!-- LOGOUT -->

        <div class="logout">

            <a href="{{ route('logout') }}">

                <i class="fa-solid fa-right-from-bracket"
                    style="color:#ef4444;">
                </i>

                <span>Logout</span>

            </a>

        </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <main class="main">

        <!-- HEADER -->

        <header class="header">

            <div class="header-left">

                <h3>
                    Manajemen Harga Tiket
                </h3>

                <p>
                    Pengelolaan harga tiket kolam renang
                </p>

            </div>


            <div class="user-profile">

                <div class="user-meta">

                    <strong>
                        {{ session('admin_pool_nama', 'Admin NEXPOOL') }}
                    </strong>

                    <span>
                        {{ session('admin_pool_id', 'pool_id_01') }}
                    </span>

                </div>


                <div class="avatar">

                    {{ strtoupper(substr(session('admin_pool_nama', 'Admin NEXPOOL'), 0, 1)) }}

                </div>

            </div>

        </header>


        <!-- ================= CONTENT ================= -->

        <section class="content">

            <!-- KEMBALI -->

            <a href="{{ route('harga-tiket.index') }}"
                class="btn-kembali">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali ke Manajemen Tiket

            </a>


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="title-wrapper">

                    <div class="title-icon">

                        <i class="fa-solid fa-circle-plus"></i>

                    </div>

                    <div>

                        <h1>
                            Tambah Harga Tiket
                        </h1>

                        <p>
                            Tambahkan harga tiket baru untuk kolam renang.
                        </p>

                    </div>

                </div>

            </div>


            <!-- ERROR VALIDASI -->

            @if ($errors->any())

                <div class="alert">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        {{ $errors->first() }}
                    </span>

                </div>

            @endif


            <!-- FORM CARD -->

            <div class="form-card">

                <div class="form-header">

                    <h3>
                        Informasi Harga Tiket
                    </h3>

                    <p>
                        Lengkapi data berikut untuk menambahkan harga tiket.
                    </p>

                </div>


                <div class="form-body">

                    <form id="hargaForm"
                        action="{{ route('harga-tiket.store') }}"
                        method="POST">

                        @csrf


                        <!-- KATEGORI -->

                        <div class="form-group">

                            <label>
                                Kategori Tiket
                            </label>

                            <div class="input-wrapper">

                                <i class="fa-solid fa-users input-icon"></i>

                                <select name="kategori" required>

                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>

                                    <option value="Dewasa"
                                        {{ old('kategori') == 'Dewasa' ? 'selected' : '' }}>

                                        Dewasa

                                    </option>

                                    <option value="Anak"
                                        {{ old('kategori') == 'Anak' ? 'selected' : '' }}>

                                        Anak

                                    </option>

                                </select>

                            </div>


                            @error('kategori')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- JENIS HARI -->

                        <div class="form-group">

                            <label>
                                Jenis Hari
                            </label>

                            <div class="input-wrapper">

                                <i class="fa-solid fa-calendar-days input-icon"></i>

                                <select name="jenis_hari" required>

                                    <option value="">
                                        -- Pilih Jenis Hari --
                                    </option>

                                    <option value="Weekday"
                                        {{ old('jenis_hari') == 'Weekday' ? 'selected' : '' }}>

                                        Weekday

                                    </option>

                                    <option value="Weekend"
                                        {{ old('jenis_hari') == 'Weekend' ? 'selected' : '' }}>

                                        Weekend

                                    </option>

                                </select>

                            </div>


                            @error('jenis_hari')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- HARGA -->

                        <div class="form-group">

                            <label>
                                Harga Tiket (Rp)
                            </label>

                            <div class="input-wrapper">

                                <i class="fa-solid fa-money-bill-wave input-icon"></i>

                                <input
                                    type="text"
                                    id="harga"
                                    name="harga"
                                    value="{{ old('harga') }}"
                                    placeholder="Contoh: 25000"
                                    maxlength="5"
                                    inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);"
                                    required
                                >

                            </div>


                            <div class="harga-info">

                                <i class="fa-solid fa-circle-info"></i>

                                <span>
                                    Masukkan harga dalam angka. Maksimal Rp99.999, contoh Rp25.000.
                                </span>

                            </div>


                            @error('harga')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- BUTTON -->

                        <div class="buttons">

                            <button type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Simpan Harga

                            </button>


                            <a href="{{ route('harga-tiket.index') }}"
                                class="btn btn-secondary">

                                <i class="fa-solid fa-xmark"></i>

                                Batal

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </section>

    </main>

</body>

</html>