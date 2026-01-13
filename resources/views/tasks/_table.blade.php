<table class="min-w-full bg-white">
    <thead class="bg-gray-50 border-b">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Task</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Assigned To</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @forelse($tasks as $task)
            <tr>
                <td class="px-6 py-4 text-sm font-semibold">{{ $task->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $task->user?->name ?? 'Unassigned' }}
                </td>
                <td class="px-6 py-4 text-sm">
                    <span @class([
                        'px-2 py-1 rounded-full text-xs font-bold',
                        'bg-gray-100 text-gray-600' => $task->status === 'todo',
                        'bg-blue-100 text-blue-700' => $task->status === 'in_progress',
                        'bg-green-100 text-green-700' => $task->status === 'done',
                    ])>
                        {{ $task->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-right flex justify-end space-x-2">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-yellow-600">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                        onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">No tasks found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- THE SENIOR LOGIC: Only show links if $tasks is a Paginator -->
@if (method_exists($tasks, 'links'))
    <div class="p-4 border-t">
        {{ $tasks->links() }}
    </div>
@endif
