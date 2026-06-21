<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'order_number',
    'total_price',
    'status',
    'payment_method',
    'shipping_method',
    'shipping_cost',
    'no_resi',
    'notes',
    'cancel_reason',
    'refund_reason',
    'previous_status',
    'payment_proof',
    'va_number',
    'va_expires_at',
    'selected_bank',
])]
class Order extends Model
{
    protected $casts = [
        'va_expires_at' => 'datetime',
    ];

    /**
     * Relasi ke User (pelanggan yang memesan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke OrderItem (detail item dalam pesanan)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}