import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

const fadeUp = {
    hidden: { opacity: 0, y: 24 },
    show: { opacity: 1, y: 0 },
};

const stagger = {
    hidden: {},
    show: { transition: { staggerChildren: 0.12 } },
};

const features = [
    {
        icon: 'fa-seedling',
        title: 'Suivi des cultures',
        desc: 'Enregistrez surfaces, coordonnées et scores de chaque parcelle en temps réel.',
    },
    {
        icon: 'fa-boxes-stacked',
        title: 'Gestion des produits',
        desc: 'Catalogue, stocks et traçabilité centralisés pour toute votre exploitation.',
    },
    {
        icon: 'fa-users-gear',
        title: 'Rôles & accès',
        desc: 'Admin, technicien, agriculteur — chacun avec son espace et ses permissions.',
    },
    {
        icon: 'fa-chart-line',
        title: 'Tableaux de bord',
        desc: 'Statistiques et indicateurs clés pour piloter vos décisions au quotidien.',
    },
];

export default function Landing() {
    return (
        <div className="min-h-screen relative overflow-hidden">
            <Head title="Accueil" />

            {/* Atmospheric background — matches login page theme */}
            <div className="fixed inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-teal-950 -z-20" />
            <div
                className="fixed orb-base bg-emerald-500/25 -z-10 animate-float-orb"
                style={{ width: 520, height: 520, top: '-8rem', left: '-8rem' }}
            />
            <div
                className="fixed orb-base bg-teal-500/20 -z-10 animate-float-orb"
                style={{ width: 600, height: 600, bottom: '-10rem', right: '-8rem', animationDelay: '-3.5s', animationDuration: '11s' }}
            />
            <div
                className="fixed orb-base bg-emerald-400/12 -z-10 animate-float-orb"
                style={{ width: 340, height: 340, top: '55%', left: '55%', animationDelay: '-6s', animationDuration: '13s' }}
            />
            <div className="fixed inset-0 -z-10 opacity-[0.025]" style={{
                backgroundImage: 'linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px)',
                backgroundSize: '48px 48px',
            }} />

            {/* ── Nav ─────────────────────────────────────────── */}
            <motion.header
                initial={{ opacity: 0, y: -16 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5 }}
                className="relative z-10 max-w-6xl mx-auto px-6 py-6 flex items-center justify-between"
            >
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <i className="fas fa-leaf text-white text-lg" aria-hidden="true" />
                    </div>
                    <span className="text-white font-bold text-lg tracking-tight">Agrifober</span>
                </div>
                <Link
                    href="/login"
                    className="btn-agri text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20"
                >
                    Se connecter
                </Link>
            </motion.header>

            {/* ── Hero ────────────────────────────────────────── */}
            <section className="relative z-10 max-w-4xl mx-auto px-6 pt-16 pb-24 text-center">
                <motion.div
                    initial="hidden"
                    animate="show"
                    variants={stagger}
                >
                    <motion.span
                        variants={fadeUp}
                        transition={{ duration: 0.5 }}
                        className="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1.5 rounded-full mb-6"
                    >
                        <i className="fas fa-circle-check" aria-hidden="true" />
                        Plateforme de gestion agricole
                    </motion.span>

                    <motion.h1
                        variants={fadeUp}
                        transition={{ duration: 0.55 }}
                        className="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight"
                    >
                        Pilotez vos cultures,<br />
                        <span className="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">
                            simplement.
                        </span>
                    </motion.h1>

                    <motion.p
                        variants={fadeUp}
                        transition={{ duration: 0.55 }}
                        className="mt-6 text-lg text-slate-300/90 max-w-2xl mx-auto"
                    >
                        Agrifober centralise le suivi des parcelles, des produits et des équipes
                        pour les administrateurs, techniciens et agriculteurs.
                    </motion.p>

                    <motion.div
                        variants={fadeUp}
                        transition={{ duration: 0.55 }}
                        className="mt-9 flex items-center justify-center gap-4"
                    >
                        <Link
                            href="/login"
                            className="btn-agri text-white font-bold px-7 py-3.5 rounded-xl text-sm shadow-xl shadow-emerald-500/25 flex items-center gap-2"
                        >
                            Accéder à mon espace
                            <i className="fas fa-arrow-right" aria-hidden="true" />
                        </Link>
                    </motion.div>
                </motion.div>
            </section>

            {/* ── Features ────────────────────────────────────── */}
            <section className="relative z-10 max-w-6xl mx-auto px-6 pb-24">
                <motion.div
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, amount: 0.3 }}
                    variants={stagger}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5"
                >
                    {features.map((f) => (
                        <motion.div
                            key={f.title}
                            variants={fadeUp}
                            transition={{ duration: 0.5 }}
                            whileHover={{ y: -6 }}
                            className="bg-white/[0.06] backdrop-blur-xl border border-white/10 rounded-2xl p-6 hover:bg-white/[0.09] transition-colors duration-300"
                        >
                            <div className="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/20">
                                <i className={`fas ${f.icon} text-white text-base`} aria-hidden="true" />
                            </div>
                            <h3 className="text-white font-bold mb-1.5">{f.title}</h3>
                            <p className="text-sm text-slate-300/80 leading-relaxed">{f.desc}</p>
                        </motion.div>
                    ))}
                </motion.div>
            </section>

            {/* ── Footer ──────────────────────────────────────── */}
            <footer className="relative z-10 border-t border-white/10 py-6 text-center">
                <p className="text-xs text-slate-400 font-medium">
                    Agrifober — v1.1
                </p>
            </footer>
        </div>
    );
}
