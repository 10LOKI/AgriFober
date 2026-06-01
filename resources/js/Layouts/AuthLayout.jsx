import React from 'react';

export default function AuthLayout({ children }) {
    return (
        <div className="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

            {/* Shared atmospheric background for all auth pages */}
            <div className="fixed inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-teal-950 -z-20" />
            <div
                className="fixed orb-base bg-emerald-500/20 -z-10 animate-float-orb"
                style={{ width: 480, height: 480, top: '-6rem', left: '-6rem' }}
            />
            <div
                className="fixed orb-base bg-teal-500/15 -z-10 animate-float-orb"
                style={{ width: 560, height: 560, bottom: '-8rem', right: '-6rem', animationDelay: '-4s', animationDuration: '11s' }}
            />
            <div
                className="fixed inset-0 -z-10 opacity-[0.025]"
                style={{
                    backgroundImage: 'linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px)',
                    backgroundSize: '48px 48px',
                }}
            />

            {children}
        </div>
    );
}
