@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">

        <!-- Page Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">System Archive</h1>
            <p class="text-gray-600 mt-2">Manage soft-deleted resources. Restore them to active status or permanently wipe
                them.</p>
        </div>

        <!-- Section 1: Projects -->
        <div class="mb-12">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-gray-800">Archived Projects</h2>
            </div>

            <!-- Reusing the Project Table Partial in Trash Mode -->
            @include('projects._table', ['projects' => $projects, 'isTrash' => true])
        </div>

        <!-- Section 2: Tasks -->
        <div class="mb-12">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-2 h-8 bg-green-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-gray-800">Archived Tasks</h2>
            </div>

            <!-- Reusing the Task Table Partial in Trash Mode -->
            @include('tasks._table', ['tasks' => $tasks, 'isTrash' => true])
        </div>

    </div>
@endsection
