<!-- Notification Bell -->
<div class="relative inline-block md:block mb-4 md:mb-0">
    <a href="{{ route('notifications.index') }}" class="text-gray-500 hover:text-blue-600 flex items-center">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        <span class="md:hidden ml-2">Notifications</span> <!-- Text visible only on mobile -->
        <span id="nav-notification-dot" @class([
            'absolute top-0 left-4 md:left-3 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-white',
            'hidden' => auth()->user()->unreadNotifications->count() === 0,
        ])></span>
    </a>
</div>

<a href="{{ route('projects.index') }}" @class([
    'block md:inline-block transition-colors',
    'text-blue-600 font-bold underline decoration-2' => request()->routeIs(
        'projects.*'),
    'text-gray-600 hover:text-blue-500' => !request()->routeIs('projects.*'),
])>Projects</a>

<a href="{{ route('tasks.index') }}" @class([
    'block md:inline-block transition-colors',
    'text-blue-600 font-bold underline decoration-2' => request()->routeIs(
        'tasks.*'),
    'text-gray-600 hover:text-blue-500' => !request()->routeIs('tasks.*'),
])>Tasks</a>

@if (auth()->user()->isAdmin())
    <a href="{{ route('admin.users.index') }}" @class([
        'block md:inline-block transition',
        'text-blue-600 font-bold underline decoration-2' => request()->routeIs(
            'admin.users.*'),
        'text-gray-600 hover:text-blue-500' => !request()->routeIs('admin.users.*'),
    ])>Users</a>

    <a href="{{ route('admin.trash.index') }}" @class([
        'block md:inline-block transition',
        'text-blue-600 font-bold underline decoration-2' => request()->routeIs(
            'admin.trash.*'),
        'text-gray-600 hover:text-blue-500' => !request()->routeIs('admin.trash.*'),
    ])>Trash</a>
@endif

<a href="{{ route('profile.edit') }}" @class([
    'block md:inline-block transition',
    'text-blue-600 font-bold underline decoration-2' => request()->routeIs(
        'profile.*'),
    'text-gray-600 hover:text-blue-500' => !request()->routeIs('profile.*'),
])>Profile</a>
