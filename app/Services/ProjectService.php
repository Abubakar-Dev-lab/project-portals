<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

class ProjectService
{
    /**
     * Inject Project and Task repositories to manage project data and integrity.
     */
    public function __construct(
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo,

    ) {}

    /**
     * Create a project and force the manager_id if the user isn't an admin.
     */
    public function createProject(array $data)
    {
        if (! auth()->user()->isAdmin()) {
            $data['manager_id'] = auth()->id();
        }
        return $this->projectRepo->create($data);
    }

    /**
     * Get a paginated list of projects based on user permissions.
     */
    public function getAllProjects()
    {
        return $this->projectRepo->paginate();
    }

    /**
     * Fetch a single project by its ID.
     */
    public function getProjectById($id)
    {
        return $this->projectRepo->find($id);
    }

    /**
     * Pass the project object and new data to the repository for updating.
     */
    public function updateProject(Project $project, array $data)
    {
        return $this->projectRepo->update($project, $data);
    }

    /**
     * Prevent deletion if the project has unfinished tasks.
     */
    public function deleteProject(Project $project)
    {
        $hasActiveWork = $this->taskRepo->hasPendingTasksInProject($project->id);

        if ($hasActiveWork) {
            return false; // Block deletion
        }
        return  $this->projectRepo->delete($project);
    }

    /**
     * Get IDs and Titles of projects for selection menus, filtered by role.
     */
    public function getProjectsForDropdown()
    {
        $user = auth()->user();

        // Admin sees all; Manager sees only their owned projects
        if ($user->isAdmin()) {
            return $this->projectRepo->getDropdownList();
        }

        return $this->projectRepo->getListByManager($user->id);
    }

    /**
     * Load project data with tasks filtered by the viewer's role.
     */
    public function getProjectDetails(Project $project): Project
    {
        $user = auth()->user();
        return $this->projectRepo->loadFilteredTasks($project, $user);
    }
}
