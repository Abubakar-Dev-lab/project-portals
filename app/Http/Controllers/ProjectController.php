<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\UserService;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
        protected UserService $userService,

    ) {}

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        $managers = $this->userService->getUsersForDropdown();
        return view('projects.create', compact('managers'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project = $this->projectService->getProjectDetails($project);
        return view('projects.show', compact('project'));
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);
        $project = $this->projectService->createProject($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project created!');
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $managers = $this->userService->getUsersForDropdown();
        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $this->projectService->updateProject($project, $request->validated());
        return redirect()->route('projects.index')->with('success', 'Project Updated Successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $isDeleted = $this->projectService->deleteProject($project);

        if (!$isDeleted) {
            return back()->with('error', 'Cannot delete project: This project still contains tasks. Please delete or reassign the tasks first.');
        }

        $this->projectService->deleteProject($project);
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
