@extends('admin.layouts.app')

@section('title', 'Gestion Produits')
@section('page-title', 'Gestion Produits')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center bg-agraire-600 hover:bg-agraire-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-agraire-600/15 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
        <i class="fas fa-plus mr-2 text-sm"></i>Ajouter Produit
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/80 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                <tr>
                    <th class="px-6 py-3.5 text-left">Nom Commercial</th>
                    <th class="px-6 py-3.5 text-left">Type</th>
                    <th class="px-6 py-3.5 text-left">Composant Actif</th>
                    <th class="px-6 py-3.5 text-left">Délai Récolte</th>
                    <th class="px-6 py-3.5 text-right pr-6">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $product->nom_commercial }}</div>
                            <div class="text-xs text-gray-400 truncate max-w-sm">{{ $product->description ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border tracking-wide
                                {{ $product->type === 'engrais' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                {{ $product->type === 'pesticide' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                {{ $product->type === 'fongicide' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                                {{ $product->type === 'herbicide' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                {{ $product->type === 'biologique' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}">
                                {{ $product->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->composant_actif ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $product->delai_avant_recolte ?? '—' }} {{ $product->delai_avant_recolte ? 'jours' : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.products.show', $product) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Éditer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline m-0" onsubmit="return confirm('Supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-box-open text-2xl text-slate-300"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Aucun produit défini</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($products->hasPages())
    <div class="mt-6 flex flex-wrap items-center justify-center gap-1.5">
        @foreach($products->links()->elements as $element)
            @if(is_string($element))
                <span class="px-3 py-2 text-sm text-gray-400">{{ $element }}</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    <a href="{{ $url }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-all duration-150
                        {{ $page == $products->currentPage()
                            ? 'bg-agraire-600 text-white shadow-md shadow-agraire-600/15'
                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-slate-50 hover:border-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach
            @endif
        @endforeach
    </div>
@endif
@endsection
