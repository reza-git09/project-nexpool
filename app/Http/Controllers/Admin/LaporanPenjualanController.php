<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Laporan Penjualan Tiket NexPool (admin).
 *
 * Controller ini MENDETEKSI sendiri nama tabel & kolom reservasi kamu
 * (lihat detectSchema). Kalau nama kolammu beda dari daftar kandidat,
 * tinggal tambahkan nama kolommu ke daftar di detectSchema().
 */
class LaporanPenjualanController extends Controller
{
    /** Kata kunci status yang dianggap "sudah dibayar / berhasil". */
    private const PAID_WORDS = ['lunas', 'paid', 'settle', 'success', 'berhasil', 'selesai', 'confirm', 'konfirmasi', 'dibayar', 'terbayar', 'capture'];

    /** Kata kunci status yang dianggap "menunggu". */
    private const PENDING_WORDS = ['pending', 'menunggu', 'belum', 'unpaid', 'proses'];

    // ------------------------------------------------------------------
    // Halaman laporan
    // ------------------------------------------------------------------
    public function index(Request $request)
    {
        $s = $this->detectSchema();
        if (is_string($s)) {
            return view('admin.laporan-penjualan', ['error' => $s]);
        }
        $this->ensurePoolScope($s);

        $statusValues = $this->statusValues($s);
        $paidValues = $this->paidValues($statusValues);
        $f = $this->filters($request, $s);
        $base = $this->filtered($s, $f, $paidValues);
        $g = DB::getQueryGrammar();

        $sumTotal = $s['total'] ? 'COALESCE(SUM('.$g->wrap($s['total']).'),0)' : '0';
        $sumDewasa = $s['dewasa'] ? 'COALESCE(SUM('.$g->wrap($s['dewasa']).'),0)' : '0';
        $sumAnak = $s['anak'] ? 'COALESCE(SUM('.$g->wrap($s['anak']).'),0)' : '0';

        // ---- Ringkasan
        $agg = (clone $base)->selectRaw("COUNT(*) as trx, $sumTotal as omzet, $sumDewasa as dewasa, $sumAnak as anak")->first();
        $trx = (int) ($agg->trx ?? 0);
        $omzet = (float) ($agg->omzet ?? 0);
        $summary = [
            'trx' => $trx,
            'omzet' => $omzet,
            'dewasa' => (int) ($agg->dewasa ?? 0),
            'anak' => (int) ($agg->anak ?? 0),
            'rata' => $trx > 0 ? $omzet / $trx : 0,
        ];

        // ---- Rekap per kolam
        $perKolam = [];
        if ($s['pool']) {
            $names = $this->kolamNames($s);
            $rows = (clone $base)
                ->selectRaw($g->wrap($s['pool'])." as kid, COUNT(*) as trx, $sumTotal as omzet, $sumDewasa as dewasa, $sumAnak as anak")
                ->groupBy($s['pool'])
                ->orderByDesc('omzet')
                ->get();
            foreach ($rows as $r) {
                $label = $names[$r->kid] ?? ($r->kid === null || $r->kid === '' ? '(tanpa kolam)' : (string) $r->kid);
                $perKolam[] = [
                    'nama' => $label,
                    'trx' => (int) $r->trx,
                    'tiket' => (int) $r->dewasa + (int) $r->anak,
                    'omzet' => (float) $r->omzet,
                    'persen' => $omzet > 0 ? round(((float) $r->omzet / $omzet) * 100, 1) : 0,
                ];
            }
        }

        // ---- Detail transaksi bulanan
        $names = $s['pool'] ? $this->kolamNames($s) : [];
        $rowsQuery = (clone $base)
            ->orderByDesc($s['tanggal'])
            ->when($s['id'], fn ($q) => $q->orderByDesc('id'));
        $isPrintView = $request->boolean('cetak');
        $rows = (clone $rowsQuery)
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($row) => $this->mapRow($row, $s, $names));
        $printRows = $isPrintView
            ? $rowsQuery->get()->map(fn ($row) => $this->mapRow($row, $s, $names))
            : collect();

