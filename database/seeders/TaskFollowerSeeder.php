<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskFollower;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskFollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            TaskFollower::create(['task_id' => $task->id, 'user_id' => $task->assigner_id]);
            TaskFollower::create(['task_id' => $task->id, 'user_id' => $task->assignee_id]);
        }
    }
}
