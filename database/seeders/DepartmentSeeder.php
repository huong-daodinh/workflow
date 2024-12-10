<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::factory()
            ->count(2)
            ->has(User::factory()->count(5))
            ->has(
              Project::factory()->count(5)
                ->has(
                  TaskList::factory()->count(5)->has(Task::factory()->count(5)->has(SubTask::factory()->count(1)))
                )->has(
                  Task::factory()->count(5)
                ))->create();
    }
}
