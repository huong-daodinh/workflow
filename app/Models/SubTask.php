<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTask extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
      'title',
      'priority',
      'description',
      'status',
      'task_id',
      'created_by',
      'assigner_id',
      'due_date',
      'description',
      'assignee_id',
      'start_date',
      'due_date'
    ];

    protected $table = 'subtasks';

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function tags() {
      return $this->hasManyThrough(Tag::class, SubtaskTag::class, 'subtask_id', 'id', 'id', 'tag_id');
    }
}
