# Configuration

The package has a single config file, `config/gingerminds-multisite.php`, holding one `resources` array — the class bindings for its two resources, `site` and `language`.

```php
'resources' => [
    'language' => [
        'model'      => Language::class,
        'controller' => LanguageController::class,
        'repository' => LanguageRepository::class,
        'request'    => LanguageRequest::class,
        'provider'   => LanguageProvider::class,
    ],

    'site' => [
        'model'           => Site::class,
        'controller'      => SiteController::class,
        'repository'      => SiteRepository::class,
        'request'         => SiteRequest::class,
        'provider'        => SiteProvider::class,
        'state_processor' => SiteStateProcessor::class,
    ],
],
```

These entries are read by `Gingerminds\LaravelMultisite\Resolver\ResourceResolver` (`model()`, `controller()`, `repository()`, `request()`, `provider()`, `stateProcessor()`), the same pattern used throughout `gingerminds-laravel-core`. `Language` has no `state_processor` entry, since it has no mutating API endpoints (see [API](./API.md)).

Publish it with `php artisan vendor:publish --tag=gingerminds-multisite-config` (see [Installation](./Installation.md#4-optional-publish-the-config)), then override just the keys you need — the package's defaults are merged in for the rest.
