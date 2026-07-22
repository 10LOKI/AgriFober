import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

const TYPE_BADGE = {
    fruit: 'bg-purple-50 text-purple-700 border-purple-100',
    legume: 'bg-emerald-50 text-emerald-700 border-emerald-100',
    cereale: 'bg-amber-50 text-amber-700 border-amber-100',
    legumineuse: 'bg-blue-50 text-blue-700 border-blue-100',
};

export default function CultureShow({ culture }) {
    const handleDelete = () => {
        if (!confirm('Supprimer cette culture ?')) return;
        router.delete(`/admin/cultures/${culture.id}`);
    };

    const facts = [
        ['Région', culture.region || 'Non spécifié'],
        ['Type de sol', culture.soil_type || 'Non spécifié'],
        ['Température min', culture.temp_min != null ? `${culture.temp_min}°C` : '-'],
        ['Température max', culture.temp_max != null ? `${culture.temp_max}°C` : '-'],
        ['Besoins en eau', culture.besoin_eau_cycle != null ? `${culture.besoin_eau_cycle} mm/cycle` : '-'],
        ['pH min sol', culture.ph_sol_min ?? '—'],
        ['pH max sol', culture.ph_sol_max ?? '—'],
    ];

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title="Détail Culture" />

            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div className="flex flex-wrap justify-between items-center gap-4 mb-8">
                    <div className="flex items-center space-x-4">
                        <div className="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700 shadow-inner">
                            <i className="fas fa-seedling text-2xl" aria-hidden="true" />
                        </div>
                        <div>
                            <h2 className="text-2xl font-bold text-slate-900">{culture.nom_commun}</h2>
                            <p className="text-slate-500 italic">{culture.nom_scientifique || '—'}</p>
                        </div>
                    </div>
                    <div className="flex space-x-2">
                        <Link
                            href={`/admin/cultures/${culture.id}/edit`}
                            className="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors"
                        >
                            <i className="fas fa-edit mr-2" aria-hidden="true" />Modifier
                        </Link>
                        <button
                            onClick={handleDelete}
                            className="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors"
                        >
                            <i className="fas fa-trash mr-2" aria-hidden="true" />Supprimer
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Type</h4>
                        <span className={`inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border capitalize ${TYPE_BADGE[culture.type] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                            {culture.type}
                        </span>
                    </div>
                    <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Saison</h4>
                        <span className="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100 capitalize">
                            {culture.saison}
                        </span>
                    </div>
                    {facts.map(([label, value]) => (
                        <div key={label} className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{label}</h4>
                            <p className="text-slate-900 font-semibold">{value}</p>
                        </div>
                    ))}
                </div>

                {culture.conseils && (
                    <div className="bg-emerald-50/60 border border-emerald-100 p-5 rounded-xl mb-6">
                        <h4 className="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Conseils de culture</h4>
                        <p className="text-slate-700 leading-relaxed">{culture.conseils}</p>
                    </div>
                )}

                {(culture.parcels?.length > 0 || culture.products?.length > 0) && (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {culture.parcels?.length > 0 && (
                            <div className="bg-slate-50 rounded-xl border border-slate-100 overflow-hidden">
                                <div className="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                                    <h4 className="text-sm font-bold text-slate-700">
                                        <i className="fas fa-map-marked-alt mr-2 text-slate-400" aria-hidden="true" />Parcelles
                                    </h4>
                                </div>
                                <ul className="divide-y divide-slate-100">
                                    {culture.parcels.map((parcel) => (
                                        <li key={parcel.id} className="px-5 py-3 text-sm text-slate-700 flex justify-between">
                                            <span>{parcel.nom || `Parcelle #${parcel.id}`}</span>
                                            <span className="text-xs text-slate-400">{parcel.user?.name || '—'}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}
                        {culture.products?.length > 0 && (
                            <div className="bg-slate-50 rounded-xl border border-slate-100 overflow-hidden">
                                <div className="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                                    <h4 className="text-sm font-bold text-slate-700">
                                        <i className="fas fa-box text-slate-400 mr-2" aria-hidden="true" />Produits associés
                                    </h4>
                                </div>
                                <ul className="divide-y divide-slate-100">
                                    {culture.products.map((product) => (
                                        <li key={product.id} className="px-5 py-3 text-sm text-slate-700">{product.nom_commercial}</li>
                                    ))}
                                </ul>
                            </div>
                        )}
                    </div>
                )}

                <div className="mt-8">
                    <Link href="/admin/cultures" className="inline-flex items-center text-emerald-600 hover:text-emerald-800 font-semibold text-sm transition-colors">
                        <i className="fas fa-arrow-left mr-2" aria-hidden="true" />Retour à la liste
                    </Link>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
