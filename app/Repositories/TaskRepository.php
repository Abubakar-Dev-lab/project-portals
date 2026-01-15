<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function create(array $data)
    {
        return Task::create($data);
    }

    public function paginate($perPage = 10)
    {
        return Task::with(['project', 'user'])->paginate($perPage);
    }

    public function find($id)
    {
        return Task::with(['project', 'user'])->findOrFail($id);
    }

    public function update(Task $task, array $data,)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }
}
