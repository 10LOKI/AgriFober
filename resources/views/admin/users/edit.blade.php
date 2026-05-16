@extends('admin.layouts.app')

@section('title', $user->exists ? 'Modifier Agriculteur' : 'Créer Agriculteur')
@section('page-title', $user->exists ? 'Modifier Agriculteur' : 'Créer Agriculteur')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if($user->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('username') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                <select name="role" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="agriculteur" {{ (old('role', $user->role) == 'agriculteur') ? 'selected' : '' }}>Agriculteur</option>
                    <option value="technicien" {{ (old('role', $user->role) == 'technicien') ? 'selected' : '' }}>Technicien</option>
                    <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                <input type="text" name="region" value="{{ old('region', $user->region) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('region') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'expérience</label>
                <select name="experience_level"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Non défini</option>
                    <option value="debutant" {{ (old('experience_level', $user->experience_level) == 'debutant') ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediaire" {{ (old('experience_level', $user->experience_level) == 'intermediaire') ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="expert" {{ (old('experience_level', $user->experience_level) == 'expert') ? 'selected' : '' }}>Expert</option>
                </select>
                @error('experience_level') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        @if(!$user->exists)
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
            <input type="password" name="password" required
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>
        @endif

        <div class="mt-6 flex items-center">
            <input type="checkbox" name="is_approved" id="is_approved" value="1" 
                   {{ old('is_approved', $user->is_approved) ? 'checked' : '' }}
                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
            <label for="is_approved" class="ml-2 block text-sm text-gray-900">
                Compte approuvé
            </label>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                {{ $user->exists ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection
