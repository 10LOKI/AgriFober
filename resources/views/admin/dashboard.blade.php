@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de Bord')

@section('content')
<div class="space-y-8">
    
    <!-- Stats Cards (Alignées sur la charte graphique Agrifober) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card Agriculteurs -->
        <div class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1.5">
                    <p class="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Agriculteurs</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['users'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-sky-50 text-sky-600 border-sky-100/50">
                    <i class="fas fa-users text-xl transition-transform duration-300 group-hover:scale-110"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-sky-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
        </div>

        <!-- Card Parcelles -->
        <div class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1.5">
                    <p class="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Parcelles</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['parcels'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-emerald-50 text-emerald-600 border-emerald-100/50">
                    <i class="fas fa-seedling text-xl transition-transform duration-300 group-hover:scale-110"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
        </div>

        <!-- Card Cultures -->
        <div class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1.5">
                    <p class="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Cultures</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['cultures'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-indigo-50 text-indigo-600 border-indigo-100/50">
                    <i class="fas fa-leaf text-xl transition-transform duration-300 group-hover:scale-110"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-indigo-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
        </div>

        <!-- Card Produits -->
        <div class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="space-y-1.5">
                    <p class="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Produits</p>
                    <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['products'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-amber-50 text-amber-600 border-amber-100/50">
                    <i class="fas fa-box text-xl transition-transform duration-300 group-hover:scale-110"></i>
                </div>
            </div>
            <div class="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
        </div>
    </div>

    <!-- Section: Approbations en attente (Bannière épurée et isolée du flux des grilles) -->
    @if($stats['pending_users_count'] > 0)
        <div class="bg-amber-50/60 backdrop-blur-sm border border-amber-200/60 rounded-2xl p-6 transition-all duration-300 hover:shadow-md">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-amber-500 text-white p-3 rounded-xl shadow-md shadow-amber-500/10">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-md font-bold text-slate-900">Comptes en attente de validation</h3>
                        <p class="text-sm text-slate-600 font-medium">{{ $stats['pending_users_count'] }} agriculteur(s) requiert votre approbation.</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index', ['filter' => 'pending']) }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-sm shadow-sm hover:bg-amber-700 transition-all duration-200 shrink-0">
                    <i class="fas fa-user-check mr-2 text-xs"></i>
                    Traiter les demandes
                </a>
            </div>
        </div>
    @endif

    <!-- Panneaux d'Activités & Actions Rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Actions Rapides -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-md font-bold text-slate-900 tracking-tight mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                        <div class="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                            <i class="fas fa-user-shield text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Gérer les comptes</span>
                    </a>
                    <a href="{{ route('admin.cultures.create') }}" class="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                        <div class="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Ajouter une culture</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                        <div class="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                            <i class="fas fa-box text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Ajouter un produit</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Flux d'Activités Récentes -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-md font-bold text-slate-900 tracking-tight mb-4">Activité Récente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Derniers Inscrits -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Derniers inscrits</h4>
                    <ul class="divide-y divide-slate-100">
                        @forelse($stats['users'] as $user)
                            <li class="flex items-center justify-between py-3 text-sm">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $user->name }}</span>
                                </div>
                                <span class="text-xs text-slate-400 font-medium">{{ $user->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-slate-400">Aucune inscription récente.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Dernières Parcelles -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dernières parcelles</h4>
                    <ul class="divide-y divide-slate-100">
                        @forelse($stats['recent_parcels'] as $parcel)
                            <li class="flex items-center justify-between py-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-slate-800">{{ $parcel->nom ?? 'Parcelle #'.$parcel->id }}</span>
                                </div>
                                <span class="text-xs text-slate-400 font-medium">{{ $parcel->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-slate-400">Aucune parcelle ajoutée.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Répartition Géographique -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Agriculteurs par Région -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-md font-bold text-slate-900 tracking-tight">Agriculteurs par région</h3>
                <i class="fas fa-map-marked-alt text-slate-300"></i>
            </div>
            @if($stats['users_by_region']->count() > 0)
                <div class="space-y-3.5">
                    @foreach($stats['users_by_region'] as $item)
                        <div>
                            <div class="flex justify-between text-sm mb-1.5 font-medium">
                                <span class="text-slate-700">{{ $item->region ?? 'Non spécifiée' }}</span>
                                <span class="text-emerald-600 font-bold">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $stats['users'] > 0 ? ($item->count / $stats['users']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-sm py-4">Aucune donnée géographique enregistrée.</p>
            @endif
        </div>

        <!-- Cultures par Région -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-md font-bold text-slate-900 tracking-tight">Cultures par région</h3>
                <i class="fas fa-chart-pie text-slate-300"></i>
            </div>
            @if($stats['cultures_by_region']->count() > 0)
                <div class="space-y-3.5">
                    @foreach($stats['cultures_by_region'] as $item)
                        <div>
                            <div class="flex justify-between text-sm mb-1.5 font-medium">
                                <span class="text-slate-700">{{ $item->region ?? 'Non spécifiée' }}</span>
                                <span class="text-indigo-600 font-bold">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $stats['cultures'] > 0 ? ($item->count / $stats['cultures']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-sm py-4">Aucune culture répertoriée par région.</p>
            @endif
        </div>

    </div>
</div>
@endsection