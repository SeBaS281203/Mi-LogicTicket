<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = ['order_item_id', 'code'];

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
            $code = 'TK-' . strtoupper(Str::random(12)) . '-' . Str::random(4);
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
