import React from 'react';
import { Link } from '@inertiajs/react';
import AdminLayout from '@/Pages/Admin/layout/AdminLayout';

// ─── Sub-components ──────────────────────────────────────────────────────────

const STAT_CONFIGS = [
    {
        key: 'total_users',
        label: 'Agriculteurs',
        icon: 'fas fa-users',
        colorBg: 'bg-sky-50',
        colorText: 'text-sky-600',
        colorBorder: 'border-sky-100/50',
        colorAccent: 'bg-sky-500',
        hoverGlow: 'card-hover-sky',
        stagger: 'animate-stagger-1',
    },
    {
        key: 'total_parcels',
        label: 'Parcelles',
        icon: 'fas fa-seedling',
        colorBg: 'bg-emerald-50',
        colorText: 'text-emerald-600',
        colorBorder: 'border-emerald-100/50',
        colorAccent: 'bg-emerald-500',
        hoverGlow: 'card-hover-emerald',
        stagger: 'animate-stagger-2',
    },
    {
        key: 'total_cultures',
        label: 'Cultures',
        icon: 'fas fa-leaf',
        colorBg: 'bg-indigo-50',
        colorText: 'text-indigo-600',
        colorBorder: 'border-indigo-100/50',
        colorAccent: 'bg-indigo-500',
        hoverGlow: 'card-hover-indigo',
        stagger: 'animate-stagger-3',
    },
    {
        key: 'total_products',
        label: 'Produits',
        icon: 'fas fa-box',
        colorBg: 'bg-amber-50',
        colorText: 'text-amber-600',
        colorBorder: 'border-amber-100/50',
        colorAccent: 'bg-amber-500',
        hoverGlow: 'card-hover-amber',
        stagger: 'animate-stagger-4',
    },
];

function StatCard({ config, value }) {
    const { label, icon, colorBg, colorText, colorBorder, colorAccent, hoverGlow, stagger } = config;
    return (
        <div
            role="region"
            aria-label={label}
            className={`
                group relative bg-white rounded-2xl border border-slate-100 p-6 shadow-sm
                transition-[transform,box-shadow] duration-300 ease-out hover:-translate-y-1.5
                ${hoverGlow} ${stagger}
            `}
        >
            <div className="flex items-center justify-between">
                <div className="space-y-1.5">
                    <p className="text-[11px] font-bold text-slate-400 tracking-widest uppercase">{label}</p>
                    <p className="text-3xl font-bold text-slate-900 tracking-tight tabular-nums">{value ?? '—'}</p>
                </div>
                <div className={`
                    w-12 h-12 rounded-xl flex items-center justify-center border shadow-sm
                    transition-[color,background-color,transform] duration-300
                    group-hover:scale-110 ${colorBg} ${colorText} ${colorBorder}
                `}>
                    <i className={`${icon} text-xl`} aria-hidden="true" />
                </div>
            </div>
            {/* Bottom accent bar */}
            <div className={`
                absolute bottom-0 left-6 right-6 h-[2px] ${colorAccent} rounded-full
                opacity-0 group-hover:opacity-100 transition-[opacity,background-color] duration-300
            `} />
        </div>
    );
}

