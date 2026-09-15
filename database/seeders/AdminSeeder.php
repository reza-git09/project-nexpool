<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin_tiara'],
            [
                'pool_id' => 'pool_id_01',
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Admin Tiara Park',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'admin_pool02'],
            [
                'pool_id' => 'pool_id_02',
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Admin Pool 02',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'admin_pool03'],
            [
                'pool_id' => 'pool_id_03',
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Admin Pool 03',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'admin_pool04'],
            [
                'pool_id' => 'pool_id_04',
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Admin Pool 04',
            ]
        );

        Admin::updateOrCreate(
            ['username' => 'admin_pool05'],
            [
                'pool_id' => 'pool_id_05',
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Admin Pool 05',
            ]
        );
    }
}