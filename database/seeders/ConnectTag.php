<?php

namespace Database\Seeders;

use App\Models\SubtaskTag;
use App\Models\TaskTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectTag extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubtaskTag::factory()->count(2000)->create();
        TaskTag::factory()->count(1000)->create();
    }
}
