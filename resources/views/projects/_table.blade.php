<div class="overflow-x-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Project Title</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Manager</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $project->title }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($project->description, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-700">{{ $project->manager->name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span @class([
                            'px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase',
                            'bg-blue-100 text-blue-800' => $project->status === 'active',
                            'bg-yellow-100 text-yellow-800' => $project->status === 'pending',
                            'bg-green-100 text-green-800' => $project->status === 'completed',
                        ])>
                            {{ $project->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-4">
                            <a href="{{ route('projects.show', $project->id) }}"
                                class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('projects.edit', $project->id) }}"
                                class="text-yellow-600 hover:text-yellow-900">Edit</a>

                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                onsubmit="return confirm('Delete this project and all its tasks?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">No projects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (method_exists($projects, 'links') && $projects->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $projects->links() }}
        </div>
    @endif
</div>
