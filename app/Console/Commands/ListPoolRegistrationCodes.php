<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pool;
use Illuminate\Support\Facades\Hash;

class ListPoolRegistrationCodes extends Command
{
    protected $signature = 'nexpool:registration-codes {--reset : Reset registration codes to defaults}';

    protected $description = 'Tampilkan dan kelola kode registrasi wisata kolam renang NEXPOOL';

    public function handle()
    {
        $defaultCodes = [
            'pool_id_01' => 'TIARA2026',
            'pool_id_02' => 'KEBON2026',
            'pool_id_03' => 'ANNASYA2026',
            'pool_id_04' => 'DIRA2026',
            'pool_id_05' => 'JATI2026',
        ];

        if ($this->option('reset')) {
            foreach ($defaultCodes as $poolId => $code) {
                Pool::where('pool_id', $poolId)->update([
                    'registration_code' => Hash::make($code),
                ]);
            }
            $this->info("Kode registrasi berhasil di-reset ke nilai bawaan.");
        }

        $pools = Pool::orderBy('pool_id')->get();
        $this->info("\n=== KODE REGISTRASI WISATA NEXPOOL ===");
        
        $tableRows = [];
        foreach ($pools as $pool) {
            $code = $defaultCodes[$pool->pool_id] ?? 'KODE_KUSTOM';
            $tableRows[] = [
                'Pool ID' => $pool->pool_id,
                'Nama Wisata' => $pool->name,
                'Kode Registrasi Sementara' => $code,
            ];
        }

        $this->table(['Pool ID', 'Nama Wisata', 'Kode Registrasi Sementara'], $tableRows);
        $this->comment("Simpan kode ini dengan aman. Kode tidak ditampilkan di antarmuka web.");
        return 0;
    }
}
