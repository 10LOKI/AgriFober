@extends('admin.layouts.app')

@section('title', 'Détail Agriculteur')
@section('page-title', 'Détail Agriculteur')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-agraire-500 to-agraire-700 flex items-center justify-center text-white text-2xl font-bold shadow-md shadow-agraire-600/20">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-slate-500 text-sm mt-0.5">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ $user->id === auth()->id() ? '#' : route('admin.users.destroy', $user) }}" 
                  onsubmit="return confirm('Supprimer cet utilisateur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors" 
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rôle</p>
                    <div class="mt-1.5">
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border
                            {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                            {{ $user->role === 'technicien' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                            {{ $user->role === 'agriculteur' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center">
                    <i class="fas fa-user-circle text-slate-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Région</p>
                    <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $user->region ?? 'Non définie' }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-amber-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Expérience</p>
                    <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $user->experience_level ?? 'Non défini' }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Statut</p>
                    @if($user->is_approved)
                        <p class="mt-1.5 text-sm font-bold text-agraire-700"><i class="fas fa-check-circle mr-1"></i>Approuvé</p>
                    @else
                        <p class="mt-1.5 text-sm font-bold text-amber-700"><i class="fas fa-clock mr-1"></i>En attente</p>
                    @endif
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Informations -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Informations</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-slate-500 text-sm">Nom d'utilisateur</dt>
                    <dd class="font-semibold text-slate-800 text-sm">{{ $user->username }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500 text-sm">Rôle</dt>
                    <dd>
                        <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full
                            {{ $user->role === 'admin' ? 'bg-red-50 text-red-700' : '' }}
                            {{ $user->role === 'technicien' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $user->role === 'agriculteur' ? 'bg-agraire-50 text-agraire-700' : '' }}">
                            {{ $user->role }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500 text-sm">Région</dt>
                    <dd class="font-semibold text-slate-800 text-sm">{{ $user->region ?? 'Non définie' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500 text-sm">Niveau d'expérience</dt>
                    <dd class="font-semibold text-slate-800 text-sm">{{ $user->experience_level ?? 'Non défini' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500 text-sm">Surface totale</dt>
                    <dd class="font-semibold text-slate-800 text-sm">{{ $user->surface_totale ? $user->surface_totale.' ha' : 'Non définie' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Statistiques</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <i class="fas fa-map text-emerald-600 text-lg mb-2"></i>
                    <p class="text-2xl font-bold text-slate-900">{{ $user->parcels()->count() }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Parcelles</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <i class="fas fa-robot text-blue-600 text-lg mb-2"></i>
                    <p class="text-2xl font-bold text-slate-900">{{ $user->interactionIas()->count() }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Interactions IA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Parcelles de l'utilisateur -->
    @if($user->parcels->count() > 0)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                <h3 class="text-sm font-bold text-slate-700"><i class="fas fa-map-marked-alt mr-2 text-slate-400"></i>Parcelles</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">Nom</th>
                        <th class="px-5 py-3 text-left">Surface</th>
                        <th class="px-5 py-3 text-left">Culture</th>
                        <th class="px-5 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($user->parcels as $parcel)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-5 py-3.5 font-medium text-slate-800">{{ $parcel->nom ?? 'Parcelle #'.$parcel->id }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $parcel->surface }} ha</td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm text-slate-700">{{ $parcel->culture->nom_commun ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full
                                    {{ $parcel->status === 'grow' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}
                                    {{ $parcel->status === 'harvest' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                    {{ $parcel->status === 'fallow' ? 'bg-slate-100 text-slate-700 border-slate-200' : '' }}">
                                    @if($parcel->status === 'grow') <i class="fas fa-leaf mr-1 text-xs"></i>En culture @endif
                                    @if($parcel->status === 'harvest') <i class="fas fa-wheat-awn mr-1 text-xs"></i>Récolté @endif
                                    @if($parcel->status === 'fallow') <i class="fas fa-pause mr-1 text-xs"></i>Repos @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-agraire-600 hover:text-agraire-800 font-semibold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
