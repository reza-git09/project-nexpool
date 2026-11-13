@extends('layout.admin')

@section('title', 'Laporan Penjualan Tiket - NEXPOOL')
@section('header-title', 'Laporan Penjualan Tiket')
@section('header-subtitle', 'Kelola dan pantau penjualan tiket')

@push('styles')
    <style>
        .lp { min-height: calc(100vh - 100px); padding: 1px 18px; color: #111; background: #eef0f3; font: 10pt/1.35 Arial, sans-serif; }
        .lp *, .lp *::before, .lp *::after { box-sizing: border-box; }
        .lp .page-header { display: flex; justify-content: space-between; margin-bottom: 18px; }
        .lp .page-title { display: flex; align-items: center; gap: 12px; }
        .lp .page-icon { width: 42px; height: 42px; display: grid; place-items: center; color: #333; background: #e5e7eb; border-radius: 8px; }
        .lp h1 { margin: 0 0 4px; font-size: 22px; }
        .lp .page-title p, .lp .sub { margin: 0; color: #667085; font-size: 13px; }
        .lp .head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .lp .btns { display: flex; flex-wrap: wrap; gap: 8px; }
        .lp .btn { display: inline-flex; align-items: center; justify-content: center; padding: 9px 14px; border: 1px solid #cbd5e1; border-radius: 6px; color: #1f2937; background: #fff; font: inherit; font-weight: 600; text-decoration: none; cursor: pointer; }
        .lp .btn.primary { border-color: #1d4ed8; color: #fff; background: #1d4ed8; }
        .lp .btn.primary:hover { border-color: #1e40af; background: #1e40af; }
        .lp .card, .lp .cards { margin-bottom: 14px; }
        .lp .card { padding: 16px; border: 1px solid #d5d9df; border-radius: 10px; background: #fff; }
        .lp .card h2 { margin: 0 0 10px; color: #1f2937; font-size: 15px; }
        .lp form.filters { display: flex; flex-wrap: wrap; align-items: end; gap: 12px; }
        .lp label { display: block; margin-bottom: 4px; color: #475569; font-size: 12px; }
        .lp input { width: min(100%, 260px); min-height: 40px; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; color: #172033; background: #fff; font: inherit; }
        .lp .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 12px; }
        .lp .stat { padding: 14px 16px; border: 1px solid #d5d9df; border-left: 4px solid #6b7280; border-radius: 8px; background: #fff; }
        .lp .stat .k { color: #667085; font-size: 11px; text-transform: uppercase; }
        .lp .stat .v { margin-top: 3px; color: #111; font-size: 22px; font-weight: 700; }
        .lp .stat .n { color: #667085; font-size: 11px; }
        .lp .note, .lp .error { margin-bottom: 12px; padding: 12px 15px; border-radius: 6px; }
        .lp .note { color: #854d0e; background: #fefce8; }
        .lp .error { color: #991b1b; background: #fef2f2; }
        .lp table { width: 100%; border-collapse: collapse; }
        .lp th, .lp td { padding: 8px 10px; border-bottom: 1px solid #d5d9df; text-align: left; vertical-align: top; }
        .lp th { color: #475569; background: #f3f4f6; font-size: 10px; text-transform: uppercase; white-space: nowrap; }
        .lp td { color: #334155; font-size: 11px; }
        .lp td.num, .lp th.num { text-align: right; white-space: nowrap; }
        .lp .scroll { overflow-x: auto; }
        .lp .badge { display: inline-block; padding: 3px 7px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .lp .badge.ok { color: #166534; background: #dcfce7; }
        .lp .badge.wait { color: #854d0e; background: #fef3c7; }
        .lp .badge.bad { color: #991b1b; background: #fee2e2; }
        .lp .badge.neutral { color: #475569; background: #e5e7eb; }
        .lp .bar { height: 7px; min-width: 70px; overflow: hidden; border-radius: 99px; background: #e5e7eb; }
        .lp .bar > span { display: block; height: 100%; background: #6b7280; }
        .lp .empty { padding: 20px; color: #667085; text-align: center; }
        .lp .pagination { display: flex; flex-wrap: wrap; justify-content: center; gap: 4px; margin: 14px 0 0; padding: 0; list-style: none; }
        .lp .pagination li a, .lp .pagination li span { display: block; padding: 6px 12px; border: 1px solid #d5d9df; border-radius: 5px; color: #111; background: #fff; text-decoration: none; }
        .lp .pagination li.active span { color: #fff; background: #333; }
        .lp .pagination li.disabled span { color: #9ca3af; }
        .lp .paper { width: min(100%, 210mm); min-height: 297mm; margin: 18px auto; padding: 18mm 16mm 20mm; color: #111; background: #fff; box-shadow: 0 2px 16px rgba(15, 23, 42, .16); }
        .lp .print-header { padding-bottom: 7px; border-bottom: 3px double #111; }
        .lp .print-brand { font-size: 18pt; font-weight: 700; letter-spacing: 1px; }
        .lp .print-subtitle { font-size: 9pt; }
        .lp .print-document-meta { float: right; text-align: right; font-size: 8pt; }
        .lp .report-title { margin: 10px 0 2px; text-align: center; font-size: 13pt; font-weight: 700; text-decoration: underline; }
        .lp .report-number { margin: 0 0 10px; text-align: center; font-size: 9pt; }
        .lp .print-meta { margin-bottom: 10px; }
        .lp .print-meta th, .lp .print-meta td { padding: 2px 4px; border: 0; background: transparent; font-size: 9pt; text-transform: none; }
        .lp .print-meta th { width: 105px; font-weight: 400; white-space: nowrap; }
        .lp .print-meta td { width: 40%; }
        .lp .report-section { margin-top: 11px; }
        .lp .report-section h2 { margin: 0 0 5px; padding-bottom: 3px; border-bottom: 1px solid #111; color: #111; font-size: 10pt; }
        .lp .report-table { border: 1px solid #111; font-size: 9pt; }
        .lp .report-table th, .lp .report-table td { padding: 4px 5px; border: 1px solid #111; color: #111; font-size: 9pt; }
        .lp .report-table th { background: #eee; text-align: center; white-space: normal; }
        .lp .report-table tfoot td { font-weight: 700; background: #f3f3f3; }
        .lp .report-total-label { text-align: right !important; }
        .lp .detail-table { table-layout: fixed; }
        .lp .detail-table th:nth-child(1) { width: 5%; }
        .lp .detail-table th:nth-child(2) { width: 18%; }
        .lp .detail-table th:nth-child(3) { width: 20%; }
        .lp .detail-table th:nth-child(4) { width: 12%; }
        .lp .detail-table th:nth-child(5), .lp .detail-table th:nth-child(6) { width: 7%; }
        .lp .detail-table th:nth-child(7) { width: 17%; }
        .lp .detail-table th:nth-child(8) { width: 14%; }
        .lp .detail-table .customer-phone { display: block; margin-top: 2px; color: #333; font-size: 8pt; }
        .lp .report-note { margin: 8px 0 0; font-size: 8pt; }
        .lp .signature { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 22px; break-inside: avoid; }
        .lp .signature-block { min-height: 105px; text-align: center; font-size: 9pt; }
        .lp .signature-role { min-height: 14px; }
        .lp .signature-space { height: 62px; }
        .lp .signature-name { display: inline-block; min-width: 150px; padding-top: 3px; border-top: 1px solid #111; font-weight: 700; }
        @media (max-width: 700px) {
            .lp .page-header { align-items: flex-start; }
            .lp h1 { font-size: 20px; }
            .lp .head { align-items: flex-start; }
            .lp .btns { width: 100%; }
            .lp .btns .btn { flex: 1; }
            .lp .card { padding: 12px; }
            .lp .paper { min-height: 0; padding: 22px 16px; }
        }
        @media print {
            @page {
                size: A4;
                margin: 18mm 16mm 20mm;
                @bottom-right {
                    content: "Halaman " counter(page) " dari " counter(pages);
                    font: 8pt Arial, sans-serif;
                }
            }
            html, body { width: auto !important; min-width: 0 !important; margin: 0 !important; padding: 0 !important; background: #fff !important; color: #111 !important; }
            .sidebar, .header, nav, .no-print { display: none !important; }
            .main, .content { width: auto !important; min-width: 0 !important; margin: 0 !important; padding: 0 !important; background: #fff !important; }
            .lp { min-height: 0 !important; padding: 0 !important; color: #111 !important; background: #fff !important; font: 10pt/1.25 Arial, sans-serif !important; }
            .lp .page-header, .lp .head, .lp .pagination { display: none !important; }
            .lp .paper { width: auto !important; min-height: 0 !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
            .lp .print-header { display: block !important; }
            .lp .card, .lp .stat, .lp .cards { margin: 0 !important; padding: 0 !important; border: 0 !important; border-radius: 0 !important; box-shadow: none !important; background: transparent !important; }
            .lp .cards { display: block !important; }
            .lp .scroll { overflow: visible !important; }
            .lp .report-section { margin-top: 9px; }
            .lp .report-section h2 { break-after: avoid; }
            .lp .print-meta { break-inside: avoid; }
            .lp table { width: 100% !important; }
            .lp thead { display: table-header-group; }
            .lp tfoot { display: table-row-group; }
            .lp tr { break-inside: avoid; page-break-inside: avoid; }
            .lp .report-table { border-collapse: collapse !important; }
            .lp .report-table th, .lp .report-table td { border: 1px solid #111 !important; font-size: 9pt !important; }
            .lp .report-table th { background: #eee !important; color: #111 !important; print-color-adjust: exact; }
            .lp .summary-table th { width: 58%; text-align: left; text-transform: none; }
            .lp .summary-table td { text-align: right; }
            .lp .no-print, .lp .badge, .lp .bar { display: none !important; }
            .lp .screen-status { display: none !important; }
            .lp .print-status { display: inline !important; }
            .lp .signature { margin-top: 16px; break-inside: avoid; page-break-inside: avoid; }
            .lp .signature-space { height: 55px; }
        }
    </style>
@endpush

@section('content')
<div class="lp">
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
                <a class="btn primary" href="{{ route('admin.laporan.penjualan.export', $exportQuery) }}">Unduh Excel</a>
            @endif
        </div>
    </div>

@if (!empty($error))

    <div class="error"><strong>Laporan belum bisa dibuat.</strong><br>{{ $error }}</div>

@else

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
        <div class="note no-print">Kolom total harga tidak ditemukan di tabel <strong>{{ $s['table'] }}</strong>, jadi nilai pendapatan tampil Rp 0. Tambahkan nama kolom total-mu di <code>detectSchema()</code>.</div>
    @endunless
    @if ($s['status'] && !$hasPaid)
        <div class="note no-print">Belum ada reservasi berstatus selesai atau terkonfirmasi pada periode ini. Reservasi menunggu atau dibatalkan tidak dihitung sebagai penjualan tiket.</div>
    @endif

    @php
        $displayRows = $isPrintView ? $printRows : $rows;
        $rowCount = $isPrintView ? $printRows->count() : $rows->total();
        $detailTotalDewasa = $displayRows->sum('dewasa');
        $detailTotalAnak = $displayRows->sum('anak');
        $detailTotalPendapatan = $displayRows->sum('total');
        $kolamTotalTransaksi = collect($perKolam)->sum('trx');
        $kolamTotalTiket = collect($perKolam)->sum('tiket');
        $kolamTotalPendapatan = collect($perKolam)->sum('omzet');
    @endphp

    <div class="paper">
        <header class="print-header">
            <div class="print-document-meta">
                Dokumen internal<br>
                Dicetak: {{ now()->format('d/m/Y H:i') }} WIB
            </div>
            <div class="print-brand">NEXPOOL</div>
            <div class="print-subtitle">Laporan Administrasi Penjualan Tiket</div>
        </header>

        <h1 class="report-title">LAPORAN PENJUALAN TIKET BULANAN</h1>
        <p class="report-number">Nomor: LPT/{{ session('admin_pool_id') }}/{{ $f['bulan'] }}</p>

        <table class="print-meta">
            <tbody>
                <tr><th>Nama Kolam</th><td>{{ $poolLabel }}</td></tr>
                <tr><th>ID Kolam</th><td>{{ session('admin_pool_id') }}</td></tr>
                <tr><th>Periode</th><td>{{ $periodeLabel }}</td></tr>
                <tr><th>Tanggal Cetak</th><td>{{ now()->format('d/m/Y H:i') }} WIB</td></tr>
            </tbody>
        </table>

        <section class="report-section">
            <h2>A. Ringkasan Penjualan</h2>
            <table class="report-table summary-table">
                <tbody>
                    <tr>
                        <th>Total pendapatan penjualan tiket</th>
                        <td>Rp {{ number_format($summary['omzet'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah transaksi</th>
                        <td>{{ number_format($summary['trx'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah tiket terjual (dewasa / anak)</th>
                        <td>{{ number_format($summary['dewasa'] + $summary['anak'], 0, ',', '.') }} ({{ number_format($summary['dewasa'], 0, ',', '.') }} tiket dewasa / {{ number_format($summary['anak'], 0, ',', '.') }} tiket anak)</td>
                    </tr>
                    <tr>
                        <th>Rata-rata pendapatan per transaksi</th>
                        <td>Rp {{ number_format($summary['rata'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        @if ($s['pool'])
            <section class="report-section">
                <h2>B. Rekap Penjualan per Kolam</h2>
                <div class="scroll">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Kolam</th>
                                <th>Transaksi</th>
                                <th>Tiket Terjual</th>
                                <th>Pendapatan Tiket</th>
                                <th>Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($perKolam as $k)
                                <tr>
                                    <td>{{ $k['nama'] }}</td>
                                    <td class="num">{{ number_format($k['trx'], 0, ',', '.') }}</td>
                                    <td class="num">{{ number_format($k['tiket'], 0, ',', '.') }}</td>
                                    <td class="num">Rp {{ number_format($k['omzet'], 0, ',', '.') }}</td>
                                    <td class="num">{{ number_format($k['persen'], 1, ',', '.') }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td class="num">{{ number_format($kolamTotalTransaksi, 0, ',', '.') }}</td>
                                <td class="num">{{ number_format($kolamTotalTiket, 0, ',', '.') }}</td>
                                <td class="num">Rp {{ number_format($kolamTotalPendapatan, 0, ',', '.') }}</td>
                                <td class="num">{{ $kolamTotalPendapatan > 0 ? '100%' : '0%' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        @endif

        <section class="report-section">
            <h2>C. Rincian Transaksi ({{ number_format($rowCount, 0, ',', '.') }} transaksi)</h2>
            <div class="scroll">
                <table class="report-table detail-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Reservasi</th>
                            <th>Nama Pemesan</th>
                            <th>Tanggal</th>
                            <th>Dewasa</th>
                            <th>Anak</th>
                            <th>Pendapatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($displayRows as $i => $r)
                            <tr>
                                <td class="num">{{ $isPrintView ? $loop->iteration : $rows->firstItem() + $i }}</td>
                                <td>{{ $r['kode'] }}</td>
                                <td>
                                    {{ $r['nama'] }}
                                    @if ($r['hp'] !== '-')
                                        <span class="customer-phone">{{ $r['hp'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $r['tanggal'] }}</td>
                                <td class="num">{{ number_format($r['dewasa'], 0, ',', '.') }}</td>
                                <td class="num">{{ number_format($r['anak'], 0, ',', '.') }}</td>
                                <td class="num">Rp {{ number_format($r['total'], 0, ',', '.') }}</td>
                                <td>
                                    @if ($isPrintView)
                                        <span class="print-status">{{ $r['status'] === '-' ? '-' : 'Lunas' }}</span>
                                    @else
                                        <span class="screen-status badge {{ $r['status_class'] }}">{{ $r['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="empty">Tidak ada penjualan tiket pada bulan ini.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($isPrintView)
                        <tfoot>
                            <tr>
                                <td colspan="4" class="report-total-label">TOTAL</td>
                                <td class="num">{{ number_format($detailTotalDewasa, 0, ',', '.') }}</td>
                                <td class="num">{{ number_format($detailTotalAnak, 0, ',', '.') }}</td>
                                <td class="num">Rp {{ number_format($detailTotalPendapatan, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            @unless ($isPrintView)
                {{ $rows->links() }}
            @endunless
        </section>

        <p class="report-note">Catatan: Laporan ini hanya memuat transaksi dengan status pembayaran lunas pada periode yang tertera.</p>

        <div class="signature">
            <div class="signature-block">
                <div>Mengetahui,</div>
                <div class="signature-role">Pemilik / Penanggung Jawab</div>
                <div class="signature-space"></div>
                <span class="signature-name">{{ $poolLabel }}</span>
            </div>
            <div class="signature-block">
                <div>{{ now()->format('d F Y') }}</div>
                <div class="signature-role">Administrator</div>
                <div class="signature-space"></div>
                <span class="signature-name">{{ session('admin_nama', session('admin_username', 'Administrator')) }}</span>
            </div>
        </div>
    </div>

@endif


</div>
@endsection

@if (!empty($isPrintView))
    @push('scripts')
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endpush
@endif