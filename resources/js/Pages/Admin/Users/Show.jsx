import React from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

const ROLE_BADGE = {
    admin: 'bg-red-50 text-red-700 border-red-100',
    technicien: 'bg-blue-50 text-blue-700 border-blue-100',
    agriculteur: 'bg-emerald-50 text-emerald-700 border-emerald-100',
};

const STATUS_BADGE = {
    grow: { icon: 'fa-leaf', label: 'En culture', cls: 'bg-emerald-50 text-emerald-700 border-emerald-100' },
    harvest: { icon: 'fa-wheat-awn', label: 'Récolté', cls: 'bg-amber-50 text-amber-700 border-amber-100' },
    fallow: { icon: 'fa-pause', label: 'Repos', cls: 'bg-slate-100 text-slate-700 border-slate-200' },
};

export default function UserShow({ user }) {
    const { auth } = usePage().props;
    const isSelf = user.id === auth?.user?.id;

    const handleDelete = () => {
        if (!confirm('Supprimer cet utilisateur ?')) return;
        router.delete(`/admin/users/${user.id}`);
    };

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title="Détail Agriculteur" />

            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div className="flex flex-wrap justify-between items-center gap-4 mb-8">
                    <div className="flex items-center space-x-4">
                        <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-2xl font-bold shadow-md shadow-emerald-600/20">
                            {user.name?.charAt(0).toUpperCase() || 'U'}
                        </div>
                        <div>
                            <h2 className="text-2xl font-bold text-slate-900">{user.name}</h2>
                            <p className="text-slate-500 text-sm mt-0.5">{user.email}</p>
                            <p className="text-xs text-slate-400 mt-1">
                                Membre depuis {new Date(user.created_at).toLocaleDateString('fr-FR')}
                            </p>
                        </div>
                    </div>
                    <div className="flex space-x-2">
                        <Link
                            href={`/admin/users/${user.id}/edit`}
                            className="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors"
                        >
                            <i className="fas fa-edit mr-2" aria-hidden="true" />Modifier
                        </Link>
                        <button
                            onClick={handleDelete}
                            disabled={isSelf}
                            className="bg-red-500 hover:bg-red-600 disabled:opacity-40 disabled:cursor-not-allowed text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors"
                        >
                            <i className="fas fa-trash mr-2" aria-hidden="true" />Supprimer
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-4">
                        <p className="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rôle</p>
                        <span className={`inline-flex mt-1.5 items-center px-2.5 py-1 text-xs font-bold rounded-full border ${ROLE_BADGE[user.role] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                            {user.role}
                        </span>
                    </div>
                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-4">
                        <p className="text-xs font-semibold text-slate-400 uppercase tracking-wider">Région</p>
                        <p className="mt-1.5 text-sm font-semibold text-slate-800">{user.region || 'Non définie'}</p>
                    </div>
                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-4">
                        <p className="text-xs font-semibold text-slate-400 uppercase tracking-wider">Expérience</p>
                        <p className="mt-1.5 text-sm font-semibold text-slate-800">{user.experience_level || 'Non défini'}</p>
                    </div>
                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-4">
                        <p className="text-xs font-semibold text-slate-400 uppercase tracking-wider">Statut</p>
                        {user.is_approved ? (
                            <p className="mt-1.5 text-sm font-bold text-emerald-700"><i className="fas fa-check-circle mr-1" aria-hidden="true" />Approuvé</p>
                        ) : (
                            <p className="mt-1.5 text-sm font-bold text-amber-700"><i className="fas fa-clock mr-1" aria-hidden="true" />En attente</p>
                        )}
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-5">
                        <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Informations</h3>
                        <dl className="space-y-3">
                            {[
                                ["Nom d'utilisateur", user.username],
                                ['Surface totale', user.surface_totale ? `${user.surface_totale} ha` : 'Non définie'],
                            ].map(([label, value]) => (
                                <div key={label} className="flex justify-between">
                                    <dt className="text-slate-500 text-sm">{label}</dt>
                                    <dd className="font-semibold text-slate-800 text-sm">{value}</dd>
                                </div>
                            ))}
                        </dl>
                    </div>

                    <div className="bg-slate-50 rounded-xl border border-slate-100 p-5">
                        <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Statistiques</h3>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="bg-white rounded-xl p-4 text-center border border-slate-100">
                                <i className="fas fa-map text-emerald-600 text-lg mb-2" aria-hidden="true" />
                                <p className="text-2xl font-bold text-slate-900">{user.parcels_count ?? 0}</p>
                                <p className="text-xs text-slate-500 mt-0.5">Parcelles</p>
                            </div>
                            <div className="bg-white rounded-xl p-4 text-center border border-slate-100">
                                <i className="fas fa-robot text-blue-600 text-lg mb-2" aria-hidden="true" />
                                <p className="text-2xl font-bold text-slate-900">{user.interaction_ias_count ?? 0}</p>
                                <p className="text-xs text-slate-500 mt-0.5">Interactions IA</p>
                            </div>
                        </div>
                    </div>
                </div>

                {user.parcels?.length > 0 && (
                    <div className="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-2">
                        <div className="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                            <h3 className="text-sm font-bold text-slate-700">
                                <i className="fas fa-map-marked-alt mr-2 text-slate-400" aria-hidden="true" />Parcelles
                            </h3>
                        </div>
                        <table className="min-w-full divide-y divide-slate-100">
                            <thead className="bg-slate-50/80 text-[11px] uppercase tracking-wider font-bold text-slate-500">
                                <tr>
                                    {['Nom', 'Surface', 'Culture', 'Status'].map((h) => (
                                        <th key={h} className="px-5 py-3 text-left">{h}</th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 text-sm">
                                {user.parcels.map((parcel) => {
                                    const status = STATUS_BADGE[parcel.status];
                                    return (
                                        <tr key={parcel.id} className="hover:bg-slate-50/80 transition-colors duration-150">
                                            <td className="px-5 py-3.5 font-medium text-slate-800">{parcel.nom || `Parcelle #${parcel.id}`}</td>
                                            <td className="px-5 py-3.5 text-slate-600">{parcel.surface} ha</td>
                                            <td className="px-5 py-3.5 text-slate-700">{parcel.culture?.nom_commun || '—'}</td>
                                            <td className="px-5 py-3.5">
                                                {status && (
                                                    <span className={`inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border ${status.cls}`}>
                                                        <i className={`fas ${status.icon} mr-1 text-xs`} aria-hidden="true" />{status.label}
                                                    </span>
                                                )}
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                )}

                <div className="mt-6">
                    <Link href="/admin/users" className="inline-flex items-center text-emerald-600 hover:text-emerald-800 font-semibold text-sm transition-colors">
                        <i className="fas fa-arrow-left mr-2" aria-hidden="true" />Retour à la liste
                    </Link>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
