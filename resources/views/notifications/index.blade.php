@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600">History of your task assignments and system alerts.</p>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <li @class([
                        'p-4 transition',
                        'bg-blue-50' => is_null($notification->read_at), // Highlight unread
                        'bg-white' => !is_null($notification->read_at),
                    ])>
                        <div class="flex items-center justify-between">
                            <div>
                                <!-- Accessing the data we saved in TaskAssignedNotification.php -->
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $notification->data['title'] }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $notification->data['message'] }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Project: {{ $notification->data['project_title'] }} •
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @if (is_null($notification->read_at))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    New
                                </span>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-gray-500 italic">
                        You have no notifications yet.
                    </li>
                @endforelse
            </ul>

            @if ($notifications->hasPages())
                <div class="p-4 border-t">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
