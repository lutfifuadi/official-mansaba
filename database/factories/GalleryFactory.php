<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'image' => 'galleries/' . fake()->uuid() . '.jpg',
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['kegiatan', 'prestasi', 'kunjungan']),
        ];
    }
}
