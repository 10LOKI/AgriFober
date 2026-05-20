import React from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Sidebar({ open, setOpen }) {
    const { url } = usePage();
    
    // Détermination stricte de la route active pour éviter les faux positifs (ex: /admin vs /admin/users)
    const isActive = (path) => {
        if (path === '/admin') {
            return url === '/admin';
        }
        return url.startsWith(path);
    };

    // Configuration des items avec les classes Font Awesome appropriées
    const menuItems = [
        { name: 'Dashboard', href: '/admin', icon: 'fas fa-home' },
        { name: 'Agriculteurs', href: '/admin/users', icon: 'fas fa-users' },
        { name: 'Cultures', href: '/admin/cultures', icon: 'fas fa-seedling' },
        { name: 'Produits', href: '/admin/products', icon: 'fas fa-box' },
    ];

    return (
        <>
            {/* Arrière-plan flouté pour le responsive mobile */}
            {open && (
                <div 
                    className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300"
                    onClick={() => setOpen(false)}
                />
            )}

            {/* Structure de la Sidebar */}
            <aside className={`
                fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 shadow-xl 
                transform transition-transform duration-300 ease-in-out flex flex-col border-r border-slate-800
                ${open ? 'translate-x-0' : '-translate-x-full'}
                lg:translate-x-0 lg:static lg:h-screen lg:shrink-0
            `}>
                
                {/* En-tête : Logo & Identité visuelle */}
                <div className="flex items-center justify-between h-16 px-6 border-b border-slate-800 bg-slate-900/50 backdrop-blur-md shrink-0">
                    <div className="flex items-center space-x-2">
                        <div className="bg-emerald-600 p-2 rounded-lg text-white shadow-md shadow-emerald-600/20">
                            <i className="fas fa-leaf text-sm"></i>
                        </div>
                        <span className="text-xl font-bold tracking-wide text-white">Agrifober</span>
                        <span className="text-[10px] uppercase tracking-widest bg-amber-500/20 text-amber-500 px-1.5 py-0.5 rounded font-semibold">Admin</span>
                    </div>
                    
                    {/* Bouton fermeture sur mobile */}
                    <button 
                        className="lg:hidden text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition-colors"
                        onClick={() => setOpen(false)}
                    >
                        <i className="fas fa-times text-lg"></i>
                    </button>
                </div>

                {/* Navigation Principale */}
                <nav className="flex-1 overflow-y-auto mt-5 px-4 space-y-1.5">
                    {menuItems.map((item) => {
                        const active = isActive(item.href);
                        return (
                            <Link
                                key={item.name}
                                href={item.href}
                                className={`
                                    group flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-200
                                    ${active 
                                        ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/10' 
                                        : 'text-slate-400 hover:bg-slate-800 hover:text-white'}
                                `}
                            >
                                <div className="w-8 shrink-0">
                                    <i className={`
                                        ${item.icon} text-lg transition-transform duration-200 group-hover:scale-110
                                        ${active ? 'text-white' : 'text-slate-400 group-hover:text-emerald-500'}
                                    `}></i>
                                </div>
                                <span>{item.name}</span>
                            </Link>
                        );
                    })}
                </nav>

                {/* Footer de la Sidebar */}
                <div className="p-4 border-t border-slate-800 bg-slate-950/40 text-xs text-center text-slate-500 shrink-0">
                    &copy; 2026 Agrifober Management
                </div>
            </aside>
        </>
    );
}