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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Menghubungkan produk ke id di tabel categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('name');            // Nama Produk (Contoh: Elvo Hoodie Black)
            $table->string('slug')->unique();  // URL ramah (Contoh: elvo-hoodie-black)
            $table->text('description');       // Detail produk
            $table->integer('price');          // Harga produk (integer agar mudah dihitung)
            $table->integer('stock');          // Stok barang
            $table->string('image')->nullable(); // Nama file gambar produk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};