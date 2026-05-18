import React from 'react';

export default function StatsCard({ title, value, icon, color = 'green' }) {
    // Configuration UI sophistiquée basée sur la palette Agrifober
    const themeClasses = {
        green: {
            bgIcon: 'bg-emerald-50 text-emerald-600 border-emerald-100/50',
            glow: 'group-hover:shadow-emerald-600/10'
        },
        orange: {
            bgIcon: 'bg-amber-50 text-amber-600 border-amber-100/50',
            glow: 'group-hover:shadow-amber-600/10'
        },
        blue: {
            bgIcon: 'bg-sky-50 text-sky-600 border-sky-100/50',
            glow: 'group-hover:shadow-sky-600/10'
        },
        purple: {
            bgIcon: 'bg-indigo-50 text-indigo-600 border-indigo-100/50',
            glow: 'group-hover:shadow-indigo-600/10'
        },
        red: {
            bgIcon: 'bg-rose-50 text-rose-600 border-rose-100/50',
            glow: 'group-hover:shadow-rose-600/10'
        },
    };

    // Sélection sécurisée du thème (fallback sur 'green' si la clé n'existe pas)
    const currentTheme = themeClasses[color] || themeClasses.green;

    // Rendu intelligent de l'icône (Texte/Font Awesome VS Élément React)
    const renderIcon = () => {
        if (typeof icon === 'string') {
            return <i className={`${icon} text-xl transition-transform duration-300 group-hover:scale-110`}></i>;
        }
        return <div className="transition-transform duration-300 group-hover:scale-110">{icon}</div>;
    };

    return (
        <div className={`group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300 ease-out hover:-translate-y-1 ${currentTheme.glow}`}>
            <div className="flex items-center justify-between">
                
                {/* Métriques & Informations */}
                <div className="space-y-1.5">
                    <p className="text-sm font-semibold text-slate-500 tracking-wide uppercase text-[11px]">
                        {title}
                    </p>
                    <p className="text-3xl font-bold text-slate-900 tracking-tight transition-all duration-300">
                        {value}
                    </p>
                </div>

                {/* Conteneur d'icône stylisé */}
                <div className={`w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm transition-all duration-300 ${currentTheme.bgIcon}`}>
                    {renderIcon()}
                </div>

            </div>

            {/* Ligne décorative d'ancrage agraire visible uniquement au survol */}
            <div className="absolute bottom-0 left-6 right-6 h-[2px] bg-slate-100 group-hover:bg-emerald-500 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300" />
        </div>
    );
}