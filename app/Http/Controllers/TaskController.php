<?php

namespace App\Http\Controllers;

use App\Actions\AssignTaskAction;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, AssignTaskAction $assignTaskAction)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255'
        ]);

        $task = $assignTaskAction->handle($data);

        return new TaskResource($task);
    }
}
