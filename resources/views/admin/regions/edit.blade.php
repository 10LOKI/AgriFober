@extends('admin.layouts.app')

@section('title', $region->exists ? 'Modifier Région' : 'Créer Région')
@section('page-title', $region->exists ? 'Modifier Région' : 'Créer Région')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ $region->exists ? route('admin.regions.update', $region) : route('admin.regions.store') }}">
        @csrf
        @if($region->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la région</label>
                <input type="text" name="nom" value="{{ old('nom', $region->nom) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                <input type="text" name="code" value="{{ old('code', $region->code) }}" required maxlength="10"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                <input type="text" name="pays" value="{{ old('pays', $region->pays) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('pays') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.regions.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                {{ $region->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
