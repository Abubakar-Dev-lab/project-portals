<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;

class TaskRepository
{
    public function create(array $data)
    {
        return Task::create($data);
    }

    public function paginate($perPage = 10)
    {
        $user = auth()->user();
        $query = Task::with(['project', 'user']);

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        return $query->where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
                ->orWhereHas('project', function ($sub) use ($user) {
                    $sub->where('manager_id', $user->id)
                        ->orWhereHas('tasks', function ($t) use ($user) {
                            $t->where('assigned_to', $user->id);
                        });
                });
        })->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Task::with(['project', 'user'])->findOrFail($id);
    }

    public function update(Task $task, array $data,)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }

    public function hasPendingTasksForUser(User $user): bool
    {
        return Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->exists();
    }

    public function hasPendingTasksInProject(int $projectId): bool
    {
        return Task::where('project_id', $projectId)
            ->whereIn('status', ['todo', 'in_progress'])
            ->exists();
    }

    public function getTrashed($perPage = 10)
    {
        return Task::onlyTrashed()
            ->with(['project', 'user']) // Crucial for context in the Trash view
            ->latest('deleted_at')
            ->paginate($perPage);
    }

    public function findTrashed($id)
    {
        return Task::onlyTrashed()->findOrFail($id);
    }

    public function restore(Task $task)
    {
        return $task->restore();
    }

    public function forceDelete(Task $task)
    {
        return $task->forceDelete();
    }
}
