import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

const TYPE_BADGE = {
    engrais: 'bg-amber-50 text-amber-700 border-amber-100',
    pesticide: 'bg-red-50 text-red-700 border-red-100',
    fongicide: 'bg-purple-50 text-purple-700 border-purple-100',
    herbicide: 'bg-blue-50 text-blue-700 border-blue-100',
    biologique: 'bg-emerald-50 text-emerald-700 border-emerald-100',
};

export default function ProductShow({ product }) {
    const handleDelete = () => {
        if (!confirm('Supprimer ce produit ?')) return;
        router.delete(`/admin/products/${product.id}`);
    };

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title="Détail Produit" />

            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div className="flex flex-wrap justify-between items-center gap-4 mb-8">
                    <div className="flex items-center space-x-4">
                        <div className="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-700 shadow-inner">
                            <i className="fas fa-box text-2xl" aria-hidden="true" />
                        </div>
                        <div>
                            <h2 className="text-2xl font-bold text-slate-900">{product.nom_commercial}</h2>
                            {product.description && (
                                <p className="text-slate-500 text-sm mt-0.5 line-clamp-2">{product.description}</p>
                            )}
                        </div>
                    </div>
                    <div className="flex space-x-2">
                        <Link
                            href={`/admin/products/${product.id}/edit`}
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
                        <span className={`inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full border capitalize ${TYPE_BADGE[product.type] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                            {product.type}
                        </span>
                    </div>
                    <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Composant actif</h4>
                        <p className="text-slate-900 font-semibold">{product.composant_actif || '—'}</p>
                    </div>
                    <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dosage recommandé</h4>
                        <p className="text-slate-900 font-semibold">{product.dosage_recommande || '—'}</p>
                    </div>
                    <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Délai avant récolte</h4>
                        <p className="text-slate-900 font-semibold">
                            {product.delai_avant_recolte ? `${product.delai_avant_recolte} jours` : '—'}
                        </p>
                    </div>
                    {product.image && (
                        <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Image</h4>
                            <img src={`/storage/${product.image}`} alt={product.nom_commercial} className="h-16 w-16 object-cover rounded-lg border border-slate-200" />
                        </div>
                    )}
                </div>

                {product.avantages && (
                    <div className="bg-emerald-50/60 border border-emerald-100 p-5 rounded-xl mb-4">
                        <h4 className="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Avantages</h4>
                        <p className="text-slate-700 leading-relaxed">{product.avantages}</p>
                    </div>
                )}

                {product.usage_method && (
                    <div className="bg-blue-50/60 border border-blue-100 p-5 rounded-xl mb-4">
                        <h4 className="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Méthode d'utilisation</h4>
                        <p className="text-slate-700 leading-relaxed">{product.usage_method}</p>
                    </div>
                )}

                {product.safety_instructions && (
                    <div className="bg-red-50/60 border border-red-100 p-5 rounded-xl mb-4">
                        <h4 className="text-xs font-bold text-red-800 uppercase tracking-wider mb-2">Instructions de sécurité</h4>
                        <p className="text-slate-700 leading-relaxed">{product.safety_instructions}</p>
                    </div>
                )}

                {product.cultures?.length > 0 && (
                    <div className="mt-6 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                        <div className="px-5 py-3.5 border-b border-slate-100 bg-slate-50/80">
                            <h4 className="text-sm font-bold text-slate-700">
                                <i className="fas fa-seedling mr-2 text-slate-400" aria-hidden="true" />Cultures compatibles
                            </h4>
                        </div>
                        <div className="p-5 flex flex-wrap gap-2">
                            {product.cultures.map((culture) => (
                                <span key={culture.id} className="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium border border-indigo-100">
                                    {culture.nom_commun}
                                </span>
                            ))}
                        </div>
                    </div>
                )}

                <div className="mt-8">
                    <Link href="/admin/products" className="inline-flex items-center text-emerald-600 hover:text-emerald-800 font-semibold text-sm transition-colors">
                        <i className="fas fa-arrow-left mr-2" aria-hidden="true" />Retour à la liste
                    </Link>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
