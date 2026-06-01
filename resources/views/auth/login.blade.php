<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Agrifober Admin</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

    {{-- ── Atmospheric dark background ──────────────────────────────── --}}
    <div class="fixed inset-0 -z-20" style="background: linear-gradient(135deg, #020617 0%, #022c22 50%, #042f2e 100%)"></div>

    {{-- Floating orbs --}}
    <div class="fixed orb-base bg-emerald-500/25 -z-10 animate-float-orb"
         style="width:520px;height:520px;top:-8rem;left:-8rem"></div>
    <div class="fixed orb-base bg-teal-500/20 -z-10 animate-float-orb"
         style="width:600px;height:600px;bottom:-10rem;right:-8rem;animation-delay:-3.5s;animation-duration:11s"></div>
    <div class="fixed orb-base bg-emerald-400/10 -z-10 animate-float-orb"
         style="width:340px;height:340px;top:55%;left:55%;animation-delay:-6s;animation-duration:13s"></div>

    {{-- Subtle grid overlay --}}
    <div class="fixed inset-0 -z-10 opacity-[0.025]"
         style="background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:48px 48px"></div>

    {{-- ── Card ──────────────────────────────────────────────────────── --}}
    <div class="relative w-full max-w-md animate-slide-up">

        {{-- Top glow line --}}
        <div class="absolute -top-px left-16 right-16 h-px"
             style="background: linear-gradient(90deg, transparent, rgba(52,211,153,0.7), transparent)"></div>

        <div class="bg-white/[0.97] backdrop-blur-xl rounded-3xl p-8 border border-white/60"
             style="box-shadow: 0 32px 80px rgba(2,44,34,0.5), 0 0 0 1px rgba(52,211,153,0.08)">

            {{-- Brand --}}
            <div class="text-center mb-8">
                <div class="relative inline-flex items-center justify-center mb-5">
                    <div class="absolute inset-0 rounded-full bg-emerald-400/20 animate-ping"
                         style="animation-duration:2.5s"></div>
                    <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center animate-glow-pulse"
                         style="background:linear-gradient(135deg,#059669,#0d9488);box-shadow:0 8px 24px rgba(5,150,105,0.35)">
                        <i class="fas fa-leaf text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Agrifober
                    <span class="ml-2 text-[11px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full align-middle">
                        Admin
                    </span>
                </h1>
                <p class="text-sm text-slate-500 mt-1.5 font-medium">
                    Connectez-vous à votre espace d'administration
                </p>
            </div>

            {{-- Error banner --}}
            @if ($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-5 animate-slide-up">
                    <i class="fas fa-triangle-exclamation mt-0.5 shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5" novalidate>
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                        Adresse Email
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <input
                            id="email" type="email" name="email" value="{{ old('email') }}"
                            required autofocus autocomplete="email"
                            placeholder="admin@agrifober.com"
                            class="input-agri w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 bg-slate-50/70 @error('email') border-red-400 bg-red-50/50 @enderror"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input
                            id="password" type="password" name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-agri w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 bg-slate-50/70 @error('password') border-red-400 bg-red-50/50 @enderror"
                        >
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="btn-agri w-full text-white font-bold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2.5 mt-2"
                >
                    <i class="fas fa-arrow-right-to-bracket text-sm"></i>
                    Se connecter
                </button>
            </form>

            {{-- Footer --}}
            <div class="mt-7 pt-5 border-t border-slate-100 flex items-center justify-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                <p class="text-xs text-slate-400 font-medium">
                    Accès réservé aux administrateurs Agrifober — v1.1
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
