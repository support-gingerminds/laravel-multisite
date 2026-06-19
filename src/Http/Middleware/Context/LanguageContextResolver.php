<?php

namespace Gingerminds\LaravelMultisite\Http\Middleware\Context;

use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Illuminate\Http\Request;

class LanguageContextResolver
{
    /**
     * @return array{0: Language|null, 1: Language|null}
     */
    public function resolve(Site $site, Request $request): array
    {
        $default = $site->languages()
            ->wherePivot('is_default', true)
            ->first()
            ?? $site->languages()->first();

        $header = $request->header('Accept-Language');

        if (!$header) {
            return [$default, $default];
        }

        $requestedLocales = collect(explode(',', $header))
            ->map(fn ($locale) => strtolower(trim(explode(';', $locale)[0])))
            ->map(fn ($locale) => explode('-', $locale)[0])
            ->filter();

        foreach ($requestedLocales as $locale) {
            $language = $site->languages()
                ->where('iso', $locale)
                ->first();

            if ($language) {
                return [$language, $default];
            }
        }

        return [$default, $default];
    }
}
