<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{

    public function __construct(protected TaskRepository $taskRepo) {}

    // public function createTask(array $data)
    // {
    //     return $this->taskRepo->create($data);
    // }

    public function getAllTasks()
    {
        return $this->taskRepo->all();
    }

    public function getTaskById($id)
    {
        return $this->taskRepo->find($id);
    }

    public function updateTask($id, array $data)
    {
        return $this->taskRepo->update($id, $data);
    }

    public function deleteTask($id)
    {
        return  $this->taskRepo->delete($id);
    }
}
