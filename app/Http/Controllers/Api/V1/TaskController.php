<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    use HttpResponses;

    public function __construct(protected TaskService $taskService) {}

    /**
     * Display a paginated listing of tasks.
     * Filtered by user permissions (Admins see all, others see assigned/project tasks).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $tasks = $this->taskService->getAllTasks();
            return $this->success(
                TaskResource::collection($tasks),
                'Tasks retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve tasks',
                500
            );
        }
    }

    /**
     * Store a newly created task.
     * Validates that user has permission to add task to the project.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Task::class);

            $task = $this->taskService->createTask($request->validated());

            return $this->success(
                new TaskResource($task),
                'Task created successfully',
                201
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to create tasks'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to create task',
                500
            );
        }
    }

    /**
     * Display the specified task with its related data.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        try {
            $this->authorize('view', $task);

            $task = $this->taskService->getTaskDetails($task);

            return $this->success(
                new TaskResource($task),
                'Task retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to view this task'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve task',
                500
            );
        }
    }

    /**
     * Update the specified task.
     * Validates that user has permission to update this task and its project.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        try {
            $this->authorize('update', $task);

            $task = $this->taskService->updateTask($task, $request->validated());

            return $this->success(
                new TaskResource($task),
                'Task updated successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to update this task'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to update task',
                500
            );
        }
    }

    /**
     * Soft delete (move to trash) the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->authorize('delete', $task);

            $this->taskService->deleteTask($task);

            return $this->success(
                null,
                'Task moved to trash successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to delete this task'],
                'Access denied',
                403
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
