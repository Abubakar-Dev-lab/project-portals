<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService) {}

    public function store(StoreProjectRequest $request)
    {
        $this->projectService->createProject($request->validated());
    }
}
