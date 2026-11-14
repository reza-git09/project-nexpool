
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

<div class="revenue-card">
    <div class="revenue-card-header">
        <div>
            <h3 id="revenue-title">Grafik Pendapatan</h3>
            <p id="revenue-total">Memuat total pendapatan...</p>
        </div>
        <div class="revenue-filters">
            <label>
                <span>Kolam</span>
                <select id="revenue-pool">
                    <option value="">Semua kolam</option>
                    @foreach($kolam as $item)
                        <option value="{{ $item->pool_id }}">{{ $item->nama_kolam }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>Periode</span>
                <select id="revenue-period">
                    <option value="7_hari">7 hari terakhir</option>
                    <option value="30_hari">30 hari terakhir</option>
                    <option value="bulan_ini">Bulan ini</option>
                </select>
            </label>
        </div>
    </div>
    <p id="revenue-message" class="revenue-message" hidden></p>
    <div class="revenue-chart-wrap">
        <canvas id="revenue-chart" aria-label="Grafik pendapatan berdasarkan tanggal"></canvas>
    </div>
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

    .revenue-card {
        background: white;
        border-radius: 13px;
        padding: 22px;
        box-shadow: 0 3px 15px rgba(15,23,42,.05);
    }

    .revenue-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 18px;
    }

    .revenue-card-header h3 {
        font-size: 18px;
        margin-bottom: 7px;
    }

    .revenue-card-header p,
    .revenue-filters label span,
    .revenue-message {
        color: #8490a3;
        font-size: 12px;
    }

    .revenue-filters {
        display: flex;
        gap: 12px;
    }

    .revenue-filters label {
        display: grid;
        gap: 6px;
    }

    .revenue-filters select {
        min-width: 150px;
        padding: 9px 11px;
        border: 1px solid #dbe2ea;
        border-radius: 8px;
        background: white;
        color: #172033;
        font-size: 12px;
    }

    .revenue-chart-wrap {
        position: relative;
        height: 310px;
    }

    .revenue-message {
        margin-bottom: 12px;
        text-align: center;
    }

    @media(max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }

        .master-welcome {
            align-items: flex-start;
            flex-direction: column;
        }

        .revenue-card-header { flex-direction: column; }
        .revenue-filters { width: 100%; }
        .revenue-filters label { flex: 1; min-width: 0; }
        .revenue-filters select { min-width: 0; width: 100%; }
        .revenue-chart-wrap { height: 250px; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
    (() => {
        const poolSelect = document.getElementById('revenue-pool');
        const periodSelect = document.getElementById('revenue-period');
        const title = document.getElementById('revenue-title');
        const total = document.getElementById('revenue-total');
        const message = document.getElementById('revenue-message');
        const context = document.getElementById('revenue-chart').getContext('2d');
        const rupiah = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        });
        const dateFormat = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            timeZone: 'Asia/Jakarta'
        });
        let chart;

        async function updateChart() {
            const params = new URLSearchParams({
                pool_id: poolSelect.value,
                periode: periodSelect.value
            });
            const response = await fetch(`{{ route('admin.master.dashboard.grafik-pendapatan') }}?${params}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) {
                throw new Error('Grafik pendapatan gagal dimuat.');
            }

            const data = await response.json();
            title.textContent = `Pendapatan: ${data.pool_name} (${data.period_name})`;
            total.textContent = `Total pendapatan: ${rupiah.format(data.total)}`;
            message.hidden = data.has_transactions;
            message.textContent = data.has_transactions
                ? ''
                : 'Belum ada transaksi pada periode ini.';

            if (chart) {
                chart.destroy();
            }

            const labels = data.labels.map((value) => {
                return new Date(`${value}T12:00:00+07:00`);
            });

            chart = new Chart(context, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: data.values,
                        backgroundColor: 'rgba(37, 99, 235, 0.72)',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    scales: {
                        x: {
                            type: 'category',
                            ticks: {
                                callback: (_value, index) => dateFormat.format(labels[index])
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => rupiah.format(value) }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                title: (items) => dateFormat.format(labels[items[0].dataIndex]),
                                label: (item) => `Pendapatan: ${rupiah.format(item.raw)}`
                            }
                        },
                        legend: { display: false }
                    }
                }
            });
        }

        [poolSelect, periodSelect].forEach((select) => {
            select.addEventListener('change', () => {
                updateChart().catch((error) => {
                    if (chart) {
                        chart.destroy();
                        chart = null;
                    }
                    message.hidden = false;
                    message.textContent = error.message;
                });
            });
        });

        updateChart().catch((error) => {
            if (chart) {
                chart.destroy();
                chart = null;
            }
            message.hidden = false;
            message.textContent = error.message;
        });
    })();
</script>
@endpush
