import React from 'react';
import { Link, usePage } from '@inertiajs/react';

function useActiveRoute() {
    const { url } = usePage();
    return (path) => path === '/admin' ? url === '/admin' : url.startsWith(path);
}

const NAV_ITEMS = [
    { name: 'Dashboard',    href: '/admin',          icon: 'fas fa-home' },
    { name: 'Agriculteurs', href: '/admin/users',     icon: 'fas fa-users' },
    { name: 'Cultures',     href: '/admin/cultures',  icon: 'fas fa-seedling' },
    { name: 'Produits',     href: '/admin/products',  icon: 'fas fa-box' },
];

export default function Sidebar({ open, setOpen }) {
    const isActive = useActiveRoute();

    return (
        <>
            {/* Mobile overlay */}
            <div
                aria-hidden="true"
                onClick={() => setOpen(false)}
                className={`
                    fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden
                    transition-opacity duration-300
                    ${open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}
                `}
            />

            <aside
                aria-label="Navigation principale"
                className={`
                    fixed inset-y-0 left-0 z-50 w-64 flex flex-col border-r border-slate-800/60
                    transition-transform duration-300 ease-in-out
                    ${open ? 'translate-x-0' : '-translate-x-full'}
                    lg:translate-x-0 lg:static lg:h-screen lg:shrink-0
                `}
                style={{
                    background: 'linear-gradient(180deg, #0b1f18 0%, #0f2920 40%, #0a1e17 100%)',
                }}
            >
                {/* Background accent — subtle radial glow at top */}
                <div
                    className="absolute top-0 left-0 right-0 h-32 pointer-events-none"
                    style={{
                        background: 'radial-gradient(ellipse at 50% 0%, rgba(5,150,105,0.15) 0%, transparent 70%)',
                    }}
                    aria-hidden="true"
                />

                {/* Brand identity */}
                <div className="relative flex items-center justify-between h-16 px-5 border-b border-slate-700/50 shrink-0">
                    <div className="flex items-center space-x-2.5">
                        <div className="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl text-white shadow-lg shadow-emerald-500/25 animate-glow-pulse">
                            <i className="fas fa-leaf text-sm" aria-hidden="true" />
                        </div>
                        <span className="text-lg font-bold tracking-wide text-white">Agrifober</span>
                        <span className="text-[9px] uppercase tracking-widest bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded-md font-bold border border-emerald-500/20">
                            Admin
                        </span>
                    </div>
                    <button
                        onClick={() => setOpen(false)}
                        aria-label="Fermer le menu de navigation"
                        className="lg:hidden text-slate-500 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-[color,background-color] duration-200"
                    >
                        <i className="fas fa-times" aria-hidden="true" />
                    </button>
                </div>

                {/* Nav label */}
                <div className="relative px-5 pt-5 pb-2">
                    <p className="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Menu principal</p>
                </div>

                {/* Primary navigation */}
                <nav className="relative flex-1 overflow-y-auto px-3 space-y-1 pb-4" aria-label="Menu principal">
                    {NAV_ITEMS.map((item) => {
                        const active = isActive(item.href);
                        return (
                            <Link
                                key={item.name}
                                href={item.href}
                                aria-current={active ? 'page' : undefined}
                                className={`
                                    group relative flex items-center px-4 py-3 rounded-xl font-medium text-sm
                                    transition-[color,background-color,box-shadow,transform] duration-200
                                    ${active
                                        ? 'nav-item-active text-white'
                                        : 'text-slate-400 hover:bg-white/8 hover:text-slate-100'}
                                `}
                            >
                                {/* Active left indicator */}
                                {active && (
                                    <span className="absolute left-0 top-2 bottom-2 w-0.5 rounded-full bg-emerald-300/80" aria-hidden="true" />
                                )}

                                <div className="w-8 shrink-0 flex items-center">
                                    <i
                                        className={`
                                            ${item.icon} text-base
                                            transition-transform duration-200 group-hover:scale-110
                                            ${active ? 'text-white' : 'text-slate-500 group-hover:text-emerald-400'}
                                        `}
                                        aria-hidden="true"
                                    />
                                </div>
                                <span className="tracking-tight">{item.name}</span>

                                {/* Hover arrow hint */}
                                {!active && (
                                    <i
                                        className="fas fa-chevron-right text-[9px] ml-auto text-slate-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200 -translate-x-1 group-hover:translate-x-0 transition-transform"
                                        aria-hidden="true"
                                    />
                                )}
                            </Link>
                        );
                    })}
                </nav>

                {/* Footer */}
                <div className="relative p-4 border-t border-slate-800/60 shrink-0">
                    <p className="text-[10px] text-center text-slate-600">&copy; 2026 Agrifober Management</p>
                </div>
            </aside>
        </>
    );
}
