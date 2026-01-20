<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{

    public function __construct(protected TaskRepository $taskRepo) {}
    //
    public function createTask(array $data)
    {
        return $this->taskRepo->create($data);
    }
    //
    public function getAllTasks()
    {
        return $this->taskRepo->paginate();
    }

    public function getTaskById($id)
    {
        return $this->taskRepo->find($id);
    }
    //
    public function updateTask(Task $task, array $data)
    {
        return $this->taskRepo->update($task, $data);
    }
    //
    public function deleteTask(Task $task)
    {
        return  $this->taskRepo->delete($task);
    }

    public function getTaskDetails(Task $task): Task
    {
        return $task->load(['project', 'user']);
    }
}
