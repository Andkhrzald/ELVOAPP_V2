<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    // Tambahkan color dan weight ke sini agar bisa disimpan
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'price', 
        'stock', 
        'is_active', // Tambahkan ini
        'color',  
        'weight', 
        'image'
    ];

    /**
     * Relasi ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}