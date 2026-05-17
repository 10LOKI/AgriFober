@extends('admin.layouts.app')

@section('title', 'Détail Culture')
@section('page-title', 'Détail Culture')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $culture->nom_commun }}</h2>
            <p class="text-gray-600 italic">{{ $culture->nom_scientifique }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.cultures.edit', $culture) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ route('admin.cultures.destroy', $culture) }}" onsubmit="return confirm('Supprimer cette culture ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Type</h4>
            <span class="px-3 py-1 rounded-full text-sm
                {{ $culture->type === 'fruit' ? 'bg-purple-100 text-purple-800' : '' }}
                {{ $culture->type === 'legume' ? 'bg-green-100 text-green-800' : '' }}
                {{ $culture->type === 'cereale' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                {{ $culture->type }}
            </span>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Saison</h4>
            <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 capitalize">
                {{ $culture->saison }}
            </span>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Région</h4>
            <p class="text-gray-900">{{ $culture->region ?? 'Non spécifié' }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Type de sol</h4>
            <p class="text-gray-900">{{ $culture->soil_type ?? 'Non spécifié' }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Température min</h4>
            <p class="text-gray-900">{{ $culture->temp_min ?? '-' }}°C</p>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Température max</h4>
            <p class="text-gray-900">{{ $culture->temp_max ?? '-' }}°C</p>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Besoins en eau</h4>
            <p class="text-gray-900">{{ $culture->besoin_eau_cycle ?? '-' }} mm/cycle</p>
        </div>
    </div>

    @if($culture->conseils)
        <div class="mt-6 bg-green-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Conseils</h4>
            <p class="text-gray-800">{{ $culture->conseils }}</p>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.cultures.index') }}" class="text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
