<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penarikan_saldos', function (Blueprint $table): void {
            $table->string('transfer_proof_path')->nullable();
            $table->foreignId('transfer_proof_uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('transfer_proof_uploaded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('penarikan_saldos', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('transfer_proof_uploaded_by');
            $table->dropColumn(['transfer_proof_path', 'transfer_proof_uploaded_at']);
        });
    }
};
