<?php

namespace App\Services;

use App\Models\Project;
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
        return $this->projectRepo->paginate();
    }

    public function getProjectById($id)
    {
        return $this->projectRepo->find($id);
    }

    public function updateProject(array $data, Project $project)
    {
        return $this->projectRepo->update($data, $project);
    }

    public function deleteProject(Project $project)
    {
        return  $this->projectRepo->delete($project);
    }

    public function getProjectsForDropdown()
    {
        return $this->projectRepo->getDropdownList();
    }

    public function getProjectDetails(Project $project): Project
    {
        return $project->load(['manager', 'tasks.user']);
    }
}
