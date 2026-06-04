<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Providers;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelMultisite\Http\Middleware\Context\ResolveLanguageContext;
use Gingerminds\LaravelMultisite\Http\Middleware\Context\ResolveSiteContext;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Gingerminds\LaravelMultisite\Services\Context\SiteContext;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LaravelMultisiteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(LaravelMultisiteAuthServiceProvider::class);

        $providerPath = __DIR__ . '/../ApiProvider';
        $iterator     = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($providerPath)
        );
        $toTag = [];
        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $relativePath = $file->getPathname();
            $relativePath = substr($relativePath, strlen($providerPath) + 1, -4); // retire le préfixe et .php
            $class        = 'Gingerminds\\LaravelMultisite\\ApiProvider\\'
                . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
            if (class_exists($class) && is_subclass_of($class, ProviderInterface::class)) {
                $toTag[] = $class;
            }
        }
        if ($toTag !== []) {
            $this->app->tag($toTag, ProviderInterface::class);
        }

        $this->app->scoped(SiteContext::class);
        $this->app->scoped(LanguageContext::class);
    }

    public function boot(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $kernel->prependMiddleware(
            ResolveSiteContext::class
        );

        $kernel->prependMiddleware(
            ResolveLanguageContext::class,
        );

        // Chargement des routes du package
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        }

        // Chargement des migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

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
