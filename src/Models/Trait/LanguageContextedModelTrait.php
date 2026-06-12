<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Trait;

use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

trait LanguageContextedModelTrait
{
    protected static function bootLanguageContextedModelTrait(): void
    {
        static::addGlobalScope('language', function (Builder $builder) {
            if (!app()->bound(LanguageContext::class)) {
                return;
            }

            $languageId = app(LanguageContext::class)->current()?->id;

            if ($languageId === null) {
                return;
            }

            $builder->where('language_id', (int) $languageId);
        });
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    protected function resolveCurrentLanguageId(): ?int
    {
        if (app()->bound(LanguageContext::class)) {
            $id = app(LanguageContext::class)->current()?->id;
            if ($id !== null) {
                return (int) $id;
            }
        }

        $ids = $this->resolveLanguageIdsFromHeader();
        return $ids[0] ?? null;
    }

    protected function resolveLanguageIdsFromHeader(): array
    {
        try {
            $header = request()->header('Accept-Language');
        } catch (Throwable) {
            $header = null;
        }

        if (!$header) {
            return [];
        }

        $requestedLocales = collect(explode(',', $header))
            ->map(fn ($locale) => trim(explode(';', $locale)[0]))
            ->map(fn ($locale) => strtolower(explode('-', $locale)[0]))
            ->filter()
            ->values();

        if ($requestedLocales->isEmpty()) {
            return [];
        }

        $languages = Language::query()
            ->whereIn('iso', $requestedLocales->all())
            ->get(['id', 'iso'])
            ->keyBy(fn ($l) => strtolower($l->iso));

        $ids = [];
        foreach ($requestedLocales as $iso) {
            $lang = $languages->get($iso);
            if ($lang) {
                $ids[] = (int) $lang->id;
            }
        }

        return array_values(array_unique($ids));
    }
}
