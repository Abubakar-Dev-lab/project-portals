<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return TaskResource::collection($tasks);
    }

    public function show($id)
    {
        $tasks =  $this->taskService->getTaskById($id);
        return new TaskResource($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $task = $this->taskService->createTask($data);
        return new TaskResource($task);
    }


    public function update(Request $request, $id)
    {
        $data = $request->only([
            'title',
            'description'
        ]);
        $task = $this->taskService->updateTask($id, $data);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
