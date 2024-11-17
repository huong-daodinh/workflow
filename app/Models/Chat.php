<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'avatar', 'is_private_chat'];

    public function messages() {
      return $this->hasMany(Message::class);
    }

    public function members() {
      return $this->belongsToMany(User::class, 'chat_members', 'chat_id', 'member_id');
    }
}
