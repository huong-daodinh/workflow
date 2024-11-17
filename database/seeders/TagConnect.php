<?php

namespace Database\Seeders;

use App\Models\SubtaskTag;
use App\Models\TaskTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagConnect extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskTag::factory()
            ->count(100)
            ->create();
        SubtaskTag::factory()
            ->count(200)
            ->create();
    }
}
