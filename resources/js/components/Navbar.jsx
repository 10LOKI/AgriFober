import React from 'react';
import { Link, router } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';

export default function Navbar({ user, toggleSidebar }) {
    const { url } = usePage();
    const isAdmin = user?.role === 'admin';

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <nav className="bg-white shadow-sm border-b">
            <div className="px-4 py-3 flex items-center justify-between">
                <div className="flex items-center">
                    <button 
                        onClick={toggleSidebar}
                        className="lg:hidden mr-4 text-gray-600 hover:text-gray-900"
                    >
                        ☰
                    </button>
                    <h2 className="text-lg font-semibold text-gray-800">
                        {url === '/admin' ? 'Dashboard' : 
                         url.startsWith('/admin/users') ? 'Gestion Agriculteurs' :
                         url.startsWith('/admin/cultures') ? 'Gestion Cultures' :
                         url.startsWith('/admin/products') ? 'Gestion Produits' :
                         url.startsWith('/admin/regions') ? 'Gestion Régions' :
                         'Administration'}
                    </h2>
                </div>

                <div className="flex items-center space-x-4">
                    <div className="flex items-center">
                        <div className="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold">
                            {user?.name?.charAt(0) || 'A'}
                        </div>
                        <span className="ml-2 text-sm font-medium text-gray-700 hidden md:inline">
                            {user?.name || 'Admin'}
                        </span>
                    </div>

                    <Link 
                        href="/"
                        className="text-sm text-green-600 hover:text-green-800 hidden md:block"
                    >
                        Voir site
                    </Link>

                    <button
                        onClick={handleLogout}
                        className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                    >
                        Déconnexion
                    </button>
                </div>
            </div>
        </nav>
    );
}