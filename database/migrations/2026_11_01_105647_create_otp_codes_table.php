<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();          // format 628xxxxxxxxxx
            $table->string('code_hash', 64);               // kode OTP di-hash, tidak disimpan polos
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');               // kode kedaluwarsa
            $table->timestamp('verified_at')->nullable();  // terisi kalau kode benar
            $table->string('token_hash', 64)->nullable()->index(); // bukti verifikasi untuk /register
            $table->timestamp('used_at')->nullable();      // token sudah dipakai registrasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};