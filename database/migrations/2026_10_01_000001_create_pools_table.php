<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pools')) {
            Schema::create('pools', function (Blueprint $table) {
                $table->id();
                $table->string('pool_id')->unique();
                $table->string('name');
                $table->string('registration_code')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pools');
    }
};
