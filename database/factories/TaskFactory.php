<?php

namespace Database\Factories;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $priority = fake()->randomElement([0, 1, 2, 3]);
        $assigner = User::inRandomOrder()->first();
        return [
          'title' => fake()->text(),
          'priority' => $priority,
          'start_date' => fake()->dateTimeBetween('-2 month', '+1 month'),
          'due_date' => fake()->dateTimeBetween('+1 month', '+2 month'),
          'status' => fake()->randomElement(['todo', 'in_progress', 'done']),
          'description' => fake()->paragraph(),
          'assigner_id' => $assigner->id,
          'assignee_id' => User::whereNot('id', $assigner->id)->inRandomOrder()->first()->id,
          'task_list_id' => TaskList::factory(),
          'created_by' => $assigner->id
        ];
    }
}
