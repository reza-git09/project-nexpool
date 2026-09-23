<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Edit Harga Tiket - NEXPOOL</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f6f8fc;
            color: #172033;
        }

        a {
            text-decoration: none;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 255px;
            height: 100vh;

            background:
                linear-gradient(
                    180deg,
                    #101c36 0%,
                    #14213d 55%,
                    #101b32 100%
                );

            color: white;
            padding: 24px 16px;
            z-index: 1000;

            box-shadow:
                8px 0 30px rgba(15, 23, 42, 0.08);
        }

        .logo {
            padding: 6px 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 25px;
            text-align: center;
        }

        .logo h2 {
            font-size: 22px;
            letter-spacing: 1px;
            color: white;
        }

        .logo p {
            margin-top: 4px;
            font-size: 10px;
            color: #91a0b9;
            letter-spacing: 1.3px;
        }

        .menu-title {
            padding: 0 12px;
            margin-bottom: 10px;
            font-size: 10px;
            font-weight: bold;
            color: #73819b;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu a {
            position: relative;

            display: flex;
            align-items: center;
            gap: 13px;

            padding: 12px 13px;
            border-radius: 10px;

            color: #cbd5e1;
            font-size: 13px;
            font-weight: 500;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.07);
            color: white;
            transform: translateX(2px);
        }

        .menu a.active {
            background:
                linear-gradient(
                    90deg,
                    #2563eb,
                    #1d4ed8
                );

            color: white;

            box-shadow:
                0 8px 20px rgba(37,99,235,0.25);
        }

        .menu a.active::before {
            content: "";

            position: absolute;
            left: -16px;
            top: 8px;

            width: 3px;
            height: calc(100% - 16px);

            border-radius: 0 5px 5px 0;
            background: #60a5fa;
        }

        /* =========================
           LOGOUT
        ========================= */

        .logout {
            position: absolute;

            bottom: 20px;
            left: 16px;
            right: 16px;

            padding-top: 15px;

            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .logout a {
            display: flex;
            align-items: center;
            gap: 13px;

            padding: 12px 13px;
            border-radius: 10px;

            color: #fca5a5;
            font-size: 13px;

            transition: 0.2s;
        }

        .logout a:hover {
            background: rgba(239,68,68,0.12);
            color: #fecaca;
        }

        .logout i {
            width: 20px;
            text-align: center;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 255px;
            min-height: 100vh;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            height: 76px;

            background: rgba(255,255,255,0.96);

            backdrop-filter: blur(10px);

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 32px;

            border-bottom: 1px solid #e8ecf3;

            position: sticky;
            top: 0;
            z-index: 900;
        }

        .header-left h3 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .header-left p {
            margin-top: 3px;
            font-size: 11px;
            color: #8a94a6;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-text {
            text-align: right;
        }

        .admin-text strong {
            display: block;
            font-size: 13px;
            color: #172033;
        }

        .admin-text span {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            color: #8a94a6;
        }

        .avatar {
            width: 42px;
            height: 42px;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #38bdf8
                );

            color: white;

            font-size: 15px;
            font-weight: bold;

            box-shadow:
                0 6px 15px rgba(37,99,235,0.2);
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 32px;
            max-width: 1700px;
        }

        .content-inner {
            max-width: 900px;
        }

        /* =========================
           BACK BUTTON
        ========================= */

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 9px 13px;

            background: #eef2f7;
            color: #475569;

            border: 1px solid #e2e8f0;

            border-radius: 9px;

            font-size: 11px;
            font-weight: 600;

            margin-bottom: 20px;

            transition: 0.2s;
        }

        .back-button:hover {
            background: #e2e8f0;
            color: #1e293b;
            transform: translateX(-2px);
        }

        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;
            align-items: center;
            gap: 14px;

            margin-bottom: 25px;
        }

        .page-icon {
            width: 48px;
            height: 48px;

            flex-shrink: 0;

            border-radius: 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #dbeafe,
                    #eff6ff
                );

            color: #2563eb;
            font-size: 19px;
        }

        .page-header h1 {
            font-size: 25px;
            color: #111827;

            margin-bottom: 6px;
        }

        .page-header p {
            color: #7b8494;
            font-size: 13px;
        }

        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            background: white;

            border: 1px solid #e8ecf3;

            border-radius: 16px;

            padding: 25px;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.025);
        }

        .form-card-header {
            display: flex;
            align-items: center;
            gap: 9px;

            padding-bottom: 18px;

            margin-bottom: 22px;

            border-bottom: 1px solid #eef1f5;
        }

        .form-card-icon {
            width: 32px;
            height: 32px;

            border-radius: 9px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eef4ff;
            color: #2563eb;

            font-size: 12px;
        }

        .form-card-header h3 {
            font-size: 14px;
            color: #172033;
        }

        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            font-size: 12px;
            font-weight: 600;

            margin-bottom: 8px;

            color: #374151;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;

            min-height: 44px;

            padding: 11px 13px;

            border: 1px solid #d8dee8;

            border-radius: 9px;

            font-size: 12px;

            background: white;
            color: #334155;

            outline: none;

            transition:
                border 0.2s ease,
                box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37,99,235,0.10);
        }

        .form-control::placeholder {
            color: #a0a9b8;
        }

        .form-control option {
            color: #334155;
        }

        /* =========================
           ERROR
        ========================= */

        .error {
            display: flex;
            align-items: center;
            gap: 5px;

            margin-top: 6px;

            color: #dc2626;
            font-size: 10px;
        }

        /* =========================
           HINT
        ========================= */

        .form-hint {
            display: flex;
            align-items: flex-start;

            gap: 5px;

            font-size: 10px;

            color: #7b8494;

            margin-top: 6px;

            line-height: 1.5;
        }

        .form-hint i {
            color: #94a3b8;
            margin-top: 1px;
        }

        /* =========================
           DIVIDER
        ========================= */

        .divider {
            height: 1px;

            background: #eef1f5;

            margin: 5px 0 22px;
        }

        /* =========================
           BUTTON
        ========================= */

        .button-group {
            display: flex;
            align-items: center;

            gap: 8px;

            margin-top: 25px;
        }

        .btn {
            border: none;

            border-radius: 9px;

            padding: 10px 16px;

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            text-decoration: none;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn-primary {
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            color: white;

            box-shadow:
                0 6px 14px rgba(37,99,235,0.18);
        }

        .btn-primary:hover {
            transform: translateY(-1px);

            box-shadow:
                0 9px 18px rgba(37,99,235,0.25);
        }

        .btn-secondary {
            background: #eef2f7;

            color: #475569;

            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;

            transform: translateY(-1px);
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

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

        @media (max-width: 800px) {

            .sidebar {
                width: 70px;
                padding: 20px 10px;
            }

            .logo {
                padding-bottom: 20px;
            }

            .logo h2,
            .logo p,
            .menu-title,
            .menu a span,
            .logout span {
                display: none;
            }

            .menu a {
                justify-content: center;
                padding: 13px 8px;
            }

            .menu a.active::before {
                left: -10px;
            }

            .logout a {
                justify-content: center;
            }

            .main {
                margin-left: 70px;
            }

            .header {
                padding: 0 20px;
            }

            .content {
                padding: 20px;
            }

            .admin-text {
                display: none;
            }
        }

        @media (max-width: 600px) {

            .page-header {
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .form-card {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
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

                <i class="fa-solid fa-gauge-high"
                    style="color:#bfdbfe;">
                </i>

                <span>
                    Dashboard
                </span>

            </a>

            <a href="{{ route('harga-tiket.index') }}"
                class="active">

                <i class="fa-solid fa-ticket"
                    style="color:#fcd34d;">
                </i>

                <span>
                    Manajemen Tiket
                </span>

            </a>

            <a href="{{ route('fasilitas.index') }}">

                <i class="fa-solid fa-person-swimming"
                    style="color:#67e8f9;">
                </i>

                <span>
                    Fasilitas
                </span>

            </a>

            <a href="{{ route('reservasi.index') }}">

                <i class="fa-solid fa-calendar-check"
                    style="color:#6ee7b7;">
                </i>

                <span>
                    Reservasi
                </span>

            </a>

            <a href="{{ route('promo.index') }}">

                <i class="fa-solid fa-tags"
                    style="color:#c4b5fd;">
                </i>

                <span>
                    Promo
                </span>

            </a>

            <a href="{{ route('review.index') }}">

                <i class="fa-solid fa-star"
                    style="color:#fde047;">
                </i>

                <span>
                    Review
                </span>

            </a>

        </div>

        <div class="logout">

            <a href="{{ route('logout') }}">

                <i class="fa-solid fa-right-from-bracket"
                    style="color:#f87171;">
                </i>

                <span>
                    Logout
                </span>

            </a>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="main">

        <!-- HEADER -->

        <header class="header">

            <div class="header-left">

                <h3>
                    Edit Harga Tiket
                </h3>

                <p>
                    Perbarui informasi harga tiket kolam renang
                </p>

            </div>

            <div class="admin-info">

                <div class="admin-text">

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

            <div class="content-inner">

                <!-- KEMBALI -->

                <a href="{{ route('harga-tiket.index') }}"
                    class="back-button">

                    <i class="fa-solid fa-arrow-left"></i>

                    Kembali ke Manajemen Tiket

                </a>


                <!-- PAGE HEADER -->

                <div class="page-header">

                    <div class="page-icon">

                        <i class="fa-solid fa-pen-to-square"></i>

                    </div>

                    <div>

                        <h1>
                            Edit Harga Tiket
                        </h1>

                        <p>
                            Perbarui informasi harga tiket kolam renang.
                        </p>

                    </div>

                </div>


                <!-- FORM CARD -->

                <div class="form-card">

                    <div class="form-card-header">

                        <div class="form-card-icon">

                            <i class="fa-solid fa-ticket"></i>

                        </div>

                        <div>

                            <h3>
                                Form Edit Harga Tiket
                            </h3>

                        </div>

                    </div>


                    <form
                        action="{{ route('harga-tiket.update', $hargaTiket->id) }}"
                        method="POST">

                        @csrf

                        @method('PUT')


                        <!-- KATEGORI TIKET -->

                        <div class="form-group">

                            <label for="kategori">

                                Kategori Tiket

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <select
                                id="kategori"
                                name="kategori"
                                class="form-control"
                                required>

                                <option
                                    value="Dewasa"
                                    {{ old('kategori', $hargaTiket->kategori) == 'Dewasa' ? 'selected' : '' }}>
                                    Dewasa
                                </option>

                                <option
                                    value="Anak"
                                    {{ old('kategori', $hargaTiket->kategori) == 'Anak' ? 'selected' : '' }}>
                                    Anak
                                </option>

                            </select>

                            @error('kategori')

                                <div class="error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- JENIS HARI -->

                        <div class="form-group">

                            <label for="jenis_hari">

                                Jenis Hari

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <select
                                id="jenis_hari"
                                name="jenis_hari"
                                class="form-control"
                                required>

                                <option
                                    value="Weekday"
                                    {{ old('jenis_hari', $hargaTiket->jenis_hari) == 'Weekday' ? 'selected' : '' }}>
                                    Weekday
                                </option>

                                <option
                                    value="Weekend"
                                    {{ old('jenis_hari', $hargaTiket->jenis_hari) == 'Weekend' ? 'selected' : '' }}>
                                    Weekend
                                </option>

                            </select>

                            @error('jenis_hari')

                                <div class="error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- HARGA TIKET -->

                        <div class="form-group">

                            <label for="harga">

                                Harga Tiket (Rp)

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="number"
                                id="harga"
                                name="harga"
                                class="form-control"
                                value="{{ old('harga', (int) $hargaTiket->harga) }}"
                                min="1000"
                                max="99999"
                                step="1"
                                placeholder="Contoh: 20000"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);"
                                required>

                            <div class="form-hint">

                                <i class="fa-solid fa-circle-info"></i>

                                <span>
                                    Harga tiket dapat diisi dari Rp1.000 sampai Rp99.999.
                                </span>

                            </div>

                            @error('harga')

                                <div class="error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- DIVIDER -->

                        <div class="divider"></div>


                        <!-- BUTTON -->

                        <div class="button-group">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Update Harga Tiket

                            </button>

                            <a
                                href="{{ route('harga-tiket.index') }}"
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