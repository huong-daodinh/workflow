<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task)
    {
        return $user->role === 'admin' ||
        $user->role === 'manager' ||
        ($user->id === $task->assignee_id && $task->status === 'doing');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task)
    {
        //
    }

    public function start(User $user, Task $task)
    {
        return $user->id === $task->assignee_id && !in_array($task->status, ['closed', 'done']);
    }

    public function done(User $user, Task $task)
    {
        return $user->id === $task->assignee_id && !in_array($task->status, ['closed', 'done']);
    }

    public function process(User $user, Task $task)
    {
        return ($user->role === 'admin' || ($user->role === 'manager' && $task->assigner_id === $user->id))
                && $task->status === 'done';
    }
}
