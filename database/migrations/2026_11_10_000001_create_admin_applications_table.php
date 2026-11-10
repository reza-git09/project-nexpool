<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel pengajuan admin baru
        Schema::create('admin_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('username', 20);
            $table->string('email');
            $table->string('phone', 20);
            $table->enum('position', ['kasir_tiket', 'pengelola_fasilitas', 'staf_admin']);
            $table->string('pool_id');
            $table->string('access_code', 20);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['pool_id']);
        });

        // Tambahkan kolom role dan must_change_password ke tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                // role: 'admin_pool' atau 'admin_master'
                $table->string('role')->default('admin_pool')->after('pool_id');
            }
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('must_change_password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('phone');
            }
        });

        // Berikan role admin_master ke admin_tiara atau admin_master jika ada
        if (Schema::hasTable('users')) {
            \Illuminate\Support\Facades\DB::table('users')
                ->whereIn('username', ['admin_tiara', 'admin_master', 'admin'])
                ->update(['role' => 'admin_master']);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_applications');

        Schema::table('users', function (Blueprint $table) {
            $cols = ['role', 'must_change_password', 'position', 'phone', 'full_name'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

