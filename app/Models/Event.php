<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'city', 'venue_name', 'venue_address', 'country', 'latitude', 'longitude',
        'start_date', 'end_date', 'status',
        'ticket_price', 'available_tickets', 'image', 'event_image',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'ticket_price' => 'decimal:2',
        ];
    }

    /**
     * Alias de venue_name para compatibilidad con el campo "venue".
     */
    public function getVenueAttribute(): ?string
    {
        return $this->venue_name ?? null;
    }

    /**
     * Establecer venue (guarda en venue_name).
     */
    public function setVenueAttribute(?string $value): void
    {
        $this->attributes['venue_name'] = $value;
    }

    /**
     * Imagen del evento (event_image tiene prioridad sobre image).
     */
    public function getEventImageAttribute(?string $value): ?string
    {
        return $value ?: ($this->attributes['image'] ?? null);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }
}
