<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin() || $user->isSuperAdmin() ) return true;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // 1. If I am the manager or the assignee, it's a fast 'Yes' (In-memory check)
        if ($user->id === $task->assigned_to || $user->id === $task->project->manager_id) {
            return true;
        }

        // 2. If I am a teammate, we check the database (1 query)
        // We only reach this line if it's a "Show" page, so there is no N+1 loop!
        return $task->project->tasks()->where('assigned_to', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        // Only Admins and Managers can create tasks
        return  $user->role === User::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->project->manager_id ||
            $user->id === $task->assigned_to;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->manager_id;
    }
}
