<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengunjung', function (Blueprint $table): void {
            $table->string('username')->nullable()->change();
            $table->text('alamat')->nullable()->change();
        });
    }

    public function down(): void
    {
        foreach (DB::table('pengunjung')->whereNull('username')->pluck('id') as $id) {
            DB::table('pengunjung')
                ->where('id', $id)
                ->update(['username' => 'legacy_'.Str::uuid()]);
        }

        DB::table('pengunjung')
            ->whereNull('alamat')
            ->update(['alamat' => '']);

        Schema::table('pengunjung', function (Blueprint $table): void {
            $table->string('username')->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
        });
    }
};
