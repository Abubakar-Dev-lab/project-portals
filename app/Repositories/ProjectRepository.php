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

        if ($user->isAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        if ($user->role === User::ROLE_MANAGER) {
            return $query->where('manager_id', $user->id)->latest()->paginate($perPage);
        }
        return $query->whereHas('tasks', function ($q) use ($user) {
            $q->where('assigned_to', $user->id);
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
}
