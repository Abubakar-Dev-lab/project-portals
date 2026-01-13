@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb & Actions -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline text-sm">← Back to All Tasks</a>
                <h1 class="text-4xl font-extrabold text-gray-900 mt-2">{{ $task->title }}</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tasks.edit', $task->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-bold shadow transition">Edit
                    Task</a>
            </div>
        </div>

        <!-- Task Info Card -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-10">
            <div class="px-8 py-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Task Information</h3>
                <dl class="divide-y divide-gray-100">

                    <!-- Project Link -->
                    <x-detail-row label="Belongs to Project">
                        <a href="{{ route('projects.show', $task->project->id) }}"
                            class="text-blue-600 font-bold hover:underline">
                            {{ $task->project->title }}
                        </a>
                    </x-detail-row>

                    <!-- Assigned User -->
                    <x-detail-row label="Assigned To" :value="$task->user?->name ?? 'Unassigned'" />

                    <!-- Status Badge -->
                    <x-detail-row label="Status">
                        <span @class([
                            'px-3 py-1 text-xs font-bold rounded-full uppercase',
                            'bg-gray-100 text-gray-700' => $task->status === 'todo',
                            'bg-blue-100 text-blue-800' => $task->status === 'in_progress',
                            'bg-green-100 text-green-800' => $task->status === 'done',
                        ])>
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                    </x-detail-row>

                    <!-- Full Description -->
                    <x-detail-row label="Description" :value="$task->description" />

                    <x-detail-row label="Created At" :value="$task->created_at->format('M d, Y - H:i')" />
                </dl>
            </div>
        </div>
    </div>
@endsection
