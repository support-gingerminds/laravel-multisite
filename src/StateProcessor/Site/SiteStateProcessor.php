<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\StateProcessor\Site;

use ApiPlatform\State\ProcessorInterface;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Gingerminds\LaravelMultisite\Http\Requests\Site\SiteRequest;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Repositories\Site\SiteRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * @implements ProcessorInterface<Site, Site>
 */
class SiteStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        SiteRepository $repository,
        ValidationFactory $validationFactory
    ) {
        $this->repository    = $repository;
        $this->formRequest   = new SiteRequest();
        $this->resourceModel = new Site();

        parent::__construct($validationFactory);
    }
}
