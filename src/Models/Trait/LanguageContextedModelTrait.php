<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Trait;

use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Throwable;

trait LanguageContextedModelTrait
{
    protected static function bootLanguageContextedModelTrait(): void
    {
        static::addGlobalScope('language', function (Builder $builder) {
            static::applyLanguageScope($builder);
        });
    }

    /**
     * Restricts the query to models linked to the resolved language, if any.
     */
    private static function applyLanguageScope(Builder $builder): void
    {
        $languageId = static::resolveContextLanguageId();

        if (!$languageId) {
            return;
        }

        $builder->whereHas('languages', fn (Builder $q) => $q->where('languages.id', $languageId));
    }

    /**
     * Resolves the current language id from the bound context, falling back
     * to the client's `Accept-Language` header (in preference order).
     */
    private static function resolveContextLanguageId(): ?int
    {
        if (app()->bound(LanguageContext::class)) {
            $languageId = app(LanguageContext::class)->current()?->id;

            if ($languageId) {
                return (int) $languageId;
            }
        }

        try {
            return static::resolveLanguageIdFromAcceptHeader();
        } catch (Throwable) {
            return null;
        }
    }

    private static function resolveLanguageIdFromAcceptHeader(): ?int
    {
        $header = request()->header('Accept-Language');

        if (!$header) {
            return null;
        }

        $requestedLocales = static::parseAcceptLanguageHeader($header);

        if ($requestedLocales->isEmpty()) {
            return null;
        }

        $languages = Language::query()
            ->whereIn('iso', $requestedLocales->all())
            ->get(['id', 'iso'])
            ->keyBy(fn ($l) => strtolower($l->iso));

        return static::firstMatchingLanguageId($requestedLocales, $languages);
    }

    /**
     * @return Collection<int, string>
     */
    private static function parseAcceptLanguageHeader(string $header): Collection
    {
        return collect(explode(',', $header))
            ->map(fn ($locale) => trim(explode(';', $locale)[0]))
            ->map(fn ($locale) => strtolower(explode('-', $locale)[0]))
            ->filter()
            ->values();
    }

    /**
     * @param Collection<int, string> $requestedLocales
     * @param Collection<string, Language> $languages
     */
    private static function firstMatchingLanguageId(Collection $requestedLocales, Collection $languages): ?int
    {
        foreach ($requestedLocales as $iso) {
            $lang = $languages->get($iso);

            if ($lang) {
                return (int) $lang->id;
            }
        }

        return null;
    }

    /**
     * Public resolver (safe reuse outside scope)
     */
    protected function resolveCurrentLanguageId(): ?int
    {
        $contextId = $this->resolveCurrentLanguageIdFromContext();

        if ($contextId !== null) {
            return $contextId;
        }

        try {
            return $this->resolveCurrentLanguageIdFromHeader();
        } catch (Throwable) {
            return null;
        }
    }

    private function resolveCurrentLanguageIdFromContext(): ?int
    {
        if (!app()->bound(LanguageContext::class)) {
            return null;
        }

        $id = app(LanguageContext::class)->current()?->id;

        return $id !== null ? (int) $id : null;
    }

    private function resolveCurrentLanguageIdFromHeader(): ?int
    {
        $header = request()->header('Accept-Language');

        if (!$header) {
            return null;
        }

        $requestedLocales = collect(explode(',', $header))
            ->map(fn ($locale) => trim(explode(';', $locale)[0]))
            ->map(fn ($locale) => strtolower(explode('-', $locale)[0]))
            ->filter();

        if ($requestedLocales->isEmpty()) {
            return null;
        }

        return Language::query()
            ->whereIn('iso', $requestedLocales->all())
            ->value('id');
    }
}
