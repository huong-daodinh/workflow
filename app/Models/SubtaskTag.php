<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtaskTag extends Model
{
    use HasFactory;
    protected $table = 'subtasks_tags';
    protected $fillable = [
      'subtask_id',
      'tag_id',
    ];
}
