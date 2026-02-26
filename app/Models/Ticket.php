<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = ['order_item_id', 'code', 'is_used', 'scanned_at'];

    protected $casts = [
        'is_used' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'scanned_at' => now(),
        ]);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderItem::class, 'order_id', 'id', 'order_item_id', 'id');
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'TK-' . strtoupper(Str::random(12)) . '-' . strtoupper(Str::random(4));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Valida si un código de ticket existe y pertenece a una orden pagada.
     */
    public static function isValidCode(string $code): bool
    {
        return self::where('code', $code)
            ->whereHas('orderItem', fn ($q) => $q->whereHas('order', fn ($oq) => $oq->where('status', 'paid')))
            ->exists();
    }
}
