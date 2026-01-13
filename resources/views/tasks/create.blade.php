@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Create New Task</h2>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <!-- Project Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Select Project</label>
                <select name="project_id"
                    class="w-full border rounded px-3 py-2 @error('project_id') border-red-500 @enderror">
                    <option value="">-- Choose a Project --</option>
                    @foreach ($projects as $id => $title)
                        <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Title -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Task Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Design Login Page"
                    class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Task Description -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="4" placeholder="What needs to be done?"
                    class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Assigned To Selection -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Assign to Worker</label>
                <select name="assigned_to"
                    class="w-full border rounded px-3 py-2 @error('assigned_to') border-red-500 @enderror">
                    <option value="">-- Select a Worker --</option>
                    @foreach ($users as $id => $name)
                        <option value="{{ $id }}" {{ old('assigned_to') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit & Cancel -->
            <div class="flex items-center justify-between border-t pt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700 transition">
                    Create Task
                </button>
                <a href="{{ route('tasks.index') }}" class="text-gray-500 hover:text-gray-700 underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
