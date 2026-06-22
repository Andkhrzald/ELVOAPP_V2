<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('material')->nullable()->after('color');
            $table->decimal('diameter', 8, 2)->nullable()->after('weight');
            $table->decimal('panjang_kalung', 8, 2)->nullable()->after('diameter');
            $table->string('kapasitas')->nullable()->after('panjang_kalung');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['material', 'diameter', 'panjang_kalung', 'kapasitas']);
        });
    }
};
