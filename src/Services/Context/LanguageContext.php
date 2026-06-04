<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Services\Context;

use Gingerminds\LaravelMultisite\Models\Language\Language;

class LanguageContext
{
    protected ?Language $current = null;

    protected ?Language $fallback = null;

    public function current(): ?Language
    {
        return $this->current;
    }

    public function fallback(): ?Language
    {
        return $this->fallback;
    }

    public function setCurrent(?Language $language): void
    {
        $this->current = $language;
    }

    public function setFallback(?Language $language): void
    {
        $this->fallback = $language;
    }

    public function has(): bool
    {
        return $this->current instanceof Language
            || $this->fallback instanceof Language;
    }
}
