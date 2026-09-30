<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Reservasi - NEXPOOL</title>

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
            background: linear-gradient(
                180deg,
                #101c36 0%,
                #14213d 55%,
                #101b32 100%
            );
            color: white;
            padding: 24px 16px;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(15, 23, 42, 0.08);
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
            background: linear-gradient(
                90deg,
                #2563eb,
                #1d4ed8
            );
            color: white;
            box-shadow: 0 8px 20px rgba(37,99,235,0.25);
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
            background: linear-gradient(
                135deg,
                #2563eb,
                #38bdf8
            );
            color: white;
            font-size: 15px;
            font-weight: bold;
            box-shadow: 0 6px 15px rgba(37,99,235,0.2);
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 32px;
            max-width: 1700px;
        }

        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
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
            background: linear-gradient(
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
           RESERVATION TABS
        ========================= */

        .reservation-tabs {
            display: flex;
            align-items: center;
            gap: 4px;
            width: fit-content;
            background: white;
            border: 1px solid #e8ecf3;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 28px;
            box-shadow: 0 3px 12px rgba(15,23,42,0.03);
        }

        .reservation-tab {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 11px 16px;
            border-radius: 9px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .reservation-tab i {
            font-size: 13px;
        }

        .reservation-tab:hover {
            background: #f1f5f9;
            color: #334155;
        }

        .reservation-tab.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 5px 14px rgba(37,99,235,0.20);
        }

        .reservation-tab.active i {
            color: white;
        }

        /* =========================
           ALERT
        ========================= */

        .alert {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #bbf7d0;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        /* =========================
           INFO BOX
        ========================= */

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #1e3a8a;
        }

        .info-title {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 4px;
            font-weight: 700;
        }

        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background: white;
            border: 1px solid #e8ecf3;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(15,23,42,0.025);
        }

        .table-top {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 21px;
            border-bottom: 1px solid #eef1f5;
        }

        .table-top-icon {
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

        .table-top h3 {
            font-size: 14px;
            color: #172033;
        }

        .table-top p {
            margin-top: 3px;
            font-size: 10px;
            color: #94a3b8;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 13px 20px;
            border-bottom: 1px solid #e8ecf3;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        td {
            padding: 14px 20px;
            font-size: 12px;
            color: #475569;
            border-bottom: 1px solid #eef1f5;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #fafcff;
        }

        /* =========================
           RESERVATION DATA
        ========================= */

        .kode {
            font-weight: 700;
            color: #2563eb;
            white-space: nowrap;
        }

        .pool {
            font-weight: 600;
            color: #334155;
        }

        .nama-pengunjung {
            font-weight: 600;
            color: #334155;
        }

        .harga {
            font-weight: 700;
            color: #172033;
            white-space: nowrap;
        }

        .jumlah-tiket {
            line-height: 1.7;
            white-space: nowrap;
        }

        .jumlah-tiket span {
            color: #64748b;
        }

        /* =========================
           STATUS BADGE
        ========================= */

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
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

        .badge-default {
            background: #f1f5f9;
            color: #475569;
        }

        /* =========================
           ACTION
        ========================= */

        .actions {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-edit,
        .btn-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 10px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #bfdbfe;
            transform: translateY(-1px);
        }

        .btn-detail {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-detail:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            text-align: center;
            padding: 70px 30px;
            color: #94a3b8;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            margin: 0 auto 15px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 22px;
        }

        .empty h3 {
            margin-bottom: 7px;
            color: #64748b;
            font-size: 14px;
        }

        .empty p {
            font-size: 11px;
            color: #94a3b8;
        }

        /* =========================
           MODAL
        ========================= */

        .modal {
            display: none;
            position: fixed;
            z-index: 5000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            width: 100%;
            max-width: 720px;
            max-height: 90vh;
            overflow-y: auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(15,23,42,0.25);
            animation: modalShow 0.2s ease;
        }

        @keyframes modalShow {
            from {
                opacity: 0;
                transform: translateY(-15px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #eef1f5;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(
                135deg,
                #dbeafe,
                #eff6ff
            );
            color: #2563eb;
            font-size: 17px;
        }

        .modal-title h2 {
            font-size: 17px;
            color: #172033;
        }

        .modal-title p {
            margin-top: 3px;
            font-size: 10px;
            color: #94a3b8;
        }

        .modal-close {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 9px;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: 0.2s;
        }

        .modal-close:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .modal-item {
            background: #f8fafc;
            border: 1px solid #e8ecf3;
            border-radius: 11px;
            padding: 14px;
        }

        .modal-item.full {
            grid-column: span 2;
        }

        .modal-label {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 7px;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .modal-label i {
            width: 15px;
            color: #2563eb;
            text-align: center;
        }

        .modal-value {
            color: #172033;
            font-size: 13px;
            font-weight: 700;
            word-break: break-word;
        }

        .modal-ticket {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .ticket-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
        }

        .ticket-box span {
            display: block;
            font-size: 9px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .ticket-box strong {
            font-size: 13px;
            color: #172033;
        }

        .modal-total {
            background: linear-gradient(
                135deg,
                #eff6ff,
                #f0f9ff
            );
            border: 1px solid #bfdbfe;
        }

        .modal-total .modal-label i {
            color: #0ea5e9;
        }

        .total-price {
            color: #0f6fc0;
            font-size: 20px;
            font-weight: 800;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 17px 24px;
            border-top: 1px solid #eef1f5;
        }

        .modal-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        .modal-btn-close {
            background: #f1f5f9;
            color: #475569;
        }

        .modal-btn-close:hover {
            background: #e2e8f0;
        }

        .modal-btn-edit {
            background: #2563eb;
            color: white;
        }

        .modal-btn-edit:hover {
            background: #1d4ed8;
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

            .page-title h1 {
                font-size: 22px;
            }

            .page-title p {
                line-height: 1.5;
            }

            .reservation-tabs {
                width: 100%;
                overflow-x: auto;
            }

            .reservation-tab {
                white-space: nowrap;
                flex: 1;
                justify-content: center;
            }

            .info-box {
                font-size: 11px;
            }

            .table-top {
                padding: 16px;
            }

            .modal {
                padding: 12px;
            }

            .modal-content {
                max-height: 94vh;
                border-radius: 15px;
            }

            .modal-header {
                padding: 16px;
            }

            .modal-body {
                padding: 16px;
            }

            .modal-grid {
                grid-template-columns: 1fr;
            }

            .modal-item.full {
                grid-column: span 1;
            }

            .modal-ticket {
                grid-template-columns: 1fr 1fr;
            }

            .modal-footer {
                padding: 14px 16px;
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
                    style="color:#bfdbfe;"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('harga-tiket.index') }}">
                <i class="fa-solid fa-ticket"
                    style="color:#fcd34d;"></i>
                <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('fasilitas.index') }}">
                <i class="fa-solid fa-person-swimming"
                    style="color:#67e8f9;"></i>
                <span>Fasilitas</span>
            </a>

            <a href="{{ route('reservasi.index') }}"
                class="active">
                <i class="fa-solid fa-calendar-check"
                    style="color:#6ee7b7;"></i>
                <span>Reservasi</span>
            </a>

            <a href="{{ route('promo.index') }}">
                <i class="fa-solid fa-tags"
                    style="color:#c4b5fd;"></i>
                <span>Promo</span>
            </a>

            <a href="{{ route('review.index') }}">
                <i class="fa-solid fa-star"
                    style="color:#fde047;"></i>
                <span>Review</span>
            </a>

        </div>

        <div class="logout">

            <a href="{{ route('logout') }}">
                <i class="fa-solid fa-right-from-bracket"
                    style="color:#f87171;"></i>
                <span>Logout</span>
            </a>

        </div>

    </aside>


    <!-- MAIN -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">

            <div class="header-left">

                <h3>
                    Reservasi
                </h3>

                <p>
                    Kelola dan pantau reservasi pengunjung
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

                    {{ strtoupper(
                        substr(
                            session(
                                'admin_pool_nama',
                                'Admin NEXPOOL'
                            ),
                            0,
                            1
                        )
                    ) }}

                </div>

            </div>

        </header>


        <!-- CONTENT -->
        <section class="content">

            <!-- SUCCESS ALERT -->
            @if(session('success'))

                <div class="alert">

                    <i class="fa-solid fa-circle-check"></i>

                    {{ session('success') }}

                </div>

            @endif


            <!-- PAGE HEADER -->
            <div class="page-header">

                <div class="page-title">

                    <div class="page-icon">

                        <i class="fa-solid fa-calendar-check"></i>

                    </div>

                    <div>

                        <h1>
                            Data Reservasi
                        </h1>

                        <p>
                            Kelola dan pantau data reservasi pengunjung NEXPOOL.
                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 2 TAB RESERVASI
            ================================================== -->

            <div class="reservation-tabs">

                <!-- RESERVASI MENUNGGU -->
                <a href="{{ url('/reservasi') }}"
                    class="reservation-tab active">

                    <i class="fa-solid fa-clock"></i>

                    Reservasi Menunggu

                </a>


                <!-- RESERVASI DIKONFIRMASI -->
                <a href="{{ url('/reservasi/dikonfirmasi') }}"
                    class="reservation-tab">

                    <i class="fa-solid fa-circle-check"></i>

                    Reservasi Dikonfirmasi

                </a>

            </div>


            <!-- INFO -->
            <div class="info-box">

                <div class="info-title">

                    <i class="fa-solid fa-circle-info"></i>

                    <strong>
                        Informasi Reservasi
                    </strong>

                </div>

                Data reservasi pada halaman ini berasal dari
                <strong>aplikasi mobile NEXPOOL</strong>.
                Admin dapat memantau, memeriksa,
                dan memperbarui status reservasi yang masuk.

                <br>

                <strong>
                    Kolam:
                </strong>

                {{ session('admin_pool_nama', 'Kolam Renang') }}

            </div>


            <!-- TABLE CARD -->
            <div class="table-card">

                <div class="table-top">

                    <div class="table-top-icon">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <div>

                        <h3>
                            Daftar Reservasi Menunggu
                        </h3>

                        <p>
                            Reservasi pengunjung yang menunggu konfirmasi admin
                        </p>

                    </div>

                </div>


                @php

                    $dataReservasi = $reservasi ?? [];

                @endphp


                @if(count($dataReservasi) > 0)

                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>No</th>

                                    <th>
                                        Kode Reservasi
                                    </th>

                                    <th>
                                        Kolam Renang
                                    </th>

                                    <th>
                                        Pengunjung
                                    </th>

                                    <th>
                                        No. HP
                                    </th>

                                    <th>
                                        Tanggal Kunjungan
                                    </th>

                                    <th>
                                        Jumlah Tiket
                                    </th>

                                    <th>
                                        Total Harga
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($dataReservasi as $item)

                                    <tr>

                                        <!-- NO -->
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        <!-- KODE -->
                                        <td class="kode">

                                            {{ $item->kode_reservasi ?? '-' }}

                                        </td>


                                        <!-- KOLAM -->
                                        <td class="pool">

                                            {{ session(
                                                'admin_pool_nama',
                                                '-'
                                            ) }}

                                        </td>


                                        <!-- PENGUNJUNG -->
                                        <td class="nama-pengunjung">

                                            {{ $item->nama_pengunjung ?? '-' }}

                                        </td>


                                        <!-- NO HP -->
                                        <td>

                                            {{ $item->no_hp ?? '-' }}

                                        </td>


                                        <!-- TANGGAL -->
                                        <td>

                                            @if($item->tanggal_kunjungan)

                                                {{ \Carbon\Carbon::parse(
                                                    $item->tanggal_kunjungan
                                                )->format('d-m-Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        <!-- JUMLAH TIKET -->
                                        <td class="jumlah-tiket">

                                            <span>
                                                Dewasa:
                                            </span>

                                            {{ $item->jumlah_dewasa ?? 0 }}

                                            <br>

                                            <span>
                                                Anak:
                                            </span>

                                            {{ $item->jumlah_anak ?? 0 }}

                                        </td>


                                        <!-- TOTAL HARGA -->
                                        <td class="harga">

                                            Rp{{ number_format(
                                                $item->total_harga ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        </td>


                                        <!-- STATUS -->
                                        <td>

                                            @if(($item->status_reservasi ?? '') === 'Menunggu')

                                                <span class="badge badge-menunggu">

                                                    <i class="fa-solid fa-clock"></i>

                                                    Menunggu

                                                </span>

                                            @elseif(($item->status_reservasi ?? '') === 'Dikonfirmasi')

                                                <span class="badge badge-dikonfirmasi">

                                                    <i class="fa-solid fa-check"></i>

                                                    Dikonfirmasi

                                                </span>

                                            @elseif(($item->status_reservasi ?? '') === 'Selesai')

                                                <span class="badge badge-selesai">

                                                    <i class="fa-solid fa-circle-check"></i>

                                                    Selesai

                                                </span>

                                            @elseif(($item->status_reservasi ?? '') === 'Dibatalkan')

                                                <span class="badge badge-dibatalkan">

                                                    <i class="fa-solid fa-xmark"></i>

                                                    Dibatalkan

                                                </span>

                                            @else

                                                <span class="badge badge-default">

                                                    {{ $item->status_reservasi ?? 'Tidak diketahui' }}

                                                </span>

                                            @endif

                                        </td>


                                        <!-- AKSI -->
                                        <td>

                                            <div class="actions">

                                                <!-- KELOLA -->
                                                <a
                                                    href="{{ route(
                                                        'reservasi.edit',
                                                        $item->id
                                                    ) }}"
                                                    class="btn-edit"
                                                >

                                                    <i class="fa-solid fa-pen-to-square"></i>

                                                    Kelola

                                                </a>


                                                <!-- DETAIL -->
                                                <button
                                                    type="button"
                                                    class="btn-detail"
                                                    onclick="openReservationModal('{{ $item->id }}')"
                                                >

                                                    <i class="fa-solid fa-eye"></i>

                                                    Detail

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                @else

                    <!-- EMPTY -->

                    <div class="empty">

                        <div class="empty-icon">

                            <i class="fa-solid fa-calendar-xmark"></i>

                        </div>

                        <h3>
                            Belum Ada Reservasi
                        </h3>

                        <p>

                            Belum ada reservasi yang masuk dari aplikasi mobile
                            untuk

                            <strong>

                                {{ session(
                                    'admin_pool_nama',
                                    'kolam ini'
                                ) }}

                            </strong>.

                        </p>

                    </div>

                @endif

            </div>

        </section>

    </main>


    <!-- =====================================================
         MODAL DETAIL RESERVASI
    ====================================================== -->

    @foreach($dataReservasi as $item)

        <div
            id="reservationModal{{ $item->id }}"
            class="modal"
            onclick="closeReservationModalOutside(
                event,
                '{{ $item->id }}'
            )"
        >

            <div
                class="modal-content"
                onclick="event.stopPropagation()"
            >

                <!-- MODAL HEADER -->

                <div class="modal-header">

                    <div class="modal-title">

                        <div class="modal-icon">

                            <i class="fa-solid fa-ticket"></i>

                        </div>

                        <div>

                            <h2>
                                Detail Reservasi
                            </h2>

                            <p>
                                Informasi lengkap reservasi pengunjung
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="modal-close"
                        onclick="closeReservationModal(
                            '{{ $item->id }}'
                        )"
                    >

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                <!-- MODAL BODY -->

                <div class="modal-body">

                    <div class="modal-grid">

                        <!-- KODE -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-hashtag"></i>

                                Kode Reservasi

                            </div>

                            <div class="modal-value">

                                {{ $item->kode_reservasi ?? '-' }}

                            </div>

                        </div>


                        <!-- NAMA -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-user"></i>

                                Nama Pengunjung

                            </div>

                            <div class="modal-value">

                                {{ $item->nama_pengunjung ?? '-' }}

                            </div>

                        </div>


                        <!-- NO HP -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-phone"></i>

                                No. HP

                            </div>

                            <div class="modal-value">

                                {{ $item->no_hp ?? '-' }}

                            </div>

                        </div>


                        <!-- KOLAM -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-person-swimming"></i>

                                Kolam Renang

                            </div>

                            <div class="modal-value">

                                {{ session(
                                    'admin_pool_nama',
                                    'Kolam Renang'
                                ) }}

                            </div>

                        </div>


                        <!-- TANGGAL -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-calendar-days"></i>

                                Tanggal Kunjungan

                            </div>

                            <div class="modal-value">

                                @if($item->tanggal_kunjungan)

                                    {{ \Carbon\Carbon::parse(
                                        $item->tanggal_kunjungan
                                    )->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <!-- STATUS -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-circle-check"></i>

                                Status Reservasi

                            </div>


                            @if(($item->status_reservasi ?? '') === 'Menunggu')

                                <span class="badge badge-menunggu">

                                    <i class="fa-solid fa-clock"></i>

                                    Menunggu

                                </span>

                            @elseif(($item->status_reservasi ?? '') === 'Dikonfirmasi')

                                <span class="badge badge-dikonfirmasi">

                                    <i class="fa-solid fa-check"></i>

                                    Dikonfirmasi

                                </span>

                            @elseif(($item->status_reservasi ?? '') === 'Selesai')

                                <span class="badge badge-selesai">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Selesai

                                </span>

                            @elseif(($item->status_reservasi ?? '') === 'Dibatalkan')

                                <span class="badge badge-dibatalkan">

                                    <i class="fa-solid fa-xmark"></i>

                                    Dibatalkan

                                </span>

                            @else

                                <span class="badge badge-default">

                                    {{ $item->status_reservasi ?? 'Tidak diketahui' }}

                                </span>

                            @endif

                        </div>


                        <!-- JUMLAH TIKET -->

                        <div class="modal-item">

                            <div class="modal-label">

                                <i class="fa-solid fa-users"></i>

                                Jumlah Tiket

                            </div>


                            <div class="modal-ticket">

                                <div class="ticket-box">

                                    <span>
                                        Dewasa
                                    </span>

                                    <strong>

                                        {{ $item->jumlah_dewasa ?? 0 }}

                                        Tiket

                                    </strong>

                                </div>


                                <div class="ticket-box">

                                    <span>
                                        Anak
                                    </span>

                                    <strong>

                                        {{ $item->jumlah_anak ?? 0 }}

                                        Tiket

                                    </strong>

                                </div>

                            </div>

                        </div>


                        <!-- TOTAL -->

                        <div class="modal-item modal-total">

                            <div class="modal-label">

                                <i class="fa-solid fa-money-bill-wave"></i>

                                Total Pembayaran

                            </div>

                            <div class="total-price">

                                Rp{{ number_format(
                                    $item->total_harga ?? 0,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        </div>


                        <!-- INFORMASI -->

                        <div class="modal-item full">

                            <div class="modal-label">

                                <i class="fa-solid fa-circle-info"></i>

                                Informasi

                            </div>

                            <div
                                class="modal-value"
                                style="
                                    font-size:11px;
                                    font-weight:500;
                                    line-height:1.6;
                                    color:#64748b;
                                "
                            >

                                Data reservasi berasal dari
                                aplikasi mobile NEXPOOL.
                                Admin dapat memeriksa dan
                                memperbarui status reservasi
                                melalui menu Kelola.

                            </div>

                        </div>

                    </div>

                </div>


                <!-- MODAL FOOTER -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="modal-btn modal-btn-close"
                        onclick="closeReservationModal(
                            '{{ $item->id }}'
                        )"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Tutup

                    </button>


                    <a
                        href="{{ route(
                            'reservasi.edit',
                            $item->id
                        ) }}"
                        class="modal-btn modal-btn-edit"
                    >

                        <i class="fa-solid fa-pen-to-square"></i>

                        Kelola Status

                    </a>

                </div>

            </div>

        </div>

    @endforeach


    <!-- =====================================================
         JAVASCRIPT MODAL
    ====================================================== -->

    <script>

        function openReservationModal(id) {

            const modal =
                document.getElementById(
                    'reservationModal' + id
                );

            if (modal) {

                modal.classList.add('show');

                document.body.style.overflow = 'hidden';

            }
        }


        function closeReservationModal(id) {

            const modal =
                document.getElementById(
                    'reservationModal' + id
                );

            if (modal) {

                modal.classList.remove('show');

                document.body.style.overflow = '';

            }
        }


        function closeReservationModalOutside(event, id) {

            if (
                event.target ===
                event.currentTarget
            ) {

                closeReservationModal(id);

            }

        }


        /* Tutup popup dengan tombol ESC */

        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    const modals =
                        document.querySelectorAll(
                            '.modal.show'
                        );

                    modals.forEach(
                        function(modal) {

                            modal.classList.remove('show');

                        }
                    );

                    document.body.style.overflow = '';

                }

            }
        );

    </script>

</body>

</html>