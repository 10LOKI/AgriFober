import React from 'react';
import { Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout'; // Ajuste le chemin selon ton arborescence

export default function Dashboard({ stats }) {
    return (
        <div className="space-y-8">
            
            {/* =========================================================================
                STATS CARDS
               ========================================================================= */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {/* Card Agriculteurs */}
                <div className="group relative bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
                    <div className="flex items-center justify-between">
                        <div className="space-y-1.5">
                            <p className="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Agriculteurs</p>
                            <p className="text-3xl font-bold text-slate-900 tracking-tight">{stats.total_farmers}</p>
                        </div>
                        <div className="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-sky-50 text-sky-600 border-sky-100/50">
                            <i className="fas fa-users text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        </div>
                    </div>
                    <div className="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-sky-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
                </div>

                {/* Card Parcelles */}
                <div className="group relative bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
                    <div className="flex items-center justify-between">
                        <div className="space-y-1.5">
                            <p className="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Parcelles</p>
                            <p className="text-3xl font-bold text-slate-900 tracking-tight">{stats.total_parcels}</p>
                        </div>
                        <div className="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-emerald-50 text-emerald-600 border-emerald-100/50">
                            <i className="fas fa-seedling text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        </div>
                    </div>
                    <div className="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
                </div>

                {/* Card Cultures */}
                <div className="group relative bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
                    <div className="flex items-center justify-between">
                        <div className="space-y-1.5">
                            <p className="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Cultures</p>
                            <p className="text-3xl font-bold text-slate-900 tracking-tight">{stats.total_cultures}</p>
                        </div>
                        <div className="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-indigo-50 text-indigo-600 border-indigo-100/50">
                            <i className="fas fa-leaf text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        </div>
                    </div>
                    <div className="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-indigo-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
                </div>

                {/* Card Produits */}
                <div className="group relative bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1">
                    <div className="flex items-center justify-between">
                        <div className="space-y-1.5">
                            <p className="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">Produits</p>
                            <p className="text-3xl font-bold text-slate-900 tracking-tight">{stats.total_products}</p>
                        </div>
                        <div className="w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 bg-amber-50 text-amber-600 border-amber-100/50">
                            <i className="fas fa-box text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        </div>
                    </div>
                    <div className="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
                </div>
            </div>

            {/* =========================================================================
                PENDING APPROVALS BANNER
               ========================================================================= */}
            {stats.pending_users_count > 0 && (
                <div className="bg-amber-50/60 backdrop-blur-sm border border-amber-200/60 rounded-2xl p-6 transition-all duration-300 hover:shadow-md">
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div className="flex items-center space-x-4">
                            <div className="bg-amber-500 text-white p-3 rounded-xl shadow-md shadow-amber-500/10">
                                <i className="fas fa-clock text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-md font-bold text-slate-900">Comptes en attente de validation</h3>
                                <p className="text-sm text-slate-600 font-medium">
                                    {stats.pending_users_count} agriculteur(s) requiert votre approbation.
                                </p>
                            </div>
                        </div>
                        <Link 
                            href="/admin/users" 
                            data={{ filter: 'pending' }}
                            className="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-sm shadow-sm hover:bg-amber-700 transition-all duration-200 shrink-0"
                        >
                            <i className="fas fa-user-check mr-2 text-xs"></i>
                            Traiter les demandes
                        </Link>
                    </div>
                </div>
            )}

            {/* =========================================================================
                ACTIVITIES & QUICK ACTIONS
               ========================================================================= */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {/* Actions Rapides */}
                <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h3 className="text-md font-bold text-slate-900 tracking-tight mb-4">Actions Rapides</h3>
                        <div className="space-y-3">
                            <Link href="/admin/users" className="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                                <div className="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                                    <i className="fas fa-user-shield text-sm"></i>
                                </div>
                                <span className="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Gérer les comptes</span>
                            </Link>
                            <Link href="/admin/cultures/create" className="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                                <div className="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                                    <i className="fas fa-plus text-sm"></i>
                                </div>
                                <span className="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Ajouter une culture</span>
                            </Link>
                            <Link href="/admin/products/create" className="group flex items-center p-3.5 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-100 rounded-xl transition-all duration-200">
                                <div className="bg-white p-2 rounded-lg text-slate-500 group-hover:text-emerald-600 border border-slate-100 shadow-sm mr-3 transition-colors">
                                    <i className="fas fa-box text-sm"></i>
                                </div>
                                <span className="text-sm font-semibold text-slate-700 group-hover:text-emerald-900">Ajouter un produit</span>
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Flux d'Activités Récentes */}
                <div className="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 className="text-md font-bold text-slate-900 tracking-tight mb-4">Activité Récente</h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {/* Derniers Inscrits */}
                        <div className="space-y-3">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider">Derniers inscrits</h4>
                            <ul className="divide-y divide-slate-100">
                                {stats.recent_users.length > 0 ? (
                                    stats.recent_users.map((user) => (
                                        <li key={user.id} className="flex items-center justify-between py-3 text-sm">
                                            <div className="flex items-center space-x-2.5">
                                                <div className="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700">
                                                    {user.name ? user.name.charAt(0).toUpperCase() : 'U'}
                                                </div>
                                                <span className="font-medium text-slate-800">{user.name}</span>
                                            </div>
                                            {/* Note: Il est recommandé de formater diffForHumans côté PHP ou via une lib JS comme lucide/date-fns */}
                                            <span className="text-xs text-slate-400 font-medium">{user.formatted_date || 'Récemment'}</span>
                                        </li>
                                    ))
                                ) : (
                                    <li className="py-3 text-sm text-slate-400">Aucune inscription récente.</li>
                                )}
                            </ul>
                        </div>

                        {/* Dernières Parcelles */}
                        <div className="space-y-3">
                            <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider">Dernières parcelles</h4>
                            <ul className="divide-y divide-slate-100">
                                {stats.recent_parcels.length > 0 ? (
                                    stats.recent_parcels.map((parcel) => (
                                        <li key={parcel.id} className="flex items-center justify-between py-3 text-sm">
                                            <div className="flex items-center space-x-2">
                                                <span className="font-medium text-slate-800">{parcel.nom || `Parcelle #${parcel.id}`}</span>
                                            </div>
                                            <span className="text-xs text-slate-400 font-medium">{parcel.formatted_date || 'Récemment'}</span>
                                        </li>
                                    ))
                                ) : (
                                    <li className="py-3 text-sm text-slate-400">Aucune parcelle ajoutée.</li>
                                )}
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            {/* =========================================================================
                GEOGRAPHIC REPARTITION
               ========================================================================= */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {/* Agriculteurs par Région */}
                <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-md font-bold text-slate-900 tracking-tight">Agriculteurs par région</h3>
                        <i className="fas fa-map-marked-alt text-slate-300"></i>
                    </div>
                    {stats.users_by_region.length > 0 ? (
                        <div className="space-y-3.5">
                            {stats.users_by_region.map((item, index) => {
                                const percentage = stats.total_farmers > 0 ? (item.count / stats.total_farmers) * 100 : 0;
                                return (
                                    <div key={index}>
                                        <div className="flex justify-between text-sm mb-1.5 font-medium">
                                            <span className="text-slate-700">{item.region || 'Non spécifiée'}</span>
                                            <span className="text-emerald-600 font-bold">{item.count}</span>
                                        </div>
                                        <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div 
                                                className="bg-emerald-500 h-full rounded-full transition-all duration-500" 
                                                style={{ width: `${percentage}%` }}
                                            />
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <p className="text-slate-400 text-sm py-4">Aucune donnée géographique enregistrée.</p>
                    )}
                </div>

                {/* Cultures par Région */}
                <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-md font-bold text-slate-900 tracking-tight">Cultures par région</h3>
                        <i className="fas fa-chart-pie text-slate-300"></i>
                    </div>
                    {stats.cultures_by_region.length > 0 ? (
                        <div className="space-y-3.5">
                            {stats.cultures_by_region.map((item, index) => {
                                const percentage = stats.total_cultures > 0 ? (item.count / stats.total_cultures) * 100 : 0;
                                return (
                                    <div key={index}>
                                        <div className="flex justify-between text-sm mb-1.5 font-medium">
                                            <span className="text-slate-700">{item.region || 'Non spécifiée'}</span>
                                            <span className="text-indigo-600 font-bold">{item.count}</span>
                                        </div>
                                        <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div 
                                                className="bg-indigo-500 h-full rounded-full transition-all duration-500" 
                                                style={{ width: `${percentage}%` }}
                                            />
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <p className="text-slate-400 text-sm py-4">Aucune culture répertoriée par région.</p>
                    )}
                </div>

            </div>
        </div>
    );
}

// Persistance automatique du Layout d'administration globale d'Agrifober
Dashboard.layout = page => <AdminLayout children={page} user={page.props.auth?.user} />;