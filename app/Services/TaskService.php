<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{

    /**
     * Inject the Task Repository to handle all database interactions.
     */
    public function __construct(protected TaskRepository $taskRepo) {}

    /**
     * Pass new task data to the repository for creation.
     */
    public function createTask(array $data)
    {
        return $this->taskRepo->create($data);
    }

    /**
     * Get a paginated list of tasks filtered by user permissions.
     */
    public function getAllTasks()
    {
        return $this->taskRepo->paginate();
    }

    /**
     * Fetch a specific task by its numeric ID.
     */
    public function getTaskById($id)
    {
        return $this->taskRepo->find($id);
    }

    /**
     * Send updated data and the task instance to the repository.
     */
    public function updateTask(Task $task, array $data)
    {
        return $this->taskRepo->update($task, $data);
    }

    /**
     * Trigger the deletion (or archiving) of a task.
     */
    public function deleteTask(Task $task)
    {
        return  $this->taskRepo->delete($task);
    }

    /**
     * Prepare a task for the Show view using the repository librarian.
     */
    public function getTaskDetails(Task $task): Task
    {
        return $this->taskRepo->loadDetails($task);
    }
}
