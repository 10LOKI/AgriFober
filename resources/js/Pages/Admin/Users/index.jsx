import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

const ROLE_STYLES = {
    admin:       'bg-red-100 text-red-800',
    technicien:  'bg-sky-100 text-sky-800',
    agriculteur: 'bg-emerald-100 text-emerald-800',
};

function PaginationLabel({ label }) {
    if (label.includes('laquo')) return <>&#8592;</>;
    if (label.includes('raquo')) return <>&#8594;</>;
    return <>{label}</>;
}

function ConfirmDialog({ action, onConfirm, onCancel }) {
    const isApprove = action.type === 'approve';
    return (
        <div
            role="dialog"
            aria-modal="true"
            aria-label={isApprove ? "Confirmer l'approbation" : 'Confirmer le rejet'}
            className="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div aria-hidden="true" onClick={onCancel} className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" />
            <div className="relative bg-white rounded-2xl shadow-xl border border-slate-100 p-6 max-w-sm w-full mx-4">
                <div className={`w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 ${isApprove ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'}`}>
                    <i className={`fas ${isApprove ? 'fa-user-check' : 'fa-user-times'} text-xl`} aria-hidden="true" />
                </div>
                <h3 className="text-center font-bold text-slate-900 mb-1">
                    {isApprove ? 'Approuver ce compte' : 'Rejeter ce compte'}
                </h3>
                <p className="text-center text-sm text-slate-500 mb-6">
                    {isApprove ? "Autoriser l'accès pour " : "Refuser l'accès pour "}
                    <span className="font-semibold text-slate-700">{action.name}</span>&nbsp;?
                </p>
                <div className="flex space-x-3">
                    <button
                        onClick={onCancel}
                        className="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-[background-color] duration-150"
                    >
                        Annuler
                    </button>
                    <button
                        onClick={onConfirm}
                        className={`flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-[background-color,box-shadow] duration-150 hover:shadow-md ${isApprove ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'}`}
                    >
                        {isApprove ? 'Approuver' : 'Rejeter'}
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function UsersIndex() {
    const { users, auth } = usePage().props;
    const list = users?.data || [];

    const [search, setSearch] = useState('');
    const [roleFilter, setRoleFilter] = useState('');
    const [approvalFilter, setApprovalFilter] = useState('');
    const [confirmAction, setConfirmAction] = useState(null);

    const executeConfirmed = () => {
        if (!confirmAction) return;
        router.post(`/admin/users/${confirmAction.id}/${confirmAction.type}`, {}, { preserveScroll: true });
        setConfirmAction(null);
    };

    const filtered = list.filter((user) => {
        const q = search.toLowerCase();
        const matchesSearch = user.name.toLowerCase().includes(q) || user.email.toLowerCase().includes(q);
        const matchesRole = roleFilter ? user.role === roleFilter : true;
        const matchesApproval = approvalFilter
            ? (approvalFilter === 'approved' ? user.is_approved : !user.is_approved)
            : true;
        return matchesSearch && matchesRole && matchesApproval;
    });

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title="Gestion Agriculteurs" />

            {/* Page header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Agriculteurs</h1>
                <Link
                    href="/admin/users/create"
                    aria-label="Créer un nouvel agriculteur"
                    className="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-[background-color,box-shadow] duration-200 hover:shadow-md shadow-sm"
                >
                    <i className="fas fa-plus mr-2 text-xs" aria-hidden="true" />
                    Créer un agriculteur
                </Link>
            </div>

            {/* Filters */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div className="relative">
                        <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true" />
                        <input
                            type="text"
                            placeholder="Rechercher par nom ou email..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            aria-label="Rechercher un utilisateur"
                            className="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-[border-color,box-shadow] duration-200"
                        />
                    </div>
                    <select
                        value={roleFilter}
                        onChange={(e) => setRoleFilter(e.target.value)}
                        aria-label="Filtrer par rôle"
                        className="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-[border-color,box-shadow] duration-200 bg-white"
                    >
                        <option value="">Tous les rôles</option>
                        <option value="agriculteur">Agriculteur</option>
                        <option value="technicien">Technicien</option>
                    </select>
                    <select
                        value={approvalFilter}
                        onChange={(e) => setApprovalFilter(e.target.value)}
                        aria-label="Filtrer par statut d'approbation"
                        className="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-[border-color,box-shadow] duration-200 bg-white"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="approved">Approuvés</option>
                        <option value="pending">En attente</option>
                    </select>
                </div>
            </div>

            {/* Table */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="min-w-full" role="table" aria-label="Liste des utilisateurs">
                        <thead>
                            <tr className="border-b border-slate-100 bg-slate-50/50">
                                {['#', 'Utilisateur', 'Rôle', 'Région', 'Statut', 'Actions'].map((h) => (
                                    <th
                                        key={h}
                                        scope="col"
                                        className="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider"
                                    >
                                        {h}
                                    </th>
                                ))}
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {filtered.length > 0 ? filtered.map((user) => (
                                <tr key={user.id} className="hover:bg-slate-50/50 transition-colors duration-100">
                                    <td className="px-6 py-4 text-xs text-slate-400 font-mono">{user.id}</td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-3">
                                            <div
                                                className="w-8 h-8 rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 flex items-center justify-center text-xs font-bold text-slate-600 shrink-0"
                                                aria-hidden="true"
                                            >
                                                {user.name?.charAt(0).toUpperCase() || 'U'}
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-slate-800">{user.name}</p>
                                                <p className="text-xs text-slate-400">{user.email}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${ROLE_STYLES[user.role] || 'bg-slate-100 text-slate-700'}`}>
                                            {user.role}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600">{user.region || '—'}</td>
                                    <td className="px-6 py-4">
                                        {user.is_approved ? (
                                            <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                                <i className="fas fa-check-circle text-[10px]" aria-hidden="true" />
                                                Approuvé
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">
                                                <i className="fas fa-clock text-[10px]" aria-hidden="true" />
                                                En attente
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-1">
                                            <Link
                                                href={`/admin/users/${user.id}`}
                                                aria-label={`Voir le profil de ${user.name}`}
                                                className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-[color,background-color] duration-150"
                                            >
                                                <i className="fas fa-eye text-sm" aria-hidden="true" />
                                            </Link>
                                            {!user.is_approved && auth?.user?.role === 'admin' && (
                                                <>
                                                    <button
                                                        onClick={() => setConfirmAction({ id: user.id, type: 'approve', name: user.name })}
                                                        aria-label={`Approuver le compte de ${user.name}`}
                                                        className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-[color,background-color] duration-150"
                                                    >
                                                        <i className="fas fa-user-check text-sm" aria-hidden="true" />
                                                    </button>
                                                    <button
                                                        onClick={() => setConfirmAction({ id: user.id, type: 'reject', name: user.name })}
                                                        aria-label={`Rejeter le compte de ${user.name}`}
                                                        className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-[color,background-color] duration-150"
                                                    >
                                                        <i className="fas fa-user-times text-sm" aria-hidden="true" />
                                                    </button>
                                                </>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={6} className="px-6 py-12 text-center text-sm text-slate-400">
                                        Aucun utilisateur ne correspond aux filtres sélectionnés.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination — no dangerouslySetInnerHTML */}
                {users?.links && (
                    <div className="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                        <p className="text-xs text-slate-400">
                            Page {users.current_page} sur {users.last_page}
                        </p>
                        <div className="flex items-center space-x-1">
                            {users.links.map((link, i) =>
                                link.url ? (
                                    <Link
                                        key={i}
                                        href={link.url}
                                        aria-current={link.active ? 'page' : undefined}
                                        className={`
                                            inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg text-sm font-medium
                                            transition-[color,background-color] duration-150
                                            ${link.active
                                                ? 'bg-emerald-600 text-white'
                                                : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800'}
                                        `}
                                    >
                                        <PaginationLabel label={link.label} />
                                    </Link>
                                ) : (
                                    <span
                                        key={i}
                                        className="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg text-sm font-medium text-slate-300 cursor-not-allowed"
                                        aria-disabled="true"
                                    >
                                        <PaginationLabel label={link.label} />
                                    </span>
                                )
                            )}
                        </div>
                    </div>
                )}
            </div>

            {/* Inline confirmation dialog — replaces native confirm/alert */}
            {confirmAction && (
                <ConfirmDialog
                    action={confirmAction}
                    onConfirm={executeConfirmed}
                    onCancel={() => setConfirmAction(null)}
                />
            )}
        </div>
        </AdminLayout>
    );
}
