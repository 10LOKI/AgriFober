@extends('admin.layouts.app')

@section('title', $culture->exists ? 'Modifier Culture' : 'Créer Culture')
@section('page-title', $culture->exists ? 'Modifier Culture' : 'Créer Culture')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ $culture->exists ? route('admin.cultures.update', $culture) : route('admin.cultures.store') }}">
        @csrf
        @if($culture->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom commun</label>
                <input type="text" name="nom_commun" value="{{ old('nom_commun', $culture->nom_commun) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('nom_commun') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom scientifique</label>
                <input type="text" name="nom_scientifique" value="{{ old('nom_scientifique', $culture->nom_scientifique) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('nom_scientifique') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choisir...</option>
                    <option value="fruit" {{ (old('type', $culture->type) == 'fruit') ? 'selected' : '' }}>Fruit</option>
                    <option value="legume" {{ (old('type', $culture->type) == 'legume') ? 'selected' : '' }}>Légume</option>
                    <option value="cereale" {{ (old('type', $culture->type) == 'cereale') ? 'selected' : '' }}>Céréale</option>
                    <option value="legumineuse" {{ (old('type', $culture->type) == 'legumineuse') ? 'selected' : '' }}>Légumineuse</option>
                    <option value="autre" {{ (old('type', $culture->type) == 'autre') ? 'selected' : '' }}>Autre</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Saison</label>
                <select name="saison" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choisir...</option>
                    <option value="printemps" {{ (old('saison', $culture->saison) == 'printemps') ? 'selected' : '' }}>Printemps</option>
                    <option value="ete" {{ (old('saison', $culture->saison) == 'ete') ? 'selected' : '' }}>Été</option>
                    <option value="automne" {{ (old('saison', $culture->saison) == 'automne') ? 'selected' : '' }}>Automne</option>
                    <option value="hiver" {{ (old('saison', $culture->saison) == 'hiver') ? 'selected' : '' }}>Hiver</option>
                    <option value="toute_annee" {{ (old('saison', $culture->saison) == 'toute_annee') ? 'selected' : '' }}>Toute l'année</option>
                </select>
                @error('saison') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Température min (°C)</label>
                <input type="number" name="temp_min" value="{{ old('temp_min', $culture->temp_min) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Température max (°C)</label>
                <input type="number" name="temp_max" value="{{ old('temp_max', $culture->temp_max) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">pH sol min</label>
                <input type="number" step="0.1" name="ph_sol_min" value="{{ old('ph_sol_min', $culture->ph_sol_min) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">pH sol max</label>
                <input type="number" step="0.1" name="ph_sol_max" value="{{ old('ph_sol_max', $culture->ph_sol_max) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Besoins eau (mm/cycle)</label>
                <input type="number" name="besoin_eau_cycle" value="{{ old('besoin_eau_cycle', $culture->besoin_eau_cycle) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de sol</label>
                <select name="soil_type"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Non défini</option>
                    <option value="argileux" {{ (old('soil_type', $culture->soil_type) == 'argileux') ? 'selected' : '' }}>Argileux</option>
                    <option value="sableux" {{ (old('soil_type', $culture->soil_type) == 'sableux') ? 'selected' : '' }}>Sableux</option>
                    <option value="limoneux" {{ (old('soil_type', $culture->soil_type) == 'limoneux') ? 'selected' : '' }}>Limoneux</option>
                    <option value="humifere" {{ (old('soil_type', $culture->soil_type) == 'humifere') ? 'selected' : '' }}>Humifère</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Conseils</label>
            <textarea name="conseils" rows="4"
                      class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('conseils', $culture->conseils) }}</textarea>
            @error('conseils') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.cultures.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                {{ $culture->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
