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

            $languageId = null;

            if (app()->bound(LanguageContext::class)) {
                $languageId = app(LanguageContext::class)->current()?->id;
            }

            if (!$languageId) {
                try {
                    $header = request()->header('Accept-Language');

                    if ($header) {
                        $requestedLocales = collect(explode(',', $header))
                            ->map(fn ($locale) => trim(explode(';', $locale)[0]))
                            ->map(fn ($locale) => strtolower(explode('-', $locale)[0]))
                            ->filter()
                            ->values();

                        if ($requestedLocales->isNotEmpty()) {
                            $languages = Language::query()
                                ->whereIn('iso', $requestedLocales->all())
                                ->get(['id', 'iso'])
                                ->keyBy(fn ($l) => strtolower($l->iso));

                            foreach ($requestedLocales as $iso) {
                                $lang = $languages->get($iso);

                                if ($lang) {
                                    $languageId = (int) $lang->id;
                                    break;
                                }
                            }
                        }
                    }
                } catch (Throwable) {
                }
            }

            if (!$languageId) {
                return;
            }

            $builder->where('language_id', (int) $languageId);
        });
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Public resolver (safe reuse outside scope)
     */
    protected function resolveCurrentLanguageId(): ?int
    {
        if (app()->bound(LanguageContext::class)) {
            $id = app(LanguageContext::class)->current()?->id;
            if ($id !== null) {
                return (int) $id;
            }
        }

        try {
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
        } catch (Throwable) {
            return null;
        }
    }
}
