<!-- 1. The Bell -->
<a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-500 hover:text-blue-600 transition">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
        </path>
    </svg>
    <!-- Red Dot with ID for Real-time sync -->
    <span id="nav-notification-dot" @class([
        'absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-white',
        'hidden' => auth()->user()->unreadNotifications->count() === 0,
    ])></span>
</a>

<!-- 2. Other Links -->
<a href="{{ route('projects.index') }}" @class([
    'text-blue-600 font-bold' => request()->routeIs('projects.*'),
    'text-gray-600' => !request()->routeIs('projects.*'),
])>Projects</a>
<a href="{{ route('tasks.index') }}" @class([
    'text-blue-600 font-bold' => request()->routeIs('tasks.*'),
    'text-gray-600' => !request()->routeIs('tasks.*'),
])>Tasks</a>

@if (auth()->user()->isAdmin())
    <a href="{{ route('admin.users.index') }}" @class([
        'text-blue-600 font-bold' => request()->routeIs('admin.users.*'),
        'text-gray-600' => !request()->routeIs('admin.users.*'),
    ])>Users</a>
    <a href="{{ route('admin.trash.index') }}" @class([
        'text-blue-600 font-bold' => request()->routeIs('admin.trash.*'),
        'text-gray-600' => !request()->routeIs('admin.trash.*'),
    ])>Trash Bin</a>
@endif

<a href="{{ route('profile.edit') }}" @class([
    'text-blue-600 font-bold' => request()->routeIs('profile.*'),
    'text-gray-600' => !request()->routeIs('profile.*'),
])>Profile</a>
