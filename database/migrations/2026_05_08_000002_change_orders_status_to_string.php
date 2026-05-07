<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom status dari enum ke string agar lebih fleksibel.
     * Status yang digunakan: pending, proses, dikirim, selesai, batal
     */
    public function up(): void
    {
        // Ubah enum ke string agar bisa menampung status tambahan
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->change();
        });
    }
};
