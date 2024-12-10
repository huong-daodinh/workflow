<?php

namespace Database\Factories;

use App\Models\Department;
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
      $user = User::where('role', 'manager')->inRandomOrder()->first();
        return [
            'title' => fake()->text('20'),
            'description' => fake()->paragraph('20'),
            'created_by' => $user->id,
            'department_id' => $user->department_id
        ];
    }
}
