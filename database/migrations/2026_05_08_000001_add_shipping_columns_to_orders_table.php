<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom pengiriman & pembayaran ke tabel orders.
     * Migration terpisah agar tidak conflict saat pull antar branch.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('shipping_method')->nullable()->after('payment_method');
            $table->integer('shipping_cost')->default(0)->after('shipping_method');
            $table->string('no_resi')->nullable()->after('shipping_cost');
            $table->text('notes')->nullable()->after('no_resi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'shipping_method', 'shipping_cost', 'no_resi', 'notes']);
        });
    }
};
