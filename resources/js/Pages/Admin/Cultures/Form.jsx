import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';
import FormField, { inputClass } from '@/components/FormField';

export default function CultureForm({ culture }) {
    const isEdit = !!culture;
    const { data, setData, post, put, processing, errors } = useForm({
        nom_commun: culture?.nom_commun || '',
        nom_scientifique: culture?.nom_scientifique || '',
        type: culture?.type || '',
        saison: culture?.saison || '',
        region: culture?.region || '',
        temp_min: culture?.temp_min ?? '',
        temp_max: culture?.temp_max ?? '',
        ph_sol_min: culture?.ph_sol_min ?? '',
        ph_sol_max: culture?.ph_sol_max ?? '',
        besoin_eau_cycle: culture?.besoin_eau_cycle ?? '',
        soil_type: culture?.soil_type || '',
        conseils: culture?.conseils || '',
    });

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) {
            put(`/admin/cultures/${culture.id}`);
        } else {
            post('/admin/cultures');
        }
    };

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title={isEdit ? 'Modifier Culture' : 'Créer Culture'} />

            <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                {isEdit ? 'Modifier Culture' : 'Créer Culture'}
            </h1>

            <form onSubmit={submit} className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <FormField label="Nom commun" error={errors.nom_commun} span>
                        <input
                            type="text"
                            value={data.nom_commun}
                            onChange={(e) => setData('nom_commun', e.target.value)}
                            required
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Nom scientifique" error={errors.nom_scientifique} span>
                        <input
                            type="text"
                            value={data.nom_scientifique}
                            onChange={(e) => setData('nom_scientifique', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Type" error={errors.type}>
                        <select value={data.type} onChange={(e) => setData('type', e.target.value)} required className={inputClass}>
                            <option value="">Choisir...</option>
                            <option value="fruit">Fruit</option>
                            <option value="legume">Légume</option>
                            <option value="cereale">Céréale</option>
                            <option value="legumineuse">Légumineuse</option>
                            <option value="autre">Autre</option>
                        </select>
                    </FormField>

                    <FormField label="Saison" error={errors.saison}>
                        <select value={data.saison} onChange={(e) => setData('saison', e.target.value)} required className={inputClass}>
                            <option value="">Choisir...</option>
                            <option value="printemps">Printemps</option>
                            <option value="ete">Été</option>
                            <option value="automne">Automne</option>
                            <option value="hiver">Hiver</option>
                            <option value="toute_annee">Toute l'année</option>
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

                    <FormField label="Température min (°C)" error={errors.temp_min}>
                        <input
                            type="number"
                            value={data.temp_min}
                            onChange={(e) => setData('temp_min', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Température max (°C)" error={errors.temp_max}>
                        <input
                            type="number"
                            value={data.temp_max}
                            onChange={(e) => setData('temp_max', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="pH sol min" error={errors.ph_sol_min}>
                        <input
                            type="number"
                            step="0.1"
                            value={data.ph_sol_min}
                            onChange={(e) => setData('ph_sol_min', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="pH sol max" error={errors.ph_sol_max}>
                        <input
                            type="number"
                            step="0.1"
                            value={data.ph_sol_max}
                            onChange={(e) => setData('ph_sol_max', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Besoins eau (mm/cycle)" error={errors.besoin_eau_cycle}>
                        <input
                            type="number"
                            value={data.besoin_eau_cycle}
                            onChange={(e) => setData('besoin_eau_cycle', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Type de sol" error={errors.soil_type}>
                        <select value={data.soil_type} onChange={(e) => setData('soil_type', e.target.value)} className={inputClass}>
                            <option value="">Non défini</option>
                            <option value="argileux">Argileux</option>
                            <option value="sableux">Sableux</option>
                            <option value="limoneux">Limoneux</option>
                            <option value="humifere">Humifère</option>
                        </select>
                    </FormField>
                </div>

                <div className="mt-6">
                    <FormField label="Conseils" error={errors.conseils}>
                        <textarea
                            rows={4}
                            value={data.conseils}
                            onChange={(e) => setData('conseils', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>
                </div>

                <div className="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/admin/cultures"
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
