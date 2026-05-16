@extends('admin.layouts.app')

@section('title', 'Gestion Agriculteurs')
@section('page-title', 'Gestion Agriculteurs')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.users.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Créer Agriculteur
        </a>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded-lg {{ !request('filter') ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white' }}">Tous</a>
        <a href="{{ route('admin.users.index', ['filter' => 'pending']) }}" class="px-4 py-2 border rounded-lg {{ request('filter') == 'pending' ? 'bg-yellow-50 border-yellow-500 text-yellow-700' : 'bg-white' }}">En attente</a>
        <a href="{{ route('admin.users.index', ['filter' => 'approved']) }}" class="px-4 py-2 border rounded-lg {{ request('filter') == 'approved' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-white' }}">Approuvés</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'technicien' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'agriculteur' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->region ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_approved)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Approuvé</span>
                        @else
                            <span class="text-yellow-600"><i class="fas fa-clock mr-1"></i>En attente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(!$user->is_approved)
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline" onsubmit="return confirm('Rejeter cet utilisateur ?');">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Rejeter">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endif
@endsection
