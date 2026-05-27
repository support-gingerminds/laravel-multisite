<?php

namespace Gingerminds\LaravelCore\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        // Chargement des vues
        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'gingerminds-multisite'
        );

        // Chargement des traductions
        $this->loadTranslationsFrom(
            __DIR__ . '/../../resources/lang',
            'gingerminds-multisite'
        );
    }
}
