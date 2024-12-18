<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(User::class);
    }

    public function projects() {
      return $this->hasMany(Project::class);
    }
}
