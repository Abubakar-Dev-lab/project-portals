@props(['tasks', 'showProject' => true])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Task Title</th>
                @if ($showProject)
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Project</th>
                @endif
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Assigned To</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tasks as $task)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $task->title }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($task->description, 40) }}</div>
                    </td>

                    @if ($showProject)
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('projects.show', $task->project->id) }}"
                                class="text-sm text-blue-600 hover:underline">
                                {{ $task->project->title }}
                            </a>
                        </td>
                    @endif

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700">
                            {{ $task->user?->name ?? 'Unassigned' }}
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span @class([
                            'px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full uppercase',
                            'bg-gray-100 text-gray-700' => $task->status === 'todo',
                            'bg-blue-100 text-blue-700' => $task->status === 'in_progress',
                            'bg-green-100 text-green-700' => $task->status === 'done',
                        ])>
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-4">
                            <a href="{{ route('tasks.show', $task->id) }}"
                                class="text-blue-600 hover:underline">View</a>
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', $task->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            @endcan
                            @can('delete', $task)
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showProject ? 5 : 4 }}" class="px-6 py-12 text-center text-gray-500">
                        No tasks found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (method_exists($tasks, 'links') && $tasks->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
