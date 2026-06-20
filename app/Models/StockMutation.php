<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'type',
        'qty', 'old_stock', 'new_stock', 'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        Product $product,
        string $type,
        int $qty,
        ?string $note = null,
        ?int $userId = null,
    ): self {
        $oldStock = $product->stock;
        $newStock = match ($type) {
            'in' => $oldStock + $qty,
            'out', 'order' => $oldStock - $qty,
            'cancel' => $oldStock + $qty,
            'adjustment' => $qty,
            default => $oldStock,
        };

        return static::create([
            'product_id' => $product->id,
            'user_id'    => $userId ?? auth()->id(),
            'type'       => $type,
            'qty'        => $type === 'adjustment' ? abs($newStock - $oldStock) : $qty,
            'old_stock'  => $oldStock,
            'new_stock'  => $newStock,
            'note'       => $note,
        ]);
    }
}
