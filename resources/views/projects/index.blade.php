@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Projects</h2>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + New Project
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Title</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Manager</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-4 text-sm">
                            <p class="text-gray-900 font-bold">{{ $project->title }}</p>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            {{ $project->manager->name }} </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-right">
                            <a href="{{ route('projects.show', $project->id) }}"
                                class="text-blue-600 hover:underline mr-3">View</a>
                            <a href="{{ route('projects.edit', $project->id) }}"
                                class="text-yellow-600 hover:underline">Edit</a>

                        <form action="{{ route('projects.destroy',$project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form> </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $projects->links() }} </div>
@endsection
