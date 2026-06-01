import React, { useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';

const ROUTE_TITLES = [
    ['/admin/users',    'Gestion des Agriculteurs'],
    ['/admin/cultures', 'Gestion des Cultures'],
    ['/admin/products', 'Gestion des Produits'],
    ['/admin/ai-logs',  'Logs Système IA'],
];

function usePageTitle() {
    const { url } = usePage();
    if (url === '/admin') return 'Tableau de bord';
    const match = ROUTE_TITLES.find(([prefix]) => url.startsWith(prefix));
    return match ? match[1] : 'Administration';
}

export default function Navbar({ user, toggleSidebar, sidebarOpen = false }) {
    const [loggingOut, setLoggingOut] = useState(false);
    const pageTitle = usePageTitle();

    const handleLogout = () => {
        setLoggingOut(true);
        router.post('/logout', {}, { onError: () => setLoggingOut(false) });
    };

    return (
        <header className="bg-white border-b border-slate-200 h-16 flex items-center shrink-0 z-30">
            <div className="w-full flex items-center justify-between px-6">

                {/* Left: sidebar toggle + dynamic page title */}
                <div className="flex items-center space-x-2">
                    <button
                        onClick={toggleSidebar}
                        aria-label="Ouvrir le menu de navigation"
                        aria-expanded={sidebarOpen}
                        aria-controls="sidebar-nav"
                        className="text-slate-500 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition-[color,background-color] duration-200 lg:hidden"
                    >
                        <i className="fas fa-bars text-xl" aria-hidden="true" />
                    </button>
                    <h2 className="text-xl font-bold text-slate-900 tracking-tight lg:pl-0 pl-1">
                        {pageTitle}
                    </h2>
                </div>

                {/* Right: global actions + user identity */}
                <div className="flex items-center space-x-4">
                    <Link
                        href="/"
                        aria-label="Voir le site public Agrifober"
                        className="hidden md:inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-[color,background-color] duration-200 group"
                    >
                        <i
                            className="fas fa-external-link-alt mr-2 text-xs transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                            aria-hidden="true"
                        />
                        Voir site public
                    </Link>

                    <div className="hidden md:block h-6 w-px bg-slate-200" aria-hidden="true" />

                    <div className="flex items-center space-x-3 bg-slate-50 border border-slate-100 py-1 pl-1.5 pr-3 rounded-full shadow-sm">
                        <div
                            className="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-semibold text-sm shadow-inner ring-2 ring-white"
                            aria-hidden="true"
                        >
                            {user?.name ? user.name.charAt(0).toUpperCase() : 'A'}
                        </div>
                        <span className="text-sm font-semibold text-slate-700 hidden sm:inline">
                            {user?.name || 'Administrateur'}
                        </span>
                    </div>

                    <button
                        onClick={handleLogout}
                        disabled={loggingOut}
                        aria-label="Déconnexion du tableau de bord"
                        aria-busy={loggingOut}
                        className="inline-flex items-center justify-center bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 p-2.5 rounded-xl text-sm font-medium transition-[color,background-color,box-shadow] duration-200 hover:shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i
                            className={`fas ${loggingOut ? 'fa-spinner fa-spin' : 'fa-power-off'}`}
                            aria-hidden="true"
                        />
                    </button>
                </div>
            </div>
        </header>
    );
}
