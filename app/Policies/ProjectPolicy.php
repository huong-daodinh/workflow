<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === "manager";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->role === "manager" &&
              $user->department_id === $project->department_id &&
              $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
      return $user->role === "manager" &&
            $user->department_id === $project->department_id &&
            $user->id === $project->created_by;
    }
}
