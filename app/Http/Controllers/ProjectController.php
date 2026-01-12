<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreProjectRequest;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function index() {}

    public function show($id) {}

    public function store(StoreProjectRequest $request)
    {
        $this->projectService->createProject($request->validated());
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
