<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Management Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 py-4 mb-8 shadow-sm">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="{{ route('projects.index') }}" class="text-2xl font-bold text-blue-600 tracking-tight">
                DevPortal
            </a>

            <div class="flex items-center space-x-8">
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
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container mx-auto px-6 pb-12">

        <!-- Flash Success Message -->
        @if (session('success'))
            <div id="flash-msg"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm transition-opacity duration-500">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
            <script>
                // Professional touch: Auto-hide flash message after 3 seconds
                setTimeout(() => {
                    const msg = document.getElementById('flash-msg');
                    if (msg) {
                        msg.style.opacity = '0';
                        setTimeout(() => msg.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        @yield('content')
    </main>

</body>

</html>
