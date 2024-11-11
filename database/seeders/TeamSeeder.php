<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::factory()->count(3)
        ->has(
          Project::factory()->count(5)->has(
            TaskList::factory()->count(10)->has(
              Task::factory()->count(5)->hasSubtasks(4)
              )
            )
        )
        ->create();
    }
}
