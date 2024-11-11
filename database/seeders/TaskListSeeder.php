<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskList::factory()
            ->count(2)
            ->hasTasks(10)
            ->create();

        TaskList::factory()
            ->count(10)
            ->has(Task::factory()->count(5)->hasSubtasks(4))
            ->create();
    }
}
