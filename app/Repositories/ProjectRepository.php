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
        return Project::with(['manager','tasks.user'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $project = $this->find($id);
        $project->update($data);
        return $project;
    }

    public function delete($id)
    {
        $project = $this->find($id);
        return $project->delete();
    }
}
