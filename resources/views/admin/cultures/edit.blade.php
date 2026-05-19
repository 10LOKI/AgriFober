@extends('admin.layouts.app')

@section('title', $culture->exists ? 'Modifier Culture' : 'Créer Culture')
@section('page-title', $culture->exists ? 'Modifier Culture' : 'Créer Culture')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ $culture->exists ? route('admin.cultures.update', $culture) : route('admin.cultures.store') }}">
        @csrf
        @if($culture->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom commun</label>
                <input type="text" name="nom_commun" value="{{ old('nom_commun', $culture->nom_commun) }}" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('nom_commun') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom scientifique</label>
                <input type="text" name="nom_scientifique" value="{{ old('nom_scientifique', $culture->nom_scientifique) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('nom_scientifique') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                <select name="type" required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="">Choisir...</option>
                    <option value="fruit" {{ (old('type', $culture->type) == 'fruit') ? 'selected' : '' }}>Fruit</option>
                    <option value="legume" {{ (old('type', $culture->type) == 'legume') ? 'selected' : '' }}>Légume</option>
                    <option value="cereale" {{ (old('type', $culture->type) == 'cereale') ? 'selected' : '' }}>Céréale</option>
                    <option value="legumineuse" {{ (old('type', $culture->type) == 'legumineuse') ? 'selected' : '' }}>Légumineuse</option>
                    <option value="autre" {{ (old('type', $culture->type) == 'autre') ? 'selected' : '' }}>Autre</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Saison</label>
                <select name="saison" required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="">Choisir...</option>
                    <option value="printemps" {{ (old('saison', $culture->saison) == 'printemps') ? 'selected' : '' }}>Printemps</option>
                    <option value="ete" {{ (old('saison', $culture->saison) == 'ete') ? 'selected' : '' }}>Été</option>
                    <option value="automne" {{ (old('saison', $culture->saison) == 'automne') ? 'selected' : '' }}>Automne</option>
                    <option value="hiver" {{ (old('saison', $culture->saison) == 'hiver') ? 'selected' : '' }}>Hiver</option>
                    <option value="toute_annee" {{ (old('saison', $culture->saison) == 'toute_annee') ? 'selected' : '' }}>Toute l'année</option>
                </select>
                @error('saison') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Région</label>
                <input type="text" name="region" value="{{ old('region', $culture->region) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('region') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Température min (°C)</label>
                <input type="number" name="temp_min" value="{{ old('temp_min', $culture->temp_min) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Température max (°C)</label>
                <input type="number" name="temp_max" value="{{ old('temp_max', $culture->temp_max) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">pH sol min</label>
                <input type="number" step="0.1" name="ph_sol_min" value="{{ old('ph_sol_min', $culture->ph_sol_min) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">pH sol max</label>
                <input type="number" step="0.1" name="ph_sol_max" value="{{ old('ph_sol_max', $culture->ph_sol_max) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Besoins eau (mm/cycle)</label>
                <input type="number" name="besoin_eau_cycle" value="{{ old('besoin_eau_cycle', $culture->besoin_eau_cycle) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Type de sol</label>
                <select name="soil_type"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="">Non défini</option>
                    <option value="argileux" {{ (old('soil_type', $culture->soil_type) == 'argileux') ? 'selected' : '' }}>Argileux</option>
                    <option value="sableux" {{ (old('soil_type', $culture->soil_type) == 'sableux') ? 'selected' : '' }}>Sableux</option>
                    <option value="limoneux" {{ (old('soil_type', $culture->soil_type) == 'limoneux') ? 'selected' : '' }}>Limoneux</option>
                    <option value="humifere" {{ (old('soil_type', $culture->soil_type) == 'humifere') ? 'selected' : '' }}>Humifère</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Conseils</label>
            <textarea name="conseils" rows="4"
                      class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">{{ old('conseils', $culture->conseils) }}</textarea>
            @error('conseils') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.cultures.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-agraire-600 hover:bg-agraire-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-agraire-600/15 hover:shadow-lg transition-all">
                {{ $culture->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
