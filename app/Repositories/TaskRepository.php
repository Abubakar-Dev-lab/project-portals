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
        $user = auth()->user();
        $query = Task::with(['project', 'user']);

        if ($user->isAdmin()) {
            return $query->latest()->paginate($perPage);
        }

        return $query->where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
                ->orWhereHas('project', function ($sub) use ($user) {
                    $sub->where('manager_id', $user->id);
                });
        })->latest()->paginate($perPage);
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
