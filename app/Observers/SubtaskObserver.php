<?php

namespace App\Observers;

use App\Models\SubTask;
use App\Models\Tag;
use Carbon\Carbon;

class SubtaskObserver
{
    /**
     * Handle the SubTask "created" event.
     */
    public function created(SubTask $subTask): void
    {
      $pendingTag = Tag::where('slug', 'pending')->first();
      $subTask->update(['status' => 'todo']);
      $subTask->tags()->attach($pendingTag->id);
    }

    /**
     * Handle the SubTask "updated" event.
     */
    public function updated(SubTask $subTask): void
    {
      $now = Carbon::now();
      $overdueTag = Tag::where('slug', 'overdue')->first();
      $overdueCompletedTag = Tag::where('slug', 'overdue_completed')->first();
      $doingTag = Tag::where('slug', 'doing')->first();
      $doneTag = Tag::where('slug', 'done')->first();
      $pendingTag = Tag::where('slug', 'pending')->first();
      if ($subTask->isDirty('status')) {
        if ($subTask->status === 'todo') {
          if ($now > $subTask->due_date) {
              $subTask->tags()->attach($overdueTag->id);
          }
          $subTask->tags()->attach($pendingTag->id);
        }
        else if ($subTask->status === 'doing') {
          if ($now > $subTask->due_date) {
            $subTask->tags()->attach($overdueTag->id);
          }
            $subTask->tags()->attach($doingTag->id);
            $subTask->tags()->detach($pendingTag->id);
        }
        else if ($subTask->status === 'done') {
          if ($now > $subTask->due_date) {
            $subTask->tags()->attach($overdueCompletedTag->id);
          } else {
            $subTask->tags()->attach($doneTag->id);
          }
          $subTask->tags()->detach($doingTag->id);
        }
      }
  }

    /**
     * Handle the SubTask "deleted" event.
     */
    public function deleted(SubTask $subTask): void
    {
        //
    }

    /**
     * Handle the SubTask "restored" event.
     */
    public function restored(SubTask $subTask): void
    {
        //
    }

    /**
     * Handle the SubTask "force deleted" event.
     */
    public function forceDeleted(SubTask $subTask): void
    {
        //
    }
}
