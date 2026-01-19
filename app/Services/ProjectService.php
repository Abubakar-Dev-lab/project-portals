<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

class ProjectService
{

    public function __construct(
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo,

    ) {}

    public function createProject(array $data)
    {
        if (! auth()->user()->isAdmin()) {
            $data['manager_id'] = auth()->id();
        }
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

    public function updateProject(Project $project, array $data)
    {
        return $this->projectRepo->update($project, $data);
    }

    public function deleteProject(Project $project)
    {
        $hasActiveWork = $this->taskRepo->hasPendingTasksInProject($project->id);

        if ($hasActiveWork) {
            // We block the archive action
            return false;
        }
        return  $this->projectRepo->delete($project);
    }

    public function getProjectsForDropdown()
    {

        $user = auth()->user();

        // If Manager, only see projects I manage.
        // (Workers usually don't see this list because they can't create tasks)
        if ($user->isAdmin()) {
            return $this->projectRepo->getDropdownList();
        }

        return $this->projectRepo->getListByManager($user->id);
    }

    public function getProjectDetails(Project $project): Project
    {
        $user = auth()->user();
        return $this->projectRepo->loadFilteredTasks($project, $user);
    }
}
