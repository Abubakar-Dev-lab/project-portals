@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Tasks</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Create New Task</h1>
        </div>

        <div class="bg-white shadow-md rounded-xl p-8 border border-gray-200">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <x-form-select name="project_id" label="Project" :options="$projects" />

                <x-form-input name="title" label="Task Title" placeholder="e.g. Implement Login Logic" />

                <x-form-textarea name="description" label="Task Details" placeholder="Describe the steps..." />

                <x-form-select name="assigned_to" label="Assign to Worker" :options="$users" />

                <div class="flex items-center justify-end space-x-4 border-t pt-6 mt-6">
                    <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
