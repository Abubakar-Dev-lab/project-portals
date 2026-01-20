<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Models\Project;
use App\Models\Task;

class TrashService
{
    public function __construct(
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo
    ) {}

    public function getTrashedItems()
    {
        return [
            'projects' => $this->projectRepo->getTrashed(),
            'tasks'    => $this->taskRepo->getTrashed(),
        ];
    }

    public function restoreProject($id)
    {
        $project = $this->projectRepo->findTrashed($id);
        return $this->projectRepo->restore($project);
    }

    public function restoreTask($id)
    {
        $task = $this->taskRepo->findTrashed($id);

        // 🛡️ Integrity Check: Can't restore a task if its project is still trashed
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

    public function wipeProject($id)
    {
        // 1. Find the trashed project
        $project = $this->projectRepo->findTrashed($id);

        // 2. 🛡️ CASCADE WIPE: Physically delete all tasks (active or trashed) for this project
        // We do this manually because force-delete doesn't trigger standard DB cascades
        Task::withTrashed()->where('project_id', $id)->forceDelete();

        // 3. Physically delete the project
        return $this->projectRepo->forceDelete($project);
    }

    public function wipeTask($id)
    {
        $task = $this->taskRepo->findTrashed($id);
        return $this->taskRepo->forceDelete($task);
    }
}
