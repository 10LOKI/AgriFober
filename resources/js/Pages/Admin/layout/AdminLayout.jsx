import React, { useState } from 'react';

export default function AdminLayout({ children, user }) {
    const [sidebarOpen, setSidebarOpen] = useState(false); // Mobile-first : fermé par défaut sur petit écran

    return (
        <div className="min-h-screen bg-gray-50/50 text-slate-800 antialiased font-sans flex overflow-hidden">
            
            {/* =========================================================================
                SIDEBAR (Barre Latérale)
               ========================================================================= */}
            <aside 
                className={`fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 shadow-xl 
                transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex lg:flex-col
                border-r border-slate-800
                ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}
            >
                {/* En-tête Sidebar : Logo & Marque */}
                <div className="flex items-center justify-between h-16 px-6 border-b border-slate-800 bg-slate-900/50 backdrop-blur-md shrink-0">
                    <div className="flex items-center space-x-2">
                        <div className="bg-emerald-600 p-2 rounded-lg text-white shadow-md shadow-emerald-600/20">
                            <i className="fas fa-leaf text-sm"></i>
                        </div>
                        <span className="text-xl font-bold tracking-wide text-white">Agrifober</span>
                        <span className="text-[10px] uppercase tracking-widest bg-amber-500/20 text-amber-500 px-1.5 py-0.5 rounded font-semibold">Admin</span>
                    </div>
                    {/* Bouton fermeture sur Mobile */}
                    <button 
                        onClick={() => setSidebarOpen(false)}
                        className="lg:hidden text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800 transition-colors"
                    >
                        <i className="fas fa-times text-lg"></i>
                    </button>
                </div>

                {/* Liens de Navigation */}
                <nav className="flex-1 overflow-y-auto mt-4 px-4 space-y-1.5">
                    {/* Exemple d'un lien Actif */}
                    <a href="#dashboard" className="group flex items-center px-4 py-3 rounded-xl bg-emerald-600 text-white font-medium shadow-md shadow-emerald-600/10 transition-all duration-200">
                        <i className="fas fa-home mr-3 text-lg transition-transform duration-200 group-hover:scale-110 text-white"></i>
                        Dashboard
                    </a>
                    
                    {/* Liens Inactifs standards */}
                    <a href="#agriculteurs" className="group flex items-center px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                        <i className="fas fa-users mr-3 text-lg transition-transform duration-200 group-hover:scale-110 text-slate-400 group-hover:text-emerald-600"></i>
                        Agriculteurs
                    </a>

                    <a href="#cultures" className="group flex items-center px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                        <i className="fas fa-seedling mr-3 text-lg transition-transform duration-200 group-hover:scale-110 text-slate-400 group-hover:text-emerald-600"></i>
                        Cultures
                    </a>

                    <a href="#produits" className="group flex items-center px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-200">
                        <i className="fas fa-box mr-3 text-lg transition-transform duration-200 group-hover:scale-110 text-slate-400 group-hover:text-emerald-600"></i>
                        Produits
                    </a>
                </nav>

                {/* Footer Sidebar */}
                <div className="p-4 border-t border-slate-800 bg-slate-950/40 text-xs text-center text-slate-500 shrink-0">
                    &copy; 2026 Agrifober
                </div>
            </aside>

            {/* Overlay Mobile (Flou d'arrière-plan quand la sidebar est ouverte) */}
            {sidebarOpen && (
                <div 
                    className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            {/* =========================================================================
                CONTENU PRINCIPAL & NAVBAR
               ========================================================================= */}
            <div className="flex-1 flex flex-col h-screen overflow-hidden">
                
                {/* Navbar supérieure */}
                <header class="bg-white border-b border-slate-200 h-16 flex items-center shrink-0 z-30">
                    <div className="w-full flex items-center justify-between px-6">
                        
                        {/* Gauche : Bouton Burger & Titre Dynamique */}
                        <div className="flex items-center space-x-4">
                            <button 
                                onClick={() => setSidebarOpen(!sidebarOpen)}
                                className="text-slate-500 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100 transition-colors lg:hidden"
                            >
                                <i className="fas fa-bars text-xl"></i>
                            </button>
                            <h2 className="text-xl font-bold text-slate-900 tracking-tight">
                                Administration
                            </h2>
                        </div>

                        {/* Droite : Actions utilisateur & Profil */}
                        <div className="flex items-center space-x-4">
                            <a href="/" target="_blank" rel="noopener noreferrer" className="hidden md:inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors group">
                                <i className="fas fa-external-link-alt mr-2 text-xs transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                                Voir site public
                            </a>

                            <div className="hidden md:block h-6 w-px bg-slate-200" />

                            {/* Badge Profil */}
                            <div className="flex items-center space-x-3 bg-slate-50 border border-slate-100 py-1 pl-1.5 pr-3 rounded-full">
                                <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-semibold text-sm shadow-sm ring-2 ring-white">
                                    {user?.name ? user.name.charAt(0).toUpperCase() : 'A'}
                                </div>
                                <span className="text-sm font-semibold text-slate-900 hidden sm:inline">
                                    {user?.name || 'Admin'}
                                </span>
                            </div>

                            {/* Déconnexion */}
                            <button 
                                className="inline-flex items-center justify-center bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 p-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:shadow-sm"
                                title="Déconnexion"
                            >
                                <i className="fas fa-power-off"></i>
                            </button>
                        </div>
                    </div>
                </header>

                {/* Zone d'injection des pages enfants */}
                <main className="flex-1 overflow-y-auto p-6 bg-slate-50/50">
                    <div className="max-w-7xl mx-auto h-full">
                        {children}
                    </div>
                </main>
            </div>

        </div>
    );
}