<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pool;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class PoolSeeder extends Seeder
{
    public function run(): void
    {
        $pools = [
            [
                'pool_id' => 'pool_id_01',
                'name' => 'Tiara Jember Park Waterboom',
                'code' => 'TIARA2026',
            ],
            [
                'pool_id' => 'pool_id_02',
                'name' => 'Pemandian Kebon Agung',
                'code' => 'KEBON2026',
            ],
            [
                'pool_id' => 'pool_id_03',
                'name' => 'Annasya Waterpark',
                'code' => 'ANNASYA2026',
            ],
            [
                'pool_id' => 'pool_id_04',
                'name' => 'Dira Park',
                'code' => 'DIRA2026',
            ],
            [
                'pool_id' => 'pool_id_05',
                'name' => 'Jati Park',
                'code' => 'JATI2026',
            ],
        ];

        foreach ($pools as $p) {
            Pool::updateOrCreate(
                ['pool_id' => $p['pool_id']],
                [
                    'name' => $p['name'],
                    'registration_code' => Hash::make($p['code']),
                ]
            );
        }

        // Backfill existing admins from Admin table to User table so old admins can log in seamlessly
        $admins = Admin::all();
        foreach ($admins as $admin) {
            User::updateOrCreate(
                [
                    'pool_id' => $admin->pool_id,
                    'username' => $admin->username,
                ],
                [
                    'name' => $admin->nama_admin,
                    'email' => $admin->username . '@nexpool.test',
                    'password' => $admin->password, // Already hashed
                ]
            );
        }
    }
}
