<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
      'title',
      'priority',
      'status',
      'result',
      'task_list_id',
      'project_id',
      'created_by',
      'assigner_id',
      'due_date',
      'description',
      'assignee_id',
      'started_at',
    ];

    public function followers() {
      return $this->belongsToMany(User::class, 'task_followers', 'task_id', 'user_id');
    }

    public function messages() {
      return $this->hasMany(TaskMessage::class, 'task_id');
    }

    public function subtasks() {
      return $this->hasMany(SubTask::class, 'task_id');
    }

    public function assignee() {
      return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assigner() {
      return $this->belongsTo(User::class, 'assigner_id');
    }

    public function tags() {
      return $this->belongsToMany(Tag::class, 'tasks_tags', 'task_id', 'tag_id');
    }

    public function attachments() {
      return $this->hasMany(TaskAttachment::class, 'task_id');
    }

    public function project() {
      return $this->belongsTo(Project::class, 'project_id');
    }
}
