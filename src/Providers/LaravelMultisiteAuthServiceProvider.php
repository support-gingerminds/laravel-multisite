<?php

namespace Gingerminds\LaravelMultisite\Providers;

use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Policies\Site\SitePolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class LaravelMultisiteAuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Site::class => SitePolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)
            ->registerPermissions(app(Gate::class));

        $this->registerPolicies();
    }
}
