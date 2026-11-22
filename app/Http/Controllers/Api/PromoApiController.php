<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PromoApiController extends Controller
{
    /**
     * GET /api/promo
     *
     * Daftar promo yang AKTIF dan MASIH BERLAKU (hari ini ada di antara
     * tanggal mulai dan tanggal selesai). Tanggal pemesanan dapat dipilih
     * melalui parameter tanggal_kunjungan.
     *
     * Opsional:
     *   ?pool_id=pool_id_01  -> promo kolam tersebut + promo umum (tanpa pool_id)
     *   ?tanggal_kunjungan=2026-11-21 -> cek periode untuk tanggal kunjungan
     *   ?semua=1             -> tampilkan semua promo tanpa saring status/tanggal
     *                           (berguna untuk mengecek data di browser)
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pool_id' => ['nullable', 'string', 'max:255'],
            'tanggal_kunjungan' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $table = $this->tableName();

        if (! $table) {
            return response()->json([
                'success' => false,
                'message' => 'Tabel promo tidak ditemukan.',
                'data' => [],
            ], 500);
        }

        $query = DB::table($table);

        // Saring per kolam (kalau tabel punya kolom pool_id)
        if (! empty($validated['pool_id']) && Schema::hasColumn($table, 'pool_id')) {
            $query->where(function ($w) use ($validated) {
                $w->where('pool_id', $validated['pool_id'])
                    ->orWhereNull('pool_id');
            });
        }

        $targetDate = Carbon::parse($validated['tanggal_kunjungan'] ?? today())
            ->startOfDay();
        $semua = $request->boolean('semua');
        $includeUpcoming = $request->boolean('include_upcoming');

        $data = $query->get()
             ->filter(function ($row) use ($targetDate, $semua, $includeUpcoming) {
                if ($semua) {
                    return true;
                }

                if (! $this->isActive($row)) {
                    return false;
                }

                $mulai = $this->parseDate($this->pick($row, [
                    'tanggal_mulai', 'tgl_mulai', 'mulai', 'start_date',
                ]));
                $selesai = $this->parseDate($this->pick($row, [
                    'tanggal_selesai', 'tgl_selesai', 'tanggal_berakhir', 'selesai', 'end_date',
                ]));

                 if (! $includeUpcoming && $mulai && $targetDate->lt($mulai)) {
                    return false; // belum mulai
                }
                if ($selesai && $targetDate->gt($selesai)) {
                    return false; // sudah berakhir
                }

                return true;
            })
            ->map(fn ($row) => $this->format($row))
            ->sortBy('tanggal_selesai')
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Data promo berhasil diambil.',
            'data' => $data,
        ]);
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Deteksi nama tabel promo ("promo" atau "promos"). */
    private function tableName(): ?string
    {
        foreach (['promo', 'promos'] as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    /** Ambil nilai dari kolom pertama yang ada isinya. */
    private function pick($row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($row->$key) && $row->$key !== '') {
                return $row->$key;
            }
        }

        return $default;
    }

    private function isActive($row): bool
    {
        $status = $this->pick($row, ['status', 'status_promo', 'is_active', 'aktif']);

        if ($status === null) {
            return true; // tidak ada kolom status -> anggap aktif
        }

        if (is_bool($status)) {
            return $status;
        }

        return in_array(
            strtolower(trim((string) $status)),
            ['1', 'true', 'aktif', 'active', 'ya', 'yes'],
            true
        );
    }

    private function parseDate($value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function dateString($value): ?string
    {
        $date = $this->parseDate($value);

        return $date ? $date->format('Y-m-d') : null;
    }

    /** Bentuk data yang rapi & konsisten untuk aplikasi mobile. */
    private function format($row): array
    {
        $jenis = (string) $this->pick($row, ['jenis_diskon', 'tipe_diskon', 'jenis', 'tipe'], '');
        $nilai = (float) $this->pick($row, ['nilai_diskon', 'diskon', 'nilai', 'besaran'], 0);

        return [
            'id' => $this->pick($row, ['id', 'promo_id']),
            'pool_id' => $this->pick($row, ['pool_id']),
            'nama' => $this->pick($row, ['nama_promo', 'nama', 'judul', 'title'], 'Promo'),
            'deskripsi' => $this->pick($row, ['deskripsi_promo', 'deskripsi', 'keterangan', 'description'], ''),
            'jenis_diskon' => $jenis,
            'nilai_diskon' => $nilai,
            'label_diskon' => $this->label($jenis, $nilai),
            'tanggal_mulai' => $this->dateString($this->pick($row, [
                'tanggal_mulai', 'tgl_mulai', 'mulai', 'start_date',
            ])),
            'tanggal_selesai' => $this->dateString($this->pick($row, [
                'tanggal_selesai', 'tgl_selesai', 'tanggal_berakhir', 'selesai', 'end_date',
            ])),
            'status' => $this->isActive($row),
        ];
    }

    /** "Hemat 30%" untuk persentase, "Potongan Rp10.000" untuk nominal. */
    private function label(string $jenis, float $nilai): string
    {
        $j = strtolower($jenis);

        $persen = str_contains($j, 'pers')
            || str_contains($j, 'percent')
            || str_contains($j, '%');

        $nominal = str_contains($j, 'nomin')
            || str_contains($j, 'fixed')
            || str_contains($j, 'rupiah')
            || str_contains($j, 'potongan');

        // Jenis tidak dikenali -> tebak dari nilainya
        if (! $persen && ! $nominal) {
            $persen = $nilai > 0 && $nilai <= 100;
        }

        if ($persen) {
            $angka = rtrim(rtrim(number_format($nilai, 2, '.', ''), '0'), '.');

            return 'Hemat '.$angka.'%';
        }

        return 'Potongan Rp'.number_format($nilai, 0, ',', '.');
    }
}
