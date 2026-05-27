<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\ApiProvider\Site;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Repositories\Site\SiteRepository;

/**
 * @implements ProviderInterface<Site>
 */
class SiteProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(SiteRepository $repository)
    {
        parent::__construct($repository);
    }
}