        $poolId = (string) session('admin_pool_id', '');
        $poolOptions = $s['pool'] ? $names : [];
        if ($poolId !== '' && $poolOptions !== []) {
            $poolOptions = array_intersect_key($poolOptions, [$poolId => true]);
        }
        $poolLabel = $poolOptions[$poolId] ?? session('admin_pool_nama', $poolId);

        return view('admin.laporan-penjualan', [
            'error' => null,
            's' => $s,
            'f' => $f,
            'summary' => $summary,
            'perKolam' => $perKolam,
            'rows' => $rows,
            'printRows' => $printRows,
            'isPrintView' => $isPrintView,
            'poolLabel' => $poolLabel,
            'hasPaid' => count($paidValues) > 0,
            'periodeLabel' => $this->periodeLabel($f),
            'exportQuery' => ['bulan' => $f['bulan']],
            'printQuery' => ['bulan' => $f['bulan'], 'cetak' => 1],
        ]);
    }

    // ------------------------------------------------------------------
    // Export CSV (bisa dibuka di Excel)
    // ------------------------------------------------------------------
    public function export(Request $request)
    {
        $s = $this->detectSchema();
        if (is_string($s)) {
            abort(404, $s);
        }
        $this->ensurePoolScope($s);

        $statusValues = $this->statusValues($s);
        $paidValues = $this->paidValues($statusValues);
        $f = $this->filters($request, $s);
        $base = $this->filtered($s, $f, $paidValues);
        $names = $s['pool'] ? $this->kolamNames($s) : [];

        $query = (clone $base)
            ->orderByDesc($s['tanggal'])
            ->when($s['id'], fn ($q) => $q->orderByDesc('id'));

        $filename = 'laporan-penjualan-tiket-'.$f['bulan'].'.csv';

        return new StreamedResponse(function () use ($query, $s, $names, $f) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel baca UTF-8
            $period = $this->periodeLabel($f);
            fputcsv($out, ['LAPORAN PENJUALAN TIKET NEXPOOL'], ';');
            fputcsv($out, ['Periode', $period], ';');
            fputcsv($out, [], ';');
            fputcsv($out, ['No', 'Kode Reservasi', 'Nama Pengunjung', 'No. HP', 'Tanggal Kunjungan', 'Kolam', 'Tiket Dewasa', 'Tiket Anak', 'Jumlah Tiket', 'Pendapatan Tiket (Rp)', 'Status'], ';');

            $no = 0;
            $totalAll = 0;
            $totalDewasa = 0;
            $totalAnak = 0;
            foreach ($query->cursor() as $row) {
                $m = $this->mapRow($row, $s, $names);
                $no++;
                $totalAll += $m['total'];
                $totalDewasa += $m['dewasa'];
                $totalAnak += $m['anak'];
                fputcsv($out, [
                    $no,
                    $this->csvSafe($m['kode']),
                    $this->csvSafe($m['nama']),
                    $this->csvSafe("'".$m['hp']),
                    $m['tanggal'],
                    $this->csvSafe($m['kolam']),
                    $m['dewasa'],
                    $m['anak'],
                    $m['dewasa'] + $m['anak'],
                    (int) round($m['total']),
                    $this->csvSafe($m['status']),
                ], ';');
            }
            fputcsv($out, ['', '', 'TOTAL', '', '', '', $totalDewasa, $totalAnak, $totalDewasa + $totalAnak, (int) round($totalAll), ''], ';');
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    // ------------------------------------------------------------------
    // Deteksi tabel & kolom
    // ------------------------------------------------------------------
    /** @return array|string  array skema, atau string pesan error */
    private function detectSchema()
    {
        $table = $this->firstTable(['reservasi', 'reservasis', 'reservations', 'reservation']);
        if (! $table) {
            return 'Tabel reservasi tidak ditemukan. Sudah kucari: reservasi, reservasis, reservations. '
                .'Kalau nama tabelmu beda, tambahkan di detectSchema() pada LaporanPenjualanController.';
        }

        $col = fn (array $c) => $this->firstColumn($table, $c);

        $s = [
            'table' => $table,
            'id' => Schema::hasColumn($table, 'id'),
            'total' => $col(['total_harga', 'total_bayar', 'grand_total', 'total_pembayaran', 'harga_total', 'jumlah_bayar', 'total']),
            'dewasa' => $col(['jumlah_dewasa', 'jml_dewasa', 'qty_dewasa', 'dewasa']),
            'anak' => $col(['jumlah_anak', 'jml_anak', 'qty_anak', 'anak']),
            'kunjungan' => $col(['tanggal_kunjungan', 'tgl_kunjungan', 'tanggal_reservasi', 'visit_date', 'tanggal']),
            'dibuat' => Schema::hasColumn($table, 'created_at') ? 'created_at' : null,
            'status' => $col(['status_pembayaran', 'payment_status', 'status_reservasi', 'status']),
            'pool' => $col(['pool_id', 'kolam_id', 'id_kolam', 'kolam_renang_id', 'nama_kolam', 'kolam']),
            'kode' => $col(['kode_reservasi', 'kode_booking', 'no_reservasi', 'order_id', 'kode']),
            'nama' => $col(['nama_pengunjung', 'nama_pemesan', 'nama_lengkap', 'nama', 'name']),
            'hp' => $col(['no_hp', 'no_telepon', 'no_wa', 'telepon', 'phone']),
        ];

        $s['tanggal'] = $s['kunjungan'] ?: $s['dibuat'];
        if (! $s['tanggal']) {
            return "Tabel '$table' tidak punya kolom tanggal (tanggal_kunjungan / created_at), jadi laporan per periode tidak bisa dibuat.";
        }

        // Tabel kolam (untuk nama kolam)
        $s['kolam_table'] = $this->firstTable(['kolam_renang', 'kolam_renangs', 'kolams', 'kolam', 'pools', 'pool']);
        $s['kolam_name'] = null;
        if ($s['kolam_table'] && Schema::hasColumn($s['kolam_table'], 'id')) {
            $s['kolam_name'] = $this->firstColumn($s['kolam_table'], ['nama_kolam', 'nama', 'name', 'nama_wisata', 'judul']);
            $s['kolam_key'] = Schema::hasColumn($s['kolam_table'], 'pool_id') ? 'pool_id' : 'id';
        } else {
            $s['kolam_key'] = null;
        }

        return $s;
    }

    private function firstTable(array $candidates): ?string
    {
        foreach ($candidates as $t) {
            if (Schema::hasTable($t)) {
                return $t;
            }
        }

        return null;
    }

    private function firstColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }

        return null;
    }

    // ------------------------------------------------------------------
    // Status & filter
    // ------------------------------------------------------------------
    private function statusValues(array $s): array
    {
        if (! $s['status']) {
            return [];
        }

        return DB::table($s['table'])
            ->where($s['pool'], session('admin_pool_id'))
            ->whereNotNull($s['status'])
            ->distinct()
            ->orderBy($s['status'])
            ->pluck($s['status'])
            ->map(fn ($v) => (string) $v)
            ->all();
    }

    private function paidValues(array $statusValues): array
    {
        return array_values(array_filter($statusValues, fn ($v) => $this->matchesAny($v, self::PAID_WORDS)));
    }

    private function matchesAny(string $value, array $words): bool
    {
        $v = mb_strtolower($value);
        foreach ($words as $w) {
            if (str_contains($v, $w)) {
                return true;
            }
        }

        return false;
    }

    private function filters(Request $r, array $s): array
    {
        $validated = $r->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);
        $bulan = $validated['bulan'] ?? now()->format('Y-m');
        $month = Carbon::createFromFormat('!Y-m', $bulan);

        return [
            'bulan' => $bulan,
            'dari' => $month->copy()->startOfMonth()->toDateString(),
            'sampai' => $month->copy()->endOfMonth()->toDateString(),
            'sampaiEksklusif' => $month->copy()->addMonthNoOverflow()->startOfMonth()->toDateString(),
            'status' => 'terbayar',
            'kolam' => (string) session('admin_pool_id'),
        ];
    }

    private function filtered(array $s, array $f, array $paidValues)
    {
        $q = DB::table($s['table']);

        if ($f['dari']) {
            $q->where($s['tanggal'], '>=', $f['dari'].' 00:00:00');
        }
        if ($f['sampaiEksklusif']) {
            $q->where($s['tanggal'], '<', $f['sampaiEksklusif']);
        }

        if ($s['status']) {
            if ($f['status'] === 'terbayar') {
                if (count($paidValues)) {
                    $q->whereIn($s['status'], $paidValues);
                } else {
                    $q->whereRaw('1 = 0');
                }
            } elseif ($f['status'] !== 'semua' && $f['status'] !== '') {
                $q->where($s['status'], $f['status']);
            }
        }

        if ($f['kolam'] !== '' && $s['pool']) {
            $q->where($s['pool'], $f['kolam']);
        }

        return $q;
    }

    // ------------------------------------------------------------------
    // Helper data
    // ------------------------------------------------------------------
    private function kolamNames(array $s): array
    {
        if (! $s['kolam_table'] || ! $s['kolam_name'] || ! $s['kolam_key']) {
            return [];
        }
        // Kalau kolom kolam di reservasi berupa nama langsung (bukan id), tidak perlu mapping.
        if (in_array($s['pool'], ['nama_kolam', 'kolam'], true)) {
            return [];
        }

        return DB::table($s['kolam_table'])->pluck($s['kolam_name'], $s['kolam_key'])->all();
    }

    private function ensurePoolScope(array $s): void
    {
        if (! $s['pool']) {
            abort(403, 'Data transaksi tidak memiliki kolom pool untuk membatasi laporan admin.');
        }

        if (! session()->has('admin_pool_id') || session('admin_pool_id') === '') {
            abort(403, 'Pool admin tidak ditemukan di sesi.');
        }
    }

    private function mapRow($row, array $s, array $names): array
    {
        $get = fn ($key) => $s[$key] ? ($row->{$s[$key]} ?? null) : null;

        $tgl = $get('tanggal');
        $tglText = '-';
        if ($tgl) {
            try {
                $c = Carbon::parse($tgl);
                $tglText = $s['tanggal'] === 'created_at' ? $c->format('d/m/Y H:i') : $c->format('d/m/Y');
            } catch (\Throwable $e) {
                $tglText = (string) $tgl;
            }
        }

        $pool = $get('pool');
        $kolam = '-';
        if ($pool !== null && $pool !== '') {
            $kolam = $names[$pool] ?? (string) $pool;
        }

        $status = $get('status');

        return [
            'kode' => (string) ($get('kode') ?? ($s['id'] ? '#'.($row->id ?? '') : '-')),
            'nama' => (string) ($get('nama') ?? '-'),
            'hp' => (string) ($get('hp') ?? '-'),
            'tanggal' => $tglText,
            'kolam' => $kolam,
            'dewasa' => (int) ($get('dewasa') ?? 0),
            'anak' => (int) ($get('anak') ?? 0),
            'total' => (float) ($get('total') ?? 0),
            'status' => $status === null ? '-' : (string) $status,
            'status_class' => $this->statusClass($status === null ? '' : (string) $status),
        ];
    }

    private function statusClass(string $status): string
    {
        if ($this->matchesAny($status, self::PAID_WORDS)) {
            return 'ok';
        }
        if ($this->matchesAny($status, self::PENDING_WORDS)) {
            return 'wait';
        }
        if ($this->matchesAny($status, ['batal', 'cancel', 'expire', 'gagal', 'deny', 'failed'])) {
            return 'bad';
        }

        return 'neutral';
    }

    private function csvSafe(string $v): string
    {
        // Cegah formula injection di Excel (=, +, -, @)
        return $v !== '' && in_array($v[0], ['=', '+', '-', '@'], true) ? "'".$v : $v;
    }

    private function periodeLabel(array $f): string
    {
        return Carbon::createFromFormat('!Y-m', $f['bulan'])->locale('id')->translatedFormat('F Y');
    }
}
