<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected ProjectService $projectService,
    ) {}

    /**
     * Display a paginated listing of projects.
     * Filtered by user permissions (Admins see all, others see owned/assigned).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return $this->success(
                ProjectResource::collection($projects),
                'Projects retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve projects',
                500
            );
        }
    }

    /**
     * Store a newly created project.
     * Non-admins automatically become the project manager.
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            // Verify user has permission to create projects
            $this->authorize('create', Project::class);

            $project = $this->projectService->createProject($request->validated());

            return $this->success(
                new ProjectResource($project),
                'Project created successfully',
                201
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to create projects'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to create project',
                500
            );
        }
    }

    /**
     * Display the specified project with its related data.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function show(Project $project): JsonResponse
    {
        try {
            $this->authorize('view', $project);

            $project = $this->projectService->getProjectDetails($project);

            return $this->success(
                new ProjectResource($project),
                'Project retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to view this project'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve project',
                500
            );
        }
    }

    /**
     * Update the specified project.
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return JsonResponse
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $this->authorize('update', $project);

            $project = $this->projectService->updateProject($project, $request->validated());

            return $this->success(
                new ProjectResource($project),
                'Project updated successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to update this project'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to update project',
                500
            );
        }
    }

    /**
     * Soft delete (move to trash) the specified project.
     * Prevents deletion if project has pending/active tasks.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function destroy(Project $project): JsonResponse
    {
        try {
            $this->authorize('delete', $project);

            $deleted = $this->projectService->deleteProject($project);

            if (!$deleted) {
                return $this->error(
                    ['tasks' => 'Project has pending tasks'],
                    'Cannot delete project with active tasks',
                    422
                );
            }

            return $this->success(
                null,
                'Project moved to trash successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to delete this project'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to delete project',
                500
            );
        }
    }
}
