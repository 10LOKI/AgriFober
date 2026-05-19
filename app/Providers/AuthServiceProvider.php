<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Parcel;
use App\Models\Culture;
use App\Models\Product;
use App\Enums\RoleEnum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Policies
        Gate::policy(Parcel::class, \App\Policies\ParcelPolicy::class);
        Gate::policy(Culture::class, \App\Policies\CulturePolicy::class);
        Gate::policy(Product::class, \App\Policies\ProductPolicy::class);
        
        // Helper Gates pour rôles
        Gate::define('is-admin', fn ($user) => $user->isAdmin());
        Gate::define('is-agriculteur', fn ($user) => $user->isAgriculteur());
        Gate::define('is-technicien', fn ($user) => $user->isTechnicien());
    }
}
