# Installation

## Requirements

- PHP ^8.4
- [`gingerminds/laravel-core`](https://github.com/gingerminds/laravel-core) ^2.8 (admin framework, API Platform integration, `ResourceResolver` pattern this package builds on)

## 1. Require the package

```bash
composer require gingerminds/laravel-multisite
```

The service provider (`LaravelMultisiteServiceProvider`) is auto-discovered — nothing to add to `config/app.php` or `bootstrap/providers.php`. It also registers an internal `LaravelMultisiteAuthServiceProvider` for you.

## 2. Register the package's models with API Platform

`Site` and `Language` are exposed as API resources via `#[ApiResource]` attributes on the model classes. Add the package's `Models` directory to `config/api-platform.php`:

```php
'resources' => [
    // ...your existing entries
    base_path('vendor/gingerminds/laravel-multisite/src/Models'),
],
```

## 3. Run the migrations

```bash
php artisan migrate
```

This creates `sites`, `languages`, and the `site_language` pivot table (with an `is_default` flag marking each site's default language).

## 4. (Optional) Publish the config

```bash
php artisan vendor:publish --tag=gingerminds-multisite-config
```

This creates `config/gingerminds-multisite.php` in your application, same mechanism as `laravel-core` and `laravel-media-manager`. See [Configuration](./Configuration.md) for what it controls. As with those packages, the package's own defaults are merged in for any key you don't override, so you never have to copy the whole file.

## What you get out of the box

- Admin CRUD screens for sites and languages at `{admin_prefix}/sites` and `{admin_prefix}/languages`.
- A generic tabbed translations Blade component for your own forms — see [Components](./Components.md).
- `SiteContext` / `LanguageContext` services for resolving "the current site" / "the current language" from the request — see [Context](./Context.md).
- Four traits for adding site-scoping, language-scoping, and translation support to your own models — see [Traits](./Traits.md).
- API Platform endpoints for `Site` (read-only collection) and `Language` (nested only) — see [API](./API.md).

No JS/SCSS assets, no seeders, no basket-style optional feature — this package is intentionally lighter than `laravel-media-manager`.
