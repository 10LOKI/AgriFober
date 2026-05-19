@extends('admin.layouts.app')

@section('title', 'Gestion Agriculteurs')
@section('page-title', 'Gestion Agriculteurs')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center bg-agraire-600 hover:bg-agraire-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-agraire-600/15 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
        <i class="fas fa-user-plus mr-2 text-sm"></i>Créer Agriculteur
    </a>

    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200/60">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 {{ !request('filter') ? 'bg-white text-agraire-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Tous</a>
        <a href="{{ route('admin.users.index', ['filter' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 {{ request('filter') == 'pending' ? 'bg-white text-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">En attente</a>
        <a href="{{ route('admin.users.index', ['filter' => 'approved']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-150 {{ request('filter') == 'approved' ? 'bg-white text-agraire-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Approuvés</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/80 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                <tr>
                    <th class="px-6 py-3.5 text-left">ID</th>
                    <th class="px-6 py-3.5 text-left">Agriculteur</th>
                    <th class="px-6 py-3.5 text-left">Email</th>
                    <th class="px-6 py-3.5 text-left">Rôle</th>
                    <th class="px-6 py-3.5 text-left">Région</th>
                    <th class="px-6 py-3.5 text-left">Statut</th>
                    <th class="px-6 py-3.5 text-right pr-6">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-400">#{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-agraire-500 to-agraire-700 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-agraire-600/20 mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-neutre-800 text-sm">{{ $user->name }}</span>
                                    <div class="text-xs text-gray-400">{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border tracking-wide uppercase
                                {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                {{ $user->role === 'technicien' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                {{ $user->role === 'agriculteur' ? 'bg-agraire-50 text-agraire-700 border-agraire-100' : '' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->region)
                                <span class="inline-flex items-center"><i class="fas fa-map-marker-alt mr-1.5 text-amber-500 text-xs"></i>{{ $user->region }}</span>
                            @else
                                <span class="text-gray-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_approved)
                                <span class="inline-flex items-center text-xs font-bold text-agraire-700 bg-agraire-50/80 px-3 py-1.5 rounded-lg border border-agraire-200/50">
                                    <i class="fas fa-check-circle mr-1.5 text-agraire-600"></i>Approuvé
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-bold text-amber-700 bg-amber-50/80 px-3 py-1.5 rounded-lg border border-amber-200/50">
                                    <i class="fas fa-clock mr-1.5 text-amber-500"></i>En attente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$user->is_approved)
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline m-0" title="Approuver">
                                        @csrf
                                        <button type="submit" class="p-2 text-agraire-600 hover:bg-agraire-50 rounded-lg transition-colors">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline m-0" title="Rejeter" onsubmit="return confirm('Rejeter cet utilisateur ?');">
                                        @csrf
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-user-slash text-2xl text-slate-300"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Aucun utilisateur trouvé</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Ajoutez un agriculteur pour commencer</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
    <div class="mt-6 flex flex-wrap items-center justify-center gap-1.5">
        @foreach($users->links()->elements as $element)
            @if(is_string($element))
                <span class="px-3 py-2 text-sm text-gray-400">{{ $element }}</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    <a href="{{ $url }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-all duration-150
                        {{ $page == $users->currentPage()
                            ? 'bg-agraire-600 text-white shadow-md shadow-agraire-600/15'
                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-slate-50 hover:border-gray-300' }}">
                        {{ $page }}
                    </a>
                @endforeach
            @endif
        @endforeach
    </div>
@endif
@endsection