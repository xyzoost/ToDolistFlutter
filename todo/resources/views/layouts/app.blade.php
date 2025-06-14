<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #ffffff;
            --secondary-bg: #f8fafc;
            --accent-color: #4f46e5;
            --accent-hover: #4338ca;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--secondary-bg);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .sidebar {
            background-color: var(--primary-bg);
            border-right: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            transition: all 0.2s ease;
            color: var(--text-secondary);
        }

        .nav-link:hover {
            color: var(--accent-color);
            background-color: #f1f5f9;
            transform: translateX(3px);
        }

        .nav-link.active {
            color: var(--accent-color);
            background-color: #eef2ff;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--accent-color);
            transition: all 0.2s ease;
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .task-card {
            background-color: var(--primary-bg);
            border-left: 4px solid var(--accent-color);
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .completed {
            opacity: 0.8;
            border-left-color: #10b981;
        }

        .user-avatar {
            background-color: #e0e7ff;
            color: var(--accent-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar w-64 flex-shrink-0 p-6 hidden md:block">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-2xl font-bold text-indigo-600">Todo<span class="text-slate-800">App</span></h1>
        </div>

        <nav class="space-y-1">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 nav-link rounded-lg @if(request()->routeIs('dashboard')) active @endif">
                        <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.index') }}" class="flex items-center p-3 nav-link rounded-lg @if(request()->routeIs('tasks.*')) active @endif">
                        <i class="fas fa-tasks w-5 mr-3 text-center"></i>
                        <span>My Tasks</span>
                    </a>
                </li>
            </ul>

            <div class="mt-10 pt-6 border-t border-gray-200">
                <div class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="user-avatar w-9 h-9 rounded-full flex items-center justify-center mr-3 font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="flex items-center p-3 nav-link rounded-lg w-full text-left hover:bg-slate-50">
                        <i class="fas fa-sign-out-alt w-5 mr-3 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Mobile sidebar toggle -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="sidebarToggle" class="p-2 rounded-lg bg-white text-indigo-600 shadow-md hover:shadow-lg transition-shadow">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Mobile sidebar -->
    <div id="mobileSidebar" class="sidebar fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full md:hidden transition-transform duration-300 ease-in-out">
        <div class="p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-indigo-600">Todo<span class="text-slate-800">App</span></h1>
                <button id="closeSidebar" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 nav-link rounded-lg @if(request()->routeIs('dashboard')) active @endif">
                            <i class="fas fa-tachometer-alt w-5 mr-3 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tasks.index') }}" class="flex items-center p-3 nav-link rounded-lg @if(request()->routeIs('tasks.*')) active @endif">
                            <i class="fas fa-tasks w-5 mr-3 text-center"></i>
                            <span>My Tasks</span>
                        </a>
                    </li>
                </ul>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center p-3 rounded-lg">
                        <div class="user-avatar w-9 h-9 rounded-full flex items-center justify-center mr-3 font-medium">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="flex items-center p-3 nav-link rounded-lg w-full text-left hover:bg-slate-50">
                            <i class="fas fa-sign-out-alt w-5 mr-3 text-center"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 overflow-auto bg-slate-50">
        <div class="p-6 max-w-6xl mx-auto">
            @yield('content')
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeSidebar = document.getElementById('closeSidebar');

        sidebarToggle.addEventListener('click', function() {
            mobileSidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });

        closeSidebar.addEventListener('click', function() {
            mobileSidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            mobileSidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // Add animation to content when loaded
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.main-content');
            if (content) {
                content.classList.add('animate-fade-in');
            }
        });
    </script>
</body>
</html>
