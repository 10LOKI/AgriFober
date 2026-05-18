import React from 'react';
import { usePage } from '@inertiajs/react';
import AdminLayout from '../Pages/Admin/layout/AdminLayout';

export default function AppLayout({ children }) {
    // Récupération automatique de l'utilisateur connecté depuis les partages Inertia 
    // afin que le layout parent mette à jour l'avatar et le nom en temps réel.
    const { auth } = usePage().props;

    return (
        <AdminLayout user={auth?.user}>
            {/* 
                Wrapper structurel optionnel pour appliquer un effet de fondu (Fade-in) 
                discret lors du chargement ou du switch entre les différentes pages admin.
            */}
            <div className="animate-fadeIn duration-200 ease-out h-full w-full">
                {children}
            </div>
        </AdminLayout>
    );
}