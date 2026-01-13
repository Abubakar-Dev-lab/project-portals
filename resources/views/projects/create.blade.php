@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6">
            <strong>Validation Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Create New Project</h2>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <x-form-input name="title" label="Project Title" />

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="5"
                    class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- This is beautiful! One line instead of 10 -->
            <x-form-select name="manager_id" label="Manager" :options="$managers" />

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Save Project</button>
                <a href="{{ route('projects.index') }}" class="text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
@endsection
