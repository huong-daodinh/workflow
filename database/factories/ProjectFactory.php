<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['P', 'T']); // P for personal, T for team
        return [
            'title' => fake()->text(),
            'description' => fake()->paragraph(),
            'created_by' => User::inRandomOrder()->first()->id,
            'type' => $type,
            'team_id' => $type === 'T' ? Team::factory() : null
        ];
    }
}
