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
      'started_at',
      'due_date',
      'result'
    ];

    protected $table = 'subtasks';

    public function tags() {
      return $this->belongsToMany(Tag::class, 'subtasks_tags', 'subtask_id', 'tag_id');
    }

    public function assignee() {
      return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assigner() {
      return $this->belongsTo(User::class, 'assigner_id');
    }
}
