import React from 'react';
import { usePage, Head, router } from '@inertiajs/react';

export default function ProductsIndex() {
    const { props } = usePage();
    const products = props.products?.data || [];

    return (
        <div>
            <Head title="Gestion Produits" />

            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Produits</h1>
                <a href="/admin/products/create" className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    + Ajouter Produit
                </a>
            </div>

            <div className="bg-white rounded shadow overflow-hidden">
                <table className="min-w-full">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom Commercial</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Composant Actif</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Délai Récolte</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {products.map((product) => (
                            <tr key={product.id} className="hover:bg-gray-50">
                                <td className="px-6 py-4">
                                    <div className="font-medium">{product.nom_commercial}</div>
                                    <div className="text-xs text-gray-500 truncate max-w-xs">{product.description}</div>
                                </td>
                                <td className="px-6 py-4">
                                    <span className={`px-2 py-1 rounded-full text-xs ${
                                        product.type === 'engrais' ? 'bg-yellow-100 text-yellow-800' :
                                        product.type === 'biologique' ? 'bg-green-100 text-green-800' :
                                        product.type === 'fongicide' ? 'bg-purple-100 text-purple-800' :
                                        'bg-blue-100 text-blue-800'
                                    }`}>
                                        {product.type}
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-sm">{product.composant_actif || '-'}</td>
                                <td className="px-6 py-4 text-sm">
                                    {product.delai_avant_recolte ? `${product.delai_avant_recolte} jours` : '-'}
                                </td>
                                <td className="px-6 py-4 text-sm">
                                    <div className="flex space-x-2">
                                        <a href={`/admin/products/${product.id}`} className="text-blue-600 hover:underline">
                                            Voir
                                        </a>
                                        <a href={`/admin/products/${product.id}/edit`} className="text-yellow-600 hover:underline">
                                            Éditer
                                        </a>
                                        <button 
                                            onClick={() => {
                                                if (confirm('Supprimer ce produit ?')) {
                                                    router.delete(`/admin/products/${product.id}`);
                                                }
                                            }}
                                            className="text-red-600 hover:underline"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}