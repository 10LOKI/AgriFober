<?php

namespace App\Providers;

use App\Repositories\Contracts\CultureRepositoryInterface;
use App\Repositories\Eloquent\CultureRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Map repository interfaces to their Eloquent implementations.
     *
     * @var array<class-string, class-string>
     */
    private array $repositories = [
        CultureRepositoryInterface::class => CultureRepository::class,
    ];

    /**
     * Register repository bindings in the service container.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }
}
