<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_applications', function (Blueprint $table) {
            $table->text('password_hash')->nullable()->after('access_code');
            $table->unique('email', 'admin_applications_email_unique');
            $table->unique('phone', 'admin_applications_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_applications', function (Blueprint $table) {
            $table->dropUnique('admin_applications_email_unique');
            $table->dropUnique('admin_applications_phone_unique');
            $table->dropColumn('password_hash');
        });
    }
};
