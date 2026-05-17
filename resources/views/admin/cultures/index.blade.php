@extends('admin.layouts.app')

@section('title', 'Gestion Cultures')
@section('page-title', 'Gestion Cultures')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.cultures.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Ajouter Culture
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saison</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temp. Min/Max (°C)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Besoins Eau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Region</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($cultures as $culture)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $culture->nom_commun }}</div>
                        <div class="text-xs text-gray-500">{{ $culture->nom_scientifique }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $culture->type === 'fruit' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $culture->type === 'legume' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $culture->type === 'cereale' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $culture->type === 'legumineuse' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ $culture->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm capitalize">{{ $culture->saison }}</td>
                    <td class="px-6 py-4 text-sm">{{ $culture->region ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        {{ $culture->temp_min ?? '-' }}°C / {{ $culture->temp_max ?? '-' }}°C
                    </td>
                    <td class="px-6 py-4 text-sm">
                        {{ $culture->besoin_eau_cycle ? $culture->besoin_eau_cycle.' mm' : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.cultures.show', $culture) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.cultures.edit', $culture) }}" class="text-yellow-600 hover:text-yellow-900" title="Éditer">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.cultures.destroy', $culture) }}" class="inline" onsubmit="return confirm('Supprimer cette culture ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        Aucune culture définie.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($cultures->hasPages())
    <div class="mt-6">
        {{ $cultures->links() }}
    </div>
@endif
@endsection
