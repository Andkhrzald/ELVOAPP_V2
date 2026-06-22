<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active',
        'color',
        'weight',
        'material',
        'diameter',
        'panjang_kalung',
        'kapasitas',
        'image'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function hasVariants(): bool
    {
        return $this->activeVariants()->exists();
    }

    public function mainImage()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary) {
            return $primary->image;
        }
        $first = $this->images()->first();
        if ($first) {
            return $first->image;
        }
        return $this->image;
    }

    public function getVariantType(): string
    {
        $parentName = strtolower($this->category?->parent?->name ?? '');

        $sizeParents = ['fashion', 'outerwear', 'bawahan', 'footwear'];

        if (in_array($parentName, $sizeParents)) {
            return 'color_size';
        }

        return 'color_only';
    }

    public function needsSize(): bool
    {
        return $this->getVariantType() === 'color_size';
    }
}
