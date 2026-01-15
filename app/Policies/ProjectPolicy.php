<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    // Senior Tip: The 'before' method runs first.
    // If it returns true, the user is granted access immediately.
    public function before(User $user)
    {
        if ($user->isAdmin()) return true;
    }

    public function view(User $user, Project $project): bool
    {
        // A Manager can see it if they own it.
        // A Worker can see it if they have a task inside it.
        return $user->id === $project->manager_id ||
            $project->tasks()->where('assigned_to', $user->id)->exists();
    }

    public function update(User $user, Project $project): bool
    {
        // Only the manager who owns the project can update it
        return $user->id === $project->manager_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->manager_id;
    }
}
