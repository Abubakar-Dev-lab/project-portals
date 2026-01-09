<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function create(array $data)
    {
        return Project::create($data);
    }
}
