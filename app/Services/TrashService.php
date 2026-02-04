<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Models\Project;
use App\Models\Task;

class TrashService
{
    /**
     * Inject both repositories to coordinate restoration and permanent deletion across modules.
     */
    public function __construct(
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo
    ) {}

    /**
     * Fetch all soft-deleted projects and tasks for the central Admin Trash view.
     */
    public function getTrashedItems()
    {
        return [
            'projects' => $this->projectRepo->getTrashed(),
            'tasks'    => $this->taskRepo->getTrashed(),
        ];
    }

    /**
     * Restore a project from the archive.
     */
    public function restoreProject($id)
    {
        $project = $this->projectRepo->findTrashed($id);
        return $this->projectRepo->restore($project);
    }

    /**
     * Restore a task, ensuring its parent project is not still in the trash.
     */
    public function restoreTask($id)
    {
        $task = $this->taskRepo->findTrashed($id);

        // Safety Check: Prevent "Orphan" tasks by ensuring the parent project is active
        $isProjectTrashed = Project::onlyTrashed()->where('id', $task->project_id)->exists();

        if ($isProjectTrashed) {
            return [
                'status' => false,
                'message' => "Cannot restore task. Please restore the project '{$task->project->title}' first."
            ];
        }

        $this->taskRepo->restore($task);
        return ['status' => true, 'message' => "Task restored successfully."];
    }

    /**
     * Permanently remove a project and all its associated tasks from the database.
     */
    public function wipeProject($id)
    {
        $project = $this->projectRepo->findTrashed($id);

        // Manually trigger a cascade wipe for all related tasks (active or archived)
        Task::withTrashed()->where('project_id', $id)->forceDelete();

        return $this->projectRepo->forceDelete($project);
    }

    /**
     * Permanently remove a single task from the database.
     */
    public function wipeTask($id)
    {
        $task = $this->taskRepo->findTrashed($id);
        return $this->taskRepo->forceDelete($task);
    }
}
