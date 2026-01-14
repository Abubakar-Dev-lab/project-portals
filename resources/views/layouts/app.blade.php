<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta tag for security - vital for future AJAX/JS needs -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 py-4 mb-8 shadow-sm">
        <!-- The container mx-auto px-6 ensures the nav aligns perfectly with the content below -->
        <div class="container mx-auto px-6 flex justify-between items-center">

            <!-- Brand / Logo -->
            <a href="{{ route('projects.index') }}" class="text-2xl font-bold text-blue-600 tracking-tight">
                DevPortal
            </a>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-8">
                @auth
                    <!-- Links visible only to Authenticated Users -->
                    <a href="{{ route('projects.index') }}" @class([
                        'transition-colors duration-200',
                        'text-blue-600 font-bold border-b-2 border-blue-600' => request()->routeIs(
                            'projects.*'),
                        'text-gray-600 hover:text-blue-500' => !request()->routeIs('projects.*'),
                    ])>
                        Projects
                    </a>

                    <a href="{{ route('tasks.index') }}" @class([
                        'transition-colors duration-200',
                        'text-blue-600 font-bold border-b-2 border-blue-600' => request()->routeIs(
                            'tasks.*'),
                        'text-gray-600 hover:text-blue-500' => !request()->routeIs('tasks.*'),
                    ])>
                        Tasks
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" @class([
                            'px-4 transition',
                            'text-blue-600 font-bold border-b-2 border-blue-600' => request()->routeIs(
                                'admin.users.*'),
                            'text-gray-600 hover:text-blue-500' => !request()->routeIs('admin.users.*'),
                        ])>
                            User Management
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" @class([
                        'transition-colors duration-200',
                        'text-blue-600 font-bold border-b-2 border-blue-600' => request()->routeIs(
                            'tasks.*'),
                        'text-gray-600 hover:text-blue-500' => !request()->routeIs('profile.*'),
                    ])>
                        Profile
                    </a>

                    <!-- Secure Logout Form -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-600 hover:text-red-600 font-bold px-4 border-l border-gray-200 ml-4">
                            Logout ({{ auth()->user()->name }})
                        </button>
                    </form>
                @endauth

                @guest
                    <!-- Links visible only to Guests -->
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200 shadow-sm">
                        Register
                    </a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="container mx-auto px-6 pb-12">

        <!-- Flash Success Message -->
        @if (session('success'))
            <div id="flash-msg"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm transition-opacity duration-500">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
            <script>
                // Senior UX Touch: Auto-hide flash messages after 3 seconds
                setTimeout(() => {
                    const msg = document.getElementById('flash-msg');
                    if (msg) {
                        msg.style.opacity = '0';
                        setTimeout(() => msg.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif
        @if (session('error'))
            <div id="error-msg" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm">
                <p class="font-bold">Access Denied</p>
                <p>{{ session('error') }}</p>
            </div>
            <script>
                // Auto-hide the error after 5 seconds (longer than success)
                setTimeout(() => {
                    const msg = document.getElementById('error-msg');
                    if (msg) msg.remove();
                }, 5000);
            </script>
        @endif

        @yield('content')
    </main>

</body>

</html>
