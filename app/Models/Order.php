<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'customer_email', 'customer_name', 'customer_phone',
        'subtotal', 'commission_amount', 'total', 'status', 'payment_method', 'payment_id',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'LT-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
