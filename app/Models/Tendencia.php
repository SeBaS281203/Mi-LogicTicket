<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tendencia extends Model
{
    protected $table = 'tendencias';

    protected $fillable = [
        'titulo', 'imagen', 'link', 'activo', 'orden', 'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}
