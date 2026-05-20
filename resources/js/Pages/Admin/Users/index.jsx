import React, { useState } from 'react';
import { usePage, Head, router } from '@inertiajs/react';

export default function UsersIndex() {
    const { props } = usePage();
    const users = props.users?.data || [];
    const auth = props.auth || { user: {} };

    const [search, setSearch] = useState('');
    const [roleFilter, setRoleFilter] = useState('');
    const [approvalFilter, setApprovalFilter] = useState('');

    const handleApprove = (id) => {
        if (confirm('Approuver cet utilisateur ?')) {
            router.post(`/admin/users/${id}/approve`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    alert('Utilisateur approuvé');
                }
            });
        }
    };

    const handleReject = (id) => {
        if (confirm('Rejeter cet utilisateur ?')) {
            router.post(`/admin/users/${id}/reject`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    alert('Utilisateur rejeté');
                }
            });
        }
    };

    const filteredUsers = users.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(search.toLowerCase()) ||
                            user.email.toLowerCase().includes(search.toLowerCase());
        const matchesRole = roleFilter ? user.role === roleFilter : true;
        const matchesApproval = approvalFilter 
            ? (approvalFilter === 'approved' ? user.is_approved : !user.is_approved)
            : true;
        return matchesSearch && matchesRole && matchesApproval;
    });

    return (
        <div>
            <Head title="Gestion Agriculteurs" />

            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Agriculteurs</h1>
                <div className="flex space-x-2">
                    <a href="/admin/users/create" className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        + Créer Agriculteur
                    </a>
                </div>
            </div>

            {/* Filters */}
            <div className="bg-white p-4 rounded shadow mb-6">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="text"
                        placeholder="Rechercher..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="border rounded px-3 py-2"
                    />
                    <select 
                        value={roleFilter}
                        onChange={(e) => setRoleFilter(e.target.value)}
                        className="border rounded px-3 py-2"
                    >
                        <option value="">Tous les rôles</option>
                        <option value="agriculteur">Agriculteur</option>
                        <option value="technicien">Technicien</option>
                    </select>
                    <select 
                        value={approvalFilter}
                        onChange={(e) => setApprovalFilter(e.target.value)}
                        className="border rounded px-3 py-2"
                    >
                        <option value="">Tous statuts</option>
                        <option value="approved">Approuvés</option>
                        <option value="pending">En attente</option>
                    </select>
                </div>
            </div>

            {/* Table */}
            <div className="bg-white rounded shadow overflow-hidden">
                <table className="min-w-full">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {filteredUsers.map((user) => (
                            <tr key={user.id} className="hover:bg-gray-50">
                                <td className="px-6 py-4 text-sm">{user.id}</td>
                                <td className="px-6 py-4 text-sm font-medium">{user.name}</td>
                                <td className="px-6 py-4 text-sm">{user.email}</td>
                                <td className="px-6 py-4 text-sm">
                                    <span className={`px-2 py-1 rounded-full text-xs ${
                                        user.role === 'admin' ? 'bg-red-100 text-red-800' :
                                        user.role === 'technicien' ? 'bg-blue-100 text-blue-800' :
                                        'bg-green-100 text-green-800'
                                    }`}>
                                        {user.role}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-sm">{user.region || '-'}</td>
                                <td className="px-6 py-4 text-sm">
                                    {user.is_approved ? (
                                        <span className="text-green-600">✓ Approuvé</span>
                                    ) : (
                                        <span className="text-yellow-600">⏳ En attente</span>
                                    )}
                                </td>
                                <td className="px-6 py-4 text-sm">
                                    <div className="flex space-x-2">
                                        <a href={`/admin/users/${user.id}`} className="text-blue-600 hover:underline">
                                            Voir
                                        </a>
                                        {!user.is_approved && auth.user.role === 'admin' && (
                                            <>
                                                <button 
                                                    onClick={() => handleApprove(user.id)}
                                                    className="text-green-600 hover:underline"
                                                >
                                                    Approuver
                                                </button>
                                                <button 
                                                    onClick={() => handleReject(user.id)}
                                                    className="text-red-600 hover:underline"
                                                >
                                                    Rejeter
                                                </button>
                                            </>
                                        )}
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {props.users?.links && (
                <div className="mt-4 flex justify-center">
                    <div className="flex space-x-1">
                        {props.users.links.map((link, i) => (
                            <a 
                                key={i}
                                href={link.url || '#'}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                                className={`px-3 py-1 rounded ${link.active ? 'bg-green-600 text-white' : 'bg-white'}`}
                            />
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}