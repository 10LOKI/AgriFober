import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';
import FormField, { inputClass } from '@/components/FormField';

export default function UserForm({ user }) {
    const isEdit = !!user;
    const { data, setData, post, put, processing, errors } = useForm({
        name: user?.name || '',
        username: user?.username || '',
        email: user?.email || '',
        role: user?.role || 'agriculteur',
        region: user?.region || '',
        experience_level: user?.experience_level || '',
        password: '',
        password_confirmation: '',
        is_approved: user?.is_approved || false,
    });

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) {
            put(`/admin/users/${user.id}`);
        } else {
            post('/admin/users');
        }
    };

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title={isEdit ? 'Modifier Agriculteur' : 'Créer Agriculteur'} />

            <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                {isEdit ? 'Modifier Agriculteur' : 'Créer Agriculteur'}
            </h1>

            <form onSubmit={submit} className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-2xl">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <FormField label="Nom" error={errors.name}>
                        <input
                            type="text"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            required
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Nom d'utilisateur" error={errors.username}>
                        <input
                            type="text"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}
                            required
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Email" error={errors.email}>
                        <input
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            required
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Rôle" error={errors.role}>
                        <select
                            value={data.role}
                            onChange={(e) => setData('role', e.target.value)}
                            required
                            className={inputClass}
                        >
                            <option value="agriculteur">Agriculteur</option>
                            <option value="technicien">Technicien</option>
                            <option value="admin">Admin</option>
                        </select>
                    </FormField>

                    <FormField label="Région" error={errors.region}>
                        <input
                            type="text"
                            value={data.region}
                            onChange={(e) => setData('region', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Niveau d'expérience" error={errors.experience_level}>
                        <select
                            value={data.experience_level}
                            onChange={(e) => setData('experience_level', e.target.value)}
                            className={inputClass}
                        >
                            <option value="">Non défini</option>
                            <option value="debutant">Débutant</option>
                            <option value="intermediaire">Intermédiaire</option>
                            <option value="expert">Expert</option>
                        </select>
                    </FormField>
                </div>

                {!isEdit && (
                    <div className="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <FormField label="Mot de passe" error={errors.password}>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                required
                                className={inputClass}
                            />
                        </FormField>

                        <FormField label="Confirmer le mot de passe" error={errors.password_confirmation}>
                            <input
                                type="password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                required
                                className={inputClass}
                            />
                        </FormField>
                    </div>
                )}

                <div className="mt-6 flex items-center pt-2">
                    <input
                        type="checkbox"
                        id="is_approved"
                        checked={data.is_approved}
                        onChange={(e) => setData('is_approved', e.target.checked)}
                        className="h-4 w-4 text-emerald-600 focus:ring-emerald-500/30 border-slate-300 rounded transition-all cursor-pointer"
                    />
                    <label htmlFor="is_approved" className="ml-2.5 text-sm font-medium text-slate-800 cursor-pointer select-none">
                        Compte approuvé
                    </label>
                </div>

                <div className="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/admin/users"
                        className="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-50 transition-colors"
                    >
                        Annuler
                    </Link>
                    <button
                        type="submit"
                        disabled={processing}
                        className="btn-agri text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-sm disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        {isEdit ? 'Mettre à jour' : 'Créer'}
                    </button>
                </div>
            </form>
        </div>
        </AdminLayout>
    );
}
