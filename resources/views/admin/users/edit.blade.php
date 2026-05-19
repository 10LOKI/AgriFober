@extends('admin.layouts.app')

@section('title', ($user->exists ?? isset($user)) ? 'Modifier Agriculteur' : 'Créer Agriculteur')
@section('page-title', ($user->exists ?? isset($user)) ? 'Modifier Agriculteur' : 'Créer Agriculteur')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-2xl">
    <form method="POST" action="{{ ($user->exists ?? isset($user)) ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if(($user->exists ?? isset($user)))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom d'utilisateur</label>
                <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('username') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('email') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Rôle</label>
                <select name="role" required
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="agriculteur" {{ (old('role', $user->role ?? '') == 'agriculteur') ? 'selected' : '' }}>Agriculteur</option>
                    <option value="technicien" {{ (old('role', $user->role ?? '') == 'technicien') ? 'selected' : '' }}>Technicien</option>
                    <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Région</label>
                <input type="text" name="region" value="{{ old('region', $user->region ?? '') }}"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('region') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Niveau d'expérience</label>
                <select name="experience_level"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none bg-white">
                    <option value="">Non défini</option>
                    <option value="debutant" {{ (old('experience_level', $user->experience_level ?? '') == 'debutant') ? 'selected' : '' }}>Débutant</option>
                    <option value="intermediaire" {{ (old('experience_level', $user->experience_level ?? '') == 'intermediaire') ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="expert" {{ (old('experience_level', $user->experience_level ?? '') == 'expert') ? 'selected' : '' }}>Expert</option>
                </select>
                @error('experience_level') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        @if(!($user->exists ?? isset($user)))
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
                @error('password') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-agraire-500/20 focus:border-agraire-500 transition-all outline-none">
            </div>
        </div>
        @endif

        <div class="mt-6 flex items-center pt-2">
            <input type="checkbox" name="is_approved" id="is_approved" value="1"
                   {{ old('is_approved', $user->is_approved ?? 0) ? 'checked' : '' }}
                   class="h-4 w-4 text-agraire-600 focus:ring-agraire-500/30 border-slate-300 rounded transition-all cursor-pointer">
            <label for="is_approved" class="ml-2.5 text-sm font-medium text-slate-800 cursor-pointer select-none">Compte approuvé</label>
        </div>

        <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-agraire-600 hover:bg-agraire-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-agraire-600/15 hover:shadow-lg transition-all">
                {{ ($user->exists ?? isset($user)) ? 'Mettre à jour' : 'Créer' }}
            </button>
        </div>
    </form>
</div>
@endsection