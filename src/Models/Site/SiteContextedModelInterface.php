<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Site;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @template TModel of Model
 */
interface SiteContextedModelInterface
{
    /**
     * @return BelongsTo<Site, TModel>
     */
    public function site(): BelongsTo;

    /**
     * @param Builder<TModel> $query
     * @return Builder<TModel>
     */
    public function scopeForSite(Builder $query, null|string|int $siteId = null): Builder;
}
