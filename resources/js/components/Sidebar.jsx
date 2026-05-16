import React from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Sidebar({ open, setOpen }) {
    const { url } = usePage();
    const isActive = (path) => url.startsWith(path);

    const menuItems = [
        { name: 'Dashboard', href: '/admin', icon: '🏠' },
        { name: 'Agriculteurs', href: '/admin/users', icon: '👨‍🌾' },
        { name: 'Cultures', href: '/admin/cultures', icon: '🌾' },
        { name: 'Produits', href: '/admin/products', icon: '📦' },
        { name: 'Logs IA', href: '/admin/ai-logs', icon: '🤖' },
    ];

    return (
        <>
            {/* Mobile sidebar backdrop */}
            {open && (
                <div 
                    className="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden"
                    onClick={() => setOpen(false)}
                />
            )}

            {/* Sidebar */}
            <div className={`
                fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out
                ${open ? 'translate-x-0' : '-translate-x-full'}
                lg:translate-x-0 lg:static lg:inset-0
            `}>
                <div className="flex items-center justify-between h-16 px-6 border-b">
                    <h1 className="text-xl font-bold text-green-700">Agrifober</h1>
                    <button 
                        className="lg:hidden"
                        onClick={() => setOpen(false)}
                    >
                        ✕
                    </button>
                </div>

                <nav className="mt-6 px-4">
                    {menuItems.map((item) => (
                        <Link
                            key={item.name}
                            href={item.href}
                            className={`
                                flex items-center px-4 py-3 mb-2 rounded-lg transition-colors
                                ${isActive(item.href) 
                                    ? 'bg-green-100 text-green-800 font-semibold' 
                                    : 'text-gray-700 hover:bg-gray-100'}
                            `}
                        >
                            <span className="mr-3 text-lg">{item.icon}</span>
                            {item.name}
                        </Link>
                    ))}
                </nav>
            </div>
        </>
    );
}