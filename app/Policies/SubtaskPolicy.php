<?php

namespace App\Policies;

use App\Models\SubTask;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubtaskPolicy
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
    public function view(User $user, SubTask $subTask)
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
    public function update(User $user, SubTask $subTask)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubTask $subTask)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubTask $subTask)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubTask $subTask)
    {
        //
    }

    public function start(User $user, SubTask $subTask)
    {
        return $user->id === $subTask->assignee_id && !in_array($subTask->status, ['closed', 'done']);
    }

    public function complete(User $user, SubTask $subTask)
    {
        return $user->id === $subTask->assignee_id && !in_array($subTask->status, ['closed', 'done']);
    }
}


