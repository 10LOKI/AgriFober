@extends('admin.layouts.app')

@section('title', $product->exists ? 'Modifier Produit' : 'Créer Produit')
@section('page-title', $product->exists ? 'Modifier Produit' : 'Créer Produit')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom commercial</label>
                <input type="text" name="nom_commercial" value="{{ old('nom_commercial', $product->nom_commercial) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('nom_commercial') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Composant actif</label>
                <input type="text" name="composant_actif" value="{{ old('composant_actif', $product->composant_actif) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('composant_actif') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dosage recommandé</label>
                <input type="text" name="dosage_recommande" value="{{ old('dosage_recommande', $product->dosage_recommande) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('dosage_recommande') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Délai avant récolte (jours)</label>
                <input type="number" name="delai_avant_recolte" value="{{ old('delai_avant_recolte', $product->delai_avant_recolte) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('delai_avant_recolte') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Choisir...</option>
                    <option value="engrais" {{ (old('type', $product->type) == 'engrais') ? 'selected' : '' }}>Engrais</option>
                    <option value="pesticide" {{ (old('type', $product->type) == 'pesticide') ? 'selected' : '' }}>Pesticide</option>
                    <option value="fongicide" {{ (old('type', $product->type) == 'fongicide') ? 'selected' : '' }}>Fongicide</option>
                    <option value="herbicide" {{ (old('type', $product->type) == 'herbicide') ? 'selected' : '' }}>Herbicide</option>
                    <option value="biologique" {{ (old('type', $product->type) == 'biologique') ? 'selected' : '' }}>Biologique</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Avantages</label>
                <textarea name="avantages" rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('avantages', $product->avantages) }}</textarea>
                @error('avantages') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Méthode d'utilisation</label>
                <textarea name="usage_method" rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('usage_method', $product->usage_method) }}</textarea>
                @error('usage_method') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructions de sécurité</label>
                <textarea name="safety_instructions" rows="2"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('safety_instructions', $product->safety_instructions) }}</textarea>
                @error('safety_instructions') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image (optionnelle)</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                @if($product->exists && $product->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="Image" class="h-20 w-20 object-cover rounded">
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                {{ $product->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
