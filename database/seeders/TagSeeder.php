<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Tag::create([
        'slug' => 'urgent',
        'color' => 'danger',
      ]);

      Tag::create([
        'slug' => 'important',
        'color' => 'warning',
      ]);

      Tag::create([
        'slug' => 'overdue',
        'color' => 'danger',
      ]);

      Tag::create([
        'slug' => 'overdue_completed',
        'color' => 'warning',
      ]);

      Tag::create([
        'slug' => 'done',
        'color' => 'success',
      ]);

      Tag::create([
        'slug' => 'doing',
        'color' => 'info',
      ]);

      Tag::create([
        'slug' => 'pending',
        'color' => 'info',
      ]);

    }
}
