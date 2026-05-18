import React from 'react';
import { Link, router, usePage } from '@inertiajs/react';

export default function Navbar({ user, toggleSidebar }) {
    const { url } = usePage();

    const handleLogout = () => {
        router.post('/logout');
    };

    // Mapping propre pour les titres dynamiques de la Topbar
    const getPageTitle = () => {
        if (url === '/admin') return 'Tableau de bord';
        if (url.startsWith('/admin/users')) return 'Gestion des Agriculteurs';
        if (url.startsWith('/admin/cultures')) return 'Gestion des Cultures';
        if (url.startsWith('/admin/products')) return 'Gestion des Produits';
        if (url.startsWith('/admin/ai-logs')) return 'Logs Système IA';
        return 'Administration';
    };

    return (
        <header className="bg-white border-b border-slate-200 h-16 flex items-center shrink-0 z-30">
            <div className="w-full flex items-center justify-between px-6">
                
                {/* Gauche : Contrôle Sidebar Mobile & Titre de Page */}
                <div className="flex items-center space-x-2">
                    <button 
                        onClick={toggleSidebar}
                        className="text-slate-500 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition-colors lg:hidden"
                        title="Ouvrir le menu"
                    >
                        <i className="fas fa-bars text-xl"></i>
                    </button>
                    <h2 className="text-xl font-bold text-slate-900 tracking-tight lg:pl-0 pl-1">
                        {getPageTitle()}
                    </h2>
                </div>

                {/* Droite : Profil & Actions Globales */}
                <div className="flex items-center space-x-4">
                    
                    {/* Lien vers l'application frontend publique */}
                    <Link 
                        href="/"
                        className="hidden md:inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors group"
                    >
                        <i className="fas fa-external-link-alt mr-2 text-xs transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        Voir site public
                    </Link>

                    {/* Séparateur visuel */}
                    <div className="hidden md:block h-6 w-px bg-slate-200" />

                    {/* Badge Utilisateur / Profil connecté */}
                    <div className="flex items-center space-x-3 bg-slate-50 border border-slate-100 py-1 pl-1.5 pr-3 rounded-full shadow-sm">
                        <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-semibold text-sm shadow-inner ring-2 ring-white">
                            {user?.name ? user.name.charAt(0).toUpperCase() : 'A'}
                        </div>
                        <span className="text-sm font-semibold text-slate-700 hidden sm:inline">
                            {user?.name || 'Administrateur'}
                        </span>
                    </div>

                    {/* Bouton d'action Déconnexion */}
                    <button
                        onClick={handleLogout}
                        className="inline-flex items-center justify-center bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 p-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:shadow-sm"
                        title="Déconnexion du tableau de bord"
                    >
                        <i className="fas fa-power-off"></i>
                    </button>

                </div>
            </div>
        </header>
    );
}