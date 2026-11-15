<?php

namespace Database\Factories;

use App\Models\CalendarioEvento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarioEvento>
 */
class CalendarioEventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'fecha' => fake()->dateTimeBetween('today', '+6 months')->format('Y-m-d'),
            'descripcion' => fake()->optional()->paragraph(),
        ];
    }
}