function Vue360({ stats }) {
    const approved = stats.total_users - (stats.pending_users_count || 0);
    const approvalRate = stats.total_users > 0
        ? Math.round((approved / stats.total_users) * 100)
        : 0;
    const regionCount = stats.users_by_region?.length || 0;

    const getCellBorder = (i) => [
        i % 2 === 0 && 'border-r border-slate-100',
        i < 2      && 'border-b border-slate-100 lg:border-b-0',
        i < 3      && 'lg:border-r lg:border-slate-100',
    ].filter(Boolean).join(' ');

    const metrics = [
        {
            label: 'Agriculteurs',
            value: stats.total_users,
            sub: <><span className="font-bold text-amber-600">{stats.pending_users_count || 0}</span>{' '}en attente</>,
        },
        {
            label: 'Variétés Actives',
            value: stats.total_cultures,
            sub: <><span className="font-bold text-emerald-600">{stats.total_parcels}</span>{' '}parcelles</>,
        },
        {
            label: 'Couverture Régionale',
            value: regionCount,
            sub: 'régions avec activité',
        },
        {
            label: "Taux d'Approbation",
            value: `${approvalRate}%`,
            sub: (
                <div className="w-full bg-slate-100 h-1 rounded-full overflow-hidden mt-2">
                    <div
                        className="bg-emerald-500 h-full rounded-full origin-left"
                        style={{ transform: `scaleX(${approvalRate / 100})` }}
                        aria-hidden="true"
                    />
                </div>
            ),
        },
    ];

    return (
        <section aria-label="Vue 360° synthèse agricole" className="animate-slide-up">
            <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="px-6 py-3.5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50/80 to-emerald-50/30">
                    <div className="flex items-center space-x-2">
                        <div className="w-2 h-2 bg-emerald-500 rounded-full animate-pulse" aria-hidden="true" />
                        <h3 className="text-xs font-bold text-slate-700 tracking-widest uppercase">Vue 360°</h3>
                        <span className="text-xs text-slate-400 font-medium">Synthèse Agricole</span>
                    </div>
                    <span className="text-[10px] text-emerald-600 font-mono tracking-widest uppercase">Temps réel</span>
                </div>
                <div className="grid grid-cols-2 lg:grid-cols-4">
                    {metrics.map(({ label, value, sub }, i) => (
                        <div key={label} className={`p-5 space-y-1 group hover:bg-slate-50/50 transition-colors duration-150 ${getCellBorder(i)}`}>
                            <p className="text-[10px] uppercase font-bold tracking-widest text-slate-400">{label}</p>
                            <p className="text-2xl font-bold text-slate-900 tracking-tight tabular-nums">{value}</p>
                            <div className="text-xs text-slate-500">{sub}</div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}

function ApprovalQueue({ count, pendingUsers = [] }) {
    if (!count) return null;
    return (
        <section aria-label="File d'attente d'approbation" aria-live="polite" className="animate-slide-up">
            <div className="rounded-2xl border border-amber-200/70 shadow-sm overflow-hidden"
                 style={{ background: 'linear-gradient(135deg, #fffbeb 0%, #fef3c7/40 100%)' }}>
                <div className="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4
                                border-b border-amber-100/80">
                    <div className="flex items-center space-x-3">
                        <div className="relative shrink-0">
                            <div className="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-400/25">
                                <i className="fas fa-user-clock text-white text-sm" aria-hidden="true" />
                            </div>
                            <span
                                className="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm"
                                aria-label={`${count} en attente`}
                            >
                                {count > 9 ? '9+' : count}
                            </span>
                        </div>
                        <div>
                            <h3 className="text-sm font-bold text-slate-900">File d'attente d'approbation</h3>
                            <p className="text-xs text-slate-500">
                                {count} compte{count > 1 ? 's' : ''} requièr{count > 1 ? 'ent' : 't'} votre validation
                            </p>
                        </div>
                    </div>
                    <Link
                        href="/admin/users"
                        data={{ filter: 'pending' }}
                        aria-label="Accéder aux comptes en attente"
                        className="btn-agri inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-white font-semibold text-xs shadow-sm shrink-0"
                    >
                        <i className="fas fa-arrow-right mr-1.5 text-[10px]" aria-hidden="true" />
                        Traiter les demandes
                    </Link>
                </div>
                {pendingUsers.length > 0 && (
                    <ul className="divide-y divide-amber-100/60" role="list">
                        {pendingUsers.slice(0, 5).map((user) => (
                            <li key={user.id} className="flex items-center justify-between px-6 py-3 hover:bg-amber-50/60 transition-colors duration-100">
                                <div className="flex items-center space-x-3">
                                    <div className="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center text-xs font-bold text-amber-700 shrink-0">
                                        {user.name?.charAt(0).toUpperCase() || 'U'}
                                    </div>
                                    <div>
                                        <p className="text-sm font-semibold text-slate-800">{user.name}</p>
                                        <p className="text-xs text-slate-400">{user.email}</p>
                                    </div>
                                </div>
                                <span className="text-xs text-slate-400">{user.formatted_date || 'Récemment'}</span>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </section>
    );
}

function RegionBar({ label, count, total, barClass, countClass }) {
    const pct = total > 0 ? (count / total) * 100 : 0;
    return (
        <div>
            <div className="flex justify-between text-sm mb-1.5 font-medium">
                <span className="text-slate-700">{label || 'Non spécifiée'}</span>
                <span className={`font-bold ${countClass}`}>{count}</span>
            </div>
            <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div
                    className={`h-full rounded-full origin-left ${barClass}`}
                    style={{ transform: `scaleX(${pct / 100})` }}
                    aria-hidden="true"
                />
            </div>
        </div>
    );
}

// ─── Page component ───────────────────────────────────────────────────────────

export default function Dashboard({ stats }) {
    return (
        <AdminLayout>
        <div className="space-y-7">

            {/* KPI Grid — staggered entrance */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                {STAT_CONFIGS.map((config) => (
                    <StatCard key={config.key} config={config} value={stats?.[config.key]} />
                ))}
            </div>

            {/* Vue 360° high-density metrics */}
            <Vue360 stats={stats} />

            {/* Approval queue */}
            <ApprovalQueue count={stats.pending_users_count} pendingUsers={stats.pending_users || []} />

            {/* Quick actions + Recent activity */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-5 animate-slide-up">

                <div className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 className="text-xs font-bold text-slate-500 tracking-widest uppercase mb-4">Actions Rapides</h3>
                    <div className="space-y-2.5">
                        {[
                            { href: '/admin/users',           icon: 'fas fa-user-shield', label: 'Gérer les comptes',   color: 'group-hover:text-emerald-600 group-hover:bg-emerald-50' },
                            { href: '/admin/cultures/create', icon: 'fas fa-plus',         label: 'Ajouter une culture', color: 'group-hover:text-teal-600 group-hover:bg-teal-50' },
                            { href: '/admin/products/create', icon: 'fas fa-box',          label: 'Ajouter un produit',  color: 'group-hover:text-indigo-600 group-hover:bg-indigo-50' },
                        ].map(({ href, icon, label, color }) => (
                            <Link
                                key={href}
                                href={href}
                                aria-label={label}
                                className="group flex items-center p-3.5 bg-slate-50 hover:bg-slate-50 border border-slate-100 hover:border-slate-200 rounded-xl transition-[background-color,border-color,box-shadow] duration-200 hover:shadow-sm"
                            >
                                <div className={`bg-white p-2 rounded-lg text-slate-400 border border-slate-100 shadow-sm mr-3 transition-[color,background-color] duration-200 ${color}`}>
                                    <i className={`${icon} text-sm`} aria-hidden="true" />
                                </div>
                                <span className="text-sm font-semibold text-slate-700 group-hover:text-slate-900">{label}</span>
                                <i className="fas fa-arrow-right ml-auto text-[9px] text-slate-300 group-hover:text-slate-400 transition-[color,transform] duration-200 group-hover:translate-x-0.5" aria-hidden="true" />
                            </Link>
                        ))}
                    </div>
                </div>

                <div className="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 className="text-xs font-bold text-slate-500 tracking-widest uppercase mb-4">Activité Récente</h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div className="space-y-3">
                            <h4 className="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Derniers inscrits</h4>
                            <ul className="divide-y divide-slate-100">
                                {stats.recent_users?.length > 0 ? stats.recent_users.map((user) => (
                                    <li key={user.id} className="flex items-center justify-between py-3">
                                        <div className="flex items-center space-x-2.5">
                                            <div className="w-7 h-7 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">
                                                {user.name?.charAt(0).toUpperCase() || 'U'}
                                            </div>
                                            <span className="text-sm font-medium text-slate-800">{user.name}</span>
                                        </div>
                                        <span className="text-xs text-slate-400">{user.formatted_date || 'Récemment'}</span>
                                    </li>
                                )) : (
                                    <li className="py-3 text-sm text-slate-400">Aucune inscription récente.</li>
                                )}
                            </ul>
                        </div>

                        <div className="space-y-3">
                            <h4 className="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dernières parcelles</h4>
                            <ul className="divide-y divide-slate-100">
                                {stats.recent_parcels?.length > 0 ? stats.recent_parcels.map((parcel) => (
                                    <li key={parcel.id} className="flex items-center justify-between py-3">
                                        <span className="text-sm font-medium text-slate-800">{parcel.nom || `Parcelle #${parcel.id}`}</span>
                                        <span className="text-xs text-slate-400">{parcel.formatted_date || 'Récemment'}</span>
                                    </li>
                                )) : (
                                    <li className="py-3 text-sm text-slate-400">Aucune parcelle ajoutée.</li>
                                )}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {/* Geographic distributions */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-5 animate-slide-up">
                {[
                    {
                        title: 'Agriculteurs par région',
                        icon: 'fas fa-map-marked-alt',
                        data: stats.users_by_region,
                        total: stats.total_users,
                        barClass: 'bg-emerald-500',
                        countClass: 'text-emerald-600',
                        empty: 'Aucune donnée géographique.',
                    },
                    {
                        title: 'Cultures par région',
                        icon: 'fas fa-chart-pie',
                        data: stats.cultures_by_region,
                        total: stats.total_cultures,
                        barClass: 'bg-indigo-500',
                        countClass: 'text-indigo-600',
                        empty: 'Aucune culture répertoriée.',
                    },
                ].map(({ title, icon, data, total, barClass, countClass, empty }) => (
                    <div key={title} className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <div className="flex items-center justify-between mb-5">
                            <h3 className="text-sm font-bold text-slate-900 tracking-tight">{title}</h3>
                            <i className={`${icon} text-slate-200 text-lg`} aria-hidden="true" />
                        </div>
                        {data?.length > 0 ? (
                            <div className="space-y-3.5">
                                {data.map((item, i) => (
                                    <RegionBar
                                        key={i}
                                        label={item.region}
                                        count={item.count}
                                        total={total}
                                        barClass={barClass}
                                        countClass={countClass}
                                    />
                                ))}
                            </div>
                        ) : (
                            <p className="text-slate-400 text-sm py-4">{empty}</p>
                        )}
                    </div>
                ))}
            </div>
        </div>
        </AdminLayout>
    );
}
