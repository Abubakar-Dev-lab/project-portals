<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;

class ProjectService
{

    public function __construct(protected ProjectRepository $projectRepo) {}

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
        $tasksCount = $this->projectRepo->getTasksCount($project);

        if ($tasksCount > 0) {
            return false;
        }
        return  $this->projectRepo->delete($project);
    }

    public function getProjectsForDropdown()
    {

        $user = auth()->user();

        // If Admin, see all projects.
        // If Manager, only see projects I manage.
        // (Workers usually don't see this list because they can't create tasks)
        if ($user->isAdmin()) {
            return $this->projectRepo->getDropdownList();
        }

        return Project::where('manager_id', $user->id)->pluck('title', 'id');
    }

    public function getProjectDetails(Project $project): Project
    {
        $user = auth()->user();

        return $project->load([
            'manager',
            'tasks' => function ($query) use ($user, $project) {
                $query->when(
                    !$user->isAdmin() &&
                        $project->manager_id !== $user->id,
                    function ($q) use ($user) {
                        $q->where('assigned_to', $user->id);
                    }
                )->with('user');
            }
        ]);
    }
}
