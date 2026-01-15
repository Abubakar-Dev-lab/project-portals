<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Services\TaskService;
use App\Services\UserService;
use App\Services\ProjectService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

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
        $users = $this->userService->getUsersForDropdown();;
        return view('tasks.create', compact('projects', 'users'));
    }

    public function show($id)
    {
        $task =  $this->taskService->getTaskById($id);
        return view('tasks.show', compact('task'));
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = $this->taskService->createTask($data);
        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }

    public function edit($id)
    {
        $task = $this->taskService->getTaskById($id);
        $projects = $this->projectService->getProjectsForDropdown();
        $users = $this->userService->getUsersForDropdown();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $data = $request->validated();
        $task = $this->taskService->updateTask($id, $data);
        return redirect()->route('tasks.index')->with('success', 'Task Updated Successfully.');
    }

    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}
