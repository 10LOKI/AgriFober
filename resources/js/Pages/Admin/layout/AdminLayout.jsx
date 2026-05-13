import React, { useState } from 'react';
import Sidebar from '../../components/Sidebar';
import Navbar from '../../components/Navbar';

export default function AdminLayout({ children, user }) {
    const [sidebarOpen, setSidebarOpen] = useState(true);

    return (
        <div className="min-h-screen bg-gray-100">
            <Sidebar open={sidebarOpen} setOpen={setSidebarOpen} />
            <div className={`transition-all duration-300 ${sidebarOpen ? 'ml-64' : 'ml-16'}`}>
                <Navbar user={user} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
                <main className="p-6">
                    {children}
                </main>
            </div>
        </div>
    );
}