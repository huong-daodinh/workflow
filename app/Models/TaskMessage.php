<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskMessage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'task_messages';
    protected $fillable = ['task_id', 'sent_by', 'content', 'edited_at', 'replied_to'];

    public function sentBy(){
        return $this->belongsTo(User::class, 'sent_by');
    }
}
