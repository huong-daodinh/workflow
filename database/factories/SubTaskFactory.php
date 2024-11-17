<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubTask>
 */
class SubTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $priority = fake()->randomElement([0, 1, 2, 3]);
        $assigner = User::where('role', 'manager')->inRandomOrder()->first();
        $status = fake()->randomElement(['todo', 'doing', 'done', 'closed']);
        return [
          'title' => fake()->text(50),
          'priority' => $priority,
          'started_at' => $status !== 'todo' ? fake()->dateTimeBetween('-1 month', 'now') : null,
          'due_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
          'status' => $status,
          'description' => fake()->paragraph(10),
          'assigner_id' => $assigner->id,
          'assignee_id' => User::whereNot('id', $assigner->id)
                          ->where('department_id', $assigner->department_id)
                          ->where('role', 'member')
                          ->inRandomOrder()->first()->id,
          'task_id' => Task::factory(),
          'created_by' => $assigner->id
        ];
    }
}
