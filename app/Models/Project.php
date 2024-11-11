<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'created_by', 'type', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
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

    }

    public function projectConversations()
    {
        return $this->hasMany(ProjectConversation::class);
    }

    public function countProjectConversations()
    {
        return $this->projectConversations()->count();
    }

    public function task_lists() {
        return $this->hasMany(TaskList::class);
    }
}
