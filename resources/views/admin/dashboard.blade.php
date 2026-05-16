@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de Bord')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Agriculteurs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::where('role', 'agriculteur')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Parcelles</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Parcel::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-seedling text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Cultures</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Culture::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-leaf text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Produits</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-box text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded transition">
                    <i class="fas fa-user-check text-green-600 mr-3"></i>
                    <span class="text-green-800">Valider les comptes en attente</span>
                </a>
                <a href="{{ route('admin.cultures.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded transition">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    <span class="text-blue-800">Ajouter une culture</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded transition">
                    <i class="fas fa-plus-circle text-purple-600 mr-3"></i>
                    <span class="text-purple-800">Ajouter un produit</span>
                </a>
                <a href="{{ route('admin.regions.create') }}" class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded transition">
                    <i class="fas fa-map-marker-alt text-orange-600 mr-3"></i>
                    <span class="text-orange-800">Ajouter une région</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Activité Récente</h3>
            <div class="space-y-4">
                @php
                    $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(5)->get();
                    $recentParcels = \App\Models\Parcel::with('user')->orderBy('created_at', 'desc')->take(5)->get();
                @endphp

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Derniers inscrits</h4>
                    <ul class="space-y-2">
                        @foreach($recentUsers as $user)
                            <li class="flex items-center justify-between text-sm">
                                <span>{{ $user->name }}</span>
                                <span class="text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Dernières parcelles</h4>
                    <ul class="space-y-2">
                        @foreach($recentParcels as $parcel)
                            <li class="flex items-center justify-between text-sm">
                                <span>{{ $parcel->nom ?? 'Parcelle #'.$parcel->id }}</span>
                                <span class="text-gray-500">{{ $parcel->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    @php
        $pendingUsers = \App\Models\User::where('is_approved', false)->count();
    @endphp

    @if($pendingUsers > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800">Comptes en attente</h3>
                        <p class="text-yellow-700">{{ $pendingUsers }} agriculteur(s) en attente d'approbation</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index', ['filter' => 'pending']) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                    Voir la liste
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
