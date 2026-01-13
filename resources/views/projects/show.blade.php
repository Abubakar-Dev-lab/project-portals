@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <a href="{{ route('projects.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Projects</a>
                <h1 class="text-4xl font-extrabold text-gray-900 mt-2">{{ $project->title }}</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.edit', $project->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-bold shadow transition">Edit</a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-10">
            <div class="px-8 py-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">General Information</h3>
                <dl class="divide-y divide-gray-100">
                    <x-detail-row label="Manager" :value="$project->manager->name" />
                    <x-detail-row label="Current Status">
                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase bg-blue-100 text-blue-800">
                            {{ $project->status }}
                        </span>
                    </x-detail-row>
                    <x-detail-row label="Description" :value="$project->description" />
                    <x-detail-row label="Created At" :value="$project->created_at->format('M d, Y')" />
                </dl>
            </div>
        </div>

        <!-- Reusable Task Table -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Project Tasks</h3>
            <a href="{{ route('tasks.create') }}" class="text-blue-600 hover:underline font-medium">+ Add Task</a>
        </div>

        <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
            <!-- We pass showProject => false because we are already inside the project! -->
            @include('tasks._table', ['tasks' => $project->tasks, 'showProject' => false])
        </div>
    </div>
@endsection
