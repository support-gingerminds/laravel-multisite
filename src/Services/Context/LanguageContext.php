<?php

namespace Gingerminds\LaravelMultisite\Services\Context;

use Gingerminds\LaravelMultisite\Http\Middleware\Context\LanguageContextResolver;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Models\Site\Site;

class LanguageContext
{
    private ?Language $current  = null;
    private ?Language $fallback = null;
    private bool $initialized   = false;

    public function __construct(
        private readonly SiteContext $siteContext,
        private readonly LanguageContextResolver $resolver,
    ) {
    }

    private function init(): void
    {
        if ($this->initialized) {
            return;
        }

        $site = $this->siteContext->site();

        if (!$site instanceof Site) {
            $this->initialized = true;
            return;
        }

        [$current, $fallback] = $this->resolver->resolve($site, request());

        $this->current  = $current;
        $this->fallback = $fallback;

        $this->initialized = true;
    }

    public function current(): ?Language
    {
        $this->init();
        return $this->current;
    }

    public function fallback(): ?Language
    {
        $this->init();
        return $this->fallback;
    }

    public function has(): bool
    {
        $this->init();

        return $this->current instanceof Language
            || $this->fallback instanceof Language;
    }
}
