<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'pool_id')) {
                $table->string('pool_id')->nullable()->after('username');
                $table->foreign('pool_id')
                    ->references('pool_id')
                    ->on('pools')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            }
            $table->unique(['pool_id', 'username'], 'users_pool_id_username_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_pool_id_username_unique');
            $table->dropForeign(['pool_id']);
            $table->dropColumn(['username', 'pool_id']);
        });
    }
};
