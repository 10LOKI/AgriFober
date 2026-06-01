<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agrifober Admin')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full text-slate-800 antialiased font-sans">
<div class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ──────────────────────────────────────────────────── --}}
    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-50 w-64 flex flex-col border-r border-slate-800/60 shadow-xl
                  transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full"
           style="background: linear-gradient(180deg, #0b1f18 0%, #0f2920 40%, #0a1e17 100%)">

        {{-- Top radial glow --}}
        <div class="absolute top-0 left-0 right-0 h-32 pointer-events-none"
             style="background: radial-gradient(ellipse at 50% 0%, rgba(5,150,105,0.18) 0%, transparent 70%)"></div>

        {{-- Brand --}}
        <div class="relative flex items-center justify-between h-16 px-5 border-b border-slate-700/50 shrink-0">
            <div class="flex items-center space-x-2.5">
                <div class="p-2 rounded-xl text-white animate-glow-pulse"
                     style="background:linear-gradient(135deg,#059669,#0d9488);box-shadow:0 4px 14px rgba(5,150,105,0.3)">
                    <i class="fas fa-leaf text-sm"></i>
                </div>
                <span class="text-lg font-bold tracking-wide text-white">Agrifober</span>
                <span class="text-[9px] uppercase tracking-widest bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded-md font-bold border border-emerald-500/20">
                    Admin
                </span>
            </div>
            <button id="close-sidebar" class="lg:hidden text-slate-500 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Nav label --}}
        <div class="relative px-5 pt-5 pb-2">
            <p class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Menu principal</p>
        </div>

        {{-- Navigation --}}
        <nav class="relative flex-1 overflow-y-auto px-3 space-y-1 pb-4">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'check' => 'admin.dashboard',  'icon' => 'fa-home',     'label' => 'Dashboard'],
                    ['route' => 'admin.users.index','check' => 'admin.users.*',    'icon' => 'fa-users',    'label' => 'Agriculteurs'],
                    ['route' => 'admin.cultures.index','check' => 'admin.cultures.*','icon' => 'fa-seedling','label' => 'Cultures'],
                    ['route' => 'admin.products.index','check' => 'admin.products.*','icon' => 'fa-box',    'label' => 'Produits'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['check']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="group relative flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200
                          {{ $active ? 'nav-item-active text-white' : 'text-slate-400 hover:text-white' }}"
                   @if(!$active) style="&:hover { background: rgba(255,255,255,0.06); }" @endif
                   @if($active) aria-current="page" @endif>

                    @if($active)
                        <span class="absolute left-0 top-2 bottom-2 w-0.5 rounded-full bg-emerald-300/80"></span>
                    @endif

                    <div class="w-8 shrink-0">
                        <i class="fas {{ $item['icon'] }} text-base transition-transform duration-200 group-hover:scale-110
                                   {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-emerald-400' }}"></i>
                    </div>
                    <span class="tracking-tight">{{ $item['label'] }}</span>

                    @if(!$active)
                        <i class="fas fa-chevron-right text-[9px] ml-auto text-slate-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                    @endif
                </a>
            @endforeach

            {{-- AI Logs --}}
            @php $aiActive = request()->is('admin/ai-logs*'); @endphp
            <a href="/admin/ai-logs"
               class="group relative flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-200
                      {{ $aiActive ? 'nav-item-active text-white' : 'text-slate-400 hover:text-white' }}"
               @if($aiActive) aria-current="page" @endif>
                @if($aiActive)<span class="absolute left-0 top-2 bottom-2 w-0.5 rounded-full bg-emerald-300/80"></span>@endif
                <div class="w-8 shrink-0">
                    <i class="fas fa-robot text-base transition-transform duration-200 group-hover:scale-110
                              {{ $aiActive ? 'text-white' : 'text-slate-500 group-hover:text-emerald-400' }}"></i>
                </div>
                <span class="tracking-tight">Logs IA</span>
                @if(!$aiActive)<i class="fas fa-chevron-right text-[9px] ml-auto text-slate-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>@endif
            </a>
        </nav>

        <div class="relative p-4 border-t border-slate-800/60 text-xs text-center text-slate-600 shrink-0">
            &copy; 2026 Agrifober Management
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden"
         onclick="toggleSidebar()"></div>

    {{-- ── Main area ─────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 h-16 flex items-center shrink-0 z-30">
            <div class="w-full flex items-center justify-between px-6">

                <div class="flex items-center space-x-2">
                    <button id="open-sidebar"
                            class="text-slate-500 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition-colors duration-200 lg:hidden"
                            aria-label="Ouvrir le menu">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                        @yield('page-title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="/" target="_blank"
                       class="hidden md:inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors duration-200 group">
                        <i class="fas fa-external-link-alt mr-2 text-xs transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        Voir site public
                    </a>
                    <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center space-x-3 bg-slate-50 border border-slate-100 py-1 pl-1.5 pr-3 rounded-full shadow-sm">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-inner ring-2 ring-white"
                             style="background: linear-gradient(135deg, #059669, #0d9488)">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold text-slate-700 hidden sm:inline">
                            {{ Auth::user()->name ?? 'Admin' }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                        @csrf
                        <button type="submit"
                                aria-label="Déconnexion"
                                class="inline-flex items-center justify-center bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 p-2.5 rounded-xl text-sm font-medium transition-colors duration-200 hover:shadow-sm">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6 admin-surface" role="main">

            @if (session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm mb-6 animate-slide-up">
                    <i class="fas fa-check-circle text-emerald-500 text-lg shrink-0 mt-0.5"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm mb-6 animate-slide-up">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg shrink-0 mt-0.5"></i>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="max-w-7xl mx-auto h-full animate-slide-up">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const isOpen  = !sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', isOpen);
    sidebar.classList.toggle('translate-x-0',     !isOpen);
    overlay.classList.toggle('hidden', isOpen);
}
document.getElementById('open-sidebar').addEventListener('click', toggleSidebar);
document.getElementById('close-sidebar').addEventListener('click', toggleSidebar);
</script>
</body>
</html>
