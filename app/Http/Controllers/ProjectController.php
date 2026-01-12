<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        return response()->json($projects);
    }

    public function show($id)
    {
        $projects =  $this->projectService->getProjectById($id);
        return response()->json($projects);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'title',
            'description'
        ]);
        $project = $this->projectService->updateProject($id, $data);
        return response()->json($project);
    }

    public function destroy($id)
    {
        $this->projectService->deleteProject($id);
        return response()->json(['message'=>'Project deleted successfully']);
    }
}
