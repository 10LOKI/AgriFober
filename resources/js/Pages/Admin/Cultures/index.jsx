import React, { useState } from 'react';
import { usePage, Head, router } from '@inertiajs/react';

export default function CulturesIndex() {
    const { props } = usePage();
    const cultures = props.cultures?.data || [];

    return (
        <div>
            <Head title="Gestion Cultures" />

            <div className="flex justify-between items-center mb-6">
                <h1 className="text-2xl font-bold text-gray-800">Cultures</h1>
                <a href="/admin/cultures/create" className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    + Ajouter Culture
                </a>
            </div>

            <div className="bg-white rounded shadow overflow-hidden">
                <table className="min-w-full">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saison</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temp. Min/Max (°C)</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Besoins Eau (mm)</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {cultures.map((culture) => (
                            <tr key={culture.id} className="hover:bg-gray-50">
                                <td className="px-6 py-4">
                                    <div className="font-medium">{culture.nom_commun}</div>
                                    <div className="text-xs text-gray-500">{culture.nom_scientifique}</div>
                                </td>
                                <td className="px-6 py-4 text-sm capitalize">{culture.type}</td>
                                <td className="px-6 py-4 text-sm capitalize">{culture.saison}</td>
                                <td className="px-6 py-4 text-sm">{culture.region || '-'}</td>
                                <td className="px-6 py-4 text-sm">
                                    {culture.temp_min}°C / {culture.temp_max}°C
                                </td>
                                <td className="px-6 py-4 text-sm">{culture.besoin_eau_cycle || '-'}</td>
                                <td className="px-6 py-4 text-sm">
                                    <div className="flex space-x-2">
                                        <a href={`/admin/cultures/${culture.id}`} className="text-blue-600 hover:underline">
                                            Voir
                                        </a>
                                        <a href={`/admin/cultures/${culture.id}/edit`} className="text-yellow-600 hover:underline">
                                            Éditer
                                        </a>
                                        <button 
                                            onClick={() => {
                                                if (confirm('Supprimer cette culture ?')) {
                                                    router.delete(`/admin/cultures/${culture.id}`);
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