import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

const TYPE_STYLES = {
    engrais:     'bg-yellow-100 text-yellow-800',
    biologique:  'bg-emerald-100 text-emerald-800',
    fongicide:   'bg-purple-100 text-purple-800',
    insecticide: 'bg-red-100    text-red-800',
    herbicide:   'bg-orange-100 text-orange-800',
};

const TYPE_ICONS = {
    engrais:     'fas fa-flask',
    biologique:  'fas fa-leaf',
    fongicide:   'fas fa-shield-virus',
    insecticide: 'fas fa-bug',
    herbicide:   'fas fa-spray-can',
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
                <h3 className="text-center font-bold text-slate-900 mb-1">Supprimer ce produit</h3>
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

export default function ProductsIndex() {
    const { props } = usePage();
    const products = props.products?.data || [];
    const [search, setSearch] = useState('');
    const [typeFilter, setTypeFilter] = useState('');
    const [deleteTarget, setDeleteTarget] = useState(null);

    const filtered = products.filter((p) => {
        const matchesSearch = p.nom_commercial?.toLowerCase().includes(search.toLowerCase()) ||
                              p.composant_actif?.toLowerCase().includes(search.toLowerCase());
        const matchesType = typeFilter ? p.type === typeFilter : true;
        return matchesSearch && matchesType;
    });

    const handleDelete = () => {
        if (!deleteTarget) return;
        router.delete(`/admin/products/${deleteTarget.id}`, { preserveScroll: true });
        setDeleteTarget(null);
    };

    const uniqueTypes = [...new Set(products.map((p) => p.type).filter(Boolean))];

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title="Gestion des Produits" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-slide-up">
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Produits</h1>
                <Link
                    href="/admin/products/create"
                    aria-label="Ajouter un nouveau produit"
                    className="btn-agri inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-white font-semibold text-sm shadow-sm"
                >
                    <i className="fas fa-plus mr-2 text-xs" aria-hidden="true" />
                    Ajouter un produit
                </Link>
            </div>

            {/* Filters */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 animate-slide-up">
                <div className="flex flex-col sm:flex-row gap-3">
                    <div className="relative flex-1">
                        <i className="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true" />
                        <input
                            type="text"
                            placeholder="Rechercher un produit..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            aria-label="Rechercher un produit"
                            className="input-agri w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder:text-slate-400 bg-slate-50/60"
                        />
                    </div>
                    <select
                        value={typeFilter}
                        onChange={(e) => setTypeFilter(e.target.value)}
                        aria-label="Filtrer par type"
                        className="input-agri px-4 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-700 bg-white focus:outline-none sm:w-48"
                    >
                        <option value="">Tous les types</option>
                        {uniqueTypes.map((t) => (
                            <option key={t} value={t} className="capitalize">{t}</option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Table */}
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden animate-slide-up">
                <div className="overflow-x-auto">
                    <table className="min-w-full" role="table" aria-label="Liste des produits">
                        <thead>
                            <tr className="border-b border-slate-100 bg-slate-50/50">
                                {['Produit', 'Type', 'Composant Actif', 'Délai Récolte', 'Actions'].map((h) => (
                                    <th key={h} scope="col"
                                        className="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                                        {h}
                                    </th>
                                ))}
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {filtered.length > 0 ? filtered.map((product) => (
                                <tr key={product.id} className="hover:bg-slate-50/60 transition-colors duration-100">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-3">
                                            <div className={`w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${TYPE_STYLES[product.type] || 'bg-slate-100 text-slate-600'}`}>
                                                <i className={`${TYPE_ICONS[product.type] || 'fas fa-box'} text-xs`} aria-hidden="true" />
                                            </div>
                                            <div className="min-w-0">
                                                <p className="text-sm font-semibold text-slate-800">{product.nom_commercial}</p>
                                                {product.description && (
                                                    <p className="text-xs text-slate-400 truncate max-w-[200px]">{product.description}</p>
                                                )}
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize ${TYPE_STYLES[product.type] || 'bg-slate-100 text-slate-700'}`}>
                                            {product.type || '—'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600">{product.composant_actif || '—'}</td>
                                    <td className="px-6 py-4 text-sm text-slate-600 tabular-nums">
                                        {product.delai_avant_recolte
                                            ? <span className="inline-flex items-center gap-1"><i className="fas fa-clock text-[10px] text-slate-400" aria-hidden="true" />{product.delai_avant_recolte} jours</span>
                                            : '—'}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center space-x-1">
                                            <Link href={`/admin/products/${product.id}`}
                                                  aria-label={`Voir ${product.nom_commercial}`}
                                                  className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-eye text-sm" aria-hidden="true" />
                                            </Link>
                                            <Link href={`/admin/products/${product.id}/edit`}
                                                  aria-label={`Éditer ${product.nom_commercial}`}
                                                  className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-pencil text-sm" aria-hidden="true" />
                                            </Link>
                                            <button
                                                onClick={() => setDeleteTarget(product)}
                                                aria-label={`Supprimer ${product.nom_commercial}`}
                                                className="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-[color,background-color] duration-150">
                                                <i className="fas fa-trash text-sm" aria-hidden="true" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={5} className="px-6 py-12 text-center text-sm text-slate-400">
                                        {search || typeFilter ? 'Aucun produit ne correspond aux filtres.' : 'Aucun produit enregistré.'}
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {props.products?.links && (
                    <div className="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                        <p className="text-xs text-slate-400">
                            Page {props.products.current_page} sur {props.products.last_page}
                        </p>
                        <div className="flex items-center space-x-1">
                            {props.products.links.map((link, i) =>
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
                    name={deleteTarget.nom_commercial}
                    onConfirm={handleDelete}
                    onCancel={() => setDeleteTarget(null)}
                />
            )}
        </div>
        </AdminLayout>
    );
}
