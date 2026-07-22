import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';
import FormField, { inputClass } from '@/components/FormField';

export default function ProductForm({ product }) {
    const isEdit = !!product;
    const { data, setData, post, processing, errors } = useForm({
        nom_commercial: product?.nom_commercial || '',
        description: product?.description || '',
        composant_actif: product?.composant_actif || '',
        dosage_recommande: product?.dosage_recommande || '',
        delai_avant_recolte: product?.delai_avant_recolte ?? '',
        type: product?.type || '',
        avantages: product?.avantages || '',
        usage_method: product?.usage_method || '',
        safety_instructions: product?.safety_instructions || '',
        image: null,
        _method: isEdit ? 'put' : 'post',
    });

    const submit = (e) => {
        e.preventDefault();
        // Multipart PUT bodies aren't parsed by PHP — always POST, spoof method via _method.
        post(isEdit ? `/admin/products/${product.id}` : '/admin/products', { forceFormData: true });
    };

    return (
        <AdminLayout>
        <div className="space-y-6">
            <Head title={isEdit ? 'Modifier Produit' : 'Créer Produit'} />

            <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                {isEdit ? 'Modifier Produit' : 'Créer Produit'}
            </h1>

            <form onSubmit={submit} className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 max-w-3xl">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <FormField label="Nom commercial" error={errors.nom_commercial} span>
                        <input
                            type="text"
                            value={data.nom_commercial}
                            onChange={(e) => setData('nom_commercial', e.target.value)}
                            required
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Description" error={errors.description} span>
                        <textarea
                            rows={3}
                            value={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Composant actif" error={errors.composant_actif}>
                        <input
                            type="text"
                            value={data.composant_actif}
                            onChange={(e) => setData('composant_actif', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Dosage recommandé" error={errors.dosage_recommande}>
                        <input
                            type="text"
                            value={data.dosage_recommande}
                            onChange={(e) => setData('dosage_recommande', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Délai avant récolte (jours)" error={errors.delai_avant_recolte}>
                        <input
                            type="number"
                            value={data.delai_avant_recolte}
                            onChange={(e) => setData('delai_avant_recolte', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Type" error={errors.type}>
                        <select value={data.type} onChange={(e) => setData('type', e.target.value)} required className={inputClass}>
                            <option value="">Choisir...</option>
                            <option value="engrais">Engrais</option>
                            <option value="pesticide">Pesticide</option>
                            <option value="fongicide">Fongicide</option>
                            <option value="herbicide">Herbicide</option>
                            <option value="biologique">Biologique</option>
                        </select>
                    </FormField>
                </div>

                <div className="mt-6 space-y-4">
                    <FormField label="Avantages" error={errors.avantages}>
                        <textarea
                            rows={3}
                            value={data.avantages}
                            onChange={(e) => setData('avantages', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Méthode d'utilisation" error={errors.usage_method}>
                        <textarea
                            rows={3}
                            value={data.usage_method}
                            onChange={(e) => setData('usage_method', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Instructions de sécurité" error={errors.safety_instructions}>
                        <textarea
                            rows={2}
                            value={data.safety_instructions}
                            onChange={(e) => setData('safety_instructions', e.target.value)}
                            className={inputClass}
                        />
                    </FormField>

                    <FormField label="Image (optionnelle)" error={errors.image}>
                        <input
                            type="file"
                            accept="image/*"
                            onChange={(e) => setData('image', e.target.files[0] || null)}
                            className={`${inputClass} py-2`}
                        />
                        {isEdit && product.image && (
                            <div className="mt-3">
                                <img
                                    src={`/storage/${product.image}`}
                                    alt="Image actuelle"
                                    className="h-20 w-20 object-cover rounded-xl border border-slate-200"
                                />
                            </div>
                        )}
                    </FormField>
                </div>

                <div className="mt-8 flex justify-end space-x-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/admin/products"
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
