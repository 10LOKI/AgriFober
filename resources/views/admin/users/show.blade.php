@extends('admin.layouts.app')

@section('title', 'Détail Agriculteur')
@section('page-title', 'Détail Agriculteur')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div class="flex items-center">
            <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center text-white text-2xl font-semibold mr-4">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ $user->id === auth()->id() ? '#' : route('admin.users.destroy', $user) }}" 
                  onsubmit="return confirm('Supprimer cet utilisateur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" 
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Infos -->
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Informations</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Nom d'utilisateur</dt>
                    <dd class="font-medium">{{ $user->username }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Rôle</dt>
                    <dd>
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'technicien' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'agriculteur' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ $user->role }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Région</dt>
                    <dd>{{ $user->region ?? 'Non définie' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Niveau d'expérience</dt>
                    <dd>{{ $user->experience_level ?? 'Non défini' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Surface totale</dt>
                    <dd>{{ $user->surface_totale ? $user->surface_totale.' ha' : 'Non définie' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Statut</dt>
                    <dd>
                        @if($user->is_approved)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Approuvé</span>
                        @else
                            <span class="text-yellow-600"><i class="fas fa-clock mr-1"></i>En attente</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Statistiques -->
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Statistiques</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Parcelles</dt>
                    <dd class="font-bold text-green-600">{{ $user->parcels()->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Interactions IA</dt>
                    <dd class="font-bold text-blue-600">{{ $user->interactionIas()->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Parcelles de l'utilisateur -->
    @if($user->parcels->count() > 0)
        <div class="mt-6 border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Parcelles</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Surface</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Culture</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($user->parcels as $parcel)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $parcel->nom ?? 'Parcelle #'.$parcel->id }}</td>
                            <td class="px-4 py-3 text-sm">{{ $parcel->surface }} ha</td>
                            <td class="px-4 py-3 text-sm">{{ $parcel->culture->nom_commun ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $parcel->status === 'grow' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $parcel->status === 'harvest' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $parcel->status === 'fallow' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $parcel->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.users.index') }}" class="text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
