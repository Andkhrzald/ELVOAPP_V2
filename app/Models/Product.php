<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    // Tambahkan color dan weight ke sini agar bisa disimpan
<<<<<<< HEAD
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
=======
    protected $fillable = ['name', 'slug', 'category_id', 'description', 'color', 'price', 'stock', 'weight', 'image'];
>>>>>>> 07f5c7a (active fitur search)

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

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $image = $this->image;

        // Jika file ada di storage/public
        if (Storage::disk('public')->exists($image)) {
            return asset('storage/' . $image);
        }

        // Jika path sudah relatif ke public
        if (file_exists(public_path($image))) {
            return asset($image);
        }

        // Jika nama file ada di public/uploads/products
        $uploadsPath = 'uploads/products/' . ltrim($image, '/');
        if (file_exists(public_path($uploadsPath))) {
            return asset($uploadsPath);
        }

        return asset('storage/' . $image);
    }
}
