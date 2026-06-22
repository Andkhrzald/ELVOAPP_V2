<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'size', 'color', 'color_hex',
        'stock', 'price', 'image', 'is_active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function displayPrice()
    {
        return $this->price ?? $this->product->price;
    }

    public function displayLabel(): string
    {
        $parts = array_filter([$this->color, $this->size]);
        return implode(' / ', $parts);
    }
}
