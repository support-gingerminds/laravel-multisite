<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Trait;

use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Services\Context\LanguageContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Throwable;

trait TranslatableModelTrait
{
    /**
     * Boot trait.
     */
    protected static function bootTranslatableModelTrait(): void
    {
        static::addGlobalScope('translations', function (Builder $builder) {
            $builder->with('translations');
        });
    }

    /**
     * All translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(
            $this->getTranslationModel(),
            $this->getTranslationForeignKey()
        );
    }

    /**
     * Current translation with fallback.
     */
    public function currentTranslation(): HasOne
    {
        $relation = $this->hasOne(
            $this->getTranslationModel(),
            $this->getTranslationForeignKey()
        );

        if (!app()->bound(LanguageContext::class)) {
            $ids = $this->resolveLanguageIdsFromHeader();
            if ($ids !== []) {
                return $relation
                    ->whereIn('language_id', $ids)
                    ->orderByRaw($this->buildCaseOrderSql($ids));
            }

            return $relation;
        }

        $context = app(LanguageContext::class);

        $currentId  = $context->current()?->id;
        $fallbackId = $context->fallback()?->id;

        $ids = array_values(array_filter([
            $currentId,
            $fallbackId,
        ]));

        if ($ids === []) {
            // Try to resolve from Accept-Language header as a safe fallback
            $ids = $this->resolveLanguageIdsFromHeader();
            if ($ids !== []) {
                return $relation
                    ->whereIn('language_id', $ids)
                    ->orderByRaw($this->buildCaseOrderSql($ids));
            }

            return $relation;
        }

        return $relation
            ->whereIn('language_id', $ids)
            ->orderByRaw($this->buildCaseOrderSql($ids));
    }

    /**
     * Try to resolve preferred language ids from the current HTTP request
     * Accept-Language header. Returns ordered language ids (best first).
     *
     * @return array<int>
     */
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

        // Map requested locales to existing language ids, preserving order
        $languages = Language::query()
            ->whereIn('iso', $requestedLocales->all())
            ->get(['id', 'iso'])
            ->keyBy(fn ($l) => strtolower($l->iso));

        $ids = [];
        foreach ($requestedLocales as $iso) {
            $lang = $languages->get($iso);
            if ($lang) {
                $ids[] = (int)$lang->id;
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * Build a CASE SQL to preserve given order of language ids.
     */
    protected function buildCaseOrderSql(array $ids): string
    {
        $cases = [];
        foreach ($ids as $index => $id) {
            $cases[] = 'WHEN language_id = ' . (int)$id . ' THEN ' . (int)$index;
        }
        $else = count($ids);
        return 'CASE ' . implode(' ', $cases) . ' ELSE ' . $else . ' END';
    }

    /**
     * Get translation for language.
     */
    public function translation(
        null|int|Language $language = null,
        bool $fallback = true,
    ): ?Model {

        if ($language === null) {
            return $this->currentTranslation;
        }

        $languageId = match (true) {
            $language instanceof Language => $language->id,
            is_int($language)             => $language,
        };

        $translation = $this->translations()
            ->where('language_id', $languageId)
            ->first();

        if ($translation) {
            return $translation;
        }

        if (!$fallback) {
            return null;
        }

        if (!app()->bound(LanguageContext::class)) {
            return null;
        }

        $fallbackId = app(LanguageContext::class)
            ->fallback()?->id;

        if (!$fallbackId) {
            return null;
        }

        return $this->translations()
            ->where('language_id', $fallbackId)
            ->first();
    }

    /**
     * Shortcut attribute.
     */
    public function getTranslatedAttribute(): ?Model
    {
        return $this->translation();
    }

    /**
     * Translation foreign key.
     */
    protected function getTranslationForeignKey(): string
    {
        return $this->getForeignKey();
    }

    /**
     * Translation model class.
     */
    protected function getTranslationModel(): string
    {
        if (property_exists($this, 'translationModel')) {
            return $this->translationModel;
        }

        return static::class . 'Translation';
    }
}
