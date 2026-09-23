<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <title>Tambah Promo - NEXPOOL</title>


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

            border-bottom:
                1px solid rgba(255,255,255,0.08);

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

            border-top:
                1px solid rgba(255,255,255,0.08);
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
            background:
                rgba(239,68,68,0.12);

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

            background:
                rgba(255,255,255,0.96);

            backdrop-filter: blur(10px);

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 32px;

            border-bottom:
                1px solid #e8ecf3;

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


        /* =========================
           KEMBALI
        ========================= */

        .btn-kembali {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            margin-bottom: 20px;

            padding: 9px 13px;

            background: #f1f5f9;

            color: #475569;

            border:
                1px solid #e2e8f0;

            border-radius: 8px;

            font-size: 11px;

            font-weight: 600;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }


        .btn-kembali:hover {
            background: #e2e8f0;

            transform: translateX(-1px);
        }


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 25px;
        }


        .page-title {
            display: flex;

            align-items: center;

            gap: 14px;
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


        .page-title h1 {
            font-size: 25px;

            color: #111827;

            margin-bottom: 6px;
        }


        .page-title p {
            color: #7b8494;

            font-size: 13px;
        }


        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            background: white;

            border:
                1px solid #e8ecf3;

            border-radius: 16px;

            padding: 25px;

            max-width: 900px;

            box-shadow:
                0 3px 12px rgba(15,23,42,0.025);
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

            font-weight: 700;

            color: #334155;

            margin-bottom: 7px;
        }


        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            padding: 11px 13px;

            border:
                1px solid #d9dee7;

            border-radius: 9px;

            font-size: 12px;

            outline: none;

            background: white;

            color: #334155;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }


        .form-group input,
        .form-group select {
            height: 44px;
        }


        .form-group textarea {
            min-height: 100px;

            resize: vertical;

            line-height: 1.5;
        }


        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37,99,235,0.08);
        }


        .form-row {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 15px;
        }


        .error {
            color: #dc2626;

            font-size: 11px;

            margin-top: 5px;

            line-height: 1.4;
        }


        /* =========================
           BUTTON
        ========================= */

        .buttons {
            display: flex;

            gap: 10px;

            margin-top: 25px;
        }


        .btn {
            border: none;

            padding: 10px 16px;

            border-radius: 8px;

            font-size: 12px;

            cursor: pointer;

            text-decoration: none;

            font-weight: 600;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
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
                0 5px 12px rgba(37,99,235,0.18);
        }


        .btn-primary:hover {
            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #1e40af
                );

            transform: translateY(-1px);
        }


        .btn-secondary {
            background: #f1f5f9;

            color: #475569;

            border:
                1px solid #e2e8f0;
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


            .form-row {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .page-header {
                align-items: flex-start;
            }


            .page-title h1 {
                font-size: 22px;
            }


            .page-title p {
                line-height: 1.5;
            }


            .form-card {
                padding: 20px;
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

                <i
                    class="fa-solid fa-gauge-high"
                    style="color:#bfdbfe;"
                ></i>

                <span>
                    Dashboard
                </span>

            </a>


            <a href="{{ route('harga-tiket.index') }}">

                <i
                    class="fa-solid fa-ticket"
                    style="color:#fcd34d;"
                ></i>

                <span>
                    Manajemen Tiket
                </span>

            </a>


            <a href="{{ route('fasilitas.index') }}">

                <i
                    class="fa-solid fa-person-swimming"
                    style="color:#67e8f9;"
                ></i>

                <span>
                    Fasilitas
                </span>

            </a>


            <a href="{{ route('reservasi.index') }}">

                <i
                    class="fa-solid fa-calendar-check"
                    style="color:#6ee7b7;"
                ></i>

                <span>
                    Reservasi
                </span>

            </a>


            <a
                href="{{ route('promo.index') }}"
                class="active"
            >

                <i
                    class="fa-solid fa-tags"
                    style="color:#c4b5fd;"
                ></i>

                <span>
                    Promo
                </span>

            </a>


            <a href="{{ route('review.index') }}">

                <i
                    class="fa-solid fa-star"
                    style="color:#fde047;"
                ></i>

                <span>
                    Review
                </span>

            </a>


        </div>


        <div class="logout">

            <a href="{{ route('logout') }}">

                <i
                    class="fa-solid fa-right-from-bracket"
                    style="color:#f87171;"
                ></i>

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
                    Tambah Promo
                </h3>

                <p>
                    Tambahkan promo baru untuk pengunjung NEXPOOL
                </p>

            </div>


            <div class="admin-info">

                <div class="admin-text">

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



        <!-- CONTENT -->

        <section class="content">


            <!-- KEMBALI -->

            <a
                href="{{ route('promo.index') }}"
                class="btn-kembali"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Kembali ke Promo

            </a>



            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="page-title">

                    <div class="page-icon">

                        <i class="fa-solid fa-tags"></i>

                    </div>


                    <div>

                        <h1>
                            Tambah Promo
                        </h1>

                        <p>
                            Tambahkan data promo diskon baru untuk pengunjung NEXPOOL.
                        </p>

                    </div>

                </div>

            </div>



            <!-- FORM -->

            <div class="form-card">


                <form
                    action="{{ route('promo.store') }}"
                    method="POST"
                >

                    @csrf


                    <!-- NAMA PROMO -->

                    <div class="form-group">

                        <label>
                            Nama Promo
                        </label>

                        <input
                            type="text"
                            name="nama_promo"
                            value="{{ old('nama_promo') }}"
                            placeholder="Contoh: Promo Liburan Hemat"
                            required
                        >

                        @error('nama_promo')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    <!-- DESKRIPSI -->

                    <div class="form-group">

                        <label>
                            Deskripsi Promo
                        </label>

                        <textarea
                            name="deskripsi"
                            placeholder="Masukkan deskripsi promo..."
                        >{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    <!-- JENIS DISKON & NILAI DISKON -->

                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Jenis Diskon
                            </label>

                            <select
                                name="jenis_diskon"
                                required
                            >

                                <option value="">
                                    -- Pilih Jenis Diskon --
                                </option>

                                <option
                                    value="Persentase"
                                    {{ old('jenis_diskon') == 'Persentase' ? 'selected' : '' }}
                                >
                                    Persentase (%)
                                </option>

                                <option
                                    value="Nominal"
                                    {{ old('jenis_diskon') == 'Nominal' ? 'selected' : '' }}
                                >
                                    Nominal (Rp)
                                </option>

                            </select>

                            @error('jenis_diskon')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group">

                            <label>
                                Nilai Diskon
                            </label>

                            <input
                                type="number"
                                id="nilai_diskon"
                                name="nilai_diskon"
                                value="{{ old('nilai_diskon') }}"
                                min="0"
                                max="100"
                                step="1"
                                placeholder="Contoh: 20 (maks. 100 untuk persentase)"
                                required
                                oninput="validateDiskon(this)"
                                onkeydown="blockSymbol(event)"
                            >

                            <div
                                id="error-diskon"
                                class="error"
                                style="display:none;"
                            ></div>

                            @error('nilai_diskon')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>



                    <!-- TANGGAL MULAI & TANGGAL SELESAI -->

                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai') }}"
                                required
                                onchange="validateTanggal()"
                            >

                            @error('tanggal_mulai')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group">

                            <label>
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                id="tanggal_selesai"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                required
                                onchange="validateTanggal()"
                            >

                            <div
                                id="error-tanggal"
                                class="error"
                                style="display:none;"
                            ></div>

                            @error('tanggal_selesai')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>



                    <!-- STATUS PROMO -->

                    <div class="form-group">

                        <label>
                            Status Promo
                        </label>

                        <select
                            name="status"
                            required
                        >

                            <option
                                value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}
                            >
                                Aktif
                            </option>

                            <option
                                value="0"
                                {{ old('status') == '0' ? 'selected' : '' }}
                            >
                                Tidak Aktif
                            </option>

                        </select>

                        @error('status')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    <!-- BUTTON -->

                    <div class="buttons">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fa-solid fa-floppy-disk"></i>

                            Simpan Promo

                        </button>


                        <a
                            href="{{ route('promo.index') }}"
                            class="btn btn-secondary"
                        >

                            Batal

                        </a>

                    </div>


                </form>


            </div>


        </section>


    </main>



<script>

    // ====== VALIDASI NILAI DISKON ======

    const jenisDiskonEl = document.querySelector('select[name="jenis_diskon"]');

    const nilaiDiskonEl = document.getElementById('nilai_diskon');

    const errorDiskon   = document.getElementById('error-diskon');


    function blockSymbol(e) {

        // Blokir karakter simbol seperti +, -, e, E, ., koma, dsb.

        const blocked = ['+', '-', 'e', 'E', '.', ','];

        if (blocked.includes(e.key)) {

            e.preventDefault();

            showDiskonError(
                'Nilai diskon tidak boleh mengandung simbol. Masukkan angka saja.'
            );

        } else {

            hideDiskonError();

        }

    }


    function validateDiskon(input) {

        const jenis = jenisDiskonEl.value;

        const val   = parseFloat(input.value);


        if (input.value === '') {

            hideDiskonError();

            return;

        }


        if (jenis === 'Persentase' && val > 100) {

            showDiskonError(
                'Nilai diskon persentase tidak boleh melebihi 100%.'
            );

            input.value = 100;

        } else if (val < 0) {

            showDiskonError(
                'Nilai diskon tidak boleh negatif.'
            );

            input.value = 0;

        } else {

            hideDiskonError();

        }

    }


    // Update max ketika jenis diskon berubah

    jenisDiskonEl.addEventListener('change', function () {

        if (this.value === 'Persentase') {

            nilaiDiskonEl.setAttribute('max', '100');

            nilaiDiskonEl.placeholder =
                'Contoh: 20 (maks. 100%)';


            // Re-validasi jika sudah ada nilai

            if (nilaiDiskonEl.value !== '') {

                validateDiskon(nilaiDiskonEl);

            }

        } else {

            nilaiDiskonEl.removeAttribute('max');

            nilaiDiskonEl.placeholder =
                'Contoh: 10000';

            hideDiskonError();

        }

    });


    function showDiskonError(msg) {

        errorDiskon.textContent = msg;

        errorDiskon.style.display = 'block';

    }


    function hideDiskonError() {

        errorDiskon.style.display = 'none';

    }



    // ====== VALIDASI TANGGAL ======

    const mulaiEl   = document.getElementById('tanggal_mulai');

    const selesaiEl = document.getElementById('tanggal_selesai');

    const errorTgl  = document.getElementById('error-tanggal');


    function validateTanggal() {

        const mulai   = mulaiEl.value;

        const selesai = selesaiEl.value;


        if (!mulai || !selesai) {

            hideTglError();

            return;

        }


        const tMulai   = new Date(mulai);

        const tSelesai = new Date(selesai);

        const diffMs   = tSelesai - tMulai;

        const diffDays =
            diffMs / (1000 * 60 * 60 * 24);


        // Hitung maks 1 bulan ke depan dari tanggal mulai

        const maxSelesai = new Date(tMulai);

        maxSelesai.setMonth(
            maxSelesai.getMonth() + 1
        );


        if (diffDays <= 0) {

            showTglError(
                'Tanggal Selesai harus minimal 1 hari setelah Tanggal Mulai.'
            );

            selesaiEl.value = '';

        } else if (tSelesai > maxSelesai) {

            showTglError(
                'Tanggal Selesai tidak boleh lebih dari 1 bulan setelah Tanggal Mulai.'
            );

            selesaiEl.value = '';

        } else {

            hideTglError();

        }

    }


    function showTglError(msg) {

        errorTgl.textContent = msg;

        errorTgl.style.display = 'block';

    }


    function hideTglError() {

        errorTgl.style.display = 'none';

    }



    // Cegah submit jika masih ada error

    document.querySelector('form').addEventListener('submit', function (e) {

        // Cek error diskon

        if (
            errorDiskon.style.display !== 'none' &&
            errorDiskon.textContent !== ''
        ) {

            e.preventDefault();

            alert(
                'Harap perbaiki nilai diskon terlebih dahulu.'
            );

            return;

        }


        // Cek error tanggal

        if (
            errorTgl.style.display !== 'none' &&
            errorTgl.textContent !== ''
        ) {

            e.preventDefault();

            alert(
                'Harap perbaiki tanggal promo terlebih dahulu.'
            );

            return;

        }


        // Cek tanggal kosong

        if (!mulaiEl.value || !selesaiEl.value) {

            return;

        }


        validateTanggal();


        if (errorTgl.style.display !== 'none') {

            e.preventDefault();

        }

    });

</script>


</body>

</html>