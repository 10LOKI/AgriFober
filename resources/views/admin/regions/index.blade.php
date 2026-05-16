@extends('admin.layouts.app')

@section('title', 'Gestion Régions')
@section('page-title', 'Gestion Régions')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.regions.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Ajouter Région
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pays</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agriculteurs</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($regions as $region)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $region->nom }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $region->code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $region->pays }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                            {{ $region->users_count }} agriculteur(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.regions.edit', $region) }}" class="text-yellow-600 hover:text-yellow-900" title="Éditer">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.regions.destroy', $region) }}" class="inline" onsubmit="return confirm('Supprimer cette région ?');">
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
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        Aucune région définie.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($regions->hasPages())
    <div class="mt-6">
        {{ $regions->links() }}
    </div>
@endif
@endsection
