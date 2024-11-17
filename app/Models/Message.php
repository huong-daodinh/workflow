<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
      'message',
      'sent_by',
      'sent_to',
      'replied_to',
      'edited_at',
      'chat_id',
      'edited_at',
    ];

    public function sentBy() {
      return $this->belongsTo(User::class, 'sent_by');
    }

    // chat room
    public function chat() {
      return $this->belongsTo(Chat::class);
    }

    public function replyTo() {
      return $this->belongsTo(Message::class, 'replied_to');
    }
}
