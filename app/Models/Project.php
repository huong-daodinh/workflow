<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'created_by', 'type', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    public function scopeFilter(Builder $query) {
      return $query->when(request('type'), function ($query) {
        $query->where('type', request('type'));
      });
    }

    public function projectConversations()
    {
        return $this->hasMany(ProjectConversation::class);
    }

    public function countProjectConversations()
    {
        return $this->projectConversations()->count();
    }

    public function taskLists() {
        return $this->hasMany(TaskList::class);
    }

    public function tasks() {
      return $this->hasMany(Task::class);
    }
}
