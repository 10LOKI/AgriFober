<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agrifober Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-active {
            margin-left: 0;
        }
        .sidebar-inactive {
            margin-left: -256px;
        }
        @media (min-width: 1024px) {
            .sidebar-inactive {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 lg:translate-x-0 sidebar-active">
            <div class="flex items-center justify-between h-16 px-6 border-b">
                <h1 class="text-xl font-bold text-green-700">Agrifober</h1>
                <button id="close-sidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="mt-6 px-4">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-800 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users mr-3"></i>
                        Agriculteurs
                    </a>
                    <a href="{{ route('admin.cultures.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.cultures.*') ? 'bg-green-100 text-green-800 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-seedling mr-3"></i>
                        Cultures
                    </a>
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-green-100 text-green-800 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-box mr-3"></i>
                        Produits
                    </a>
                    <a href="{{ route('admin.regions.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.regions.*') ? 'bg-green-100 text-green-800 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-map mr-3"></i>
                        Régions
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button id="open-sidebar" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-lg font-semibold text-gray-800">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700 hidden md:inline">
                                {{ Auth::user()->name ?? 'Admin' }}
                            </span>
                        </div>

                        <a href="/" target="_blank" class="text-sm text-green-600 hover:text-green-800 hidden md:block">
                            Voir site
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('sidebar-active');
            sidebar.classList.toggle('sidebar-inactive');
            overlay.classList.toggle('hidden');
        }

        document.getElementById('open-sidebar').addEventListener('click', toggleSidebar);
        document.getElementById('close-sidebar').addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
