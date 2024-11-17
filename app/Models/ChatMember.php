<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMember extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'chat_members';

    protected $fillable = ['member_id', 'chat_id', 'is_leader'];

    public function member() {
      return $this->belongsTo(User::class, 'member_id');
    }
}
