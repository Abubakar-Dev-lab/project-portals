<?php

namespace App\Http\Controllers;


use App\Services\TaskService;
use App\Services\UserService;
use App\Services\ProjectService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected ProjectService $projectService,
        protected UserService $userService,

    ) {}

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $projects = $this->projectService->getProjectsForDropdown();
        $users = $this->userService->getUsersForDropdown();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function show(Task $task)
    {
        $task = $this->taskService->getTaskDetails($task);
        return view('tasks.show', compact('task'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }

    public function edit(Task $task)
    {
        $projects = $this->projectService->getProjectsForDropdown();
        $users = $this->userService->getUsersForDropdown();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($request->validated(), $task);
        return redirect()->route('tasks.index')->with('success', 'Task Updated Successfully.');
    }

    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}
