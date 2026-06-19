<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Providers;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelMultisite\ApiProvider\Language\LanguageProvider;
use Gingerminds\LaravelMultisite\ApiProvider\Site\SiteProvider;
use Gingerminds\LaravelMultisite\Http\Controllers\Language\LanguageController;
use Gingerminds\LaravelMultisite\Http\Controllers\Site\SiteController;
use Gingerminds\LaravelMultisite\Http\Middleware\Context\LanguageContextResolver;
use Gingerminds\LaravelMultisite\Http\Middleware\Context\SiteContextResolver;
use Gingerminds\LaravelMultisite\Http\Requests\Language\LanguageRequest;
use Gingerminds\LaravelMultisite\Http\Requests\Site\SiteRequest;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Repositories\Language\LanguageRepository;
use Gingerminds\LaravelMultisite\Repositories\Site\SiteRepository;
use Gingerminds\LaravelMultisite\Resolver\ResourceResolver;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Gingerminds\LaravelMultisite\Services\Context\SiteContext;
use Gingerminds\LaravelMultisite\StateProcessor\Site\SiteStateProcessor;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LaravelMultisiteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(LaravelMultisiteAuthServiceProvider::class);

        $this->app->bind(
            LanguageController::class,
            ResourceResolver::controller('language')
        );
        $this->app->bind(
            LanguageRepository::class,
            ResourceResolver::repository('language')
        );
        $this->app->bind(
            Language::class,
            ResourceResolver::model('language')
        );
        $this->app->bind(
            LanguageRequest::class,
            ResourceResolver::request('language')
        );
        $this->app->bind(
            LanguageProvider::class,
            ResourceResolver::provider('language')
        );

        $this->app->bind(
            SiteController::class,
            ResourceResolver::controller('site')
        );
        $this->app->bind(
            SiteRepository::class,
            ResourceResolver::repository('site')
        );
        $this->app->bind(
            Site::class,
            ResourceResolver::model('site')
        );
        $this->app->bind(
            SiteRequest::class,
            ResourceResolver::request('site')
        );
        $this->app->bind(
            SiteProvider::class,
            ResourceResolver::provider('site')
        );
        $this->app->bind(
            SiteStateProcessor::class,
            ResourceResolver::stateProcessor('site')
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/gingerminds-multisite.php',
            'gingerminds-multisite'
        );

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
    }

    public function boot(): void
    {
        $this->app->scoped(SiteContext::class);
        $this->app->singleton(SiteContextResolver::class);

        $this->app->scoped(LanguageContext::class);
        $this->app->singleton(LanguageContextResolver::class);

        Route::model('site', ResourceResolver::model('site'));

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
