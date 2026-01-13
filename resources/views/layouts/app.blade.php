<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg py-4 mb-8">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="{{ route('projects.index') }}" class="text-2xl font-bold text-blue-600">DevPortal</a>
            <div class="space-x-6">
                <a href="{{ route('projects.index') }}"
                    class="text-gray-600 hover:text-blue-500 transition">Projects</a>
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-blue-500 transition">Tasks</a>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <div class="container mx-auto px-6">
        <!-- Success Message Component -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-md" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </div>

</body>

</html>
