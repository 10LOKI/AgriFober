<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agrifober Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        agraire: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                        },
                        ambre: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        neutre: {
                            800: '#1f2937',
                            900: '#111827',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .sidebar-active {
            margin-left: 0;
        }
        .sidebar-inactive {
            margin-left: -264px;
        }
        @media (min-width: 1024px) {
            .sidebar-inactive {
                margin-left: 0;
            }
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full text-neutre-800 antialiased font-sans">
    <div class="flex h-screen overflow-hidden bg-gray-50">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-66 bg-neutre-900 text-gray-300 shadow-xl transform transition-all duration-300 ease-in-out lg:translate-x-0 sidebar-inactive flex flex-col border-r border-neutre-800">
            <div class="flex items-center justify-between h-16 px-6 border-b border-neutre-800 bg-neutre-900/50 backdrop-blur-md">
                <div class="flex items-center space-x-2">
                    <div class="bg-agraire-600 p-2 rounded-lg text-white shadow-md shadow-agraire-600/20">
                        <i class="fas fa-leaf text-md"></i>
                    </div>
                    <span class="text-xl font-bold tracking-wide text-white">Agrifober</span>
                    <span class="text-[10px] uppercase tracking-widest bg-ambre-500/20 text-ambre-500 px-1.5 py-0.5 rounded font-semibold">Admin</span>
                </div>
                <button id="close-sidebar" class="lg:hidden text-gray-400 hover:text-white p-1 rounded-md hover:bg-neutre-800 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Liens de Navigation enrichis -->
            <nav class="flex-1 overflow-y-auto mt-4 px-4 space-y-1.5">
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-agraire-600 text-white font-medium shadow-md shadow-agraire-600/10' : 'hover:bg-neutre-800 hover:text-white' }}">
                    <i class="fas fa-home mr-3 text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-agraire-600' }}"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-agraire-600 text-white font-medium shadow-md shadow-agraire-600/10' : 'hover:bg-neutre-800 hover:text-white' }}">
                    <i class="fas fa-users mr-3 text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400 group-hover:text-agraire-600' }}"></i>
                    Agriculteurs
                </a>
                
                <a href="{{ route('admin.cultures.index') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.cultures.*') ? 'bg-agraire-600 text-white font-medium shadow-md shadow-agraire-600/10' : 'hover:bg-neutre-800 hover:text-white' }}">
                    <i class="fas fa-seedling mr-3 text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.cultures.*') ? 'text-white' : 'text-gray-400 group-hover:text-agraire-600' }}"></i>
                    Cultures
                </a>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-agraire-600 text-white font-medium shadow-md shadow-agraire-600/10' : 'hover:bg-neutre-800 hover:text-white' }}">
                    <i class="fas fa-box mr-3 text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-gray-400 group-hover:text-agraire-600' }}"></i>
                    Produits
                </a>

                <!-- Alignement avec tes besoins applicatifs récents -->
                <a href="/admin/ai-logs" 
                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('admin/ai-logs*') ? 'bg-agraire-600 text-white font-medium shadow-md shadow-agraire-600/10' : 'hover:bg-neutre-800 hover:text-white' }}">
                    <i class="fas fa-robot mr-3 text-lg transition-transform duration-200 group-hover:scale-110 {{ request()->is('admin/ai-logs*') ? 'text-white' : 'text-gray-400 group-hover:text-agraire-600' }}"></i>
                    Logs IA
                </a>
            </nav>

            <div class="p-4 border-t border-neutre-800 bg-neutre-950/40 text-xs text-center text-gray-500">
                &copy; 2026 Agrifober Management
            </div>
        </aside>

        <!-- Overlay Mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-neutre-900/40 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main content area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center shrink-0">
                <div class="w-full flex items-center justify-between px-6">
                    
                    <div class="flex items-center space-x-4">
                        <button id="open-sidebar" class="lg:hidden text-gray-500 hover:text-neutre-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-bold text-neutre-900 tracking-tight">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="/" target="_blank" class="hidden md:inline-flex items-center text-sm font-medium text-agraire-600 hover:text-agraire-700 bg-agraire-50 hover:bg-agraire-100 px-3 py-1.5 rounded-lg transition-colors group">
                            <i class="fas fa-external-link-alt mr-2 text-xs transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                            Voir site public
                        </a>

                        <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                        <div class="flex items-center space-x-3 bg-gray-50 border border-gray-100 py-1 pl-1.5 pr-3 rounded-full">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-agraire-600 to-emerald-500 flex items-center justify-center text-white font-semibold text-sm shadow-sm ring-2 ring-white">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-neutre-900 hidden sm:inline">
                                {{ Auth::user()->name ?? 'Admin' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 p-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:shadow-sm" title="Déconnexion">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
                
                @if (session('success'))
                    <div class="flex items-start bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-xl shadow-sm mb-6 animate-fadeIn transition-all">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-start bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-xl shadow-sm mb-6 animate-fadeIn transition-all">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="h-full">
                    @yield('content')
                </div>
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