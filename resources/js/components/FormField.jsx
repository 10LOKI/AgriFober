import React from 'react';

export const inputClass =
    'input-agri w-full border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none bg-white text-sm text-slate-800';

export default function FormField({ label, error, span, children }) {
    return (
        <div className={span ? 'md:col-span-2' : ''}>
            <label className="block text-sm font-semibold text-slate-700 mb-2">{label}</label>
            {children}
            {error && <p className="text-red-500 text-xs mt-1.5 font-medium">{error}</p>}
        </div>
    );
}
