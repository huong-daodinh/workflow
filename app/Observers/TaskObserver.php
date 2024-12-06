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
      $task->tags()->attach($pendingTag->id);
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
        $closedTag = Tag::where('slug', 'closed')->first();
        $readyReviewTag = Tag::where('slug', 'ready_to_review')->first();
        if ($now > $task->due_date) {
          $task->tags()->attach($overdueTag->id);
        }
        if ($task->isDirty('status')) {
          if ($task->status === 'todo') {
        $task->tags()->attach($pendingTag->id);
          }
          else if ($task->status === 'doing') {
        $task->tags()->attach($doingTag->id);
            $task->tags()->detach($pendingTag->id);
          }
          else if ($task->status === 'done') {
            if ($now > $task->due_date) {
              $task->tags()->attach($overdueCompletedTag->id);
            } else {
              $task->tags()->attach($doneTag->id);
            }
            $task->tags()->detach($doingTag->id);
            $task->tags()->attach($readyReviewTag->id);
          } else if ($task->status === 'closed') {
            $task->tags()->attach($closedTag->id);
            $task->tags()->detach($readyReviewTag->id);
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
