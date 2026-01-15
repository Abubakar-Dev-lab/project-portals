<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function create(array $data)
    {
        return Project::create($data);
    }

    public function paginate($perPage = 10)
    {
        return Project::with('manager')->paginate($perPage);
    }

    public function find($id)
    {
        return Project::with(['manager', 'tasks.user'])->findOrFail($id);
    }

    public function update(array $data, Project $project)
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
