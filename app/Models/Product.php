<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable; // Import atribut Fillable
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import untuk tipe data relasi

#[Fillable(['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image'])]
class Product extends Model
{
    /**
     * Relasi ke Category: Satu produk punya satu kategori
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}