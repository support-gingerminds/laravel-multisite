<?php

namespace Gingerminds\LaravelMultisite\Models\Site;

use Gingerminds\LaravelMultisite\Services\Context\SiteContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait SiteContextedModelTrait
{
    /**
     * Boot trait.
     */
    protected static function bootSiteContextedModelTrait(): void
    {
        static::addGlobalScope('site', function (Builder $builder) {

            $siteId = app(SiteContext::class)->id();

            if (!$siteId) {
                return;
            }

            $table = $builder->getModel()->getTable();

            $builder->where(function (Builder $q) use ($table, $siteId) {

                $q->where($table . '.site_id', $siteId)
                    ->orWhereNull($table . '.site_id');
            });
        });

        static::creating(function ($model) {

            if (
                empty($model->site_id)
                && app(SiteContext::class)->has()
            ) {
                $model->site_id = app(SiteContext::class)->id();
            }
        });
    }

    /**
     * Site relation.
     *
     * @return BelongsTo<Site, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Manual scope.
     *
     * @param Builder<$this> $query
     * @return Builder<$this>
     */
    public function scopeForSite(
        Builder $query,
        null|string|int $siteId = null
    ): Builder {

        $siteId ??= app(SiteContext::class)->id();

        $table = $this->getTable();

        return $query->where(function (Builder $q) use ($table, $siteId) {

            $q->where($table . '.site_id', $siteId)
                ->orWhereNull($table . '.site_id');
        });
    }
}
