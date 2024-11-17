<?php

namespace App\Observers;

use App\Models\Tag;
use App\Models\Task;
use Carbon\Carbon;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
      $pendingTag = Tag::where('slug', 'pending')->first();
      $task->tags()->sync($pendingTag->id);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $now = Carbon::now();
        $overdueTag = Tag::where('slug', 'overdue')->first();
        $overdueCompletedTag = Tag::where('slug', 'overdue_completed')->first();
        $doingTag = Tag::where('slug', 'doing')->first();
        $doneTag = Tag::where('slug', 'done')->first();
        $pendingTag = Tag::where('slug', 'pending')->first();
        if ($task->isDirty('status')) {
          if ($task->status === 'todo') {
            if ($now > $task->due_date) {
                $task->tags()->sync($overdueTag->id);
            }
            $task->tags()->sync($pendingTag->id);
          }
          else if ($task->status === 'doing') {
            if ($now > $task->due_date) {
              $task->tags()->sync($overdueTag->id);
            }
              $task->tags()->sync($doingTag->id);
              $task->tags()->detach($pendingTag->id);
          }
          else if ($task->status === 'done') {
            if ($now > $task->due_date) {
              $task->tags()->sync($overdueCompletedTag->id);
            } else {
              $task->tags()->sync($doneTag->id);
            }
            $task->tags()->detach($doingTag->id);
          }
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
