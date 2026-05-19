@extends('admin.layouts.app')

@section('title', 'Détail Produit')
@section('page-title', 'Détail Produit')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-700 shadow-inner">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $product->nom_commercial }}</h2>
                @if($product->description)
                    <p class="text-slate-500 text-sm mt-0.5 line-clamp-2">{{ $product->description }}</p>
                @endif
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Type</h4>
            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border
                {{ $product->type === 'engrais' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                {{ $product->type === 'pesticide' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                {{ $product->type === 'fongicide' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                {{ $product->type === 'herbicide' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                {{ $product->type === 'biologique' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}">
                {{ $product->type }}
            </span>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Composant actif</h4>
            <p class="text-slate-900 font-semibold">{{ $product->composant_actif ?? '—' }}</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dosage recommandé</h4>
            <p class="text-slate-900 font-semibold">{{ $product->dosage_recommande ?? '—' }}</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Délai avant récolte</h4>
            <p class="text-slate-900 font-semibold">{{ $product->delai_avant_recolte ?? '—' }} {{ $product->delai_avant_recolte ? 'jours' : '' }}</p>
        </div>
    </div>

    @if($product->avantages)
        <div class="bg-agraire-50/60 border border-agraire-100 p-5 rounded-xl mb-4">
            <h4 class="text-xs font-bold text-agraire-800 uppercase tracking-wider mb-2">Avantages</h4>
            <p class="text-slate-700 leading-relaxed">{{ $product->avantages }}</p>
        </div>
    @endif

    @if($product->usage_method)
        <div class="bg-blue-50/60 border border-blue-100 p-5 rounded-xl mb-4">
            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Méthode d'utilisation</h4>
            <p class="text-slate-700 leading-relaxed">{{ $product->usage_method }}</p>
        </div>
    @endif

    @if($product->safety_instructions)
        <div class="bg-red-50/60 border border-red-100 p-5 rounded-xl mb-4">
            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-2">Instructions de sécurité</h4>
            <p class="text-slate-700 leading-relaxed">{{ $product->safety_instructions }}</p>
        </div>
    @endif

    @if($product->cultures->count() > 0)
        <div class="mt-6 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                <h4 class="text-sm font-bold text-slate-700"><i class="fas fa-seedling mr-2 text-slate-400"></i>Cultures compatibles</h4>
            </div>
            <div class="p-5 flex flex-wrap gap-2">
                @foreach($product->cultures as $culture)
                    <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium border border-indigo-100">
                        {{ $culture->nom_commun }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-agraire-600 hover:text-agraire-800 font-semibold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
