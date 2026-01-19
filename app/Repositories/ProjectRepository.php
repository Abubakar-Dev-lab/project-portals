<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Project;

class ProjectRepository
{
    public function create(array $data)
    {
        return Project::create($data);
    }

    public function paginate($perPage = 10)
    {
        $user = auth()->user();
        $query = Project::with('manager');

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        return $query->where(function ($q) use ($user) {
            $q->where('manager_id', $user->id)
                ->orWhereHas('tasks', function ($sub) use ($user) {
                    $sub->where('assigned_to', $user->id);
                });
        })->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Project::with(['manager', 'tasks.user'])->findOrFail($id);
    }

    public function update(Project $project, array $data,)
    {
        $project->update($data);
        return $project;
    }

    public function delete(Project $project)
    {
        return $project->delete();
    }

    public function getDropdownList()
    {

        return Project::orderBy('title')->pluck('title', 'id');
    }

    public function getTasksCount(Project $project)
    {
        return $project->tasks()->count();
    }


    /**
     * Get projects owned by a specific manager for a dropdown.
     */
    public function getListByManager(int $userId)
    {
        return Project::where('manager_id', $userId)
            ->orderBy('title')
            ->pluck('title', 'id');
    }

    /**
     * Load relationships with specific security filters.
     */
    public function loadFilteredTasks(Project $project, $user)
    {
        return $project->load([
            'manager',
            'tasks' => function ($query) use ($user, $project) {
                $query->when(!$user->isAdmin() && $project->manager_id !== $user->id, function ($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                })->with('user');
            }
        ]);
    }

    public function hasPendingProjects(User $user): bool
    {
        return Project::where('manager_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();
    }

    public function getTrashed($perPage = 10)
    {
        // onlyTrashed() filters the query to ONLY show items in the bin
        return Project::onlyTrashed()
            ->with('manager') // Still eager load to avoid N+1
            ->latest('deleted_at') // Show recently deleted items first
            ->paginate($perPage);
    }

    public function findTrashed($id)
    {
        // onlyTrashed() ensures we don't accidentally find an active project
        return Project::onlyTrashed()->findOrFail($id);
    }

    public function restore(Project $project)
    {
        return $project->restore();
    }
    public function forceDelete(Project $project)
    {
        return $project->forceDelete();
    }
}
