import React from 'react';
import AdminLayout from '../Pages/Admin/layout/AdminLayout';

export default function AppLayout({ children }) {
    return (
        <AdminLayout>
            {children}
        </AdminLayout>
    );
}