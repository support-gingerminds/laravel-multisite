<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Http\Middleware\Context;

use Closure;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Gingerminds\LaravelMultisite\Services\Context\SiteContext;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveLanguageContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = app(SiteContext::class)->site();

        if (!$site) {
            return $next($request);
        }

        /** @var Language|null $defaultLanguage */
        $defaultLanguage = $site->languages()->wherePivot('is_default', true)->first()
            ?? $site->languages()->first();

        if ($this->isAdminRequest($request)) {
            return $next($request);
        }

        $language = $this->resolveLanguage(
            request: $request,
            site: $site,
            fallback: $defaultLanguage,
        );

        $context = app(LanguageContext::class);
        if ($language instanceof Language) {
            $context->setCurrent($language);
        }
        if ($defaultLanguage) {
            $context->setFallback($defaultLanguage);
        }

        if ($language instanceof Language) {
            app()->setLocale($language->iso);
        }

        return $next($request);
    }

    protected function isAdminRequest(Request $request): bool
    {
        return str_starts_with($request->path(), config('gingerminds-core.admin_prefix'));
    }

    protected function resolveLanguage(Request $request, Site $site, ?Language $fallback): ?Language
    {
        $header = $request->header('Accept-Language');

        if (!$header) {
            return $fallback;
        }

        $requestedLocales = collect(explode(',', $header))
            ->map(fn ($locale) => trim(explode(';', $locale)[0]))
            ->map(fn ($locale) => strtolower(explode('-', $locale)[0]))
            ->filter();

        foreach ($requestedLocales as $locale) {
            /** @var Language|null $language */
            $language = $site->languages()->where('iso', $locale)->first();
            if ($language) {
                return $language;
            }
        }

        abort(400, 'Unsupported language');
    }
}
