@extends('admin.layouts.app')

@section('title', 'Détail Produit')
@section('page-title', 'Détail Produit')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $product->nom_commercial }}</h2>
            <p class="text-gray-600">{{ $product->type }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold mb-4">Informations</h3>
            <dl class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">Composant actif</dt>
                    <dd class="font-medium">{{ $product->composant_actif ?? '-' }}</dd>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">Dosage recommandé</dt>
                    <dd class="font-medium">{{ $product->dosage_recommande ?? '-' }}</dd>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">Délai avant récolte</dt>
                    <dd class="font-medium">{{ $product->delai_avant_recolte ? $product->delai_avant_recolte.' jours' : '-' }}</dd>
                </div>
            </dl>
        </div>

        @if($product->image)
        <div>
            <h3 class="text-lg font-semibold mb-4">Image</h3>
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->nom_commercial }}" class="rounded-lg max-w-xs">
        </div>
        @endif
    </div>

    @if($product->avantages)
        <div class="mt-6 bg-green-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Avantages</h4>
            <p class="text-gray-800">{{ $product->avantages }}</p>
        </div>
    @endif

    @if($product->usage_method)
        <div class="mt-4 bg-blue-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Méthode d'utilisation</h4>
            <p class="text-gray-800">{{ $product->usage_method }}</p>
        </div>
    @endif

    @if($product->safety_instructions)
        <div class="mt-4 bg-red-50 p-4 rounded">
            <h4 class="font-semibold mb-2">Instructions de sécurité</h4>
            <p class="text-gray-800">{{ $product->safety_instructions }}</p>
        </div>
    @endif

    @if($product->cultures->count() > 0)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Cultures compatibles</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($product->cultures as $culture)
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                        {{ $culture->nom_commun }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.products.index') }}" class="text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
