<?php

namespace App\Actions;

use App\Repositories\TaskRepository;

class AssignTaskAction
{
    public function __construct(protected TaskRepository $taskRepo) {}

    public function handle(array $data)
    {
       return $this->taskRepo->create($data);
    }
}
