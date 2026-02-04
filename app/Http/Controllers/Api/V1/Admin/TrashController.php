<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Project;
use App\Traits\HttpResponses;
use App\Services\TrashService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TaskResource;
use App\Http\Resources\V1\ProjectResource;

class TrashController extends Controller
{
    use HttpResponses;

    public function __construct(protected TrashService $trashService) {}

    /**
     * Get all trashed items (Projects and Tasks).
     * Admin only endpoint.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Project::class);

            $items = $this->trashService->getTrashedItems();

            return $this->success([
                'projects' => ProjectResource::collection($items['projects']),
                'tasks' => TaskResource::collection($items['tasks']),
            ], 'Trashed items retrieved successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Only admins can access trash'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve trash items',
                500
            );
        }
    }

    /**
     * Restore a trashed project.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restoreProject(int $id): JsonResponse
    {
        try {
            $this->authorize('viewAny', \App\Models\Project::class);

            $project = $this->trashService->restoreProject($id);

            return $this->success(
                new ProjectResource($project),
                'Project restored successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized'],
                'Access denied',
                403
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(
                ['not_found' => 'Project not found in trash'],
                'Project not found',
                404
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to restore project',
                500
            );
        }
    }

    /**
     * Restore a trashed task (with safety check for parent project).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restoreTask(int $id): JsonResponse
    {
        try {
            $this->authorize('viewAny', \App\Models\Task::class);

            $result = $this->trashService->restoreTask($id);

            if (!$result['status']) {
                return $this->error(
                    ['constraint' => $result['message']],
                    $result['message'],
                    409
                );
            }

            return $this->success(
                null,
                $result['message']
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized'],
                'Access denied',
                403
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(
                ['not_found' => 'Task not found in trash'],
                'Task not found',
                404
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to restore task',
                500
            );
        }
    }

    /**
     * Permanently delete a project and all its tasks.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function wipeProject(int $id): JsonResponse
    {
        try {
            $this->authorize('viewAny', \App\Models\Project::class);

            $this->trashService->wipeProject($id);

            return $this->success(
                null,
                'Project and all associated tasks permanently deleted'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized'],
                'Access denied',
                403
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(
                ['not_found' => 'Project not found in trash'],
                'Project not found',
                404
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to delete project',
                500
            );
        }
    }

    /**
     * Permanently delete a single task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function wipeTask(int $id): JsonResponse
    {
        try {
            $this->authorize('viewAny', \App\Models\Task::class);

            $this->trashService->wipeTask($id);

            return $this->success(
                null,
                'Task permanently deleted'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized'],
                'Access denied',
                403
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error(
                ['not_found' => 'Task not found in trash'],
                'Task not found',
                404
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to delete task',
                500
            );
        }
    }
}
