<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectConversation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['project_id', 'content', 'sent_by', 'reply_to'];
    protected $table = ['project_conversation'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function replyTo()
    {
        return $this->belongsTo(ProjectConversation::class, 'reply_to');
    }
}
