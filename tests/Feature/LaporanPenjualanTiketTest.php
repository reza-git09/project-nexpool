<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LaporanPenjualanTiketTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('reservasis', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_reservasi');
            $table->string('pool_id');
            $table->string('nama_pengunjung');
            $table->string('no_hp');
            $table->date('tanggal_kunjungan');
            $table->unsignedInteger('jumlah_dewasa');
            $table->unsignedInteger('jumlah_anak');
            $table->decimal('total_harga', 12, 2);
            $table->string('status_reservasi');
        });

        Schema::create('kolam_renang', function (Blueprint $table): void {
            $table->id();
            $table->string('pool_id');
            $table->string('nama_kolam');
        });

        DB::table('kolam_renang')->insert([
            ['pool_id' => 'pool_id_01', 'nama_kolam' => 'Tiara Park'],
            ['pool_id' => 'pool_id_02', 'nama_kolam' => 'Kolam Lain'],
        ]);
    }

    public function test_it_reports_confirmed_ticket_sales_for_the_current_pool_only(): void
    {
        $this->travelTo('2026-11-06 12:00:00');

        DB::table('reservasis')->insert([
            [
                'kode_reservasi' => 'TIKET-MILIK-ADMIN',
                'pool_id' => 'pool_id_01',
                'nama_pengunjung' => 'Pengunjung Admin',
                'no_hp' => '081234567890',
                'tanggal_kunjungan' => '2026-10-31',
                'jumlah_dewasa' => 2,
                'jumlah_anak' => 1,
                'total_harga' => 100000,
                'status_reservasi' => 'Dikonfirmasi',
            ],
            [
                'kode_reservasi' => 'TIKET-MENUNGGU',
                'pool_id' => 'pool_id_01',
                'nama_pengunjung' => 'Pengunjung Menunggu',
                'no_hp' => '081111111111',
                'tanggal_kunjungan' => '2026-10-31',
                'jumlah_dewasa' => 1,
                'jumlah_anak' => 0,
                'total_harga' => 50000,
                'status_reservasi' => 'Menunggu',
            ],
            [
                'kode_reservasi' => 'TIKET-KOLAM-LAIN',
                'pool_id' => 'pool_id_02',
                'nama_pengunjung' => 'Pengunjung Kolam Lain',
                'no_hp' => '082222222222',
                'tanggal_kunjungan' => '2026-10-31',
                'jumlah_dewasa' => 4,
                'jumlah_anak' => 2,
                'total_harga' => 200000,
                'status_reservasi' => 'Dikonfirmasi',
            ],
            [
                'kode_reservasi' => 'TIKET-BULAN-DEPAN',
                'pool_id' => 'pool_id_01',
                'nama_pengunjung' => 'Pengunjung November',
                'no_hp' => '083333333333',
                'tanggal_kunjungan' => '2026-11-01',
                'jumlah_dewasa' => 1,
                'jumlah_anak' => 0,
                'total_harga' => 25000,
                'status_reservasi' => 'Dikonfirmasi',
            ],
        ]);

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ])->get('/laporan-penjualan?bulan=2026-10');

        $response->assertOk()
            ->assertSee('Laporan Penjualan Tiket')
            ->assertSee('Oktober 2026')
            ->assertSee('TIKET-MILIK-ADMIN')
            ->assertDontSee('TIKET-MENUNGGU')
            ->assertDontSee('TIKET-KOLAM-LAIN')
            ->assertDontSee('TIKET-BULAN-DEPAN')
            ->assertDontSee('Pendapatan Tiket per Hari')
            ->assertDontSee('7 hari')
            ->assertSee('100.000')
            ->assertSee('2 tiket dewasa')
            ->assertSee('1 tiket anak');
    }

    public function test_print_view_includes_all_monthly_rows_instead_of_only_the_current_page(): void
    {
        $this->travelTo('2026-11-06 12:00:00');

        $reservations = [];
        for ($index = 1; $index <= 16; $index++) {
            $reservations[] = [
                'kode_reservasi' => 'TIKET-PRINT-'.$index,
                'pool_id' => 'pool_id_01',
                'nama_pengunjung' => 'Pengunjung '.$index,
                'no_hp' => '081234567890',
                'tanggal_kunjungan' => '2026-11-05',
                'jumlah_dewasa' => 1,
                'jumlah_anak' => 0,
                'total_harga' => 25000,
                'status_reservasi' => 'Dikonfirmasi',
            ];
        }
        DB::table('reservasis')->insert($reservations);

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ])->get('/laporan-penjualan?bulan=2026-11&cetak=1');

        $response->assertOk()
            ->assertSee('LPT/pool_id_01/2026-11')
            ->assertSee('TIKET-PRINT-16')
            ->assertSee('TIKET-PRINT-1')
            ->assertSee('Penanggung Jawab');
    }

    public function test_it_rejects_an_invalid_month(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
        ])->get('/laporan-penjualan?bulan=2026-13')
            ->assertRedirect()
            ->assertSessionHasErrors('bulan');
    }

    public function test_it_downloads_an_excel_compatible_csv_for_the_selected_month(): void
    {
        DB::table('reservasis')->insert([
            'kode_reservasi' => 'TIKET-EKSPOR',
            'pool_id' => 'pool_id_01',
            'nama_pengunjung' => 'Pengunjung Ekspor',
            'no_hp' => '081234567890',
            'tanggal_kunjungan' => '2026-11-05',
            'jumlah_dewasa' => 1,
            'jumlah_anak' => 1,
            'total_harga' => 50000,
            'status_reservasi' => 'Selesai',
        ]);

        $this->withSession([
            'admin_id' => 1,
            'admin_pool_id' => 'pool_id_01',
            'admin_pool_nama' => 'Tiara Park',
        ])->get('/laporan-penjualan/export?bulan=2026-11')
            ->assertDownload('laporan-penjualan-tiket-2026-11.csv');
    }

    public function test_it_denies_the_report_when_the_admin_pool_is_missing(): void
    {
        $this->withSession(['admin_id' => 1])
            ->get('/laporan-penjualan')
            ->assertForbidden();
    }
}
