@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="text-gray-600 mt-1">Manage and track your company projects.</p>
        </div>

        <a href="{{ route('projects.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
            + New Project
        </a>
    </div>

    <!-- We just include the partial and pass the data -->
    @include('projects._table', ['projects' => $projects])
@endsection
