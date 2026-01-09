<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Auth;

class ProjectService
{

    public function __construct(protected ProjectRepository $projectRepo){}

    public function createProject(array $data)
    {
        $data['manager_id'] = Auth::id();

        $this->projectRepo->create($data);
    }
}
