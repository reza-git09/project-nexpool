
@extends('layout.admin_master')

@section('title', 'Dashboard Admin Master')
@section('header-title', 'Dashboard Admin Master')
@section('header-subtitle', 'Pusat pengelolaan seluruh kolam renang NEXPOOL')

@section('content')

<div class="master-welcome">
    <div>
        <h2>Selamat Datang, Admin Master!</h2>
        <p>Kelola dan pantau seluruh operasional NEXPOOL dalam satu dashboard.</p>
    </div>
    <div class="master-badge">
        <i class="fa-solid fa-crown"></i> SUPER ADMIN
    </div>
</div>

{{-- STATISTIK --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p>Total Admin Kolam</p>
            <h2>{{ $totalAdmin }}</h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon cyan">
            <i class="fa-solid fa-person-swimming"></i>
        </div>
        <div>
            <p>Total Kolam Renang</p>
            <h2>{{ $totalKolam }}</h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div>
            <p>Total Reservasi</p>
            <h2>{{ $totalReservasi }}</h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-ticket"></i>
        </div>
        <div>
            <p>Total Data Harga Tiket</p>
            <h2>{{ $totalHargaTiket }}</h2>
        </div>
    </div>

</div>

{{-- PENGAJUAN ADMIN --}}
<div class="application-card">
    <div class="application-icon">
        <i class="fa-solid fa-user-plus"></i>
    </div>
    <div class="application-info">
        <h3>Pengajuan Admin Baru</h3>
        <p>Jumlah pengajuan yang menunggu persetujuan.</p>
    </div>
    <div class="application-number">
        {{ $totalPengajuan }}
    </div>
</div>

{{-- MENU MANAJEMEN --}}
<div class="section-heading">
    <h3>Manajemen NEXPOOL</h3>
    <p>Pilih menu untuk mengelola sistem secara keseluruhan.</p>
</div>

<div class="management-grid">

    @php
        $menus = [
            [
                'icon' => 'fa-person-swimming',
                'title' => 'Data Kolam Renang',
                'desc' => 'Mengelola seluruh data kolam renang.',
                'route' => null,
                'color' => 'blue'
            ],
            [
                'icon' => 'fa-user-plus',
                'title' => 'Data Admin Kolam',
                'desc' => 'Mengelola akun administrator setiap kolam.',
                'route' => null,
                'color' => 'cyan'
            ],
            [
                'icon' => 'fa-calendar-check',
                'title' => 'Data Reservasi',
                'desc' => 'Memantau reservasi dari seluruh kolam.',
                'route' => null,
                'color' => 'green'
            ],
            [
                'icon' => 'fa-calendar-check',
                'title' => 'Harga Tiket',
                'desc' => 'Memantau dan mengelola harga tiket.',
                'route' => null,
                'color' => 'orange'
            ],
            [
                'icon' => 'fa-tags',
                'title' => 'Data Promo',
                'desc' => 'Mengelola promo seluruh kolam renang.',
                'route' => null,
                'color' => 'purple'
            ],
            [
                'icon' => 'fa-user-plus',
                'title' => 'Pengajuan Admin',
                'desc' => 'Memeriksa dan menyetujui pengajuan admin.',
                'route' => 'admin.master.applications.index',
                'color' => 'pink'
            ],
        ];
    @endphp

    @foreach($menus as $menu)
        <div class="management-card">
            <div class="management-icon {{ $menu['color'] }}">
                <i class="fa-solid {{ $menu['icon'] }}"></i>
            </div>

            <h4>{{ $menu['title'] }}</h4>
            <p>{{ $menu['desc'] }}</p>

            @if($menu['route'] && \Illuminate\Support\Facades\Route::has($menu['route']))
                <a href="{{ route($menu['route']) }}" class="management-link">
                    Buka Menu <i class="fa-solid fa-arrow-right"></i>
                </a>
            @else
                <span class="management-disabled">Menu belum tersedia</span>
            @endif
        </div>
    @endforeach

</div>

@endsection

@push('styles')
<style>
    .master-welcome {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 25px;
        background: linear-gradient(120deg, #173f7a, #2675d8);
        border-radius: 15px;
        color: white;
        margin-bottom: 25px;
    }

    .master-welcome h2 {
        font-size: 22px;
        margin-bottom: 8px;
    }

    .master-welcome p {
        font-size: 13px;
        color: #dbeafe;
    }

    .master-badge {
        background: rgba(255,255,255,.17);
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: bold;
        white-space: nowrap;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        padding: 22px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 3px 15px rgba(15,23,42,.05);
    }

    .stat-card p {
        color: #8490a3;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .stat-card h2 {
        font-size: 26px;
        color: #172033;
    }

    .stat-icon, .management-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .blue { background: #e8f0ff; color: #2563eb; }
    .cyan { background: #e0f7fa; color: #0891b2; }
    .green { background: #dcfce7; color: #16a34a; }
    .orange { background: #fff1df; color: #ea580c; }
    .purple { background: #f3e8ff; color: #9333ea; }
    .pink { background: #fce7f3; color: #db2777; }

    .application-card {
        background: white;
        padding: 20px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 3px 15px rgba(15,23,42,.05);
        margin-bottom: 30px;
    }

    .application-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #fff1f2;
        color: #e11d48;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .application-info { flex: 1; }

    .application-info h3 {
        font-size: 15px;
        margin-bottom: 6px;
    }

    .application-info p {
        font-size: 12px;
        color: #8490a3;
    }

    .application-number {
        font-size: 25px;
        font-weight: bold;
        color: #2563eb;
    }

    .section-heading { margin-bottom: 18px; }

    .section-heading h3 {
        font-size: 18px;
        margin-bottom: 6px;
    }

    .section-heading p {
        font-size: 12px;
        color: #8490a3;
    }

    .management-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .management-card {
        background: white;
        border-radius: 13px;
        padding: 22px;
        box-shadow: 0 3px 15px rgba(15,23,42,.05);
        transition: .2s;
    }

    .management-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(15,23,42,.09);
    }

    .management-card h4 {
        font-size: 14px;
        margin: 15px 0 8px;
    }

    .management-card p {
        color: #8490a3;
        font-size: 12px;
        line-height: 1.6;
        min-height: 38px;
        margin-bottom: 15px;
    }

    .management-link {
        color: #2563eb;
        font-size: 12px;
        font-weight: bold;
    }

    .management-link i { margin-left: 5px; }

    .management-disabled {
        color: #9ca3af;
        font-size: 12px;
    }

    @media(max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .management-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width: 600px) {
        .stats-grid, .management-grid {
            grid-template-columns: 1fr;
        }

        .master-welcome {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush
