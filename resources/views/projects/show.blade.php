@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">{{ $project->title }}</h2>
            <div class="space-x-2">
                <a href="{{ route('projects.edit', $project->id) }}"
                    class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <x-detail-row label="Manager" :value="$project->manager->name" />
            <x-detail-row label="Status">
                <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-bold uppercase">
                    {{ $project->status }}
                </span>
            </x-detail-row>
            <x-detail-row label="Description" :value="$project->description" />
        </div>

        <!-- Tasks List Section -->
        <h3 class="text-xl font-bold mb-4 text-gray-700">Project Tasks</h3>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @include('tasks._table', ['tasks' => $project->tasks])
        </div>
    </div>
@endsection
