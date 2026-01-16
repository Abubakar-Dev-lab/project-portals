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

        return Task::where(function ($query) use ($user) {
            // 1. Task assigned to me
            $query->where('assigned_to', $user->id)
                // 2. OR I am the manager of the parent project
                ->orWhereHas('project', function ($q) use ($user) {
                    $q->where('manager_id', $user->id);
                })
                // 3. OR I am a 'teammate' (I have OTHER tasks in that same project)
                ->orWhereIn('project_id', function ($q) use ($user) {
                    $q->select('project_id')->from('tasks')->where('assigned_to', $user->id);
                });
        })
            ->with(['project', 'user']) // Standard eager loading
            ->latest()
            ->paginate($perPage);
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
