<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        $user_id = User::where('role_id', 2)->inRandomOrder()->first()->id ?: null;
        $position_id = Position::where('hiring', true)->inRandomOrder()->first()->id ?: null;

        return [
            'user_id' => $user_id,
            'position_id' => $position_id,
            'status_id' => fake()->numberBetween(1, 4),
            'attachment_text' => fake()->paragraph(),
            'attachment_file' => fake()->imageUrl(),
        ];
    }
}
