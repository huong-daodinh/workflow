<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['member_id', 'is_team_lead', 'team_id'];
    public function members()
    {
        return $this->belongsToMany(User::class, 'member_id');
    }
}
