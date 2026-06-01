import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [errors, setErrors] = useState({});
    const [processing, setProcessing] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});
        router.post('/login', { email, password }, {
            onError: (errs) => { setErrors(errs); setProcessing(false); },
            onFinish:      () => setProcessing(false),
        });
    };

    const nonFieldErrors = Object.entries(errors).filter(([k]) => !['email', 'password'].includes(k));

    return (
        <div className="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
            <Head title="Connexion — Agrifober Admin" />

            {/* ── Atmospheric background ─────────────────────────── */}
            <div className="fixed inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-teal-950 -z-20" />

            {/* Floating orbs */}
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

            {/* Subtle radial vignette */}
            <div className="fixed inset-0 -z-10 bg-[radial-gradient(ellipse_80%_60%_at_50%_40%,rgba(5,150,105,0.06)_0%,transparent_70%)]" />

            {/* Decorative grid lines */}
            <div
                className="fixed inset-0 -z-10 opacity-[0.025]"
                style={{
                    backgroundImage: 'linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px)',
                    backgroundSize: '48px 48px',
                }}
            />

            {/* ── Card ───────────────────────────────────────────── */}
            <div className="relative w-full max-w-md animate-slide-up">

                {/* Top glow line */}
                <div className="absolute -top-px left-16 right-16 h-px bg-gradient-to-r from-transparent via-emerald-400/70 to-transparent" />

                <div className="bg-white/[0.97] backdrop-blur-xl rounded-3xl shadow-2xl shadow-emerald-950/40 p-8 border border-white/60">

                    {/* Brand header */}
                    <div className="text-center mb-8">
                        <div className="relative inline-flex items-center justify-center mb-5">
                            {/* Pulse ring */}
                            <div className="absolute inset-0 rounded-full bg-emerald-400/20 animate-ping" style={{ animationDuration: '2.5s' }} />
                            <div className="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/35 animate-glow-pulse">
                                <i className="fas fa-leaf text-white text-2xl" aria-hidden="true" />
                            </div>
                        </div>
                        <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                            Agrifober
                            <span className="ml-2 text-[11px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full align-middle">
                                Admin
                            </span>
                        </h1>
                        <p className="text-sm text-slate-500 mt-1.5 font-medium">
                            Connectez-vous à votre espace d'administration
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleSubmit} className="space-y-5" noValidate>

                        {/* Email field */}
                        <div className="space-y-1.5">
                            <label htmlFor="email" className="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                Adresse Email
                            </label>
                            <div className="relative">
                                <div className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <i className="fas fa-envelope text-sm" aria-hidden="true" />
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    className={`
                                        input-agri w-full pl-10 pr-4 py-3 rounded-xl border text-sm text-slate-800
                                        placeholder:text-slate-400 bg-slate-50/70
                                        ${errors.email ? 'border-red-400 bg-red-50/50' : 'border-slate-200'}
                                    `}
                                    placeholder="admin@agrifober.com"
                                    autoComplete="email"
                                    autoFocus
                                    required
                                    aria-describedby={errors.email ? 'email-error' : undefined}
                                />
                            </div>
                            {errors.email && (
                                <p id="email-error" className="text-xs text-red-500 font-medium flex items-center gap-1.5 mt-1">
                                    <i className="fas fa-circle-exclamation text-[10px]" aria-hidden="true" />
                                    {errors.email}
                                </p>
                            )}
                        </div>

                        {/* Password field */}
                        <div className="space-y-1.5">
                            <label htmlFor="password" className="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                Mot de passe
                            </label>
                            <div className="relative">
                                <div className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <i className="fas fa-lock text-sm" aria-hidden="true" />
                                </div>
                                <input
                                    id="password"
                                    type={showPassword ? 'text' : 'password'}
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    className={`
                                        input-agri w-full pl-10 pr-11 py-3 rounded-xl border text-sm text-slate-800
                                        placeholder:text-slate-400 bg-slate-50/70
                                        ${errors.password ? 'border-red-400 bg-red-50/50' : 'border-slate-200'}
                                    `}
                                    placeholder="••••••••"
                                    autoComplete="current-password"
                                    required
                                    aria-describedby={errors.password ? 'password-error' : undefined}
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword((s) => !s)}
                                    aria-label={showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'}
                                    className="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors duration-150"
                                >
                                    <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} text-sm`} aria-hidden="true" />
                                </button>
                            </div>
                            {errors.password && (
                                <p id="password-error" className="text-xs text-red-500 font-medium flex items-center gap-1.5 mt-1">
                                    <i className="fas fa-circle-exclamation text-[10px]" aria-hidden="true" />
                                    {errors.password}
                                </p>
                            )}
                        </div>

                        {/* Non-field error banner */}
                        {nonFieldErrors.length > 0 && (
                            <div
                                role="alert"
                                className="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm animate-slide-up"
                            >
                                <i className="fas fa-triangle-exclamation mt-0.5 shrink-0" aria-hidden="true" />
                                <span>{nonFieldErrors.map(([, m]) => (typeof m === 'string' ? m : m[0])).join(', ')}</span>
                            </div>
                        )}

                        {/* Submit button */}
                        <button
                            type="submit"
                            disabled={processing}
                            aria-busy={processing}
                            className="btn-agri w-full text-white font-bold py-3.5 px-4 rounded-xl text-sm disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2.5 mt-2"
                        >
                            {processing ? (
                                <>
                                    <i className="fas fa-spinner fa-spin text-sm" aria-hidden="true" />
                                    <span>Connexion en cours…</span>
                                </>
                            ) : (
                                <>
                                    <i className="fas fa-arrow-right-to-bracket text-sm" aria-hidden="true" />
                                    <span>Se connecter</span>
                                </>
                            )}
                        </button>
                    </form>

                    {/* Footer */}
                    <div className="mt-7 pt-5 border-t border-slate-100 flex items-center justify-center gap-2">
                        <div className="w-1.5 h-1.5 rounded-full bg-emerald-400" aria-hidden="true" />
                        <p className="text-xs text-slate-400 font-medium">
                            Accès réservé aux administrateurs Agrifober — v1.1
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
