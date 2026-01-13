@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('projects.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Projects</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Edit Project</h1>
        </div>

        <div class="bg-white shadow-md rounded-xl p-8 border border-gray-200">
            <form action="{{ route('projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Crucial: Browsers don't support PUT, so Laravel "fakes" it -->

                <x-form-input name="title" label="Project Title" :value="$project->title" />

                <x-form-textarea name="description" label="Description" :value="$project->description" />

                <x-form-select name="manager_id" label="Project Manager" :options="$managers" :selected="$project->manager_id" />

                <!-- Now we can easily add a Status dropdown only on the Edit page -->
                <x-form-select name="status" label="Project Status" :options="['pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed']" :selected="$project->status" />

                <div class="flex items-center justify-end space-x-4 border-t pt-6 mt-6">
                    <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
