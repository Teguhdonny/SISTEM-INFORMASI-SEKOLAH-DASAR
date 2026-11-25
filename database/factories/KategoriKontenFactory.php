<?php

namespace Database\Factories;

use App\Models\KategoriKonten;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriKonten>
 */
class KategoriKontenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipe' => fake()->randomElement([
                KategoriKonten::TYPE_PROGRAM,
                KategoriKonten::TYPE_EKSTRAKURIKULER,
            ]),
            'nama' => fake()->unique()->words(2, true),
        ];
    }
}
