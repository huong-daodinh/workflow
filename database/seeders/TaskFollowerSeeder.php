<?php

namespace Database\Seeders;

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
        TaskFollower::factory()->count(20)->create();
    }
}
