<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity', 'quantity_sold',
        'max_per_order', 'sale_start', 'sale_end',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_start' => 'datetime',
            'sale_end' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAvailableQuantityAttribute(): int
    {
        return max(0, $this->quantity - $this->quantity_sold);
    }

    public function isOnSale(): bool
    {
        $now = now();
        if ($this->sale_start && $this->sale_start->isFuture()) {
            return false;
        }
        if ($this->sale_end && $this->sale_end->isPast()) {
            return false;
        }
        return $this->available_quantity > 0;
    }
}
