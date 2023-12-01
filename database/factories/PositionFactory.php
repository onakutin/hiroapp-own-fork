<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->boolean(70) ? fake()->unique()->numberBetween(1, 450) : null,
            'department_id' => fake()->numberBetween(1, 20),
            'grade_id' => fake()->numberBetween(1, 3),
            'name' => fake()->jobTitle(),
            'hiring' => fake()->boolean(35),
            'description' => fake()->paragraph(),
            'start_date' => fake()->boolean() ? fake()->dateTimeBetween('now', '+6 months') : null,
            'end_date' => fake()->boolean() ? fake()->dateTimeBetween('+12 months', '+3 years') : null,
        ];
    }
}
