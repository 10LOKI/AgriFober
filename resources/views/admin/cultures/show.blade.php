@extends('admin.layouts.app')

@section('title', 'Détail Culture')
@section('page-title', 'Détail Culture')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-agraire-100 flex items-center justify-center text-agraire-700 shadow-inner">
                <i class="fas fa-seedling text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $culture->nom_commun }}</h2>
                <p class="text-slate-500 italic">{{ $culture->nom_scientifique ?? '—' }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.cultures.edit', $culture) }}" class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <form method="POST" action="{{ route('admin.cultures.destroy', $culture) }}" onsubmit="return confirm('Supprimer cette culture ?');">
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
                {{ $culture->type === 'fruit' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                {{ $culture->type === 'legume' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}
                {{ $culture->type === 'cereale' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                {{ $culture->type === 'legumineuse' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}">
                {{ $culture->type }}
            </span>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Saison</h4>
            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100 capitalize">
                {{ $culture->saison }}
            </span>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Région</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->region ?? 'Non spécifié' }}</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Type de sol</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->soil_type ?? 'Non spécifié' }}</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Température min</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->temp_min ?? '-' }}°C</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Température max</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->temp_max ?? '-' }}°C</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Besoins en eau</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->besoin_eau_cycle ?? '-' }} mm/cycle</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">pH min sol</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->ph_sol_min ?? '—' }}</p>
        </div>

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">pH max sol</h4>
            <p class="text-slate-900 font-semibold">{{ $culture->ph_sol_max ?? '—' }}</p>
        </div>
    </div>

    @if($culture->conseils)
        <div class="bg-agraire-50/60 border border-agraire-100 p-5 rounded-xl mb-6">
            <h4 class="text-xs font-bold text-agraire-800 uppercase tracking-wider mb-2">Conseils de culture</h4>
            <p class="text-slate-700 leading-relaxed">{{ $culture->conseils }}</p>
        </div>
    @endif

    @if($culture->parcels->count() > 0 || $culture->products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            @if($culture->parcels->count() > 0)
            <div class="bg-slate-50 rounded-xl border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                    <h4 class="text-sm font-bold text-slate-700"><i class="fas fa-map-marked-alt mr-2 text-slate-400"></i>Parcelles</h4>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach($culture->parcels as $parcel)
                        <li class="px-5 py-3 text-sm text-slate-700 flex justify-between">
                            <span>{{ $parcel->nom ?? 'Parcelle #'.$parcel->id }}</span>
                            <span class="text-xs text-slate-400">{{ $parcel->user->name ?? '—' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($culture->products->count() > 0)
            <div class="bg-slate-50 rounded-xl border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                    <h4 class="text-sm font-bold text-slate-700"><i class="fas fa-box text-slate-400 mr-2"></i>Produits associés</h4>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach($culture->products as $product)
                        <li class="px-5 py-3 text-sm text-slate-700">{{ $product->nom_commercial }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('admin.cultures.index') }}" class="inline-flex items-center text-agraire-600 hover:text-agraire-800 font-semibold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection
