import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';

const SAISON_STYLES = {
    printemps: 'bg-green-100  text-green-800',
    été:       'bg-yellow-100 text-yellow-800',
    automne:   'bg-orange-100 text-orange-800',
    hiver:     'bg-sky-100    text-sky-800',
};

function ConfirmDelete({ name, onConfirm, onCancel }) {
    return (
        <div role="dialog" aria-modal="true" aria-label="Confirmer la suppression"
             className="fixed inset-0 z-50 flex items-center justify-center">
            <div aria-hidden="true" onClick={onCancel}
                 className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" />
            <div className="relative bg-white rounded-2xl shadow-xl border border-slate-100 p-6 max-w-sm w-full mx-4 animate-slide-up">
                <div className="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <i className="fas fa-trash-alt text-xl" aria-hidden="true" />
                </div>
                <h3 className="text-center font-bold text-slate-900 mb-1">Supprimer cette culture</h3>
                <p className="text-center text-sm text-slate-500 mb-6">
                    Cette action est irréversible. Supprimer{' '}
                    <span className="font-semibold text-slate-700">{name}</span>&nbsp;?
                </p>
                <div className="flex space-x-3">
                    <button onClick={onCancel}
                            className="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-[background-color] duration-150">
                        Annuler
                    </button>
                    <button onClick={onConfirm}
                            className="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-[background-color,box-shadow] duration-150 hover:shadow-md">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function CulturesIndex() {
    const { props } = usePage();
    const cultures = props.cultures?.data || [];
    const [search, setSearch] = useState('');
    const [deleteTarget, setDeleteTarget] = useState(null);

    const filtered = cultures.filter((c) =>
        c.nom_commun?.toLowerCase().includes(search.toLowerCase()) ||
        c.nom_scientifique?.toLowerCase().includes(search.toLowerCase())
    );

    const handleDelete = () => {
        if (!deleteTarget) return;
        router.delete(`/admin/cultures/${deleteTarget.id}`, { preserveScroll: true });
        setDeleteTarget(null);
    };

    return (
        <div className="space-y-6">
            <Head title="Gestion des Cultures" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-slide-up">
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Cultures</h1>
                <Link
                    href="/admin/cultures/create"
                    aria-label="Ajouter une nouvelle culture"
                    className="btn-agri inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-white font-semibold text-sm shadow-sm"
                >
                    <i className="fas fa-plus mr-2 text-xs" aria-hidden="true" />
                    Ajouter une culture
                </Link>
            </div>

            {/* Search */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 animate-slide-up">
                <div className="relative max-w-sm">
                    <i className="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true" />
                    <input
                        type="text"
                        placeholder="Rechercher une culture..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        aria-label="Rechercher une culture"
                        className="input-agri w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder:text-slate-400 bg-slate-50/60"
                    />
                </div>
            </div>

            {/* Table */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden animate-slide-up">
                <div className="overflow-x-auto">
                    <table className="min-w-full" role="table" aria-label="Liste des cultures">
                        <thead>
                            <tr className="border-b border-slate-100 bg-slate-50/50">
                                {['Culture', 'Type', 'Saison', 'Région', 'Temp. °C', 'Eau (mm)', 'Actions'].map((h) => (
                                    <th key={h} scope="col"
                                        className="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                                        {h}
                                    </th>
                                ))}
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {filtered.length > 0 ? filtered.map((culture) => (
                                <tr key={culture.id} className="hover:bg-slate-50/60 transition-colors duration-100 group">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-3">
                                            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center shrink-0">
                                                <i className="fas fa-seedling text-emerald-600 text-xs" aria-hidden="true" />
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-slate-800">{culture.nom_commun}</p>
                                                {culture.nom_scientifique && (
                                                    <p className="text-xs text-slate-400 italic">{culture.nom_scientifique}</p>
                                                )}
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 capitalize">
                                            {culture.type || '—'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize ${SAISON_STYLES[culture.saison] || 'bg-slate-100 text-slate-700'}`}>
                                            {culture.saison || '—'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600">{culture.region || '—'}</td>
                                    <td className="px-6 py-4 text-sm text-slate-600 tabular-nums">
                                        {culture.temp_min != null && culture.temp_max != null
                                            ? <span>{culture.temp_min}° / {culture.temp_max}°</span>
                                            : '—'}
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600 tabular-nums">
                                        {culture.besoin_eau_cycle ? `${culture.besoin_eau_cycle} mm` : '—'}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-1">
                                            <Link href={`/admin/cultures/${culture.id}`}
                                                  aria-label={`Voir ${culture.nom_commun}`}
                                                  className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-eye text-sm" aria-hidden="true" />
                                            </Link>
                                            <Link href={`/admin/cultures/${culture.id}/edit`}
                                                  aria-label={`Éditer ${culture.nom_commun}`}
                                                  className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-pencil text-sm" aria-hidden="true" />
                                            </Link>
                                            <button
                                                onClick={() => setDeleteTarget(culture)}
                                                aria-label={`Supprimer ${culture.nom_commun}`}
                                                className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-trash text-sm" aria-hidden="true" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={7} className="px-6 py-12 text-center text-sm text-slate-400">
                                        {search ? 'Aucune culture ne correspond à cette recherche.' : 'Aucune culture enregistrée.'}
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {props.cultures?.links && (
                    <div className="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                        <p className="text-xs text-slate-400">
                            Page {props.cultures.current_page} sur {props.cultures.last_page}
                        </p>
                        <div className="flex items-center space-x-1">
                            {props.cultures.links.map((link, i) =>
                                link.url ? (
                                    <Link key={i} href={link.url}
                                          aria-current={link.active ? 'page' : undefined}
                                          className={`inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg text-sm font-medium transition-[color,background-color] duration-150 ${link.active ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800'}`}>
                                        {link.label.includes('laquo') ? '←' : link.label.includes('raquo') ? '→' : link.label}
                                    </Link>
                                ) : (
                                    <span key={i} className="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg text-sm font-medium text-slate-300 cursor-not-allowed">
                                        {link.label.includes('laquo') ? '←' : link.label.includes('raquo') ? '→' : link.label}
                                    </span>
                                )
                            )}
                        </div>
                    </div>
                )}
            </div>

            {/* Delete confirmation */}
            {deleteTarget && (
                <ConfirmDelete
                    name={deleteTarget.nom_commun}
                    onConfirm={handleDelete}
                    onCancel={() => setDeleteTarget(null)}
                />
            )}
        </div>
    );
}
