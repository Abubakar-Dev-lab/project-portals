<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) return true;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->project->manager_id ||
            $user->id === $task->assigned_to;
    }

    public function create(User $user): bool
    {
        // Only Admins and Managers can create tasks
        return $user->isAdmin() || $user->role === User::ROLE_MANAGER;
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
