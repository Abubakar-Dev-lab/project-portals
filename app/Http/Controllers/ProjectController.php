<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
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
        $managers = $this->userService->getUsersForDropdown();
        return view('projects.create', compact('managers'));
    }

    public function show($id)
    {
        $project =  $this->projectService->getProjectById($id);
        return view('projects.show', compact('project'));
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project created!');
    }

    public function edit($id)
    {
        $project =  $this->projectService->getProjectById($id);
        $managers = $this->userService->getUsersForDropdown();

        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $data = $request->validated();
        $project = $this->projectService->updateProject($id, $data);
        return redirect()->route('projects.index')->with('success', 'Project Updated Successfully.');
    }

    public function destroy($id)
    {
        $this->projectService->deleteProject($id);
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
