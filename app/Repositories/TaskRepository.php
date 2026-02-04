<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;

class TaskRepository
{
    /**
     * Save a new task record.
     */
    public function create(array $data)
    {
        return Task::create($data);
    }

    /**
     * Get tasks based on role: Admins see all, others see tasks in projects they belong to.
     */
    public function paginate($perPage = 10)
    {
        $user = auth()->user();
        $query = Task::with(['project', 'user']);

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        return $query->where(function ($q) use ($user) {
            // Show if assigned to user, or user manages project, or user is a teammate
            $q->where('assigned_to', $user->id)
                ->orWhereHas('project', function ($sub) use ($user) {
                    $sub->where('manager_id', $user->id)
                        ->orWhereHas('tasks', function ($t) use ($user) {
                            $t->where('assigned_to', $user->id);
                        });
                });
        })->latest()->paginate($perPage);
    }


    /**
     * Find a specific task with its project and assignee data.
     */
    public function find($id)
    {
        return Task::with(['project', 'user'])->findOrFail($id);
    }

    /**
     * Update a task model instance.
     */
    public function update(Task $task, array $data,)
    {
        $task->update($data);
        return $task;
    }

    /**
     * Move a task to the archive (Soft Delete).
     */
    public function delete(Task $task)
    {
        return $task->delete();
    }

    /**
     * Check if a specific user has unfinished tasks.
     */
    public function hasPendingTasksForUser(User $user): bool
    {
        return Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->exists();
    }

    /**
     * Check if a project contains any unfinished tasks.
     */
    public function hasPendingTasksInProject(int $projectId): bool
    {
        return Task::where('project_id', $projectId)
            ->whereIn('status', ['todo', 'in_progress'])
            ->exists();
    }

    /**
     * Get a paginated list of archived (soft deleted) tasks.
     */
    public function getTrashed($perPage = 10)
    {
        return Task::onlyTrashed()
            ->with(['project', 'user'])
            ->latest('deleted_at')
            ->get();
    }

    /**
     * Find a soft-deleted task by ID.
     */
    public function findTrashed($id)
    {
        return Task::onlyTrashed()->findOrFail($id);
    }

    /**
     * Restore an archived task to active status.
     */
    public function restore(Task $task)
    {
        return $task->restore();
    }

    /**
     * Permanently delete a task from the database.
     */
    public function forceDelete(Task $task)
    {
        return $task->forceDelete();
    }

    /**
     * Load necessary relationships for a task that is already in memory.
     */
    public function loadDetails(Task $task)
    {
        return $task->load(['project', 'user']);
    }
}
