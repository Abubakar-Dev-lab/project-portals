@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Tasks</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Edit Task</h1>
        </div>

        <div class="bg-white shadow-md rounded-xl p-8 border border-gray-200">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-select name="project_id" label="Project" :options="$projects" :selected="$task->project_id" />

                <x-form-input name="title" label="Task Title" :value="$task->title" />

                <x-form-textarea name="description" label="Task Details" :value="$task->description" />

                <x-form-select name="assigned_to" label="Assign to Worker" :options="$users" :selected="$task->assigned_to" />

                <x-form-select name="status" label="Status" :options="['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done']" :selected="$task->status" />

                <div class="flex items-center justify-end space-x-4 border-t pt-6 mt-6">
                    <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
