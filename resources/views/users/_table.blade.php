<div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase text-center">Role</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase text-center">Tasks</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        {{ $user->name }}
                        @if ($user->id === auth()->id())
                            <span class="text-xs text-blue-500 font-normal">(You)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span @class([
                            'px-3 py-1 rounded-full text-xs font-bold uppercase',
                            'bg-red-100 text-red-700' => $user->role === 'admin',
                            'bg-blue-100 text-blue-700' => $user->role === 'manager',
                            'bg-gray-100 text-gray-700' => $user->role === 'worker',
                        ])>
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                        {{ $user->tasks_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-3">
                            @can('update', $user)
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="text-yellow-600 hover:underline">Edit</a>
                            @endcan
                            <!-- Senior Security Logic: Admin cannot delete themselves -->
                            @can('delete', $user)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Delete user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            @endcan
                            <!-- If they can't do either, show a neutral status -->
                            @if (auth()->user()->cannot('update', $user) && auth()->user()->cannot('delete', $user))
                                <span class="text-gray-400 text-xs italic">Read Only</span>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($users->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $users->links() }}
        </div>
    @endif
</div>
