@extends('admin.layouts.app')

@section('title', 'Gestion Produits')
@section('page-title', 'Gestion Produits')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Ajouter Produit
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom Commercial</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Composant Actif</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Délai Récolte</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $product->nom_commercial }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $product->type === 'engrais' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $product->type === 'pesticide' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $product->type === 'fongicide' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $product->type === 'herbicide' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $product->type === 'biologique' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ $product->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $product->composant_actif ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        {{ $product->delai_avant_recolte ? $product->delai_avant_recolte.' jours' : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900" title="Éditer">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Supprimer ce produit ?');">
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
                        Aucun produit défini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endif
@endsection
