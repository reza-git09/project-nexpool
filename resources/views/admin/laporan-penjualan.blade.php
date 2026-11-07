@extends('layout.admin')

@section('title', 'Laporan Penjualan Tiket - NEXPOOL')
@section('header-title', 'Laporan Penjualan Tiket')
@section('header-subtitle', 'Kelola dan pantau penjualan tiket')

@push('styles')
    <style>
        .lp {
            --bg: #f6f8fc; --card: #ffffff; --ink: #172033; --muted: #7b8494; --line: #e8ecf3;
            --brand: #1d4ed8; --brand-2: #2563eb; --ok: #047857; --ok-bg: #ecfdf5;
            --wait: #a86a00; --wait-bg: #fff3d6; --bad: #b3261e; --bad-bg: #fde8e6; --neutral-bg: #eceff4;
            color: var(--ink); font-size: 14px; line-height: 1.5;
        }
        .lp *, .lp *::before, .lp *::after { box-sizing: border-box; }
        .lp .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; }
        .lp .page-title { display: flex; align-items: center; gap: 14px; }
        .lp .page-icon { width: 48px; height: 48px; flex-shrink: 0; border-radius: 13px; background: linear-gradient(135deg,#dbeafe,#eff6ff); color: #2563eb; font-size: 19px; display: flex; align-items: center; justify-content: center; }
        .lp h1 { margin: 0 0 6px; font-size: 25px; color: #111827; }
        .lp .page-title p { color: var(--muted); font-size: 13px; }
        .lp .sub { color: var(--muted); margin-top: 2px; }
        .lp .head { display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: flex-end; margin-bottom: 18px; }
        .lp .btns { display: flex; gap: 8px; flex-wrap: wrap; }
        .lp .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; border: 1px solid var(--line); background: #fff; color: var(--ink); padding: 9px 14px; border-radius: 9px; font: inherit; font-weight: 600; cursor: pointer; text-decoration: none; transition: .2s; }
        .lp .btn:hover { border-color: var(--brand-2); }
        .lp .btn.primary { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }
        .lp .btn.primary:hover { background: #1e40af; }
        .lp .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 18px; margin-bottom: 16px; box-shadow: 0 3px 12px rgba(15,23,42,.025); }
        .lp .card h2 { margin: 0 0 12px; font-size: 15px; color: #1f2937; }
        .lp form.filters { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; }
        .lp label { display: block; font-size: 12px; color: #718096; margin-bottom: 4px; }
        .lp input { width: min(100%, 260px); min-height: 41px; padding: 8px 10px; border: 1px solid #e2e8f0; border-radius: 9px; font: inherit; background: #fff; color: #172033; }
        .lp input:focus { outline: 2px solid #bfdbfe; border-color: #60a5fa; }
        .lp .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .lp .stat { background: var(--card); border: 1px solid var(--line); border-radius: 15px; padding: 15px 17px; border-left: 5px solid #3b82f6; }
        .lp .stat .k { color: #718096; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
        .lp .stat .v { font-size: 25px; font-weight: 700; color: #17457f; margin-top: 3px; }
        .lp .stat .n { color: var(--muted); font-size: 12px; }
        .lp .note { padding: 12px 15px; border-radius: 10px; background: #fff4d6; color: #a86a00; margin-bottom: 12px; }
        .lp .error { padding: 16px; border-radius: 10px; background: var(--bad-bg); color: var(--bad); }
        .lp table { width: 100%; border-collapse: collapse; }
        .lp th, .lp td { text-align: left; padding: 12px 16px; border-bottom: 1px solid #eef1f5; vertical-align: middle; }
        .lp th { font-size: 11px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; background: #f8fafc; white-space: nowrap; }
        .lp td { font-size: 12px; color: #475569; }
        .lp tbody tr { transition: background .2s; }
        .lp tbody tr:hover { background: #fafcff; }
        .lp td.num, .lp th.num { text-align: right; white-space: nowrap; }
        .lp .scroll { overflow-x: auto; }
        .lp .badge { display: inline-block; padding: 5px 9px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .lp .badge.ok { background: var(--ok-bg); color: var(--ok); }
        .lp .badge.wait { background: var(--wait-bg); color: var(--wait); }
        .lp .badge.bad { background: var(--bad-bg); color: var(--bad); }
        .lp .badge.neutral { background: var(--neutral-bg); color: var(--muted); }
        .lp .bar { height: 8px; background: var(--neutral-bg); border-radius: 99px; overflow: hidden; min-width: 80px; }
        .lp .bar > span { display: block; height: 100%; background: var(--brand-2); }
        .lp .empty { text-align: center; color: var(--muted); padding: 35px 24px; }
        .lp .pagination { display: flex; flex-wrap: wrap; gap: 4px; list-style: none; padding: 0; margin: 14px 0 0; justify-content: center; }
        .lp .pagination li a, .lp .pagination li span { display: block; padding: 6px 12px; border: 1px solid var(--line); border-radius: 6px; color: var(--ink); text-decoration: none; background: #fff; }
        .lp .pagination li.active span { background: var(--brand); border-color: var(--brand); color: #fff; }
        .lp .pagination li.disabled span { color: #b8c2d1; }
        .lp .print-header, .lp .signature { display: none; }
        @media (max-width: 600px) {
            .lp .page-header { align-items: flex-start; }
            .lp h1 { font-size: 22px; }
            .lp .head { align-items: flex-start; }
            .lp .btns { width: 100%; }
            .lp .btns .btn { flex: 1; }
            .lp .card { padding: 14px; }
        }
        @media print {
            @page { size: A4 landscape; margin: 14mm; }
            .sidebar, .header { display: none !important; }
            .main { margin-left: 0 !important; }
            .content { padding: 0 !important; }
            body { background: #fff !important; color: #111827 !important; }
            .lp { color: #111827; font-size: 10pt; }
            .lp .no-print, .lp .page-header, .lp .head { display: none !important; }
            .lp .print-header { display: block; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 2px solid #1f2937; }
            .lp .print-brand { font-size: 19pt; font-weight: 700; letter-spacing: 1px; }
            .lp .print-title { margin-top: 8px; font-size: 14pt; font-weight: 700; text-transform: uppercase; }
            .lp .print-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 20px; margin-top: 8px; font-size: 9pt; }
            .lp .card, .lp .stat { break-inside: avoid; box-shadow: none; border: 1px solid #9ca3af; }
            .lp .cards { grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .lp .stat { border-left: 3px solid #374151; padding: 10px 12px; }
            .lp .stat .v { font-size: 16pt; color: #111827; }
            .lp .scroll { overflow: visible; }
            .lp table { width: 100%; }
            .lp th, .lp td { padding: 6px 7px; border-color: #d1d5db; font-size: 8pt; }
            .lp th { background: #e5e7eb !important; color: #111827; print-color-adjust: exact; }
            .lp thead { display: table-header-group; }
            .lp tr { break-inside: avoid; }
            .lp .pagination { display: none; }
            .lp .signature { display: flex; justify-content: flex-end; margin-top: 28px; break-inside: avoid; }
            .lp .signature-block { width: 250px; text-align: center; }
            .lp .signature-space { height: 65px; }
        }
    </style>
@endpush

@section('content')
<div class="lp">

    @if (!empty($isPrintView))
        <div class="print-header">
            <div class="print-brand">NEXPOOL</div>
            <div>Laporan Administrasi Penjualan Tiket</div>
            <div class="print-title">Laporan Penjualan Tiket Bulanan</div>
            <div class="print-meta">
                <div><strong>Kolam:</strong> {{ $poolLabel }}</div>
                <div><strong>Periode:</strong> {{ $periodeLabel }}</div>
                <div><strong>Nomor laporan:</strong> LPT/{{ session('admin_pool_id') }}/{{ $f['bulan'] }}</div>
                <div><strong>Tanggal cetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    @endif

    <div class="page-header">
        <div class="page-title">
            <div class="page-icon"><i class="fa-solid fa-chart-column"></i></div>
            <div>
                <h1>Laporan Penjualan Tiket</h1>
                <p>Ringkasan pendapatan dan jumlah tiket pengunjung NEXPOOL.</p>
            </div>
        </div>
    </div>

    <div class="head">
        <div>
            @if (empty($error))
                <div class="sub">NexPool · Periode: <strong>{{ $periodeLabel }}</strong> · Kolam: <strong>{{ $poolLabel }}</strong></div>
            @else
                <div class="sub">NexPool</div>
            @endif
        </div>
        <div class="btns no-print">
            <a class="btn" href="{{ route('admin.laporan.penjualan') }}">← Kembali</a>
            @if (empty($error))
                <a class="btn" href="{{ route('admin.laporan.penjualan', $printQuery) }}" target="_blank" rel="noopener">Cetak / Simpan PDF</a>
                <a class="btn primary" href="{{ route('admin.laporan.penjualan.export', $exportQuery) }}">Unduh Excel (CSV)</a>
            @endif
        </div>
    </div>

@if (!empty($error))

    <div class="error"><strong>Laporan belum bisa dibuat.</strong><br>{{ $error }}</div>

@else

    {{-- ====== FILTER ====== --}}
    <div class="card no-print">
        <form class="filters" method="GET" action="{{ route('admin.laporan.penjualan') }}">
            <div>
                <label for="bulan">Pilih bulan laporan</label>
                <input type="month" id="bulan" name="bulan" value="{{ $f['bulan'] }}" required>
            </div>
            <div>
                <button class="btn primary" type="submit">Tampilkan Laporan</button>
            </div>
        </form>
    </div>

    @unless ($s['total'])
        <div class="note">Kolom total harga tidak ditemukan di tabel <strong>{{ $s['table'] }}</strong>, jadi nilai pendapatan tampil Rp 0. Tambahkan nama kolom total-mu di <code>detectSchema()</code>.</div>
    @endunless
    @if ($s['status'] && !$hasPaid)
        <div class="note">Belum ada reservasi berstatus selesai atau terkonfirmasi pada periode ini. Reservasi menunggu atau dibatalkan tidak dihitung sebagai penjualan tiket.</div>
    @endif

    {{-- ====== RINGKASAN ====== --}}
    <div class="cards">
        <div class="stat">
            <div class="k">Pendapatan Penjualan Tiket</div>
            <div class="v">Rp {{ number_format($summary['omzet'], 0, ',', '.') }}</div>
            <div class="n">{{ $periodeLabel }}</div>
        </div>
        <div class="stat">
            <div class="k">Transaksi Tiket</div>
            <div class="v">{{ number_format($summary['trx'], 0, ',', '.') }}</div>
            <div class="n">Rata-rata Rp {{ number_format($summary['rata'], 0, ',', '.') }} / transaksi</div>
        </div>
        <div class="stat">
            <div class="k">Jumlah Tiket Terjual</div>
            <div class="v">{{ number_format($summary['dewasa'] + $summary['anak'], 0, ',', '.') }}</div>
            <div class="n">{{ number_format($summary['dewasa'], 0, ',', '.') }} tiket dewasa · {{ number_format($summary['anak'], 0, ',', '.') }} tiket anak</div>
        </div>
    </div>

    {{-- ====== PER KOLAM ====== --}}
    @if ($s['pool'])
        <div class="card">
            <h2>Rekap Penjualan Tiket per Kolam</h2>
            <div class="scroll">
                <table>
                    <thead>
                    <tr>
                        <th>Kolam</th>
                        <th class="num">Transaksi</th>
                        <th class="num">Tiket Terjual</th>
                        <th class="num">Pendapatan Tiket</th>
                        <th style="width:22%">Kontribusi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($perKolam as $k)
                        <tr>
                            <td><strong>{{ $k['nama'] }}</strong></td>
                            <td class="num">{{ number_format($k['trx'], 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($k['tiket'], 0, ',', '.') }}</td>
                            <td class="num">Rp {{ number_format($k['omzet'], 0, ',', '.') }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="bar" style="flex:1"><span style="width: {{ $k['persen'] }}%"></span></div>
                                    <span>{{ $k['persen'] }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ====== DETAIL TRANSAKSI ====== --}}
    @php
        $displayRows = $isPrintView ? $printRows : $rows;
        $rowCount = $isPrintView ? $printRows->count() : $rows->total();
    @endphp
    <div class="card">
        <h2>Detail Penjualan Tiket <span style="color:var(--muted);font-weight:400">({{ number_format($rowCount, 0, ',', '.') }} transaksi)</span></h2>
        <div class="scroll">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    @if ($s['pool']) <th>Kolam</th> @endif
                    <th class="num">Tiket Dewasa</th>
                    <th class="num">Tiket Anak</th>
                    <th class="num">Pendapatan Tiket</th>
                    @if ($s['status']) <th>Status</th> @endif
                </tr>
                </thead>
                <tbody>
                @forelse ($displayRows as $i => $r)
                    <tr>
                        <td>{{ $isPrintView ? $loop->iteration : $rows->firstItem() + $i }}</td>
                        <td>{{ $r['kode'] }}</td>
                        <td>{{ $r['nama'] }}@if ($r['hp'] !== '-')<div style="color:var(--muted);font-size:12px">{{ $r['hp'] }}</div>@endif</td>
                        <td>{{ $r['tanggal'] }}</td>
                        @if ($s['pool']) <td>{{ $r['kolam'] }}</td> @endif
                        <td class="num">{{ $r['dewasa'] }}</td>
                        <td class="num">{{ $r['anak'] }}</td>
                        <td class="num">Rp {{ number_format($r['total'], 0, ',', '.') }}</td>
                        @if ($s['status']) <td><span class="badge {{ $r['status_class'] }}">{{ $r['status'] }}</span></td> @endif
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty">Tidak ada penjualan tiket pada bulan ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @unless ($isPrintView)
            {{ $rows->links() }}
        @endunless
    </div>

    @if (!empty($isPrintView))
        <div class="signature">
            <div class="signature-block">
                <div>{{ $poolLabel }}, {{ now()->format('d/m/Y') }}</div>
                <div>Penanggung Jawab</div>
                <div class="signature-space"></div>
                <strong>{{ session('admin_nama', session('admin_username', 'Administrator')) }}</strong>
            </div>
        </div>
    @endif

@endif


</div>
@endsection

@if ($isPrintView)
    @push('scripts')
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endpush
@endif