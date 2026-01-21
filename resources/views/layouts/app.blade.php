<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta tag for security - vital for future AJAX/JS needs -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev Portal</title>
    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <x-toast-notification />

    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 py-4 mb-8 shadow-sm">
        <div class="container mx-auto px-6 flex justify-between items-center">

            <!-- Brand / Logo -->
            <a href="{{ route('projects.index') }}" class="text-2xl font-bold text-blue-600 tracking-tight">
                DevPortal
            </a>

            <!-- Right Side Navigation -->
            <div class="flex items-center space-x-8">
                @auth
                    <!-- 🟢 THE SENIOR WAY: Use the partial for all links (including the Bell) -->
                    @include('layouts._nav-links')

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
                    <a href="{{ route('login') }}" @class([
                        'text-blue-600 font-bold' => request()->routeIs('login'),
                        'text-gray-600' => !request()->routeIs('login'),
                    ])>Login</a>
                   <a href="{{ route('register') }}" @class([
                        'text-blue-600 font-bold' => request()->routeIs('register'),
                        'text-gray-600' => !request()->routeIs('register'),
                    ])>Register</a>
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
