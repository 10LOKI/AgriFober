@extends('admin.layouts.app')

@section('title', $product->exists ? 'Modifier Produit' : 'Créer Produit')
@section('page-title', $product->exists ? 'Modifier Produit' : 'Créer Produit')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom commercial</label>
                <input type="text" name="nom_commercial" value="{{ old('nom_commercial', $product->nom_commercial) }}" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('nom_commercial') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Composant actif</label>
                <input type="text" name="composant_actif" value="{{ old('composant_actif', $product->composant_actif) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('composant_actif') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Dosage recommandé</label>
                <input type="text" name="dosage_recommande" value="{{ old('dosage_recommande', $product->dosage_recommande) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('dosage_recommande') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Délai avant récolte (jours)</label>
                <input type="number" name="delai_avant_recolte" value="{{ old('delai_avant_recolte', $product->delai_avant_recolte) }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('delai_avant_recolte') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                <select name="type" required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="">Choisir...</option>
                    <option value="engrais" {{ (old('type', $product->type) == 'engrais') ? 'selected' : '' }}>Engrais</option>
                    <option value="pesticide" {{ (old('type', $product->type) == 'pesticide') ? 'selected' : '' }}>Pesticide</option>
                    <option value="fongicide" {{ (old('type', $product->type) == 'fongicide') ? 'selected' : '' }}>Fongicide</option>
                    <option value="herbicide" {{ (old('type', $product->type) == 'herbicide') ? 'selected' : '' }}>Herbicide</option>
                    <option value="biologique" {{ (old('type', $product->type) == 'biologique') ? 'selected' : '' }}>Biologique</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Avantages</label>
                <textarea name="avantages" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">{{ old('avantages', $product->avantages) }}</textarea>
                @error('avantages') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Méthode d'utilisation</label>
                <textarea name="usage_method" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">{{ old('usage_method', $product->usage_method) }}</textarea>
                @error('usage_method') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Instructions de sécurité</label>
                <textarea name="safety_instructions" rows="2"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">{{ old('safety_instructions', $product->safety_instructions) }}</textarea>
                @error('safety_instructions') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Image (optionnelle)</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('image') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                @if($product->exists && $product->image)
                    <div class="mt-3">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="Image" class="h-20 w-20 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-agraire-600 hover:bg-agraire-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-agraire-600/15 hover:shadow-lg transition-all">
                {{ $product->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
