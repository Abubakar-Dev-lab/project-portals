<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Project;

class ProjectRepository
{
    /**
     * Save a new project to the database.
     */
    public function create(array $data)
    {
        return Project::create($data);
    }

    /**
     * Get a list of projects filtered by user permissions.
     */
    public function paginate($perPage = 10)
    {
        $user = auth()->user();
        $query = Project::with('manager');

        // Admins see everything
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        // Managers see their own; Workers see projects they have tasks in
        return $query->where(function ($q) use ($user) {
            $q->where('manager_id', $user->id)
                ->orWhereHas('tasks', function ($sub) use ($user) {
                    $sub->where('assigned_to', $user->id);
                });
        })->latest()->paginate($perPage);
    }

    /**
     * Get one project with its manager and tasks (including task workers).
     */
    public function find($id)
    {
        return Project::with(['manager', 'tasks.user'])->findOrFail($id);
    }

    /**
     * Update an existing project object.
     */
    public function update(Project $project, array $data,)
    {
        $project->update($data);
        return $project;
    }

    /**
     * Archive (Soft Delete) a project.
     */
    public function delete(Project $project)
    {
        return $project->delete();
    }

    /**
     * Get a simple list of all project titles and IDs for dropdowns.
     */
    public function getDropdownList()
    {
        return Project::orderBy('title')->pluck('title', 'id');
    }

    /**
     * Count how many tasks are in a specific project.
     */
    public function getTasksCount(Project $project)
    {
        return $project->tasks()->count();
    }


    /**
     * Get IDs and Titles of projects owned by a specific manager.
     */
    public function getListByManager(int $userId)
    {
        return Project::where('manager_id', $userId)
            ->orderBy('title')
            ->pluck('title', 'id');
    }

    /**
     * Load tasks into a project, hiding other people's tasks if the viewer is a worker.
     */
    public function loadFilteredTasks(Project $project, $user)
    {
        return $project->load([
            'manager',
            'tasks' => function ($query) use ($user, $project) {

                // If not admin/owner, only show tasks assigned to this user
                $query->when(!$user->isAdmin() && $project->manager_id !== $user->id, function ($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                })->with('user');
            }
        ]);
    }

    /**
     * Check if a manager has projects that are still active or pending.
     */
    public function hasPendingProjects(User $user): bool
    {
        return Project::where('manager_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();
    }

    /**
     * Get only the projects that have been archived (soft deleted).
     */
    public function getTrashed($perPage = 10)
    {
        return Project::onlyTrashed()
            ->with('manager')
            ->latest('deleted_at')
            ->get();
    }


    /**
     * Find a specific project inside the trash bin.
     */
    public function findTrashed($id)
    {
        // onlyTrashed() ensures we don't accidentally find an active project
        return Project::onlyTrashed()->findOrFail($id);
    }

    /**
     * Bring a project back from the trash to active status.
     */
    public function restore(Project $project)
    {
        $project->restore();

        return $project;
    }

    /**
     * Physically wipe a project from the database forever.
     */
    public function forceDelete(Project $project)
    {
        return $project->forceDelete();
    }
}
