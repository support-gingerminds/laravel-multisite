<?php

namespace Gingerminds\LaravelMultisite\Models\Trait;

use Gingerminds\LaravelMultisite\Models\Language\Language;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TranslationModelTrait
{
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function isFor(Language|int $language): bool
    {
        $languageId = $language instanceof Language ? $language->id : $language;
        return $this->language_id === $languageId;
    }
}
