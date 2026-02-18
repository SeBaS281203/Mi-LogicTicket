<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Conciertos', 'Deportes', 'Teatro', 'Conferencias', 'Fiestas',
            'Cine', 'Festivales', 'Talleres', 'Gastronomía', 'Infantil',
        ]);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'image' => null,
            'is_active' => true,
        ];
    }
}
