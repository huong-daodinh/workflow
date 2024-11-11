<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'created_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }
}
