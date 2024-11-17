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
        $assigner = User::where('role', 'manager')->inRandomOrder()->first();
        $status = fake()->randomElement(['todo', 'doing', 'done', 'closed']);
        return [
          'title' => fake()->text(50),
          'due_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
          'started_at' => $status !== 'todo' ? fake()->dateTimeBetween('-1 month', 'now') : null,
          'status' => $status,
          'description' => fake()->paragraph(10),
          'assigner_id' => $assigner->id,
          'assignee_id' => User::whereNot('id', $assigner->id)
                          ->where('department_id', $assigner->department_id)
                          ->where('role', 'member')
                          ->inRandomOrder()->first()->id,
          'task_list_id' => TaskList::factory(),
          'created_by' => $assigner->id
        ];
    }
}
