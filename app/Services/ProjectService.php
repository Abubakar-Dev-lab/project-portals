<?php

namespace App\Services;

use App\Repositories\ProjectRepository;

class ProjectService
{

    public function __construct(protected ProjectRepository $projectRepo) {}

    public function createProject(array $data)
    {
        return $this->projectRepo->create($data);
    }

    public function getAllProjects()
    {
        return $this->projectRepo->all();
    }

    public function getProjectById($id)
    {
        return $this->projectRepo->find($id);
    }

    public function updateProject($id, array $data)
    {
        return $this->projectRepo->update($id, $data);
    }

    public function deleteProject($id)
    {
        return  $this->projectRepo->delete($id);
    }
}
