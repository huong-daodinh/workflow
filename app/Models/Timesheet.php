<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class Timesheet extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
      'created_by',
      'date',
      'check_in_time',
      'check_out_time',
    ];

    public function scopeFilter(Builder $query) {
      $query->when(request()->input('month') && request()->input('year'), function($query) {
          $query->whereMonth('date', request()->input('month'))
              ->whereYear('date', request()->input('year'));
      });
    }
}
