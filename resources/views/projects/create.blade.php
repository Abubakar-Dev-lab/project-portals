@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('projects.index') }}" class="text-blue-600 hover:underline text-sm">← Back to Projects</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Create New Project</h1>
        </div>

        <div class="bg-white shadow-md rounded-xl p-8 border border-gray-200">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <x-form-input name="title" label="Project Title" placeholder="e.g. Modernizing the Warehouse API" />

                <!-- Note: We are using a component for Textarea too -->
                <x-form-textarea name="description" label="Description" placeholder="What is this project about?" />

                <x-form-select name="manager_id" label="Project Manager" :options="$managers" />

                <div class="flex items-center justify-end space-x-4 border-t pt-6 mt-6">
                    <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                        Save Project
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
