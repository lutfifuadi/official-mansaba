<?php

namespace Database\Factories;

use App\Models\Extracurricular;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Extracurricular>
 */
class ExtracurricularFactory extends Factory
{
    protected $model = Extracurricular::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Paskibra',
            'Pramuka',
            'PMR',
            'Futsal',
            'Basket',
            'Voli',
            'English Club',
            'Rohis',
            'Seni Tari',
            'Paduan Suara',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'coach' => fake()->name(),
            'image' => null,
            'schedule' => fake()->randomElement(['Setiap Senin', 'Setiap Rabu', 'Setiap Sabtu', 'Setiap Jumat']),
            'category' => fake()->randomElement(['olahraga', 'seni', 'akademik', 'keagamaan']),
        ];
    }
}
