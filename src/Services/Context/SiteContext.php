<?php

namespace Gingerminds\LaravelMultisite\Services\Context;

use Gingerminds\LaravelMultisite\Models\Site\Site;

class SiteContext
{
    protected ?Site $site = null;

    public function set(Site $site): void
    {
        $this->site = $site;
    }

    public function site(): ?Site
    {
        return $this->site;
    }

    public function id(): ?int
    {
        return $this->site?->id;
    }

    public function has(): bool
    {
        return $this->site instanceof Site;
    }
}
