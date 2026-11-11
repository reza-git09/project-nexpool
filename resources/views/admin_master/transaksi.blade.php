@extends('layout.admin_master')

@section('title', 'Transaksi Admin Master')
@section('header-title', 'Transaksi')
@section('header-subtitle', 'Monitoring pembayaran seluruh kolam renang NEXPOOL')

@section('content')

<style>
    .trx { --ink:#1b2a41; --muted:#7a8799; --line:#e8ecf3; --blue:#2459db; --blue-soft:#edf3ff;
           --ok:#12864a; --ok-bg:#e3f6ea; --wait:#a76a00; --wait-bg:#fff2d6; --off:#667085; --off-bg:#eef0f4;
           color:var(--ink); font-size:13px; padding:4px 0 28px; }
    .trx *, .trx *::before, .trx *::after { box-sizing:border-box; }

    /* judul */
    .trx-top { display:flex; justify-content:space-between; align-items:flex-end; gap:12px; margin-bottom:20px; }
    .trx-top h2 { margin:0 0 4px; font-size:22px; font-weight:750; }
    .trx-top p { margin:0; color:var(--muted); }
    .trx-tag { background:var(--blue-soft); color:var(--blue); padding:7px 14px; border-radius:999px;
               font-size:11px; font-weight:800; letter-spacing:.8px; white-space:nowrap; }

    /* kartu */
    .trx-card { background:#fff; border:1px solid var(--line); border-radius:14px; margin-bottom:18px; overflow:hidden; }
    .trx-card-head { padding:16px 20px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
    .trx-card-head h4 { margin:0; font-size:15px; font-weight:750; }
    .trx-card-head small { color:var(--muted); }

    /* statistik */
    .trx-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px; margin-bottom:18px; }
    .trx-stat { background:#fff; border:1px solid var(--line); border-radius:14px; padding:16px 18px; }
    .trx-stat span { display:flex; align-items:center; gap:7px; color:var(--muted); font-size:12px; margin-bottom:8px; }
    .trx-stat span i { width:8px; height:8px; border-radius:50%; background:var(--c, var(--blue)); display:inline-block; }
    .trx-stat b { font-size:22px; font-weight:800; display:block; }
    .trx-stat.hero { background:linear-gradient(135deg,#2459db,#3b82f6); border:none; color:#fff; }
    .trx-stat.hero span { color:rgba(255,255,255,.8); }
    .trx-stat.hero span i { background:#fff; }

    /* pendapatan per kolam */
    .trx-pools { padding:4px 20px 18px; display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px 32px; }
    .trx-pool-row { display:flex; justify-content:space-between; gap:10px; margin-bottom:7px; }
    .trx-pool-row small { color:var(--muted); display:block; margin-top:2px; }
    .trx-bar { height:7px; background:#eef1f6; border-radius:99px; overflow:hidden; }
    .trx-bar i { display:block; height:100%; background:var(--blue); border-radius:99px; }

    /* filter */
    .trx-filter { padding:0 20px 16px; display:grid; grid-template-columns:1.4fr 1.2fr 1fr 1fr auto; gap:10px; align-items:end; }
    .trx-filter label { display:block; font-size:11px; font-weight:700; color:var(--muted); margin-bottom:5px; }
    .trx-filter input, .trx-filter select { width:100%; height:40px; padding:0 11px; border:1px solid var(--line);
        border-radius:9px; background:#fff; color:var(--ink); font-size:13px; }
    .trx-filter input:focus, .trx-filter select:focus { outline:none; border-color:var(--blue); box-shadow:0 0 0 3px rgba(36,89,219,.12); }
    .trx-actions { display:flex; gap:8px; }
    .trx-btn { height:40px; padding:0 18px; border:none; border-radius:9px; font-size:13px; font-weight:700;
               cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; }
    .trx-btn.primary { background:var(--blue); color:#fff; }
    .trx-btn.ghost { background:#f1f4f9; color:#5b6678; }

    /* tabel */
    .trx-scroll { overflow-x:auto; border-top:1px solid var(--line); }
    .trx-table { width:100%; border-collapse:collapse; min-width:860px; }
    .trx-table th { text-align:left; padding:12px 20px; font-size:11px; font-weight:800; text-transform:uppercase;
                    letter-spacing:.5px; color:var(--muted); background:#f8fafc; white-space:nowrap; }
    .trx-table td { padding:14px 20px; border-top:1px solid #f0f2f7; white-space:nowrap; }
    .trx-table tbody tr:hover { background:#fafcff; }
    .trx-table .num { text-align:right; }
    .trx-code { color:var(--blue); font-weight:750; }
    .trx-sub { color:var(--muted); font-size:12px; }
    .trx-pill { display:inline-flex; align-items:center; gap:6px; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:750; }
    .trx-pill::before { content:""; width:6px; height:6px; border-radius:50%; background:currentColor; }
    .trx-pill.ok { color:var(--ok); background:var(--ok-bg); }
    .trx-pill.wait { color:var(--wait); background:var(--wait-bg); }
    .trx-pill.off { color:var(--off); background:var(--off-bg); }
    .trx-empty { text-align:center; padding:44px 20px !important; color:var(--muted); }

    /* halaman */
    .trx-foot { padding:14px 20px; display:flex; justify-content:space-between; align-items:center; gap:12px;
                flex-wrap:wrap; border-top:1px solid var(--line); color:var(--muted); }
    .trx-pager { display:flex; gap:6px; }
    .trx-pager a, .trx-pager span { padding:7px 14px; border-radius:8px; text-decoration:none; font-weight:700; }
    .trx-pager a { background:var(--blue-soft); color:var(--blue); }
    .trx-pager span { background:#f1f4f9; color:#a3adbd; }

    @media (max-width:1000px) {
        .trx-filter { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .trx-actions { grid-column:1 / -1; }
        .trx-pools { grid-template-columns:1fr; }
    }
    @media (max-width:600px) {
        .trx-top { flex-direction:column; align-items:flex-start; }
        .trx-stats { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .trx-stat.hero { grid-column:1 / -1; }
        .trx-filter { grid-template-columns:1fr; }
        .trx-actions .trx-btn { flex:1; }
    }
</style>

<div class="trx">

    {{-- JUDUL --}}
    <div class="trx-top">
        <div>
            <h2>Transaksi Pembayaran</h2>
            <p>Pantau pembayaran Midtrans dari seluruh kolam renang.</p>
        </div>
        <span class="trx-tag">ADMIN MASTER</span>
    </div>

    {{-- RINGKASAN --}}
    <div class="trx-stats">
        <div class="trx-stat hero">
            <span><i></i>Uang Berhasil Masuk</span>
            <b>Rp {{ number_format($statistik->total_pendapatan ?? 0, 0, ',', '.') }}</b>
        </div>
        <div class="trx-stat">
            <span><i></i>Total Transaksi</span>
            <b>{{ number_format($statistik->total_transaksi ?? 0, 0, ',', '.') }}</b>
        </div>
        <div class="trx-stat" style="--c:#12864a">
            <span><i></i>Berhasil</span>
            <b>{{ number_format($statistik->transaksi_berhasil ?? 0) }}</b>
        </div>
        <div class="trx-stat" style="--c:#e0a100">
            <span><i></i>Pending</span>
            <b>{{ number_format($statistik->transaksi_pending ?? 0) }}</b>
        </div>
        <div class="trx-stat" style="--c:#98a2b3">
            <span><i></i>Kedaluwarsa</span>
            <b>{{ number_format($statistik->transaksi_expired ?? 0) }}</b>
        </div>
    </div>

    {{-- PENDAPATAN PER KOLAM --}}
    <div class="trx-card">
        <div class="trx-card-head">
            <h4>Pendapatan per Kolam</h4>
            <small>Hanya transaksi yang berhasil dibayar</small>
        </div>

        @php
            $maxPendapatan = $pendapatanKolam->max('total_pendapatan') ?: 0;
        @endphp

        <div class="trx-pools">
            @forelse($kolam as $item)
                @php
                    $dataKolam = $pendapatanKolam->firstWhere('pool_id', $item->pool_id);
                    $pendapatan = $dataKolam->total_pendapatan ?? 0;
                    $jumlah = $dataKolam->jumlah_transaksi ?? 0;
                    $persentase = $maxPendapatan > 0 ? ($pendapatan / $maxPendapatan) * 100 : 0;
                @endphp
                <div>
                    <div class="trx-pool-row">
                        <div>
                            <strong>{{ $item->nama_kolam }}</strong>
                            <small>{{ $jumlah }} transaksi berhasil</small>
                        </div>
                        <strong>Rp {{ number_format($pendapatan, 0, ',', '.') }}</strong>
                    </div>
                    <div class="trx-bar"><i style="width: {{ $persentase }}%"></i></div>
                </div>
            @empty
                <p style="color:var(--muted)">Belum ada data kolam renang.</p>
            @endforelse
        </div>
    </div>

    {{-- FILTER + TABEL --}}
    <div class="trx-card">
        <div class="trx-card-head">
            <h4>Daftar Transaksi</h4>
            <small>{{ number_format($transaksi->total()) }} data</small>
        </div>

        <form method="GET" action="{{ route('admin.master.transaksi.index') }}" class="trx-filter">
            <div>
                <label>Kode reservasi</label>
                <input type="text" name="search" placeholder="Cari kode..." value="{{ request('search') }}">
            </div>
            <div>
                <label>Kolam renang</label>
                <select name="pool_id">
                    <option value="">Semua kolam</option>
                    @foreach($kolam as $item)
                        <option value="{{ $item->pool_id }}" @selected(request('pool_id') == $item->pool_id)>{{ $item->nama_kolam }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Status</label>
                <select name="status_pembayaran">
                    <option value="">Semua status</option>
                    <option value="paid" @selected(request('status_pembayaran') == 'paid')>Berhasil</option>
                    <option value="pending" @selected(request('status_pembayaran') == 'pending')>Pending</option>
                    <option value="expired" @selected(request('status_pembayaran') == 'expired')>Kedaluwarsa</option>
                </select>
            </div>
            <div>
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}">
            </div>
            <div class="trx-actions">
                <button type="submit" class="trx-btn primary">Cari</button>
                <a href="{{ route('admin.master.transaksi.index') }}" class="trx-btn ghost">Reset</a>
            </div>
        </form>

        <div class="trx-scroll">
            <table class="trx-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Kolam</th>
                        <th>Pengunjung</th>
                        <th class="num">Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $i => $item)
                        @php
                            $status = strtolower($item->status_pembayaran ?? '');
                            $waktu = $status == 'paid' ? $item->paid_at : $item->created_at;
                        @endphp
                        <tr>
                            <td class="trx-sub">{{ $transaksi->firstItem() + $i }}</td>
                            <td class="trx-code">{{ $item->kode_reservasi ?? '-' }}</td>
                            <td>{{ $item->kolamRenang->nama_kolam ?? '-' }}</td>
                            <td>{{ $item->nama_pengunjung ?? '-' }}</td>
                            <td class="num"><strong>Rp {{ number_format($item->total_harga ?? 0, 0, ',', '.') }}</strong></td>
                            <td class="trx-sub">{{ $item->payment_method ? strtoupper($item->payment_method) : '-' }}</td>
                            <td>
                                @if($status == 'paid')
                                    <span class="trx-pill ok">Berhasil</span>
                                @elseif($status == 'pending')
                                    <span class="trx-pill wait">Menunggu</span>
                                @elseif($status == 'expired')
                                    <span class="trx-pill off">Kedaluwarsa</span>
                                @else
                                    <span class="trx-pill off">{{ $item->status_pembayaran ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="trx-sub">{{ $waktu ? \Carbon\Carbon::parse($waktu)->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="trx-empty">Belum ada transaksi yang ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="trx-foot">
            <span>Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} transaksi</span>
            <div class="trx-pager">
                @if($transaksi->onFirstPage())
                    <span>‹ Sebelumnya</span>
                @else
                    <a href="{{ $transaksi->previousPageUrl() }}">‹ Sebelumnya</a>
                @endif

                @if($transaksi->hasMorePages())
                    <a href="{{ $transaksi->nextPageUrl() }}">Berikutnya ›</a>
                @else
                    <span>Berikutnya ›</span>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection