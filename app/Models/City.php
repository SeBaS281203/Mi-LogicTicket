<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = ['name', 'slug', 'country', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function booted(): void
    {
        static::saving(function (City $city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });
    }
}
