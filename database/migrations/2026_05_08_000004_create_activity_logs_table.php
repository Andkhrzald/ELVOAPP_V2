<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel log aktivitas untuk widget "Aktivitas Terkini" di dashboard admin.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');           // Jenis aksi: order_created, stock_updated, payment_confirmed, dll
            $table->text('description');         // Deskripsi ringkas aktivitas
            $table->string('model_type')->nullable(); // Model terkait: Order, Product, User
            $table->unsignedBigInteger('model_id')->nullable(); // ID record terkait
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
