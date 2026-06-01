import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import Sidebar from '@/components/Sidebar';
import Navbar from '@/components/Navbar';

export default function AdminLayout({ children }) {
    const { auth } = usePage().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    return (
        <div className="min-h-screen text-slate-800 antialiased font-sans flex overflow-hidden">
            {/* Sidebar owns its mobile overlay */}
            <Sidebar open={sidebarOpen} setOpen={setSidebarOpen} />

            <div className="flex-1 flex flex-col h-screen overflow-hidden">
                <Navbar
                    user={auth?.user}
                    toggleSidebar={() => setSidebarOpen((s) => !s)}
                    sidebarOpen={sidebarOpen}
                />
                {/* admin-surface applies the subtle dot-grid background */}
                <main role="main" className="flex-1 overflow-y-auto p-6 admin-surface">
                    <div className="max-w-7xl mx-auto h-full">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
