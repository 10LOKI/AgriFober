import React from 'react';
import { usePage } from '@inertiajs/react';
import StatsCard from '../../components/StatsCard';

export default function Dashboard() {
    const { props } = usePage();
    const stats = props.stats || {
        users: 0,
        parcels: 0,
        cultures: 0,
        products: 0,
        ai_interactions_today: 0,
    };

    return (
        <div>
            <h1 className="text-2xl font-bold text-gray-800 mb-6">Tableau de Bord</h1>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <StatsCard 
                    title="Agriculteurs" 
                    value={stats.users} 
                    icon="👨‍🌾" 
                    color="blue" 
                />
                <StatsCard 
                    title="Parcelles" 
                    value={stats.parcels} 
                    icon="🌱" 
                    color="green" 
                />
                <StatsCard 
                    title="Cultures" 
                    value={stats.cultures} 
                    icon="🌾" 
                    color="purple" 
                />
                <StatsCard 
                    title="Produits" 
                    value={stats.products} 
                    icon="📦" 
                    color="orange" 
                />
            </div>

            {/* Additional Info */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div className="bg-white rounded-lg shadow p-6">
                    <h3 className="text-lg font-semibold mb-4">Activité Récente</h3>
                    <p className="text-gray-600">
                        Interactions IA aujourd'hui: <span className="font-bold">{stats.ai_interactions_today}</span>
                    </p>
                    <p className="text-sm text-gray-500 mt-2">
                        Plus de détails dans la section "Logs IA"
                    </p>
                </div>

                <div className="bg-white rounded-lg shadow p-6">
                    <h3 className="text-lg font-semibold mb-4">Actions Rapides</h3>
                    <div className="space-y-2">
                        <a href="/admin/users" className="block bg-green-50 hover:bg-green-100 px-4 py-2 rounded text-green-700">
                            Valider les comptes en attente
                        </a>
                        <a href="/admin/cultures/create" className="block bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded text-blue-700">
                            Ajouter une culture
                        </a>
                        <a href="/admin/products/create" className="block bg-purple-50 hover:bg-purple-100 px-4 py-2 rounded text-purple-700">
                            Ajouter un produit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
}